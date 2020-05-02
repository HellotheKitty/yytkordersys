<? require("../inc/conn.php"); 
session_start();
if ($_SESSION["YKOAOK"]<>"OK") { 
echo "<script language=JavaScript>{window.location.href='error.php';}</script>";
exit; }?>
<? 
if ($_GET["team"]<>"") $_SESSION["CRM_TEAM"]=$_GET["team"];
if ($_GET["gp"]<>"") $gp=$_GET["gp"]; else $gp=$_SESSION["YKOAUSER"];
$page_num=15;    
$total = mysql_fetch_array(mysql_query("select count(1) from crm_khb_contact,crm_khb where crm_khb.id=crm_khb_contact.khbid and czy ='".$gp."'"));//查询数据库中一共有多少条数据    
$Total = $total[0];                       //    
$page_t = ceil($Total/$page_num);//上舍，取整    
if(!isset($_GET['pno'])||!intval($_GET['pno'])||$_GET['pno']>$page_t)//page可能的四种状态    
    $page_no=1;    
else    
    $page_no=$_GET['pno'];//如果不满足以上四种情况，则page的值为$_GET['page']    
$startnum = ($page_no-1)*$page_num;//开始条数    
$rs=mysql_query("select crm_khb.id,khmc,lxr,czsj,content,lxrmobile,lxdh from crm_khb,crm_khb_contact where crm_khb.id=crm_khb_contact.khbid and czy ='".$gp."' order by crm_khb_contact.czsj desc limit $startnum,$page_num", $conn); 	

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

<body marginwidth="0" topmargin="0" leftmargin="0"  marginheight="0">
<div class="mainbackground">
<form method="post" id="actForm" name="actForm" action="msg_send_del.php">
<input type="hidden" name="lx" value="<? echo $_GET["lx"];?>" />

		<DIV ID=Title_bar>
			<DIV ID=Title_bar_Head>
				<DIV ID=Title_Head></DIV>
				<DIV ID=Title>
					<img border="0" width="18" height="18" src="../images/title_arrow2.gif" />
					联系日志
		    </DIV>
				<DIV ID=Title_End></DIV>
				<DIV ID=Title_bar_bg></DIV>
			</DIV>
			<DIV ID=Title_bar_Tail>
				<DIV ID=Title_FuncBar>
					<ul>
					<LI CLASS=line></LI>
							<LI CLASS=title>
							  <div onClick="window.history.go(-1);" class="Btn">
							    返回
						      </div>
 
</LI>	
		<LI CLASS=line></LI>	
					</ul>
				</DIV>
			</DIV>
		</DIV>     
<DIV ID=MainArea>
				<TABLE WIDTH=100% BORDER=0 CELLSPACING=0 CELLPADDING=0 CLASS=TableStyle>
					<TR ALIGN=center VALIGN=middle ID=TableTitle>
						<TD WIDTH=26 STYLE="border-left: 0px solid #000;">&nbsp;</TD>
						<TD WIDTH=25>序号</TD>
						<TD WIDTH=100>客户名称</TD>
						<TD WIDTH=40>客户联系人</TD>
						<TD WIDTH=50>客户电话</TD>
						<TD WIDTH=50>联系时间</TD>
						<TD WIDTH=60>联系内容</TD>
					</TR>
			<tbody ID=TableData>
            <? for($i=0;$i<mysql_num_rows($rs);$i++){  ?>
					<tr onMouseOver="this.style.backgroundColor='#FFD'" onMouseOut="this.style.backgroundColor='white'"  class="TableDetail2" id="d0">
							<TD  ALIGN=CENTER STYLE="padding:0px;"><input  type="checkbox" value="" name="checkBox[]" /></TD>
							<TD ><? echo $i +1;?></TD>
							<TD onClick="window.location.href='KH_add.php?id=<? echo mysql_result($rs,$i,0);?>'" style="cursor:pointer"><? echo mysql_result($rs,$i,"khmc");?></TD>
							<TD ><? echo mysql_result($rs,$i,"lxr");?></TD>
							<TD ><? echo mysql_result($rs,$i,"lxrmobile")," ",mysql_result($rs,$i,"lxdh");?></TD>
							<TD ><? echo mysql_result($rs,$i,"czsj")<date('Y-m-d')?mysql_result($rs,$i,"czsj"):"<font color='#0000FF'>".mysql_result($rs,$i,"czsj")."</font>";?></TD>
							<TD ><? echo mysql_result($rs,$i,"content");?></TD>
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
</body>
</HTML>