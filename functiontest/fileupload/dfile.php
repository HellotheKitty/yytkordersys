<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<? 
$file="scfiles/".$_GET["dfile"];
$file=iconv("utf-8","gbk",$file);
if(is_file($file)) { 
 header("Content-Type: application/force-download"); 
 header("Content-Disposition: attachment; filename=".basename($file)); 
 readfile($file); 
 exit; 
 }else{ 
 echo $_GET["dfile"],"文件不存在！请检查。"; 
 exit; 
 }
?>