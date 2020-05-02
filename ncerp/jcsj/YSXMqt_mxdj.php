<? require("../includes/conn.php");?>
<? session_start();
if ($_SESSION["OK"]<>"OK") {
echo "<script language=JavaScript>{window.location.href='../error.php';}</script>";
exit;
}?>
<?
$bh=$_GET["ddh"];
if ($_GET["mxid"]<>"") {
	$rs=mysql_query("select * from order_mxqt where id=".$_GET["mxid"],$conn);
	$bh=mysql_result($rs,0,"ddh");
	$cpms=mysql_result($rs,0,"cpms");
	$chicun=mysql_result($rs,0,"chicun");
	$sl=mysql_result($rs,0,"sl");
	$sfdy=mysql_result($rs,0,"sfdy");
	$zz=mysql_result($rs,0,"zz");
	$tsgy=mysql_result($rs,0,"tsgy");
	$scfs=mysql_result($rs,0,"scfs");
	$gfile=mysql_result($rs,0,"file");
	$zj=mysql_result($rs,0,"zj");
	$zhongliang=mysql_result($rs,0,"zhongliang");
	$memo=mysql_result($rs,0,"memo");
	$psfs=mysql_result($rs,0,"psfs");
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>新建订单</title>
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
<td height="222" valign="top"><form action="YSXMqt_mxdj_save.php" method="post" ENCTYPE="multipart/form-data" name="form1" id="form1" onSubmit="return checkForm()">
      <label><span class="STYLE8 STYLE13">
          </span></label>
      <table width="442" height="277" border="0" align="center">
            <tr>
              <td height="27" class="STYLE13">订单号</td>
              <td width="134" class="STYLE13"> <? echo $bh?> </td>
              <input type="hidden" name="ddh" value="<? echo $bh?>" />
              <input type="hidden" name="id" value="<? echo $_GET["mxid"]?>" />
              <td width="201" align="right" class="STYLE13">&nbsp;</td>
            </tr>

            <tr>
              <td width="51" height="27" class="STYLE13">产品</td>
              <td colspan="2" class="STYLE13">
                <input  type="text" size=50 name="cpms" id="cpms" value="<? echo $cpms;?>" >
              </td>
            </tr>
            <tr>
              <td width="51" height="27" class="STYLE13">尺寸</td>
              <td class="STYLE13">
                <input  type="text" size=20 name="chicun" id="chicun" value="<? echo $chicun;?>" >
              </td>
            <td class="STYLE13">数量
                <input  type="text" size=3 name="sl" id="sl" value="<? echo $sl==""?1:$sl;?>" >
                　　
                <input type="checkbox" name="sfdy" id="sfdy" value="1" <? if($sfdy=="1") echo "checked";?>>
               打样
             </td>
            </tr>
            <tr>
              <td height="27" class="STYLE13">纸张</td>
              <td colspan="2" class="STYLE13">
              <input  type="text" size=50 name="zz" id="zz" value="<? echo $zz;?>" >
              </td>
            </tr>
            <tr>
              <td height="27" class="STYLE13">工艺要求</td>
              <td colspan="2" class="STYLE13">
                <input  type="text" size=50 name="tsgy" id="tsgy" value="<? echo $tsgy;?>" >
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
              <td colspan="2" class="STYLE13"><INPUT TYPE="FILE" NAME="gfile" SIZE="34" MAXLENGTH="80" value="<? echo $gfile;?>"></td>
            </tr>
              <tr>
              <td height="27" class="STYLE13">价格</td>
              <td class="STYLE13">
                <input  type="text" size=11 name="zj" id="zj" value="<? echo $zj==""?0.00:$zj;?>" >
                元
              </td>
              <td class="STYLE13">重量
                <input  type="text" size=11 name="zhongliang" id="zhongliang" value="<? echo $zhongliang==""?0.00:$zhongliang;?>" >
                公斤</td>
            </tr>
             <tr>
              <td height="27" class="STYLE13">配送方式</td>
              <td colspan="2" class="STYLE13">
                <input  type="text" size=10 name="psfs" id="psfs" value="<? echo $psfs;?>" ><a href='void(0)' onclick='javascript:document.getElementById("psfs").value="物流配送";return false;'>物流配送</a> <a href='void(0)' onclick='javascript:document.getElementById("psfs").value="普通快递";return false;'>普通快递</a> <a href='void(0)' onclick='javascript:document.getElementById("psfs").value="顺丰快递";return false;'>顺丰快递</a> <a href='void(0)' onclick='javascript:document.getElementById("psfs").value="其他";return false;'>其他</a>
              </td>
            </tr>
             <tr>
              <td height="27" class="STYLE13">备注</td>
              <td colspan="2" class="STYLE13">
                <input  type="text" size=50 name="memo" id="memo" value="<? echo $memo;?>" >
              </td>
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