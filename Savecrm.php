<?
$id=$_POST["ID"];
$user=$_POST["user"];
$pass=$_POST["pass"];
$mysql_server_name='60.191.88.122:3386';
$mysql_username='root';
$mysql_password='Winner88382383';
$mysql_database='oa';
$conn=mysql_connect($mysql_server_name,$mysql_username,$mysql_password,$mysql_database);
mysql_select_db($mysql_database, $conn);   
mysql_query("SET NAMES UTF8"); 
mysql_query("update b_ry set crmuser='$user',crmpass='$pass' where id=$id",$conn);
echo "update b_ry set crmuser='$user',crmpass='$pass' where id=$id";
?>