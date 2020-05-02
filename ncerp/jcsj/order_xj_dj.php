<? require("../includes/conn.php");?>
<? session_start();
if ($_SESSION["OK"]<>"OK") { 
echo "<script language=JavaScript>{window.location.href='../error.php';}</script>";
exit; 
}?>
<?

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>新建询价单</title>
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
<td height="222" valign="top"><form action="order_xj_dj_save.php" method="post" name="form1" id="form1" ENCTYPE="multipart/form-data" >
      <label><span class="STYLE8 STYLE13">
          </span></label><br>
      <table width="542" height="169" border="0" align="center">
            <tr>
              <td height="34" class="STYLE13" width="16%">询价人</td>
              <td width="179"><span class="field">
                <input  id="bh" type="text" size=15 name="bh" value="<? echo $_SESSION["USERBH"]?>" readonly='readonly'>
              </span></td>
              <td width="198" align="left" class="STYLE13"><? echo date("Y-m-d H:i:s");?></td>
            </tr>
			<tr>
              <td height="34" class="STYLE13" width="16%">客户名称</td>
              <td colspan="2" class="STYLE13"><span class="field">
                <select name="khmc">
                <? $rss=mysql_query("select id,khmc from base_kh where xsbh='".$_SESSION["USERBH"]."' order by khmc",$conn);
				for ($i=0;$i<mysql_num_rows($rss);$i++) {
					echo "<option value='".mysql_result($rss,$i,1)."'>".mysql_result($rss,$i,1)."</option>";
				}
				?>
                </select>
              <a href='void(0)' onClick="opener.document.location='KH_list.php';window.close();">客户管理</a></span></td>
            </tr>
            <tr>
              <td  height="35" class="STYLE13">产品描述</td>
              <td colspan="2" class="STYLE13"><p>
                <input name="prod" type="text" id="tsgy" size="50"><br>
                <a href='void(0)' onclick='javascript:document.getElementById("tsgy").value=document.getElementById("tsgy").value+"单张印刷品(单页、贺卡、信纸、便签);";return false;'>单张印刷品(单页、贺卡、信纸、便签)</a> <a href='void(0)' onclick='javascript:document.getElementById("tsgy").value=document.getElementById("tsgy").value+"封套;";return false;'>封套</a> <a href='void(0)' onclick='javascript:document.getElementById("tsgy").value=document.getElementById("tsgy").value+"样本画册;";return false;'>样本画册</a> <a href='void(0)' onclick='javascript:document.getElementById("tsgy").value=document.getElementById("tsgy").value+"手提袋;";return false;'>手提袋</a> <a href='void(0)' onclick='javascript:document.getElementById("tsgy").value=document.getElementById("tsgy").value+"信封;";return false;'>信封</a> <a href='void(0)' onclick='javascript:document.getElementById("tsgy").value=document.getElementById("tsgy").value+"其他;";return false;'>其他</a></p></td>
            </tr>
            <tr>
              <td height="37" class="STYLE13">是否开票</td>
              <td colspan="2" class="STYLE13"><input type="radio" name="fp" id="fp" value="1">是 <input name="fp" type="radio" id="fp" value="0" checked>否</td>
            </tr>
             <tr>
              <td height="37" class="STYLE13">包装要求</td>
              <td colspan="2" class="STYLE13"><textarea name="bzyq" cols="50" rows="3"></textarea></td>
            </tr>
            <tr>
              <td height="37" class="STYLE13">配送要求</td>
              <td colspan="2" class="STYLE13"><textarea name="psyq" cols="50" rows="3"></textarea></td>
            </tr>
            <tr>
              <td height="37" class="STYLE13">询价备注</td>
              <td colspan="2" class="STYLE13"><textarea name="memo" cols="50" rows="3"></textarea></td>
            </tr>
            <tr>
              <td height="37" class="STYLE13">附件文件</td>
              <td colspan="2" class="STYLE13"><input name="fjfile" type="file">
            </tr>
            <tr>
              <td height="53" colspan="3"><div align="center">
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