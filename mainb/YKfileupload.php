<?
header("Content-type: text/html; charset=utf-8");

session_start();
?>
<HTML>
<HEAD>
    <TITLE>文件上传</TITLE>
    <META content="MSHTML 6.00.2800.1276" name=GENERATOR>
    <META http-equiv=Content-Type content="text/html; charset=utf-8">
    <META http-equiv=Content-Style-Type content=text/css>
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<meta name="viewport" content="width=device-width,minimum-scale=1.0"/>
    <base target="_self">
</HEAD>
<? 
require("inc/conn.php");
require_once("img2thumb.php");
require_once("size3to.php");

if ($_FILES['userfile']['name']<>"") {
    $uploaddir= 'Printit3/';//设置上传的文件夹地址
    $FILES_NAME=$_FILES['userfile']['name'];
    $FILES_EXT=array('.pdf');//设置允许上传文件的类型
    $MAX_SIZE = 1000000000;//设置文件上传文件20000000byte=2M
    if($_FILES['userfile']['size']>$MAX_SIZE){//检查文件大小
        echo "文件大小超程序允许范围！";
        exit;
    }
//if(in_array($file_ext, $FILES_EXT)){//检查文件类型 
$rs=mysql_query("select mbc,mbk,smd5,TSGYfile,mbbh from temp_ry where id=".$_GET["id"],$conn);
$_FILES['userfile']['name']=mysql_result($rs,0,2).".pdf";
$fn=substr($_FILES['userfile']['name'],0,-4);
$uploadfile = $uploaddir. $_FILES['userfile']['name'];//上传后文件的路径及文件名
$uploadfile1 = iconv('utf-8','gbk',$uploadfile);
move_uploaded_file($_FILES['userfile']['tmp_name'],$uploadfile1); //用move函数生成临时文件名，并按照 $_FILES['userfile']['name']上传到$uploaddir下

if (!file_exists('Printit3/'.$fn.'.pdf')) {echo "上传错误！请重新上传！";exit;}


    if (substr(mysql_result($rs,0,"mbbh"), -3) == '-GP') {
        $cardsWidth = (int)mysql_result($rs, 0, "mbc") + 6;
        $cardsHeight = (int)mysql_result($rs, 0, "mbk") + 6;
        $_SESSION['b54'] = (int)mysql_result($rs, 0, "mbc");
        $_SESSION['b90'] = (int)mysql_result($rs, 0, "mbk");

        if ($cardsWidth > $cardsHeight) {
	        $cardsWidth = (int)mysql_result($rs, 0, "mbk") + 6;
	        $cardsHeight = (int)mysql_result($rs, 0, "mbc") + 6;
	        $_SESSION['b54'] = (int)mysql_result($rs, 0, "mbk");
	        $_SESSION['b90'] = (int)mysql_result($rs, 0, "mbc");
	    }
    } else {
        $cardsWidth = 60; //以竖版为基础
        $cardsHeight = 96;
        $_SESSION['b54'] = 54;
        $_SESSION['b90'] = 90;
    }



if ($_POST["shuban"]=="1") $_SESSION["YKSHU"]=1; else $_SESSION["YKSHU"]=0;
if ($_POST["tj"]<>"zhuanqu") {
	$fng=$fn;
  if (substr(mysql_result($rs,0,3),-4,4)==".pdf") {   //叠加显示
	//require_once('fpdi.php');
	//class PDF extends fpdi{}
	$gfile = mysql_result($rs,0,"TSGYfile");
	if (!file_exists($gfile)) $gfile= "TSGY/".$gfile;
	$pdf=new PDF();
	$pdf->SetAutoPageBreak(1,1);
	$pdf->Open();
	$pdf->AliasNbPages();
	$pagecount = $pdf->setSourceFile("Printit3/".$fn.".pdf");
	$tplidx = $pdf->ImportPage(1); //模板文件的第一页作为模板
	if ($_POST["shuban"]=="1") {
		$pdf->AddPage('P',array($cardsHeight,$cardsWidth));
		$pdf->useTemplate($tplidx,0,0,$cardsWidth,$cardsHeight);
		$pagecount = $pdf->setSourceFile($gfile);
		$tplidx = $pdf->ImportPage(1); //模板文件的第一页作为模板
		$pdf->useTemplate($tplidx,0,0,$cardsWidth,$cardsHeight);

		$pdf->AddPage('P',array($cardsHeight,$cardsWidth));
		$pagecount = $pdf->setSourceFile("Printit3/".$fn.".pdf");
		$tplidx = $pdf->ImportPage(2); //模板文件的第一页作为模板
		$pdf->useTemplate($tplidx,0,0,$cardsWidth,$cardsHeight);
		$pagecount = $pdf->setSourceFile($gfile);
		$tplidx = $pdf->ImportPage(2); //模板文件的第一页作为模板
		$pdf->useTemplate($tplidx,0,0,$cardsWidth,$cardsHeight);
	} else {
		$pdf->AddPage('L',array($cardsHeight,$cardsWidth));
		$pdf->useTemplate($tplidx,0,0,$cardsHeight,$cardsWidth);
		$pagecount = $pdf->setSourceFile($gfile);
		$tplidx = $pdf->ImportPage(1); //模板文件的第一页作为模板
		$pdf->useTemplate($tplidx,0,0,$cardsHeight,$cardsWidth);

		$pdf->AddPage('L',array($cardsHeight,$cardsWidth));
		$pagecount = $pdf->setSourceFile("Printit3/".$fn.".pdf");
		$tplidx = $pdf->ImportPage(2); //模板文件的第一页作为模板
		$pdf->useTemplate($tplidx,0,0,$cardsHeight,$cardsWidth);
		$pagecount = $pdf->setSourceFile($gfile);
		$tplidx = $pdf->ImportPage(2); //模板文件的第一页作为模板
		$pdf->useTemplate($tplidx,0,0,$cardsHeight,$cardsWidth);
	}
	$fng=$fn."G";
	$pdf->Output('Printit3/'.$fn.'G.pdf', 'F');
	$pdf->close();
	unset($pdf);
  }

	pdf2('Printit3/'.$fng.'.pdf','showfile/'.$fn.'Z.jpg',mysql_result($rs,0,0),mysql_result($rs,0,1),1);
	pdf2('Printit3/'.$fng.'.pdf','showfile/'.$fn.'F.jpg',mysql_result($rs,0,0),mysql_result($rs,0,1),2);
	mysql_query("update temp_ry set mbid=-1,sl=0 where id=".$_POST["id"],$conn);
	mysql_query("update nccheck set wctime=now(),dealry='".$_SESSION["ZZUSER"]."' where wctime is null and temp_ry_id=".$_POST["id"],$conn);


} else {  //转曲
	//mysql_query("update temp_ry set sl=101 where id=".$_POST["id"],$conn);
}

	
	if ($_POST["shuban"]=="1") {    //旋转
		copy("printit3/".$fn.".pdf","temp.rr");
		$cmd="pdftk.exe temp.rr cat 1east 2west output printit3/".$fn.".pdf";
		exec($cmd, $output, $return_var);	
		unlink("temp.rr");
	}
	if (!size3to('Printit3/'.$fn.'.pdf','Printit/'.$fn.'.pdf',3,1.5)) {
		if (!size3to('Printit3/'.$fn.'.pdf','Printit/'.$fn.'.pdf',3,1.5)) echo "生成印刷文件错误，请报告技术检查！";
	};
	if (!size3to('Printit3/'.$fn.'.pdf','Printit1/'.$fn.'.pdf',3,1)) {
		if (!size3to('Printit3/'.$fn.'.pdf','Printit1/'.$fn.'.pdf',3,1)) echo "生成印刷文件错误，请报告技术检查！";
	};

echo "上传完成！";
$rs=mysql_query("select base_user.mobile from base_user,temp_ry where zh=user and temp_ry.id=".$_POST["id"],$conn);
if (strlen(mysql_result($rs,0,0))>10) {   //发送短信通知
echo "<br><br><br><a href='YK_newman_save.php?dxtz=".mysql_result($rs,0,0)."'>发送短信通知用户</a>";
}
exit;
}

$rs=mysql_query("select smd5 from temp_ry where id=".$_GET["id"],$conn);
?>
<body marginwidth="0" topmargin="0" leftmargin="0" marginheight="0">
	<DIV ID=Title_bar>文件上传　　<a href='Printit3/<? echo mysql_result($rs,0,0);?>.pdf'>下载3mm文件</a>　　<a href='Printit/<? echo mysql_result($rs,0,0);?>.pdf'>下载1.5mm文件</a>　　<a href='Printing/<? echo mysql_result($rs,0,0);?>.pdf'>下载源文件</a></DIV>
		<TABLE CELLSPACING=0 CELLPADDING=0 BORDER=0>
			<TR VALIGN=MIDDLE>
				<TD WIDTH=30></TD>
				<TD><input onClick="" type="radio" value="1" name="uploadType" checked="checked" /></TD>
				<TD>从本地上传</TD>
			</TR>
		</TABLE>
	<DIV ID=MainArea>
        <form method="post" id="actForm" name="actForm" enctype="multipart/form-data" action="">
			<CENTER>
            <TABLE WIDTH=100% CELLSPACING=0 CELLPADDING=0 BORDER=0 ALIGN=CENTER>
                
                    <TR HEIGHT=27>
                        <TD WIDTH=30></TD>
                        <TD>从本地上传(3mm出血文件，<font color=red>注意小胶印页数</font>)
                          <input type="hidden" name="tj" value="<? echo $_GET["tj"];?>" /><input type="hidden" name="id" value="<? echo $_GET["id"];?>" /></TD>
                      <TD COLSPAN=2><input type="checkbox" name="shuban" id="shuban" value="1">
                        竖版名片</TD>
                    </TR>
                    <TR>
                        <TD></TD>
                        <TD WIDTH=430><input size="50" class="InputStyle" type="file" name="userfile" /></TD>
                        <TD WIDTH=5></TD>
                        <TD><input class="ButtonStyle" style="width:100px;" onClick="beforeSubmit(this)" type="button" value="上传" name="sss" /></TD>
                    </TR>
                
                
            </TABLE>

			</CENTER>
		</form>
	</DIV>
<script>
    function beforeSubmit(i) {
        i.value = '正在上传...';
        setTimeout("document.getElementById('actForm').submit()", 100);
    }
</script>
</body>
</HTML>