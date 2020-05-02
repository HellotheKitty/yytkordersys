<?php
require("../../inc/conn.php");
if($_GET["type"] == "1"){
	$khmc = mysql_result(mysql_query("select khmc from order_mainqt where ddh='".$_GET["ddh"]."'",$conn),0,"khmc");
	$pricers = mysql_query("select price from price_of_print where khmc='".$khmc."' and machine='".$_GET["machine"]."' and dsm='".$_GET["dsm"]."' and materialid='".$_GET["paper"]."' and unit='".$_GET["jldw"]."'",$conn);
	echo "select price from price_of_print where khmc='".$khmc."' and machine='".$_GET["machine"]."' and dsm='".$_GET["dsm"]."' and materialid='".$_GET["paper"]."' and unit='".$_GET["jldw"]."'";
	$price = 0;
	if($pricers && mysql_num_rows($pricers) > 0)
		$price = mysql_result($pricers,0,"price");
	echo $price;
}else if($_GET["type"] == 2){
	$khmc = mysql_result(mysql_query("select khmc from order_mainqt where ddh='".$_GET["ddh"]."'",$conn),0,"khmc");
	$pricers = mysql_query("select price from price_of_afterprocess where khmc='".$khmc."' and afterprocess='".$_GET["jgfs"]."' and chicun='".$_GET["cpcc"]."' and unit='".$_GET["jldw"]."'",$conn);
//	echo "select price from price_of_afterprocess where khmc='".$khmc."' and afterprocess='".$_GET["jgfs"]."' and chicun='".$_GET["cpcc"]."' and unit='".$_GET["jldw"]."'";
	$price = 0;
	if($pricers && mysql_num_rows($pricers) > 0)
		$price = mysql_result($pricers,0,"price");
	echo $price;
}
