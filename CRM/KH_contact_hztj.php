<? require("../inc/conn.php"); 
session_start();
if ($_SESSION["YKOAOK"]<>"OK") { 
echo "<script language=JavaScript>{window.location.href='error.php';}</script>";
exit; }?>
<? 
if ($_GET["gp"]=="1" or $_GET["gp"]=="") {
 $rs=mysql_query("select crm_callconfig.team,teamname,bh,xm from crm_callconfig,crm_teamdq where crm_callconfig.team=crm_teamdq.team and crm_callconfig.team like 'T%' and qx='100' order by crm_callconfig.team,bh",$conn);
 $zd="callout";$st="-1";$st2="-2";$st3="-1";$sj="xsryfpsj";$gp="1";
} else {
 $rs=mysql_query("select crm_callconfig.team,teamname,bh,xm from crm_callconfig,crm_teamdq where crm_callconfig.team=crm_teamdq.team and crm_callconfig.team like 'X%' and qx='100' order by crm_callconfig.team,bh",$conn);
 $zd="xsry";$st="-2";$st2="1";$st3="1";$sj="yikayin_zhtime";$gp="2";
}
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
        <input name="select" type="radio" onClick="window.location.href='KH_contact_hztj.php?gp=1'" value="1" checked <? if ($gp=="" or $gp=="1") echo "checked";?>>电呼统计
        <input name="select" type="radio" value="2" <? if ($gp=="2") echo "checked";?> onClick="window.location.href='KH_contact_hztj.php?gp=2'">销售统计   
<DIV ID=MainArea>
				<TABLE WIDTH=80% BORDER=0 CELLSPACING=0 CELLPADDING=0 CLASS=TableStyle>
					<TR ALIGN=center VALIGN=middle ID=TableTitle>
						<TD WIDTH=1% rowspan="2" STYLE="border-left: 0px solid #000;">&nbsp;</TD>
						<TD WIDTH=10% rowspan="2">组号</TD>
						<TD WIDTH=17% rowspan="2">业务人员</TD>
						<TD WIDTH=8% rowspan="2"><B>当前客户数</B></TD>
						<TD WIDTH=32% colspan="4">客户联络</TD>
						<TD WIDTH=32% colspan="4" ><font color="green">客户成熟</font></TD>
					</TR>
					<TR ALIGN=center VALIGN=middle ID=TableTitle>
					  <TD width="8%">本日</TD>
					  <TD width="8%"><font color="red">本日待联系</font></TD>
					  <TD width="8%">本周</TD>
					  <TD width="8%">本月</TD>
					  <TD width="8%" ><font color="green">本日</font></TD>
					  <TD width="8%" ><font color="green">本周</font></TD>
					  <TD width="8%" ><font color="green">本月</font></TD>
					  <TD width="8%" ><font color="green">月成功率</font></TD>
                  </TR>
			<tbody ID=TableData>
            <? for($i=0;$i<mysql_num_rows($rs);$i++){  ?>
					<tr onMouseOver="this.style.backgroundColor='#FFD'" onMouseOut="this.style.backgroundColor='white'"  class="TableDetail2" id="d0">
							<TD  ALIGN=CENTER STYLE="padding:0px;">&nbsp;</TD>
							<TD align="center" ><? echo mysql_result($rs,$i,"team"),"-",mysql_result($rs,$i,"teamname");?></TD>
							<TD ><a href='KH_contact_log.php?gp=<? echo mysql_result($rs,$i,"bh")?>'>[联系日志]</a> <? echo mysql_result($rs,$i,"bh"),"/",mysql_result($rs,$i,"xm");?></TD>
							<TD align="center" ><B><? echo mysql_result(mysql_query("select count(1) from crm_khb where left({$zd},instr({$zd},'/')-1)='".mysql_result($rs,$i,"bh")."' and state={$st}",$conn),0,0);?></B></TD>
							<TD align="center" ><? echo mysql_result(mysql_query("select count(1) from crm_khb where left({$zd},instr({$zd},'/')-1)='".mysql_result($rs,$i,"bh")."' and (state={$st} or state={$st3}) and contacttime>'".date('Y-m-d')."'",$conn),0,0);?></TD>
							<TD align="center" ><font color="red"><? echo mysql_result(mysql_query("select count(1) from crm_khb where left({$zd},instr({$zd},'/')-1)='".mysql_result($rs,$i,"bh")."' and state={$st} and (nextlx is null or datediff(now(),nextlx)>=0)",$conn),0,0);?></font></TD>
							<TD align="center" ><? echo mysql_result(mysql_query("select count(1) from crm_khb where left({$zd},instr({$zd},'/')-1)='".mysql_result($rs,$i,"bh")."' and (state={$st} or state={$st3}) and contacttime>'".this_monday(0,false)."'",$conn),0,0);?></TD>
							<TD align="center" ><? $zs=mysql_result(mysql_query("select count(1) from crm_khb where left({$zd},instr({$zd},'/')-1)='".mysql_result($rs,$i,"bh")."' and (state={$st} or state={$st3}) and contacttime>'".date('Y-m-')."01'",$conn),0,0);echo $zs;?></TD>
							<? $rs0=mysql_query("select count(1),group_concat(khmc,'\n') from crm_khb where left({$zd},instr({$zd},'/')-1)='".mysql_result($rs,$i,"bh")."' and state={$st2} and ({$sj}>'".date('Y-m-d')."' or ({$sj} is null and contacttime>'".date('Y-m-d')."'))",$conn);?>
                            <TD align="center" title="<? echo mysql_result($rs0,0,1);?>"><font color="green"><? echo mysql_result($rs0,0,0);?></font></TD>
							<? $rs0=mysql_query("select count(1),group_concat(khmc,'\n') from crm_khb where left({$zd},instr({$zd},'/')-1)='".mysql_result($rs,$i,"bh")."' and state={$st2} and ({$sj}>'".this_monday(0,false)."' or ({$sj} is null and contacttime>'".this_monday(0,false)."'))",$conn);?>
                            <TD align="center" title="<? echo mysql_result($rs0,0,1);?>"><font color="green"><? echo mysql_result($rs0,0,0);?></font></TD>
							<? $rs0=mysql_query("select count(1),group_concat(khmc,'\n') from crm_khb where left({$zd},instr({$zd},'/')-1)='".mysql_result($rs,$i,"bh")."' and state={$st2} and ({$sj}>'".date('Y-m-')."01' or ({$sj} is null and contacttime>'".date('Y-m-')."01'))",$conn);?>
                            <TD align="center" title="<? echo mysql_result($rs0,0,1);?>"><font color="green"><? echo mysql_result($rs0,0,0),"</font>";?></TD>
                            <TD align="center" ><? echo $zs>0?round(mysql_result($rs0,0,0)/$zs*1000,2):0,"/千次联络";?></TD>
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