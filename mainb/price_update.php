<?php
if($_GET["type"]){
	require("../inc/conn.php");
	$type = $_GET["type"];
	$id = $_GET["id"];
	$np = $_GET["np"];
	if($type == "print")
		$table = "price_of_print";
	else
		$table = "price_of_afterprocess";

	if(mysql_query("update $table set price='$np' where id='$id'",$conn))
		echo $id;
	else
		echo "0";
}

