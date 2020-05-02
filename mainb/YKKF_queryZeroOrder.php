<? 
session_start();
require("inc/conn.php");
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
    
    <title>名片工坊-免单情况</title>
    <link href="./css/CITICcss.css" rel="stylesheet" type="text/css">
	<script src="jsp/WdatePicker.js" type="text/javascript" language="javascript"></script>
<base target="_self" />
<style type="text/css">
.STYLE13 {font-size: 12px}
</style>
</head>
<?
$d1=empty($_POST["rq1"])?$_GET["rq1"]:$_POST["rq1"];
if ($d1=="") {$d1=date("Y-m-d",strtotime("-7 day"));$ss="";$tss="客服个人任务列表";}
$d2=empty($_POST["rq2"])?$_GET["rq2"]:$_POST["rq2"];
if ($d2=="") {$d2=date("Y-m-d");}
if ($_GET["tt"]<>"") $ttt=$_GET["tt"]."%"; else $ttt="%";

$rs=mysql_query("select m.*,sum(x.sl) sl from order_main m,order_mx x where m.ddh=x.ddh and m.memo like ':$ttt' and ddate>='$d1 00:00:00' and ddate<='$d2 23:59:59' and left(state,4)<>'订单取消' group by m.ddh order by ddate",$conn);

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
            免单类型
			  <select name="type" id="type" onchange="window.location.href='?tt='+this.options[this.selectedIndex].value+'&rq1=<? echo $d1?>&rq2=<? echo $d2?>';">
			    <? $rs0=mysql_query("select distinct substr(memo,2,instr(substr(memo,2),':')-1) from order_main where memo like ':%' and ddate>='$d1 00:00:00' and ddate<='$d2 23:59:59'");
				$tt=0;
				echo '<option value="%" >全部</option>';
				for ($i=0;$i<mysql_num_rows($rs0);$i++)
					if (mysql_result($rs0,$i,0)==$_GET["tt"]) {
						echo '<option value="'.mysql_result($rs0,$i,0).'" selected>'.mysql_result($rs0,$i,0).'</option>';
						$tt=$i;
					} else
						echo '<option value="'.mysql_result($rs0,$i,0).'" >'.mysql_result($rs0,$i,0).'</option>';
					?>
			    </select>
			</span>
            </td><td width="73%" align="right" valign="bottom" nowrap="true" class="" style="width:60%;">			  　　　　</td>
		</tr>
	</tbody></table>
</div>

		    </div>
                    <div>
	<table cellspacing="0" cellpadding="0" rules="all" border="0" id="gvOrder" style="border-width:0px;width:100%;border-collapse:collapse;border:1px solid #D3D3D3; border-bottom:0px;">
		<tbody><tr class="td_title" style="height:30px;">
			<th align="center" scope="col">账号</th>
			<th align="center" scope="col">订单号</th>
			<th align="center" scope="col">下单日期</th>
			<th align="center" scope="col">配送方式</th>
			<th align="center" scope="col">订单状态</th>
			<th align="center" scope="col">名片数量</th>
			<th align="center" scope="col">备注</th>
			
		</tr>
        <? $jhj=0;$dhj=0;$zsj=0;
		for($i=0;$i<mysql_num_rows($rs);$i++){  ?>
        <tr >
			<td  class="td_content" style="width:77px;text-align:left"><? echo mysql_result($rs,$i,"user");?></td>
            <td align="center" class="td_content" style="width:77px;"><? echo mysql_result($rs,$i,"ddh");?></td>
            <td class="td_content" align="center" style="width:80px;"><? echo mysql_result($rs,$i,"ddate");?></td>
            <td align="center" class="td_content" style="width:80px;"><? echo mysql_result($rs,$i,"psfs");?></td>
            <td align="center" class="td_content" style="width:63px;"><? echo mysql_result($rs,$i,"state");?></td>
            <td align="center" class="td_content" style="width:63px;"><? echo mysql_result($rs,$i,"sl");?></td>
            <td  class="td_content" style="width:163px; text-align:left"><? echo mysql_result($rs,$i,"memo");?></td>
           
		</tr>
        <? $jhj=$jhj+mysql_result($rs,$i,"sl");$dhj++;
		}?>
        <tr>
			<td colspan="5" align="center" class="td_content" style="width:77px;">合计：</td>
            <td colspan="2" align="left" class="td_content" style="width:80px; text-align:left"><? echo '共：',$dhj,'个订单，盒数：',$jhj;?></td>
            
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
