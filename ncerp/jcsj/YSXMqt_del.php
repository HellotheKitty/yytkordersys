<? require("../../inc/conn.php");
require("../../commonfile/log.php");
require("../../commonfile/dingtalk_notice_class.php");
require("../../commonfile/wechat_notice_class.php");
?>
<?
session_start();
if ($_SESSION["YKUSERNAME"]=="") {
echo "<script language=JavaScript>{window.location.href='../../error.php';}</script>";
exit; 
}?>
<?
header("Content-type:text/html;charset=utf-8");

$dwdm = substr($_SESSION["GDWDM"],0,4);

if ($_GET["BH"]<>"") {

	mysql_query("delete from order_mainqt where ddh='".$_GET["BH"]."'");
	mysql_query("delete from order_mxqt where ddh='".$_GET["BH"]."'");
	mysql_query("delete from order_mxqt_hd where ddhao='".$_GET["BH"]."'");

//	删除订单的时候把外协表里面对应的关系也删掉
	mysql_query("delete from order_waixie where ddh = '".$_GET['BH']."' or copyddh =  '".$_GET['BH']."'");

//		fh_info 表里面对应的记录删掉
	mysql_query("delete from fh_info where ddh = '" . $_GET['BH'] . "'" ,$conn);
}

if ($_GET["BH2"]<>"") { //提交前台
	$orderrs = mysql_query("select waixie from order_mainqt where ddh='".$_GET["BH2"]."'", $conn);
	if($orderrs && mysql_num_rows($orderrs) > 0) {
		$waixie = mysql_result($orderrs, 0, 'waixie');
	}
	if($dwdm == '3301' || $dwdm == '3308' ||substr($dwdm,0,2)=='34'){
		mysql_query("update order_mainqt set state='待生产',sdate=now() where ddh='".$_GET["BH2"]."'",$conn);
		//$info = json_decode(file_get_contents("http://oa.skyprint.cn/mainb/Getkfry.php?tasktype=14&user=$zh&dwdm=".substr($_SESSION["GDWDM"],0,4)));
		//$zzry=$info->kfry;
		//mysql_query("INSERT INTO task_list (taskcreatetime, tasktype, fromuser, fromorder, taskmemo, taskstate,statetime,taskrecver,taskrecvtime,taskdescribe,taskfile1,taskfile2,taskparam,srcid) select now(),'14',khmc,'',concat('".$_SESSION["YKUSERNAME"]."创建的工单,订单号：',ddh),'排队中',now(),'$zzry',now(),'请核对并打印生产单','','','gongdan',1 from order_mainqt where  ddh='".$_GET["BH2"]."'", $conn);
	}else{
		$state = '进入生产';
	//	若外协的单子需要审核，则取消下面两行注释	
		if($waixie != '0' && $waixie != '')
			$state = '待生产';
		mysql_query("update order_mainqt set state='$state',sdate=now() where ddh='".$_GET["BH2"]."'",$conn);
	}
	//	rtx 提示生产部门 易卡工坊
	$bh2 = $_GET['BH2'];
	$khmmemores = mysql_query("select khmc,memo from order_mainqt where ddh = $bh2");

	$khm = mysql_result($khmmemores,0,'khmc');
	$tsmemo = mysql_result($khmmemores,0,'memo');
	if($khm == '商务部/易卡工坊'){

//		$ss = "新的印艺天空订单:".$bh2."(非标)\r备注:".$tsmemo.";\r请联系印艺天空客服打印生产单";
//		file_get_contents("http://115.29.164.6/notify.php?msg=".urlencode($ss)."&receiver=hehq,lisx,zhuxj,zhouwh,lijing,zhangjy,zhousw&title=有新的订单进入印艺天空");


//		dingtalk ykgf
		ding_notice::send_text('hehq|lisx|lijing|zhangjy|zhousw|qidd|liqq' ,$ss);

//		wechat notice
		wechat_notice::send_temp_gongdan('hehq|lisx|lijing|zhangjy|zhousw|qidd|liqq','印艺天空非标',$ss);
	}
}
if ($_GET["did"]<>""  && $_GET['jdf'] <> '') { //确定生产jdf

//	$ch = curl_init();
//	curl_setopt($ch,CURLOPT_URL,"http://192.168.1.71:88/skyserver/make_jdf.php?ddh=".$_GET['did']);
////	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//	curl_setopt($ch, CURLOPT_HEADER, 0);
//	curl_exec($ch);
//	curl_close($ch);


	mysql_query("update order_mainqt set state='进入生产',sdate=now() where ddh='".$_GET["did"]."'");
	if ($_GET["lx"]=="new") {
		echo "<script language=JavaScript> {window.location.href='NS_new.php?ddh=".$_GET["did"]."';}</script>";
//		echo "<script language=JavaScript> {window.location.href='http://192.168.1.71:88/skyserver/make_jdf.php?ddh=".$_GET["did"]."';}/script>";
		exit;
	}
	//mysql_query("insert into order_zh select 0,xsbh,now(),0,dje+kdje-djje,'订单',memo,null,khmc,ddh from order_mainqt where ddh='".$_GET["did"]."'");
}

//if ($_GET["did"]<>"" && substr($dwdm,0,2) <> '34') { //确定生产
//
//
//	mysql_query("update order_mainqt set state='进入生产',sdate=now() where ddh='".$_GET["did"]."'");
//	if ($_GET["lx"]=="new") {
//		echo "<script language=JavaScript> {window.location.href='NS_new.php?ddh=".$_GET["did"]."';}/script>";
//		exit;
//	}
//	//mysql_query("insert into order_zh select 0,xsbh,now(),0,dje+kdje-djje,'订单',memo,null,khmc,ddh from order_mainqt where ddh='".$_GET["did"]."'");
//}

if ($_GET["did"]<>"" ) {
//	已收款确定生产
	$sfsk = mysql_query("select id from order_zh WHERE ddh = '".$_GET['did']."' and zy='订单结算'",$conn);
	$sfqz = mysql_query("select needsign from order_mainqt WHERE ddh ='".$_GET['did']."'");
	$prepayed = mysql_query("select djje , dje from order_mainqt where ddh = '" .$_GET['did']. "'",$conn);
	if(mysql_num_rows($sfsk)<>0 || mysql_result($sfqz,0,'needsign')=='1' || floatval(mysql_result($prepayed,0,'djje')) > (floatval(mysql_result($prepayed,0,'dje')) / 2)){
		mysql_query("update order_mainqt set state='进入生产',sdate=now() where ddh='".$_GET["did"]."'");
		if ($_GET["lx"]=="new") {
			//added 2018-7-11
			if( $_SESSION['GDWDM']=='340500') {
				// TODO
				// include 'make_jdf.php';
            	// makejdf($_GET['did'],$conn);
			}
			//
			echo "<script language=JavaScript> {window.location.href='NS_new.php?ddh=".$_GET["did"]."';}</script>";
			exit;
		}
	}else{
		echo "<script language=JavaScript> alert('未收款订单不能进入生产，请收款或者预收50%订金');</script>";
		exit();
	}

	//mysql_query("insert into order_zh select 0,xsbh,now(),0,dje+kdje-djje,'订单',memo,null,khmc,ddh from order_mainqt where ddh='".$_GET["did"]."'");
}
if ($_POST["didth"]<>"") { //退回
	$thyy=$_POST["thyy"];

    $yy =mysql_result( mysql_query("select description from base_wtdd_code where code = '$thyy'"),0,'description');
	$yy .= $_POST['memo'];

    $ifjiesuan = mysql_query("select id from order_zh where ddh = (select ddh from order_mainqt where id = '".$_POST['didth']."') and zy = '订单结算'",$conn);

    if(mysql_num_rows($ifjiesuan)>0){
        echo json_encode(array('code'=>'订单已结算不能退回'));
        exit;

    }else{

        mysql_query("update order_mainqt set state='新建订单',memo=concat(memo,'  退回原因：$yy'),sdate=now() where id='".$_POST["didth"]."'");
//        mysql_query("delete from order_zh where ddh=(select ddh from order_mainqt where id='".$_POST["didth"]."') and zy= '订单订金'",$conn);

//		echo "<script>{window.open('../MYOrderShowqt.php', 'main');window.close();}/script>";
        echo json_encode(array('code'=>'订单已退回'));
		$ddh = mysql_result(mysql_query("select ddh from order_mainqt where id = ".$_POST['didth']),0,'ddh');
		//	rtx 提示生产部门 易卡工坊

		$khm = mysql_result(mysql_query("select khmc from order_mainqt where ddh = $ddh "),0,'khmc');

		if($khm == '商务部/易卡工坊'){

//			$ss = "印艺天空单号:".$ddh.";  \r退回原因：$yy";
//			file_get_contents("http://115.29.164.6/notify.php?msg=".urlencode($ss)."&receiver=hehq,lisx,zhuxj,zhouwh,lijing,zhangjy,zhousw&title=印艺天空订单退回");


//		dingtalk ykgf
			$ss="印艺天空订单退回:".$ddh.";\r退回原因：$yy";
			ding_notice::send_text('hehq|lisx|lijing|zhangjy|zhousw|qidd|liqq' ,$ss);
//			wechat notice
			wechat_notice::send_temp_gongdan('hehq|lisx|lijing|zhangjy|zhousw|qidd|liqq','印艺天空订单退回',$ddh.";\r退回原因：$yy");

		}
        exit;
    }

//		mysql_query("update order_xj set state='关闭',memo=concat(memo,'  关闭原因：$yy'),sdate=now() where id='".$_POST["didth"]."'");
//		echo "<script>{window.open('../MYOrderxj.php', 'main');window.close();}/script>";exit;

}

if ($_POST["didzf"]<>"") { //作废订单
	$zfyy=$_POST["zfyy"];

	$yy =mysql_result( mysql_query("select description from base_wtdd_code where code = '$zfyy'",$conn),0,'description');
	$yy .= $_POST['memo'];

	$ifjiesuan = mysql_query("select * from order_zh where ddh = (select ddh from order_mainqt where id = '".$_POST['didzf']."') and (zy = '订单结算' or zy = '订单订金')",$conn);
//	$ifjiesuan = mysql_query("select order_mainqt.pczy ,order_zh.*   from order_mainqt left join order_zh on order_mainqt.ddh = order_zh.ddh where order_mainqt.id = '".$_POST['didzf']."' and order_zh.zy = '订单结算'",$conn);

//    是否开始打印 不管了
    /*$ifprint = mysql_query("select pczy from order_mainqt where id ='".$_POST['didzf']."'",$conn);
    if(mysql_result($ifprint,0,'pczy')<>''){
        echo "<script>alert('订单已经开始打印不能作废');</script>";
        exit();
    }*/

    //        已结算通过调账退款
    if(mysql_num_rows($ifjiesuan)>0){

        $tkfs = $_POST['tkfs'];
        $tkje = $_POST['tkje'];
        $tkbz = $_POST['tkbz'];

        $skje = mysql_result($ifjiesuan,0,'df');
        $ddh = mysql_result($ifjiesuan,0,'ddh');
        $khmc = mysql_result($ifjiesuan,0,'khmc');

        if($skje < $tkje){ //退款金额大于订单金额
            echo "<script>alert('退款金额大于订单金额,请重新操作');</script>";
            exit();
        }
        if ($tkfs == "预存款"){

            beginTransaction();
            $zfddres[] = mysql_query("insert into order_zh(id,xsbh,fssj,jf,df,zy,memo,sksj,khmc,ddh) values (0,'$tkfs',now(),$tkje,0,'订单退款','$tkbz',now(),'$khmc','$ddh')",$conn);

        }else{
            $zfddres[] =mysql_query("insert into order_zh(id,xsbh,fssj,jf,df,zy,memo,sksj,khmc,ddh) values (0,'$tkfs',now(),$tkje,$tkje,'订单退款','$tkbz',now(),'$khmc','$ddh')",$conn);

        }

        $zfddres[] = mysql_query("update order_mainqt set state='作废订单',memo=concat(memo,'  作废原因：$yy'),sdate=now() where id='".$_POST["didzf"]."'",$conn);

        if(!transaction($zfddres)){
            echo "<script>alert('作废订单失败！请重新操作');</script>";
            exit();
        }

		$log = new Log();
		$log -> INFO('有退款订单作废:'. $ddh . $_SESSION['YKUSERNAME']. "\r\n");
        echo json_encode(array('code'=>'订单已作废'));

//		dingtalk notice
		if($khmc == '商务部/易卡工坊'){
			$ss="印艺天空订单作废:".$ddh.";\r作废原因：$yy";
			ding_notice::send_text('hehq|lisx|lijing|zhangjy|zhousw' ,$ss);
//			wechat notice
			wechat_notice::send_temp_gongdan('hehq|lisx|lijing|zhangjy|zhousw','印艺天空订单作废',$ddh.";\r作废原因：$yy");
		}
        exit;

    }else{

        beginTransaction();

        $zfddres[] = mysql_query("update order_mainqt set state='作废订单',memo=concat(memo,'  作废原因：$yy'),sdate=now() where id='".$_POST["didzf"]."'",$conn);
//        mysql_query("delete from order_zh where ddh=(select ddh from order_mainqt where id='".$_POST["didzf"]."') and zy= '订单订金'",$conn);

        if(!transaction($zfddres)){

            echo "<script>alert('作废订单失败！请重新操作');</script>";
			exit();
        }
		$log = new Log();
		$log -> INFO('未收款订单作废:orderid'. $_POST["didzf"] . $_SESSION['YKUSERNAME'] . "\r\n");

		echo json_encode(array('code'=>'订单已作废'));
		exit;
	}

}

if ($_GET["lx"]=="new") 
	echo "<script language=JavaScript> {window.opener.location.reload();window.close();}</script>";
elseif ($_GET["lx"]=="new2") 
	echo "<script language=JavaScript> {window.open('../MYOrderShowns.php', 'main');}</script>";
else
	echo "<script language=JavaScript> {window.open('../MYOrderShowns.php', 'main');}</script>";
?>

