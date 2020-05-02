<?php
require("inc/conn.php");
$bhrs = mysql_query("select bh,xm from b_ry where qx in ('sc','hd') and id>105 and (dwdm=330100 or dwdm=330300)", $conn);
while($bharr = mysql_fetch_array($bhrs)) {
	$bh = '%5B'.$bharr[0].'%5D';
	$src = "http://barcode.cnaidc.com/html/cnaidc.php?filetype=PNG&dpi=300&scale=4&rotation=0&font_family=Arial.ttf&font_size=8&text=".$bh."&thickness=30&start=NULL&code=BCGcode128";
	echo $bharr[1]."<img src='".$src."' /><br><br><br><br>";
}
