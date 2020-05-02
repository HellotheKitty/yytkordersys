<? 
header("Content-Type:text/html;charset=UTF-8");
require("../../inc/conn.php");?>
<?

session_start();
if ($_SESSION["OK"]<>"OK") { 
echo "<script language=JavaScript>{window.location.href='error.php';}</script>";
exit; 
}?>
<? if ($_POST["Submit"]<>"查 询") {          //保存数据
if ($_POST["ID"]<>"") {
	mysql_query("delete from base_kh where id=".$_POST["ID"],$conn);
	$id=$_POST["ID"];
	$khmcold=$_POST["KHMCOLD"];
} else $id="0";
$rss=mysql_query("select xsbh from base_kh where khmc='".$_POST["khmc"]."' or mpzh='".$_POST["mpzh"]."'",$conn);
if (mysql_num_rows($rss)>0) {
	echo "<script language=JavaScript>alert('客户名称已经存在，所属销售编号是：".mysql_result($rss,0,0)."，请联系沟通。不能增加重复用户名称！');window.location.href='KH_list.php';</script>";
	exit;
}

mysql_query("insert into base_kh (id,khmc,lxr,lxdh,lxdz,kp_sm,gdzk,memo,mpzh,xsbh,zctime,qq,hyjb,jg,gdzk) values ($id,'".$_POST["khmc"]."','".$_POST["lxr"]."','".$_POST["lxdh"]."','".$_POST["lxdz"]."','".$_POST["kpsm"]."','".$_POST["gdzk"]."','".$_POST["memo"]."','".$_POST["mpzh"]."','".$_SESSION["YKOAUSER"]."',now(),'".$_POST["qq"]."','".$_POST["hyjb"]."','".$_POST["jg"]."','".substr($_SESSION["GDWDM"],0,4)."')"); 
if ($khmcold<>"") {
	mysql_query("update order_mainqt set khmc='".$_POST["khmc"]."' where khmc='$khmcold'",$conn);
}
header("Location:KH_list.php");
}
else {							//查询数据
header("Location:KH_list.php?zdm=".$_POST["khmc"]."&zmc=".$_POST["lxr"]."&gsmc=".$_POST["lxdh"]."&gwmc=".$_POST["lxdz"]."&qq=".$_POST["qq"]);
} 
?>
