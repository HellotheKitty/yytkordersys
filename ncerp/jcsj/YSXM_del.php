<? require("../includes/conn.php");?>
<?
session_start();
if ($_SESSION["OK"]<>"OK") { 
echo "<script language=JavaScript>{window.location.href='../error.php';}</script>";
exit; 
}?>
<?
if ($_GET["BH"]<>"") { mysql_query("delete from order_main where ddh='".$_GET["BH"]."'");}
if ($_GET["BH2"]<>"") { mysql_query("update order_main set state='待审核',sdate=now() where ddh='".$_GET["BH2"]."'");}
echo "<script language=JavaScript> {window.open('../MYOrderShow.php?lx=nb', 'main');}</script>";
?>

