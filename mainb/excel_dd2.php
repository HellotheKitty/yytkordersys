<?
session_start();
require("../inc/conn.php");

if ($_SESSION["OK"] <> "OK") {
    echo "<script language=JavaScript>{window.location.href='error.php';}</script>";
    exit;
}

include '../commonfile/calc_area_get.php';
//重新赋值
/*if ( $_SESSION['GDWDM']=='340000' ) {
    $seldw = '3405';
    $dwdmStr = "('3405')";
} elseif($_SESSION['GDWDM']=='330000'){
    $seldw = '3301';
    $dwdmStr = "('3301')";
}elseif($_SESSION['GDWDM']=='300000'){
    $seldw = 'sh';
    $dwdmStr = "('3301')";
}else{
    $dwdmStr = "('" . $dwdm . "')";
}*/

@$d1 = $_GET["rq1"];
if ($d1 == "") {
    $d1 = date("Y-m-") . "01";
    $ss = "";
    $tss = "全部信息";
}
@$d2 = $_GET["rq2"];
if ($d2 == "") {
    $d2 = date("Y-m-d");
}
@$d3 = $_GET["fkhmc"];
if ($d3 == "") {
    $d3 = "%";
}
@$kfmc = $_GET["fkfmc"];
if ($kfmc != "") {

    $reskf = mysql_result(mysql_query("select bh from b_ry where xm like '%$kfmc%' limit 1",$conn),0,'bh');
    $fkfmc = " and m.xsbh = '$reskf' ";
}


$skfs = $_GET["skfs"];
if ($skfs != "") $skfstj = " and z.xsbh='$skfs' "; else $skfstj = "";

$fddh = $_GET['fddh'];
if($fddh<>'') $ddhtj = " and locate('$fddh',z.ddh)>0 "; else $ddhtj ='';



?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>名片工坊-业务管理</title>
    <script src="../js/WdatePicker.js" type="text/javascript" language="javascript"></script>
    <style type="text/css">
        a {
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
            color: blue;
        }
        .suminfo span{
            margin-right:20px;
            margin-left:10px;
            font-size:13px;
            font-weight:bold;
            color:#333;
        }
        .shdtb td{
            text-align: center;
            border-bottom: 1px solid #d3d3d3;
            padding: 5px 0;
        }
    </style>
</head>

<body style="font-size:12px">

<script src="../js/WdatePicker.js" type="text/javascript" language="javascript"></script>
<form method="get" name="form1" action="">
    按日期：<input onClick="WdatePicker();" maxlength="50" class="Wdate" style="cursor:pointer;" type="text" name="rq1" id="rq1" value="<? echo $d1; ?>" size="9" readonly/>～<input onClick="WdatePicker();" maxlength="50" class="Wdate" style="cursor:pointer;" type="text" name="rq2" id="rq2" value="<? echo $d2; ?>" size="9" readonly/>&nbsp;
    收款方式：<select name="skfs">
        <option value="">全部</option>
        <option value="预存扣款"<? if ($skfs == "预存扣款") echo " selected" ?>>预存扣款</option>
        <option value="现金"<? if ($skfs == "现金") echo " selected" ?>>现金</option>
        <option value="支票"<? if ($skfs == "支票") echo " selected" ?>>支票</option>
        <option value="POS刷卡" <? if ($skfs == "POS刷卡") echo " selected" ?>>POS刷卡</option>
        <option value="汇款"<? if ($skfs == "汇款") echo " selected" ?>>汇款</option>
    </select>
<!--    <input type="checkbox" name="onlydiff" id="onlydiff" --><?// if ($_GET["onlydiff"] == "on") echo "checked" ?>
<!--    <label for="onlydiff">只查看结算与订单金额不同的订单</label>　　-->

    <select name="onlydiff">
        <option value="">请选择</option>
        <option value="discount" <? if ($_GET["onlydiff"] == "discount") echo "selected" ?>>有折扣的订单</option>
        <option value="skzero" <? if ($_GET["onlydiff"] == "skzero") echo "selected" ?>>收款金额为零的订单</option>
    </select>

    <? include '../commonfile/calc_options.php'; ?>

    <input name="bt1" type="submit" value="查 询"/>　　<input name="bt2" type="submit" value="导 出"/>

<?
/*if (!$_GET["bt1"] && !$_GET["bt2"]) {
    echo "查询的时间为结算收款时间，查找的结果为已结算的订单，这些订单可能未生成配送单或暂未配送。";
    exit;
}*/
@$dwdm = substr($_SESSION["GDWDM"], 0, 4);
//$restotal = mysql_query("select count(*) rowcount , sum(m.dje) zdje , sum(z.df) zysje from order_mainqt m left join order_zh z on m.ddh=z.ddh where z.fssj>='$d1 00:00:00' and z.fssj<='$d2 23:59:59' and z.khmc like '%{$d3}%' and zzfy in $dwdmStr and z.zy='订单结算' and z.df>0 $skfstj ",$conn);
$restotal = mysql_query("select count(*) rowcount ,sum(m.dje) zdje , sum(z.df) zysje from order_mainqt m left join order_zh z on m.ddh=z.ddh where z.fssj>='$d1 00:00:00' and z.fssj<='$d2 23:59:59' and z.khmc like '%{$d3}%' and zzfy in $dwdmStr and z.zy='订单结算' and m.state <>'作废订单' $skfstj $ddhtj $fkfmc",$conn);
$rowcount = mysql_result($restotal,0,'rowcount');
$zdje = mysql_result($restotal,0,'zdje');
$zysje = mysql_result($restotal,0,'zysje');
include '../commonfile/paging_data.php';
//打印 查询
if($_GET["bt2"] || $_GET['onlydiff']=='discount' || $_GET['onlydiff'] == 'skzero'){
    $startrow = 0;
    $pagenum = $rowcount;
}

//	$rs=mysql_query("select order_mainqt.*,count(order_mxqt.id) mxsl,group_concat(order_mxqt.productname) cpms,xs.xm,sksj from b_ry xs,order_mainqt left join order_mxqt on order_mainqt.ddh=order_mxqt.ddh left join order_zh on order_mainqt.ddh=order_zh.ddh and zy<>'订单订金' where order_mainqt.xsbh=xs.bh and order_mainqt.state='订单完成' and ddate>='$d1 00:00:00' and ddate<='$d2 23:59:59' and order_mainqt.khmc like '%{$d3}%' and zzfy='".substr($_SESSION["GDWDM"],0,4)."' group by order_mainqt.ddh order by ddate desc",$conn);
//	$rs=mysql_query("select main.ddh,main.kydh,ry.bh,ry.xm,main.khmc,main.sdate,main.dje+main.kdje je,main.djje,zh.df,zh.xsbh,zh.sksj,main.skbz from order_mainqt main inner join order_zh zh on (main.state='订单完成') and main.zzfy=3301 and zh.ddh=main.ddh inner join b_ry ry on main.xsbh=ry.bh group by main.ddh",$conn);
//$rs=mysql_query("select * from order_mainqt where ddate>='$d1 00:00:00' and ddate<='$d2 23:59:59' and order_mainqt.khmc like '%{$d3}%' and state in ('待配送','订单完成') and zzfy='$dwdm'",$conn);
$rs = mysql_query("select m.ddh ddh,m.xsbh xsbh,m.khmc khmc,m.dje dje,m.djje djje,z.df df,z.xsbh skfs,z.sksj sksj,m.skbz skbz from order_mainqt m left join order_zh z on m.ddh=z.ddh where z.fssj>='$d1 00:00:00' and z.fssj<='$d2 23:59:59' and z.khmc like '%{$d3}%' and zzfy in $dwdmStr and z.zy='订单结算' and m.state <>'作废订单' $skfstj $ddhtj $fkfmc order by fssj limit $startrow ,$pagenum", $conn);
//echo "select m.ddh ddh,m.xsbh xsbh,m.khmc khmc,m.dje dje,m.djje djje,z.df df,z.xsbh skfs,z.sksj sksj,m.skbz skbz from order_mainqt m left join order_zh z on m.ddh=z.ddh where ddate>='$d1 00:00:00' and ddate<='$d2 23:59:59' and m.khmc like '%{$d3}%' and state in ('待配送','订单完成') and zzfy='$dwdm' and z.zy='订单结算' and z.df>0 $skfstj";//exit;
$tdje = 0;
$tdjje = 0;
$tskje = 0;
$tzkje = 0;
if ($_GET["bt2"]) {
    header("Content-type:application/vnd.ms-excel;charset=UTF-8");
    header("Content-Disposition:filename=" . iconv("utf-8", "gb2312", "送货单导出[" . $d1 . "-" . $d2 . "].xls"));
    header("Expires:0");
    header('Pragma:   public');
    header("Cache-control:must-revalidate,post-check=0,pre-check=0");
}
?>

<span id='xxx' style="display:none"></span>
<div class="suminfo">
    <span>订单数量:<? echo $rowcount; ?></span>
    <span>订单总金额:<? echo $zdje; ?></span>
    <span>总实收金额:<? echo $zysje; ?></span>
</div>
<table class="shdtb" width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:0px;">
    <tbody>
    <tr>
        <td valign="top">
            <div style="padding:15px 4px 22px 4px; color:#58595B">
                <div class="bot_line"></div>
                <div class="page">

                    <div id="AspNetPager2" style="width:100%;text-align:right;">

                        <table cellspacing="0" cellpadding="0" rules="all" border="1" id="gvOrder"
                               style="border-width:0px;width:100%;border-collapse:collapse;border:1px solid #D3D3D3; border-bottom:1px solid #D3D3D3; font-size:12px">
                            <tbody>
                            <tr class="td_title" style="height:30px;">
                                <th scope="col">订单号</th>
                                <!--<th  scope="col">配送单号</th>-->
                                <th scope="col">下单客服</th>
                                <th scope="col">客户名称</th>
                                <!--<th  scope="col">开单时间</th>-->
                                <th scope="col">订单金额</th>
                                <th scope="col">预付定金</th>
                                <th scope="col">实收金额</th>
                                <th scope="col">折扣/多收金额</th>
                                <!-- <th  scope="col">收款方式</th>-->
                                <th scope="col">收款时间</th>
                                <th scope="col">收款备注</th>
                            </tr>
                            <? if (!($_GET["bt2"])) { ?>
                                <tr style="height:30px;">
                                    <td>
                                        <input name="fddh" type="text" placeholder="输入订单号" style="height:29px;" value="<? echo $fddh; ?>"/>
                                    </td>
                                    <td>
                                        <input type="text" name="fkfmc" placeholder="输入客服名" style="height: 29px" value="<? echo $kfmc; ?>"/>
                                    </td>
                                    <td><input type="text" name="fkhmc" width="15" value="<? echo $d3 == "%" ? "" : $d3; ?>" placeholder="输入客户名称" style="height:29px;"/></td>

                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>
<!--                                        <input name="zhaiyao" style="height:29px" placeholder="输入备注" value="--><?// echo $_GET["zhaiyao"] ?><!--"/>-->
                                    </td>
                                </tr>
                            <? } ?>
                            <? //$t = mysql_num_rows($rs); for($i=0;$i<$t;$i++)
                            while ($row = mysql_fetch_assoc($rs)) {

                                $flag = $row["dje"] - $row["djje"] - $row["df"];

                                if ($_GET["onlydiff"] == "discount" && $flag == 0) continue;

                                if($_GET['onlydiff'] == 'skzero' && $row['df'] >0) continue;

                                if (@!$xsxm[$row["xsbh"]]) {
                                    $kfrs = mysql_query("select xm from b_ry where bh='" . $row["xsbh"] . "'", $conn);
                                    if (mysql_num_rows($kfrs) > 0) {
                                        $xsxm[$row["xsbh"]] = mysql_result($kfrs, 0, "xm");
                                    } else {
                                        $xsxm[$row["xsbh"]] = '人员离职';
                                    }
                                }
                                ?>
                                <tr style="height:30px;">
                                    <td>
                                        <a href="javascript:void(0)" onclick="javascript:window.open('../ncerp/jcsj/YSXMqt_sh_p.php?ddh=<? echo $row["ddh"]; ?>','YSXMqt_sh_p','height=600px,width=920px,top=100px,left=200px,location=no,menubar=no,resizable=no,scrollbars=yes,status=no,titlebar=no,toolbar=no,directories=no');return false;" class="a1"><? echo $row["ddh"]; ?>

                                    </a></td>
                                    <td><? echo $xsxm[$row["xsbh"]]; ?></td>
                                    <td><? echo $row["khmc"]; ?></td>
                                    <td><? echo $flag == 0 ? $row["dje"] : "<font color='red'>" . $row["dje"] . "</font>";
                                        $tdje += $row["dje"]; ?></td>
                                    <td><? echo $flag == 0 ? $row["djje"] : "<font color='red'>" . $row["djje"] . "</font>";
                                        $tdjje += $row["djje"]; ?>
                                    </td>
                                    <td><? echo $flag == 0 ? $row["df"] : "<font color='red'><a style='color:red' target='_blank' href='../ncerp/jcsj/NS_new.php?resk=1&ddh=" . $row['ddh'] . "' >" . $row["df"] . "</a></font>";
                                        $tskje += $row["df"]; ?>
                                    </td>
                                    <td><? $zkje =round(($row['dje']-$row['df'] - $row['djje']),2) ;echo abs($zkje); $tzkje+=$zkje;  ?></td>

                                    <!--			<td >--><? // echo $row["skfs"];?><!--</td>-->
                                    <td><? echo $row["sksj"]; ?></td>
                                    <td><? echo $row["skbz"]; ?></td>
                                </tr>

                            <? } ?>
                            <tr style="height:30px;">
                                <td colspan='3'>合计</td>
                                <td><? echo $tdje; ?></td>
                                <td><? echo $tdjje; ?></td>
                                <td><? echo $tskje; ?></td>
                                <td><? echo $tzkje; ?></td>
                                <!--	<td ></td>-->
                                <td></td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <br>
                </div>
        </td>
    </tr>
    </tbody>
</table>
<?
//paging
//查询参数
$param = 'rq1='.$d1.'&rq2='.$d2.'&fkhmc='.$d3.'&skfs='.$skfs;

include '../commonfile/paging_show.php';
?>
</form>
</body>
</html>

