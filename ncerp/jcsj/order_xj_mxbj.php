<? require("../../inc/conn.php");require("../../OAfile/SendSMS.php");?>
<? session_start();
if ($_SESSION["OK"]<>"OK") { 
echo "<script language=JavaScript>{window.location.href='../../error.php';}</script>";
exit; 
}?>
<?
if ($_POST["zzfy"]<>"") {
	if ($_POST["waixiefp"]=="1") $wxfp=1; else $wxfp=0;
	$xjmxid=$_POST["xjmxid"];
	mysql_query("delete from order_xjmxbj where xjmxid=".$_POST["xjmxid"],$conn);
	//更新mx
	mysql_query("update order_mxqt set zj=".$_POST["jybj"]." where khmc=(select concat('-1',xjid) from order_xjmx where id=".$_POST["xjmxid"].")");
	$rs00=mysql_query("select sum(zj),ddh from order_mxqt where ddh=(select ddh from order_mxqt where khmc=(select concat('-1',xjid) from order_xjmx where id=".$_POST["xjmxid"]."))");
	mysql_query("update order_mainqt set dje=".mysql_result($rs00,0,0)." where ddh='".mysql_result($rs00,0,1)."'",$conn);
	
	mysql_query("insert into order_xjmxbj values (0,".$_POST["xjmxid"].",".$_POST["zzfy"].",".$_POST["banfei"].",".$_POST["yingong"].",".$_POST["waixie"].",$wxfp,".$_POST["filefy"].",".$_POST["psfy"].",".$_POST["tax"].",".$_POST["qtfy"].",'".$_POST["dyzq"]."','".$_POST["sczq"]."','".$_POST["memo"]."','".$_POST["psfs"]."','".$_POST["minbj"]."','".$_POST["jybj"]."',now(),'".$_SESSION["USER"]."')");
	$rss=mysql_query("select count(1),xj.id from order_xjmx mx left join order_xjmxbj bj on bj.xjmxid=mx.id,order_xj xj where bj.jyjg is null and mx.xjid=xj.id and xjid=(SELECT xjid FROM `order_xjmx` where id=$xjmxid )",$conn);    //是否都完成报价
	if (mysql_result($rss,0,0)==0) {
		$rsry=mysql_query("select xm,mb,khmc,zje from yikab.ry_xs,order_xj where order_xj.id=".mysql_result($rss,0,1)." and order_xj.xsbh=ry_xs.xsbh",$conn);
		if (mysql_num_rows($rsry)>0) 
			sendsms(mysql_result($rsry,0,1),"".mysql_result($rsry,0,0).":您的非标询价已经报价！客户：".mysql_result($rsry,0,2).",建议报价：".mysql_result($rsry,0,3)."元。");
	}
	echo "<script>alert('保存完成');window.opener.location.reload();window.close();</script>";
}
$bh=$_GET["ddh"];
if ($bh=="")
	$bh=mysql_result(mysql_query("select id from order_xjmx where xjid=".$_GET["xjid"],$conn),0,0);
$rs=mysql_query("select *,sfdy+0 sfdy1 from order_xjmx where id=$bh",$conn);
//$rs2=mysql_query("select * from order_xjmxbj where xjmxid=$bh",$conn);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>询价单报价</title>
<script language="JavaScript" src="../htgl/Mymodify.js"></script>
<SCRIPT language=JavaScript src="../form.js"></SCRIPT>
<SCRIPT language=JavaScript>
function zjhj(){
	var zzfy=form1.zzfy.value;
	var banfei=form1.banfei.value;
	var yingong=form1.yingong.value;
	var waixie=form1.waixie.value;
	var filefy=form1.filefy.value;
	var psfy=form1.psfy.value;
	var qtfy=form1.qtfy.value;
	var tax=form1.tax.value;
	var hj=parseFloat(zzfy)+parseFloat(banfei)+parseFloat(yingong)+parseFloat(waixie)+parseFloat(filefy)+parseFloat(psfy)+parseFloat(qtfy)+parseFloat(tax);
	document.getElementById("zj").innerHTML=hj;
	document.form1.jybj.value=Math.round(hj*1.5*100)/100;
	document.form1.minbj.value=Math.round(hj*1.2*100)/100;
	document.getElementById("jy").innerHTML=150.00;
	document.getElementById("minb").innerHTML=120.00;
}
</SCRIPT>
<style type="text/css">
<!--
body {
	background-color: #A5CBF7;
}
.style11 {font-size: 14px}
.STYLE13 {font-size: 12px}
.STYLE14 {font-size: 12px; font-weight:bold}
-->
</style>
</head>

<body>
<form action="" method="post"  name="form1" id="form1" >
<input type="hidden" name="xjmxid" value="<? echo $bh?>" />
<table width="100%" border="1" cellpadding="4" cellspacing="1" bordercolor="#000099" bgcolor="f6f6f6" >
  <tr>
<td height="222" valign="top">
      <label><span class="STYLE8 STYLE13">
          </span></label>
      <table width="70%" height="277" border="1" align="center" cellspacing="0" cellpadding="0" style="border-width:0px;border-collapse:collapse;border:1px solid #D3D3D3; border-bottom:1px solid #D3D3D3; font-size:12px">
            <tr>
              <td height="27" class="STYLE13">询价号</td>
              <td width="134" class="STYLE13"> <? echo mysql_result($rs,0,"xjid");?> </td>
              <input type="hidden" name="id" value="<? echo $bh?>" />
              <td width="201" align="right" class="STYLE13">&nbsp;</td>
            </tr>
            
            <tr>
              <td width="51" height="27" class="STYLE13">产品</td>
              <td colspan="2" class="STYLE13">
                <? echo mysql_result($rs,0,"cpms");?>
              </td>
            </tr>
            <tr>
              <td width="51" height="27" class="STYLE13">尺寸</td>
              <td class="STYLE13">
                <? echo mysql_result($rs,0,"chicun");?>
              </td>
            <td class="STYLE13">展开尺寸：<? echo mysql_result($rs,0,"chicun2");?></td>
            </tr>
            <tr>
              <td width="51" height="27" class="STYLE13">数量</td>
              <td class="STYLE13"><? echo mysql_result($rs,0,"sl");?> 　　
              <? if (mysql_result($rs,0,"sfdy1")==1) echo "要求打样"; else echo "不打样";?></td>
            <td class="STYLE13">预估重量：<? echo mysql_result($rs,0,"zhongliang");?>公斤</td>
            </tr>
            <tr>
              <td height="27" class="STYLE13">纸张</td>
              <td colspan="2" class="STYLE13">
              <? echo mysql_result($rs,0,"zz");?>
              </td>
            </tr>
            <tr>
              <td height="27" class="STYLE13">工艺</td>
              <td colspan="2" class="STYLE13">
                <? echo mysql_result($rs,0,"tsgy");?>
              </td>
            </tr>
            <tr>
              <td height="27" class="STYLE13">生产方式</td>
              <td colspan="2" class="STYLE13">
                 <? echo mysql_result($rs,0,"scfs");?>
              </td>
            </tr>
            <tr>
              <td height="27" class="STYLE13">附属文件</td>
              <td colspan="2" class="STYLE13">
              <? if (strpos(mysql_result($rs,0,"file"),";")>0) {
				  $aaa=array_unique(explode(";",mysql_result($rs,0,"file")));
			foreach ($aaa as $key=>$a1)  
				if ($a1<>"") 
					echo "<a href='../fbfile/{$a1}' target='_blank'>{$a1}</a>","<br>";
			  } else 
              	echo "<a href=".mysql_result($rs,0,"file").">".mysql_result($rs,0,"file")."</a>"; ?>
				</td>
            </tr>
             <tr>
              <td height="27" class="STYLE13">备注</td>
              <td colspan="2" class="STYLE13">
                 <? echo mysql_result($rs,0,"memo");?>
              </td>
            </tr>
           
        </table>
    </td>
  </tr>
  <tr>
  <td>
  <? $rs1=mysql_query("select *,waixiefp+0 waixiefp1 from order_xjmxbj where xjmxid=$bh",$conn); 
  if (mysql_num_rows($rs1)>0) {
  	$zzfy=mysql_result($rs1,0,2);
	$banfei=mysql_result($rs1,0,3);
	$yingong=mysql_result($rs1,0,4);
	$waixie=mysql_result($rs1,0,5);
	$waixiefp=mysql_result($rs1,0,"waixiefp1");
	$filefy=mysql_result($rs1,0,7);
	$psfy=mysql_result($rs1,0,8);
	$qtfy=mysql_result($rs1,0,10);
	$tax=mysql_result($rs1,0,9);
	$dyzq=mysql_result($rs1,0,11);
	$sczq=mysql_result($rs1,0,12);
	$memo=mysql_result($rs1,0,13);
	$psfs=mysql_result($rs1,0,14);
	$minbj=mysql_result($rs1,0,15);
	$jybj=mysql_result($rs1,0,16);
  }?><font color="#FF0000">所有报价必须是询价单整体价格，不能是单价。</font>
   <table cellspacing="0" cellpadding="0" rules="all" border="1" id="gvOrder" style="border-width:0px;width:100%;border-collapse:collapse;border:1px solid #D3D3D3; border-bottom:1px solid #D3D3D3; font-size:12px">
	  <tbody><tr class="td_title" style="height:30px;">
			<th   align="center" scope="col">纸费</th>
			<th  align="center" scope="col">版费</th>
			<th align="center" scope="col">印工</th>
			<th  align="center" scope="col">外协</th>
            <th   align="center" scope="col">文件制作</th>
            <th  align="center" scope="col">送货</th>
            <th  align="center" scope="col">其他费用</th>
            <th  align="center" scope="col">发票税</th>
		</tr>
        <tr class="td_title" style="height:30px;">
			<td class="td_content" align="center"><input name="zzfy" type="text" value="<? echo is_numeric($zzfy)?$zzfy:0;?>" size="10" onChange="zjhj();"></td>
            <td class="td_content" align="center" ><input name="banfei" type="text" value="<? echo is_numeric($banfei)?$banfei:0;?>" size="10" onChange="zjhj();">
            </td>
            <td class="td_content" align="center"><input name="yingong" type="text" value="<? echo is_numeric($yingong)?$yingong:0;?>" size="10" onChange="zjhj();">
            </td>
            <td align="center" class="td_content" >
              <input name="waixie" type="text" value="<? echo is_numeric($waixie)?$waixie:0;?>" size="10" onChange="zjhj();">
              <input type="checkbox" name="waixiefp" id="waixiefp" value="<? echo $waixiefp;?>">含税
              </td>
            <td class="td_content" align="center" >
              <input name="filefy" type="text" value="<? echo is_numeric($filefy)?$filefy:0;?>" size="10" onChange="zjhj();">
            </td>
            <td class="td_content" align="center" >
              <input name="psfy" type="text" value="<? echo is_numeric($psfy)?$psfy:0;?>" size="10" onChange="zjhj();">
           </td>
            <td class="td_content" align="center">
              <input name="qtfy" type="text" value="<? echo is_numeric($qtfy)?$qtfy:0;?>" size="10" onChange="zjhj();">
           </td>
            <td class="td_content" align="center" >
              <input name="tax" type="text" value="<? echo is_numeric($tax)?$tax:0;?>" size="10" onChange="zjhj();">
           </td>
		</tr>
		<tr>
              <td height="33" colspan="4"><div align="left" class="STYLE14">
                
                打样周期及说明：<input name="dyzq" id="dyzq" type="text" size="30" maxlength="100" value="<? echo $dyzq;?>"><? if ($_GET["lx"]!="read") {?><br>　　　　　　　　<a href='void(0)' onclick='javascript:document.getElementById("dyzq").value="1天";return false;'>1天</a> <a href='void(0)' onclick='javascript:document.getElementById("dyzq").value="2天";return false;'>2天</a> <a href='void(0)' onclick='javascript:document.getElementById("dyzq").value="3天";return false;'>3天</a> <a href='void(0)' onclick='javascript:document.getElementById("dyzq").value="4天";return false;'>4天</a> <a href='void(0)' onclick='javascript:document.getElementById("dyzq").value="1周";return false;'>1周</a> <a href='void(0)' onclick='javascript:document.getElementById("dyzq").value="2周";return false;'>2周</a> <a href='void(0)' onclick='javascript:document.getElementById("dyzq").value="不确定";return false;'>不确定</a>
            <? }?>    
              </div></td>
              <td height="33" colspan="4"><div align="left" class="STYLE14">
                
                生产周期及说明：<input name="sczq" id="sczq" type="text" size="30" maxlength="100" value="<? echo $sczq;?>"><? if ($_GET["lx"]!="read") {?><br>　　　　　　　　
                <a href='javascript:void(0)' onclick='javascript:document.getElementById("sczq").value="1天";return false;'>1天</a> 
                <a href='javascript:void(0)' onclick='javascript:document.getElementById("sczq").value="2天";return false;'>2天</a> 
                <a href='javascript:void(0)' onclick='javascript:document.getElementById("sczq").value="3天";return false;'>3天</a> 
                <a href='javascript:void(0)' onclick='javascript:document.getElementById("sczq").value="4天";return false;'>4天</a> 
                <a href='javascript:void(0)' onclick='javascript:document.getElementById("sczq").value="1周";return false;'>1周</a> 
                <a href='javascript:void(0)' onclick='javascript:document.getElementById("sczq").value="2周";return false;'>2周</a> 
                <a href='javascript:void(0)' onclick='javascript:document.getElementById("sczq").value="3周";return false;'>3周</a>
                <? }?>
              </div></td>
            </tr>
          <tr>
              <td height="33" colspan="8"><div align="left" class="STYLE14">

                报价备注：<input name="memo" type="text" size="50" maxlength="200" value="<? echo $memo;?>">

              　　配送方式：
              <input name="psfs" id="psfs" type="text" size="20" maxlength="100" value="<? echo $psfs;?>">
              <? if ($_GET["lx"]!="read") {?><a href='void(0)' onclick='javascript:document.getElementById("psfs").value="物流配送";return false;'>物流配送</a> <a href='void(0)' onclick='javascript:document.getElementById("psfs").value="普通快递";return false;'>普通快递</a> <a href='void(0)' onclick='javascript:document.getElementById("psfs").value="顺丰快递";return false;'>顺丰快递</a> <a href='void(0)' onclick='javascript:document.getElementById("psfs").value="其他";return false;'>其他</a><? }?></div></td>
          </tr>
          <tr>
              <td height="33" colspan="2"><div align="left" class="STYLE14">
                
                总价：<span id="zj"><? $zbj=(is_numeric($zzfy)?$zzfy:0)+(is_numeric($banfei)?$banfei:0)+(is_numeric($yingong)?$yingong:0)+(is_numeric($waixie)?$waixie:0)+(is_numeric($filefy)?$filefy:0)+(is_numeric($psfy)?$psfy:0)+(is_numeric($qtfy)?$qtfy:0)+(is_numeric($tax)?$tax:0);echo $zbj?></span>元。 
                
              </div></td>
              <td height="33" colspan="2" class="STYLE14">建议售价：<span class="td_content">
                <input name="jybj" type="text" value="<? echo is_numeric($jybj)?$jybj:0;?>" size="12" onChange="document.getElementById('jy').innerHTML=Math.round(this.value/parseFloat(document.getElementById('zj').innerHTML)*10000)/100;">
              （<span id="jy"><? echo sprintf("%1\$.2f",$zbj>0?($jybj/$zbj*100):0);?></span>%）</span></td>
              <td height="33" colspan="4" class="STYLE14">最低售价：<span class="td_content">
                <input name="minbj" type="text" value="<? echo is_numeric($minbj)?$minbj:0;?>" size="12" onChange="document.getElementById('minb').innerHTML=Math.round(this.value/parseFloat(document.getElementById('zj').innerHTML)*10000/100;">
              （<span id="minb"><? echo sprintf("%1\$.2f",$zbj>0?($minbj/$zbj*100):0);?></span>%）,系统默认建议根据情况更改</span></td>
            </tr>
         <tr>
              <td height="33" colspan="8"><div align="center">
                <label>
                <? if ($_GET["lx"]!="read") {?><input type="submit" name="Submit" value="保存报价"> <? }?>
                </label>
              </div></td>
          </tr>
	</tbody></table>
  </td>
  </tr>
</table>
<br>需要填入纸费、版费等明细数据，直接填建议售价会导致报价不成功。
</form>
</body>
</html>
<? 
$rs=null;
unset($rs);
$rss=null;
unset($rss);
?>