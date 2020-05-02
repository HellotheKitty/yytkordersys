<?
header("Content-Type:text/html;charset=UTF-8");
require("../../inc/conn.php");
session_start();
if($_SESSION["OK"]<>"OK"){
	echo "<script language=JavaScript>{window.location.href='error.php';}</script>";
	exit;
}

if($id = $_GET["id"]){
	$rs = mysql_query("select * from b_ry where id = '".$id."'");
	$empInfo = mysql_fetch_array($rs,MYSQL_ASSOC);
	echo "<form name='infoForm' id='infoForm'>";
	foreach($empInfo as $key => $val)
		echo $key."：<input type=text name='".$key."' value='".$val."'/><br>";
	echo "</form>";
	?>
	<table>
		<tr>
			<td colspan="1">个人信息</td>
		</tr>
		<tr>
			<td>编号<input type="text" value="hife"/>姓名<input type="text" value="测试时"/></td>
		</tr>
<?
}else{
	exit;
}
