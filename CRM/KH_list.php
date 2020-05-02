<? require("../inc/conn.php"); 
session_start();
if ($_SESSION["YKOAOK"]<>"OK") { 
echo "<script language=JavaScript>{window.location.href='error.php';}</script>";
exit; }?>
<? 
if ($_GET["team"]<>"") {
	$_SESSION["CRM_TEAM"]=$_GET["team"];
	$rscf=mysql_query("select * from crm_callconfig where bh='".$_SESSION["YKOAUSER"]."' and team='".$_GET["team"]."' and qx>'000'",$conn);
	$_SESSION["CRM_FJH"]=mysql_result($rscf,0,"fjh");
	$_SESSION["CRM_HTTP"]=mysql_result($rscf,0,"http");
}
if ($_GET["finds"]!==null) $_SESSION["FINDSTR"]=" and (crm_khb.khmc like '%".$_GET["finds"]."%' or crm_khb.lxr like '%".$_GET["finds"]."%')";
//callout
$gp=$_GET["gp"];
if ($gp=="2") 
	$rs=mysql_query("select crm_khb.*,count(crm_khb_contact.id) lxcs from crm_khb left join crm_khb_contact on crm_khb.id=crm_khb_contact.khbid where left(callout,instr(callout,'/')-1)='".$_SESSION["YKOAUSER"]."' and (datediff(now(),nextlx)<0) and (state is null or state=-1) ".$_SESSION["FINDSTR"]." group by crm_khb.id order by nextlx", $conn); 
elseif ($gp=="3") 
	$rs=mysql_query("select crm_khb.*,count(crm_khb_contact.id) lxcs from crm_khb left join crm_khb_contact on crm_khb.id=crm_khb_contact.khbid where left(callout,instr(callout,'/')-1)='".$_SESSION["YKOAUSER"]."' and (state>0 or state=-2) ".$_SESSION["FINDSTR"]." group by crm_khb.id order by xsryfpsj desc", $conn); 
else 
	$rs=mysql_query("select crm_khb.*,count(crm_khb_contact.id) lxcs from crm_khb left join crm_khb_contact on crm_khb.id=crm_khb_contact.khbid where left(callout,instr(callout,'/')-1)='".$_SESSION["YKOAUSER"]."' and (nextlx is null or datediff(now(),nextlx)>=0) and (state is null or state=-1) ".$_SESSION["FINDSTR"]." group by crm_khb.id order by calloutfpsj desc", $conn); 


//分页
if ($tj<>"") {$page_num=mysql_num_rows($rs)+1;} else {$page_num=15;}     //每页行数
$page_no=$_GET["pno"];     //当前页
if ($page_no=="") {$page_no=1;}
$page_f=$page_num*($page_no -1);   //开始行
$page_e=$page_f+$page_num;			//结束行
if ($page_e>mysql_num_rows($rs)) {$page_e=mysql_num_rows($rs);}
$page_t=ceil(mysql_num_rows($rs) / $page_num);  //总页数
//分页
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
    <base target="_self">
</HEAD>

<body marginwidth="0" topmargin="0" leftmargin="0"  marginheight="0">
<div class="mainbackground">
<form method="post" id="actForm" name="actForm" action="">
<input type="hidden" name="lx" value="<? echo $_GET["lx"];?>" />

		<DIV ID=Title_bar>
			<DIV ID=Title_bar_Head>
				<DIV ID=Title_Head></DIV>
				<DIV ID=Title>
					<img border="0" width="18" height="18" src="../images/title_arrow2.gif" />
					我的客户
		    </DIV>
				<DIV ID=Title_End></DIV>
				<DIV ID=Title_bar_bg></DIV>
			</DIV>
			<DIV ID=Title_bar_Tail>
				<DIV ID=Title_FuncBar>
					<ul>
							<LI CLASS=line></LI>
							<LI CLASS=title>
							  <div onClick="window.location.href='KH_add.php';" class="Btn">
							    增加客户
						      </div>
 
</LI>
					  <LI CLASS=line></LI>
						
					</ul>
				</DIV>
			</DIV>
		</DIV>
        <input name="select" type="radio" value="1" <? if ($gp=="" or $gp=="1") echo "checked";?> onClick="window.location.href='KH_list.php?gp=1'">今日需联系<? if ($gp=="" or $gp=="1") echo "[",mysql_num_rows($rs),"]";?>
        <input name="select" type="radio" value="2" <? if ($gp=="2") echo "checked";?> onClick="window.location.href='KH_list.php?gp=2'">
        以后联系<? if ($gp=="2") echo "[",mysql_num_rows($rs),"]";?>
        <input name="select" type="radio" value="3" <? if ($gp=="3") echo "checked";?> onClick="window.location.href='KH_list.php?gp=3'">
        我呼出的客户<? if ($gp=="3") echo "[",mysql_num_rows($rs),"]";?>
        　　
        <input name="finds" type="text" value="<? echo substr(substr($_SESSION["FINDSTR"],0,-3),strrpos(substr($_SESSION["FINDSTR"],0,-3),"%")+1)?>">
        <input name="find" type="button" value="查找" onClick="javascript:window.location.href='?finds='+actForm.finds.value;">
        　
        <input name="find2" type="button" value="联系日志" onClick="javascript:window.location.href='KH_contact_log.php';">
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
						<TD WIDTH=80>联系备注</TD>
						<TD WIDTH=160>联系信息</TD>
					</TR>
			<tbody ID=TableData>
            <? for($i=$page_f;$i<$page_e;$i++){ 
			$rsk=mysql_query("select group_concat(content order by czsj desc separator ';') from crm_khb_contact where khbid=".mysql_result($rs,$i,"id"),$conn); ?>
					<tr onMouseOver="this.style.backgroundColor='#FFD'" onMouseOut="this.style.backgroundColor='white'"  class="TableDetail2" id="d0">
							<TD  ALIGN=CENTER STYLE="padding:0px;"><input  type="checkbox" value="" name="checkBox[]" /></TD>
							<TD ><? echo $i +1;?></TD>
							<TD onClick="window.location.href='<? echo $gp=="3"?"KH_show":"KH_add"?>.php?id=<? echo mysql_result($rs,$i,0);?>'" style="cursor:pointer;width:100px;"><? echo mysql_result($rs,$i,"khmc");?></TD>
							<TD style="width:50px;"><? echo mysql_result($rs,$i,"lxr");?></TD>
							<TD ><? echo mysql_result($rs,$i,"sshy");?></TD>
							<TD ><? echo mysql_result($rs,$i,"province"),mysql_result($rs,$i,"city");?></TD>
							<TD ><? echo mysql_result($rs,$i,"khcsd");?></TD>
							<TD ><? echo mysql_result($rs,$i,"lxcs");?></TD>
							<TD style="width:80px;"><? echo mb_substr(mysql_result($rs,$i,"memo"),0,80,"utf-8");?></TD>
						  	<TD><? echo mysql_result($rsk,0,0)?></TD>
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