<? require("../includes/conn.php");?>
<?
session_start();
if ($_SESSION["OK"]<>"OK") { 
echo "<script language=JavaScript>{window.location.href='../error.php';}</script>";
exit; 
}?>
<?
if ($_GET["BH"]<>"") { 
	mysql_query("delete from order_xj where id='".$_GET["BH"]."'");
	mysql_query("delete from order_xjmx where xjid='".$_GET["BH"]."'");
}
if ($_GET["BH2"]<>"") { 
	mysql_query("update order_xj set state='待".$_GET["bjr"]."报价',sdate=now() where id='".$_GET["BH2"]."'");
}
echo "<script language=JavaScript> {window.open('../MYOrderxj.php', 'main');}</script>";
?>

