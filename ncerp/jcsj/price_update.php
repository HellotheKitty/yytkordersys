<?php
require("../../inc/conn.php");
if($_GET["type"]){
	$type = $_GET["type"];
	$id = $_GET["id"];
	$np = $_GET["np"];
	if($type == "print")
		$table = "price_of_print";
	elseif($type == "afterprocess")
		$table = "price_of_afterprocess";
	elseif($type == "fumo")
		$table = "price_of_fumo";
	if(mysql_query("update $table set price='$np' where id='$id'",$conn))
		echo $id;
	else
		echo "0";
}else if($_GET["table"]){
	$id = $_GET["id"];
	if($_GET["table"] == "print")
		$table = "price_of_print";
	elseif($_GET['table'] == "afterprocess")
		$table = "price_of_afterprocess";
    elseif($_GET['table'] == "fumo")
        $table = "price_of_fumo";
	if(mysql_query("delete from $table where id='$id'", $conn))
		echo $id;
	else
		echo "0";
}


