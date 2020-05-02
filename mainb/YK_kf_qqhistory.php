<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title>易卡工坊-聊天记录</title>
    <link href="./css/CITICcss.css" rel="stylesheet" type="text/css">
</head>

<? require("../inc/conn.php");
  		$zh=base_decode($_GET["zh"]);
		if ($_POST["QQmemo"]<>"") {
			mysql_query("update base_kh set qqmemo='".$_POST["QQmemo"]."' where mpzh='$zh'",$conn);
			echo "<script>alert('保存完成！');</script>";
		}
		$rs=mysql_query("select qqmemo,qq from base_kh where mpzh='$zh'",$conn);
 ?>
<body style="overflow-x:hidden;overflow-y:auto; text-align:center; margin-top:0px">
<form name=form1 action="YK_kf_qqhistory.php?zh=<? echo base_encode($zh);?>" method="post">
<div style="margin-left:10px; margin-top:10px; margin-right:10px"> 
  QQ：<? echo mysql_result($rs,0,1);?><br><textarea name="QQmemo" cols="100"  rows="37"><? echo mysql_result($rs,0,0);?></textarea>
<br><br>
        <input name="sms" type="submit" value="保 存" >
  <br>
        
</div>
</form>
</body>
</html>
