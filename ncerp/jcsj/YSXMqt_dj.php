<? require("../includes/conn.php");?>
<? session_start();
if ($_SESSION["OK"]<>"OK") { 
echo "<script language=JavaScript>{window.location.href='../error.php';}</script>";
exit; 
}?>
<?

$bh=date("ymdhis",time()).rand(10,99)."5";

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
<td height="222" valign="top"><form action="YSXMqt_dj_save.php" method="post" name="form1" id="form1" onSubmit="return checkForm()">
      <label><span class="STYLE8 STYLE13">
          </span></label><br>
      <table width="442" height="169" border="0" align="center">
            <tr>
              <td height="34" class="STYLE13">销售编号</td>
              <td width="154"><? echo $_GET["xsbh"];?></td>
              <input type="hidden" name="xsbh" value="<? echo $_GET["xsbh"];?>" />
              <td width="150" align="right" class="STYLE13">&nbsp;</td>
            </tr>
            <tr>
              <td height="34" class="STYLE13">订单号</td>
              <td width="154"><span class="field">
                <input  id="bh" type="text" size=15 name="bh" value="<? echo $bh?>" <? if ($_GET["lx"]<="0") {echo "readonly='readonly'";} ?>>
              </span></td>
              <td width="150" align="right" class="STYLE13">建议生产地：
                <label for="scjd"></label>
                <select name="scjd" id="scjd">
                  <option value="上海">上海</option>
                  <option value="北京">北京</option>
                  <option value="杭州">杭州</option>
                  <option value="广州">广州</option>
              </select></td>
            </tr>
            <tr>
              <td height="37" class="STYLE13">客户名称</td><? $sql="select khbh,concat(khbh,'-',khmc) from base_kh where loginuserid='".$_SESSION["USERBH"]."' order by khbh";?>
              <td colspan="2"><select name="mc">
                <? $rss=mysql_query("select id,khmc from base_kh where xsbh='".$_GET["xsbh"]."' order by khmc",$conn);
				for ($i=0;$i<mysql_num_rows($rss);$i++) {
					echo "<option value='".mysql_result($rss,$i,1)."'>".mysql_result($rss,$i,1)."</option>";
				}
				?>
                </select></td>
            </tr>
            <tr>
              <td height="37" class="STYLE13">询价单</td>
              <? $rs1=mysql_query("select id,khmc,zje from order_xj where xsbh='".$_GET["xsbh"]."' and zje>0",$conn);?>
              <td colspan="2"><select name="xjdbh">
                <option value="0">无</option>
                <? for ($i=0;$i<mysql_num_rows($rs1);$i++) {?>
                <option value="<? echo mysql_result($rs1,$i,0);?>"><? echo mysql_result($rs1,$i,1),"金额：",mysql_result($rs1,$i,2);?></option>
                <? }?>
              </select></td>
            </tr>
            <tr>
              <td width="60" height="35" class="STYLE13">开票要求</td>
              <td colspan="2"><span class="field">
                <input  type="text" size=50 name="kpyq" id="kpyq" value="" >
              </span></td>
            </tr>
            <tr>
              <td width="60" height="35" class="STYLE13">包装要求</td>
              <td colspan="2"><span class="field">
                <input  type="text" size=50 name="bzyq" id="bzyq" value="" >
              </span></td>
            </tr>
            <tr>
              <td width="60" height="35" class="STYLE13">订单说明</td>
              <td colspan="2"><span class="field">
                <input  type="text" size=50 name="sm" id="sm" value="" >
              </span></td>
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