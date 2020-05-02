<? require("../includes/conn.php");?>
<? session_start();
if ($_SESSION["OK"]<>"OK") { 
echo "<script language=JavaScript>{window.location.href='../error.php';}</script>";
exit; 
}?>
<?
if ($_POST["Submit"]<>"") {
	mysql_query("update order_mainqt set zzfy=".$_POST["zzfy"].",banfei=".$_POST["banfei"].",yingong=".$_POST["yingong"].",waixie=".$_POST["waixie"].",waixiedw='".$_POST["waixiedw"]."',filefy=".$_POST["filefy"].",psfy=".$_POST["psfy"].",tax=".$_POST["tax"].",qtfy=".$_POST["qtfy"].",sjpsfs='".$_POST["sjpsfs"]."',sjpssj='".$_POST["sjpssj"]."',tbsj=now() where ddh='".$_POST["ddh"]."'");
	echo "<script>alert('保存完成');</script>";
}

if ($_GET["wcddh"]<>"") {  //订单完成
	mysql_query("update order_mainqt set state='订单完成' where ddh='".$_GET["wcddh"]."'",$conn);
	echo "<script>alert('订单完成');window.opener.location.reload();window.close();</script>";
}
$bh=$_GET["ddh"];
$rs=mysql_query("select * from order_mainqt where ddh='".$bh."' ",$conn);

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>非标订单生产</title>
<script language="JavaScript" src="../htgl/Mymodify.js"></script>
<SCRIPT language=JavaScript src="../form.js"></SCRIPT>
<SCRIPT language=JavaScript>
function checkForm(){
	var tmpFrm = document.forms[0];
	if (tmpFrm.waixie.value>0 && tmpFrm.waixiedw.value=="") {alert("外协单位不能为空！");return false;}
	return true; }
	
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
}
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
<form action="" method="post"  name="form1" id="form1" onSubmit="return checkForm();" >
<input type="hidden" name="ddh" value="<? echo $bh?>" />
<table width="100%" border="1" cellpadding="4" cellspacing="1" bordercolor="#000099" bgcolor="f6f6f6" >
  <tr>
<td height="222" valign="top">
      <label><span class="STYLE8 STYLE13">
          </span></label>
      <table width="80%"   border="1" align="center" cellspacing="0" cellpadding="0" style="border-width:0px;border-collapse:collapse;border:1px solid #D3D3D3; border-bottom:1px solid #D3D3D3; font-size:12px">
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
              <td colspan="2" class="STYLE13"><? echo "配送：",mysql_result($rs,0,"psfs"),"&nbsp;&nbsp;收货人：",mysql_result($rs,0,"shr"),"&nbsp;&nbsp;电话：",mysql_result($rs,0,"shdh"),"<br>地址：",mysql_result($rs,0,"province"),mysql_result($rs,0,"city"),mysql_result($rs,0,"county"),mysql_result($rs,0,"shdz");?></td>
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
              <td height="24" align="right"></td>
              <td height="24" align="left"><? if (mysql_result($rs,0,"xjdid")!=0) echo "询价单ID:",mysql_result($rs,0,"xjdid"),"<a href='javascript:void(0)' onclick='javascript:window.open(\"order_xj_show.php?ddh=".mysql_result($rs,0,"xjdid")."\",\"XJD\",\"height=600px,width=920px,top=150px,left=280px,location=no,menubar=no,resizable=no,scrollbars=yes,status=no,titlebar=no,toolbar=no,directories=no\");return false;'>[查看]</a>";?></td>
              <td height="24" align="right"><a href="javascript:void(0)" onClick="javascript:window.open('YSXMqt_show.php?ddh=<? echo $bh;?>','OrderDetail','height=600px,width=920px,top=100px,left=200px,location=no,menubar=no,resizable=no,scrollbars=yes,status=no,titlebar=no,toolbar=no,directories=no');return false;"  class="a1">查看订单详情</a></td>
            </tr>
        </table>
    </td>
  </tr>
  <tr>
  <td>
  <? $rs1=mysql_query("select * from order_mainqt where ddh='$bh'",$conn); 
  if (mysql_num_rows($rs1)>0) {
  	$zzfy=mysql_result($rs1,0,"zzfy");
	$banfei=mysql_result($rs1,0,"banfei");
	$yingong=mysql_result($rs1,0,"yingong");
	$waixie=mysql_result($rs1,0,"waixie");
	$waixiedw=mysql_result($rs1,0,"waixiedw");
	$filefy=mysql_result($rs1,0,"filefy");
	$psfy=mysql_result($rs1,0,"psfy");
	$qtfy=mysql_result($rs1,0,"qtfy");
	$tax=mysql_result($rs1,0,"tax");
	$sjpsfs=mysql_result($rs1,0,"sjpsfs");
	$sjpssj=mysql_result($rs1,0,"sjpssj");
	if ($sjpssj=="") $sjpssj=date("Y-m-d h:i:s");
  } ?>
  订单生产执行情况（实际发生情况）：
   <table cellspacing="0" cellpadding="0" rules="all" border="0" id="gvOrder" style="border-width:0px;width:100%;border-collapse:collapse;border:1px solid #D3D3D3; border-bottom:1px solid #D3D3D3; font-size:12px">
	  <tbody><tr class="td_title" style="height:30px;">
			<th   align="center" scope="col">纸费</th>
			<th  align="center" scope="col">版费</th>
			<th align="center" scope="col">印工</th>
			<th  align="center" scope="col">外协费</th>
			<th  align="center" scope="col">外协单位[<a href='Waixiedw_list.php';">管理</a>]</th>
            <th  align="center" scope="col">文件制作</th>
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
              <input name="waixie" type="text" value="<? echo is_numeric($waixie)?$waixie:0;?>" size="10" onChange="zjhj();"></td>
            <td align="center" class="td_content" > 
            <select name="waixiedw">
            	<option value=""></option>
                <? $rss=mysql_query("select id,khmc from base_waixiedw order by khmc",$conn);
				for ($i=0;$i<mysql_num_rows($rss);$i++) {
					if ($waixiedw==mysql_result($rss,$i,1))
						echo "<option value='".mysql_result($rss,$i,1)."' selected>".mysql_result($rss,$i,1)."</option>";
					else
						echo "<option value='".mysql_result($rss,$i,1)."'>".mysql_result($rss,$i,1)."</option>";
				}
				?>
                </select></td>
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
              <td height="33" colspan="9"><div align="left">

                配送方式：<input name="sjpsfs" id="sjpsfs" type="text" size="20" maxlength="200" value="<? echo $sjpsfs;?>"><a href='void(0)' onclick='javascript:document.getElementById("sjpsfs").value="物流配送";return false;'>物流配送</a> <a href='void(0)' onclick='javascript:document.getElementById("sjpsfs").value="普通快递";return false;'>普通快递</a> <a href='void(0)' onclick='javascript:document.getElementById("sjpsfs").value="顺丰快递";return false;'>顺丰快递</a> <a href='void(0)' onclick='javascript:document.getElementById("sjpsfs").value="其他";return false;'>其他</a>&nbsp;&nbsp;
                发货时间：<input name="sjpssj" type="text" size="15" maxlength="15" value="<? echo $sjpssj;?>">

                <span class="page1">
                <input type="button" onClick="window.open('YSXMqt_sh_p.php?ddh=<? echo mysql_result($rs,0,"ddh");?>','new');" value="生成送货单" />
                </span></div></td>
          </tr>
          <tr>
              <td height="33" colspan="9"><div align="left">

                总价：<span id="zj"><? echo (is_numeric($zzfy)?$zzfy:0)+(is_numeric($banfei)?$banfei:0)+(is_numeric($yingong)?$yingong:0)+(is_numeric($waixie)?$waixie:0)+(is_numeric($filefy)?$filefy:0)+(is_numeric($psfy)?$psfy:0)+(is_numeric($qtfy)?$qtfy:0)+(is_numeric($tax)?$tax:0)?></span>元。 

              </div></td>
          </tr>
         <tr>
              <td height="33" colspan="9"><div align="center">
              <? if (mysql_result($rs,0,"state")!="订单完成") {?>
                <label>
                <input type="submit" name="Submit" value=" 保 存 "> 
                </label>
                <? if ((is_numeric($zzfy)?$zzfy:0)+(is_numeric($banfei)?$banfei:0)+(is_numeric($yingong)?$yingong:0)+(is_numeric($waixie)?$waixie:0)+(is_numeric($filefy)?$filefy:0)+(is_numeric($psfy)?$psfy:0)+(is_numeric($qtfy)?$qtfy:0)+(is_numeric($tax)?$tax:0)>0) {?>
                　　<input type="button" name="wcdd" onClick="window.location.href='YSXMqt_zx.php?wcddh=<? echo $bh?>';" value=" 订单完成 "> <? } else echo "　　订单待完成，完成订单销售才可以打印送货单";
				}?>
              </div></td>
          </tr>
	</tbody></table>
  </td>
  </tr>
</table>
</form>
</body>
</html>
<? 
$rs=null;
unset($rs);
$rss=null;
unset($rss);
?>