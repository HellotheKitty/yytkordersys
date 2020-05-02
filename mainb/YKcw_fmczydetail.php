<?
session_start();
require("../inc/conn.php");
?>
<? $tss="全部信息";
if($_GET['rq1']<>''){
    $d1 = $_GET['rq1'];
}
if($_GET['rq2']<>''){
    $d2 = $_GET['rq2'];
}
if($_POST['rq1']<>'' || $_POST['rq2']<>''){
    $d1=$_POST["rq1"];if ($d1=="") {$d1=date("Y-m-")."01";$ss="";$tss="全部信息";}
    $d2=$_POST["rq2"];if ($d2=="") {$d2=date("Y-m-d");}
}

if ($_GET["dq"]<>"") $dq=urldecode($_GET["dq"]); else $dq="%";
$czyid = urldecode($_GET['id']);


$tjddh='';$tjfmfs='';
if($_POST['ddh']<>''){
    $ddh = $_POST['ddh'];
    $tjddh = " and f.ddh = '$ddh' ";
}
if($_POST['fmfs']<>''){
    $fmfs = $_POST['fmfs'];
    $tjfmfs = " and f.fmfs = '$fmfs' ";
}

$sql = "select f.ddh,f.fmczy, f.finishdate,r.xm,r.bh,f.fmfs,f.sl,f.jg from b_ry r,order_mxqt_fm f where locate('$czyid' ,f.fmczy)>0 and f.fmczy = r.bh and f.finishdate>='$d1 00:00:00' and f.finishdate<='$d2 23:59:59' $tjddh $tjfmfs";
$rs = mysql_query($sql,$conn);

if ($_POST["bt2"]) {
    header("Content-type:application/vnd.ms-excel;charset=UTF-8");
    header("Content-Disposition:filename=" . iconv("utf-8", "gb2312", $czyid."操作员统计导出[" . $d1 . "-" . $d2 . "].xls"));
    header("Expires:0");
    header('Pragma:   public');
    header("Cache-control:must-revalidate,post-check=0,pre-check=0");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title>名片工坊-账户使用情况</title>
    <link href="../css/CITICcss.css" rel="stylesheet" type="text/css">
    <script src="../js/WdatePicker.js" type="text/javascript" language="javascript"></script>
    <script type="text/javascript" src="../js/jquery-1.8.3.min.js"></script>
    <style type="text/css">
        .ptb td{
            text-align: center;
            line-height:25px;
        }
        #totalnum{
            display: block;
            height:40px;
            line-height:40px;
            padding-left:10px;
        }
        .detb td,.detb th{
            text-align: center;
            line-height:22px;
            height:24px;
        }
    </style>
</head>

<body style="overflow-x:hidden;overflow-y:auto">
<div>
    <form method="post">

        <div style="padding:10px; font-weight:bold; text-align:right;">
            <input name="bt1" type="submit" value="查 询" onclick="changeje()" />
            <input name="bt2" type="submit" value="导 出" />

        </div>

    <? if( $_SESSION["FBFM"] != 1){
        ?>
        <span id="totalnum">总金额：</span>
    <? } ?>
    <table class="detb"  cellspacing="0" cellpadding="0" rules="all" border="0" id="gvOrder" style="border-width:0px;width:100%;border-collapse:collapse;border:1px solid #D3D3D3; border-bottom:0px;">
        <thead>
        <tr  class="td_title" style="height:30px;">
            <th>姓名</th>
            <th>订单号</th>
            <th>覆膜方式</th>
            <th>数量</th>
            <? if( $_SESSION["FBFM"] != 1){
                ?>
                <th>价格</th>
                <th>覆膜金额</th>
                <? } ?>


            <th>完成时间</th>
        </tr>
        <tr  class="td_title" style="height:30px;">
            <th></th>
            <th><input name="ddh" style="height:29px" placeholder="输入完整订单号" value="<? echo $_POST["ddh"] ?>"/></th>
            <th><select name="fmfs" style="height:29px;"><option value="">覆膜方式</option>
                    <? $rsfm = mysql_query("select DISTINCT fumo from b_fumo",$conn);
                    while($row=mysql_fetch_array($rsfm)){
                        ?>
                        <option value="<? echo $row[0]; ?>" <? if ($fmfs == $row[0]) echo "selected"; ?>><? echo $row[0]; ?></option>
                    <? } ?>

                </select>
            </th>
            <th><input name="slmin" style="height:29px;width:50px;" placeholder="数量下限" value="<? echo $_POST["slmin"] ?>"/>~<input name="slmax" style="height:29px;width:50px;" placeholder="数量上限" value="<? echo $_POST["slmax"] ?>"/></th>
            <th><input name="jgmin" style="height:29px;width:50px;" placeholder="价格下限" value="<? echo $_POST["jgmin"] ?>"/>~<input name="jgmax" style="height:29px;width:50px;" placeholder="价格上限" value="<? echo $_POST["jgmax"] ?>"/></th>
            <th><input name="jemin" style="height:29px;width:50px;" placeholder="金额下限" value="<? echo $_POST["jemin"] ?>"/>~<input name="jemax" style="height:29px;width:50px;" placeholder="金额上限" value="<? echo $_POST["jemax"] ?>"/></th>
            <th><input onClick="WdatePicker();" maxlength="50" class="Wdate" style="width: 100px;" type="text" name="rq1" id="rq1" value="<? echo $d1;?>" />~<input onClick="WdatePicker();" maxlength="50" class="Wdate" style="width: 100px;" type="text" name="rq2" id="rq2" value="<? echo $d2;?>" /></th>
        </tr>
        </thead>
        <tbody>
        <? for($i = 0;$i<mysql_num_rows($rs);$i++){
            if ($_POST['slmax'] <> '' || $_POST['slmin'] <> '') {
                $sl =mysql_result($rs,$i,'sl');
                if ($_POST['slmin'] <> '' && $_POST['slmax'] <> '') {
                    if ($sl > $_POST['slmax'] || $sl < $_POST['slmin'])
                        continue;
                } else if ($_POST['slmin'] <> '') {
                    if ($sl < $_POST['slmin'])
                        continue;
                } else if ($_POST['slmax'] <> '') {
                    if ($sl > $_POST['slmax'])
                        continue;
                }
            }
            if ($_POST['jgmax'] <> '' || $_POST['jgmin'] <> '') {
                $je = mysql_result($rs,$i,'jg');
                if ($_POST['jgmin'] <> '' && $_POST['jgmax'] <> '') {
                    if ($je > $_POST['jgmax'] || $je < $_POST['jgmin'])
                        continue;
                } else if ($_POST['jgmin'] <> '') {
                    if ($je < $_POST['jgmin'])
                        continue;
                } else if ($_POST['jgmax'] <> '') {
                    if ($je > $_POST['jgmax'])
                        continue;
                }
            }
            if ($_POST['jemax'] <> '' || $_POST['jemin'] <> '') {
                $je = mysql_result($rs,$i,'sl')*mysql_result($rs,$i,'jg');
                if ($_POST['jemin'] <> '' && $_POST['jemax'] <> '') {
                    if ($je > $_POST['jemax'] || $je < $_POST['jemin'])
                        continue;
                } else if ($_POST['jemin'] <> '') {
                    if ($je < $_POST['jemin'])
                        continue;
                } else if ($_POST['jemax'] <> '') {
                    if ($je > $_POST['jemax'])
                        continue;
                }
            }

            ?>
            <tr>
                <td><? echo mysql_result($rs,$i,'xm') ?></td>
                <td><? echo mysql_result($rs,$i,'ddh') ?></td>
                <td><? echo mysql_result($rs,$i,'fmfs') ?></td>
                <td><? echo mysql_result($rs,$i,'sl') ?></td>
                <? if( $_SESSION["FBFM"] != 1){
                    ?>

                    <td><? echo mysql_result($rs,$i,'jg') ?></td>
                    <td class="jetd"><? echo mysql_result($rs,$i,'sl')*mysql_result($rs,$i,'jg') ?></td>
                <? } ?>
                <td><? echo mysql_result($rs,$i,'finishdate') ?></td>
            </tr>
        <? } ?>
        </tbody>
    </table>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        changeje();

    });
    function changeje(){
        var jetds = $('.jetd');
        var _l = jetds.length;
        var totalje = 0;
        for(var i =0; i < _l;i++){
            var _everyje = parseFloat(jetds[i].innerHTML);
            totalje += _everyje;
        }
        $('#totalnum').text('覆膜总金额：￥'+totalje.toFixed(2) + '元');
    }

</script>
</body>

</html>
