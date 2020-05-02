<? require("../../inc/conn.php");
require('../../commonfile/log.php');
require("../../commonfile/wechat_notice_class.php");

 session_start();
if ($_SESSION["OK"]<>"OK") { 
echo "<script language=JavaScript>{window.location.href='../../error.php';}</script>";
exit; }

//ajax获取客户的待付款订单号

if($_GET['khmcddhs']<>''){
    $khmc = $_GET['khmc'];
    $sql = "select m.ddh,m.dje from order_mainqt m where (m.state = '待生产' or m.state = '待结算') and m.khmc = '$khmc' and m.ddh not in (select ddh from order_zh where zy = '订单结算' and khmc ='$khmc')";

    $res = mysql_query($sql,$conn);

    if(mysql_num_rows($res)<>0){
        for($i=0;$i<mysql_num_rows($res);$i++){

//            $ddhs .= $row['ddh'].':'.$row['dje'] .'元;'."\n";
            $ddhs[$i]['ddh'] =mysql_result($res,$i,'ddh');
            $ddhs[$i]['dje'] =mysql_result($res,$i,'dje');

        }
        echo json_encode($ddhs);

    }else{
//        $ddhs[0]['ddh'] ="无待付款订单";
//        $ddhs[0]['dje'] =0;
//        echo json_encode($ddhs);
    }
    exit();
}

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>数据处理</title>
<SCRIPT language=JavaScript src="../form.js"></SCRIPT>
<style type="text/css">
<!--
body {
	background-color: #A5CBF7;
}
.style11 {font-size: 14px}
.STYLE13 {font-size: 12px}
.STYLE15 {font-size: 12px; color: #FF0000; }
-->
</style>
</head>
<?


if ($_POST["khmc"]<>"") {

//	print_r($_POST);
//	exit;

	$khmc=urldecode($_POST["khmc"]);
	$jf=$_POST["jf"];
	$df=$_POST["df"];
	$zy=$_POST["zy"];
	$fssj=$_POST["fssj"];
	$xsbh=$_POST["skfs"];
	$type=$_POST["type"];
    $ddhs = $_POST['ddhs'];
	if($type <> "")
		$xsbh=$type."-".$xsbh;
	if (!isdate($fssj)) {
		echo "<script>alert('日期输入有误，请检查重新输入！');window.location.href='YK_cwmodifb.php?khmc=".$_POST["khmc"]."';</script>";
		exit;
	}
	$memo=$_POST["memo"];
	if ($jf<>0 && empty($ddhs)) {

        mysql_query("insert into order_zh values (0,'$xsbh','{$fssj}',$jf,0,'$zy','$memo',now(),'$khmc','0000')",$conn);
        echo "<script>alert('数据已插入，请核对！');</script>";

        //一次预存超过一定数额有会员等级,减去消费

        $yc = $jf - $df;
        if($yc>= 6000 && $yc< 10000){
            mysql_query("update base_kh set hyjb = '6' where khmc = '".$khmc."'",$conn);
            echo "<script>alert('会员等级修改为6');</script>";
        }elseif($yc>=10000 && $yc<20000){
            mysql_query("update base_kh set hyjb = '5' where khmc = '".$khmc."'",$conn);
            echo "<script>alert('会员等级修改为5');</script>";

        }elseif($yc>=20000 && $yc< 30000){
            mysql_query("update base_kh set hyjb = '4' where khmc = '".$khmc."'",$conn);
            echo "<script>alert('会员等级修改为4');</script>";

        }elseif($yc>=30000 && $yc< 50000){
            mysql_query("update base_kh set hyjb = '3' where khmc = '".$khmc."'",$conn);
            echo "<script>alert('会员等级修改为3');</script>";

        }elseif($yc>=50000 && $yc< 100000){
            mysql_query("update base_kh set hyjb = '2' where khmc = '".$khmc."'",$conn);
            echo "<script>alert('会员等级修改为2');</script>";

        }elseif($yc>=100000){
            mysql_query("update base_kh set hyjb = '1' where khmc = '".$khmc."'",$conn);
            echo "<script>alert('会员等级修改为1');</script>";
        }

//        notice wechat
       /* $first =  "您好，您的账户余额发生变动";
        $remark = '感谢您的使用';
        require '../../commonfile/get_kh_ye.php';
        wechat_notice::send_temp_finance_change($khmc,$first,$fssj,$yc,$yue,'充值/调账',$remark);*/
        $first =  "您的账户[$khmc]于".$fssj."成功充值";
        $remark = '感谢您的使用';
        require '../../commonfile/get_kh_ye.php';
        wechat_notice::send_temp_topup($khmc,$first,round($yc,2),round($yue,2),$remark);

        $log = new Log();
        $log -> INFO("|调账-借方:". $jf ."贷方:" . $df . '操作人:' . $_SESSION['YKUSERNAME'] . "\r\n");
    }
       /*	
	if ($zjf<>0) {
		mysql_query("insert into order_zh values (0,'','{$fssj}',$zjf,0,'$zy','$memo',null,'$khmc','1111')",$conn);
		echo "<script>alert('数据已插入，请核对！');</script>";
	}
	*/	
	if ($df<>0 && empty($ddhs)) {
		mysql_query("insert into order_zh values (0,'$xsbh','{$fssj}',0,$df,'$zy','$memo',now(),'$khmc','0000')",$conn);
		echo "<script>alert('数据已插入，请检查！');</script>";
	}
    //订单号合并付款
    if($df<>0 && !empty($ddhs)){

//        判断总金额与提交金额是否相等
        $zje = 0;
        foreach ($_POST['ddhs'] as $ddh) {
            $res = mysql_query("select dje,state from order_mainqt where ddh ='$ddh'");
            $je = mysql_result($res,0,'dje');
            $zje += $je;
        }
        $pje = $_POST['df'];
        $isequal = $pje-$zje;

        if(abs($isequal) < 1){
//            拼接订单号插入数据库
//            $ddhstr = '';
//            $i = 0;


            //            合并收款的订单计入合并收款表
            $rsuskbh = mysql_query("select skdh from union_sk ORDER BY id desc LIMIT 1",$conn);

            $arr1 = mysql_fetch_array($rsuskbh);
            $_last = $arr1[0];

            $_lastYM = substr($_last, 0, 4);
            $_nowYM = substr(date("Ym", time()), 2, 4);
            if ($_lastYM == $_nowYM)
                $uskbh = $_last + 1;
            else
                $uskbh = $_nowYM  . "00001";

            beginTransaction();

            foreach ($_POST['ddhs'] as $ddh) {

//                $ddhstr.= $ddh.',';
//                $i++;

                $res = mysql_query("select dje,state from order_mainqt where ddh ='$ddh'");
                $je = mysql_result($res,0,'dje');
                $zje += $je;

                $skfs=$_POST["skfs"];
                $skbz=$_POST["memo"];
                $_skinfo = mysql_query("select id from order_zh where zy='订单结算' and ddh='$ddh'",$conn);
                if(!mysql_fetch_array($_skinfo)){

                    if(mysql_result($res,0,'state')=='待结算'){
                        $unionsk[] = mysql_query("update order_mainqt set state='待配送',skbz='$skbz',sdate=now() where ddh='$ddh'");
                    }else{
                        $unionsk[] = mysql_query("update order_mainqt set skbz='$skbz',sdate=now() where ddh='$ddh'");

                    }
                    if ($_POST["skfs"]=="预存扣款")
                        $unionsk[] = mysql_query("insert into order_zh select 0,'$skfs',now(),0,$je,'订单结算',skbz,now(),khmc,ddh from order_mainqt where ddh='$ddh'");

                    else
                        $unionsk[] = mysql_query("insert into order_zh select 0,'$skfs',now(),$je,$je,'订单结算',skbz,now(),khmc,ddh from order_mainqt where ddh='$ddh'");
                }

//            $ddhstr = substr($ddhstr,0,-1);
//            合并收款的订单计入合并收款表

                $unionsk[] = mysql_query("insert into union_sk (id,skdh,ddh,memo,sksj) values (0,$uskbh,'$ddh','$skbz',now())",$conn);

            }


//            mysql_query("COMMIT");
            if(!transaction($unionsk)){

                echo "<script>alert('合并收款失败！请重新操作');</script>";
            }else{

                echo "<script>alert('合并收款成功，请检查！');</script>";
            }


        }else{
            echo "<script>alert('收款金额与订单金额不等，请核对！');</script>";
        }

    }

    echo "<script>window.opener.location.reload();window.close();</script>";
}

function isdate($str,$format="Y-m-d G:i:s"){
	$strArr = explode("-",$str);
	if(empty($strArr)){
		return false;
	}
	foreach($strArr as $val){
		if(strlen($val)<2){
			$val="0".$val;
		}
		$newArr[]=$val;
	}
	$str =implode("-",$newArr);
    $unixTime=strtotime($str);
    $checkDate= date($format,$unixTime);
    if($checkDate==$str)
        return true;
    else
        return false;
}

if ($_GET["khmc"]<>"") {
	$khmc=urldecode($_GET["khmc"]);
}
?>
<body>
<table width="100%" border="1" cellpadding="4" cellspacing="1" bordercolor="#000099" bgcolor="f6f6f6" >
  <tr>
<td height="222" valign="top">
<form action="" method="post"  name="form1" id="form1" >
     <table width="90%" height="240" border="0" align="center">
     
            <tr>
              <td width="84" height="27" class="STYLE13">客户名称</td>
            <td colspan="3">
              <select name="khmc" id="khmc" onChange="javascript:window.location.href='?userzh=<? echo $xsbh?>&khmc='+this.options[this.options.selectedIndex].value;">
              <option value="<? echo urlencode($khmc);?>"><? echo $khmc;?></option>
              
              </select>
            </td>
            </tr>
            <tr>
              <td width="84" height="27" class="STYLE13">增加</td>
            <td width="180" class="STYLE13">
              <input  type="text" size=10 name="jf" id="jf" value="0.00" >
              元              <br></td>
              <td width="57" class="STYLE13">减少</td>
              <td class="STYLE13"><span class="field">
                <input  type="text" size=10 name="df" id="df" value="0.00" >
              元</span></td>
            </tr>
	     <tr>
		<td width="84" height="27" class="STYLE13">类　　型</td>
		<td><select name="type" id="isyingsk"><option value="">选择类型...</option><option value="预收款">预收款</option><option value="应收款">应收款</option></select></td>
		<td width="84" height="27" class="STYLE13">方　　式</td>
		<!--<td><select name="skfs"><option value="调账">调账</option><option value="汇款">汇款</option><option value="划账">划账</option><option value="恒丰店划账">恒丰店划账</option><option value="旧系统预存">旧系统预存</option><option value="POS机招行">POS机招行</option><option value="现金">现金</option><option value="预存赠送">预存赠送</option><option value="支票">支票</option></select></td>-->
		<td><select name="skfs"><?
			$skrs = mysql_query("select * from b_skfs order by id ", $conn);
			while($__row = mysql_fetch_array($skrs)){
				echo "<option value='".$__row[1]."'>".$__row[1]."</option>";
			}
		?></select></td>
            </tr>
            <tr>
              <td width="84" height="27" class="STYLE13">摘要</td>
            <td colspan="3"><span class="STYLE13">
              <input name="zy"  type="text" id="zy" size=50 maxlength="50" required>
              </span></td>
            </tr>
            <tr>
              <td height="27" class="STYLE13">发生时间</td>
              <td colspan="3" class="STYLE13">
              <input  type="text" size=20 name="fssj" id="fssj" value="<? echo date("Y-m-d G:i:s");?>" readonly>
              格式如:2014-01-01 12:12:12</td>
            </tr>
            <tr>
              <td height="27" class="STYLE13">备注</td>
              <td colspan="3"><span class="STYLE13">
                <input name="memo"  type="text" id="memo" size=50 maxlength="50" >
              </span></td>
          </tr>
           
            <tr>
              <td height="27" class="STYLE13">&nbsp;</td>
              <td colspan="3" class="STYLE13"><span class="STYLE15">请仔细检查数据的正确性，摘要填用户相关信息，内部信息填备注。<br>
                </span></td>
            </tr>
            <tr>
              <td height="33" colspan="4"><div align="center">
                <label>
                <input type="submit" name="Submit" value="提 交"> 
                </label>
              </div></td>
            </tr>
        </table>
</form>
    </td>
  </tr>
</table>
<script type="text/javascript" src="../../js/jquery-1.8.3.min.js"></script>
<script type="text/javascript">


    var ddhs ='';
    $('#isyingsk').on('change',function(){
        return 0;
        var _this  = $(this);
        var _sktype = _this.val();
        if(_sktype == '订单合并收款') {

            var _html = '<tr id="ddhtr"><td style="color:#FF6347;" height="27" class="STYLE13">订单号<br>(请选择需要合并付款的订单号)</td>' +
                '<td colspan="3"><span class="STYLE13"> ' +
                '<div id="ddhs" maxlength="500"></div> ' +
                '</span></td></tr>';
            _this.closest('tr').after(_html);

            var _senddata = 'khmcddhs='+ $('#khmc').val();
            $.ajax({
                type:'GET',
                data: _senddata,
                dataType:"json",
                success: function (data) {

                    var _html =  "<lable><input id='ifcheckall' type='checkbox' checked='checked'/>全选</lable><br>";
                    var _hbje =0.00;
                    if(data!=null){
                        var _l = data.length;
                        for(var i=0;i<_l;i++){
                            _html += '<lable><input name="ddhs[]" class="aaaa" type="checkbox" checked="checked" value="'+ data[i]['ddh'] +'" datatype= "'+ data[i]['dje'] +'">订单号:' +data[i]['ddh'] + '金额：'+ data[i]['dje'] +'元</lable><br>';
                            _hbje += parseFloat(data[i]['dje']);
                        }

                    }else{
                        _html='无待付款订单';
                    }

//                    _hbjestr = _hbje + "";
//                    form1.df.value = _hbjestr.substring(0,_hbjestr.indexOf(".")+3);
                    form1.df.value = _hbje.toFixed(2);
                    form1.zy.value = "订单结算";
                    $('#ddhs').html(_html);



                },
                error: function (){
//                    $.each(data,function(index,value){
//                        $('#ddhs').html(value);
//                    });
                        $('#ddhs').html('error');

                }
            });
        }else{

            $('#ddhtr').hide();
            form1.df.value = '0.00';
            form1.zy.value = "";

        }
    });

    $(document).on('click','input.aaaa',function(){

        var _this = $(this);
        var _hbje = 0.00;

        var items = _this.closest('div').find('input:checkbox[name="ddhs[]"]:checked');

        for(var i=0;i<items.length;i++){
            var itemje = $(items[i]).attr('datatype');
            _hbje += parseFloat(itemje);
        }
//        _hbjestr = _hbje + "";
//        form1.df.value = _hbjestr.substring(0,_hbjestr.indexOf(".")+3);
        form1.df.value = _hbje.toFixed(2);


    });

    $(document).on('click','#ifcheckall',function(){

        var allinput = $("input[name='ddhs[]']");
        var ischecked = $('#ifcheckall').is(':checked');
        allinput.attr('checked',ischecked);

//算钱
        var _this = $(this);
        var _hbje = 0.00;

        var items = _this.closest('div').find('input:checkbox[name="ddhs[]"]:checked');

        for(var i=0;i<items.length;i++){
            var itemje = $(items[i]).attr('datatype');
            _hbje += parseFloat(itemje);
        }

        form1.df.value = _hbje.toFixed(2);


    });



</script>
</body>
</html>
<? 
$rs=null;
unset($rs);
$rss=null;
unset($rss);
?>
