<? session_start();
require("../inc/conn.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title>历史任务管理</title>
    <link href="../css/CITICcss.css" rel="stylesheet" type="text/css">
    <style type="text/css">
    .content {		font-size:12px;
}
.content {		font-size:12px;
}
    .but6 {		border-radius: 5px; cursor:pointer; width:35px; height:25px; text-align: center; color:#fff; border:1px solid #333333; background: #333333; -webkit-appearance: none; font-size: 12px;
}
.but6 {		border-radius: 5px; cursor:pointer; width:35px; height:25px; text-align: center; color:#fff; border:1px solid #333333; background: #333333; -webkit-appearance: none; font-size: 12px;
}
.orderOption {		height: 30px;
		line-height: 30px;
		margin: 10px 0px 10px 0px;
		text-align:center
}
.orderOption {		height: 30px;
		line-height: 30px;
		margin: 10px 0px 10px 0px;
		text-align:center
}
    </style>
<script src="../js/WdatePicker.js" type="text/javascript" language="javascript"></script></head>

<? 
if ($_GET["fromuser"]<>"") {  //用户历史
	$rs=mysql_query("select l.*,t.taskname,t.taskpointer from task_list l,task_type t where t.tasktype=l.tasktype and l.fromuser='".base_decode($_GET["fromuser"])."' order by taskrecvtime desc",$conn);
} else {
$d1=$_POST["rq1"];if ($d1=="") {$d1=date("Y-m-d",strtotime("-1 day"));}
$d2=$_POST["rq2"];if ($d2=="") {$d2=date("Y-m-d",strtotime("-0 day"));}

$rs=mysql_query("select l.*,t.taskname,t.taskpointer from task_list l,task_type t where t.tasktype=l.tasktype and l.taskrecver='".$_SESSION["YKOAUSER"]."' and taskrecvtime>='$d1 00:00:00' and taskrecvtime<='$d2 23:59:59' order by taskrecvtime",$conn);
}
	   
?>

<body style="overflow-x:hidden;overflow-y:auto;margin:20px">
<? if ($_GET["fromuser"]=="") {?>
<form name="form1" method="post" action="" id="form1">
 <div>开始日期：
    <input onClick="WdatePicker();" maxlength="50" class="Wdate" style="width: 100px;" type="text" name="rq1" id="rq1" value="<? echo $d1;?>" />
  &nbsp;&nbsp;&nbsp;&nbsp;结束日期：<input onClick="WdatePicker();" maxlength="50" class="Wdate" style="width: 100px;" type="text" name="rq2" id="rq2" value="<? echo $d2;?>" />&nbsp;
  <input name="bt1" type="submit" value="查 询" />
  </div>
 <? }?>
<table cellspacing="0" cellpadding="0" rules="all" border="0" id="gvOrder" style="border-width:0px;width:100%;border-collapse:collapse;border:1px solid #D3D3D3; border-bottom:0px;">
		<tbody><tr class="td_title" style="height:30px;">
			<th width="69" align="center" scope="col">任务类型</th>
			<th  align="center" scope="col">账号</th>
			<th  align="center" scope="col">创建时间</th>
			<th  align="center" scope="col">任务描述</th>
			<th  align="center" scope="col">完成时间</th>
			<th  align="center" scope="col">操作</th>
		</tr>
        <? 
		for($i=0;$i<mysql_num_rows($rs);$i++){ 
		?>
        <tr onMouseOver="this.style.backgroundColor='#FFD'" onMouseOut="this.style.backgroundColor='white'">
            <td class="td_content" style="text-align:left"><? echo mysql_result($rs,$i,"taskname");?></td>
			<td  class="td_content" style="text-align:left"><? echo mysql_result($rs,$i,"fromuser");?></td>
			<td  class="td_content" style="text-align:center"><? echo mysql_result($rs,$i,"taskcreatetime");?></td>
			<td  class="td_content" style="text-align:center"><? echo mysql_result($rs,$i,"taskdescribe"),mysql_result($rs,$i,"taskmemo");?></td>
			<td  class="td_content" style="text-align:center"><? echo mysql_result($rs,$i,"taskendtime");?></td>
            <td class="td_content" style="text-align:left">
<? if (strpos(mysql_result($rs,$i,"fromuser"),"^")>0) { //A类
		$rsp=mysql_query("select http from nc_erp.dbinfo where dname='".substr(mysql_result($rs,$i,"fromuser"),0,-1)."'");?>
            <input type="button" class="but6" href="javascript:void(0)" onClick="javascript:window.open('<? echo mysql_result($rsp,0,0)."/YKCart.php"?>');document.cookie='currenttaskid=<? echo mysql_result($rs,$i,"id")?>';return false;" value="打开" > 
            <? } else {
				if ($_GET["fromuser"]=="") {?>
            <input type="button" class="but6" href="javascript:void(0)" onClick="javascript:parent.cmain.document.location='<? echo mysql_result($rs,$i,"taskparam")=="gongdan"?("YKKF_taskshow.php?id=".mysql_result($rs,$i,"id")):(mysql_result($rs,$i,"taskpointer").mysql_result($rs,$i,"taskparam"))?>';document.cookie='currenttaskid=<? echo mysql_result($rs,$i,"id")?>';return false;" value="打开" > 
            <? } else {?>
            <input type="button" class="but6" href="javascript:void(0)" onClick="javascript:window.open('<? echo mysql_result($rs,$i,"taskparam")=="gongdan"?("YKKF_taskshow.php?id=".mysql_result($rs,$i,"id")):(mysql_result($rs,$i,"taskpointer").mysql_result($rs,$i,"taskparam"))?>', 'HT_d22', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=yes,width=800,height=650,left=350,top=100');return false;" value="打开" > 
			<? }
			}?></td>

		</tr>
        <? }?>
	</tbody></table>
    	
</div>
      

	<div class="c_t22">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tbody><tr>
    <td style="padding-top:15px; padding-bottom:15px;">
       </td>
  </tr>
</tbody></table>

</div>
</div>


</div>

</div>
</form>
</body></html>