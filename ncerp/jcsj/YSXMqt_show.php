<? require("../includes/conn.php");?>
<? session_start();
if ($_SESSION["OK"]<>"OK") { 
echo "<script language=JavaScript>{window.location.href='../error.php';}</script>";
exit; 
}?>
<?
if ($_POST["button"]<>"") {
	$bzyq=$_POST["bzyq"];
	$kpyq=$_POST["kpyq"];
	$memo=$_POST["memo"];
	$scjd=$_POST["scjd"];
	mysql_query("update order_mainqt set psfs='".$_POST["psfs"]."',shr='".$_POST["shr"]."',shdh='".$_POST["shdh"]."',shdz='".$_POST["shdz"]."',scjd='$scjd',bzyq='$bzyq',kpyq='$kpyq',memo='$memo' where ddh='".$_POST["ddh"]."'",$conn);
	header("location:YSXMqt_show.php?ddh=".$_POST["ddh"]);
}
if ($_GET["deleid"]<>"") {
	mysql_query("delete from order_mxqt where id='".$_GET["deleid"]."'",$conn);
	header("location:YSXMqt_show.php?ddh=".$_GET["ddh"]);
}
$rs=mysql_query("select * from order_mainqt where ddh='".$_GET["ddh"]."'",$conn);
$xjzje=mysql_result($rs,0,"dje");$state=mysql_result($rs,0,"state");
$rsmx=mysql_query("select * from order_mxqt where ddh='".$_GET["ddh"]."'",$conn);
$rskh=mysql_query("select * from base_kh where khmc='".mysql_result($rs,0,"khmc")."'",$conn);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>订单信息</title>
<script language="JavaScript" src="../htgl/Mymodify.js"></script>
<SCRIPT language=JavaScript src="../form.js"></SCRIPT>

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
      <table width="80%"  border="1" align="center" cellspacing="0" cellpadding="0" style="border-width:0px;border-collapse:collapse;border:1px solid #D3D3D3; border-bottom:1px solid #D3D3D3; font-size:12px">
            <tr>
              <td height="24" class="STYLE11" width="16%" align="center">订单编号</td>
              <td width="179"><span class="field">
                <? echo mysql_result($rs,0,"ddh");?>
              </span></td>
              <td width="198" align="left" class="STYLE11">下单时间：<? echo mysql_result($rs,0,"ddate");?></td>
            </tr>
            <tr>
              <td  height="24" class="STYLE11" align="center">客户名称</td>
              <td colspan="2" class="STYLE13"><? echo mysql_result($rs,0,"khmc");?></td>
            </tr>
            <tr>
              <td  height="24" class="STYLE11" align="center">订单金额</td>
              <td colspan="2" class="STYLE13"><? echo mysql_result($rs,0,"dje"),"元， 快递费：",mysql_result($rs,0,"kdje"),"元， 合计：",mysql_result($rs,0,"dje")+mysql_result($rs,0,"kdje");?>元</td>
            </tr>
            <tr>
              <td height="24" class="STYLE11" align="center">配送信息</td>
              <td colspan="2" class="STYLE13">配送：<input name="psfs" type="text" value="<? echo mysql_result($rs,0,"psfs")?>" size="6">&nbsp;&nbsp;收货人：<input name="shr" type="text" value="<? echo mysql_result($rs,0,"shr")?>" size="6">&nbsp;&nbsp;电话：<input name="shdh" type="text" value="<? echo mysql_result($rs,0,"shdh")?>" size="12"><br>
              地址：<input name="shdz" type="text" value="<? echo mysql_result($rs,0,"shdz")?>" size="35"></td>
            </tr>
             <tr>
              <td height="24" class="STYLE11" align="center">开票要求</td>
              <td colspan="2" class="STYLE13"><textarea name="kpyq" cols="50" rows="3"><? echo mysql_result($rs,0,"kpyq");?></textarea></td>
            </tr>
            <tr>
              <td height="24" class="STYLE11" align="center">包装要求</td>
              <td colspan="2" class="STYLE13"><textarea name="bzyq" cols="50" rows="3"><? echo mysql_result($rs,0,"bzyq");?></textarea></td>
            </tr>
            <tr>
              <td height="24" class="STYLE11" align="center">订单备注</td>
              <td colspan="2" class="STYLE13"><textarea name="memo" cols="50" rows="3"><? echo mysql_result($rs,0,"memo");?></textarea></td>
            </tr>
			<tr>
              <td height="24" class="STYLE11" align="center">建议生产地</td>
              <td colspan="2" class="STYLE13"><input name="scjd" type="text" value="<? echo mysql_result($rs,0,"scjd");?>" size="12" maxlength="4"> <? if (mysql_result($rs,0,"state")!="订单完成" and mysql_result($rs,0,"state")!="退回") {?><input type="submit" name="button" id="button" value="保存"><? }?></td>
            </tr>
            <tr>
              <td height="24" align="right"></td>
              <td height="24" align="left"><? if (mysql_result($rs,0,"xjdid")!=0) echo "询价单ID:",mysql_result($rs,0,"xjdid"),"<a href='javascript:void(0)' onclick='javascript:window.open(\"order_xj_show.php?ddh=".mysql_result($rs,0,"xjdid")."\",\"XJD\",\"height=600px,width=920px,top=150px,left=280px,location=no,menubar=no,resizable=no,scrollbars=yes,status=no,titlebar=no,toolbar=no,directories=no\");return false;'>[查看]</a>";?></td>
              <td height="24" align="right">明细合计金额：<span id="zje" style="color:#F00"></span>元</td>
            </tr>
        </table>
        
    </td>
  </tr>
  <tr>
  	<td>
    <table cellspacing="0" cellpadding="0" rules="all" border="1" id="gvOrder" style="border-width:0px;width:100%;border-collapse:collapse;border:1px solid #D3D3D3; border-bottom:1px solid #D3D3D3; font-size:12px">
	  <tbody><tr class="td_title" style="height:30px;">
			
			<th width="132"  align="center" scope="col">产品</th>
			<th width="92"  align="center" scope="col">尺寸</th>
			<th width="35"  align="center" scope="col">数量</th>
            <th width="64"  align="center" scope="col">纸张</th>
            <th width="64" align="center" scope="col">工艺</th>
            <th width="33"  align="center" scope="col">打样</th>
            <th width="85"  align="center" scope="col">生产方式</th>
            <th width="28"  align="center" scope="col">配送方式</th>
            <th width="28"  align="center" scope="col">附件</th>
            <th width="66"  align="center" scope="col">备注</th>
            <th width="45"   align="center" scope="col">价格</th>
            <th width="45"   align="center" scope="col">生产文件</th>
		</tr>
        <? 
		for($i=0;$i<mysql_num_rows($rsmx);$i++){  ?>
        <tr class="td_title" style="height:30px;">
			
            <td class="td_content" align="center" ><? echo mysql_result($rsmx,$i,"cpms");if (($state=="新建订单" and mysql_result($rs,0,"xjdid")>0) or ($_SESSION["FBCW"]=="1" and date('m',strtotime(mysql_result($rs,0,"ddate")))==date('m'))) echo "<a href='#' class='nav' onClick='javascript:window.open(\"YSXMqt_mxdj.php?mxid=".mysql_result($rsmx,$i,"id")."\", \"HT_dhdj\", \"toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=yes,width=500,height=410,left=300,top=100\")'>[修改]</a>"; if ($state=="新建订单" and mysql_result($rs,0,"xjdid")==0) {?> <a href='YSXMqt_show.php?deleid=<? echo mysql_result($rsmx,$i,"id");?>&ddh=<? echo mysql_result($rs,0,"ddh");?>'>删除</a><? }?></td>
            <td class="td_content" align="center" ><? echo mysql_result($rsmx,$i,"chicun");?></td>
            <td align="center" class="td_content" ><? echo mysql_result($rsmx,$i,"sl");?></td>
            <td class="td_content" align="center" ><? echo mysql_result($rsmx,$i,"zz");?></td>
            <td class="td_content" align="center"><? echo mysql_result($rsmx,$i,"tsgy");?></td>
            <td class="td_content" align="center"><? echo mysql_result($rsmx,$i,"sfdy");?></td>
            <td class="td_content" align="center" ><? echo mysql_result($rsmx,$i,"scfs");?></td>
            <td align="center" class="td_content"><? echo mysql_result($rsmx,$i,"psfs");?></td>
            <td align="center" class="td_content"><? if (strpos(mysql_result($rsmx,$i,"file"),";")>0) {
				  $aaa=array_unique(explode(";",mysql_result($rsmx,$i,"file")));
			foreach ($aaa as $key=>$a1)  
				if ($a1<>"") 
					echo "<a href='../fbfile/{$a1}' target='_blank'>{$a1}</a>","<br>";
			  } else 
              	echo "<a href=".mysql_result($rsmx,$i,"file").">".mysql_result($rsmx,$i,"file")."</a>"; ?></td>
            <td align="center" class="td_content"><? echo mysql_result($rsmx,$i,"memo");?></td>
            <td align="center" class="td_content" ><? echo mysql_result($rsmx,$i,"zj");  ?>
            </td>
            <td align="center" class="td_content" ><? 
			$aaa=array_unique(explode(";",mysql_result($rsmx,$i,"scfile")));
			foreach ($aaa as $key=>$a1)  
				if ($a1<>"") 
					echo "<a href='../fbfile/{$a1}' target='_blank'>{$a1}</a>","<br>";
			?></td>
		</tr>
        <? $zje=$zje+mysql_result($rsmx,$i,"zj");
		}?>
	</tbody></table>
    <font color="#FF0000">注意：生产文件同类型文件以最后一个为准，如有疑问请及时沟通。</font>
    </td>
  </tr>
</table>
<br>
<? if ((mysql_result($rs,0,"state")=="待生产" or mysql_result($rs,0,"state")=="进入生产") and $_SESSION["FBSD"]=="1") {?><div align="center"><input name="b1" value="打印生产单" type="button" onClick="window.open('YSXMqt_show_p.php?ddh=<? echo $_GET["ddh"];?>')"></div><? }?>
<? if (mysql_result($rs,0,"state")=="订单完成") {?><div align="center"><input type="button" onClick="window.open('YSXMqt_sh_p.php?ddh=<? echo $_GET["ddh"];?>','new');" value="生成送货单" /></div><? }?>
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