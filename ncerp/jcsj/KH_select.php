<? require("../../inc/conn.php");
session_start();
?>
<HTML>
<HEAD>
    <TITLE>易卡工坊--文件上传</TITLE>
    <META content="MSHTML 6.00.2800.1276" name=GENERATOR>
    <META http-equiv=Content-Type content="text/html; charset=utf-8">
    <META http-equiv=Content-Style-Type content=text/css>
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<meta name="viewport" content="width=device-width,minimum-scale=1.0"/>

<LINK href="../../css/mainWin.css" type=text/css  media=screen rel=stylesheet>
<LINK href="../../css/mainWin2.css" type=text/css  media=screen rel=stylesheet>
<LINK href="../../css/swfUpload.css" type=text/css  media=screen rel=stylesheet>
	
    <base target="_self">
</HEAD>
<?
?>
<body marginwidth="0" topmargin="0" leftmargin="0" marginheight="0">
	
	<DIV ID=MainArea>
        <form method="post" id="actForm" name="actForm"  action="">
			<CENTER>
            <TABLE WIDTH=100% CELLSPACING=0 CELLPADDING=0 BORDER=0 ALIGN=CENTER>
                
                    <TR HEIGHT=27>
                        <TD width="430"> 查找客户名称(只显示100条，请输入条件查找)</TD>
                        <TD COLSPAN=2>&nbsp;</TD>
                    </TR>
                    <TR>
                        <TD colspan="3"><span style="padding:0px 10px 0px 10px;">客户名称
  <input name="t1" type="text" id="t1" size="15" />
                        客户编号
<input name="t2" type="text" id="t2" size="10" />
                        联系人
<input name="t3" type="text" id="t3" size="10" />
                        手机号
<input name="t4" type="text" id="t4" size="10" />
                        QQ
<input name="t5" type="text" id="t5" size="8" />
  <input type="submit" name="button2" id="button2" value="查找" />
                      </span></TD>
                    </TR>
                
                
            </TABLE>

				<ul STYLE="margin: 0px;">
<LI STYLE="width: 100%;">
						<TABLE WIDTH=90% border="1" align="center" cellspacing="0" cellpadding="0" style="border-width:0px;border-collapse:collapse;border:1px solid #D3D3D3; border-bottom:1px solid #D3D3D3; font-size:12px">
                        <? $tj = "gdzk='".substr($_SESSION["GDWDM"],0,4)."'";
                            if($_POST["t1"]<>"") $tj.=" and khmc like '%".$_POST["t1"]."%'";
                        if($_POST["t2"]<>"") $tj.=" and mpzh like '%".$_POST["t2"]."%'";
                        if($_POST["t3"]<>"") $tj.=" and lxr like '%".$_POST["t3"]."%'";
                        if($_POST["t4"]<>"") $tj.=" and lxdh like '%".$_POST["t4"]."%'";
                        if($_POST["t5"]<>"") $tj.=" and qq like '%".$_POST["t5"]."%'";
		//$rs2=mysql_query("select khmc,lxr,lxdh,lxdz,mpzh,qq from base_kh where khmc like '%".$_POST["t1"]."%' and lxr like '%".$_POST["t3"]."%' and lxdh like '%".$_POST["t4"]."%' and qq like '%".$_POST["t5"]."%' order by khmc limit 100",$conn);
        //              $rs2=mysql_query("select khmc,lxr,lxdh,lxdz,mpzh,qq from base_kh where khmc like '%".$_POST["t1"]."%'  order by khmc limit 100",$conn);
                        $rs2=mysql_query("select khmc,lxr,lxdh,lxdz,mpzh,qq from base_kh where $tj  order by khmc limit 100",$conn);
        //mysql_query返回false值时，mysql_fetch_row报错。先判断$rs2的值。
        if($rs2)
		while ($row=mysql_fetch_row($rs2)) {  ?>
							<TR>
								<TD HEIGHT=20 CLASS=NavigationListTitle ><? if($_GET["lx"]=="task") echo "<a href='javascript:void(0);' onclick='window.opener.document.form1.zh.value=\"".$row[4]."\";window.opener.document.getElementById(\"khmc\").innerHTML=\"".$row[0]."\";window.close();'>$row[4]-$row[0]</a>";else echo "<a href='#' onclick='window.opener.document.form1.khmc.value=\"".$row[0]."\";window.opener.document.form1.shr.value=\"".$row[1]."\";window.opener.document.form1.shdh.value=\"".$row[2]."\";window.opener.document.form1.shdz.value=\"".$row[3]."\";window.close();'>$row[0]</a>";?></TD>
								<TD CLASS=GrayDottedLine WIDTH=80><? echo $row[1]?></TD>
								<TD CLASS=GrayDottedLine WIDTH=80><? echo $row[2]?></TD>
                                <TD CLASS=GrayDottedLine WIDTH=80><? echo $row[5]?></TD>
							</TR>
                            <? }
						?>
						</TABLE>
					</LI>
			  </ul>
			</CENTER>
		</form>
	</DIV>
</body>
</HTML>
