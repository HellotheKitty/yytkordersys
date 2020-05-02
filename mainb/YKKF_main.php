<?php
session_start();
header("P3P:CP=CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR");
require("../inc/conn.php");//

if ($_GET["lx"]=="out") {
	$_SESSION["YKOAUSER"]="";
	echo "<script>alert('注销成功！');</script>";
	exit;
}
if ($_GET["uu"]<>"") {
	if ($_GET["cks"]==md5("hzyk".$_GET["uu"]."winner")) {  //验证通过
	session_start();
	$rs=mysql_query("select b.xm,bh from b_ry b where b.bh='".$_GET["uu"]."'",$conn);
	if (mysql_num_rows($rs)==0) {echo "用户定义错误，请检查：",$_GET["uu"];exit;}
			$_SESSION["YKOAUSER"] = $_GET["uu"];
			$_SESSION["YKUSERNAME"] = mysql_result($rs,0,"xm");
			$_SESSION["YKUSERZZBH"] = mysql_result($rs,0,"bh");
			$_SESSION["ZZUSER"] = mysql_result($rs,0,"bh");
			$_SESSION["KFUSER"] = mysql_result($rs,0,"bh");
			$_SESSION["OK"] = "OK";
			$_SESSION["YKOAOK"]="OK";
            $_SESSION['OPERATOR'] = 'DESIGN';
			print "<script language=JavaScript>{ window.location.href='YKKF_main.php';}</script>";
			exit;
	}
} 
if ($_SESSION["YKOAUSER"]=="") {
	echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
	echo $_COOKIE["YKOAUSER"];
	echo "用户错误，请重新登录OA！";
	exit;
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
<title>OA - 易卡工坊</title>
</head>

	<frameset COLS="202, *" ID=resize>
		<frame noresize name="cmenu" scrolling="yes" src="YKKF_tasklist_s.php"></frame>
		<frame name="cmain" scrolling="yes" src="../home.php"></frame>
	</frameset>
	<noframes></noframes>

</html>
