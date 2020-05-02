<? require("../inc/conn.php"); 
session_start();
if ($_SESSION["YKOAOK"]<>"OK") { 
echo "<script language=JavaScript>{window.location.href='error.php';}</script>";
exit; }?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
</head>
<?
if ($_POST["khmc"]<>"") {
	if ($_POST["ok"]=="提交") {
	$sqly=$_POST["sqly"];
	mysql_query("update crm_khb set sqycly=concat(now(),'[$sqly];',ifnull(sqycly,'')) where khmc='".$_POST["khmc"]."'",$conn);
	} else {
		if ($_POST["ok"]=="同意") 
			mysql_query("update crm_khb set xsryfpsj=now(),sqycly=concat('同意->',ifnull(sqycly,'')) where khmc='".$_POST["khmc"]."'",$conn);
		else
			mysql_query("update crm_khb set sqycly=concat('不同意->',ifnull(sqycly,'')) where khmc='".$_POST["khmc"]."'",$conn);
	}
	echo "<script>alert('保存完成！');window.opener.location.reload();window.close();
</script>";
exit;
}


if ($_GET["lx"]=="sp") {
	$rs=mysql_query("select sqycly from crm_khb where khmc='".base_decode($_GET["khmc"])."'",$conn);
	$sqly=mysql_result($rs,0,0);
}
else $sqly="";
?>
<body>
<p>单位：<? echo base_decode($_GET["khmc"])?></p>
输入申请延长理由：
<form id="form1" name="form1" method="post" action="">
<input type="hidden" name="khmc" value="<? echo base_decode($_GET["khmc"])?>" />
  <label for="sqly"></label>
    <textarea name="sqly" id="sqly" cols="45" rows="5"><? echo $sqly;?></textarea>
  <br>
<? if ($_GET["lx"]=="") {?>
    <input type="submit" name="ok" id="ok" value="提交" />
<? } else {?>
    <input type="submit" name="ok" id="ok" value="同意" />
    <input type="submit" name="ok" id="ok" value="不同意" />
<? }?>
</form>

</body>
</html>
