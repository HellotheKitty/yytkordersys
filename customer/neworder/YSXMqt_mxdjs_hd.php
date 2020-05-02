<? require("../../inc/conn.php");?>
<? session_start();
if ($_SESSION["OK"]<>"OK") {
    echo "<script language=JavaScript>{window.location.href='../error.php';}</script>";
    exit;
}?>
<?
if ($_POST["mxids"]<>"") {
    $ddh=$_POST["ddh"];
    $id=$_POST["id"];
    if ($id=="") {
        mysql_query("insert into order_mxqt_hd (id,mxid,jgfs,cpcc,jldw,sl,jg,memo,ddhao) values (0,'".$_POST["mxids"]."','".$_POST["jgfs"]."','".$_POST["cpcc"]."','".$_POST["jldw"]."','".$_POST["sl"]."','".$_POST["jg"]."','".$_POST["memo"]."','$ddh')",$conn);
    } else {   //有id 明细修改
        mysql_query("update order_mxqt_hd set jgfs='".$_POST["jgfs"]."',cpcc='".$_POST["cpcc"]."',jldw='".$_POST["jldw"]."',sl='".$_POST["sl"]."',jg='".$_POST["jg"]."',memo='".$_POST["memo"]."' where id=$id",$conn);
    }
    $rs=mysql_query("select sum(jg1*pnum1*sl1+jg2*pnum2*sl2) from order_mxqt mx where mx.ddh='$ddh'",$conn);
    $rshd=mysql_query("select sum(jg*sl) from order_mxqt_hd where mxid in (select id from order_mxqt where ddh='$ddh')",$conn);
    $rsfm = mysql_query("select sum(jg*sl) from order_mxqt_fm where mxid in (SELECT id from order_mxqt where ddh='$ddh')",$conn);


    if ($_SESSION["FBCW"]=="1") {
        mysql_query("update order_mainqt set dje=".(mysql_result($rs,0,0)+mysql_result($rshd,0,0)+mysql_result($rsfm,0,0)).",memo=concat(ifnull(memo,''),'财务调整".$_SESSION["SSUSER"]."',now()) where ddh='$ddh'",$conn);
    } else {
        mysql_query("update order_mainqt set dje=".(mysql_result($rs,0,0)+mysql_result($rshd,0,0)+mysql_result($rsfm,0,0))." where ddh='$ddh'",$conn);
    }
//调整账务系统
    mysql_query("update order_zh set df=(select dje+ifnull(kdje,0) from order_mainqt where ddh='$ddh') where ddh='$ddh'",$conn);

    echo "<script>window.opener.location.reload();window.opener.opener.location.href='NS_new.php?ddh={$ddh}';window.close();</script>";

}


$pn=$_GET["pn"];$ddh=$_GET["ddh"];
$rs0=mysql_query("select productname,sl from order_mxqt where id=".$_GET["mxids"]);
$sl=mysql_result($rs0,0,1);
$jgfs=$_GET["jgfs"];
if ($_GET["mxhdid"]<>"" ) {
    $id=$_GET["mxhdid"];
    $rs=mysql_query("select * from order_mxqt_hd where id=".$_GET["mxhdid"]);
    $jgfs=mysql_result($rs,0,"jgfs");
    $cpcc=mysql_result($rs,0,"cpcc");
    $jldw=mysql_result($rs,0,"jldw");
    $sl=mysql_result($rs,0,"sl");
    $jg=mysql_result($rs,0,"jg");
    $memo=mysql_result($rs,0,"memo");
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>订单明细</title>
    <SCRIPT language=JavaScript src="../../js/form.js"></SCRIPT>
    <SCRIPT language=JavaScript src="../../js/jquery-1.8.3.min.js"></SCRIPT>
    <SCRIPT language=JavaScript>
        function checkForm(){
            //          有数量的价格不能为零
            if(form1.sl.value != 0 && parseFloat(form1.jg.value) == 0 ){
                form1.jg.focus();
                return confirm('单价为零，确定提交？');

            }

            if(!($("#jg").val()>0)) set();
            var tmpFrm = document.forms[0];
            var charBag = "-0123456789.";
            if (!checkNotNull(form1.mc, "")) return false;
            if (!checkNotNull(form1.bm, "")) return false;
            if (!checkNotNull(form1.je, "")) return false;
            if (!checkStrLegal(form1.je, "", charBag)) return false;
            return true; }
        function slc(obj) {
            var js=0,cs=1,xdj;
            var dj="<? echo $dj?>";
            var zz=form1.zz.value;
            if (zz.indexOf("-")>0) js=zz.substr(zz.indexOf("-")+1);
            var chicun=form1.chicun.value;
            if (chicun.indexOf("-")>0) cs=chicun.substr(chicun.indexOf("-")+1);
            if (dj.indexOf("-")>0) {
                var i;
                var aa=dj.split(";");
                if (parseFloat(aa[aa.length-1].substr(0,aa[aa.length-1].indexOf("-")))<=obj.value)
                    xdj=aa[aa.length-1].substr(aa[aa.length-1].indexOf("-")+1);
                else {
                    for (i = 0; i < aa.length-1; i++) {
                        if (parseFloat(aa[i].substr(0,aa[i].indexOf("-")))<=obj.value && parseFloat(aa[i+1].substr(0,aa[i+1].indexOf("-")))>obj.value) xdj=aa[i].substr(aa[i].indexOf("-")+1);
                    }
                }
                xdj=(parseFloat(xdj)+parseFloat(js))*parseFloat(cs)
                document.getElementById("dj").innerHTML=xdj;
                form1.zj.value=formatCurrency(xdj*obj.value);
            } else {
                xdj=(parseFloat(dj)+parseFloat(js))*parseFloat(cs)
                document.getElementById("dj").innerHTML=xdj;
                form1.zj.value=formatCurrency(xdj*obj.value);
            }
        }
        function formatCurrency(num) {
            if(isNaN(num))
                num = "0";
            num = num.toString().replace(/\$|\,/g,'');
            sign = (num == (num = Math.abs(num)));
            num = Math.floor(num*100+0.50000000001);
            cents = num%100;
            num = Math.floor(num/100).toString();
            if(cents<10)
                cents = "0" + cents;
            for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
                num = num.substring(0,num.length-(4*i+3))+','+
                    num.substring(num.length-(4*i+3));
            return (((sign)?'':'-') + num + '.' + cents);
        }

        window.addEventListener('message',function(e){
            var data=e.data;
            if (data.substr(0,1)=="3")
                if (data.substr(1)=="del") document.getElementById('file1').value=""; else document.getElementById('file1').value+=data.substr(1)+";";
            else
            if (data.substr(1)=="del") document.getElementById('file2').value=""; else document.getElementById('file2').value+=data.substr(1)+";";
        },false);
        function set()
        {
//	$("#test").html("getprice.php?type=2&ddh="+$("#ddh").val()+"&jgfs="+$("#jgfs").val()+"&cpcc="+$("#cpcc").val()+"&jldw="+$("#jldw").val());
            if($("#jgfs").val() == "-1")
                return;
            $.ajax({
                type: "get",
                url: "getprice.php?type=2&ddh="+$("#ddh").val()+"&jgfs="+$("#jgfs").val()+"&cpcc="+$("#cpcc").val()+"&jldw="+$("#jldw").val(),
                async: false,
                success: function(data){
                    data = parseFloat(data);

                    $("#jg").val(data);
                },
                error: function(){
                    alert("价格获取失败");
                }
            });
        }
        //document.ready = set();
        /*
         function set()
         {
         //	$("#test").html("getprice.php?type=2&ddh="+$("#ddh").val()+"&jgfs="+$("#jgfs").val()+"&cpcc="+$("#cpcc").val()+"&jldw="+$("#jldw").val());
         if($("#jgfs").val() == "-1")
         return;
         $.ajax({
         type: "get",
         url: "getprice.php?type=2&ddh="+$("#ddh").val()+"&jgfs="+$("#jgfs").val()+"&cpcc="+$("#cpcc").val()+"&jldw="+$("#jldw").val(),
         async: false;
         success: function(data){
         //	alert(data);
         $("#jg").val(data);
         },
         error: function(){
         alert("价格获取失败");
         }
         });
         }
         */
    </SCRIPT>
    <style type="text/css">
        body {
            background-color: #A5CBF7;
        }
        .style11 {font-size: 14px}
        .STYLE13 {font-size: 12px}
        .tstyle1 { background-color:#DDD; text-align:center}
    </style>
</head>

<body onload="set()">

<form action="" method="post" name="form1" id="form1" onSubmit="return checkForm()">
    <table width="100%" border="1" cellpadding="4" cellspacing="1" bordercolor="#000099" bgcolor="f6f6f6">
        <tr>
            <td height="222" valign="top">
                <table width="95%" height="153" border="0" align="center">
                    <tr>
                        <td height="27" class="STYLE13">产品名称</td>
                        <td width="332" class="STYLE13"> <? echo mysql_result($rs0,0,0)?> </td>
                        <input type="hidden" name="ddh" id="ddh" value="<? echo $ddh?>" />
                        <input type="hidden" name="mxids" value="<? echo $_GET["mxids"]?>" />
                        <input type="hidden" name="id" value="<? echo $id?>" />
                        <td width="192" align="right" class="STYLE13">&nbsp;</td>
                    </tr>

                    <tr>
                        <td width="78" height="27" class="STYLE13">后加工方式</td>
                        <td colspan="2" class="STYLE13">
                            <select name="jgfs" id="jgfs" onChange="window.location.href='YSXMqt_mxdjs_hd.php?ddh=<? echo $ddh?>&mxids=<? echo $_GET["mxids"]?>&pn=<? echo $pn?>&jgfs='+this.options[this.selectedIndex].value;">
                                <option value="-1">请选择...</option>
                                <? $rs1=mysql_query("select distinct afterprocess from b_afterprocess order by afterprocess");
                                if($rs1)
                                    for ($i=0;$i<mysql_num_rows($rs1);$i++) {?>
                                        <option value="<? echo mysql_result($rs1,$i,0);?>" <? if ($jgfs==mysql_result($rs1,$i,0)) echo "selected";?> ><? echo mysql_result($rs1,$i,0);?></option>
                                    <? }?>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td height="27" colspan="3" class="STYLE13">

                            <div id="n1" >

                                <table width="100%" border="1" align="center" cellspacing="0" cellpadding="0" style="border-width:0px;border-collapse:collapse;border:1px solid #D3D3D3; border-bottom:1px solid #D3D3D3; font-size:12px">

                                    <tr>
                                        <td width="21%" class="tstyle1">成品尺寸</td>
                                        <td width="30%"><select name="cpcc" id="cpcc" onchange="set()" >
                                                <? $rs1=mysql_query("select distinct chicun from b_afterprocess where afterprocess='$jgfs' order by chicun");
                                                if($rs1)
                                                    for ($i=0;$i<mysql_num_rows($rs1);$i++) {?>
                                                        <option value="<? echo mysql_result($rs1,$i,0);?>" <? if ($cpcc==mysql_result($rs1,$i,0)) echo "selected";?> ><? echo mysql_result($rs1,$i,0);?></option>
                                                    <? }
                                                $rs2 = mysql_query("select * from b_psize",$conn);
                                                for($i=0;$i<mysql_num_rows($rs2);$i++){ ?>
                                                    <option value="自定-<? echo mysql_result($rs2,$i,0); ?>" <? if ($cpcc==("自定-".mysql_result($rs2,$i,0))) echo "selected";?> >自定-<? echo mysql_result($rs2,$i,0);?></option>

                                                <? }

                                                ?>
                                            </select>

                                        </td>
                                        <td width="15%" class="tstyle1">单位</td>
                                        <td width="34%"><select name="jldw" id="jldw" >
                                                <? $rs1=mysql_query("select distinct unit from b_afterprocess where afterprocess='$jgfs' order by unit");
                                                if($rs1)
                                                    for ($i=0;$i<mysql_num_rows($rs1);$i++) {?>
                                                        <option value="<? echo mysql_result($rs1,$i,0);?>" <? if ($jldw==mysql_result($rs1,$i,0)) echo "selected";?> ><? echo mysql_result($rs1,$i,0);?></option>
                                                    <? }?>
                                            </select></td>
                                    </tr>

                                    <tr>
                                        <td class="tstyle1">数量</td>
                                        <td colspan="3"><input name="sl" type="text" id="sl2" size="4" onchange="form1.je.value=form1.sl.value*form1.jg.value;" value="<? echo $sl?>"/></td>
                                    </tr>
                                    <tr>
                                        <td class="tstyle1">加工单价</td>
                                        <td><input name="jg" type="text" id="jg" size="4" onchange="form1.je.value=form1.sl.value*form1.jg.value;" value="<? echo $jg==""?0.00:$jg?>" /></td>
                                        <td class="tstyle1">加工金额</td>
                                        <td><input name="je" type="text" id="je" size="8" readonly="readonly" value="<? echo $sl*($jg==""?0.00:$jg)?>"/></td>
                                    </tr>
                                    <tr>
                                        <td class="tstyle1">备注</td>
                                        <td colspan="3"><input name="memo" type="text" id="memo" size="50"  value="<? echo $memo?>"/></td>
                                    </tr>
                                </table>
                            </div>

                        </td>
                    </tr>
                    <tr>
                        <td height="33" colspan="3"><div align="center">

                                <input type="submit" name="Submit" value="保 存">

                            </div>
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>
</form>
<div id="test"></div>
</body>
</html>
