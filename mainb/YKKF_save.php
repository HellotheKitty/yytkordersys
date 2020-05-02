<? 
session_start();
require("../inc/conn.php");
header("Content-Type:text/html;charset=UTF-8"); 
					//$fp = fopen("test.txt", "a");
					//fwrite($fp,$_POST["command"]."\r\n"); 
					//fwrite($fp,$_POST["id"]."\r\n"); 
					//fclose($fp);
switch ($_POST["command"]) {
	case "begin":
		mysql_query("update task_list set taskstate='处理中',statetime=now(),taskbegintime=now() where id='".$_POST["id"]."'",$conn);
		echo "OK";
		break;
	case "open":
		mysql_query("update task_list set taskstate='处理中',statetime=now() where id='".$_POST["id"]."'",$conn);
		mysql_query("update task_suspend set susetime=now() where taskid='".$_POST["id"]."' and susetime is null",$conn);
		//echo "OK";
		break;
	case "end":
		mysql_query("update task_list set taskstate='已完成',statetime=now(),taskbegintime=ifnull(taskbegintime,now()),taskendtime=now() where id='".$_POST["id"]."'",$conn);
		mysql_query("update task_suspend set susetime=now() where taskid='".$_POST["id"]."' and susetime is null",$conn);
		echo "OK";
		break;
}
?>
