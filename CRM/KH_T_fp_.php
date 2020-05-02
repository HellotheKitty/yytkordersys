<? require("../inc/conn.php"); 
session_start();
if ($_SESSION["YKOAOK"]<>"OK") { 
echo "<script language=JavaScript>{window.location.href='error.php';}</script>";
exit; }?>
<? 
if ($_GET["team"]<>"") $_SESSION["CRM_TEAM"]=$_GET["team"];

$page_num=15;    
$total = mysql_fetch_array(mysql_query("select count(1) from crm_khb where callout is null and not datainputsj is null and instr((select dq from crm_teamdq where team='".$_SESSION["CRM_TEAM"]."'),province)>0"));//查询数据库中一共有多少条数据    
$Total = $total[0];                       //    
$page_t = ceil($Total/$page_num);//上舍，取整    
if(!isset($_GET['pno'])||!intval($_GET['pno'])||$_GET['pno']>$page_t)//page可能的四种状态    
    $page_no=1;    
else    
    $page_no=$_GET['pno'];//如果不满足以上四种情况，则page的值为$_GET['page']    
$startnum = ($page_no-1)*$page_num;//开始条数    
$rs=mysql_query("select crm_khb.id,khmc,lxr,sshy,province,city,khcsd,memo,datainputsj,count(crm_khb_contact.id) lxcs from crm_khb left join crm_khb_contact on crm_khb.id=crm_khb_contact.khbid where callout is null and not datainputsj is null and instr((select dq from crm_teamdq where team='".$_SESSION["CRM_TEAM"]."'),province)>0 group by crm_khb.id order by crm_khb.id limit $startnum,$page_num", $conn); 	

?> 
<script language="javascript">
function checkAll(selectAllObj) {
	var checkBoxObjAry = document.getElementsByName("checkBox[]");
	var count = checkBoxObjAry.length;
	var selectAllFlg = selectAllObj.checked;
	for (var i = 0; i < count; i++) {
		checkBoxObjAry[i].checked = selectAllFlg;
	}
}
function ifChecked() 
{
   var a = document.getElementsByName("checkBox[]"); 
   var n = a.length;
   var k = 0;
   for (var i=0; i<n; i++){
        if(a[i].checked){
            k = 1;
        }
    }
        if(k==0){
        alert("请先选择条目!");
        return false;
    }
	return true;
 }
 </script>

<HTML>
<HEAD>
    
    <META content="MSHTML 6.00.2800.1276" name=GENERATOR>
    <META http-equiv=Content-Type content="text/html; charset=utf-8">
    <META http-equiv=Content-Style-Type content=text/css>
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<meta name="viewport" content="width=device-width,minimum-scale=1.0"/>
<TITLE>百立易卡--客户信息</TITLE>

<LINK href="../css/mainWin.css" type=text/css  media=screen rel=stylesheet>
<LINK href="../css/query.css" type=text/css  media=screen rel=stylesheet>
<LINK href="../css/02.css" type=text/css  media=screen rel=stylesheet>
<LINK href="../css/mainWin2.css" type=text/css  media=screen rel=stylesheet>
<script src="../js/jquery-1.3.2.min.js" type="text/javascript"></script>
    <base target="_self">
</HEAD>
<style>
.black_overlay{
display: none;
position: absolute;
top: 0%;
left: 0%;
width: 100%;
height: 100%;
background-color: black;
z-index:1001;
-moz-opacity: 0.3;
opacity:.30;
filter: alpha(opacity=30);
}
.white_content {
display: none;
position: absolute;
top: 10%;
left: 10%;
width: 70%;
height: 70%;
border: 16px solid lightblue;
background-color: white;
z-index:1002;
overflow: auto;
}
</style>
<script type="text/javascript">
//弹出隐藏层
function ShowDiv(show_div,bg_div){
document.getElementById(show_div).style.display='block';
document.getElementById(bg_div).style.display='block' ;
var bgdiv = document.getElementById(bg_div);
bgdiv.style.width = document.body.scrollWidth;
// bgdiv.style.height = $(document).height();
//$("#"+bgdiv).height($(document).height());
};
//关闭弹出层
function CloseDiv(show_div,bg_div)
{
document.getElementById(show_div).style.display='none';
document.getElementById(bg_div).style.display='none';
};
</script>
<body marginwidth="0" topmargin="0" leftmargin="0"  marginheight="0">
<div class="mainbackground">
<form method="post" id="actForm" name="actForm" action="msg_send_del.php">
<input type="hidden" name="lx" value="<? echo $_GET["lx"];?>" />

		<DIV ID=Title_bar>
			<DIV ID=Title_bar_Head>
				<DIV ID=Title_Head></DIV>
				<DIV ID=Title>
					<img border="0" width="18" height="18" src="../images/title_arrow2.gif" />
					分配客户
		    </DIV>
				<DIV ID=Title_End></DIV>
				<DIV ID=Title_bar_bg></DIV>
			</DIV>
			<DIV ID=Title_bar_Tail>
				<DIV ID=Title_FuncBar>
					<ul>
							<LI CLASS=line></LI>
							<LI CLASS=title>
							  <div onClick="ShowDiv('MyDiv','fade')" class="Btn">
							    分配客户
						      </div>
 
</LI>
					  <LI CLASS=line></LI>
						
					</ul>
				</DIV>
			</DIV>
		</DIV>
        <input name="select" type="radio" value="1" <? if ($gp=="" or $gp=="1") echo "checked";?> >待我分配[共<? echo $Total?>条]
<DIV ID=MainArea>
				<TABLE WIDTH=100% BORDER=0 CELLSPACING=0 CELLPADDING=0 CLASS=TableStyle>
					<TR ALIGN=center VALIGN=middle ID=TableTitle>
						<TD WIDTH=26 STYLE="border-left: 0px solid #000;">&nbsp;</TD>
						<TD WIDTH=25>序号</TD>
						<TD WIDTH=100>客户名称</TD>
						<TD WIDTH=40>联系人</TD>
						<TD WIDTH=50>所属行业</TD>
						<TD WIDTH=60>所属地区</TD>
						<TD WIDTH=50>客户成熟度</TD>
						<TD WIDTH=50>已联系次数</TD>
						<TD WIDTH=180>联系备注</TD>
						<TD WIDTH=60>输入时间</TD>
					</TR>
			<tbody ID=TableData>
            <? for($i=0;$i<mysql_num_rows($rs);$i++){  ?>
					<tr onMouseOver="this.style.backgroundColor='#FFD'" onMouseOut="this.style.backgroundColor='white'"  class="TableDetail2" id="d0">
							<TD  ALIGN=CENTER STYLE="padding:0px;"><input  type="checkbox" value="" name="checkBox[]" /></TD>
							<TD ><? echo $i +1;?></TD>
							<TD onClick="window.location.href='KH_add.php?id=<? echo mysql_result($rs,$i,0);?>'" style="cursor:pointer"><? echo mysql_result($rs,$i,"khmc");?></TD>
							<TD ><? echo mysql_result($rs,$i,"lxr");?></TD>
							<TD ><? echo mysql_result($rs,$i,"sshy");?></TD>
							<TD ><? echo mysql_result($rs,$i,"province"),mysql_result($rs,$i,"city");?></TD>
							<TD ><? echo mysql_result($rs,$i,"khcsd");?></TD>
							<TD ><? echo mysql_result($rs,$i,"lxcs");?></TD>
							<TD ><? echo mb_substr(mysql_result($rs,$i,"memo"),0,80,"utf-8");?></TD>
						  	<TD><? echo mysql_result($rs,$i,"datainputsj");?></TD>
						</tr>
                        <? }?>
            </tbody>
				</TABLE>
			<DIV ID=TableTail>
				
			</DIV>
			
<DIV STYLE="width:87%; float:right;" align="right"><A <? if ($page_t>1 and $page_no>1) {echo "href=".$_SERVER["PHP_SELF"]."?pno=1&gp=".$_GET["gp"];} else {echo "disabled style='color:gray;'";};?>>首页</A>　<A <? if ($page_t>1 and $page_no>1) {echo "href=".$_SERVER["PHP_SELF"]."?pno=".($page_no-1)."&gp=".$_GET["gp"];} else {echo "disabled style='color:gray;'";};?>>上一页</A>　<A <? if ($page_t>1 and $page_no<$page_t) {echo "href=".$_SERVER["PHP_SELF"]."?pno=".($page_no+1)."&gp=".$_GET["gp"];} else {echo "disabled style='color:gray;'";};?>>下一页</A>　<A <? if ($page_t>1 and $page_no<>$page_t) {echo "href=".$_SERVER["PHP_SELF"]."?pno=".$page_t."&gp=".$_GET["gp"];} else {echo "disabled style='color:gray;'";};?>>尾页</A>　
    <INPUT name="pno" onKeyDown="" value="<? echo $page_no?>" size="3">
    <INPUT name="ZKPager1" type="button" class="menubutton" value="转到" onClick="javascript:window.location.href='<? echo $_SERVER["PHP_SELF"]?>?pno='+document.actForm.pno.value+'&gp=<? echo $_GET["gp"] ?>'">　
    第<? echo $page_no."/".$page_t?>页&nbsp;&nbsp;&nbsp;&nbsp;</DIV>
    
		</DIV>
	</form>
</div> 
<div id="fade" class="black_overlay">
</div>
<div id="MyDiv" class="white_content">
<div style="text-align: right; cursor: default; height: 20px;">
<span style="font-size: 16px;" onClick="CloseDiv('MyDiv','fade')">关闭</span>
</div>
<form name="lxxx" id="lxxx" action="" method="post">
<input type="hidden" name="lx" value="fp" />
待分配客户数：<? echo mysql_num_rows($rs);?><hr>
选择电销人员：<INPUT TYPE=CHECKBOX NAME=selectAll ONCLICK="javascript:for (var i = 0; i < document.getElementsByName('ry[]').length;i++) {document.getElementsByName('ry[]')[i].checked = this.checked;}">全选<br>
<? $rsdx=mysql_query("select * from crm_callconfig where team='".$_SESSION["CRM_TEAM"]."' and qx>'000'",$conn);
echo '<table width="100%" border="0">';
for ($k=0;$k<mysql_num_rows($rsdx);$k+=4) { 
	$rs00=mysql_query("select count(1) from crm_khb where left(callout,instr(callout,'/')-1)='".mysql_result($rsdx,$k,"bh")."' and (nextlx is null or datediff(now(),nextlx)>=0) and (state is null or state=-1)",$conn);
echo '<tr><td><input name="ry[]" type="checkbox" value="'.mysql_result($rsdx,$k,"bh")."/".mysql_result($rsdx,$k,"xm").'">'.mysql_result($rsdx,$k,"bh")."/".mysql_result($rsdx,$k,"xm"),'['.mysql_result($rs00,0,0).']','</td>';
if (mysql_num_rows($rsdx)>$k+1) echo '<td><input name="ry[]" type="checkbox" value="'.mysql_result($rsdx,$k+1,"bh")."/".mysql_result($rsdx,$k+1,"xm").'">'.mysql_result($rsdx,$k+1,"bh")."/".mysql_result($rsdx,$k+1,"xm"),'</td>';
if (mysql_num_rows($rsdx)>$k+2) echo '<td><input name="ry[]" type="checkbox" value="'.mysql_result($rsdx,$k+2,"bh")."/".mysql_result($rsdx,$k+2,"xm").'">'.mysql_result($rsdx,$k+2,"bh")."/".mysql_result($rsdx,$k+2,"xm"),'</td>';
if (mysql_num_rows($rsdx)>$k+3) echo '<td><input name="ry[]" type="checkbox" value="'.mysql_result($rsdx,$k+3,"bh")."/".mysql_result($rsdx,$k+3,"xm").'">'.mysql_result($rsdx,$k+3,"bh")."/".mysql_result($rsdx,$k+3,"xm"),'</td></tr>';
}
echo "</table>";
?>
<hr>
把：<input name="sjsl" type="text" value="<? echo mysql_num_rows($rs);?>" style="width:30px">条数据  
<input name="zxx" type="button" value="平均分配给选中电销人员" onClick="$.post('KH_saveinfo.php?lx=fp', $('#lxxx').serialize(),function(data){alert('Data Loaded: ' + data);});CloseDiv('MyDiv','fade');">
<input name="zxx" type="button" value="撤回选中人员下次联系为空数据" onClick="$.post('KH_saveinfo.php', $('#lxxx').serialize(),function(data){alert('Data Loaded: ' + data);});CloseDiv('MyDiv','fade');">
</form>
</div>
</body>
</HTML>