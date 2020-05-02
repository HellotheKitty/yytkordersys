<? require("../inc/conn.php"); 
session_start();
if ($_SESSION["YKOAOK"]<>"OK") { 
echo "<script language=JavaScript>{window.location.href='error.php';}</script>";
exit; }?>
<? if ($_GET["team"]=="all")
 $rs=mysql_query("select bh,xm,team from crm_callconfig where team like 'X%' and qx='100' order by team,bh",$conn);
 else
 $rs=mysql_query("select bh,xm,team from crm_callconfig where team='".$_SESSION["CRM_TEAM"]."' and qx='100' order by bh",$conn);
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
		<DIV ID=Title_bar>
			<DIV ID=Title_bar_Head>
				<DIV ID=Title_Head></DIV>
				<DIV ID=Title>
					<img border="0" width="18" height="18" src="../images/title_arrow2.gif" />
					联系统计
</DIV>
				<DIV ID=Title_End></DIV>
				<DIV ID=Title_bar_bg></DIV>
			</DIV>
			<DIV ID=Title_bar_Tail>
				<DIV ID=Title_FuncBar>
					<ul>
							
					</ul>
				</DIV>
			</DIV>
		</DIV>     
<DIV ID=MainArea>
				<TABLE WIDTH=100% BORDER=0 CELLSPACING=0 CELLPADDING=0 CLASS=TableStyle>
					<TR ALIGN=center VALIGN=middle ID=TableTitle>
						<TD WIDTH=1% rowspan="2" STYLE="border-left: 0px solid #000;">&nbsp;</TD>
						<TD WIDTH=6% rowspan="2">序号</TD>
						<TD WIDTH=19% rowspan="2">业务人员</TD>
						<TD WIDTH=5% rowspan="2" class="Download_title">意向客户数</TD>
						<TD WIDTH=5% rowspan="2" class="Download_title">名片客户数</TD>
						<TD WIDTH=16% colspan="4">本日</TD>
						<TD WIDTH=16% colspan="4" bgcolor="#99FFFF">本周</TD>
						<TD WIDTH=16% colspan="4">本月</TD>
						<TD WIDTH=16% colspan="4" ><font color="green">全部</font></TD>
					</TR>
					<TR ALIGN=center VALIGN=middle ID=TableTitle>
					  <TD width="4%">挖掘客户</TD>
					  <TD width="4%"><font color="red">转意向</font></TD>
					  <TD width="4%">联系客户</TD>
					  <TD width="4%">转用户</TD>
					  <TD width="4%" bgcolor="#99FFFF">挖掘客户</TD>
					  <TD width="4%" bgcolor="#99FFFF"><font color="red">转意向</font></TD>
					  <TD width="4%" bgcolor="#99FFFF">联系客户</TD>
					  <TD width="4%" bgcolor="#99FFFF">转用户</TD>
					  <TD width="4%">挖掘客户</TD>
					  <TD width="4%"><font color="red">转意向</font></TD>
					  <TD width="4%">联系客户</TD>
					  <TD width="4%">转用户</TD>
					  <TD width="4%"><font color="green">挖掘客户</font></TD>
					  <TD width="4%"><font color="red">转意向</font></TD>
					  <TD width="4%"><font color="green">联系客户</font></TD>
					  <TD width="4%"><font color="green">转用户</font></TD>
	              </TR>
			<tbody ID=TableData>
            <? for($i=0;$i<mysql_num_rows($rs);$i++){  ?>
					<tr onMouseOver="this.style.backgroundColor='#FFD'" onMouseOut="this.style.backgroundColor='white'"  class="TableDetail2" id="d0">
							<TD  ALIGN=CENTER STYLE="padding:0px;">&nbsp;</TD>
							<TD ><? echo $i +1;?></TD>
							<TD ><a href='KH_contact_log.php?gp=<? echo mysql_result($rs,$i,"bh")?>'>[联系日志]</a> <? echo "[",mysql_result($rs,$i,"team"),"]",mysql_result($rs,$i,"bh"),"/",mysql_result($rs,$i,"xm");?></TD>
							<TD align="center" class="Download_title" ><? echo mysql_result(mysql_query("select count(1) from crm_khb where left(xsry,instr(xsry,'/')-1)='".mysql_result($rs,$i,"bh")."' and state=-2 and yikayin_zh is null",$conn),0,0);?></TD>
							<TD align="center" bgcolor="#99FFFF" class="Download_title" ><? echo mysql_result(mysql_query("select count(1) from crm_khb where left(xsry,instr(xsry,'/')-1)='".mysql_result($rs,$i,"bh")."' and state>0 and not yikayin_zh is null",$conn),0,0);?></TD>
							<TD align="center" ><? echo mysql_result(mysql_query("select count(1) from crm_khb_contact c,crm_khb where c.khbid=crm_khb.id and czy='".mysql_result($rs,$i,"bh")."' and (state=-1 or (state=-2 and xsryfpsj>'".date('Y-m-d')."')) and czsj>'".date('Y-m-d')."' and content<>'占线或无人接听'",$conn),0,0);?></TD>
							<TD align="center" ><font color="red"><? echo mysql_result(mysql_query("select count(1) from crm_khb where left(xsry,instr(xsry,'/')-1)='".mysql_result($rs,$i,"bh")."' and (state=-2 or state=1) and xsryfpsj>'".date('Y-m-d')."'",$conn),0,0);?></font></TD>
							<TD align="center" ><? echo mysql_result(mysql_query("select count(1) from crm_khb_contact c,crm_khb where c.khbid=crm_khb.id and czy='".mysql_result($rs,$i,"bh")."' and (state=-2 and xsryfpsj<='".date('Y-m-d')."') and czsj>'".date('Y-m-d')."'",$conn),0,0);?></TD>
							<TD align="center" ><? echo mysql_result(mysql_query("select count(1) from (select count(distinct crm_khb.id) from crm_khb,nc_erp.order_main where left(xsry,instr(xsry,'/')-1)='".mysql_result($rs,$i,"bh")."' and crm_khb.state>0 and yikayin_zh=order_main.user and order_main.dje>0 group by crm_khb.id having min(ddate)>'".date('Y-m-d')."') cc",$conn),0,0);?></TD>
                            
							<TD align="center" bgcolor="#99FFFF" ><? echo mysql_result(mysql_query("select count(1) from crm_khb_contact c,crm_khb where c.khbid=crm_khb.id and czy='".mysql_result($rs,$i,"bh")."' and (state=-1 or (state=-2 and xsryfpsj>'".this_monday(0,false)."')) and czsj>'".this_monday(0,false)."' and content<>'占线或无人接听'",$conn),0,0);?></TD>
							<TD align="center" bgcolor="#99FFFF" ><font color="red"><? echo mysql_result(mysql_query("select count(1) from crm_khb where left(xsry,instr(xsry,'/')-1)='".mysql_result($rs,$i,"bh")."' and (state=-2 or state=1) and xsryfpsj>'".this_monday(0,false)."'",$conn),0,0);?></font></TD>
							<TD align="center" bgcolor="#99FFFF" ><? echo mysql_result(mysql_query("select count(1) from crm_khb_contact c,crm_khb where c.khbid=crm_khb.id and czy='".mysql_result($rs,$i,"bh")."' and (state=-2 and xsryfpsj<='".date('Y-m-d')."') and czsj>'".this_monday(0,false)."'",$conn),0,0);?></TD>
							<TD align="center" bgcolor="#99FFFF" ><? echo mysql_result(mysql_query("select count(1) from (select count(distinct crm_khb.id) from crm_khb,nc_erp.order_main where left(xsry,instr(xsry,'/')-1)='".mysql_result($rs,$i,"bh")."' and crm_khb.state>0 and yikayin_zh=order_main.user and order_main.dje>0 group by crm_khb.id having min(ddate)>'".this_monday(0,false)."') cc",$conn),0,0);?></TD>
                            
							<TD align="center" ><? echo mysql_result(mysql_query("select count(1) from crm_khb_contact c,crm_khb where c.khbid=crm_khb.id and czy='".mysql_result($rs,$i,"bh")."' and (state=-1 or (state=-2 and xsryfpsj>'".date('Y-m-')."01')) and czsj>'".date('Y-m-')."01' and content<>'占线或无人接听'",$conn),0,0);?></TD>
							<TD align="center" ><font color="red"><? echo mysql_result(mysql_query("select count(1) from crm_khb where left(xsry,instr(xsry,'/')-1)='".mysql_result($rs,$i,"bh")."' and (state=-2 or state=1) and xsryfpsj>'".date('Y-m-')."01'",$conn),0,0);?></font></TD>
							<TD align="center" ><? echo mysql_result(mysql_query("select count(1) from crm_khb_contact c,crm_khb where c.khbid=crm_khb.id and czy='".mysql_result($rs,$i,"bh")."' and (state=-2 and xsryfpsj<='".date('Y-m-d')."') and czsj>'".date('Y-m-')."01'",$conn),0,0);?></TD>
							<TD align="center" ><? echo mysql_result(mysql_query("select count(1) from (select count(distinct crm_khb.id) from crm_khb,nc_erp.order_main where left(xsry,instr(xsry,'/')-1)='".mysql_result($rs,$i,"bh")."' and crm_khb.state>0 and yikayin_zh=order_main.user and order_main.dje>0 group by crm_khb.id having min(ddate)>'".date('Y-m-')."01') cc",$conn),0,0);?></TD>
                            
							<TD align="center" bgcolor="#99FFFF" ><? echo mysql_result(mysql_query("select count(1) from crm_khb_contact c,crm_khb where c.khbid=crm_khb.id and czy='".mysql_result($rs,$i,"bh")."' and (state=-1 or (state=-2)) and content<>'占线或无人接听'",$conn),0,0);?></TD>
							<TD align="center" bgcolor="#99FFFF" ><font color="red"><? echo mysql_result(mysql_query("select count(1) from crm_khb where left(xsry,instr(xsry,'/')-1)='".mysql_result($rs,$i,"bh")."' and (state=-2 or state=1) ",$conn),0,0);?></font></TD>
							<TD align="center" bgcolor="#99FFFF" ><? echo mysql_result(mysql_query("select count(1) from crm_khb_contact c,crm_khb where c.khbid=crm_khb.id and czy='".mysql_result($rs,$i,"bh")."' and (state=-2 ) ",$conn),0,0);?></TD>
                            <? $rstt=mysql_query("select count(khmc),group_concat(khmc,'/',dd,'\n' order by dd desc) from (select distinct crm_khb.khmc,min(ddate) dd from crm_khb,nc_erp.order_main where left(xsry,instr(xsry,'/')-1)='".mysql_result($rs,$i,"bh")."' and crm_khb.state>0 and yikayin_zh=order_main.user and order_main.dje>0 group by khmc) kk",$conn);?>
							<TD align="center" bgcolor="#99FFFF" title="<? echo mysql_result($rstt,0,1);?>"><? echo mysql_result($rstt,0,0);?></TD>

						</tr>
                        <? }?>   
            </tbody>
				</TABLE>
			<DIV ID=TableTail>
				
			</DIV>
</DIV>
</div> 
</body>
</HTML>

<?
function this_monday($timestamp=0,$is_return_timestamp=true){ 
static $cache ; 
$id = $timestamp.$is_return_timestamp; 
if(!isset($cache[$id])){ 
if(!$timestamp) $timestamp = time(); 
$monday_date = date('Y-m-d', $timestamp-86400*date('w',$timestamp)+(date('w',$timestamp)>0?86400:-/*6*86400*/518400)); 
if($is_return_timestamp){ 
$cache[$id] = strtotime($monday_date); 
}else{ 
$cache[$id] = $monday_date; 
} 
} 
return $cache[$id]; 
} 
?>