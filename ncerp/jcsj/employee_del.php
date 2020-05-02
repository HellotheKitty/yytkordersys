<?
header("Content-Type:text/html;charset=UTF-8");
require("../../inc/conn.php");
session_start();
if($_SESSION["OK"]<>"OK"){
	echo "<script language=JavaScript>{window.location.href='error.php';}</script>";
	exit;
}

if($id = $_GET["ID"]){
	mysql_query("update b_ry set xm = concat('离职',xm) , password = 'lizhi' where id='".$id."'",$conn);
}
	$return_page = "Location:employee_list.php?pno=".$_GET["pno"];
	header($return_page);
