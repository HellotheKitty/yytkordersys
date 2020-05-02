<? session_start();
require("../../inc/conn.php");//require("../../OAfile/SendSMS.php");?>
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7"><title>
	</title></head>
<?

$id=$_POST["id"];
$ddh=$_POST["ddh"];
$productname=$_POST["productname"];
$chicun=$_POST["chicun"];
$sl=$_POST["sl"];
$fileName=$_POST["file1"];
//$fileName=str_replace(".","",substr($fileName,0,-4)).substr($fileName,-4,4);
$fileName=str_replace(" ","",$fileName);
$fileName=str_replace("(","",$fileName);
$fileName=str_replace(")","",$fileName);
//$fileName=str_replace(";","",$fileName);
//$fileName = 'http://192.168.1.71:99/'.$fileName;
$zfile=$fileName;
$fileName=$_POST["file2"];

//$fileName=str_replace(".","",substr($fileName,0,-4)).substr($fileName,-4,4);
$fileName=str_replace(" ","",$fileName);
$fileName=str_replace("(","",$fileName);
$fileName=str_replace(")","",$fileName);
//$fileName=str_replace(";","",$fileName);
//$fileName = 'http://192.168.1.71:99/'.$fileName;
$gfile=$fileName;
if(strpos($_POST["machine1"], "彩色")) $color1 = "彩色";
if(strpos($_POST["machine1"], "三色")) $color1 = "三色";
if(strpos($_POST["machine1"], "黑白")) $color1 = "黑白";
if(strpos($_POST["machine2"], "彩色")) $color2 = "彩色";
if(strpos($_POST["machine2"], "三色")) $color2 = "三色";
if(strpos($_POST["machine2"], "黑白")) $color2 = "黑白";
if ($id=="") {
	mysql_query("insert into order_mxqt (id,ddh,productname,pname,chicun,sl,n1,file1,machine1,paper1,color1,jldw1,dsm1,hzx1,pnum1,sl1,jg1,n2,file2,machine2,paper2,color2,jldw2,dsm2,hzx2,pnum2,sl2,jg2) values (0,'$ddh','$productname','".$_POST["pname"]."','$chicun','$sl','".$_POST["n1"]."','".$zfile."','".$_POST["machine1"]."','".$_POST["paper1"]."','".$color1."','".$_POST["jldw1"]."','".$_POST["dsm1"]."','".$_POST["hzx1"]."','".$_POST["pnum1"]."','".$_POST["sl1"]."','".$_POST["jg1"]."','".$_POST["n2"]."','".$gfile."','".$_POST["machine2"]."','".$_POST["paper2"]."','".$color2."','".$_POST["jldw2"]."','".$_POST["dsm2"]."','".$_POST["hzx2"]."','".$_POST["pnum2"]."','".$_POST["sl2"]."','".$_POST["jg2"]."')",$conn);
	$id=mysql_result(mysql_query("select last_insert_id()"),0,0);
} else {   //有id 明细修改
	mysql_query("update order_mxqt set chicun='$chicun',pname='".$_POST["pname"]."',sl='$sl',n1='".$_POST["n1"]."',file1='".$zfile."',machine1='".$_POST["machine1"]."',paper1='".$_POST["paper1"]."',color1='".$color1."',jldw1='".$_POST["jldw1"]."',dsm1='".$_POST["dsm1"]."',hzx1='".$_POST["hzx1"]."',pnum1='".$_POST["pnum1"]."',sl1='".$_POST["sl1"]."',jg1='".$_POST["jg1"]."',n2='".$_POST["n2"]."',file2='".$gfile."',machine2='".$_POST["machine2"]."',paper2='".$_POST["paper2"]."',color2='".$color2."',jldw2='".$_POST["jldw2"]."',dsm2='".$_POST["dsm2"]."',hzx2='".$_POST["hzx2"]."',pnum2='".$_POST["pnum2"]."',sl2='".$_POST["sl2"]."',jg2='".$_POST["jg2"]."' where id=$id",$conn);


}
$rs=mysql_query("select sum(jg1*pnum1*sl1+jg2*pnum2*sl2) from order_mxqt mx where mx.ddh='$ddh'",$conn);
$rshd=mysql_query("select ifnull(sum(jg*sl),0) from order_mxqt_hd where mxid in (select id from order_mxqt where ddh='$ddh')",$conn);
$rsfm=mysql_query("select ifnull(sum(jg*sl),0) from order_mxqt_fm where mxid in (select id from order_mxqt where ddh='$ddh')",$conn);

if ($_SESSION["FBCW"]=="1") {
	mysql_query("update order_mainqt set dje=".(mysql_result($rs,0,0)+mysql_result($rshd,0,0)+mysql_result($rsfm,0,0)).",memo=concat(ifnull(memo,''),'财务调整".$_SESSION["SSUSER"]."',now()) where ddh='$ddh'",$conn);
} else {
	mysql_query("update order_mainqt set dje=".(mysql_result($rs,0,0)+mysql_result($rshd,0,0)+mysql_result($rsfm,0,0))." where ddh='$ddh'",$conn);
}
//调整账务系统
//mysql_query("update order_zh set df=(select dje+ifnull(kdje,0) from order_mainqt where ddh='$ddh') where ddh='$ddh'",$conn);

echo "<script>window.location.href='YSXMqt_mxdjs.php?ddh=$ddh&mxsid=$id';</script>";

?>

