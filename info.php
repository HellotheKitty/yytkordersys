<?php echo $GLOBALS["taskFPtime"];
echo phpinfo();
if (!file_exists("inc/gl.sxs")) {
	$fp = fopen("inc/gl.sxs", "w"); 
	fwrite($fp,date('Y-m-d'));
} else {
	$fp = fopen("inc/gl.sxs", "r");
	if (fgets($fp)==date('Y-m-d'))
		echo "ok";
	else {
		$fp = fopen("inc/gl.sxs", "w"); 
		fwrite($fp,date('Y-m-d'));
	}
}

?>