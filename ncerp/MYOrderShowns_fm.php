<? require("../inc/conn.php");?>
<? session_start();
if ($_SESSION["OK"]<>"OK") {
    echo "<script language=JavaScript>{window.location.href='../error.php';}</script>";
    exit;
}?>

<?
if ($_SESSION["FBFM"]==1)//覆膜
{
//    $rs = mysql_query("select ddh,ddate ,yqwctime from order_mainqt where (order_mainqt.state='进入生产' or order_mainqt.state='生产完成') and not pczy is null and order_mainqt.fumoczy is null  and zzfy='" . substr($_SESSION["GDWDM"], 0, 4) . "' and ddh in (SELECT ddh from order_mxqt_fm)  group by order_mainqt.ddh order by ddate desc", $conn);
    $rs = mysql_query("select fm.ddh,m.ddate ,fm.fmfs,fm.cpcc,fm.sl,fm.memo,fm.fmczy,fm.finishdate from order_mxqt_fm fm left join order_mainqt m on fm.ddh=m.ddh where (m.state='已打印' or m.state='已发货') and fm.fmczy is null  and zzfy='" . substr($_SESSION["GDWDM"], 0, 4) . "'  order by m.ddate desc", $conn);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title></title>
    <SCRIPT language=JavaScript src="../../js/jquery-1.8.3.min.js"></SCRIPT>

    <style type="text/css">
        .tb1 th {
            background-color: #A5CBF7;
        }
        .tb1{
            border-right:1px solid #555;
            border-bottom:1px solid #555;
            width:90%;
        }
        .tb1 th,.tb1 td{
            border-top:1px solid #555555;
            border-left:1px solid #555555;
            width:100px;
        }
        .tb1 td{
            font-size:15px;
        }

    </style>
</head>

<body>
<?
if ($_SESSION["FBFM"]==1)//覆膜
{?>
订单号：<input type="text" class="txt" id="checkNum" name="checkNum" maxlength="15" onkeydown="keyboardEvent(event);" />
<!--<input type="button" value="确定" onclick="search()" /> -->
请扫描<? echo $_SESSION["FBFH"]=="1"?"配送单":"生产单";?>上条码或手动输入完整订单号，按回车提交查询
<br>
<? } ?>
<span id='xxx' style="display:none"></span>
<table class="tb1" cellpadding="0" cellspacing="0">

    <thead>
    <tr>
        <th>订单号</th>
        <th>下单日期</th>
        <th>覆膜方式</th>
        <th>尺寸</th>
        <th>数量</th>
        <th>备注</th>
        <th>覆膜操作员</th>
        <th>覆膜完成时间</th>
        <th>操作</th>
    </tr>

    </thead>
    <tbody>
    <? for($i=0;$i<mysql_num_rows($rs);$i++){ ?>
    <tr>
        <td align="center" class="td_content"  style="width:80px"><? echo mysql_result($rs,$i,'ddh'); ?></td>
        <td align="center" class="td_content"  style="width:80px"><? echo mysql_result($rs,$i,"ddate"); ?></td>
        <td align="center" class="td_content"  style="width:80px"><? echo mysql_result($rs,$i,"fmfs"); ?></td>
        <td align="center" class="td_content"  style="width:80px"><? echo mysql_result($rs,$i,"cpcc"); ?></td>
        <td align="center" class="td_content"  style="width:80px"><? echo mysql_result($rs,$i,"sl"); ?></td>
        <td align="center" class="td_content"  style="width:80px"><? echo mysql_result($rs,$i,"memo"); ?></td>
        <td align="center" class="td_content"  style="width:80px"><? echo mysql_result($rs,$i,"fmczy"); ?></td>
        <td align="center" class="td_content"  style="width:80px"><? echo mysql_result($rs,$i,"finishdate"); ?></td>
        <td><? if($_SESSION['FBFM']==1) {
            echo "<a href='#' onclick=window.location.href='jcsj/YSXMqt_show_p_fm.php?ddh=" . mysql_result($rs, $i, "ddh") . "&getin=ok' >生产单</a>";
        }?></td>

    </tr>
    <? } ?>
    </tbody>
</table>

</body>
<script type="text/javascript">

    function keyboardEvent(event){
        var keyCode=event.keyCode ? event.keyCode:event.which?event.which:event.charCode;//解决浏览器之间的差异问题
        document.getElementById("xxx").innerHTML+=keyCode;
        var allkey=document.getElementById("xxx").innerHTML;
        if((allkey.substr(-4,4)=="9799" && keyCode != 99) || keyCode == 13){

            window.location.href="jcsj/YSXMqt_show_p_fm.php?lx=show&getin=ok&ddh="+document.getElementById("checkNum").value;
        }
    }

    function formsub(){
        document.getElementById("form1").submit();
    }

</script>
</html>

