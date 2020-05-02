<? require("../inc/conn.php"); 
session_start();
if ($_SESSION["YKOAOK"]<>"OK") { 
echo "<script language=JavaScript>{window.location.href='error.php';}</script>";
exit; }?>
<? 

 $rs=mysql_query("select team,teamname,dq from crm_teamdq where team like 'X%' order by team",$conn);
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
					成熟统计
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
				<TABLE WIDTH=80% BORDER=0 CELLSPACING=0 CELLPADDING=0 CLASS=TableStyle>
					<TR ALIGN=center VALIGN=middle ID=TableTitle>
						<TD WIDTH=1% rowspan="2" STYLE="border-left: 0px solid #000;">&nbsp;</TD>
						<TD WIDTH=4% rowspan="2">组号</TD>
						<TD WIDTH=6% rowspan="2">组名称</TD>
						<TD WIDTH=8% rowspan="2"><B>当前客户数</B></TD>
						<TD WIDTH=16% colspan="4">本周情况</TD>
						<TD WIDTH=16% colspan="4" ><font color="green">本月情况</font></TD>
						<TD WIDTH=16% colspan="4" ><font color="blue">全部时间</font></TD>
					</TR>
					<TR ALIGN=center VALIGN=middle ID=TableTitle>
					  <TD width="4%">电呼转意向</TD>
					  <TD width="4%">电呼意向转客户</TD>
					  <TD width="4%">其他转客户</TD>
					  <TD width="4%">合计转客户</TD>
					  <TD width="4%"><font color="green">电呼转意向</font></TD>
					  <TD width="4%"><font color="green">电呼意向转客户</font></TD>
					  <TD width="4%"><font color="green">其他转客户</font></TD>
					  <TD width="4%"><font color="green">合计转客户</font></TD>
					  <TD width="4%"><font color="blue">电呼转意向</font></TD>
					  <TD width="4%"><font color="blue">电呼意向转客户</font></TD>
					  <TD width="4%"><font color="blue">其他转客户</font></TD>
					  <TD width="4%"><font color="blue">合计转客户</font></TD>
				  </TR>
			<tbody ID=TableData>
            <? for($i=0;$i<mysql_num_rows($rs);$i++){  ?>
					<tr onMouseOver="this.style.backgroundColor='#FFD'" onMouseOut="this.style.backgroundColor='white'"  class="TableDetail2" id="d0">
							<TD  ALIGN=CENTER STYLE="padding:0px;">&nbsp;</TD>
							<TD align="center" ><? echo mysql_result($rs,$i,"team");?></TD>
							<TD ><? echo mysql_result($rs,$i,"teamname");?></TD>
                            <? $rsq=mysql_query("select count(1),group_concat(khmc) from crm_khb where left(xsry,instr(xsry,'/')-1) in (select bh from crm_callconfig where team='".mysql_result($rs,$i,"team")."')",$conn);?>
							<TD align="center" ><B><? echo mysql_result($rsq,0,0);?></B></TD>
                            <? $rsq=mysql_query("select count(1),group_concat(khmc,'\n') from crm_khb where (xsryfpsj>'".this_monday(0,false)."' and not calloutfpsj is null and left(xsry,instr(xsry,'/')-1) in (select bh from crm_callconfig where team='".mysql_result($rs,$i,"team")."')) or (xsryfpsj is null and state=-2 and instr((select dq from crm_teamdq where team='".mysql_result($rs,$i,"team")."'),province)>0)",$conn);?>
							<TD align="center" title="<? echo mysql_result($rsq,0,1);?>"><? echo mysql_result($rsq,0,0);?></TD>
                            <? $rsq=mysql_query("select count(1),group_concat(khmc,'\n') from crm_khb where yikayin_zhtime>'".this_monday(0,false)."' and not calloutfpsj is null and left(xsry,instr(xsry,'/')-1) in (select bh from crm_callconfig where team='".mysql_result($rs,$i,"team")."')",$conn);?>
							<TD align="center" title="<? echo mysql_result($rsq,0,1);?>"><? echo mysql_result($rsq,0,0);?></TD>
                            <? $rsq=mysql_query("select count(1),group_concat(khmc,'\n') from crm_khb where yikayin_zhtime>'".this_monday(0,false)."' and calloutfpsj is null and left(xsry,instr(xsry,'/')-1) in (select bh from crm_callconfig where team='".mysql_result($rs,$i,"team")."')",$conn);?>
							<TD align="center" title="<? echo mysql_result($rsq,0,1);?>"><? echo mysql_result($rsq,0,0);?></TD>
                            <? $rsq=mysql_query("select count(1),group_concat(khmc,'\n') from crm_khb where yikayin_zhtime>'".this_monday(0,false)."' and left(xsry,instr(xsry,'/')-1) in (select bh from crm_callconfig where team='".mysql_result($rs,$i,"team")."')",$conn);?>
							<TD align="center" title="<? echo mysql_result($rsq,0,1);?>"><? echo mysql_result($rsq,0,0);?></TD>
							
						
                            <? $rsq=mysql_query("select count(1),group_concat(khmc,'\n') from crm_khb where (xsryfpsj>'".date('Y-m-')."01' and not calloutfpsj is null and left(xsry,instr(xsry,'/')-1) in (select bh from crm_callconfig where team='".mysql_result($rs,$i,"team")."')) or (xsryfpsj is null and state=-2 and instr((select dq from crm_teamdq where team='".mysql_result($rs,$i,"team")."'),province)>0)",$conn);?>
							<TD align="center" title="<? echo mysql_result($rsq,0,1);?>"><? echo mysql_result($rsq,0,0);?></TD>
                            <? $rsq=mysql_query("select count(1),group_concat(khmc,'\n') from crm_khb where yikayin_zhtime>'".date('Y-m-')."01' and not calloutfpsj is null and left(xsry,instr(xsry,'/')-1) in (select bh from crm_callconfig where team='".mysql_result($rs,$i,"team")."')",$conn);?>
							<TD align="center" title="<? echo mysql_result($rsq,0,1);?>"><? echo mysql_result($rsq,0,0);?></TD>
                            <? $rsq=mysql_query("select count(1),group_concat(khmc,'\n') from crm_khb where yikayin_zhtime>'".date('Y-m-')."01' and calloutfpsj is null and left(xsry,instr(xsry,'/')-1) in (select bh from crm_callconfig where team='".mysql_result($rs,$i,"team")."')",$conn);?>
							<TD align="center" title="<? echo mysql_result($rsq,0,1);?>"><? echo mysql_result($rsq,0,0);?></TD>
                            <? $rsq=mysql_query("select count(1),group_concat(khmc,'\n') from crm_khb where yikayin_zhtime>'".date('Y-m-')."01' and left(xsry,instr(xsry,'/')-1) in (select bh from crm_callconfig where team='".mysql_result($rs,$i,"team")."')",$conn);?>
							<TD align="center" title="<? echo mysql_result($rsq,0,1);?>"><? echo mysql_result($rsq,0,0);?></TD>

                            <? $rsq=mysql_query("select count(1),group_concat(khmc,'\n') from crm_khb where (not xsryfpsj is null and not calloutfpsj is null and left(xsry,instr(xsry,'/')-1) in (select bh from crm_callconfig where team='".mysql_result($rs,$i,"team")."')) or (xsryfpsj is null and state=-2 and instr((select dq from crm_teamdq where team='".mysql_result($rs,$i,"team")."'),province)>0)",$conn);?>
							<TD align="center" title="<? echo mysql_result($rsq,0,1);?>"><? echo mysql_result($rsq,0,0);?></TD>
                            <? $rsq=mysql_query("select count(1),group_concat(khmc,'\n') from crm_khb where not yikayin_zhtime is null and not calloutfpsj is null and left(xsry,instr(xsry,'/')-1) in (select bh from crm_callconfig where team='".mysql_result($rs,$i,"team")."')",$conn);?>
							<TD align="center" title="<? echo mysql_result($rsq,0,1);?>"><? echo mysql_result($rsq,0,0);?></TD>
                            <? $rsq=mysql_query("select count(1),group_concat(khmc,'\n') from crm_khb where not yikayin_zhtime is null and calloutfpsj is null and left(xsry,instr(xsry,'/')-1) in (select bh from crm_callconfig where team='".mysql_result($rs,$i,"team")."')",$conn);?>
							<TD align="center" title="<? echo mysql_result($rsq,0,1);?>"><? echo mysql_result($rsq,0,0);?></TD>
                            <? $rsq=mysql_query("select count(1),group_concat(khmc,'\n') from crm_khb where not yikayin_zhtime is null and left(xsry,instr(xsry,'/')-1) in (select bh from crm_callconfig where team='".mysql_result($rs,$i,"team")."')",$conn);?>
							<TD align="center" title="<? echo mysql_result($rsq,0,1);?>"><? echo mysql_result($rsq,0,0);?></TD>

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