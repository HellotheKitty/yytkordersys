﻿<? 
session_start();
require("../inc/conn.php");
if ($_GET["uu"]<>"") {
	if ($_GET["cks"]==md5("hzyk".$_GET["uu"]."winner")) {  //验证通过
	$_SESSION["OK"] = "OK";
	}
}
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
<style type="text/css">
.STYLE13 {font-size: 12px}
</style>
</head>
<?
$dwdm = substr($_SESSION['GDWDM'],0,4);
$d1=empty($_POST["rq1"])?$_GET["rq1"]:$_POST["rq1"];
if ($d1=="") {$d1=date("Y-m-d",strtotime("-1 day"));$ss="";$tss="客服个人任务列表";}
$d2=empty($_POST["rq2"])?$_GET["rq2"]:$_POST["rq2"];
if ($d2=="") {$d2=date("Y-m-d");}
if ($_GET["tt"]<>"") $ttt=$_GET["tt"]; else $ttt="%";
if ($_GET["kfry"]<>"") $kfry=$_GET["kfry"]; else $kfry="%";
$rs=mysql_query("select l.*,t.xm from task_list l,task_kfry t where t.oabh=l.taskrecver and l.tasktype like '$ttt' and taskrecvtime>='$d1 00:00:00' and taskrecvtime<='$d2 23:59:59' and t.xm like '$kfry' and t.zzfy = $dwdm order by taskrecvtime",$conn);

?>

<body style="overflow-x:hidden;overflow-y:auto">
<form name="form1" method="post" action="" id="form1">

<div class="">

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
			<td width="27%" align="left" valign="bottom" nowrap="true" style="width:40%;"><span class="STYLE13">
            任务类型
			  <select name="type" id="type" onchange="window.location.href='?tt='+this.options[this.selectedIndex].value+'&rq1=<? echo $d1?>&rq2=<? echo $d2?>';">
			    <? $rs0=mysql_query("select tasktype,taskname,taskryxm,taskrecvxm from task_type order by tasktype");
				$tt=0;
				echo '<option value="%" >全部任务</option>';
				for ($i=0;$i<mysql_num_rows($rs0);$i++)
					if (mysql_result($rs0,$i,0)==$_GET["tt"]) {
						echo '<option value="'.mysql_result($rs0,$i,0).'" selected>'.mysql_result($rs0,$i,1).'</option>';
						$tt=$i;
					} else
						echo '<option value="'.mysql_result($rs0,$i,0).'" >'.mysql_result($rs0,$i,1).'</option>';
					?>
			    </select>
			</span>
            <span class="STYLE13">
            客服人员
			  <select name="kfry" id="kfry" onchange="window.location.href='?tt=<? echo $_GET["tt"]?>&kfry='+this.options[this.selectedIndex].value+'&rq1=<? echo $d1?>&rq2=<? echo $d2?>';">
			    <? $rs0=mysql_query("select distinct t.xm from task_list l,task_kfry t where t.oabh=l.taskrecver and l.tasktype like '$ttt' and taskrecvtime>='$d1 00:00:00' and taskrecvtime<='$d2 23:59:59' and t.zzfy = $dwdm order by t.xm");
				$tt=0;
				echo '<option value="%" >全部</option>';
				for ($i=0;$i<mysql_num_rows($rs0);$i++)
					if (mysql_result($rs0,$i,0)==$_GET["kfry"]) {
						echo '<option value="'.mysql_result($rs0,$i,0).'" selected>'.mysql_result($rs0,$i,0).'</option>';
						$tt=$i;
					} else
						echo '<option value="'.mysql_result($rs0,$i,0).'" >'.mysql_result($rs0,$i,0).'</option>';
					?>
			    </select>
			</span></td><td width="73%" align="right" valign="bottom" nowrap="true" class="" style="width:60%;">			  　　　　</td>
		</tr>
	</tbody></table>
</div>

		    </div>
                    <div>
	<table cellspacing="0" cellpadding="0" rules="all" border="0" id="gvOrder" style="border-width:0px;width:100%;border-collapse:collapse;border:1px solid #D3D3D3; border-bottom:0px;">
		<tbody><tr class="td_title" style="height:30px;">
			<th align="center" scope="col">任务创建</th>
			<th align="center" scope="col">客服人员</th>
			<th align="center" scope="col">任务所属</th>
			<th align="center" scope="col">任务备注</th>
			<th align="center" scope="col">任务状态</th>
			<th align="center" scope="col">任务接收</th>
			<th align="center" scope="col">开始处理</th>
			<th align="center" scope="col">结束时间</th>
			<th align="center" scope="col">时长</th>
			<th align="center" scope="col"><? echo $ttt=='9'?'投诉类型':'数量';?></th>
			<th align="center" scope="col">任务描述</th>
		</tr>
        <? $jhj=0;$dhj=0;$zsj=0;
		for($i=0;$i<mysql_num_rows($rs);$i++){  ?>
        <tr bgcolor="<? echo mysql_result($rs,$i,"taskstate")=="已完成"?"":"#CCBBFF";?>">
			<td align="center" class="td_content" style="width:77px;"><? echo mysql_result($rs,$i,"taskcreatetime");?></td>
            <td align="center" class="td_content" style="width:77px;"><? echo mysql_result($rs,$i,"xm");?></td>
            <td class="td_content" align="center" style="width:80px;"><? echo mysql_result($rs,$i,"fromuser");?></td>
            <td align="center" class="td_content" style="width:120px;"><? echo mysql_result($rs,$i,"taskmemo");?></td>
            <td align="center" class="td_content" style="width:63px;" title="<? echo mysql_result($rs,$i,"statetime");?>"><? echo mysql_result($rs,$i,"taskstate");?></td>
            <td align="center" class="td_content" style="width:63px;" title="<? echo mysql_result($rs,$i,"taskrecvtime");?>"><? echo mysql_result($rs,$i,"taskrecver");?></td>
            <td align="center" class="td_content" style="width:63px;"><? echo mysql_result($rs,$i,"taskbegintime");?></td>
            <td align="center" class="td_content" style="width:63px;"><? echo mysql_result($rs,$i,"taskendtime");?></td>
            <td align="center" class="td_content" style="width:63px;">
			<? if(mysql_result($rs,$i,"taskbegintime")<>'' and mysql_result($rs,$i,"taskendtime")<>''){
				$rrr=mysql_query("select sum(susetime-susbtime) from task_suspend where not susetime is null and taskid=".mysql_result($rs,$i,"id"));
				$zsj+=strtotime(mysql_result($rs,$i,"taskendtime"))-strtotime(mysql_result($rs,$i,"taskbegintime"))-mysql_result($rrr,0,0);
				echo time2Units(strtotime(mysql_result($rs,$i,"taskendtime"))-strtotime(mysql_result($rs,$i,"taskbegintime"))-mysql_result($rrr,0,0));
			} else echo "&nbsp;";?></td>
            <td align="center" class="td_content" style="width:33px;">
			<? 
				if (mysql_result($rs,$i,"tasktype")=='9') {
					switch (mysql_result($rs,$i,"srcid")) { 
				 		case 9001: echo "对产品质量的投诉：文件制作 色差 裁切 纸张 印刷"; break;
				 		case 9002: echo "对收货时效的投诉：没有如期发货 快递送货延迟 发错货 少发货"; break;
				 		case 9003: echo "对服务的投诉：服务质量 服务流程 服务人员"; break;
				 		case 9004: echo "其他投诉"; break;
				 }} else echo mysql_result($rs,0,"srcid");?></td>
            <td align="center" class="td_content" style="width:120px;word-wrap: break-word;word-break:break-all;"><? echo mysql_result($rs,$i,"taskdescribe");
			if (mysql_result($rs,$i,"tasktype")=='9' and mysql_result($rs,$i,"fromorder")<>'') echo "<br><font color=pink>处理结果：</font>",mysql_result($rs,$i,"fromorder");?></td>
		</tr>
        <? $jhj=$jhj+1;if (mysql_result($rs,$i,"taskendtime")=="") $dhj=$dhj+1;
		}?>
        <tr>
			<td colspan="2" align="center" class="td_content" style="width:77px;">合计：</td>
            <td colspan="6" align="left" class="td_content" style="width:80px; text-align:left"><? echo '任务总数：',$jhj,'，未完成：',$dhj;?><br></td>
            <td colspan="3" align="left" class="td_content" style="width:80px; text-align:left"><? echo '平均：',$jhj-$dhj==0?'':time2Units($zsj/($jhj-$dhj));?><br></td>
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

<?
function time2Units ($time)
{
   $year   = floor($time / 60 / 60 / 24 / 365);
   $time  -= $year * 60 * 60 * 24 * 365;
   $month  = floor($time / 60 / 60 / 24 / 30);
   $time  -= $month * 60 * 60 * 24 * 30;
   $week   = floor($time / 60 / 60 / 24 / 7);
   $time  -= $week * 60 * 60 * 24 * 7;
   $day    = floor($time / 60 / 60 / 24);
   $time  -= $day * 60 * 60 * 24;
   $hour   = floor($time / 60 / 60);
   $time  -= $hour * 60 * 60;
   $minute = floor($time / 60);
   $time  -= $minute * 60;
   $second = $time;
   $elapse = '';

   $unitArr = array('年'  =>'year', '个月'=>'month',  '周'=>'week', '天'=>'day',
                    '小时'=>'hour', '分钟'=>'minute', '秒'=>'second'
                    );

   foreach ( $unitArr as $cn => $u )
   {
       if ( $$u > 0 )
       {
           $elapse = $$u . $cn;
           break;
       }
   }
    return $elapse;
}
   ?>
