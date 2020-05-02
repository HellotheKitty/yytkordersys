<?php
ini_set("memory_limit", "100M");//必须的，根据你环境的实际情况尽量大，防止报错
ini_set("max_execution_time", "100");
/*
 *
 *  Merge Upload fractions
 *
 *
 */
function merge_upload($uploadpath,$prename,$rname,$totalsize,$blocklength)
{
	//$fp = fopen("uploads/".$rname,"ab");

	global $rcontent;

	if(file_exists($uploadpath.$rname))
	{
		unlink($uploadpath.$rname);
	}

	$fp = fopen($uploadpath.$rname,"ab");
	$ofset = 0;

	for($ofset = 0; $ofset < $totalsize; $ofset = $ofset + $blocklength)
	{
		$tname = $uploadpath.$prename."_".$ofset."_".$totalsize."_".$rname;
		$handle = fopen($tname,"rb");
		$rcontent = fread($handle,filesize($tname));
		fwrite($fp,$rcontent);
		fclose($handle);

		unset($handle);

		unlink($tname);
	}

	fclose($fp);
}
/*
 *
 *
 *
 *
 *
 */
function mergerfiles($file1,$file2)
{
	$fp = fopen($file1,"ab");
	$handle = fopen($file2,"rb");
	$rcontent = fread($handle,filesize($file2));
	fwrite($fp,$rcontent);
	fclose($handle);
	fclose($fp);
	unlink($file2);

	@rename($file1,$file2);

}
/*
 *
 *   Transfer Init
 *
 *
 */
function init_upload($uploadpath,$prename,$rname,$totalsize,$blocklength)
{
	$ofset = 0;


	//Check uploaded fractions
	for($ofset = 0; $ofset < $totalsize; $ofset = $ofset + $blocklength)
	{
		$tname = $uploadpath.$prename."_".$ofset."_".$totalsize."_".$rname;

		if(file_exists($tname))
		{
			return $ofset+$blocklength;
		}
		else
		{

		}
	}

	return 0;
}

$taction = $_POST['taction'];
$userid = $_POST['userid'];
$charset = $_POST['chst'];
$uname = $_POST['name'];

$uname = iconv('utf-8',$charset,$uname);
$uname = iconv($charset,'GBK',$uname);
$tsize = $_POST['tsize'];
$len = $_POST['len'];
$filename = $_FILES['file']['name'];
$blocklen = $_POST['blocklen'];
$offset = $_POST['offset'];
$tmppath = "tmp";

if(file_exists($tmppath))
{

}
else
{
	mkdir($tmppath,0777,true);
}

$tmpuserdir = "tmp/".$userid;

if(file_exists($tmpuserdir))
{

}
else
{
	mkdir($tmpuserdir,0777,true);
}

$userdir = "upload/".$userid;
$mode = 0755;


if(file_exists($userdir))
{

}
else
{
	mkdir($userdir,0777,true);
}


if($taction == "transfer")
{

	if ($filename) {

		/*
		move_uploaded_file($_FILES["file"]["tmp_name"],
		  "uploads/" . $filename."_".$offset."_".$uname);
		  */
		move_uploaded_file($_FILES["file"]["tmp_name"],
				$tmpuserdir."/" . $filename."_".$offset."_".$tsize."_".$uname);

		if($offset > 0)
		{
			mergerfiles($tmpuserdir."/" . $filename."_".($offset-$blocklen)."_".$tsize."_".$uname,
					$tmpuserdir."/" . $filename."_".$offset."_".$tsize."_".$uname);
		}


	}

	if(($offset + $len) >= $tsize)
	{
		//merge_upload($userdir."/",$filename,$uname,$tsize,$blocklen);
		if(file_exists($userdir."/".$uname))
		{
			unlink($userdir."/".$uname);
		}
		@rename($tmpuserdir."/" . $filename."_".$offset."_".$tsize."_".$uname,$userdir."/".$uname);
	}
	//echo $uname."_".$tsize."_".$offset."_".$len;
	echo $offset + $len;
}
else
{
	if($taction == "transferinit")
	{
		$init_offset = init_upload($tmpuserdir."/",$filename,$uname,$tsize,$blocklen);

		//echo $init_offset."_".$userdir."/".$filename."_".$uname."_".$tsize."_".$blocklen;
		echo $init_offset;
	}
}




?>