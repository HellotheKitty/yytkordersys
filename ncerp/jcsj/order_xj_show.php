<? require("../../inc/conn.php");require("../../OAfile/SendSMS.php");?>
<? session_start();
if ($_SESSION["OK"]<>"OK") { 
echo "<script language=JavaScript>{window.location.href='../../error.php';}</script>";
exit; 
}?>
<?
if ($_GET["lxdx"]!="") {
	$rsbj=mysql_query("select mobile from base_nsbj where quyubs='".$_GET["lxdx"]."'",$conn);
	if (strlen(mysql_result($rsbj,0,0))==11) {
		sendsms(mysql_result($rsbj,0,0),"客户【".$_GET["khmc"]."】的非标询价请快报价，谢谢！");
		echo "<script>alert('发送成功！');window.location.href='order_xj_show.php?ddh=".$_GET["ddh"]."';</script>";
		exit;
	}
}

if ($_POST["button"]<>"") {
	$bzyq=$_POST["bzyq"];
	$psyq=$_POST["psyq"];
	$memo=$_POST["memo"];
	mysql_query("update order_xj set bzyq='$bzyq',psyq='$psyq',memo='$memo' where id='".$_POST["ddh"]."'",$conn);
	header("location:order_xj_show.php?ddh=".$_POST["ddh"]);
}
if ($_GET["deleid"]<>"") {
	mysql_query("delete from order_xjmx where id='".$_GET["deleid"]."'",$conn);
	header("location:order_xj_show.php?ddh=".$_GET["ddh"]);
}

$rs=mysql_query("select order_xj.*,xs.xm,xs.mb from order_xj,yikab.ry_xs xs where xs.xsbh=order_xj.xsbh and order_xj.id='".$_GET["ddh"]."'",$conn);
$xjzje=mysql_result($rs,0,"zje");$state=mysql_result($rs,0,"state");
$rsmx=mysql_query("select order_xjmx.*,zzfy+banfei+yingong+waixie+filefy+psfy+tax+qtfy as zj,jyjg,minjg from order_xjmx left join order_xjmxbj on order_xjmx.id=order_xjmxbj.xjmxid where xjid='".$_GET["ddh"]."'",$conn);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>询价单信息</title>
<script language="JavaScript" src="../htgl/Mymodify.js"></script>
<SCRIPT language=JavaScript src="../form.js"></SCRIPT>
<SCRIPT language=JavaScript>
function checkForm(){
	var tmpFrm = document.forms[0];
    var charBag = "-0123456789.";
	if (!checkNotNull(form1.mc, "")) return false;
	if (!checkNotNull(form1.bm, "")) return false;
	if (!checkNotNull(form1.je, "")) return false;
	if (!checkStrLegal(form1.je, "", charBag)) return false;
	return true; }
</SCRIPT>
<style type="text/css">
<!--
body {
	background-color: #A5CBF7;
}
.style11 {font-size: 14px}
.STYLE13 {font-size: 12px}
-->
</style>
</head>

<body>
<form name=form1 method="post" action="#">
<input type="hidden" name="ddh" value="<? echo $_GET["ddh"]?>">
<table width="100%" border="1" cellpadding="4" cellspacing="1" bordercolor="#000099" bgcolor="f6f6f6" >
  <tr>
<td height="222" valign="top">
      <table width="75%" height="169" border="1" align="center" cellspacing="0" cellpadding="0" style="border-width:0px;border-collapse:collapse;border:1px solid #D3D3D3; border-bottom:1px solid #D3D3D3; font-size:12px">
            <tr>
              <td height="24" class="STYLE13" width="16%">询价人</td>
              <td width="179"><span class="field">
                <? echo mysql_result($rs,0,"xsbh"),'-',mysql_result($rs,0,"xm"),mysql_result($rs,0,"mb");?>
              </span></td>
              <td width="198" align="left" class="STYLE13">询价时间：<? echo mysql_result($rs,0,"xjsj");?></td>
            </tr>
            <tr>
              <td  height="24" class="STYLE13">客户名称</td>
              <td colspan="2" class="STYLE13"><? echo mysql_result($rs,0,"khmc");?></td>
            </tr>
            <tr>
              <td  height="24" class="STYLE13">产品明细</td>
              <td colspan="2" class="STYLE13"><? echo mysql_result($rs,0,"product");?></td>
            </tr>
            <tr>
              <td height="24" class="STYLE13">是否开票</td>
              <td colspan="2" class="STYLE13"><? echo mysql_result($rs,0,"sffp");?></td>
            </tr>
             <tr>
              <td height="24" class="STYLE13">包装要求</td>
              <td colspan="2" class="STYLE13"><textarea name="bzyq" cols="50" rows="3"><? echo mysql_result($rs,0,"bzyq");?></textarea></td>
            </tr>
            <tr>
              <td height="24" class="STYLE13">配送要求</td>
              <td colspan="2" class="STYLE13"><textarea name="psyq" cols="50" rows="3"><? echo mysql_result($rs,0,"psyq");?></textarea></td>
            </tr>
            <tr>
              <td height="24" class="STYLE13">询价备注</td>
              <td colspan="2" class="STYLE13"><textarea name="memo" cols="50" rows="3"><? echo mysql_result($rs,0,"memo");?></textarea><? if (!is_numeric(mysql_result($rs,0,"zje"))) {?><input type="submit" name="button" id="button" value="保存"><? }?></td>
            </tr>
            <tr>
              <td height="24" class="STYLE13">附件文件</td>
              <td colspan="2" class="STYLE13"><a href='<? echo mysql_result($rs,0,"fjfile");?>'><? echo mysql_result($rs,0,"fjfile");?></a>
            </tr>
            <tr>
              <td height="24" colspan="2" align="left">
              <?
			  if (is_numeric(mysql_result($rs,0,"zje")))
			  	echo "已报价：".mysql_result($rs,$i,"zje")."元","&nbsp;&nbsp;";
			  else {
              if (strpos("1".mysql_result($rs,0,"state"),"待")>0) 
						echo "<font color=red>".mysql_result($rs,0,"state")."</font>";
					else 
						echo mysql_result($rs,0,"state");
					if (mysql_result($rs,0,"state")=="待上海报价" and $_SESSION["FBBJ"]!="1") echo " <a href='?lxdx=sh&ddh=".$_GET["ddh"]."&khmc=".mysql_result($rs,0,"khmc")."'>短信催一下</a>";
					if (mysql_result($rs,0,"state")=="待北京报价" and $_SESSION["FBBJ"]!="1") echo " <a href='?lxdx=bj&ddh=".$_GET["ddh"]."&khmc=".mysql_result($rs,0,"khmc")."'>短信催一下</a>";
					if (mysql_result($rs,0,"state")=="待杭州报价" and $_SESSION["FBBJ"]!="1") echo " <a href='MYOrderxj.php?lxdx=hz2&ddh=".$_GET["ddh"]."&khmc=".mysql_result($rs,0,"khmc")."'>短信催周</a>";
					if (mysql_result($rs,0,"state")=="待广州报价" and $_SESSION["FBBJ"]!="1") echo " <a href='?lxdx=gz&ddh=".$_GET["ddh"]."&khmc=".mysql_result($rs,0,"khmc")."'>短信催一下</a>";
			  }?>
              </td>
              <td height="24" align="right">建议给客户报价总金额：<span id="zje" style="color:#F00"></span>元</td>
            </tr>
        </table>
        
    </td>
  </tr>
  <tr>
  	<td>
    <table cellspacing="0" cellpadding="0" rules="all" border="1" id="gvOrder" style="border-width:0px;width:100%;border-collapse:collapse;border:1px solid #D3D3D3; border-bottom:1px solid #D3D3D3; font-size:12px">
	  <tbody><tr class="td_title" style="height:30px;">
			
			<th  align="center" scope="col">产品</th>
			<th  align="center" scope="col">尺寸</th>
			<th   align="center" scope="col">展开尺寸</th>
			<th   align="center" scope="col">数量</th>
            <th   align="center" scope="col">纸张</th>
            <th align="center" scope="col">工艺</th>
            <th  align="center" scope="col">打样</th>
            <th  align="center" scope="col">生产方式</th>
            <th  align="center" scope="col">附件</th>
            <th  align="center" scope="col">备注</th>
            <th align="center" scope="col">&nbsp;</th>
		</tr>
        <? $zbj=1;
		for($i=0;$i<mysql_num_rows($rsmx);$i++){  ?>
        <tr class="td_title" style="height:30px;">
			
            <td class="td_content" align="center" style="width:80px;"><? echo mysql_result($rsmx,$i,"cpms");?><? if ($state=="新建询价单") { echo "<a href='#' class='nav' onClick='javascript:window.open(\"order_xj_mxdj.php?ddh=".$_GET["ddh"]."&mxid=".mysql_result($rsmx,$i,"id")."\", \"HT_dhdj\", \"toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=yes,width=500,height=410,left=300,top=100\")'>[修改]</a>";?> <a href='order_xj_show.php?deleid=<? echo mysql_result($rsmx,$i,"id");?>&ddh=<? echo mysql_result($rs,0,"id");?>'>删除</a><? }?></td>
            <td align="center" class="td_content" style="width:90px;"><? echo mysql_result($rsmx,$i,"chicun");?></td>
            <td align="center" class="td_content" style="width:90px;"><span class="td_content" style="width:90px;"><? echo mysql_result($rsmx,$i,"chicun2");?></span></td>
            <td align="center" class="td_content" style="width:35px;"><? echo mysql_result($rsmx,$i,"sl");?></td>
            <td class="td_content" align="center" style="width:63px;"><? echo mysql_result($rsmx,$i,"zz");?></td>
            <td class="td_content" align="center" style="width:63px;"><? echo mysql_result($rsmx,$i,"tsgy");?></td>
            <td class="td_content" align="center" style="width:33px;"><? echo mysql_result($rsmx,$i,"sfdy");?></td>
            <td align="center" class="td_content" style="width:83px;"><? echo mysql_result($rsmx,$i,"scfs");?></td>
            <td align="center" class="td_content" style="width:83px;"><? 
			$aaa=array_unique(explode(";",mysql_result($rsmx,$i,"file")));
			foreach ($aaa as $key=>$a1)  
				if ($a1<>"") 
					echo "<a href='../fbfile/{$a1}' target='_blank'>{$a1}</a> ","<br>";
			?></td>
            <td align="center" class="td_content" style="width:83px;"><? echo mysql_result($rsmx,$i,"memo");?></td>
            <td align="center" class="td_content" style="width:83px;"><? if (mysql_result($rsmx,$i,"zj")>0) {echo '建议',mysql_result($rsmx,$i,"jyjg"),'<br>最低',mysql_result($rsmx,$i,"minjg"),'<br>成本',mysql_result($rsmx,$i,"zj");?> <br><a href="#" onClick="javascript:window.open('order_xj_mxbj.php?ddh=<? echo mysql_result($rsmx,$i,"id");?>&lx=read','Orderbj','height=600px,width=920px,top=150px,left=250px,location=no,menubar=no,resizable=no,scrollbars=yes,status=no,titlebar=no,toolbar=no,directories=no');return false;" class="a1">详情</a><? } else {echo "待报价";$zbj=0;} 
			if ($_SESSION["FBBJ"]=="1") {?> 
            <a href="#" onClick="javascript:window.open('order_xj_mxbj.php?ddh=<? echo mysql_result($rsmx,$i,"id");?>','Orderbj','height=600px,width=920px,top=150px,left=250px,location=no,menubar=no,resizable=no,scrollbars=yes,status=no,titlebar=no,toolbar=no,directories=no');return false;" class="a1">报价</a>
            <? }?>
            </td>
		</tr>
        <? $zje=$zje+mysql_result($rsmx,$i,"jyjg");
		}
		if ($zbj==0) 
			$zje="待报价";
		else {
			if ($xjzje!=$zje)
				mysql_query("update order_xj set zje=$zje where id='".$_GET["ddh"]."'",$conn);
		}?>
	</tbody></table>
    </td>
  </tr>
</table>
</form>
</body>
</html>
<script language="javascript">
	document.getElementById("zje").innerHTML='<? echo $zje;?>';
</script>
<? 
$rs=null;
unset($rs);
$rss=null;
unset($rss);
?>