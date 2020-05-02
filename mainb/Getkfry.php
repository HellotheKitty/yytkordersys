<? 
require("../inc/conn.php");//require("inc/SendSMS.php");
header("Content-Type:text/html;charset=utf-8");
$tasktype=$_GET["tasktype"];
$user=$_GET["user"];
$new="";
$dwdm = $_GET["dwdm"];
 {
	   if (!file_exists("../inc/gl.sxs")) {
			$fp = fopen("../inc/gl.sxs", "w"); 
			fwrite($fp,date('Y-m-d'));
			mysql_query("update task_kfry set taskamount=0,taskDay=date(now()) where taskDay<date(now())",$conn);
		} else {
			$fp = fopen("../inc/gl.sxs", "r");
			if (fgets($fp)!=date('Y-m-d'))
			{
				$fp = fopen("../inc/gl.sxs", "w"); 
				fwrite($fp,date('Y-m-d'));
				mysql_query("update task_kfry set taskamount=0,taskDay=date(now()) where taskDay<date(now())",$conn);
			}
		}

	   $rsduty=mysql_query("select oabh,xm from task_kfry where isonduty=1 and zzfy='$dwdm'",$conn);
	   if (mysql_num_rows($rsduty)>0) $isonduty=1; else $isonduty=0;  //是否值班模式,都不是值班模式
	   
	   $rskry=mysql_query("select taskryxm,needhl+0 from task_type where tasktype='$tasktype' and zzfy='$dwdm'");
	   if (mysql_num_rows($rskry)>0) {
	   		$taskry=mysql_result($rskry,0,0);$needhl=mysql_result($rskry,0,1);
	   }
	   if ($new=="") {
		   if ($isonduty==0) {
		   		$rqqq=mysql_query("select oaBH,xm from task_kfry where taskamount=(select min(taskamount) from task_kfry where isok=1 and instr('$taskry',xm)>0 and zzfy='$dwdm') and isok=1 and instr('$taskry',xm)>0 and zzfy='$dwdm' limit 1",$conn);
		   } else {
				if ($needhl==1)
					$rqqq=mysql_query("select oaBH,xm from task_kfry where taskamount=(select min(taskamount) from task_kfry where isonduty=1 and instr('$taskry',xm)>0 and zzfy='$dwdm' ) and isonduty=1 and instr('$taskry',xm)>0 and zzfy='$dwdm' limit 1",$conn);
				else 
					$rqqq=mysql_query("select oaBH,xm from task_kfry where taskamount=(select min(taskamount) from task_kfry where isonduty=1 and zzfy='$dwdm' ) and isonduty=1 and zzfy='$dwdm' limit 1",$conn);
			}
		if (mysql_num_rows($rqqq)>0) {
    	   	$new = mysql_result($rqqq,0,0);
			$newxm=mysql_result($rqqq,0,1);
		}
	  }
	   if ($new=="") {
		   if (mysql_num_rows($rsduty)>0) {
			   $new = mysql_result($rsduty,0,0);$newxm=mysql_result($rsduty,0,1);
			} else {
			   $new="wangyr";$newxm="王艳茹";
			}
		}
	   mysql_query("update task_kfry set taskamount=taskamount+1 where oabh='$new'",$conn);
       $arr = array ('kfry'=>$new); 
	    echo json_encode($arr);
		//处理短信
		//if ($tasktype=="1" or $tasktype=="2") {
		//$rsx=mysql_query("select base_user.mobile,base_user.xm,ry_xs.xm xsxm,depart,lastxdtime,bb.mobile mb from base_user,ry_xs,yikaoa.b_ry bb where bb.bh=ry_xs.crm_bh and base_user.xsbh=ry_xs.xsbh and zh='$user'");
		//if (mysql_num_rows($rsx)>0) {
		//	if (strlen(mysql_result($rsx,0,"mobile"))==11) {  //用户
		//		$ss='尊敬的用户，感谢您选择易卡工坊，客服【'.$newxm.'】将为您提供服务，联系该客服请拨4008-229-377或加QQ：4008229377。';
		//		sendsms(mysql_result($rsx,0,"mobile"),$ss);
		//		mysql_query("insert into nc_erp.smssend_log values (0,now(),'".mysql_result($rsx,0,"mobile")."','$ss')");
		//	}
		//}
		//}
}
?>
