<? 
require("../inc/conn.php");require("../OAfile/SendSMS.php"); 
session_start();
if ($_POST["idd"]<>"") {
	$_SESSION["SRY"]=$_POST["idd"];
	exit;
}
if ($_POST["lx"]=="fp") {   //电销分配
	$sl=$_POST["sjsl"];$kk=0;
	if ($_GET["lx"]=="fp") {
		while ($kk<$sl) {
		  foreach($_POST["ry"] as $key => $bh) {
			$rsid=mysql_query("select id from crm_khb where callout is null and xsry is null and not datainputsj is null and instr((select dq from crm_teamdq where team='".$_SESSION["CRM_TEAM"]."'),province)>0 limit 1",$conn);
			if (mysql_num_rows($rsid)>0) mysql_query("update crm_khb set state=-1,callout='$bh',calloutfpsj=now() where xsry is null and id=".mysql_result($rsid,0,0),$conn);
			$kk++;
		  }
		}
	} else {  //撤回
		  foreach($_POST["ry"] as $key => $bh) {
			mysql_query("update crm_khb set callout=null,calloutfpsj=null where callout='$bh' and contacttime is null and state=-1 and instr((select dq from crm_teamdq where team='".$_SESSION["CRM_TEAM"]."'),province)>0 and xsry is null",$conn);
		  }
	}
	echo "OK";
	exit;
}
if ($_POST["lx"]=="xfp") {   //销售分配
	$sl=$_POST["sjsl"];$kk=0;
	if ($_GET["lx"]=="fp") {
		while ($kk<$sl) {
		  foreach($_POST["ry"] as $key => $bh) {
			$rsid=mysql_query("select id from crm_khb where xsry is null and id in (".$_SESSION["SRY"].") limit 1",$conn);
			if (mysql_num_rows($rsid)>0) mysql_query("update crm_khb set xsry='$bh',xsryfpsj=now(),state=-2 where id=".mysql_result($rsid,0,0),$conn);
			$kk++;
		  }
		}
	} else {
		  foreach($_POST["ry"] as $key => $bh) {
			mysql_query("update crm_khb set xsry=null,xsryfpsj=null,state=-1 where xsry='$bh' and state=-2 and (nextlx is null or datediff(now(),nextlx)>=0) and instr((select dq from crm_teamdq where team='".$_SESSION["CRM_TEAM"]."'),province)>0",$conn);
		  }
	}
	echo "OK";
	exit;
}

if ($_POST["khmc"]<>"") {
	if ($_POST["lxrmobile"]=="" and $_POST["lxdh"]=="") {
	echo "联系手机或电话不能都为空!";
	exit;
	}
	$rss=mysql_query("select count(1) from crm_khb where khmc='".$_POST["khmc"]."' and id<>'".$_POST["id"]."'",$conn);
	if (mysql_result($rss,0,0)>0) {
		echo "客户名称已经存在，保存失败!";
		exit;
	}
	if ($_POST["id"]>0) {
		$id=$_POST["id"];
		mysql_query("update crm_khb set khmc='".$_POST["khmc"]."',lxr='".$_POST["lxr"]."',lxrzw='".$_POST["lxrzw"]."',lxrmobile='".$_POST["lxrmobile"]."',lxdh='".$_POST["lxdh"]."',lxqq='".$_POST["lxqq"]."',lxfax='".$_POST["lxfax"]."',frdb='".$_POST["frdb"]."',yzbm='".$_POST["yzbm"]."',lxdz='".$_POST["lxdz"]."',khygs='".$_POST["khygs"]."',province='".$_POST["province"]."',city='".$_POST["city"]."',fzjg='".$_POST["fzjg"]."',sshy='".$_POST["sshy"]."',zczb='".$_POST["zczb"]."',zyyw='".$_POST["zyyw"]."',memo='".$_POST["memo"]."',lxemail='".$_POST["lxemail"]."',tag='".$_POST["tag"]."' where id=".$id, $conn);
		if ($_POST["xsry"]<>"")
			mysql_query("update crm_khb set xsry='".$_POST["xsry"]."',xsryfpsj=now() where id=".$id, $conn);
		if ($_POST["dianhu"]=="1") {
			mysql_query("update crm_khb set state='-1' where id=".$id, $conn);
			mysql_query("delete from crm_khb_contact where khbid=".$id." and content like '%[无法联系]%'",$conn);
			mysql_query("insert into crm_khb_contact values (0,".$id.",'".$_SESSION["YKOAUSER"]."',now(),'号码已经修正，请再次联系客户','')",$conn);
		}
	} else {
	$state= "NULL";
	if (substr($_SESSION["CRM_TEAM"],0,1)=="T") $state="-1";
	if (substr($_SESSION["CRM_TEAM"],0,1)=="X") $state="-2";
	$rs=mysql_query("insert into crm_khb (khmc,lxr,lxrzw,lxrmobile,lxdh,lxfax,lxEmail,lxQQ,frdb,yzbm,lxdz,khygs,datafrom,province,city,fzjg,sshy,zczb,zyyw,memo,khcsd,state,nextlx,tag,".(substr($_SESSION["CRM_TEAM"],0,1)=="D"?"datainput,datainputsj":(substr($_SESSION["CRM_TEAM"],0,1)=="T"?"callout,calloutfpsj":"xsry,xsryfpsj")).") values ('".$_POST["khmc"]."','".$_POST["lxr"]."','".$_POST["lxrzw"]."','".$_POST["lxrmobile"]."','".$_POST["lxdh"]."','".$_POST["lxfax"]."','".$_POST["lxemail"]."','".$_POST["lxqq"]."','".$_POST["frdb"]."','".$_POST["yzbm"]."','".$_POST["lxdz"]."','".$_POST["khygs"]."','input','".$_POST["province"]."','".$_POST["city"]."','".$_POST["fzjg"]."','".$_POST["sshy"]."','".$_POST["zczb"]."','".$_POST["zyyw"]."','".$_POST["memo"]."',0,$state,now(),'".$_POST["tag"]."','".$_SESSION["YKOAUSER"]."/".$_SESSION["XM"]."',now())", $conn);
	if ($_POST["from"]=="service") {  //客服过来的客户
	if ($_POST["xsry"]<>"")
		mysql_query("update crm_khb set memo='客服创建',state=-2,xsry='".$_POST["xsry"]."',xsryfpsj=now() where khmc='".$_POST["khmc"]."'", $conn);
	else
		mysql_query("update crm_khb set memo='客服创建',state=-1,xsry=null,xsryfpsj=null where khmc='".$_POST["khmc"]."'", $conn);
	}  //end
	}
	echo "保存完成!";
	exit;
}


$kid=$_POST["kid"];
if ((date("h")==substr($kid,-6,2) and date("his")-substr($kid,-6,6)>=2000) or (date("h")>substr($kid,-6,2) and date("is")+6000-substr($kid,-4,4))>=2000) {  //超过20分钟
	echo "操作超时，不能保存，一个客户数据的有效操作时间是20分钟。";
	exit;
}
$id=$_POST["id"];
if ($id==0 or $id=="") {
	echo "客户信息出错，可能超时，请刷新客户信息后重试。";
	exit;
}
if ($_GET["lx"]=="zongji") { //是总机号码
	$ru=mysql_query("update crm_khb set fzjg='总机' where id=$id",$conn);
	if (mysql_affected_rows($ru)==0) $msg="保存失败！请重试。"; else $msg="设定完成";
	mysql_query("insert into crm_khb_contact values (0,$id,'".$_SESSION["YKOAUSER"]."',now(),'判断为总机，需要非云呼叫。','000000')",$conn);
	echo $msg;
	exit;
}
if ($_GET["lx"]=="becustom") {  //成为客户
	$rskh=mysql_query("select khmc from crm_khb where yikayin_zh='".$_POST["zh"]."'",$conn);
	if (mysql_num_rows($rskh)>0) {
		echo "该账号已经关联，客户：".mysql_result($rskh,0,0);exit;
	} 
	$rskh=mysql_query("select zh from nc_erp.v_base_user where zh='".substr($_POST["zh"],0,strpos($_POST["zh"]."^","^"))."'",$conn);
	if (mysql_num_rows($rskh)<1) {
		echo "关联账号不存在，请选择账号或者输入正确的账号！";exit;
	} 
	mysql_query("update crm_khb set khcsd='100%',state=1,yikayin_zh='".$_POST["zh"]."',yikayin_zhtime=now() where id=$id and not xsry is null",$conn);
	if (mysql_affected_rows()>0) echo "保存完成";	 else echo "出错，请检查原因！";
	exit;
}
$lxnr=$_POST["lxnr"];
$khcsd=$_POST["khcsd"];
$khcsd1=$_POST["khcsd1"];
$xclx=date("Y-m-d",strtotime("+".$_POST["xclx"]." day"));
if (strpos("11".$lxnr,"寄纸样")>0) {
	$rss=mysql_query("select count(1) from crm_khb where id=$id and LENGTH(lxr)>2 and LENGTH(lxdz)>6 and (LENGTH(lxrmobile)>10 or LENGTH(lxdh)>8)",$conn);
	if (mysql_result($rss,0,0)==0) {
		echo "客户信息不完整，请先输入客户联系人、联系人手机、电话、联系地址并保存才能寄纸样。";
		exit;
	}
}
$msg="保存完成";
mysql_query("delete from crm_khb_contact where khbid=$id and kid='$kid'",$conn);
if ($_POST["xclx"]=="0")
	mysql_query("update crm_khb set khcsd='$khcsd',contacttime=now() where id=$id",$conn);
else
	mysql_query("update crm_khb set khcsd='$khcsd',nextlx='$xclx',contacttime=now() where id=$id",$conn);
if ($_GET["lx"]=="zhuan") {  //转客户
	$ru=mysql_query("update crm_khb set state=-2,xsry='".$_SESSION["YKOAUSER"]."/".$_SESSION["XM"]."',xsryfpsj=now() where id=$id",$conn);
	if (mysql_affected_rows($ru)==0) $msg="保存失败！请重试。";
	$lxnr.="[转意向客户]";
}
if ($_GET["lx"]=="diuqi") {
	$ru=mysql_query("update crm_khb set khcsd='0%',state=-10,contacttime=now() where id=$id",$conn);$lxnr.="[无法联系]";
	if (mysql_affected_rows($ru)==0) $msg="保存失败！请重试。";
	}
if ($_GET["lx"]=="tuihui") { //暂无意向
	$xclx=date("Y-m-d",strtotime("+30 day"));
	$ru=mysql_query("update crm_khb set khcsd='10%',state=-1,xsry=null,xsryfpsj=null,nextlx='$xclx',yikayin_zh=null,yikayin_zhtime=null,contacttime=now() where id=$id",$conn);
	if (mysql_affected_rows($ru)==0) $msg="保存失败！请重试。";
	$lxnr.="[暂无意向]";
}
if (!mysql_query("insert into crm_khb_contact values (0,$id,'".$_SESSION["YKOAUSER"]."',now(),'$lxnr','$kid')",$conn)) $msg="保存失败！请重试。";

if (strpos("11".$lxnr,"寄纸样")>0) {
	$ru=mysql_query("update crm_khb set zyrequesttime=now() where id=$id",$conn);
	if (mysql_affected_rows($ru)==0) $msg="保存失败！请重试。";
}

echo $msg;
?>