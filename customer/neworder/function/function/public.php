<?
//$w 宽度，$fontsize 字体大小,$txt 文本,$file 生成图片文件名,$r$g$b颜色。
//example：cptext(400,20,"特殊工艺：1234","test7.png",255);
function cptext($w,$fontszie,$txt,$file,$r=0,$g=0,$b=0) {
    $im=imagecreate($w,$fontszie+4);
    $white=ImageColorAllocate($im, 255,255,255);
    $cc=ImageColorAllocate($im, $r,$g,$b);
    ImageTTFText($im,$fontszie, 0, 0, $fontszie+2, $cc, "font/aa.otf", $txt);
    Imagepng($im,"image/$file.png");
    ImageDestroy($im);
}
//----------文字转曲
function unzip_file($file, $destination = './'){ 
	$zip = new ZipArchive() ; 
	$zip->open($file);
	$zip->extractTo($destination); 
	$zip->close(); 
} 
//------解压，废弃
function outputpdf($fileout, $pdfd) {
	$pdfd->Output($fileout, 'F');
	$pdfd->close();
	unset($pdfd);
}
//---------输出pdf
function deldir($dir){
   $dh = opendir($dir);
   while ($file = readdir($dh))
   {
      if ($file != "." && $file != "..")
      {
         $fullpath = $dir . "/" . $file;
         if (!is_dir($fullpath))
         {
            unlink($fullpath);
         } else
         {
            deldir($fullpath);
         }
      }
   }
   closedir($dh);
   if (rmdir($dir))
   {
      return true;
   } else
   {
      return false;
   }
}
//----------删除文件
//  移动文件
function moveFile($fileUrl, $aimUrl) {
    if (!file_exists($fileUrl)) {
        return false;
    }

    rename($fileUrl, $aimUrl);
//    copy($fileUrl, $aimUrl);
//    unlink($fileUrl);
    return true;
}
//rotate
function imgrotate($file,$degree){
//逆时针
    //创建图像资源
    if(substr($file,-3)=='jpg'){
        $source = imagecreatefromjpeg($file);
        //rotate
        $imgr = imagerotate($source,$degree,0);
        //save
        imagejpeg($imgr,$file);
    }elseif(substr($file,-3)=='png'){
        $source = imagecreatefrompng($file);
        //rotate
        $imgr = imagerotate($source,$degree,0);
        //save
        imagepng($imgr,$file);
    }elseif(substr($file,-3)=='gif'){
        $source = imagecreatefromgif($file);
        //rotate
        $imgr = imagerotate($source,$degree,0);
        //save
        imagegif($imgr,$file);
    }

}
?>