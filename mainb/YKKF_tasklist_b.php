<? session_start();
require("../inc/conn.php");
?>

<? $rs=mysql_query("select DISTINCT list.id,list.tasktype,fromuser,taskcreatetime,taskstate,statetime,taskname,taskpointer,taskparam,taskdescribe,list.taskmemo from task_list list,task_type type where list.tasktype=type.tasktype and taskrecver='".$_SESSION["YKOAUSER"]."' and (taskstate<>'已完成' or (taskstate='已完成' and timestampdiff(minute,taskendtime,now())<1)) order by taskrecvtime",$conn);
$tot=mysql_num_rows($rs);
for($i=0;$i<mysql_num_rows($rs);$i++){  ?>
        <div id="orderList">
        <div class="<? echo mysql_result($rs,$i,"id")==$_COOKIE['currenttaskid']?"titleIDnow":"titleID";?>">&nbsp;<? echo $tot,"-",$i+1,":",mysql_result($rs,$i,"taskname"),"[<a href='#' onclick=\"javascript:alert('[任务描述]：".mysql_result($rs,$i,"taskdescribe")."  [任务备注]：".mysql_result($rs,$i,"taskmemo")."');return false;\">",mysql_result($rs,$i,"id"),"</a>]";?></div>
        <div class="content">
        &nbsp;&nbsp;账号：<? echo mysql_result($rs,$i,"fromuser");if (mysql_result($rs,$i,"fromuser")<>"") {?>
        <a href='javascript:window.open("YK_kf_user.php?YIKAZH=<? echo base_encode(mysql_result($rs,$i,"fromuser"));?>",window,"width=850,height=585,top=100,left=100,toolbar=no,menubar=no,scrollbars=no, resizable=yes,location=no, status=no");'>详情</a><? } else echo "<font color=red>无账号异常</font>";?>
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
            <!--<input type="button" class="but6" href="javascript:void(0)" onClick="javascript:window.open('<? //echo mysql_result($rsp,0,0)."/YKCart.php"?>');document.cookie='currenttaskid=<? //echo mysql_result($rs,$i,"id")?>';window.location.reload();return false;" value="打开" >  -->
            <input type="button" class="but6 click2OpenA" href="javascript:void(0)" data-open="<? echo mysql_result($rsp,0,0)."/YKCart.php"?>" data-id="<? echo mysql_result($rs,$i,"id")?>" value="打开" >
            <? } else {?>
            <!--<input type="button" class="but6" href="javascript:void(0)" onClick="javascript:parent.cmain.document.location='<? //echo mysql_result($rs,$i,"taskparam")=="gongdan"?("YKKF_taskshow.php?id=".mysql_result($rs,$i,"id")):(mysql_result($rs,$i,"taskpointer").mysql_result($rs,$i,"taskparam"))?>';document.cookie='currenttaskid=<? //echo mysql_result($rs,$i,"id")?>';window.location.reload();return false;" value="打开" >  -->
             <input type="button" class="but6 click2Open" href="javascript:void(0)" data-parent="<? echo mysql_result($rs,$i,"taskparam")=="gongdan"?("YKKF_taskshow.php?id=".mysql_result($rs,$i,"id")):(mysql_result($rs,$i,"taskpointer").mysql_result($rs,$i,"taskparam"))?>" data-id="<? echo mysql_result($rs,$i,"id")?>" value="打开" >
            <? }
			}?>
            <? if (mysql_result($rs,$i,"taskstate")=="挂起" or mysql_result($rs,$i,"taskstate")=="已完成") {?>
        	<input type="button" class="but8" value="挂起" > 
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
