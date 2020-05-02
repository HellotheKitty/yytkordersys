<? require("../inc/conn.php"); 
session_start();
if ($_SESSION["YKOAOK"]<>"OK" and $_GET["callid"]=="") { 
echo "<script language=JavaScript>{window.location.href='error.php';}</script>";
exit; }?>
<HTML>
<HEAD>
    <TITLE>易卡工坊--文件上传</TITLE>
    <META content="MSHTML 6.00.2800.1276" name=GENERATOR>
    <META http-equiv=Content-Type content="text/html; charset=utf-8">
    <META http-equiv=Content-Style-Type content=text/css>
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<meta name="viewport" content="width=device-width,minimum-scale=1.0"/>

<LINK href="../css/mainWin.css" type=text/css  media=screen rel=stylesheet>
<LINK href="../css/mainWin2.css" type=text/css  media=screen rel=stylesheet>
<LINK href="../css/swfUpload.css" type=text/css  media=screen rel=stylesheet>
	
    <base target="_self">
</HEAD>
<?
?>
<body marginwidth="0" topmargin="0" leftmargin="0" marginheight="0">
	<DIV ID=Title_bar>
		<DIV ID=Title_bar_Head500>
			<DIV ID=Title_Head></DIV>
			<DIV ID=Title>
				<img border="0" src="../images/title_arrow2.gif" />选择账户
			</DIV>
			<DIV ID=Title_End></DIV>
			<DIV ID=Title_bar_bg></DIV>
		</DIV>
		<DIV ID=Title_bar_Tail500>
			<DIV ID=Title_FuncBar>
				<ul>
					<LI CLASS=line></LI>
					<LI CLASS=title><a name="BTN8043415675850L1" href=""></a>
    <div onClick="window.close();" class="Btn">关闭</div>
</LI>
					
					<LI CLASS=line></LI>
				</ul>
			</DIV>
		</DIV>
	</DIV>
		
	<DIV ID=MainArea>
        <form method="post" id="actForm" name="actForm"  action="">
			<CENTER>
            <TABLE WIDTH=100% CELLSPACING=0 CELLPADDING=0 BORDER=0 ALIGN=CENTER>
                
                    <TR HEIGHT=27>
                        <TD WIDTH=30></TD>
                        <TD width="430"><img border="0" src="../images/item_point.gif" /> 选择对应的名片账户</TD>
                        <TD COLSPAN=2>&nbsp;</TD>
                    </TR>
                    <TR>
                        <TD></TD>
                        <TD colspan="3"><span style="padding:0px 10px 0px 10px;">登录名
<input name="t1" type="text" id="t1" size="10" />
姓名
<input name="t3" type="text" id="t3" size="10" />
手机号
<input name="t4" type="text" id="t4" size="10" />
单位
<input name="t2" type="text" id="t2" size="15" />
<input type="submit" name="button2" id="button2" value="查找" />
                    </span></TD>
                    </TR>
                
                
            </TABLE>

				<ul STYLE="margin: 0px;">
<LI STYLE="width: 100%;">
						<TABLE WIDTH=90%>
                        <? if ($_POST["t1"]<>"" or $_POST["t2"]<>"" or $_POST["t3"]<>"" or $_POST["t4"]<>"") {
		$rs2=mysql_query("select zh,depart,id,dbname from nc_erp.v_base_user where zh like '%".$_POST["t1"]."%' and depart like '%".$_POST["t2"]."%' and xm like '%".$_POST["t3"]."%' and mobile like '%".$_POST["t4"]."%' order by zh limit 50",$conn);
		while ($row=mysql_fetch_row($rs2)) {    ?>
							<TR>
								<TD HEIGHT=10 CLASS=NavigationListTitle WIDTH=100><? echo ($row[3]!="yikab"?"":"[<a href='#' class='nav' onClick='javascript:window.open(\"http://mp.yikayin.com/YK_kf_user.php?YIKAZH=".base_encode($row[0])."\", \"HT_dhdj\", \"toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=yes,width=850,height=800,left=580,top=100\")'>用户详情</a>]&nbsp;&nbsp;");?></TD>
								<TD CLASS=GrayDottedLine><? echo "<a href='#' onclick='window.opener.document.lxxx.zh.value=\"".$row[0].($row[3]=="yikab"?"":"^".$row[3])."\";window.close();'>$row[0]-$row[1]".($row[3]=="yikab"?"":"/".$row[3])."</a>";?></TD>
							</TR>
                            <? }
						}?>
						</TABLE>
					</LI>
					<LI CLASS=Layer2 STYLE="width: 95%;"></LI>
			  </ul>
			</CENTER>
		</form>
	</DIV>
</body>
</HTML>