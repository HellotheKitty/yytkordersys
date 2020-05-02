<? require("../includes/conn.php");?>
<? session_start();
if ($_SESSION["OK"]<>"OK") { 
echo "<script language=JavaScript>{window.location.href='../error.php';}</script>";
exit; 
}?>
<?

$bh=$_GET["ddh"];
if ($_GET["mxid"]<>"") {
	$rs=mysql_query("select *,sfdy+0 sfdy0 from order_xjmx where id=".$_GET["mxid"],$conn);
	$cpms=mysql_result($rs,0,"cpms");
	$chicun=mysql_result($rs,0,"chicun");
	$chicun2=mysql_result($rs,0,"chicun2");
	$sl=mysql_result($rs,0,"sl");
	$zhongliang=mysql_result($rs,0,"zhongliang");
	$sfdy=mysql_result($rs,0,"sfdy0");
	$zz=mysql_result($rs,0,"zz");
	$tsgy=mysql_result($rs,0,"tsgy");
	$scfs=mysql_result($rs,0,"scfs");
	$gfile=mysql_result($rs,0,"file");
	$memo=mysql_result($rs,0,"memo");
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>询价单明细</title>
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
<table width="100%" border="1" cellpadding="4" cellspacing="1" bordercolor="#000099" bgcolor="f6f6f6" >
  <tr>
<td height="222" valign="top"><form action="order_xj_mxdj_save.php" method="post" ENCTYPE="multipart/form-data" name="form1" id="form1" >
      <label><span class="STYLE8 STYLE13">
          </span></label>
      <table width="442" height="277" border="0" align="center">
            <tr>
              <td height="27" class="STYLE13">询价号</td>
              <td width="134" class="STYLE13"> <? echo $bh?> </td>
              <input type="hidden" name="id" value="<? echo $bh?>" />
              <input type="hidden" name="mxid" value="<? echo $_GET["mxid"]?>" />
              <td width="201" align="right" class="STYLE13">&nbsp;</td>
            </tr>
     
            <tr>
              <td width="51" height="27" class="STYLE13">产品</td>
              <td colspan="2" class="STYLE13">
                <input  type="text" size=50 name="cpms" id="cpms" value="<? echo $cpms;?>" >
                <br>
                <a href='void(0)' onclick='javascript:document.getElementById("cpms").value=document.getElementById("cpms").value+"单张印刷品(单页、贺卡、信纸、便签);";return false;'>单张印刷品(单页、贺卡、信纸、便签)</a> <a href='void(0)' onclick='javascript:document.getElementById("cpms").value=document.getElementById("cpms").value+"封套;";return false;'>封套</a> <a href='void(0)' onclick='javascript:document.getElementById("cpms").value=document.getElementById("cpms").value+"样本画册;";return false;'>样本画册</a> <a href='void(0)' onclick='javascript:document.getElementById("cpms").value=document.getElementById("cpms").value+"手提袋;";return false;'>手提袋</a> <a href='void(0)' onclick='javascript:document.getElementById("cpms").value=document.getElementById("cpms").value+"信封;";return false;'>信封</a> <a href='void(0)' onclick='javascript:document.getElementById("cpms").value=document.getElementById("cpms").value+"其他;";return false;'>其他</a>
              </td>
            </tr>
            <tr>
              <td width="51" height="27" class="STYLE13">成品尺寸</td>
              <td class="STYLE13">
                <input  type="text" size=16 name="chicun" id="chicun" value="<? echo $chicun;?>" >
              </td>
            <td class="STYLE13">展开尺寸
              <input  type="text" size=16 name="chicun2" id="chicun2" value="<? echo $chicun2;?>" ></td>
            </tr>
            <tr>
              <td width="51" height="27" class="STYLE13">数量</td>
              <td class="STYLE13"><input  type="text" size=3 name="sl" id="sl" value="<? echo $sl==""?1:$sl;?>" >
                <input type="checkbox" name="sfdy" id="sfdy" value="1" <? if ($sfdy==1) echo "checked";?>>打样</td>
            <td class="STYLE13"> 预估重量
			  <input  type="text" size=8 name="zhongliang" id="zhongliang" value="<? echo $zhongliang==""?0:$zhongliang;?>" >公斤</td>
            </tr>
            <tr>
              <td height="27" class="STYLE13">纸张材质</td>
              <td colspan="2" class="STYLE13">
              <input  type="text" size=50 name="zz" id="zz" value="<? echo $zz;?>" >
              </td>
            </tr>
            <tr>
              <td height="27" class="STYLE13">工艺要求</td>
              <td colspan="2" class="STYLE13">
                <input  type="text" size=50 name="tsgy" id="tsgy" value="<? echo $tsgy;?>" >
                <br><a href='void(0)' onclick='javascript:document.getElementById("tsgy").value=document.getElementById("tsgy").value+"UV;";return false;'>UV</a> <a href='void(0)' onclick='javascript:document.getElementById("tsgy").value=document.getElementById("tsgy").value+"圆角;";return false;'>圆角</a> <a href='void(0)' onclick='javascript:document.getElementById("tsgy").value=document.getElementById("tsgy").value+"烫金;";return false;'>烫金</a> <a href='void(0)' onclick='javascript:document.getElementById("tsgy").value=document.getElementById("tsgy").value+"烫银;";return false;'>烫银</a> <a href='void(0)' onclick='javascript:document.getElementById("tsgy").value=document.getElementById("tsgy").value+"突字;";return false;'>突字</a> <a href='void(0)' onclick='javascript:document.getElementById("tsgy").value=document.getElementById("tsgy").value+"凹凸;";return false;'>凹凸</a> <a href='void(0)' onclick='javascript:document.getElementById("tsgy").value=document.getElementById("tsgy").value+"压线;";return false;'>压线</a>
              </td>
            </tr>
            <tr>
              <td height="27" class="STYLE13">生产方式</td>
              <td colspan="2" class="STYLE13">
                <input  type="text" size=50 name="scfs" id="scfs" value="<? echo $scfs;?>" >
              </td>
            </tr>
            <tr>
              <td height="27" class="STYLE13">附属文件</td>
              <input type="hidden" name="ffile" value="<? echo $gfile;?>"/>
              <td colspan="2" class="STYLE13"><INPUT TYPE="FILE" NAME="gfile" SIZE="34" MAXLENGTH="80"></td>
            </tr>
             <tr>
              <td height="27" class="STYLE13">备注</td>
              <td colspan="2" class="STYLE13">
                <input  type="text" size=50 name="memo" id="memo" value="<? echo $memo;?>" ><br>如有样张或实物寄出，请注明快递单号等信息。</td>
            </tr>
            <tr>
              <td height="33" colspan="3"><div align="center">
                <label>
                <input type="submit" name="Submit" value="提 交"> 
                </label>
              </div></td>
            </tr>
        </table>
         
</form>
    </td>
  </tr>
</table>
</body>
</html>
<? 
$rs=null;
unset($rs);
$rss=null;
unset($rss);
?>