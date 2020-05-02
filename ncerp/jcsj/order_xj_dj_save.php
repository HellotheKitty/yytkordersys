<? require("../includes/conn.php");?>
<?
session_start();
if ($_SESSION["OK"]<>"OK") { 
echo "<script language=JavaScript>{window.location.href='error.php';}</script>";
exit; 
}?>
<? 
if ($_GET["xjid"]=="") {
$uploaddir= '../files/';//设置上传的文件夹地址 
$MAX_SIZE = 900000000;//设置文件上传文件20000000byte=2M 

if ($_FILES['fjfile']['name']<>'') {

$_FILES['fjfile']['name']=$_SESSION["USERBH"]."-".$_FILES['fjfile']['name']; 

$uploadfile = $uploaddir. $_FILES['fjfile']['name'];//上传后文件的路径及文件名 
//$uploadfile = iconv('utf-8','gb2312',$uploadfile);
move_uploaded_file($_FILES['fjfile']['tmp_name'], iconv('utf-8','gb2312',$uploadfile)); 
} else {
$uploadfile="";
}
$prod=$_POST["prod"];
$fp=$_POST["fp"];if ($fp=="") $fp="0";
$bzyq=$_POST["bzyq"];
$psyq=$_POST["psyq"];
$memo=$_POST["memo"];
$khmc=$_POST["khmc"];
mysql_query("insert into order_xj values (0,'".$_SESSION["USERBH"]."',now(),'$prod',$fp,'$bzyq','$psyq','$memo','$uploadfile','新建询价单',now(),null,null,'$khmc')",$conn);

echo "<script language=JavaScript> {window.opener.location.reload();window.close();}</script>"; 

} else {  //克隆
	$id=$_GET["xjid"];
	mysql_query("insert into order_xj select 0,xsbh,now(),product,sffp,bzyq,psyq,memo,fjfile,'新建询价单',now(),null,yl2,khmc from order_xj where id=$id",$conn);
	$rs=mysql_query("SELECT LAST_INSERT_ID()",$conn);
 	mysql_query("insert into order_xjmx select 0,".mysql_result($rs,0,0).",'',cpms,chicun,chicun2,sl,zhongliang,zz,tsgy,sfdy,scfs,file,memo from order_xjmx where xjid=$id",$conn);
	echo "<script language=JavaScript> {alert('克隆完成，请检查。');window.location.href='../MYOrderxj.php';}</script>"; 
}

?>

