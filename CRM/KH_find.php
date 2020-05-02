<? 
require("../inc/conn.php"); 
session_start();
if ($_POST["lx"]=="duplicate") {   //电销分配
	$khmc=$_POST["khmc"];
	$khmc=str_replace("有限公司","%",$khmc);
	$khmc=str_replace("股份","%",$khmc);
	$khmc=str_replace("有限责任公司","%",$khmc);
	$khmc=str_replace("门市部","%",$khmc);
	$khmc=str_replace(" ","",$khmc);
	$rsid=mysql_query("select khmc,ifnull(lxdz,''),xsry from crm_khb where khmc like '%{$khmc}%' limit 20",$conn);
	for ($k=0;$k<mysql_num_rows($rsid);$k++) {
		$str.=($k+1).".".mysql_result($rsid,$k,0)."/".mysql_result($rsid,$k,1).(mysql_result($rsid,$k,2)==""?"":"销售：".mysql_result($rsid,$k,2))."\n";
	}
	echo $str;
	exit;
}
?>