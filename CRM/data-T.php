<?
	require("../inc/conn.php"); 
	session_start();
	$row = $_POST["curPage"];
	$pageSize = $_POST["pageSize"];

	$startnum = ($row-1)*$pageSize;


	$rs = mysql_query("select crm_khb.id,khmc,lxr,sshy,province,city,khcsd,memo,datainputsj,count(crm_khb_contact.id) lxcs from crm_khb left join crm_khb_contact on crm_khb.id=crm_khb_contact.khbid where callout is null and not datainputsj is null and instr((select dq from crm_teamdq where team='".$_SESSION["CRM_TEAM"]."'),province)>0 group by crm_khb.id order by crm_khb.id limit $startnum,$pageSize", $conn); 

	$arr=array();
	//把$res转移到$arr
	if (mysql_num_rows($rs) >　0) {
		while($row_ = mysql_fetch_assoc($rs)){
			$arr[]=$row_;
		}
	}


	$sql = mysql_query("select count(1) from crm_khb where callout is null and not datainputsj is null and instr((select dq from crm_teamdq where team='".$_SESSION["CRM_TEAM"]."'),province)>0",$conn);

	if (mysql_num_rows($sql) >　0) {
		$count = mysql_result($sql, 0,0);
	}

	$num = mysql_num_rows($rs);
	$pageCount=ceil($count/$pageSize);

	echo json_encode(["list"=>$arr,"rowCount"=>$count,"curPage"=>$row,"pageSize"=>$pageSize,"numSize"=>$num]);



?>