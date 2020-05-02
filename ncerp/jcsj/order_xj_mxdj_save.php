<? session_start();
require("../includes/conn.php");require_once("img2thumb.php");?>
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7">
<title></title>
</head>
<? 
$uploaddir= '../files/';//设置上传的文件夹地址 
$MAX_SIZE = 50000000;//设置文件上传文件20000000byte=2M 
$FILES_NAME=$_FILES['gfile']['name']; 
$_FILES['gfile']['name']=$_SESSION["USERBH"]."-".$_FILES['gfile']['name']; 
$uploadfile = $uploaddir. $_FILES['gfile']['name'];//上传后文件的路径及文件名 
if ($FILES_NAME<>'' and $_POST["ffile"]!=$uploadfile) {
	move_uploaded_file($_FILES['gfile']['tmp_name'], iconv('utf-8','gb2312',$uploadfile)); 
} else {
	$uploadfile ="";
}

$khmc=$_POST["khmc"];
$cpms=$_POST["cpms"];
$chicun=$_POST["chicun"];
$chicun2=$_POST["chicun2"];
$sl=$_POST["sl"];
$zhongliang=$_POST["zhongliang"];
$sfdy=$_POST["sfdy"];if ($sfdy=="") $sfdy="0";
$zz=$_POST["zz"];
$tsgy=$_POST["tsgy"];
$scfs=$_POST["scfs"];
$memo=$_POST["memo"];
$xjid=$_POST["id"];

if ($_POST["mxid"]=="") {  //NEW
	mysql_query("delete from order_xjmx where xjid={$xjid}",$conn);  //清除旧数据
	mysql_query("insert into order_xjmx values (0,$xjid,'$khmc','$cpms','$chicun','$chicun2','$sl','$zhongliang','$zz','$tsgy',$sfdy,'$scfs','$uploadfile','$memo')",$conn);
} else  {
	if ($uploadfile<>"") 
		mysql_query("update order_xjmx set cpms='$cpms',chicun='$chicun',chicun2='$chicun2',sl='$sl',zhongliang='$zhongliang',zz='$zz',tsgy='$tsgy',sfdy=$sfdy,scfs='$scfs',file='$uploadfile',memo='$memo' where id=".$_POST["mxid"],$conn);
	else
		mysql_query("update order_xjmx set cpms='$cpms',chicun='$chicun',chicun2='$chicun2',sl='$sl',zhongliang='$zhongliang',zz='$zz',tsgy='$tsgy',sfdy=$sfdy,scfs='$scfs',memo='$memo' where id=".$_POST["mxid"],$conn);
}
echo "<script>window.opener.location.reload();window.close();</script>";

?> 