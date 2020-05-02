<? require("../inc/conn.php");?>
<? session_start();
if ($_SESSION["YKUSERNAME"]=="") { 
echo "<script language=JavaScript>{window.location.href='../error.php';}</script>";
exit; 
}
?>
<?
$dwdm = substr($_SESSION["GDWDM"],0,4);echo $dwdm;
if ($_POST["type"]<>"") {
	$type=$_POST["type"];
	$zzry=$_POST["kfry"];
	$zh=$_POST["zh"];
	$fileName=$_POST["fileup1"];
	//$fileName=str_replace(".","",substr($fileName,0,-4)).substr($fileName,-4,4);
	$fileName=str_replace(" ","",$fileName);
	$fileName=str_replace("(","",$fileName);
	$fileName=str_replace(")","",$fileName);
	$zfile=$fileName;
	$fileName=$_POST["fileup2"];
	//$fileName=str_replace(".","",substr($fileName,0,-4)).substr($fileName,-4,4);
	$fileName=str_replace(" ","",$fileName);
	$fileName=str_replace("(","",$fileName);
	$fileName=str_replace(")","",$fileName);
	$gfile=$fileName;
	if ($zh<>"") {
		$rsc=mysql_query("select mpzh from base_kh where mpzh='$zh'",$conn);
		if (mysql_num_rows($rsc)==0) {
			echo "<script>alert('相关用户账号错误！请重新输入。');window.history.go(-1);</script>";
			exit;
		}
	}
	$ddh=$_POST["ddh"];
	$describe=$_POST["describe"];
	$csl=is_numeric($_POST["csl"])?$_POST["csl"]:1;


if ($zzry=="") {
	$info = json_decode(file_get_contents("http://oa.skyprint.cn/mainb/Getkfry.php?tasktype=$type&user=$zh"));
	$zzry=$info->kfry;	//制作人员
}
mysql_query("INSERT INTO task_list (taskcreatetime, tasktype, fromuser, fromorder, taskmemo, taskstate,statetime,taskrecver,taskrecvtime,taskdescribe,taskfile1,taskfile2,taskparam,srcid) VALUES (now(),'$type','$zh', '$ddh', '".$_SESSION["YKUSERNAME"]."创建的工单', '排队中', now(),'".$zzry."',now(),'$describe','$zfile','$gfile','gongdan',$csl)", $conn);
mysql_query("update task_kfry set taskamount=taskamount+1 where oabh='".$zzry."'",$conn);
if ($_POST["QQmemo"]<>"") 
	mysql_query("update base_kh set qqmemo=concat(ifnull(qqmemo,''),'".$_POST["QQmemo"]."') where mpzh='$zh'",$conn);

echo "<script>alert('工单创建成功！请等待处理。');window.location.href='YKKF_newtask.php';</script>";
}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>新建名片</title>

<style type="text/css">
<!--
body {
	background-color: #A5CBF7;
}
.style11 {font-size: 14px}
.STYLE13 {font-size: 12px}
.STYLE15 {font-size: 12px; color: #FF0000; }
-->
</style>
</head>
<SCRIPT language=JavaScript>
function checkForm(){
	var tmpFrm = document.forms[0];
    var charBag = "-0123456789.";
	if (tmpFrm.zh.value=="") {alert("用户账号必须填写！");return false;}
	if (tmpFrm.describe.value=="") {alert("任务描述请输入！");return false;}
	
	document.getElementById("wait").style.display="";
	return true; 
}

window.addEventListener('message',function(e){
            var data=e.data;
			if (data.substr(0,1)=="1")
            	if (data.substr(1)=="del") document.getElementById('fileup1').value=""; else document.getElementById('fileup1').value+=data.substr(1)+";";
			else
				if (data.substr(1)=="del") document.getElementById('fileup2').value=""; else document.getElementById('fileup2').value+=data.substr(1)+";";
        },false);
		
</SCRIPT>

<body>
<table width="599" border="1" cellpadding="4" cellspacing="1" bordercolor="#000099" bgcolor="f6f6f6" >
  <tr>
<td height="222" valign="top">
<form action="" method="post" ENCTYPE="multipart/form-data" name="form1" id="form1" onSubmit="return checkForm()">
      <input type="hidden" name="MAX_FILE_SIZE" value="100000000">
      <input type="hidden" name="needid" value="<? echo $_GET["needid"]?>">
     <table width="559" height="211" border="0" align="center">
      <tr>
              <td height="47" colspan="2" align="center">客服任务工单</td>
          </tr>
          <tr>
              <td width="54" height="27" class="STYLE13">任务类型</td>
              <td><span class="STYLE13">
                <select name="type" id="type" onChange="window.location.href='?tt='+this.options[this.selectedIndex].value;">
		<?
				$rs0=mysql_query("select tasktype,taskname,taskryxm,taskrecvxm from task_type where zzfy='$dwdm' order by tasktype");
				$tt=0;
				for ($i=0;$i<mysql_num_rows($rs0);$i++)
					if (mysql_result($rs0,$i,0)==$_GET["tt"]) {
						echo '<option value="'.mysql_result($rs0,$i,0).'" selected>'.mysql_result($rs0,$i,1).'</option>';
						$tt=$i;
					} else
						echo '<option value="'.mysql_result($rs0,$i,0).'" >'.mysql_result($rs0,$i,1).'</option>';
					?>
                </select>
                　指定客服人员：
                <select name="kfry" id="kfry">
			<? 
				$rs01=mysql_query("select bh,xm from b_ry where (instr('".mysql_result($rs0,$tt,2)."',xm)>0 or instr('".mysql_result($rs0,$tt,3)."',xm)>0) order by bh");
				  echo '<option value="">自动分配</option>';
				for ($i=0;$i<mysql_num_rows($rs01);$i++)
						echo '<option value="'.mysql_result($rs01,$i,0).'">'.mysql_result($rs01,$i,1).'</option>';
					?>
                </select>
              默认自动分配</span></td>
              <input type="hidden" name="ddh" value="<? echo $bh?>" />
          </tr>

        <tr>
            <td height="27" class="STYLE13">客户编号</td>
            <td height="27" class="STYLE13">
                <input name="zh" type="text" id="zh" size="12">
                *<input name="zxx6" type="button" value="选择" onClick="window.open('../ncerp/jcsj/KH_select.php?lx=task','actSwfUploadOpenWin1','dependent, toolbar=no,location=no,status=no,menubar=no,resizable=yes,scrollbars=auto,width=700,height=480,left=335.0,top=242.0'); return false"><span id='khmc'></span></td>

          </tr>
		
                <input name="ddh" type="hidden" id="ddh" size="20">
          <? if (mysql_result($rs0,$tt,0)=="9") {  //投诉类?> 
          <tr>
            <td height="27" class="STYLE13">投诉种类</td>
            <td height="27" class="STYLE13">
                 <select name="csl" id="csl">
                 <option value="9001">对产品质量的投诉：文件制作 色差 裁切 纸张 印刷</option>
                 <option value="9002">对收货时效的投诉：没有如期发货 快递送货延迟 发错货 少发货</option>
                 <option value="9003">对服务的投诉：服务质量 服务流程 服务人员</option>
                 <option value="9004">其他投诉</option>
                 </select>
              </td>

          </tr>
          <? } else {?>    
          <tr style="display:none">
            <td height="27" class="STYLE13">数量/次数</td>
            <td height="27" class="STYLE13">
                <input name="csl" type="text" id="csl" value="1" size="10">
                下单人数、制作人次等影响工作量的数量</td>

          </tr>
          <? }?>
         <tr>
             <td height="27" class="STYLE13">任务描述</td>
             <td height="27">
             <textarea name="describe" id="describe" cols="45" rows="3"></textarea></td>

          </tr>

		 <tr>
             <td height="27" class="STYLE13">QQ记录<br>粘贴</td>
             <td height="27">
             <textarea name="QQmemo" id="QQmemo" cols="45" rows="5"></textarea></td>

          </tr>
            <tr>
              <td height="27" class="STYLE13">主要文件</td>
              <td class="STYLE13"><input name="fileup1" type="text" id="fileup1" size="40"><a href="javascript:void(0)" onClick="javascript:window.open('<? echo $localftp?>/fileup.php?mxid=1&flx=file','OrderDetail4','height=300px,width=400px,top=100px,left=100px,location=no,menubar=no,resizable=no,scrollbars=yes,status=no,titlebar=no,toolbar=no,directories=no');return false;"  class="a1">[上传]</a></td>
            </tr>
            
            <tr>
              <td height="27" class="STYLE13">其他文件</td>
              <td class="STYLE13"><input name="fileup2" type="text" id="fileup2" size="40"><a href="javascript:void(0)" onClick="javascript:window.open('<? echo $localftp?>/fileup.php?mxid=2&flx=file','OrderDetail4','height=300px,width=400px,top=100px,left=100px,location=no,menubar=no,resizable=no,scrollbars=yes,status=no,titlebar=no,toolbar=no,directories=no');return false;"  class="a1">[上传]</a></td>
            </tr>
            <tr>
              <td height="53" colspan="2"><div align="center">

                <input type="submit" name="Submit"  value="提 交"> 

              </div></td>
            </tr>
        </table>
<div id="wait" style="width: 300px; padding: 5px 0; text-align: center; border: solid 1px #dddddd; background-color: #99FF66; position: absolute; top: 305px; left: 161px; margin: -50px 0 0 -100px; display:none; z-index: 1000; height: 58px; font-size:18px; line-height:58px">
  正在保存中，请稍候...
</div>               
</form>
    </td>
  </tr>
  <? $rsk=mysql_query("select taskdescribe,taskcreatetime,y.taskname,taskrecver,fromuser,taskstate from task_list l,task_type y where l.tasktype=y.tasktype and l.taskmemo like '%".$_SESSION["YKUSERNAME"]."创建的工单%' order by taskcreatetime desc limit 5");
  if (mysql_num_rows($rsk)>0) {?>
  <tr>
  <td>我最近创建的5个工单：
  <table cellspacing="0" cellpadding="0" rules="all" border="1" id="gvOrder" style="border-width:0px;width:100%;border-collapse:collapse;border:1px solid #D3D3D3; border-bottom:1px solid #D3D3D3; font-size:12px">
  <tr style="font-weight:bold">
    <td class="STYLE13" align="center">客户编码</td>
    <td class="STYLE13" align="center">任务类型</td>
    <td class="STYLE13" align="center">分配给</td>
    <td class="STYLE13" align="center">任务描述</td>
    <td class="STYLE13" align="center">创建时间</td>
    <td class="STYLE13" align="center">当前状态</td>
  </tr>
  <? for ($i=0;$i<mysql_num_rows($rsk);$i++) {?>
  <tr>
    <td class="STYLE13"><? echo mysql_result($rsk,$i,"fromuser")?></td>
    <td class="STYLE13"><? echo mysql_result($rsk,$i,"taskname")?></td>
    <td class="STYLE13"><? echo mysql_result($rsk,$i,"taskrecver")?></td>
    <td class="STYLE13"><? echo mysql_result($rsk,$i,"taskdescribe")?></td>
    <td class="STYLE13"><? echo mysql_result($rsk,$i,"taskcreatetime")?></td>
    <td class="STYLE13"><? echo mysql_result($rsk,$i,"taskstate")?></td>
  </tr>
  <? }?>
</table>

  </td>
  </tr>
  <? }?>
</table>
</body>
</html>
<? 
$rs=null;
unset($rs);
$rss=null;
unset($rss);
?>
