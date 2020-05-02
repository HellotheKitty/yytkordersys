<?  
	function pinban_double_double($inPutDir, $file,$files , $pdfd,$text,$x1,$x2){
	$zzk=320;$zzg=464;
	$cx=3;
	$file_arr=explode("A", $file);
	$file_arrs=explode("B", $files);
	
	$sfile1 = $inPutDir.$file_arr[0];
	$sfile2 = $inPutDir.$file_arr[1];
	$sfile3 = $inPutDir.$file_arrs[0];
	$sfile4 = $inPutDir.$file_arrs[1];
    
	list($width, $height, $type, $attr) = getimagesize($sfile2);
	$width = $width*2.54/30;               //2.54的距离
	$height= $height*2.54/30;
	$lmargin_1=($zzg - $width*2+6 )/2;   //左右居中
	$tmargin_1=($zzk - $height  )/2;  //顶部留空
	$lmargin_2=$lmargin_1+$width+6;
	$tmargin_2=($zzk - $height  )/2;  //顶部留空
	$pdfd->AddPage('L',array($zzk,$zzg));   //纸张大小 7600
	
	$pdfd->Image($sfile1, $lmargin_1,$tmargin_1,$width,$height);
	if($file_arr[1]!=""){
    $pdfd->Image($sfile2, $lmargin_2,$tmargin_2,$width,$height);
	}
    
	
	$file=explode(".", $file_arr[0]);
	$file=$file[0];
	

	
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
    //居中线  上
//  $pdfd->Line($lmargin_1+$width/2-$cx,$tmargin_1 - $cx-$cx/4,$lmargin_1+$width/2+$cx,$tmargin_1 - $cx-$cx/4);       //横
//  $pdfd->Line($lmargin_1+$width/2,$tmargin_1 - $cx,$lmargin_1+$width/2,$tmargin_1 - 2*$cx);             //竖
//  
//  $pdfd->Line($lmargin_1+$width*3/2-$cx+6*$cx,$tmargin_1 - $cx-$cx/4,$lmargin_1+$width*3/2+7*$cx,$tmargin_1 - $cx-$cx/4);       //横
//  $pdfd->Line($lmargin_1+$width*3/2+6*$cx,$tmargin_1 - $cx,$lmargin_1+$width*3/2+8*$cx,$tmargin_1 - 2*$cx);             //竖
//  
//  //居中线  下	
//  $pdfd->Line($lmargin_1+$width/2-$cx,$tmargin_1 +$height+ $cx-$cx/4,$lmargin_1+$width/2+$cx,$tmargin_1+$height + $cx-$cx/4);       //横
//  $pdfd->Line($lmargin_1+$width/2,$tmargin_1 +$height+ $cx,$lmargin_1+$width/2,$tmargin_1+$height + $cx);             //竖
//  
//  $pdfd->Line($lmargin_1+$width*3/2-$cx+6*$cx,$tmargin_1+$height+ $cx-$cx/4,$lmargin_1+$width*3/2+7*$cx,$tmargin_1 +$height+ $cx-$cx/4);       //横
//  $pdfd->Line($lmargin_1+$width*3/2+6*$cx,$tmargin_1+$height+ $cx,$lmargin_1+$width*3/2+8*$cx,$tmargin_1 +$height+ $cx);             //竖
    
    
	$images=$file;
	cptext(600,10, $text.$x1, $images);
	$pdfd->image("image/$images.png",2*$cx+$lmargin_1,$tmargin_2+$height+$cx);
	
	//纸张大小 7600
	$pdfd->AddPage('L',array($zzk,$zzg));
	if($file_arr[0]!=$file_arrs[0]&$file_arr[1]!=$file_arrs[1]){
	if($file_arrs[0]!=""){
	$pdfd->Image($sfile3, $lmargin_1,$tmargin_1,$width,$height);
	}
	if($file_arrs[0]!=$file_arrs[1]){
	if($file_arrs[1]!=""){
	$pdfd->Image($sfile4, $lmargin_2,$tmargin_2,$width,$height);
	  }
	}
}
	
	
	$files=explode(".", $file_arrs[0]);
	$files=$files;
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
	cptext(600,10, $text.$x2, $images);
	$pdfd->image("image/$images.png",2*$cx+$lmargin_1,$tmargin_2+$height+$cx);
}
//----------一页拼双，双面
?>