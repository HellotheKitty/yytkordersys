<? session_start();
require("../inc/conn.php");//require("inc/SendSMS.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script src="../js/jquery-1.8.3.min.js" type="text/javascript"></script>
<title></title>
<style type="text/css">
.STYLE13 {font-size: 12px}
</style>
</head>
<style>
	#navigation {
		height: 50px;
		width: 100%;
		text-align: center;
		background: #2aa439;
	}
	#navigation a.back {
		position: absolute;
		top: 15px;
		left: 15px;
		width: 50px;
		background: none;
		color: #fff;
		font-size: 16px;
	}
	h3 {
		width: 180px;
		height: 50px;
		line-height: 50px;
		margin: 0 auto;
		color: #fff;
		font-size: 20px;
	}
	.text_line {
		height: 30px;
	}
	.but7 {
		border-radius: 5px; cursor:pointer; width:40px; height:25px; text-align: center; color:#fff; border:1px solid #2aa439; background: #288439; -webkit-appearance: none; font-size: 13px;
	}
	.but8 {
		border-radius: 5px; cursor:pointer; width:40px; height:25px; text-align: center; color:#fff; border:1px solid #2aa439; background: #bbbbbb; -webkit-appearance: none; font-size: 13px;
	}
	.motop {
		margin-top: 5px;
	}
	.titleID {
		height: 25px;
		width: 100%;
		line-height: 25px;
		text-align: left;
		background: #eee;
		color: #666;
		font-size: 14px;
	}
	#orderList {
		border: 1px solid #eaeaea;
		border-radius: 5px;
		margin-top: 10px;
	}
	.content {
		font-size:12px;
	}
	.orderOption {
		height: 30px;
		line-height: 30px;
		margin: 10px 0px 15px 30px;
		text-align:left
	}
</style>
<body>
<? if ($_POST["id"]<>"") {
	$rsd=mysql_query("SELECT DATEDIFF(taskCreatetime,now()) FROM task_list where id=".$_POST["id"],$conn);
	mysql_query("update task_list set taskstate='排队中',statetime=now(),taskrecver='".$_POST["kfry"]."',taskrecvtime=now(),taskbegintime=null,taskendtime=null,taskmemo=concat(ifnull(taskmemo,''),'/".$_SESSION["YKUSERNAME"]."转移原因：','".$_POST["sr"]."') where id=".$_POST["id"],$conn);
	mysql_query("update task_kfry set taskamount=taskamount+1 where oabh='".$_POST["kfry"]."'",$conn);
	if (mysql_result($rsd,0,0)==0)  //当天任务
		mysql_query("update task_kfry set taskamount=taskamount-1 where oabh='".$_SESSION["YKOAUSER"]."'",$conn);
	
	$rst=mysql_query("select tasktype,fromuser from task_list where id=".$_POST["id"],$conn);
	//if (mysql_result($rst,0,0)=="2") {  //需要短信
	//$user=mysql_result($rst,0,1);
	//$rsx=mysql_query("select base_user.mobile,base_user.xm,ry_xs.xm xsxm,depart,lastxdtime,bb.mobile mb from base_user,ry_xs,yikaoa.b_ry bb where bb.bh=ry_xs.crm_bh and base_user.xsbh=ry_xs.xsbh and zh='".$user."'",$conn);
	//$newxm=mysql_result(mysql_query("select xm from task_kfry where oabh='".$_POST["kfry"]."'",$conn),0,0);
	//	if (mysql_num_rows($rsx)>0) {
	//		if (strlen(mysql_result($rsx,0,"mb"))==11 and mysql_result($rsx,0,"lastxdtime")=="") {  //新用户需要通知销售
	//			$ss='你的新客户['.$user.']'.mysql_result($rsx,0,"depart").'，客服已经变更为【'.$newxm.'】。';
	//			sendsms(mysql_result($rsx,0,"mb"),$ss);
	//			mysql_query("insert into nc_erp.smssend_log values (0,now(),'".mysql_result($rsx,0,"mb")."','$ss')");
	//		}
	//		if (strlen(mysql_result($rsx,0,"mobile"))==11) {  //用户
	//			$ss='尊敬的用户，感谢您选择易卡工坊，您的客服变更为【'.$newxm.'】，联系该客服请拨4008-229-377或加QQ：4008229377。';
	//			sendsms(mysql_result($rsx,0,"mobile"),$ss);
	//			mysql_query("insert into nc_erp.smssend_log values (0,now(),'".mysql_result($rsx,0,"mobile")."','$ss')");
	//		}
	//	}
	//}
	echo "","<div class=h3>任务转移成功！</div>";
	exit;
}
?>
<div style="font-size:14px;background: #eee;line-height: 25px;">&nbsp;任务转移给其他同事</div>
<form name="form1" method=post>
<input type="hidden" name="id" value="<? echo $_GET["id"]?>" />

<div id="orderList">
  <div class="titleID">&nbsp;<? echo $_GET["task"];?></div>
        <div class="content">
        <br>转移原因：<br>
        &nbsp;&nbsp;<input name="sr" type="radio" value="其他任务忙" checked="checked" />其他任务忙
        <br>&nbsp;&nbsp;<input name="sr" type="radio" value="业务暂时不能胜任" />业务暂时不能胜任
        <br>&nbsp;&nbsp;<input name="sr" type="radio" value="用户要求" />用户要求
        <br>&nbsp;&nbsp;<input name="sr" type="radio" value="其他原因" />其他原因
        <p><span class="STYLE13">转移给：
            <select name="kfry" id="kfry">
              <? $rs0=mysql_query("select bh,xm from b_ry,task_type,task_list where task_list.tasktype=task_type.tasktype and (instr(taskryxm,xm)>0 or instr(taskrecvxm,xm)>0) and task_list.id=".$_GET["id"]." order by bh");
				for ($i=0;$i<mysql_num_rows($rs0);$i++)
					if (mysql_result($rs0,$i,1)==$_SESSION["YKUSERNAME"]) 
						echo '<option value="'.mysql_result($rs0,$i,0).'" selected>'.mysql_result($rs0,$i,1).'</option>';
					else
						echo '<option value="'.mysql_result($rs0,$i,0).'">'.mysql_result($rs0,$i,1).'</option>';
					?>
            </select>
        </span></p>
        </div>
        <div class="orderOption">
           	
        	<input type="submit" class="but7" value="确定" > 
        	 
        	</div>
        </div>
		
		</div>
</form>
</body>
</html>