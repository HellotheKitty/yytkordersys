<?
session_start();
require 'inc/conn.php';

$rs0=mysql_query("select count(*) from mess_recv where recv_id=".$_SESSION["YKOAUID"]." and readtime is null", $conn);
$rs1=mysql_query("select count(*) from RF_read where recv_id=".$_SESSION["YKOAUID"]." and readtime is null ", $conn);
$rs2=mysql_query("select count(*) from TK_read where recv_id=".$_SESSION["YKOAUID"]." and readtime is null", $conn);

if(mysql_result($rs0,0,0)+mysql_result($rs1,0,0)+mysql_result($rs2,0,0)>$_SESSION["YJSL"]){ 
		$sid='<embed id = "soundControl1"  src = "Notify.wav"  mastersound hidden = "true" loop ="0"></embed>';
}
$_SESSION["YJSL"]=mysql_result($rs0,0,0)+mysql_result($rs1,0,0)+mysql_result($rs2,0,0);

$rs = mysql_query("SELECT content FROM global_notice WHERE internal = 1 AND TO_DAYS(date_format(expire,'%Y-%m-%d')) > TO_DAYS(NOW()) order by createTime desc limit 1", $conn);
$content = mysql_num_rows($rs) > 0 ? mysql_result($rs, 0, 0) : '';

$arr = array('rs0' => mysql_result($rs0,0,0), 'rs1' => mysql_result($rs1,0,0)+mysql_result($rs2,0,0), 'mqs' => $content, 'sid' => $sid);

echo json_encode($arr);

?>