<? require("../../inc/conn.php");
require("../../commonfile/dingtalk_notice_class.php");
require("../../commonfile/wechat_notice_class.php");
?>
<?
header("Content-Type: text/html;charset=utf-8");
session_start();
if ($_SESSION["CUSTOMER"]<>"OK") { 
echo "<script language=JavaScript>{window.location.href='../../error.php';}</script>";
exit; 
}?>
<?
$dwdm = substr($_SESSION["GDWDM"],0,4);
//删除
if ($_GET["BH"]<>"") {

	//验证
	$authRequest = $_GET["auth"];
	$authToCheck = md5($_SESSION["KHID"]."-".$_SESSION["AUTHCODE"]."-".$_GET["BH"]."-"."新建订单");
	if($authRequest == $authToCheck){
		mysql_query("delete from order_mxqt_hd where mxid in (select id from order_mxqt where ddh='".$_GET["BH"]."')",$conn);
		mysql_query("delete from order_mainqt where ddh='".$_GET["BH"]."'",$conn);
		mysql_query("delete from order_mxqt where ddh='".$_GET["BH"]."'",$conn);

//	删除订单的时候把外协表里面对应的关系也删掉
		mysql_query("delete from order_waixie where ddh = '".$_GET['BH']."' or copyddh =  '".$_GET['BH']."'");

//		fh_info 表里面对应的记录删掉
		mysql_query("delete from fh_info where ddh = '" . $_GET['BH'] . "'" ,$conn);


		echo "<script>alert('删除成功');</script>";
		echo "<script>window.open('../order/orderlist.php','main');window.close();</script>";
		exit;
	}else{
		echo "<script>alert('验证失败！订单未删除');history.go(-1);</script>";
		exit;
	}
}

if ($_GET["BH2"]<>"") { //提交前台
	//验证
	$authRequest = $_GET["auth"];
	$authToCheck = md5($_SESSION["KHID"]."-".$_SESSION["AUTHCODE"]."-".$_GET["BH2"]."-"."新建订单");
//	echo $authToCheck."<br>";
//	echo $authRequest;
//	exit;
	if($authToCheck != $authRequest){
		echo "<script>alert('验证失败！订单未提交生产');history.go(-1);</script>";
		exit;		
	}

		mysql_query("update order_mainqt set state='待生产',sdate=now() where ddh='".$_GET["BH2"]."'",$conn);
		$info = json_decode(file_get_contents("http://59.110.17.13/ordersys/mainb/Getkfry.php?tasktype=14&user=$zh&dwdm=".substr($_SESSION["GDWDM"],0,4)));
		$zzry=$info->kfry;
		mysql_query("INSERT INTO task_list (taskcreatetime, tasktype, fromuser, fromorder, taskmemo, taskstate,statetime,taskrecver,taskrecvtime,taskdescribe,taskfile1,taskfile2,taskparam,srcid) select now(),'14',khmc,'',concat('".$_SESSION["YKUSERNAME"]."创建的工单,订单号：',ddh),'排队中',now(),'$zzry',now(),'请核对并打印生产单','','','gongdan',1 from order_mainqt where  ddh='".$_GET["BH2"]."'", $conn);

//	rtx 提示生产部门 易卡工坊
	$bh2 = $_GET['BH2'];
	$khmmemores = mysql_query("select khmc,memo from order_mainqt where ddh = $bh2");

	$khm = mysql_result($khmmemores,0,'khmc');
	$tsmemo = mysql_result($khmmemores,0,'memo');
	if($khm == '商务部/易卡工坊'){

		$ss = "印艺天空单号:".$bh2."(自主下单）备注:".$tsmemo."[取纸单>>>|http://59.110.17.13/ordersys/customer/zzck/get_paper.php?nolog=1&ids=".$bh2."]";
//		file_get_contents("http://115.29.164.6/notify.php?msg=".urlencode($ss)."&receiver=hehq,lisx,zhouwh,zhuxj,lijing,zhangjy,zhousw&title=有新的订单进入印艺天空");

//		dingtalk ykgf
		$bodyform = array(
			0=> array(
				"key" => "印艺天空单号: ",
				"value" => $bh2
			),
			1=> array(
				"key" => "备注: ",
				"value" => $tsmemo
			)
		);
		$linkurl = "http://59.110.17.13/ordersys/customer/zzck/get_paper.php?nolog=1&ids=".$bh2;
		ding_notice::send_oa('hehq|lisx|zhouwh|zhuxj|lijing|zhangjy|zhousw|jimyun|hife.wu|qidd|liqq' ,$linkurl,"有新的订单进入印艺天空",$bodyform,'有新的订单进入印艺天空','');

//		wechat notice
		$remark = "备注: ".$tsmemo;
		wechat_notice::send_temp_neworder('hehq|lisx|zhouwh|zhuxj|lijing|zhangjy|zhousw|jimyun|hife.wu|qidd|liqq','有新的订单进入印艺天空',$bh2,date('Y-m-d H-m'),$remark,$linkurl);
	}

//	echo "<script>window.location.href='NS_new.php?ddh=".$_GET["BH2"]."&auth=$authToCheck';</script>";
	$_auth = md5($_SESSION["KHID"]."-".$_SESSION["AUTHCODE"]."-".$_GET["BH2"]."-"."noah");
	echo "<script>window.location.href='../order/orderdetail.php?ddh=".$_GET["BH2"]."&auth=$_auth';</script>";
}

if ($_GET["did"]<>"") {
	mysql_query("update order_mainqt set state='进入生产',sdate=now() where ddh='".$_GET["did"]."'");
	if ($_GET["lx"]=="new") {
		echo "<script language=JavaScript> {window.location.href='NS_new.php?ddh=".$_GET["did"]."';}</script>";
		exit;
	}
	//mysql_query("insert into order_zh select 0,xsbh,now(),0,dje+kdje-djje,'订单',memo,null,khmc,ddh from order_mainqt where ddh='".$_GET["did"]."'");
}

if ($_POST["didth"]<>"") {exit;
	$yy=$_POST["thyy"];
	if ($_POST["lx"]=="退回") {
		mysql_query("update order_mainqt set state='退回',memo=concat(memo,'  退回原因：$yy'),sdate=now() where id='".$_POST["didth"]."'");
		mysql_query("delete from order_zh where ddh=(select ddh from order_mainqt where id='".$_POST["didth"]."')",$conn);
		echo "<script>{window.open('../MYOrderShowqt.php', 'main');window.close();}</script>";exit;
	} else {
		mysql_query("update order_xj set state='关闭',memo=concat(memo,'  关闭原因：$yy'),sdate=now() where id='".$_POST["didth"]."'");
		echo "<script>{window.open('../MYOrderxj.php', 'main');window.close();}</script>";exit;
	}
}
/*
if ($_GET["lx"]=="new") 
	echo "<script language=JavaScript> {window.opener.location.reload();window.close();}</script>";
elseif ($_GET["lx"]=="new2") 
	echo "<script language=JavaScript> {window.open('../MYOrderShowns.php', 'main');}</script>";
else
	echo "<script language=JavaScript> {window.open('../MYOrderShowns.php', 'main');}</script>";
 */
?>

