<?
session_start();
require("../inc/conn.php");
// 不显示错误信息，调试时候注释掉。
error_reporting(0);

if ($_SESSION["OK"] <> "OK") {
    echo "<script language=JavaScript>{window.location.href='error.php';}</script>";
    exit;
}
include '../commonfile/calc_area_get.php';
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
$d4 = $_GET["fddh"];
if($d4 ==''){
    $d4 = '';
}
$d5 = $_GET['fmemo'];
if($d5 ==''){
    $d5='';
}

if ($_GET["bt2"]) {
    header("Content-type:application/vnd.ms-excel;charset=UTF-8");
    header("Content-Disposition:filename=" . iconv("utf-8", "gb2312", "客户消费导出[" . $d1 . "-" . $d2 . "].xls"));
    header("Expires:0");
    header('Pragma:   public');
    header("Cache-control:must-revalidate,post-check=0,pre-check=0");
}
$dwdm = substr($_SESSION["GDWDM"], 0, 4);

//$rsdd = mysql_query("select order_zh.*,order_mainqt.memo as dmemo , order_mxqt.*, order_mxqt_hd.* , order_mxqt_fm.* from order_zh left join order_mainqt on order_zh.ddh=order_mainqt.ddh LEFT JOIN order_mxqt on order_mainqt.ddh = order_mxqt.ddh LEFT JOIN order_mxqt_hd on order_mxqt.id = order_mxqt_hd.mxid left join order_mxqt_fm on order_mxqt.id = order_mxqt_fm.mxid where order_zh.zy='订单结算' and order_zh.fssj>='$d1 00:00:00' and order_zh.fssj<='$d2 23:59:59' and order_zh.khmc like '%{$d3}%' and locate('$d4',order_zh.ddh)>0 and order_zh.df>0 and order_mainqt.zzfy in $dwdmStr order by order_zh.fssj", $conn);

//总条数
$restotal = mysql_query("select count(*) rowcount , sum(order_mainqt.dje) zdje from order_mainqt left join order_zh on order_zh.ddh=order_mainqt.ddh where order_zh.zy='订单结算' and order_zh.df>0 and order_zh.fssj>='$d1 00:00:00' and order_zh.fssj<='$d2 23:59:59' and order_zh.khmc like '%{$d3}%' and locate('$d4',order_zh.ddh)>0 and (locate('$d5',order_mainqt.memo)>0 or locate('$d5',bzyq)>0 ) and order_mainqt.zzfy in $dwdmStr and order_mainqt.state<>'作废订单' ",$conn);
$rowcount = mysql_result($restotal,0,'rowcount');
$zdje = mysql_result($restotal,0,'zdje');

include '../commonfile/paging_data.php';
//打印daochu
if($_GET["bt2"]){
    $startrow = 0;
    $pagenum = $rowcount;
}
//backend 单价为0不显示
//$rsdd = mysql_query("select order_zh.*,order_mainqt.memo as dmemo from order_zh left join order_mainqt on order_zh.ddh=order_mainqt.ddh where order_zh.zy='订单结算' and order_zh.fssj>='$d1 00:00:00' and order_zh.fssj<='$d2 23:59:59' and order_zh.khmc like '%{$d3}%' and locate('$d4',order_zh.ddh)>0 and order_zh.df>0 and order_mainqt.zzfy in $dwdmStr order by order_zh.fssj", $conn);
$rsdd = mysql_query("select order_zh.*,order_mainqt.memo as dmemo,order_mainqt.scqkbz,order_mainqt.dje from order_zh left join order_mainqt on order_zh.ddh=order_mainqt.ddh where order_zh.zy='订单结算' and order_zh.df>0 and order_zh.fssj>='$d1 00:00:00' and order_zh.fssj<='$d2 23:59:59' and order_zh.khmc like '%{$d3}%' and locate('$d4',order_zh.ddh)>0 and (locate('$d5',order_mainqt.memo)>0 or locate('$d5',bzyq)>0) and order_mainqt.zzfy in $dwdmStr and order_mainqt.state<>'作废订单' order by order_zh.fssj limit $startrow ,$pagenum ", $conn);

?>
<script src="../js/WdatePicker.js" type="text/javascript" language="javascript"></script>
<form method="get">
    按日期：<input onClick="WdatePicker();" maxlength="50" class="Wdate" style="cursor:pointer;" type="text" name="rq1" id="rq1" value="<? echo $d1; ?>" size="9" readonly/>～<input onClick="WdatePicker();" maxlength="50" class="Wdate" style="cursor:pointer;" type="text" name="rq2" id="rq2" value="<? echo $d2; ?>" size="9" readonly/>&nbsp;客户名称：
    <input type="text" name="fkhmc" width="15" value="<? echo $d3 == "%" ? "" : $d3; ?>"/>
    &nbsp;&nbsp;&nbsp;订单号: <input type="text" name="fddh" width="15" value="<? echo $d4 == "%" ? "" : $d4; ?>"/>
    &nbsp;&nbsp;&nbsp;备注: <input type="text" name="fmemo" width="15" value="<? echo $d5 == "%" ? "" : $d5; ?>"/>

    <? include '../commonfile/calc_options.php'; ?>

    <input name="bt1" type="submit" value="查 询"/>　
    <input type="submit" name="bt2" value="导 出"/>

    <input style="display: none;" type="button" onclick="javascript:window.open('../phpExcel/toexcel_common.php?fddh=<? echo $d4 ?>&fkhmc=<? echo $d3; ?>&dd1=<? echo urlencode($dd1); ?>&dd2=<? echo urlencode($dd2); ?>&dwdmstr=<? echo urlencode($dwdmStr) ?>&type=<? echo 'excel_mx' ?>')" name="buttonprint" value="导出">

</form>
<?
if (!$_GET["bt1"] && !$_GET["bt2"]) {
//    echo "查询的时间为结算收款时间，查找的结果为已结算的订单，这些订单可能未生成配送单或暂未配送。";
//    exit;
}


//	$rs=mysql_query("select order_mainqt.*,count(order_mxqt.id) mxsl,group_concat(order_mxqt.productname) cpms,xs.xm,sksj from b_ry xs,order_mainqt left join order_mxqt on order_mainqt.ddh=order_mxqt.ddh left join order_zh on order_mainqt.ddh=order_zh.ddh and zy<>'订单订金' where order_mainqt.xsbh=xs.bh and order_mainqt.state='订单完成' and ddate>='$d1 00:00:00' and ddate<='$d2 23:59:59' and order_mainqt.khmc like '%{$d3}%' and zzfy='".substr($_SESSION["GDWDM"],0,4)."' group by order_mainqt.ddh order by ddate desc",$conn);
//	$rs=mysql_query("select main.ddh,main.kydh,ry.bh,ry.xm,main.khmc,main.sdate,main.dje+main.kdje je,main.djje,zh.df,zh.xsbh,zh.sksj,main.skbz from order_mainqt main inner join order_zh zh on (main.state='订单完成') and main.zzfy=3301 and zh.ddh=main.ddh inner join b_ry ry on main.xsbh=ry.bh group by main.ddh",$conn);


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
<style type="text/css">
    .suminfo span{
        margin-right:20px;
        margin-left:10px;
        font-size:13px;
        font-weight:bold;
        color:#333;
    }
    .mxtb td{
        text-align: center;
        padding: 5px 0;
    }
</style>
<div class="suminfo">
    <span>订单数量:<? echo $rowcount; ?></span>
    <span>订单总金额:<? echo $zdje; ?></span>
</div>
<span id='xxx' style="display:none"></span>
<table class="mxtb" width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:0px;">
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
                                <th scope="col">客户名称</th>
                                <th scope="col">收款时间</th>
                                <th scope="col">后加工方式</th>
                                <th scope="col">覆膜方式</th>
                                <th scope="col" width="15%">印件名称</th>
                                <th scope="col" width="10%">订单备注</th>
                                <th scope="col" width="10%">生产备注</th>
                                <th scope="col">构件</th>
                                <th scope="col">文件名</th>
                                <th scope="col">机器及颜色</th>
                                <th scope="col">纸张</th>
                                <th scope="col">规格</th>
                                <th scope="col">单双</th>
                                <th scope="col">单位</th>
                                <th scope="col">数量</th>
                                <th scope="col">单价</th>
                                <!--<th  scope="col">小计金额</th>-->
                                <th scope="col">结算金额</th>
                            </tr>
                            <? while ($dd = mysql_fetch_array($rsdd)) {
                                $xj = 0;
                                unset($memomemo);
                                unset($memomemo1);
//                                $zhrs = mysql_query("select sksj from order_zh where ddh='" . $dd["ddh"] . "' and zy<>'订单定金' and df>0", $conn);
//                                if (!$zhrs || mysql_num_rows($zhrs) <= 0) continue;
                                $sksj =$dd["sksj"];
                                $dd["sdate"] = $sksj;
                                $rsmx = mysql_query("select * from order_mxqt where ddh='" . $dd["ddh"] . "'", $conn);
                                while ($mx = mysql_fetch_array($rsmx)) { ?>
                                    <tr class="td_title" style="height:30px;">
                                        <td align="center"><? echo $dd["ddh"] ?></td>
                                        <!--          		<td  align="center">--><? //echo $dd["kydh"]?><!--</td>-->
                                        <td align="center"><? echo $dd["khmc"] ?></td>
                                        <td align="center"><? echo $dd["sdate"] ?></td>
                                        <td align="center"></td>
                                        <td align="center"></td>
                                        <td align="center"><? echo $mx["pname"]; ?></td>
                                        <td align="center"><? if (!isset($memomemo)) {
                                                echo $dd["dmemo"];
                                                $memomemo = 1;
                                            } ?></td>
                                        <td>
                                            <? if (!isset($memomemo1)) {
                                                echo $dd["scqkbz"];
                                                $memomemo1 = 1;
                                            } ?>
                                        </td>
                                        <td align="center"></td>
                                        <td align="center"></td>
                                        <td align="center"></td>
                                        <td align="center"></td>
                                        <td align="center"></td>
                                        <td align="center"></td>
                                        <td align="center"></td>
                                        <td align="center"></td>
                                        <td align="center"></td>
                                        <!--<td  align="center"></td>-->
                                        <td align="center"></td>
                                    </tr>
                                <? }
                                $mxid = "(";
                                $rsmx = mysql_query("select * from order_mxqt where ddh='" . $dd["ddh"] . "'", $conn);
                                while ($mx = mysql_fetch_array($rsmx)) { ?>
                                    <tr class="td_title" style="height:30px;">
                                        <td align="center"></td>
                                        <td  align="center"></td>
                                        <td align="center"></td>
                                        <td align="center"></td>
                                        <td align="center"></td>
                                        <td align="center"></td>
                                        <td align="center"></td>
                                        <td align="center"></td>
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
                                            <td align="center"></td>
                                            <td  align="center"></td>
                                            <td align="center"></td>
                                            <td align="center"></td>
                                            <td align="center"></td>
                                            <td align="center"></td>
                                            <td align="center"></td>
                                            <td align="center"></td>
                                            <td align="center"><? echo $mx["n2"] ?></td>
                                            <td align="center"><? echo $mx["file2"] ?></td>
                                            <td align="center"><? echo $mx["machine2"] ?></td>
                                            <td align="center"><? $p2 = mysql_query("select * from material where MaterialCode='" . $mx["paper2"] . "'", $conn);
                                                if (!$p2 || mysql_num_rows($p2) <= 0) $p2 = mysql_query("select * from material1 where MaterialCode='" . $mx["paper2"] . "'", $conn);
                                                echo mysql_result($p2, 0, "MaterialName") ?></td>
                                            <td align="center"><? echo mysql_result($p2, 0, "Specs") ?></td>
                                            <td align="center"><? echo $mx["dsm2"] ?></td>
                                            <td align="center"><? echo $mx["jldw2"] ?></td>
                                            <td align="center"><? echo $mx["pnum2"] * $mx["sl2"]; ?></td>
                                            <td align="center"><? echo $mx["jg2"] ?></td>
                                            <!--<td  align="center"><?//$xj += $mx["pnum2"]*$mx["sl2"]*$mx["jg2"]?></td>-->
                                            <td align="center"></td>
                                        </tr>

                                        <?
                                    }
                                    $mxid .= $mx["id"] . ",";
                                }
                                $mxid = substr($mxid, 0, -1) . ")";
                                //echo $mxid;
                                $sql = "select * from order_mxqt_hd where mxid in $mxid";
                                $hdrs = mysql_query($sql, $conn);
                                while ($hdrs && $hd = mysql_fetch_array($hdrs)) {
                                    ?>
                                    <tr class="td_title" style="height:30px;">
                                        <td align="center"></td>
                                        <td  align="center"></td>
                                        <td align="center"></td>
                                        <td align="center"><? echo $hd["jgfs"] ?></td>
                                        <td align="center"></td>
                                        <td align="center"></td>
                                        <td align="center"></td>
                                        <td align="center"></td>
                                        <td align="center"></td>
                                        <td align="center"></td>
                                        <td align="center"></td>
                                        <td align="center"></td>
                                        <td align="center"></td>
                                        <td align="center"></td>
                                        <td align="center"><? echo $hd["jldw"] ?></td>
                                        <td align="center"><? echo $hd["sl"] ?></td>
                                        <td align="center"><? echo $hd["jg"];//$xj+=$hd["sl"]*$hd["jg"];?></td>
                                        <!--<td  align="center"></td>-->
                                        <td align="center"></td>
                                    </tr>

                                <? } ?>
                                <?
                                $sql = "select * from order_mxqt_fm where mxid in $mxid";
                                $hdrs = mysql_query($sql, $conn);
                                while ($hdrs && $hd = mysql_fetch_array($hdrs)) {
                                    ?>
                                    <tr class="td_title" style="height:30px;">
                                        <td align="center"></td>
                                        <td align="center"></td>
                                        <td align="center"></td>
                                        <td align="center"></td>
                                        <td align="center"><? echo $hd["fmfs"] ?></td>
                                        <td align="center"></td>
                                        <td align="center"></td>
                                        <td align="center"></td>
                                        <td align="center"></td>
                                        <td  align="center"></td>
                                        <td align="center"></td>
                                        <td align="center"></td>
                                        <td align="center"></td>
                                        <td align="center"></td>
                                        <td align="center"><? echo $hd["jldw"] ?></td>
                                        <td align="center"><? echo $hd["sl"] ?></td>
                                        <td align="center"><? echo $hd["jg"];//$xj+=$hd["sl"]*$hd["jg"];?></td>
                                        <!--<td  align="center"></td>-->
                                        <td align="center"></td>
                                    </tr>

                                <? } ?>
                                <tr style="height: 30px">

                                    <td align="center" colspan="10">小计</td>

                                    <!--<td  align="center"><? //echo sprintf("%.2f" ,$xj); $txj += $xj;?></td>-->
                                    <td align="center" colspan="8"><? echo $dd["dje"];
                                        $txjj += $dd["dje"] ?></td>
                                </tr>

                            <? } ?>
                            <tr style="height: 30px">

                                <td align = "center" colspan = "10">合计</td>

                                <!--<td  align="center"><? //echo sprintf("%.2f", $txj); //$txj += $xj;?></td>-->
                                <td align="center" colspan="8"><? echo $txjj; //$txj += $xj;?></td>
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
$param = 'rq1='.$d1.'&rq2='.$d2.'&fkhmc='.$d3.'&fddh='.$d4.'&fmemo='.$d5;
include '../commonfile/paging_show.php';
?>
</body>
</html>
