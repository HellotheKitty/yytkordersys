<? 
session_start();
header("Content-Type:text/html;charset=UTF-8");
require("../../inc/conn.php");
if ($_SESSION["OK"]<>"OK") { 
echo "<script language=JavaScript>{window.location.href='../error.php';}</script>";
exit; }?>

<? 
if ($_GET["DELID"]<>"") mysql_query("delete from base_kh where id=".$_GET["DELID"]);
$tj="xsbh='".$_SESSION["YKOAUSER"]."'";
if ($_GET["zdm"]<>"") {$tj=$tj." and (khmc like '%".$_GET["zdm"]."%' or mpzh like '%".$_GET["zdm"]."%' or lxr like '%".$_GET["zdm"]."%')";}
$rs=mysql_query("select * from base_kh where $tj order by id",$conn); 
//echo "select * from base_kh where $tj order by id";


//分页
if ($tj<>"1=1") {$page_num=mysql_num_rows($rs)+1;} else {$page_num=15;}     //每页行数
$page_no=$_GET["pno"];     //当前页
if ($page_no=="") {$page_no=1;}
$page_f=$page_num*($page_no -1);   //开始行
$page_e=$page_f+$page_num;			//结束行
if ($page_e>mysql_num_rows($rs)) {$page_e=mysql_num_rows($rs);}
$page_t=ceil(mysql_num_rows($rs) / $page_num);  //总页数
//分页
?>
<html><head><title></title>
<META http-equiv=Content-Type content="text/html; charset=utf-8">
<LINK href="../../css/content.css" type=text/css rel=stylesheet>
<SCRIPT language=JavaScript src="../../js/form.js"></SCRIPT>
<SCRIPT language=JavaScript>
function checkForm(){
    var charBag = "0123456789";
	if (!checkNotNull(form1.mpzh, "客户编号")) return false;
	if (!checkNotNull(form1.khmc, "客户名称")) return false;
	return true; }
</SCRIPT>

<meta content="MSHTML 6.00.3790.1830" name=GENERATOR>
<style type="text/css">
<!--
.STYLE1 {color: #000000}
-->
</style>
<style TYPE="text/css">
<!--
A:link{text-decoration:none}
A:visited{text-decoration:none}
A:hover {color: #EF6D21;text-decoration:underline}
.STYLE4 {color: #FF0000}
.STYLE13 {font-size: 12px}
 -->
</style>
<script language="JavaScript">
<!--
function suredo(src,q)
    {
      var ret;
      ret = confirm(q);
      if(ret!=false) window.location=src;
    }
//-->
</script>    
</head>
<body text=#000000 bgColor=#ffffff topMargin=0>
<form name="form1" method="post" action="" onSubmit="return checkForm()">
<table cellSpacing=0 cellPadding=0 width="100%" border=0>
  <tbody>
  <tr>
    <td width="57%" height=13 class=guide style="background-image: url('../images/main_guide_bg2.gif')">
	<img src="../images/guide.gif" 
      align=absMiddle>客户信息列表</td>
    <td width="43%" align=right class=guide style="background-image: url('../images/main_guide_bg2.gif')">
	<img
  src="../images/main_r.gif"></td>
</tr></tbody></table><br>
<? if ($_GET["ID"]=="") {$id="";$zdm="";}
else
{ $id=$_GET["ID"];
$rss=mysql_query("select * from base_kh where id='".$id."'",$conn);
$zdm=mysql_result($rss,0,"khmc");
$zmc=mysql_result($rss,0,"lxr");
$gsmc=mysql_result($rss,0,"lxdh");
$gwmc=mysql_result($rss,0,"lxdz");
$gdzk=mysql_result($rss,0,"gdzk");
$kpsm=mysql_result($rss,0,"kp_sm");
$memo=mysql_result($rss,0,"memo");
$mpzh=mysql_result($rss,0,"mpzh");
$qq=mysql_result($rss,0,"qq");
$hyjb=mysql_result($rss,0,"hyjb");
$jg=mysql_result($rss,0,"jg");

}?>
<input name="ID" id="zdm" type="hidden" class="STYLE13" value="<? echo $id?>" size=25>
<input name="KHMCOLD" id="khmcold" type="hidden" class="STYLE13" value="<? echo $zdm?>" size=25>

      查找：
      <input name="khmc" id="khmc" type="text" class="STYLE13" value="" size=25><input name="Submit" type="button" class="button" value="查 询" onClick="javascript:window.location.href='<? echo $_SERVER["PHP_SELF"]?>?zdm='+form1.khmc.value">
     
      <input name="b1" type="button" class="button" value="新增" onClick='javascript:window.open("KH_add.php", "HT_dhdj", "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=yes,width=850,height=410,left=300,top=100")'>
      </td>
  </tr>

<table class=maintable cellSpacing=1 cellPadding=1 width="100%" border=0>
  <tbody>
  <tr>
    <td class=head style="background-image: url('../images/nabg1.gif')" width="63">编号</td>
    <td class=head style="background-image: url('../images/nabg1.gif')" width="103">客户名称</td>
    <td class=head style="background-image: url('../images/nabg1.gif')" width="103">级别</td>
    <td class=head style="background-image: url('../images/nabg1.gif')" width="103">打印价格</td>
    <td class=head style="background-image: url('../images/nabg1.gif')" width="157">联系人</td>
    <td class=head style="background-image: url('../images/nabg1.gif')" width="158">联系电话</td>
    <td height="26" colspan="1" class=head style="background-image: url('../images/nabg1.gif')">联系地址</td>
    </tr>
 <? for($i=$page_f;$i<$page_e;$i++){ ?>
  <tr>
    <td width="63"><? echo mysql_result($rs,$i,"mpzh")?></td>
    <td width="103"><? echo mysql_result($rs,$i,"khmc")?></td>
    <td width="103"><? echo mysql_result($rs,$i,"hyjb")?></td>
    <td width="103"><? echo mysql_result($rs,$i,"jg")?></td>
    <td width="157"><? echo mysql_result($rs,$i,"lxr")?></td>
    <td width="158"><? echo mysql_result($rs,$i,"lxdh")?></td>
    <td width="182"><? echo mysql_result($rs,$i,"lxdz")?></td>
    <!--<td width="55" align="center"><a href="#" onClick='javascript:window.open("KH_add.php?mpzh=<? //echo mysql_result($rs,$i,"mpzh")?>", "HT_dhdj", "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=yes,width=850,height=410,left=300,top=100")'><img src="../images/func_edit.gif" alt="修改" width="18" height="17" border="0"></a> 
    <a onClick="javascript:suredo('?DELID=<? //echo mysql_result($rs,$i,"id")?>','确定删除?')"><img src="../images/func_delete.gif" width="15" height="17" alt="删除"></a></td>-->
  </tr>
       <? 
	   } ?>
 </tbody>
</table>
 
<table class=maintable cellSpacing=1 cellPadding=1 width="100%" border=0 id="table1" height="19">
  <tr>
    <td height="16" background="../images/nabg1.gif" class=alert><span class="STYLE4">·客户信息管理。</span></TD>
  </tr>
</table>
<div align="right"><A <? if ($page_t>1 and $page_no>1) {echo "href=".$_SERVER["PHP_SELF"]."?pno=1";} else {echo "disabled";};?>>首页</A>　<A <? if ($page_t>1 and $page_no>1) {echo "href=".$_SERVER["PHP_SELF"]."?pno=".($page_no-1);} else {echo "disabled";};?>>上一页</A>　<A <? if ($page_t>1 and $page_no<$page_t) {echo "href=".$_SERVER["PHP_SELF"]."?pno=".($page_no+1);} else {echo "disabled";};?>>下一页</A>　<A <? if ($page_t>1 and $page_no<>$page_t) {echo "href=".$_SERVER["PHP_SELF"]."?pno=".$page_t;} else {echo "disabled";};?>>尾页</A>　
    <INPUT name="pno" onKeyDown="" value="1" size="3">
    <INPUT name="ZKPager1" type="button" class="menubutton" value="转到" onClick="javascript:window.location.href='<? echo $_SERVER["PHP_SELF"]?>?pno='+document.form1.pno.value">　
    第<? echo $page_no."/".$page_t?>页
</div>
</form>
</body>
</html>
