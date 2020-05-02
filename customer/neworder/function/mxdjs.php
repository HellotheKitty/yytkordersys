<? require("../../inc/conn.php");?>
<? session_start();
if ($_SESSION["CUSTOMER"]<>"OK") { 
echo "<script language=JavaScript>{window.location.href='../error.php';}</script>";
exit; 
}?>
<?
$dwdm = substr($_SESSION["GDWDM"],0,4);
if ($_GET["deleid"]<>"") {
	$ddh=$_GET["ddh"];
	mysql_query("delete from order_mxqt_hd where id='".$_GET["deleid"]."'",$conn);
	$rs=mysql_query("select sum(jg1*pnum1*sl1+jg2*pnum2*sl2) from order_mxqt mx where mx.ddh='$ddh'",$conn);
	$rshd=mysql_query("select sum(jg*sl) from order_mxqt_hd where mxid in (select id from order_mxqt where ddh='$ddh')",$conn);
	mysql_query("update order_mainqt set dje=".(mysql_result($rs,0,0)+mysql_result($rshd,0,0))." where ddh='$ddh'",$conn);
	header("location:YSXMqt_mxdjs.php?ddh=".$ddh."&mxsid=".$_GET["mxsid"]);
	exit;
}


$bh=$_GET["ddh"];$state="新建";
if ($_GET["lx"]=="show") $state="查看";
$staters = mysql_query("select state from order_mainqt where ddh='".$bh."'",$conn);
$orderstate = mysql_result($staters,0,"state");
if($orderstate == '待配送' or $orderstate == '订单完成')
	$state="查看";
else
	$state="新建";
$rsjg=mysql_query("select jg from base_kh,order_mainqt qt where qt.khmc=base_kh.khmc and qt.ddh='$bh'");
if (mysql_num_rows($rsjg)>0) $jg=mysql_result($rsjg,0,0); else $jg=0.0;
$dj=0;$n1="";$n2="";
if ($_GET["pn"]<>"" ) {
	$pn=$_GET["pn"];
	$rs1=mysql_query("select * from printclasscomponent where printclass='".$_GET["pn"]."'",$conn);
	if (mysql_num_rows($rs1)==1) {$n1=mysql_result($rs1,0,2);}
	if (mysql_num_rows($rs1)==2) {$n1=mysql_result($rs1,0,2);$n2=mysql_result($rs1,1,2);}
}
if ($_GET["mxsid"]<>"" ) {
	$id=$_GET["mxsid"];
	$rs=mysql_query("select * from order_mxqt where id=".$_GET["mxsid"]);
	$pn=mysql_result($rs,0,"productname");
	$pname=mysql_result($rs,0,"pname");
	$sl=mysql_result($rs,0,"sl");$chicun=mysql_result($rs,0,"chicun");
	$n1=mysql_result($rs,0,"n1");$n2=mysql_result($rs,0,"n2");
	$file1=mysql_result($rs,0,"file1");$file2=mysql_result($rs,0,"file2");
	$machine1=mysql_result($rs,0,"machine1");$machine2=mysql_result($rs,0,"machine2");
	$paper1=mysql_result($rs,0,"paper1");$paper2=mysql_result($rs,0,"paper2");
	$jldw1=mysql_result($rs,0,"jldw1");$jldw2=mysql_result($rs,0,"jldw2");
	$dsm1=mysql_result($rs,0,"dsm1");$dsm2=mysql_result($rs,0,"dsm2");
	$hzx1=mysql_result($rs,0,"hzx1");$hzx2=mysql_result($rs,0,"hzx2");
	$pnum1=mysql_result($rs,0,"pnum1");$pnum2=mysql_result($rs,0,"pnum2");
	$sl1=mysql_result($rs,0,"sl1");$sl2=mysql_result($rs,0,"sl2");
	$jg1=mysql_result($rs,0,"jg1");$jg2=mysql_result($rs,0,"jg2");
}
?>