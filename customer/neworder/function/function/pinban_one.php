<?
	function pinban($inPutDir, $file, $pdfd,$text,$hzx) {
	$sfile = $inPutDir.$file;
	$zzk=320;$zzg=464;
	$cx=3;
    $pdfd->AddPage($hzx,array($zzk,$zzg));   //纸张大小 7600
	if (substr($sfile,-3)=="pdf") {
		
		$pagecount = $pdfd->setSourceFile("{$sfile}");  //源文件pdf
		$tplidx = $pdfd->ImportPage(1); //文件的第一页
		$pdfd->useTemplate($tplidx,0,0,429,303);
	} elseif (substr($sfile,-3)=="jpg" or substr($sfile,-3)=="png") {
		list($width, $height, $type, $attr) = getimagesize($sfile);
		$width = $width*2.54/30;               //2.54的距离
		$height= $height*2.54/30;

		if($hzx=="P"){
			
		$lmargin=($zzk - $width  )/2;   //左右居中
		$tmargin=($zzg - $height  )/2;  //顶部留空
		
		$pdfd->Image($sfile, $lmargin,$tmargin,$width,$height);
			
		}else if($hzx=="L"){
			
        $tmargin=($zzk - $height  )/2;   //左右居中
		$lmargin=($zzg - $width  )/2;  //顶部留空
		
		$pdfd->Image($sfile, $lmargin,$tmargin,$width,$height);
		
		}
	}

    $file=explode(".", $file);
	$file=$file[0];
	// 左上
	$pdfd->line($lmargin - $cx, $tmargin, $lmargin - 2*$cx, $tmargin);		// 短横
	$pdfd->line($lmargin - $cx, $tmargin, $lmargin - $cx, $tmargin - 2*$cx);	// 长竖

	$pdfd->line($lmargin, $tmargin - $cx, $lmargin, $tmargin - 2*$cx);		// 短竖
	$pdfd->line($lmargin, $tmargin - $cx, $lmargin - 2*$cx, $tmargin - $cx);	// 长横

	// 右上
	$pdfd->line($lmargin + $width + $cx, $tmargin, $lmargin + $width + 2*$cx, $tmargin);		// 短横
	$pdfd->line($lmargin + $width + $cx, $tmargin, $lmargin + $width + $cx, $tmargin - 2*$cx);	// 长竖

	$pdfd->line($lmargin + $width, $tmargin - $cx, $lmargin + $width, $tmargin - 2*$cx);		// 短竖
	$pdfd->line($lmargin + $width, $tmargin - $cx, $lmargin + $width + 2*$cx, $tmargin - $cx);	// 长横

	// 左下
	$pdfd->line($lmargin - $cx, $tmargin + $height, $lmargin - 2*$cx, $tmargin + $height);		// 短横
	$pdfd->line($lmargin - $cx, $tmargin + $height, $lmargin - $cx, $tmargin + $height + 2*$cx);	// 长竖

	$pdfd->line($lmargin, $tmargin + $height + $cx, $lmargin, $tmargin + $height + 2*$cx);		// 短竖
	$pdfd->line($lmargin, $tmargin + $height + $cx, $lmargin - 2*$cx, $tmargin + $height + $cx);	// 长横

	// 右下
	$pdfd->line($lmargin + $width + $cx, $tmargin + $height, $lmargin + $width + 2*$cx, $tmargin + $height);	// 短横
	$pdfd->line($lmargin + $width + $cx, $tmargin + $height, $lmargin + $width + $cx, $tmargin + $height + 2*$cx);	// 长竖

	$pdfd->line($lmargin + $width, $tmargin + $height + $cx, $lmargin + $width, $tmargin + $height + 2*$cx);	// 短竖
	$pdfd->line($lmargin + $width, $tmargin + $height + $cx, $lmargin + $width + 2*$cx, $tmargin + $height + $cx);	// 长横
	$c=$c-2;
  	$images=$file;
	cptext(600,10,$text, $images);
	$pdfd->image("image/$images.png",2*$cx+$lmargin,$tmargin+$height+$cx);
}
//---------------单拼
?>