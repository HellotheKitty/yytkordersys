<? require("../inc/conn.php");//require("inc/SendSMS.php");?>
<? session_start();
if ($_SESSION["YKOAUSER"]=="") { 
echo "<script language=JavaScript>{window.location.href='../error.php';}</script>";
exit; }
if ($_POST["ID"]<>"") {
	mysql_query("update task_list set fromOrder='".$_POST["vv"]."' where id=".$_POST["ID"],$conn);
	echo "操作成功";
	exit;
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>任务工单</title>

<style type="text/css">
<!--
body {
	background-color: #A5CBF7;
}
.style11 {font-size: 14px}
.STYLE13 {font-size: 12px}
.STYLE15 {font-size: 12px; color: #FF0000; }
h3 {
		width: 180px;
		height: 50px;
		line-height: 50px;
		margin: 0 auto;
		color: #fff;
		font-size: 20px;
	}
-->
</style>
<script language="javascript">
 function modiit(b1,b2,b3) {
 			var xmlHttpReq;
            if (typeof (XMLHttpRequest) != "undefined")
                xmlHttpReq = new XMLHttpRequest();
            else if (window.ActiveXObject)
                xmlHttpReq = new ActiveXObject("MSXML2.XMLHTTP.3.0");
            xmlHttpReq.open("POST", "YKKF_taskshow.php?jid=" + Math.round(Math.random() * 10000), false);
            xmlHttpReq.setRequestHeader("Content-Type","application/x-www-form-urlencoded;charset=UTF-8");
            xmlHttpReq.send("ID=" + b1 + "&vv=" + b2+ "&lx=" + b3);
            if (xmlHttpReq.status == 200) {
                var data = xmlHttpReq.responseText;
				alert(data);     //测试返回数据
                if (data.indexOf("Error") == 0) {
                    alert(data.replace("Error:",""));
                } else {
                    isOk = true;
                }
            }
}
</script>
</head>
<? 

if ($_GET["BH6"]<>"") { 
	mysql_query("update task_list set taskfile1=replace(taskfile1,'".$_GET["BH6"].";','') where id=".$_GET["id"],$conn);
}
if ($_GET["BH7"]<>"") { 
	mysql_query("update task_list set taskfile2=replace(taskfile2,'".$_GET["BH7"].";','') where id=".$_GET["id"],$conn);
}

if ($_GET["oldid"]<>"" && $_SESSION["YKUSERNAME"]) {
	mysql_query("INSERT INTO task_list (taskcreatetime, tasktype, fromuser, fromorder, taskmemo, taskstate,statetime,taskrecver,taskrecvtime,taskdescribe,taskfile1,taskfile2,taskparam,srcid) select now(),'13',fromuser,fromorder,'".$_SESSION["YKUSERNAME"]."创建的工单:".urldecode($_GET["sm"])."','排队中',now(),'".$_GET["oabh"]."',now(),taskdescribe,taskfile1,taskfile2,taskparam,srcid from task_list where id=".$_GET["oldid"], $conn);
	echo "<div class=h3>工单验收任务创建成功!</div>";
	exit;
}
/* if ($_GET["sendmb"]<>"") {
	$ss=urldecode($_GET["sm"]);
	sendsms($_GET["sendmb"],$ss);
	mysql_query("insert into nc_erp.smssend_log values (0,now(),'".$_GET["sendmb"]."','$ss')");
	echo "<div class=h3>发送短信成功!【".$_GET["sendmb"].":$ss】</div>";
	exit;
}
 */
$rs=mysql_query("select taskname,taskrecver,taskrecvtime,fromuser,fromorder,taskdescribe,taskfile1,taskfile2,srcid,task_list.taskmemo,task_list.tasktype from task_list,task_type where task_list.tasktype=task_type.tasktype and task_list.id=".$_GET["id"],$conn);
?>


<body>
<table width="600" border="1" cellpadding="4" cellspacing="1" bordercolor="#000099" bgcolor="f6f6f6" >
  <tr>
<td height="222" valign="top">
<form action="" method="post" ENCTYPE="multipart/form-data" name="form1" id="form1" onSubmit="return checkForm()">
      
     <table width="580" height="211" cellspacing="0" cellpadding="0" rules="all" border="1" id="gvOrder" style="border-width:0px;border-collapse:collapse;border:1px solid #D3D3D3; border-bottom:1px solid #D3D3D3; font-size:12px">
      <tr>
              <td height="27" colspan="4" class="STYLE13" align="center"><B>任务工单</B></td>
          </tr>
          <tr>
              <td width="72" height="27" class="STYLE13">任务类型</td>
              <td width="185"><span class="STYLE13">
                <? echo "<B>",mysql_result($rs,0,"taskname"),"</B>";?>
                　
              </span></td>
            <td width="69"><span class="STYLE13">客服人员： </span></td>
              <td width="205"><span class="STYLE13"><? echo mysql_result($rs,0,"taskrecver")?></span></td>
              
          </tr>

        
        <tr>
            <td height="27" class="STYLE13">客户账号</td>
            <td height="27" colspan="3" class="STYLE13">
                <? $rss=mysql_query("select khmc,lxr,lxdh from base_kh where mpzh='".mysql_result($rs,0,"fromuser")."'",$conn);
				if (mysql_num_rows($rss)>0) 
					echo "<B>",mysql_result($rs,0,"fromuser"),"</B> ",mysql_result($rss,0,"khmc"),'-',mysql_result($rss,0,"lxr"),mysql_result($rss,0,"lxdh"); 
				else
					echo "<B>",mysql_result($rs,0,"fromuser"),"</B> ";
				if (mysql_result($rs,0,"tasktype")=="3") {?> 　　<input type="button" value="创建客户订单" onClick="javascript:window.open('../ncerp/jcsj/NS_new.php?lx=1&taskid=<? echo $_GET["id"]?>&xsbh='+encodeURI('<? echo $_SESSION["YKOAUSER"]?>'), 'OrderDetail12', 'height=600px,width=920px,top=100px,left=200px,location=no,menubar=no,resizable=no,scrollbars=yes,status=no,titlebar=no,toolbar=no,directories=no')"><? }?></td>

          </tr>
		<? if (mysql_result($rs,0,"tasktype")=="9") {  //投诉类?> 
         <tr>
            <td height="27" class="STYLE13">投诉种类</td>
            <td height="27" colspan="3" class="STYLE13"><font color="red">
                 <? switch (mysql_result($rs,0,"srcid")) { 
				 		case 9001: echo "对产品质量的投诉：文件制作 色差 裁切 纸张 印刷"; break;
				 		case 9002: echo "对收货时效的投诉：没有如期发货 快递送货延迟 发错货 少发货"; break;
				 		case 9003: echo "对服务的投诉：服务质量 服务流程 服务人员"; break;
				 		case 9004: echo "其他投诉"; break;
				 }?>
        		</font>
              </td>

          </tr>
          <? } ?>
         <tr>
             <td height="27" class="STYLE13">任务描述</td>
             <td height="27" colspan="3">
             <textarea name="describe" id="describe" cols="45" rows="5"><? echo mysql_result($rs,0,"taskdescribe")?></textarea></td>

          </tr>


            <tr>
              <td height="27" class="STYLE13">主要文件</td>
              <td colspan="3" class="STYLE13"><? 
			$aaa=array_unique(explode(";",mysql_result($rs,0,"taskfile1")));
			foreach ($aaa as $key=>$a1)  
				if ($a1<>"") 
					echo "<a href='{$localftp}/files/{$a1}' target='_blank'>{$a1}</a>  ","<br>";
			?></td>
            </tr>
            
            <tr>
              <td height="27" class="STYLE13">其他文件</td>
              <td colspan="3" class="STYLE13"><? 
			$aaa=array_unique(explode(";",mysql_result($rs,0,"taskfile2")));
			foreach ($aaa as $key=>$a1)  
				if ($a1<>"") 
					echo "<a href='{$localftp}/files/{$a1}?".rand(10,1000)."' target='_blank'>{$a1}</a>  ","　";
			?></td>
            </tr>
            <tr>
              <td height="33" colspan="4" class="STYLE13">任务收到时间：<? echo mysql_result($rs,0,"taskrecvtime")?></td>
            </tr>
             <tr>
              <td height="33" colspan="4" class="STYLE13">任务备注：<? echo mysql_result($rs,0,"taskmemo");
			  if (mysql_result($rs,0,"tasktype")=="14") {?> 　　<input type="button" value="打开订单" onClick="javascript:window.open('../ncerp/jcsj/NS_new.php?ddh=<? $testddh = substr(mysql_result($rs,0,"taskmemo"),-15); echo (is_numeric($testddh))?$testddh:substr($testddh,-11);?>', 'OrderDetail13', 'height=600px,width=920px,top=100px,left=200px,location=no,menubar=no,resizable=no,scrollbars=yes,status=no,titlebar=no,toolbar=no,directories=no')"><? }
			   if (mysql_result($rs,0,"tasktype")=="15") {?> 　　<input type="button" value="结算订单" onClick="javascript:window.open('../ncerp/jcsj/NS_new.php?lx=js&ddh=<? $testddh = substr(mysql_result($rs,0,"taskmemo"),-15); echo (is_numeric($testddh))?$testddh:substr($testddh,-11);?>', 'OrderDetail13', 'height=600px,width=920px,top=100px,left=200px,location=no,menubar=no,resizable=no,scrollbars=yes,status=no,titlebar=no,toolbar=no,directories=no')"><? }?></td>
            </tr>
             <tr>
              <td height="33" colspan="4" class="STYLE13">执行情况记录：<textarea name="taskzx" id="taskzx" cols="50" rows="3"><? echo mysql_result($rs,0,"fromorder")?></textarea><input name="zx" type="button" value="保存" onClick="modiit('<? echo $_GET["id"];?>',document.getElementById('taskzx').value,'')">
              
			  <? $rsry=mysql_query("select oabh from task_kfry where xm='".mb_substr(mysql_result($rs,0,"taskmemo"),0,mb_strpos(mysql_result($rs,0,"taskmemo"),'创建的工单',null,'utf-8'),"utf-8")."'",$conn);
			  
			  if (mysql_result($rs,0,"taskname")<>'工单验收' and mysql_num_rows($rsry)<0) {?>
              <br>
              任务需要验收：<input name="gdhf" type="text" id="gdhf" value="您的工单已经处理完成，请验收！" size="50"/>
              <input type="button" value="发送给<? echo substr(mysql_result($rs,0,"taskmemo"),0,strpos(mysql_result($rs,0,"taskmemo"),'创建的工单'));?>" onClick="javascript:window.location.href='?oldid=<? echo $_GET["id"]?>&oabh=<? echo mysql_result($rsry,0,0)?>&sm='+encodeURI(document.getElementById('gdhf').value);">
              <? } 
			  $rskh=mysql_query("select lxdh from base_kh where mpzh='".mysql_result($rs,0,"fromuser")."'",$conn);
			  if ((mysql_result($rs,0,"taskname")=='文件制作11') and mysql_num_rows($rskh)>0) {?>
              <br>
              短信联系客户：<input name="gdhf" type="text" id="gdhf" value="尊敬的用户，您的文件已制作完成，如需确认，请联系客服123456。" size="50"/>
              <input type="button" value="发送短信" onClick="javascript:window.location.href='?sendmb=<? echo mysql_result($rskh,0,"lxdh")?>&sm='+encodeURI(document.getElementById('gdhf').value);" disabled>
              <? }?></td>
            </tr>
        </table>
        
</form>
    </td>
  </tr>
</table>
</body>
</html>
<? 
$rs=null;
unset($rs);
$rss=null;
unset($rss);
?>
