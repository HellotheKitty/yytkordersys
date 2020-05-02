<? session_start();
require("inc/conn.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="refresh" content="2000">
<script src="jsp/jquery-1.9.1.min.js" type="text/javascript"></script>
<script>
$.support.cors = true;
</script>
<title></title>
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
	.but6 {
		border-radius: 5px; cursor:pointer; width:35px; height:25px; text-align: center; color:#fff; border:1px solid #333333; background: #333333; -webkit-appearance: none; font-size: 12px;
	}
	.but7 {
		border-radius: 5px; cursor:pointer; width:35px; height:25px; text-align: center; color:#fff; border:1px solid #2aa439; background: #288439; -webkit-appearance: none; font-size: 13px;
	}
	.but8 {
		border-radius: 5px; cursor:pointer; width:35px; height:25px; text-align: center; color:#fff; border:1px solid #2aa439; background: #bbbbbb; -webkit-appearance: none; font-size: 13px;
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
	.titleIDnow {
		height: 25px;
		width: 100%;
		line-height: 25px;
		text-align: left;
		background:#0CF;
		color: #333;
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
		margin: 10px 0px 10px 0px;
		text-align:center
	}
</style>
<body>
<div style="text-align:center">
<input type="button" class="but7" href="javascript:void(0)" onClick="javascript:window.parent.cmain.location.href='YKKF_newtask.php';return false;" value="新建" >
<input type="button" class="but7" href="javascript:void(0)" onClick="javascript:window.parent.cmain.location.href='YKKF_tools.php';return false;" value="工具" >
<input type="button" class="but7" href="javascript:void(0)" onClick="javascript:window.location.reload();return false;" value="刷新" >
<input type="button" class="but7" href="javascript:void(0)" onClick="javascript:window.parent.cmain.location.href='YKKF_taskhistory.php';return false;" value="历史" >
</div>
<div style="font-size:14px;background: #eee;line-height: 25px;">&nbsp;<? echo $_SESSION["YKOAUSER"]?>'s任务列表</div>
<? $rs=mysql_query("select list.id,list.tasktype,fromuser,taskcreatetime,taskstate,statetime,taskname,taskpointer,taskparam,taskdescribe from task_list list,task_type type where list.tasktype=type.tasktype and taskrecver='".$_SESSION["YKOAUSER"]."' and (taskstate<>'已完成' or (taskstate='已完成' and timestampdiff(minute,taskendtime,now())<1)) order by taskrecvtime",$conn);
$tot=mysql_num_rows($rs);
for($i=0;$i<mysql_num_rows($rs);$i++){  ?>
        <div id="orderList">
        <div class="<? echo mysql_result($rs,$i,"id")==$_COOKIE['currenttaskid']?"titleIDnow":"titleID";?>">&nbsp;<? echo $tot,"-",$i+1,":",mysql_result($rs,$i,"taskname"),"[",mysql_result($rs,$i,"id"),"]";?></div>
        <div class="content">
        &nbsp;&nbsp;账号：<? echo mysql_result($rs,$i,"fromuser");if (mysql_result($rs,$i,"fromuser")<>"") {?>
        <a href='javascript:window.open("YK_kf_user.php?lx=show&YIKAZH=<? echo base_encode(mysql_result($rs,$i,"fromuser"));?>",window,"width=800,height=485,top=100,left=100,toolbar=no,menubar=no,scrollbars=no, resizable=yes,location=no, status=no");'>详情</a><? } else echo "<font color=red>无账号异常</font>";?>
        <br>&nbsp;&nbsp;创建：<? echo mysql_result($rs,$i,"taskcreatetime");?>
        <br>&nbsp;&nbsp;状态：<? echo mysql_result($rs,$i,"taskstate")=="处理中"?("<font color=blue>".mysql_result($rs,$i,"taskstate")."</font>"):mysql_result($rs,$i,"taskstate")," ",floor((strtotime(date('Y-m-d H:i:s'))-strtotime(mysql_result($rs,$i,"statetime")))%86400/3600),":",floor((strtotime(date('Y-m-d H:i:s'))-strtotime(mysql_result($rs,$i,"statetime")))%86400%3600/60),":",floor((strtotime(date('Y-m-d H:i:s'))-strtotime(mysql_result($rs,$i,"statetime")))%86400%3600%60);?>
        </div>
        <div class="orderOption">
           	<? if (mysql_result($rs,$i,"taskstate")=="排队中") {
				if (strpos(mysql_result($rs,$i,"fromuser"),"^")>0) { //A类
					$rsp=mysql_query("select http from nc_erp.dbinfo where dname='".substr(mysql_result($rs,$i,"fromuser"),0,-1)."'");?>
             <input type="button" class="but6 click2BeginA" href="javascript:void(0)" data-open="<? echo mysql_result($rsp,0,0)."/YKCart.php"?>" data-id="<? echo mysql_result($rs,$i,"id")?>" value="开始" > 
            <? } else {?>
            <input type="button" class="but6 click2Begin" href="javascript:void(0)" data-parent="<? echo mysql_result($rs,$i,"taskparam")=="gongdan"?("YKKF_taskshow.php?id=".mysql_result($rs,$i,"id")):(mysql_result($rs,$i,"taskpointer").mysql_result($rs,$i,"taskparam"))?>" data-id="<? echo mysql_result($rs,$i,"id")?>" value="开始" > 
            <? }
			} else {
				if (strpos(mysql_result($rs,$i,"fromuser"),"^")>0) { //A类
					$rsp=mysql_query("select http from nc_erp.dbinfo where dname='".substr(mysql_result($rs,$i,"fromuser"),0,-1)."'");?>
            <input type="button" class="but6" href="javascript:void(0)" onClick="javascript:window.open('<? echo mysql_result($rsp,0,0)."/YKCart.php"?>');document.cookie='currenttaskid=<? echo mysql_result($rs,$i,"id")?>';window.location.reload();return false;" value="打开" > 
            <? } else {?>
            <input type="button" class="but6" href="javascript:void(0)" onClick="javascript:parent.cmain.document.location='<? echo mysql_result($rs,$i,"taskparam")=="gongdan"?("YKKF_taskshow.php?id=".mysql_result($rs,$i,"id")):(mysql_result($rs,$i,"taskpointer").mysql_result($rs,$i,"taskparam"))?>';document.cookie='currenttaskid=<? echo mysql_result($rs,$i,"id")?>';window.location.reload();return false;" value="打开" > 
            <? }
			}?>
            <? if (mysql_result($rs,$i,"taskstate")=="挂起" or mysql_result($rs,$i,"taskstate")=="已完成") {?>
        	<input type="button" class="but8" value="挂起" title="<? echo mysql_result($rs,$i,"taskdescribe")?>"> 
            <? } else {?>
            <input type="button" class="but6" href="javascript:void(0)" onClick="javascript:parent.cmain.document.location='YKKF_tasksuspend.php?id=<? echo mysql_result($rs,$i,"id");?>&task=<? echo $tot,"-",$i+1,":",mysql_result($rs,$i,"taskname"),"[",mysql_result($rs,$i,"id"),"]";?>';return false;" value="挂起" > 
            <? }?>
             <input type="button" class="but6" href="javascript:void(0)" onClick="javascript:parent.cmain.document.location='YKKF_taskredirect.php?id=<? echo mysql_result($rs,$i,"id");?>&task=<? echo $tot,"-",$i+1,":",mysql_result($rs,$i,"taskname"),"[",mysql_result($rs,$i,"id"),"]";?>';return false;" value="转移" > 
             <? if (mysql_result($rs,$i,"taskstate")=="已完成") {?>
             <input type="button" class="but8" value="完成"> 
             <? } else {?>
        	<input type="button" class="but6 click2Finish" data-id="<? echo mysql_result($rs,$i,"id")?>" value="结束" > 
            <? }?>
        	</div>
        </div>
		
		</div>
        <? }?>
        <script>

        function actions(thisBtn, command) {

        	//var c = arguments[2] ? arguments[2] : ''; 

        	var mId = thisBtn.attr('data-id');
        	$.ajax({  
                cache: false,  
                type: "POST",  
                url: "YKKF_save.php",  
                data: {
                    command: command,
                    id: mId
                },
                //dataType: 'json', 
                beforeSend: function() { 

                },
                error: function(request) {  

                },  
                success: function(data) {  
                	if (c != '') {
                		document.cookie = 'currenttaskid=' + mId;
                	}
                	setTimeout("window.location.reload()", 100);
                	
                }  
            })
        }

        $(document).ready(function() {

        	$(".click2BeginA").on('click', function() {

        		window.open($(this).attr('data-open'));
        		actions($(this), 'begin');

        	})

        	$(".click2Begin").on('click', function() {

        		parent.cmain.document.location = $(this).attr('data-parent');
        		actions($(this), 'begin');
        	})

        	$(".click2Finish").on('click', function() {

        	
        		if (confirm('确定任务完成并结束吗？')) {
        			//$.post('YKKF_save.php',{command:'end',id: mId},function(data){});
        			actions($(this), 'end');
        		}
        	})

        });

        </script>
</body>
</html>