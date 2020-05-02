<?php
// 编辑order_zh表摘要用
require("../inc/conn.php");
require("../commonfile/log.php");
$id = $_GET["id"];

if($_GET['zy']<>''){
	$zy = $_GET["zy"];
	if(mysql_query("update order_zh set zy='$zy' where id='$id'", $conn))
		echo 1;
	else
		echo 0;
	exit();
}

if($_GET['memo']<>''){
	$memo = $_GET["memo"];
	if(mysql_query("update order_zh set memo='$memo' where id='$id'", $conn))
		echo 1;
	else
		echo 0;
	exit();
}

if($_GET['delskd']=='1'){

	$res_ddxq2 = mysql_query("select jf,df,khmc,fssj from order_zh where id=$id",$conn);

	if(mysql_num_rows($res_ddxq2)>0){

		$jf = floatval(mysql_result($res_ddxq2,0,'jf'));
		$df = floatval(mysql_result($res_ddxq2,0,'df'));
		$sk_fssj = mysql_result($res_ddxq2 ,0 , 'fssj');
		$khmc = mysql_result($res_ddxq2,0,'khmc');

	}else{

        exit();
	}

	if(mysql_query("delete from order_zh where id = $id",$conn)){


//    更新账户余额
		$res_khye = mysql_query("select id,sdate from kh_ye where depart = '$khmc' ",$conn);


		if (mysql_num_rows($res_khye) > 0) {

            $ye_sdate = mysql_result($res_khye, 0, 'sdate');

            if(strtotime($ye_sdate) > strtotime($sk_fssj)) {

				$ressk_0[] = mysql_query("update kh_ye set ye = ( ye + $df - $jf )  where depart = '$khmc' ", $conn);

			}

		} else {

			if(strtotime($ye_sdate) > strtotime($sk_fssj)) {

				$ressk_0[] = mysql_query("insert into kh_ye (id,zh,xm,mobile,depart,ye,xsbh,sdate) values (0,0,0,0,'$khmc', $df , 0,now())", $conn);

			}
		}
		$log = new Log();
		$log -> INFO('|删除收款单:'.$id . '操作人:' . $_SESSION['YKUSERNAME']  . "\r\n");
		echo 1;
	}else{
		echo 0;
	}
	exit;
}