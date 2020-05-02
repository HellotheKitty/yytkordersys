<? require("../includes/conn.php");?>
<?
session_start();
if ($_SESSION["OK"]<>"OK") { 
echo "<script language=JavaScript>{window.location.href='error.php';}</script>";
exit; 
}?>
<? 
if ($_GET["xjid"]=="") {
$rs=mysql_query("select ddh from order_mainqt where ddh='".$_POST["bh"]."'",$conn);
if (mysql_num_rows($rs)>0) { 
echo "<script language=JavaScript> {alert('订单号重复，请检查！');window.history.go(-1);}</script>"; exit; }
else {
mysql_query("insert into order_mainqt (ddh,xjdid,khmc,memo,xsbh,ddate,state,sdate,kpyq,bzyq,dje,kdje,scjd) values ('".$_POST["bh"]."','".$_POST["xjdbh"]."','".$_POST["mc"]."','".$_POST["sm"]."','".$_POST["xsbh"]."',now(),'新建订单',now(),'".$_POST["kpyq"]."','".$_POST["bzyq"]."',0,0,'".$_POST["scjd"]."')",$conn); 
}
echo "<script language=JavaScript> {window.opener.location.reload();window.close();}</script>"; 
} else {  //自动转
	$bh=date("ymdhis",time()).rand(10,99)."8";
	mysql_query("update order_xj set memo=concat(ifnull(memo,''),'【已转订单】') where id=".$_GET["xjid"],$conn);  //标识
	$rs=mysql_query("select * from order_xj where id=".$_GET["xjid"],$conn);
	mysql_query("insert into order_mainqt (ddh,xjdid,khmc,memo,xsbh,ddate,state,sdate,kpyq,bzyq,dje,psfs,scjd) values ('$bh','".mysql_result($rs,0,"id")."','".mysql_result($rs,0,"khmc")."','".mysql_result($rs,0,"memo")."','".mysql_result($rs,0,"xsbh")."',now(),'新建订单',now(),'".(mysql_result($rs,0,"sffp")=="1"?"开票":"不开票")."','".mysql_result($rs,0,"bzyq")."',".mysql_result($rs,0,"zje").",'".mysql_result($rs,0,"psyq")."','".(mysql_result($rs,0,"state")==""?"":substr(mysql_result($rs,0,"state"),3,6))."')",$conn);
 	mysql_query("insert into order_mxqt select 0,'$bh','',cpms,chicun,sl,zz,tsgy,sfdy,scfs,file,order_xjmx.memo,1,jyjg,order_xjmxbj.psfs,order_xjmxbj.minjg,null from order_xjmx,order_xjmxbj where order_xjmx.id=xjmxid and xjid=".$_GET["xjid"],$conn);
	echo "<script language=JavaScript> {alert('转入完成，请检查。订单号：".$bh."');window.location.href='../MYOrderShowqt.php';}</script>"; 
}
?>

