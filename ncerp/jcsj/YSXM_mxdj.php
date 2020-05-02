<? require("../includes/conn.php");?>
<? session_start();
if ($_SESSION["OK"]<>"OK") { 
echo "<script language=JavaScript>{window.location.href='../error.php';}</script>";
exit; 
}?>
<?

$bh=$_GET["ddh"];

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
<td height="222" valign="top"><form action="YSXM_mxdj_save.php" method="post" ENCTYPE="multipart/form-data" name="form1" id="form1" onSubmit="return checkForm()">
      <label><span class="STYLE8 STYLE13">
          </span></label>
      <table width="442" height="277" border="0" align="center">
            <tr>
              <td height="27" class="STYLE13">订单号</td>
              <td colspan="2"> <? echo $bh?> </td>
              <input type="hidden" name="ddh" value="<? echo $bh?>" />
              <td width="201" align="right" class="STYLE13">&nbsp;</td>
            </tr>
            <tr>
              <td width="51" height="27" class="STYLE13">姓名</td>
              <td width="134"><span class="field">
                <input  type="text" size=10 name="xm" id="mc" value="" >
              </span></td>
              <td width="38" class="STYLE13">纸张</td>
              <td class="STYLE13">
                <select name="zz" id="zz">
              <? $rszz=mysql_query("select zzmc,jcjg from base_zz order by jcjg",$conn);
                 for($i=0;$i<mysql_num_rows($rszz);$i++) {?>
                  <option value="<? echo mysql_result($rszz,$i,"zzmc");?>"><? echo mysql_result($rszz,$i,"zzmc")."(".mysql_result($rszz,$i,"jcjg").")"?></option>
                <? }?>
              </select></td>
            </tr>
            <tr>
              <td width="51" height="27" class="STYLE13">工艺</td>
              <td width="134"><span class="field">
                <input  type="text" size=10 name="gy" id="mc" value="" >
              </span></td>
              <td width="38" class="STYLE13">名片</td>
              <td class="STYLE13"><label for="select">宽 <span class="field">
              <input  type="text" size=2 name="mpk" id="sl2" value="90" >
              </span>高<span class="field">
              <input  type="text" size=2 name="mpg" id="sl3" value="54" >
              </span></label></td>
            </tr>
            <tr>
              <td width="51" height="27" class="STYLE13">数量</td>
              <td width="134"><span class="STYLE13"><span class="field">
                <input  type="text" size=3 name="sl" id="sl" value="1" >
              </span></span></td>
              <td width="38" class="STYLE13"><span class="field">价格</span></td>
              <td class="STYLE13"><label for="select"></label>
                <span class="field">
                <input  type="text" size=10 name="jg" id="jg" value="0" >
                </span></td>
            </tr>
            <tr>
              <td height="27" class="STYLE13">正面文件</td>
              <td colspan="3" class="STYLE13"><INPUT TYPE="FILE" NAME="zfile" SIZE="34" MAXLENGTH="80">
                <br><input name="cb" type="checkbox" id="cb" value="1" checked>
                正反面同一文件
               </td>
            </tr>
            <tr>
              <td height="27" class="STYLE13">反面文件</td>
              <td colspan="3"><INPUT TYPE="FILE" NAME="ffile" SIZE="34" MAXLENGTH="80"></td>
            </tr>
            <tr>
              <td height="27" class="STYLE13">工艺文件</td>
              <td colspan="3"><INPUT TYPE="FILE" NAME="gfile" SIZE="34" MAXLENGTH="80"></td>
            </tr>
            <tr>
              <td height="33" colspan="4"><div align="center">
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