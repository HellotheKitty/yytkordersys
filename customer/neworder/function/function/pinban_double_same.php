<?
	function pinban_two_double($inPutDir, $files, $pdfd,$text){         //两个相同的
	$zzk=320;$zzg=464;
	$cx=3;

	list($width, $height, $type, $attr) = getimagesize($inPutDir.$files);
	$width = $width*2.54/30;               //2.54的距离
	$height= $height*2.54/30;
	$lmargin_1=($zzg - $width*2+6 )/2;   //左右居中
	$tmargin_1=($zzk - $height  )/2;  //顶部留空
	$lmargin_2=$lmargin_1+$width+6;
	$tmargin_2=($zzk - $height  )/2;  //顶部留空
	
	$file=explode(".", $file);
	$file=$file[0];
	
	$pdfd->AddPage('L',array($zzk,$zzg));   //纸张大小 7600
	
	$pdfd->Image($inPutDir.$files, $lmargin_1,$tmargin_1,$width,$height);
	$pdfd->Image($inPutDir.$files, $lmargin_2,$tmargin_2,$width,$height);
	// 左上
	$pdfd->line($lmargin_1 - $cx, $tmargin_1, $lmargin_1 - 2*$cx, $tmargin_1);		// 短横
	$pdfd->line($lmargin_1 - $cx, $tmargin_1, $lmargin_1 - $cx, $tmargin_1 - 2*$cx);	// 长竖

	$pdfd->line($lmargin_1, $tmargin_1 - $cx, $lmargin_1, $tmargin_1 - 2*$cx);		// 短竖
	$pdfd->line($lmargin_1, $tmargin_1 - $cx, $lmargin_1 - 2*$cx, $tmargin_1 - $cx);	// 长横

	// 右上
	$pdfd->line($lmargin_2 + $width + $cx, $tmargin_2, $lmargin_2 + $width + 2*$cx, $tmargin_2);		// 短横
	$pdfd->line($lmargin_2 + $width + $cx, $tmargin_2, $lmargin_2 + $width + $cx, $tmargin_2 - 2*$cx);	// 长竖

	$pdfd->line($lmargin_2 + $width, $tmargin_2 - $cx, $lmargin_2 + $width, $tmargin_2 - 2*$cx);		// 短竖
	$pdfd->line($lmargin_2 + $width, $tmargin_2 - $cx, $lmargin_2 + $width + 2*$cx, $tmargin_2 - $cx);	// 长横

	// 左下
	$pdfd->line($lmargin_1 - $cx, $tmargin_1 + $height, $lmargin_1 - 2*$cx, $tmargin_1 + $height);		// 短横
	$pdfd->line($lmargin_1 - $cx, $tmargin_1 + $height, $lmargin_1 - $cx, $tmargin_1 + $height + 2*$cx);	// 长竖

	$pdfd->line($lmargin_1, $tmargin_1 + $height + $cx, $lmargin_1, $tmargin_1 + $height + 2*$cx);		// 短竖
	$pdfd->line($lmargin_1, $tmargin_1 + $height + $cx, $lmargin_1 - 2*$cx, $tmargin_1 + $height + $cx);	// 长横

	// 右下
	$pdfd->line($lmargin_2 + $width + $cx, $tmargin_2 + $height, $lmargin_2 + $width + 2*$cx, $tmargin_2 + $height);	// 短横
	$pdfd->line($lmargin_2 + $width + $cx, $tmargin_2 + $height, $lmargin_2 + $width + $cx, $tmargin_2 + $height + 2*$cx);	// 长竖

	$pdfd->line($lmargin_2 + $width, $tmargin_2 + $height + $cx, $lmargin_2 + $width, $tmargin_2 + $height + 2*$cx);	// 短竖
	$pdfd->line($lmargin_2 + $width, $tmargin_2 + $height + $cx, $lmargin_2 + $width + 2*$cx, $tmargin_2 + $height + $cx);	// 长横

	//中上
	$pdfd->Line($lmargin_1 - $cx+$width, $tmargin_1 - $cx-$cx/4,$lmargin_1 - $cx+$width+4*$cx, $tmargin_1 - $cx-$cx/4);   //长横

	$pdfd->Line($lmargin_1+$width, $tmargin_1 - $cx,$lmargin_1+$width, $tmargin_1 - 2*$cx);                //竖一
	$pdfd->Line($lmargin_1+$cx+$width, $tmargin_1 - $cx,$lmargin_1 +$width+$cx, $tmargin_1 - 2*$cx);       //竖二
    $pdfd->Line($lmargin_1+2*$cx+$width, $tmargin_1 - $cx,$lmargin_1 +$width+2*$cx, $tmargin_1 - 2*$cx);   //竖三
    
    //中下
    $pdfd->Line($lmargin_1 - $cx+$width, $tmargin_1 + $height + $cx,$lmargin_1 - $cx+$width+4*$cx, $tmargin_1 + $height + $cx);   //长横
    
    $pdfd->Line($lmargin_1+$width, $tmargin_1 + $height + $cx-$cx/4,$lmargin_1+$width, $tmargin_1 + $height + 2*$cx-$cx/4);                //竖一
	$pdfd->Line($lmargin_1+$cx+$width, $tmargin_1 + $height + $cx-$cx/4,$lmargin_1 +$width+$cx, $tmargin_1 + $height + 2*$cx-$cx/4);       //竖二
    $pdfd->Line($lmargin_1+2*$cx+$width, $tmargin_1 + $height + $cx-$cx/4,$lmargin_1 +$width+2*$cx, $tmargin_1 + $height + 2*$cx-$cx/4);   //竖三
	
	$images=$files;
	cptext(600,10, $text, $images);
	$pdfd->image("image/$images.png",2*$cx+$lmargin_1,$tmargin_1+$height+$cx);
	
}
//----------双拼
?>