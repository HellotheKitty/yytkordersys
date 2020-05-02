<? session_start();
require("../inc/conn.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script src="../js/jquery-1.8.3.min.js" type="text/javascript"></script>
<title></title>
</head>
<style>
	#navigation {
		height: 50px;
		width: 100%;
		text-align: center;
		background: #2aa439;
	}
	#navigation a.back {
		position: absolute;
		top: 15px;
		left: 15px;
		width: 50px;
		background: none;
		color: #fff;
		font-size: 16px;
	}
	h3 {
		width: 180px;
		height: 50px;
		line-height: 50px;
		margin: 0 auto;
		color: #fff;
		font-size: 20px;
	}
	.text_line {
		height: 30px;
	}
	.but7 {
		border-radius: 5px; cursor:pointer; width:40px; height:25px; text-align: center; color:#fff; border:1px solid #2aa439; background: #288439; -webkit-appearance: none; font-size: 13px;
	}
	.but8 {
		border-radius: 5px; cursor:pointer; width:40px; height:25px; text-align: center; color:#fff; border:1px solid #2aa439; background: #bbbbbb; -webkit-appearance: none; font-size: 13px;
	}
	.motop {
		margin-top: 5px;
	}
	.titleID {
		height: 25px;
		width: 100%;
		line-height: 25px;
		text-align: left;
		background: #eee;
		color: #666;
		font-size: 14px;
	}
	#orderList {
		border: 1px solid #eaeaea;
		border-radius: 5px;
		margin-top: 10px;
	}
	.content {
		font-size:12px;
	}
	.orderOption {
		height: 30px;
		line-height: 30px;
		margin: 10px 0px 15px 30px;
		text-align:left
	}
</style>
<body>
<? if ($_POST["id"]<>"") {
	if ($_POST["sr"]=="其他原因") $ss="其他原因:".$_POST["res"]; else $ss=$_POST["sr"];
	mysql_query("update task_list set taskstate='挂起',statetime=now(),taskmemo=concat(ifnull(taskmemo,''),'".$ss."') where id=".$_POST["id"],$conn);
	mysql_query("insert into task_suspend values (".$_POST["id"].",now(),null,'$ss')",$conn);
	echo "<div class=h3>任务挂起成功！</div>";
	exit;
}
?>
<div style="font-size:14px;background: #eee;line-height: 25px;">&nbsp;任务挂起</div>
<form name="form1" method=post>
<input type="hidden" name="id" value="<? echo $_GET["id"]?>" />
<div id="orderList">
  <div class="titleID">&nbsp;<? echo $_GET["task"];?></div>
        <div class="content">
        &nbsp;&nbsp;<input name="sr" type="radio" value="用户暂时联系不上" checked="checked" />用户暂时联系不上
        <br>&nbsp;&nbsp;<input name="sr" type="radio" value="等待用户提供内容" />等待用户提供内容
        <br>&nbsp;&nbsp;<input name="sr" type="radio" value="等待用户确认" />
        等待用户确认
        <br>&nbsp;&nbsp;<input name="sr" type="radio" value="其他原因" />其他原因
        <input type="text" name="res" id="res" />
        </div>
        <div class="orderOption">
           	
        	<input type="submit" class="but7" value="确定" > 
        	 
        	</div>
        </div>
		
		</div>
</form>
</body>
</html>