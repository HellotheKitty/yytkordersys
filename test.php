<?
$info=json_decode(file_get_contents("http://oa.skyprint.cn/mainb/Getkfry.php?tasktype=14&zh=aa"));
	$zzry=$info->kfry;
	echo $zzry;
