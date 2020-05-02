<? 
session_start();
require("../inc/conn.php");
if ($_SESSION["OK"]<>"OK") { 
echo "<script language=JavaScript>{window.location.href='error.php';}</script>";
exit; }?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    
    <title>名片工坊-个人任务情况</title>
    <link href="../css/CITICcss.css" rel="stylesheet" type="text/css">
	<script src="../js/WdatePicker.js" type="text/javascript" language="javascript"></script>
<base target="_self" />
</head>
<?
$d1=$_POST["rq1"];if ($d1=="") {$d1=date("Y-m-d",strtotime("-1 day"));$ss="";$tss="客服个人任务列表";}
$d2=$_POST["rq2"];if ($d2=="") {$d2=date("Y-m-d");}
if ($_GET["id"]<>"") $id=$_GET["id"];
if ($_POST["id"]<>"") $id=$_POST["id"];
$rs=mysql_query("select l.*,t.taskname from task_list l,task_type t where t.tasktype=l.tasktype and l.taskrecver='$id' and taskrecvtime>='$d1 00:00:00' and taskrecvtime<='$d2 23:59:59' order by taskrecvtime",$conn);

?>

<body style="overflow-x:hidden;overflow-y:auto">
<form name="form1" method="post" action="" id="form1">

<div class="main_box">

<table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:0px;">
  <tbody><tr>
    <td valign="top">
<div style="padding:5px 32px 22px 35px; color:#58595B">
 <div style="padding-bottom:10px; font-weight:bold;"></div>
  <div>开始日期：
    <input onClick="WdatePicker();" maxlength="50" class="Wdate" style="width: 100px;" type="text" name="rq1" id="rq1" value="<? echo $d1;?>" />
  &nbsp;&nbsp;&nbsp;&nbsp;结束日期：<input onClick="WdatePicker();" maxlength="50" class="Wdate" style="width: 100px;" type="text" name="rq2" id="rq2" value="<? echo $d2;?>" />&nbsp;
  <input type="hidden" name="id" value="<? echo $id?>" />
  <input name="bt1" type="submit" value="查 询" />
  </div>
	        <div class="page">
<div id="AspNetPager2" style="width:100%;text-align:right;">
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tbody><tr>
			<td width="27%" align="left" valign="bottom" nowrap="true" style="width:40%;"><? echo $tss;?></td><td width="73%" align="right" valign="bottom" nowrap="true" class="" style="width:60%;">			  　　　　</td>
		</tr>
	</tbody></table>
</div>

		    </div>
                    <div>
	<table cellspacing="0" cellpadding="0" rules="all" border="0" id="gvOrder" style="border-width:0px;width:100%;border-collapse:collapse;border:1px solid #D3D3D3; border-bottom:0px;">
		<tbody><tr class="td_title" style="height:30px;">
			<th align="center" scope="col">任务创建</th>
			<th align="center" scope="col">类型</th>
			<th align="center" scope="col">任务所属</th>
			<th align="center" scope="col">任务备注</th>
			<th align="center" scope="col">任务状态</th>
			<th align="center" scope="col">任务接收</th>
			<th align="center" scope="col">开始处理</th>
			<th align="center" scope="col">结束时间</th>
			<th align="center" scope="col">任务描述</th>
		</tr>
        <? $jhj=0;$dhj=0; 
		for($i=0;$i<mysql_num_rows($rs);$i++){  ?>
        <tr bgcolor="<? echo mysql_result($rs,$i,"taskstate")=="已完成"?"":"#CCBBFF";?>">
			<td align="center" class="td_content" style="width:77px;"><? echo mysql_result($rs,$i,"taskcreatetime");?></td>
            <td align="center" class="td_content" style="width:77px;"><? echo mysql_result($rs,$i,"taskname");?></td>
            <td class="td_content" align="center" style="width:80px;"><? echo mysql_result($rs,$i,"fromuser"),mysql_result($rs,$i,"fromorder");?></td>
            <td align="center" class="td_content" ><? echo mysql_result($rs,$i,"taskmemo");?></td>
            <td align="center" class="td_content" style="width:63px;" title="<? echo mysql_result($rs,$i,"statetime");?>"><? echo mysql_result($rs,$i,"taskstate");?></td>
            <td align="center" class="td_content" style="width:63px;" title="<? echo mysql_result($rs,$i,"taskrecvtime");?>"><? echo mysql_result($rs,$i,"taskrecver");?></td>
            <td align="center" class="td_content" style="width:63px;"><? echo mysql_result($rs,$i,"taskbegintime");?></td>
            <td align="center" class="td_content" style="width:63px;"><? echo mysql_result($rs,$i,"taskendtime");?></td>
            <td align="center" class="td_content" style="width:63px;"><? echo mysql_result($rs,$i,"taskdescribe");?></td>
		</tr>
        <? $jhj=$jhj+1;if (mysql_result($rs,$i,"taskendtime")=="") $dhj=$dhj+1;
		}?>
        <tr>
			<td colspan="2" align="center" class="td_content" style="width:77px;">合计：</td>
            <td colspan="7" align="left" class="td_content" style="width:80px; text-align:left"><? echo '任务总数：',$jhj,'，未完成：',$dhj;?><br></td>
            </tr>
	</tbody></table>
</div>
</div>
 </td>
  </tr>
</tbody></table>
    
</div>

</div>
</form>

</body></html>
