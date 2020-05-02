<? session_start();
require("../inc/conn.php");

if ($_POST["ID"]<>"") {
	if ($_POST["lx"]=="8") {
		if ($_POST["vv"]=="true")
			mysql_query("update task_type set needHL=1 where id=".$_POST["ID"],$conn);	
		else
			mysql_query("update task_type set needHL=0 where id=".$_POST["ID"],$conn);	
	} elseif($_POST["lx"]=="91") {
			mysql_query("update task_type set taskryxm='".$_POST["vv"]."' where id=".$_POST["ID"],$conn);
	} elseif($_POST["lx"]=="92") {
			mysql_query("update task_type set taskrecvxm='".$_POST["vv"]."' where id=".$_POST["ID"],$conn);	
	}
	echo "操作成功";
	exit;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title>任务管理</title>
    <link href="../css/CITICcss.css" rel="stylesheet" type="text/css">
</head>
<? 
if ($_GET["uu"]<>"") {
	if ($_GET["cks"]==md5("hzyk".$_GET["uu"]."winner")) {  //验证通过
	session_start();
	$_SESSION["OK"] = "OK";
	}
}
$dwdm = substr($_SESSION["GDWDM"],0,4);
?>
<? 
$rs=mysql_query("select * from task_type where zzfy='$dwdm' order by tasktype",$conn);
	   
?>
<script language="javascript">
 function modiit(b1,b2,b3) {
 			var xmlHttpReq;
            if (typeof (XMLHttpRequest) != "undefined")
                xmlHttpReq = new XMLHttpRequest();
            else if (window.ActiveXObject)
                xmlHttpReq = new ActiveXObject("MSXML2.XMLHTTP.3.0");
            xmlHttpReq.open("POST", "YKKF_taskman.php?jid=" + Math.round(Math.random() * 10000), false);
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
			<th width="69" align="center" scope="col">编号</th>
			<th  align="center" scope="col">名称</th>
			<th  align="center" scope="col">执行时长</th>
			<th  align="center" scope="col">任务指向</th>
			<th  align="center" scope="col">备注</th>
			<th  align="center" scope="col">执行人员</th>
			<th  align="center" scope="col">后备人员</th>
			<th  align="center" scope="col">高级</th>
		</tr>
        <? 
		while ($row=mysql_fetch_row($rs)) {
		?>
        <tr onMouseOver="this.style.backgroundColor='#FFD'" onMouseOut="this.style.backgroundColor='white'">
            <td class="td_content" style="text-align:center"><? echo $row[1]?></td>
			<td  class="td_content" style="text-align:left"><? echo $row[2]?></td>
			<td  class="td_content" style="text-align:center"><? echo $row[3]?></td>
			<td  class="td_content" style="text-align:left"><? echo $row[4];?></td>
			<td  class="td_content" style="text-align:left"><? echo $row[5];?></td>
			<td  class="td_content" style="text-align:left">
			<input name="zx" type="text" onchange="modiit('<? echo $row[0];?>',this.value,'91')" value="<? echo $row[6];?>" size="50" style="width:90%" />
            </td>
            <td  class="td_content" style="text-align:left">
			<input name="hb" type="text" onchange="modiit('<? echo $row[0];?>',this.value,'92')" value="<? echo $row[7];?>" size="30" style="width:90%" />
            </td>
            <td class="td_content" style="text-align:center">
              <input name="zb" type="checkbox" id="zb" value="1" <? if ($row[8]==1) echo "checked";?> onchange="modiit('<? echo $row[0];?>',this.checked,'8')" />
            
          </td>

		</tr>
        <? }?>
	</tbody></table>
    注意：人员姓名不能有错误，人员之间用分号隔开。
</form>
</body></html>
