<?
require "public.php";
$ddh=$_GET['ddh'];
$path=substr($ddh, -6);
$path="../BillFiles/".$path;

if(file_exists($path)==TRUE){

    deldir($path);
    exit;
}
?>