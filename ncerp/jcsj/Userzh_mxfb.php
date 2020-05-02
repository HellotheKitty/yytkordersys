<? 
session_start();
require("../../inc/conn.php");
if ($_SESSION["OK"]<>"OK") { 
echo "<script language=JavaScript>{window.location.href='error.php';}</script>";
exit;

}

if ($_POST["QRID"]<>"") {
	mysql_query("update order_zh set sksj=now() where id=".$_POST["QRID"],$conn);
	echo "OK";exit;
	}
?>
<?
    if($_POST["out_log"]){
        header("Content-type:application/vnd.ms-excel;charset=UTF-8");
        header("Content-Disposition:filename=".iconv("utf-8","gb2312","财务单导出.xls"));
        header("Expires:0");
        header('Pragma:   public'   );
        header("Cache-control:must-revalidate,post-check=0,pre-check=0");

    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    
    <title>名片工坊-账户使用情况</title>
     <link href="../../css/CITICcss.css" rel="stylesheet" type="text/css">
	<script src="../../js/WdatePicker.js" type="text/javascript" language="javascript"></script>
<base target="_self" />
</head>
<? 

if ($_POST["rq1"]<>"") {$cb1=$_POST["cb1"];$cb2=$_POST["cb2"];} else {$cb1="1";$cb2="1";}

if ($_GET["khmc"]<>"") $_SESSION["fbkhmc"]=base_decode($_GET["khmc"]);
if (!isset($_SESSION["fbkhmc"])) $_SESSION["fbkhmc"]="%";
$d1=$_POST["rq1"];if ($d1=="") {$d1=date("Y-")."01-01";}
$d2=$_POST["rq2"];if ($d2=="") {$d2=date("Y-m-d");}
$rs=mysql_query("SELECT concat(xs.kfbh,xs.xm),khmc,jf,df,zy,fssj,ifnull(sksj,''),order_zh.id,memo,ddh,order_zh.xsbh from order_zh left join ry_kf xs on order_zh.xsbh=xs.kfbh where  fssj>='$d1 00:00:00' and fssj<='$d2 23:59:59' AND khmc like '".$_SESSION["fbkhmc"]."' order by khmc,fssj",$conn);

?>

<body style="overflow-x:hidden;overflow-y:auto">
<form name="form1" method="post" action="" id="form1">

<div></div>

<div class="main_box">

<table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:0px;">
  <tbody><tr>
    <td valign="top">
<div style="padding:5px 5px 5px 5px; color:#58595B">
 <div style="padding-bottom:10px; font-weight:bold;">
   客户：
     <select name="khmc" id="khmc" onchange="javascript:window.location.href='?userzh=<? echo $xsbh?>&amp;khmc='+this.options[this.options.selectedIndex].value;">
     <option value="<? echo base_encode($_SESSION["fbkhmc"]);?>"><? echo $_SESSION["fbkhmc"];?></option>

   </select>
 </div>
  <div>开始日期：
    <input onClick="WdatePicker();" maxlength="50" class="Wdate" style="width: 100px;" type="text" name="rq1" id="rq1" value="<? echo $d1;?>" />
  &nbsp;&nbsp;&nbsp;&nbsp;结束日期：<input onClick="WdatePicker();" maxlength="50" class="Wdate" style="width: 100px;" type="text" name="rq2" id="rq2" value="<? echo $d2;?>" />&nbsp;
  <input name="bt1" type="submit" value="查 询" />
  </div>
	        <div class="page">
<div id="AspNetPager2" style="width:100%;text-align:right;">
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tbody><tr>
			<td width="27%" align="left" valign="bottom" nowrap="true" style="width:40%;"><? echo $_SESSION["fbkhmc"];?></td>
            <td width="73%" align="right" valign="bottom" nowrap="true" class="" style="width:60%;">
                <!--<input type="button"  onclick="window.open('Userzh_mxfb_excel.php?tss=<?/* echo urlencode($tss)*/?>&ss=<?/* echo $ss*/?>&rq1='+form1.rq1.value+'&rq2='+form1.rq2.value);" value="Excel导出" style="display:" />-->
                <input type="submit" name="out_log" value="Excel导出"  />
        　  </td>
		</tr>
	</tbody></table>
</div>

		    </div>
                    <div>
	<table cellspacing="0" cellpadding="0" rules="all" border="1" id="gvOrder" style="border-width:1px;width:100%;border-collapse:collapse;border:1px solid #D3D3D3; border-bottom:0px; font-size:12px">
		<tbody><tr class="td_title" style="height:30px;">
			<th align="center" scope="col"><!--销售（姓名）-->方　　式</th>
			<th align="center" scope="col">客户名称</th>
			<th align="center" scope="col">支付金额</th>
			<th align="center" scope="col">消费金额</th>
			<th align="center" scope="col">摘要</th>
			<th align="center" scope="col">发生时间</th>
		</tr>
        <? $jhj=0;$dhj=0; 
		for($i=0;$i<mysql_num_rows($rs);$i++){  ?>
        <tr>
			<td align="center" class="td_content" style="width:77px;"><? echo mysql_result($rs,$i,10);?></td>
            <td align="center" class="td_content" style="width:100px;"><? echo mysql_result($rs,$i,1);?></td>
            <td class="td_content" align="center" style="width:80px;"><? if (mysql_result($rs,$i,2)<>0) echo mysql_result($rs,$i,2); else echo "&nbsp;";?></td>
            <td class="td_content" align="center" style="width:90px;"><? if (mysql_result($rs,$i,3)<>0) echo mysql_result($rs,$i,3); else echo "&nbsp;";?></td>
            <td class="td_content" style="width:140px; text-align:left"><? echo mysql_result($rs,$i,4),mysql_result($rs,$i,"ddh");if ($_SESSION["FBCW"]<>"" and mysql_result($rs,$i,"memo")<>"") echo "<br>备注:",mysql_result($rs,$i,"memo");?></td>
            <td class="td_content" align="center" style="width:63px;"><? echo mysql_result($rs,$i,5);if ($_SESSION["FBCW"]<>"" and mysql_result($rs,$i,4)<>"订单结算") {echo "<a href='javascript:void(0);' onclick='javascript:if (confirm(\"真的要删除吗？\")) go(\"../../mainb/editzy.php?userzh={$xsbh}&delskd=1&id=".mysql_result($rs,$i,"id")."\");'>删</a>";}?></td>
		</tr>
        <? $jhj=$jhj+mysql_result($rs,$i,2);$dhj=$dhj+mysql_result($rs,$i,3);
		}?>
        <tr>
			<td colspan="2" align="center" class="td_content" style="height:25px;">合计：</td>
            <td class="td_content" align="center" style="width:80px;"><? echo $jhj;?><br></td>
            <td colspan="3" align="center" class="td_content" style="width:270px;">充值：<? echo $jhj;?>，消费：<? echo $dhj;?></font></td>
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
<script language="javascript">
function go(url) 
{       
    var aaa=document.createElement("a");   
    aaa.href=url;   
    document.body.appendChild(aaa);   
    aaa.click();  
}

 function modiit(b1) {
 			var xmlHttpReq;
            if (typeof (XMLHttpRequest) != "undefined")
                xmlHttpReq = new XMLHttpRequest();
            else if (window.ActiveXObject)
                xmlHttpReq = new ActiveXObject("MSXML2.XMLHTTP.3.0");
            xmlHttpReq.open("POST", "Userzh_mxfb.php?jid=" + Math.round(Math.random() * 10000), false);
            xmlHttpReq.setRequestHeader("Content-Type","application/x-www-form-urlencoded;charset=UTF-8");
            xmlHttpReq.send("QRID=" + b1 );
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
