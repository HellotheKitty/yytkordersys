<? require("../includes/conn.php");?>
<?
session_start();
if ($_SESSION["OK"]<>"OK") { 
echo "<script language=JavaScript>{window.location.href='error.php';}</script>";
exit; 
}?>
<? 
$rs=mysql_query("select ddh from order_main where ddh='".$_POST["bh"]."'",$conn);
if (mysql_num_rows($rs)>0) { 
echo "<script language=JavaScript> {alert('订单号重复，请检查！');window.history.go(-1);}</script>"; exit; }
else {
mysql_query("insert into order_main (ddh,id,bip,user,ddate,state,sdate,psfs,kdje) values ('".$_POST["bh"]."',0,'".$_POST["mc"]."','".substr($_POST["bm"],0,strpos($_POST["bm"],"-"))."',now(),'新建订单',now(),'自取/送货上门',0)",$conn); 
}
echo "<script language=JavaScript> {window.close();window.open('../MYOrderShow.php?lx=nb', 'main');}</script>"; 
?>

