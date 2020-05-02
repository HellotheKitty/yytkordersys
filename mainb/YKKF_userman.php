<? session_start();
require("../inc/conn.php");

if ($_POST["ID"]<>"") {
	if ($_POST["lx"]=="0") {
		if ($_POST["vv"]=="true")
			mysql_query("update task_kfry set isok=1 where id=".$_POST["ID"],$conn);	
		else
			mysql_query("update task_kfry set isok=0 where id=".$_POST["ID"],$conn);	
	} elseif($_POST["lx"]=="1") {
		if ($_POST["vv"]=="true")
			mysql_query("update task_kfry set isonduty=1 where id=".$_POST["ID"],$conn);	
		else
			mysql_query("update task_kfry set isonduty=0 where id=".$_POST["ID"],$conn);	
	} elseif($_POST["lx"]=="5") {
		if ($_POST["vv"]=="true")
			mysql_query("update task_kfry set isHead=1 where id=".$_POST["ID"],$conn);	
		else
			mysql_query("update task_kfry set isHead=0 where id=".$_POST["ID"],$conn);	
	}
	echo "操作成功";
	exit;
}
if ($_GET["id"]<>"") {  //转移任务
	$id=$_GET["id"];
	$rs=mysql_query("select * from task_list where taskrecver='$id' and taskendtime is null",$conn);
	for ($i=0;$i<mysql_num_rows($rs);$i++) {
		$taskno=mysql_result($rs,$i,"tasktype");
		$user=mysql_result($rs,$i,"fromuser");
		$info = json_decode(file_get_contents("Getkfry.php?tasktype={$taskno}&user={$user}"));
		$zzry=$info->kfry;
		mysql_query("update task_list set taskrecver='$zzry',taskmemo=concat(taskmemo,'/原制作','$id') where id=".mysql_result($rs,$i,"id"),$conn);
	}
	mysql_query("update task_kfry set taskamount=0 where oabh='$id'",$conn);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title>任务人员管理</title>
    <link href="../css/CITICcss.css" rel="stylesheet" type="text/css">
</head>
<? 
if ($_GET["uu"]<>"") {
	if ($_GET["cks"]==md5("hzyk".$_GET["uu"]."winner")) {  //验证通过
	session_start();
	$_SESSION["OK"] = "OK";
	}
}
$dwdm = substr($_SESSION["GDWDM"],0,4);echo $dwdm;
?>
<? 
$rs=mysql_query("select t.*,count(l.id) total from task_kfry t left join task_list l on t.zzfy='$dwdm' and t.oabh=l.taskrecver and l.taskendtime is null  group by t.oabh order by t.groupname,t.oabh",$conn);
	   
?>
<script language="javascript">
 function modiit(b1,b2,b3) {
 			var xmlHttpReq;
            if (typeof (XMLHttpRequest) != "undefined")
                xmlHttpReq = new XMLHttpRequest();
            else if (window.ActiveXObject)
                xmlHttpReq = new ActiveXObject("MSXML2.XMLHTTP.3.0");
            xmlHttpReq.open("POST", "YKKF_userman.php?jid=" + Math.round(Math.random() * 10000), false);
            xmlHttpReq.setRequestHeader("Content-Type","application/x-www-form-urlencoded;charset=UTF-8");
            xmlHttpReq.send("ID=" + b1 + "&vv=" + b2+ "&lx=" + b3);
            if (xmlHttpReq.status == 200) {
                var data = xmlHttpReq.responseText;
				alert(data);     //测试返回数据
                if (data.indexOf("Error") == 0) {
                    alert(data.replace("Error:",""));
                } else {
                    isOk = true;
                }
            }
}
</script>
<body style="overflow-x:hidden;overflow-y:auto;margin:20px">
<form name="form1" method="post" action="" id="form1">
 
<table cellspacing="0" cellpadding="0" rules="all" border="0" id="gvOrder" style="border-width:0px;width:100%;border-collapse:collapse;border:1px solid #D3D3D3; border-bottom:0px;">
		<tbody><tr class="td_title" style="height:30px;">
			<th width="69" align="center" scope="col">OA账号</th>
			<th  align="center" scope="col">姓名</th>
			<th  align="center" scope="col">主管</th>
			<th  align="center" scope="col">日期</th>
			<th  align="center" scope="col">今日-挂起-未完成</th>
			<th  align="center" scope="col">正常上班</th>
			<!--<th  align="center" scope="col">值班</th>-->
			<th  align="center" scope="col">操作</th>
		</tr>
        <? 
		while ($row=mysql_fetch_row($rs)) {
			$rsgq=mysql_query("select count(1) from task_list where taskendtime is null and taskstate='挂起' and taskrecver='".$row[1]."'",$conn);
		?>
        <tr onMouseOver="this.style.backgroundColor='#FFD'" onMouseOut="this.style.backgroundColor='white'">
            <td class="td_content" style="text-align:left"><? echo $row[1]?></td>
			<td  class="td_content" style="text-align:left"><? echo $row[7]?></td>
			<td  class="td_content" style="text-align:center">
			  <input name="zg" type="checkbox" id="zg" value="1" <? if ($row[5]==1) echo "checked";?> onchange="modiit('<? echo $row[0];?>',this.checked,'5')" />
			</td>
			<td  class="td_content" style="text-align:center"><? echo $row[6];?></td>
			<td  class="td_content" style="text-align:center"><? echo $row[3],'-',mysql_result($rsgq,0,0),"-",$row[10]-mysql_result($rsgq,0,0)>0?"<font color=red>".($row[10]-mysql_result($rsgq,0,0))."</font>":0;?>
			   <input type="button"  onclick='window.open("YKKF_persontask.php?id=<? echo $row[1];?>",window,"width=800,height=485,,top=100,left=100,toolbar=no,menubar=no,scrollbars=no, resizable=yes,location=no, status=no");' name="button2" id="button2" value="查看" />
			</td>
			<td  class="td_content" style="text-align:center"><input name="sb" type="checkbox" id="sb" value="1" <? if ($row[2]==1) echo "checked";?> onchange="modiit('<? echo $row[0];?>',this.checked,'0')" /></td>
            <!--<td  class="td_content" style="text-align:center"><input name="zb" type="checkbox" id="zb" value="1" <? if ($row[8]==1) echo "checked";?> onchange="modiit('<? echo $row[0];?>',this.checked,'1')" /></td>-->
            <td class="td_content" style="text-align:left">
&nbsp;<input type="button" onclick="javascrpt:if (confirm('真的要把所有未完成任务转移给其他人吗？不能撤回！')) window.location.href='?id=<? echo $row[1];?>'" name="button" id="button" value="把任务转给其他客服" /></td>

		</tr>
        <? }?>
	</tbody></table>
1、值班有设置的话，正常上班设置不起作用。  <br>
2、主管可以进入本页面维护。
</form>
</body></html>
