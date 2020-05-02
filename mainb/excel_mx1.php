<?
session_start();
require("../inc/conn.php");
// 不显示错误信息，调试时候注释掉。
error_reporting(0);

if ($_SESSION["OK"] <> "OK") {
    echo "<script language=JavaScript>{window.location.href='error.php';}</script>";
    exit;
}
include '../commonfile/calc_area.php';
@$d1 = $_POST["rq1"];
if ($d1 == "") {
    $d1 = date("Y-m-") . "01";
    $ss = "";
    $tss = "全部信息";
}
@$d2 = $_POST["rq2"];
if ($d2 == "") {
    $d2 = date("Y-m-d");
}
@$d3 = $_POST["fkhmc"];
if ($d3 == "") {
    $d3 = "%";
}
$d4 = $_POST["fddh"];
if($d4 ==''){
    $d4 = '';
}

if ($_POST["bt2"]) {
    header("Content-type:application/vnd.ms-excel;charset=UTF-8");
    header("Content-Disposition:filename=" . iconv("utf-8", "gb2312", "客户消费导出[" . $d1 . "-" . $d2 . "].xls"));
    header("Expires:0");
    header('Pragma:   public');
    header("Cache-control:must-revalidate,post-check=0,pre-check=0");
}

?>
<script src="../js/WdatePicker.js" type="text/javascript" language="javascript"></script>
<form method="post">
    按日期：<input onClick="WdatePicker();" maxlength="50" class="Wdate" style="cursor:pointer;" type="text" name="rq1" id="rq1" value="<? echo $d1; ?>" size="9" readonly/>～<input onClick="WdatePicker();" maxlength="50" class="Wdate" style="cursor:pointer;" type="text" name="rq2" id="rq2" value="<? echo $d2; ?>" size="9" readonly/>&nbsp;客户名称：
    <input type="text" name="fkhmc" width="15" value="<? echo $d3 == "%" ? "" : $d3; ?>"/>
    &nbsp;&nbsp;&nbsp;订单号: <input type="text" name="fddh" width="15" value="<? echo $d4 == "%" ? "" : $d4; ?>"/>

    <? include '../commonfile/calc_options.php'; ?>

    <input name="bt1" type="submit" value="查 询"/><? if ($_POST["bt1"]) { ?>　　
        <input type="submit" name="bt2" value="导 出"/>

        <input style="display: none;" type="button" onclick="javascript:window.open('../phpExcel/toexcel_common.php?fddh=<? echo $d4 ?>&fkhmc=<? echo $d3; ?>&dd1=<? echo urlencode($dd1); ?>&dd2=<? echo urlencode($dd2); ?>&dwdmstr=<? echo urlencode($dwdmStr) ?>&type=<? echo 'excel_mx' ?>')" name="buttonprint" value="导出">

    <? } ?></form>
<?
if (!$_POST["bt1"] && !$_POST["bt2"]) {
    echo "查询的时间为结算收款时间，查找的结果为已结算的订单，这些订单可能未生成配送单或暂未配送。";
    exit;
}
$dwdm = substr($_SESSION["GDWDM"], 0, 4);


//	$rs=mysql_query("select order_mainqt.*,count(order_mxqt.id) mxsl,group_concat(order_mxqt.productname) cpms,xs.xm,sksj from b_ry xs,order_mainqt left join order_mxqt on order_mainqt.ddh=order_mxqt.ddh left join order_zh on order_mainqt.ddh=order_zh.ddh and zy<>'订单订金' where order_mainqt.xsbh=xs.bh and order_mainqt.state='订单完成' and ddate>='$d1 00:00:00' and ddate<='$d2 23:59:59' and order_mainqt.khmc like '%{$d3}%' and zzfy='".substr($_SESSION["GDWDM"],0,4)."' group by order_mainqt.ddh order by ddate desc",$conn);
//	$rs=mysql_query("select main.ddh,main.kydh,ry.bh,ry.xm,main.khmc,main.sdate,main.dje+main.kdje je,main.djje,zh.df,zh.xsbh,zh.sksj,main.skbz from order_mainqt main inner join order_zh zh on (main.state='订单完成') and main.zzfy=3301 and zh.ddh=main.ddh inner join b_ry ry on main.xsbh=ry.bh group by main.ddh",$conn);

//$rsdd = mysql_query("select order_zh.*,order_mainqt.memo as dmemo , order_mxqt.*, order_mxqt_hd.* , order_mxqt_fm.* from order_zh left join order_mainqt on order_zh.ddh=order_mainqt.ddh LEFT JOIN order_mxqt on order_mainqt.ddh = order_mxqt.ddh LEFT JOIN order_mxqt_hd on order_mxqt.id = order_mxqt_hd.mxid left join order_mxqt_fm on order_mxqt.id = order_mxqt_fm.mxid where order_zh.zy='订单结算' and order_zh.fssj>='$d1 00:00:00' and order_zh.fssj<='$d2 23:59:59' and order_zh.khmc like '%{$d3}%' and locate('$d4',order_zh.ddh)>0 and order_zh.df>0 and order_mainqt.zzfy in $dwdmStr order by order_zh.fssj", $conn);
$rsdd = mysql_query("select order_zh.*,order_mainqt.memo as dmemo from order_zh left join order_mainqt on order_zh.ddh=order_mainqt.ddh where order_zh.zy='订单结算' and order_zh.fssj>='$d1 00:00:00' and order_zh.fssj<='$d2 23:59:59' and order_zh.khmc like '%{$d3}%' and locate('$d4',order_zh.ddh)>0 and order_zh.df>0 and order_mainqt.zzfy in $dwdmStr order by order_zh.fssj", $conn);

$txj = 0;
$txjj = 0;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>名片工坊-业务管理</title>

</head>

<body style="font-size:12px">

<span id='xxx' style="display:none"></span>
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:0px;">
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
                                <th align="center" scope="col">订单号</th>
                                <!--<th  align="center" scope="col">配送单号</th>-->
                                <th align="center" scope="col">客户名称</th>
                                <th align="center" scope="col">收款时间</th>
                                <th align="center" scope="col">后加工方式</th>
                                <th align="center" scope="col">覆膜方式</th>
                                <th align="center" scope="col" width="15%">印件名称</th>
                                <th align="center" scope="col" width="15%">备注</th>
                                <th align="center" scope="col">构件</th>
                                <th align="center" scope="col">文件名</th>
                                <th align="center" scope="col">机器及颜色</th>
                                <th align="center" scope="col">纸张</th>
                                <th align="center" scope="col">规格</th>
                                <th align="center" scope="col">单双</th>
                                <th align="center" scope="col">单位</th>
                                <th align="center" scope="col">数量</th>
                                <th align="center" scope="col">单价</th>
                                <!--<th  align="center" scope="col">小计金额</th>-->
                                <th align="center" scope="col">结算金额</th>
                            </tr>
                            <? while ($dd = mysql_fetch_array($rsdd)) {
                                $xj = 0;
                                unset($memomemo);
//                                $zhrs = mysql_query("select sksj from order_zh where ddh='" . $dd["ddh"] . "' and zy<>'订单定金' and df>0", $conn);
//                                if (!$zhrs || mysql_num_rows($zhrs) <= 0) continue;
//                                $sksj = mysql_result($zhrs, 0, "sksj");
//                                $dd["sksj"] = $sksj;
                                $rsmx = mysql_query("select order_mxqt.* , hd.jgfs as hdfs,hd.sl as hdsl, hd.jldw as hddw ,hd.jg as hdjg ,hd.memo as hdmemo , fm.fmfs, fm.sl as fmsl, fm.jg as fmjg, fm.memo as fmmemo from order_mxqt LEFT JOIN order_mxqt_hd hd on order_mxqt.id = hd.mxid LEFT JOIN order_mxqt_fm fm on order_mxqt.id = fm.mxid where order_mxqt.ddh='" . $dd["ddh"] . "'", $conn);

                                while ($mx = mysql_fetch_array($rsmx)) { ?>
                                    <tr class="td_title" style="height:30px;">
                                        <td align="center"><? echo $dd["ddh"] ?></td>
                                        <!--          		<td  align="center">--><? //echo $dd["kydh"]?><!--</td>-->
                                        <td align="center"><? echo $dd["khmc"] ?></td>
                                        <td align="center"><? echo $dd["sksj"] ?></td>
                                        <td align="center"><? echo $mx['hdfs']? ($mx['hdfs'] .'  ' . $mx['hdjg'] . '*' . $mx['hdsl'].$mx['hddw']):'' ?></td>
                                        <td align="center"><? echo  $mx['fmfs']? ($mx['fmfs'] .$mx['fmjg'] . '*' . $mx['fmsl'].$mx['fmdw']):'' ?></td>
                                        <td align="center"><? echo $mx["pname"]; ?></td>
                                        <td align="center"><? if (!isset($memomemo)) {echo $dd["dmemo"];$memomemo = 1;} ?></td>

                                        <td align="center"><? echo $mx["n1"] ?></td>
                                        <td align="center"><? echo $mx["file1"] ?></td>
                                        <td align="center"><? echo $mx["machine1"] ?></td>
                                        <td align="center"><? $p1 = mysql_query("select * from material where MaterialCode='" . $mx["paper1"] . "'", $conn);
                                            if (!$p1 || mysql_num_rows($p1) <= 0) $p1 = mysql_query("select * from material1 where MaterialCode='" . $mx["paper1"] . "'", $conn);
                                            echo mysql_result($p1, 0, "MaterialName"); ?></td>
                                        <td align="center"><? echo mysql_result($p1, 0, "Specs") ?></td>
                                        <td align="center"><? echo $mx["dsm1"] ?></td>
                                        <td align="center"><? echo $mx["jldw1"] ?></td>
                                        <td align="center"><? echo $mx["pnum1"] * $mx["sl1"]; ?></td>
                                        <td align="center"><? echo $mx["jg1"] ?></td>


                                        <!--<td  align="center"><?//$xj += $mx["pnum1"]*$mx["sl1"]*$mx["jg1"]?></td>-->
                                        <td align="center"></td>

                                    </tr>
                                    <? if ($mx["n2"] <> "") { ?>
                                        <tr class="td_title" style="height:30px;">
                                            <td align="center"><? echo $dd["ddh"] ?></td>
                                            <!--          		<td  align="center">--><? //echo $dd["kydh"]?><!--</td>-->
                                            <td align="center"><? echo $dd["khmc"] ?></td>
                                            <td align="center"><? echo $dd["sksj"] ?></td>
                                            <td align="center"><? echo $mx['hdfs'] ? ($mx['hdfs'] .'  ' . $mx['hdjg'] . '*' . $mx['hdsl'].$mx['hddw']):'' ?></td>
                                            <td align="center"><? echo  $mx['fmfs'] ? ($mx['fmfs'] .$mx['fmjg'] . '*' . $mx['fmsl'].$mx['fmdw']):'' ?></td>
                                            <td align="center"><? echo $mx["pname"]; ?></td>
                                            <td align="center"><? if (!isset($memomemo)) {echo $dd["dmemo"];$memomemo = 1;} ?></td>


                                            <td align="center"><? echo $mx["n2"] ?></td>
                                            <td align="center"><? echo $mx["file2"] ?></td>
                                            <td align="center"><? echo $mx["machine2"] ?></td>
                                            <td align="center"><? $p2 = mysql_query("select * from material where MaterialCode='" . $mx["paper2"] . "'", $conn);
                                                if (!$p2 || mysql_num_rows($p2) <= 0) $p2 = mysql_query("select * from material1 where MaterialCode='" . $mx["paper2"] . "'", $conn);
                                                echo mysql_result($p2, 0, "MaterialName") ?></td>
                                            <td align="center"><? echo mysql_result($p1, 0, "Specs") ?></td>
                                            <td align="center"><? echo $mx["dsm2"] ?></td>
                                            <td align="center"><? echo $mx["jldw2"] ?></td>
                                            <td align="center"><? echo $mx["pnum2"] * $mx["sl2"]; ?></td>
                                            <td align="center"><? echo $mx["jg2"] ?></td>
                                            <!--<td  align="center"><?//$xj += $mx["pnum2"]*$mx["sl2"]*$mx["jg2"]?></td>-->
                                            <td align="center"></td>
                                        </tr>

                                        <?
                                    }
                                 }

                                ?>
                                <tr style="height: 30px">
                                    <td align="center"></td>
                                    <!--<td  align="center"></td>-->
                                    <td align="center" colspan="9">小计</td>

                                    <!--<td  align="center"><? //echo sprintf("%.2f" ,$xj); $txj += $xj;?></td>-->
                                    <td align="center" colspan="7"><? echo $dd["df"];$txjj += $dd["df"] ?></td>
                                </tr>

                            <? } ?>
                            <tr style="height: 30px">
                                <td align="center"></td>
                                <!--<td  align="center"></td>-->
                                <td align="center" colspan="9">合计</td>

                                <!--<td  align="center"><? //echo sprintf("%.2f", $txj); //$txj += $xj;?></td>-->
                                <td align="center" colspan="7"><? echo $txjj; //$txj += $xj;?></td>

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
</body>
</html>
