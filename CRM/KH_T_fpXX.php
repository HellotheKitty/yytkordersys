<? 
require("../inc/conn.php"); 
session_start();
if ($_SESSION["YKOAOK"]<>"OK") { 
	echo "<script language=JavaScript>{window.location.href='error.php';}</script>";
	exit; 
}
if ($_GET["team"]<>"") $_SESSION["CRM_TEAM"]=$_GET["team"];
?>
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
	<link type="text/css" rel="stylesheet" href="../css/manage.css" />
<LINK href="../css/mainWin2.css" type=text/css  media=screen rel=stylesheet>
<script src="../js/jquery-1.3.2.min.js" type="text/javascript"></script>
<script type="text/javascript" src="../js/pageList.js"></script>
<script type="text/javascript" src="../js/pagination.js"></script>
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


	$(document).ready(function(){
	  	   
	   $.pagelist.defaults.pgniBigId = "paginationBig";
	   $.pagelist.defaults.pageSize = 15;
	   $.pagelist.initTwWrinfoa();
	
	});

function checkAll(selectAllObj) {
	var checkBoxObjAry = document.getElementsByName("checkBox[]");
	var count = checkBoxObjAry.length;
	var selectAllFlg = selectAllObj.checked;
	for (var i = 0; i < count; i++) {
		checkBoxObjAry[i].checked = selectAllFlg;
	}
};
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
 };

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
							<LI CLASS=title>
							  <div onClick="window.location.href='KH_contact_tj.php'" class="Btn">
							    电呼统计
						      </div>
 
</LI>
					  <LI CLASS=line></LI>
						
					</ul>
				</DIV>
			</DIV>
		</DIV>
        <input name="select" type="radio" value="1" <? if ($gp=="" or $gp=="1") echo "checked";?> >待我分配[共<span id="totalC"></span>条]
<DIV ID=MainArea>


			<TABLE WIDTH=100% BORDER=0 CELLSPACING=0 CELLPADDING=0 CLASS=TableStyle id="listTable">
				<thead>
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
				</thead>
					<tbody ID=TableData>

		            </tbody>
			</TABLE>
			<DIV ID=TableTail>
				
			</DIV>
			
           <div class="pagination" id="paginationBig"></div>
    
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
待分配客户数：<span id="numSize"></span><hr>
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
把：<input name="sjsl" type="text" value="<? //echo mysql_num_rows($rs);?>" id="numSizeInput" style="width:30px">条数据  
<input name="zxx" type="button" value="平均分配给选中电销人员" onClick="$.post('KH_saveinfo.php?lx=fp', $('#lxxx').serialize(),function(data){alert('Data Loaded: ' + data);});CloseDiv('MyDiv','fade');">
<input name="zxx" type="button" value="撤回选中人员下次联系为空数据" onClick="$.post('KH_saveinfo.php', $('#lxxx').serialize(),function(data){alert('Data Loaded: ' + data);});CloseDiv('MyDiv','fade');">
</form>
</div>
</body>
</HTML>