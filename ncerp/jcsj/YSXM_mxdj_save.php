<? session_start();
require("../../inc/conn.php");require_once("img2thumb.php");?>
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7">
<title></title>
</head>
<? 
$uploaddir= '../Printing/';//设置上传的文件夹地址 
$MAX_SIZE = 50000000;//设置文件上传文件20000000byte=2M 
$FILES_NAME=$_FILES['zfile']['name']; 
$FILES_EXT=array('.pdf'); 
$file_ext=substr($FILES_NAME,strrpos($FILES_NAME,"."));
if($_FILES['zfile']['size']>$MAX_SIZE){ 
echo "文件大小超程序允许范围,5M！"; exit; 
} 
if(in_array($file_ext, $FILES_EXT)){//检查文件类型 
$_FILES['zfile']['name']=date("ymdHis").rand().".pdf"; 
$zfile=substr($_FILES['zfile']['name'],0,strlen($_FILES['zfile']['name'])-4);
$uploadfile = $uploaddir. $_FILES['zfile']['name'];//上传后文件的路径及文件名 
$uploadfile = iconv('utf-8','gb2312',$uploadfile);
move_uploaded_file($_FILES['zfile']['tmp_name'], $uploadfile); 
} else { 
echo $file_ext." 不是允许导入的文件类型，必须使用正确格式！"; 
exit; 
} 

$FILES_NAME=$_FILES['ffile']['name']; 
if ($FILES_NAME<>"") {
$FILES_EXT=array('.pdf'); 
$file_ext=substr($FILES_NAME,strrpos($FILES_NAME,"."));
if($_FILES['ffile']['size']>$MAX_SIZE){ 
echo "文件大小超程序允许范围,5M！"; exit; 
} 
if(in_array($file_ext, $FILES_EXT)){//检查文件类型 
$_FILES['ffile']['name']=date("ymdHis").rand().".pdf"; 
$ffile=substr($_FILES['ffile']['name'],0,strlen($_FILES['ffile']['name'])-4);
$uploadfile = $uploaddir. $_FILES['ffile']['name'];//上传后文件的路径及文件名 
$uploadfile = iconv('utf-8','gb2312',$uploadfile);
move_uploaded_file($_FILES['ffile']['tmp_name'], $uploadfile); 
} else { 
echo $file_ext." 不是允许导入的文件类型，必须使用正确格式！"; 
exit; 
} 
}

$uploaddir= '../TSGY/';//设置上传的文件夹地址 
$FILES_NAME=$_FILES['gfile']['name']; 
$_FILES['gfile']['name']=date("ymdHis").rand().$_FILES['gfile']['name']; 
if ($FILES_NAME<>'') $gfile="/TSGY/".$_FILES['gfile']['name']; else $gfile="";
$uploadfile = $uploaddir. $_FILES['gfile']['name'];//上传后文件的路径及文件名 
$uploadfile = iconv('utf-8','gb2312',$uploadfile);
move_uploaded_file($_FILES['gfile']['tmp_name'], $uploadfile); 
if ($_POST["cb"]==1) {
	$ffile=$zfile."S";
	pdf2('../Printing/'.$zfile.'.pdf','../showfile/'.$zfile.'.jpg',1);
	pdf2('../Printing/'.$zfile.'.pdf','../showfile/'.$zfile.'S.jpg',2);
} else {
	pdf2('../Printing/'.$zfile.'.pdf','../showfile/'.$zfile.'.jpg');
}
$mpk=$_POST["mpk"];
$mpg=$_POST["mpg"];
$xm=$_POST["xm"];
$zz=$_POST["zz"];
$gy=$_POST["gy"];
$sl=$_POST["sl"];
$jg=$_POST["jg"];
$ddh=$_POST["ddh"];
mysql_query("insert into order_mx values (0,'bip',0,'".$_SESSION["USERBH"]."','$xm','',0,'$zfile','$ffile',$sl,$jg,'$ddh','',$mpk,$mpg,'$zz','$gy','$gfile')",$conn);

//echo "<script>window.close();</script>";


function pdf2($pdf_file,$save_to,$pp) {
$save_to=iconv('utf-8','gb2312',$save_to);
$output ='';
$return_var=0;
$cmd="gswin32c.exe -dBATCH -dNOPAUSE -dFirstPage=$pp -dLastPage=$pp -dAlignToPixels=0 -dGridFitTT=0 -sDEVICE=jpeg -dTextAlphaBits=4 -dGraphicsAlphaBits=4 -sOutputFile=".$save_to." -dJPEGQ=100 -r300x300 -q ".$pdf_file." -c quit";
echo $cmd;
exec($cmd, $output, $return_var);
img2thumb($save_to,$save_to,0,224,0,0);  //同步模式可以直接生成
}
?> 