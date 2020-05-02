<?
session_start();
header("Content-Type:text/html;charset=UTF-8");
require("../../inc/conn.php");
if ($_SESSION["OK"] <> "OK") {
    echo "<script language=JavaScript>{window.location.href='../error.php';}</script>";
    exit;
} ?>

<?
$dwdm = substr($_SESSION["GDWDM"], 0, 4);

include '../../commonfile/calc_area_get.php';

if ($_GET["bt2"]) {
    header("Content-type:application/vnd.ms-excel;charset=UTF-8");
    header("Content-Disposition:filename=" . iconv("utf-8", "gb2312", "客户消费导出[" . $d1 . "-" . $d2 . "].xls"));
    header("Expires:0");
    header('Pragma:   public');
    header("Cache-control:must-revalidate,post-check=0,pre-check=0");
}

if ($_GET["DELID"] <> "") {
    $khrs = mysql_query("select * from order_mainqt where khmc=(select khmc from base_kh where id='" . $_GET["DELID"] . "')", $conn);
    if ($khrs && mysql_num_rows($khrs) > 0) {
        ?>
        <script type="text/javascript">
            if (confirm("该客户有订单，如要删除，则该客户的订单也将一并删除。是否仍要删除？")) {
                //alert("?QZDEL=<?php //echo $_GET["DELID"]?>");
                //exit;
                window.location = "?QZDEL=<?echo $_GET["DELID"]?>";
            }
        </script>
        <?
    } else {
        mysql_query("delete from base_kh where id=" . $_GET["DELID"]);
    }
}
//会员级别修改
if($_GET['khidjb']<>''){

    $new_jb = $_GET['nhyjb'];
    if(empty($new_jb)){
        mysql_query("update base_kh set hyjb = null where mpzh = '".$_GET['khidjb']."'",$conn);

    }else{
        mysql_query("update base_kh set hyjb = '".$new_jb ."' where mpzh = '".$_GET['khidjb']."'",$conn);
    }

        echo $new_jb?$new_jb:'0';
        exit();

}
if ($_GET["QZDEL"] <> "") {

    $khrs = mysql_query("select khmc from base_kh where id='" . $_GET["QZDEL"] . "'", $conn);
//    $khmc = mysql_result($khrs, 0, "khmc");
}

$tj = " and gdzk in $dwdmStr ";

if($_GET['khmc']<>''){

    $f_khmc = $_GET['khmc'];
    $tj .= " and base_kh.khmc like '%$f_khmc%'";
}else{
    $f_khmc = '';
}

//pageing
$restotal =  mysql_query("SELECT count(*) as rowcount from kh_ye LEFT JOIN (SELECT sum((ifnull(`order_zh`.`jf`, 0) - ifnull(`order_zh`.`df`, 0))) as czxf ,khmc FROM order_zh WHERE fssj > IFNULL((SELECT sdate FROM kh_ye LIMIT 1),'2015-01-01')  GROUP BY khmc ) a on kh_ye.depart = a.khmc LEFT JOIN base_kh on kh_ye.depart = base_kh.khmc where 1=1 $tj ", $conn);
$rowcount= mysql_result($restotal,0,'rowcount');

include '../../commonfile/paging_data.php';
//    print
if($_GET['bt2']){
    $startrow = 0;
    $pagenum = $rowcount;
}
//$rs = mysql_query("SELECT base_kh.id,base_kh.mpzh,kh_ye.zh,base_kh.lxr,mobile,depart, sum(ye + IFNULL(czxf,0)) AS ye, hyjb,waixie,lxdz from kh_ye LEFT JOIN (SELECT sum((ifnull(`order_zh`.`jf`, 0) - ifnull(`order_zh`.`df`, 0))) as czxf ,khmc FROM order_zh WHERE fssj > IFNULL((SELECT sdate FROM kh_ye LIMIT 1),'2015-01-01')  GROUP BY khmc ) a on kh_ye.depart = a.khmc LEFT JOIN base_kh on kh_ye.depart = base_kh.khmc where 1=1 $tj group by kh_ye.depart limit $startrow ,$pagenum", $conn);
$rs = mysql_query("SELECT * from base_kh where 1=1 $tj group by base_kh.khmc limit $startrow ,$pagenum", $conn);

?>
<html>
<head><title></title>
    <META http-equiv=Content-Type content="text/html; charset=utf-8">
    <LINK href="../../css/content.css" type=text/css rel=stylesheet>
    <SCRIPT language=JavaScript src="../../js/form.js"></SCRIPT>
    <SCRIPT language=JavaScript>
        function checkForm() {
            var charBag = "0123456789";
            if (!checkNotNull(form1.mpzh, "客户编号")) return false;
            if (!checkNotNull(form1.khmc, "客户名称")) return false;
            return true;
        }
    </SCRIPT>

    <meta content="MSHTML 6.00.3790.1830" name=GENERATOR>

    <style TYPE="text/css">

        A:link {
            text-decoration: none;
        }

        A:visited {
            text-decoration: none;
        }

        A:hover {
            color: #EF6D21;
            text-decoration: underline;
        }

        .STYLE4 {  color: #FF0000  }

        .STYLE13 {  font-size: 12px  }

        .button, .STYLE13 {  height: 26px;  }

        .bt2 {
            background-color: #f58541;
            border-color: #be5414;
            border-style: solid;
            border-width: 1px;
            clear: both;
            height: 25px;
            color: #ffffff;
            font-size: 10.5pt;
            width: 45px;
        }
        .fontcl{  color:#3366cc; text-decoration: underline; }
        .jbtd{  text-align: center; cursor: pointer; }
        .button_khjb{  margin-left:10px;}
    </style>
    <script language="JavaScript">

        function suredo(src, q) {
            var ret;
            ret = confirm(q);
            if (ret != false) window.location = src;
        }

    </script>
</head>
<body text=#000000 bgColor=#ffffff >
<form name="form1" method="get" action="" onSubmit="return checkForm()">
    <table cellSpacing=0 cellPadding=0 width="100%" border=0>
        <tbody>
        <tr>
            <td width="57%" height=13 class=guide style="background-image: url('../images/main_guide_bg2.gif')">
                <img src="../images/guide.gif" align=absMiddle>客户信息列表
            </td>
            <td width="43%" align=right class=guide style="background-image: url('../images/main_guide_bg2.gif')">
                <img src="../images/main_r.gif">
            </td>
        </tr>
        </tbody>
    </table>
    <br>
    <? if ($_GET["ID"] == "") {
        $id = "";
        $zdm = "";
    } else {
        $id = $_GET["ID"];
        $rss = mysql_query("select * from base_kh where id='" . $id . "'", $conn);
        $zdm = mysql_result($rss, 0, "khmc");
        $zmc = mysql_result($rss, 0, "lxr");
        $gsmc = mysql_result($rss, 0, "lxdh");
        $gwmc = mysql_result($rss, 0, "lxdz");
        $gdzk = mysql_result($rss, 0, "gdzk");
        $kpsm = mysql_result($rss, 0, "kp_sm");
        $memo = mysql_result($rss, 0, "memo");
        $mpzh = mysql_result($rss, 0, "mpzh");
        $qq = mysql_result($rss, 0, "qq");
        $hyjb = mysql_result($rss, 0, "hyjb");
        $jg = mysql_result($rss, 0, "jg");
        $waixie = mysql_result($rss, 0, "waixie");

    } ?>
    <?
    include '../../commonfile/calc_options.php';
    if($_GET['bt2']<>''){

    }else{

        ?>
    <input name="ID" id="zdm" type="hidden" class="STYLE13" value="<? echo $id ?>" size=25>
    <input name="KHMCOLD" id="khmcold" type="hidden" class="STYLE13" value="<? echo $zdm ?>" size=25>

    查找：
    <input name="khmc" id="khmc" type="text" class="STYLE13" value="<? echo $khmc; ?>" size=25>
        <input  type="submit" class="button" value="查 询">
        （可输入完整或部分的客户编号、客户名称、联系人进行模糊查询，客户编号不区分大小写）
    <? if($_SESSION["FBCW"] == 1){
//            cw导出客户资料
            ?>
            <input type="submit" class="bt2" name="bt2" value="导出">

            <? } ?>
    <? } ?>
    <input name="b1" type="button" class="button" value="新增" onClick='javascript:window.open("KH_add.php", "HT_dhdj", "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=yes,width=850,height=410,left=300,top=100")'>

    <div>
        <span style="font-weight: bold; line-height: 20px;">系统总余额:<?
//            echo mysql_result(mysql_query("select sum(ye) as zye from base_kh left join user_zhjf on base_kh.khmc = user_zhjf.depart where base_kh.gdzk in $dwdmStr and user_zhjf.ye>0"),0,'zye');

            $dfk = mysql_result(mysql_query("select sum(ye) as dfk from base_kh left join user_zhjf on base_kh.khmc = user_zhjf.depart where base_kh.gdzk in $dwdmStr and user_zhjf.ye<0"),0,'dfk');

            $zye = mysql_result(mysql_query("SELECT SUM(IFNULL(jf,0)) - SUM(IFNULL(df,0)) zye from order_zh LEFT JOIN base_kh on order_zh.khmc = base_kh.khmc where base_kh.gdzk in $dwdmStr "),0,'zye');

            echo (floatval($zye) + abs(floatval($dfk)));
            echo "  客户待付款总额:";
            echo (0-$dfk),'客户总数量:';
            echo mysql_result(mysql_query("select count(id) as khsl from base_kh where gdzk in $dwdmStr"),0,'khsl');
            ?>
        </span>

    </div>
    <table id="khguanlitb" class=maintable cellSpacing=1 cellPadding=1 width="100%" border=0>
        <tbody>
        <tr>
            <td class=head style="background-image: url('../images/nabg1.gif')" width="63">编号</td>
            <td class=head style="background-image: url('../images/nabg1.gif')" width="103">客户名称</td>
            <td class=head style="background-image: url('../images/nabg1.gif')" width="103">级别</td>
            <td class=head style="background-image: url('../images/nabg1.gif')" width="158">外协客户</td>
            <td class=head style="background-image: url('../images/nabg1.gif')" width="103">打印价格</td>
            <td class=head style="background-image: url('../images/nabg1.gif')" width="157">联系人</td>
            <td class=head style="background-image: url('../images/nabg1.gif')" width="158">联系电话</td>
            <td class=head style="background-image: url('../images/nabg1.gif')">联系地址</td>
            <td class=head style="background-image: url('../images/nabg1.gif')" width="158">账户余额</td>
            <td class=head style="background-image: url('../images/nabg1.gif')" width="158">待付款</td>
            <td class=head style="background-image: url('../images/nabg1.gif')" width="158">操作</td>
        </tr>
        <?
//        $zyc = 0;
//        $zqk = 0;
        while($row = mysql_fetch_array($rs)) {

            /*if(floatval($row['ye']) >0){

                $zyc += floatval($row['ye']);

            }elseif(floatval($row['ye'])<=0){

                $zqk += abs(floatval($row['ye']));
            }*/
            ?>
            <tr>
                <td width="63"><? echo $row['mpzh'] ?></td>
                <td width="103"><? echo $row["khmc"] ?></td>
                <td class="jbtd" id="<? echo $row['mpzh']; ?>" width="103">
                    <span class="fontcl"><? echo $row["hyjb"] ?></span>
                    <span>
                        <select class='nkhjb'  style="display:none;">
                            <option> </option>
                            <? $hyjbres = mysql_query("select hyjb,yck from base_hyjb ORDER BY hyjb");

                            for($j=0;$j<mysql_num_rows($hyjbres);$j++){
                                ?>
                                <option value="<? echo mysql_result($hyjbres,$j,'hyjb') ?>" <? if(mysql_result($hyjbres,$j,'hyjb') == $row["hyjb"]) echo "selected"; ?>>
                                    <? echo mysql_result($hyjbres,$j,'hyjb'); ?>
                                </option>
                            <? }  ?>
                        </select>
                        <a class='button_khjb'  style="display:none;" href="javascript:void(0);">提交</a>

                    </span>
                      </td>
                <td width="103">
                <? echo $row['waixie']; ?>
                </td>
                <td width="103">
                    <a href="priceofprint.php?khid=<? echo $row["id"] ?>" target="_blank">打印</a>
                    <a href="priceofafterprocess.php?khid=<? echo $row["id"] ?>"  target="_blank">后道</a>
                    <a href="priceoffumo.php?khid=<? echo$row["id"] ?>"  target="_blank">覆膜</a></td>
                <td width="157"><? echo $row["lxr"] ?></td>
                <td width="158"><? echo $row["lxdh"] ?></td>
                <td width="182"><? echo $row["lxdz"] ?></td>
                <td width="70"><?
                    $khmc = $row['khmc'];
                    include '../../commonfile/get_kh_ye.php';

                    echo $yue>=0? $yue : ''; ?>
                </td>
                <td width="70"><? echo $yue>=0? 0.00 : "<span style='color:#f00;'>".abs($yue)."</span>"; ?></td>
                <? if ($_SESSION["FBCW"] == 1||$_SESSION['FBSD']==1) { ?>
                    <td width="55" align="center"><a href="KH_add.php?mpzh=<? echo $row["mpzh"] ?>" target="_blank"  title="修改"  ><img
                            src="../images/func_edit.gif" alt="修改" width="18" height="17" border="0"></a>
                    <a onClick="javascript:suredo('?DELID=<? echo $row["id"] ?>','如要删除，则该客户的订单也将一并被删除，并且不可恢复。是否仍要删除？')"><img
                            src="../images/func_delete.gif" width="15" height="17" alt="删除" title="删除"></a></td><? } ?>
            </tr>
            <?
        } ?>
        </tbody>
    </table>

    <table class=maintable cellSpacing=1 cellPadding=1 width="100%" border=0 id="table1" height="19">
        <tr>
            <td height="16" background="../images/nabg1.gif" class=alert><span class="STYLE4">·客户信息管理。</span></TD>
        </tr>
    </table>
    <?
//    pageing
    $param = 'khmc='.$f_khmc;

    include '../../commonfile/paging_show.php'; ?>
</form>
</body>
<script type="text/javascript" src="../../js/jquery-1.8.3.min.js"></script>
<script type="text/javascript">

    $('#khguanlitb').on('click','.button_khjb',function(){
        var _this = $(this);
        var khid = _this.closest('td').attr('id');
        var _nhyjb = _this.closest('td').find('.nkhjb').val();
        var _senddata = 'khidjb='+ khid + '&nhyjb=' + _nhyjb ;
        $.ajax({
            type:"GET",
            data:_senddata,
            dataType:'json',
            success:function(data){
                _this.closest('td').find('.fontcl').html(data);
            },
            error:function(data){
//                console.log(data);
            }

        });
    });

    $('#khguanlitb').on('click','.jbtd',function(){
       $(this).find('.nkhjb').show();
       $(this).find('.button_khjb').show();
    });

</script>

</html>
