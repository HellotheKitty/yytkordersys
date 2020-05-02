<? require("../inc/conn.php"); 
session_start();
if ($_SESSION["YKOAOK"]<>"OK") { 
echo "<script language=JavaScript>{window.location.href='error.php';}</script>";
exit; }?>
<? 
if ($_POST["lx"]=="D") {
	foreach($_POST["checkBox"] as $key => $id) { 
		mysql_query("update crm_khb set state=-100,contacttime=now() where id=".$id);
	}
}
if ($_GET["team"]<>"") {
	$_SESSION["CRM_TEAM"]=$_GET["team"];
	$rscf=mysql_query("select * from crm_callconfig where bh='".$_SESSION["YKOAUSER"]."' and team='".$_GET["team"]."' and qx>'000'",$conn);
	$_SESSION["CRM_FJH"]=mysql_result($rscf,0,"fjh");
	$_SESSION["CRM_HTTP"]=mysql_result($rscf,0,"http");
}
if ($_GET["finds"]!==null) {
	$_SESSION["FINDSTR"]=" and (crm_khb.khmc like '%".$_GET["finds"]."%' or crm_khb.lxr like '%".$_GET["finds"]."%')";
	$_SESSION["Xpage"]=1;
}
if ($_GET["khcsd"]!==null) {$_SESSION["FINDKHCSD"]=$_GET["khcsd"];$_SESSION["FINDSSHY"]=$_GET["sshy"];$_SESSION["Xpage"]=1;}

if ($_GET["wjdq"]!==null) mysql_query("update crm_callconfig set shareto='".$_GET["wjdq"]."' where bh='".$_SESSION["YKOAUSER"]."'",$conn);

//sale
if ($_GET["gp"]<>"") {$gp=$_GET["gp"];$_SESSION["Xpage"]=1;} else $gp=$_SESSION["Xgp"];
$_SESSION["Xgp"]=$gp;
if ($gp=="2") 
	$rs=mysql_query("select crm_khb.*,count(crm_khb_contact.id) lxcs from crm_khb left join crm_khb_contact on crm_khb.id=crm_khb_contact.khbid where left(xsry,instr(xsry,'/')-1)='".$_SESSION["YKOAUSER"]."' and (datediff(now(),nextlx)<0) and state=-2 ".$_SESSION["FINDSTR"]." and (khcsd like '".$_SESSION["FINDKHCSD"]."%' or khcsd is null) and (sshy like '".$_SESSION["FINDSSHY"]."%' or sshy is null) group by crm_khb.id order by xsryfpsj desc", $conn); 
elseif ($gp=="5") {
	$rs=mysql_query("select crm_khb.*,count(crm_khb_contact.id) lxcs,count(m.ddh) dds from crm_khb left join crm_khb_contact on crm_khb.id=crm_khb_contact.khbid left join nc_erp.order_main m on m.user=yikayin_zh and m.dje>0 where (left(xsry,instr(xsry,'/')-1)='".$_SESSION["YKOAUSER"]."' or left(xsry,instr(xsry,'/')-1) in (select bh from crm_callconfig where instr(shareto,'".($_SESSION["XM"]==""?"hjhjhj":$_SESSION["XM"])."')>0)) and crm_khb.state>0 ".$_SESSION["FINDSTR"]." and (khcsd like '".$_SESSION["FINDKHCSD"]."%' or khcsd is null) and (sshy like '".$_SESSION["FINDSSHY"]."%' or sshy is null) group by crm_khb.id order by instr(xsry,'".$_SESSION["YKOAUSER"]."') desc,xsryfpsj desc", $conn); 
	}
else {
	if ($_GET["lx"]=="xc")
		$rs=mysql_query("select crm_khb.*,count(crm_khb_contact.id) lxcs,datediff(now(),xsryfpsj) xc,datediff(now(),nextlx) nc from crm_khb left join crm_khb_contact on crm_khb.id=crm_khb_contact.khbid where left(xsry,instr(xsry,'/')-1)='".$_SESSION["YKOAUSER"]."' and (nextlx is null or datediff(now(),nextlx)>=0) and state=-2 ".$_SESSION["FINDSTR"]." and (khcsd like '".$_SESSION["FINDKHCSD"]."%' or khcsd is null) and (sshy like '".$_SESSION["FINDSSHY"]."%' or sshy is null) and datediff(now(),xsryfpsj)>85  group by crm_khb.id order by nextlx,xsryfpsj", $conn); 
	if ($_GET["lx"]=="nc")
		$rs=mysql_query("select crm_khb.*,count(crm_khb_contact.id) lxcs,datediff(now(),xsryfpsj) xc,datediff(now(),nextlx) nc from crm_khb left join crm_khb_contact on crm_khb.id=crm_khb_contact.khbid where left(xsry,instr(xsry,'/')-1)='".$_SESSION["YKOAUSER"]."' and (nextlx is null or datediff(now(),nextlx)>=0) and state=-2 ".$_SESSION["FINDSTR"]." and (khcsd like '".$_SESSION["FINDKHCSD"]."%' or khcsd is null) and (sshy like '".$_SESSION["FINDSSHY"]."%' or sshy is null) and datediff(now(),nextlx)>0 group by crm_khb.id order by nextlx,xsryfpsj", $conn); 
	if ($_GET["lx"]=="gc")
		$rs=mysql_query("select crm_khb.*,count(crm_khb_contact.id) lxcs,datediff(now(),xsryfpsj) xc,datediff(now(),nextlx) nc from crm_khb left join crm_khb_contact on crm_khb.id=crm_khb_contact.khbid where left(xsry,instr(xsry,'/')-1)='".$_SESSION["YKOAUSER"]."' and (nextlx is null or datediff(now(),nextlx)>=0) and state=-2 ".$_SESSION["FINDSTR"]." and (khcsd like '".$_SESSION["FINDKHCSD"]."%' or khcsd is null) and (sshy like '".$_SESSION["FINDSSHY"]."%' or sshy is null) and datediff(now(),xsryfpsj)<86 and datediff(now(),nextlx)<=0 group by crm_khb.id order by nextlx,xsryfpsj", $conn); 
	if ($_GET["lx"]=="")
		$rs=mysql_query("select crm_khb.*,count(crm_khb_contact.id) lxcs,datediff(now(),xsryfpsj) xc,datediff(now(),nextlx) nc from crm_khb left join crm_khb_contact on crm_khb.id=crm_khb_contact.khbid where left(xsry,instr(xsry,'/')-1)='".$_SESSION["YKOAUSER"]."' and (nextlx is null or datediff(now(),nextlx)>=0) and state=-2 ".$_SESSION["FINDSTR"]." and (khcsd like '".$_SESSION["FINDKHCSD"]."%' or khcsd is null) and (sshy like '".$_SESSION["FINDSSHY"]."%' or sshy is null) group by crm_khb.id order by nextlx,xsryfpsj", $conn);
	$rs0=mysql_query("select crm_khb.*,count(crm_khb_contact.id) lxcs,datediff(now(),xsryfpsj) xc,datediff(now(),nextlx) nc from crm_khb left join crm_khb_contact on crm_khb.id=crm_khb_contact.khbid where left(xsry,instr(xsry,'/')-1)='".$_SESSION["YKOAUSER"]."' and (nextlx is null or datediff(now(),nextlx)>=0) and state=-2 ".$_SESSION["FINDSTR"]." and (khcsd like '".$_SESSION["FINDKHCSD"]."%' or khcsd is null) and (sshy like '".$_SESSION["FINDSSHY"]."%' or sshy is null) group by crm_khb.id order by nextlx,xsryfpsj", $conn); 
	$xc=0;$nc=0;$gc=0;
	for($i=0;$i<mysql_num_rows($rs0);$i++){
		if (mysql_result($rs0,$i,"nc")>0) $nc++; 
		if (mysql_result($rs0,$i,"xc")>85) $xc++; 
		if (mysql_result($rs0,$i,"nc")<=0 and mysql_result($rs0,$i,"xc")<86) $gc++;
	}
}

//分页
if ($tj<>"") {$page_num=mysql_num_rows($rs)+1;} else {$page_num=15;}     //每页行数
if ($_GET["pno"]<>"") $page_no=$_GET["pno"]; else $page_no=$_SESSION["Xpage"];     //当前页
if ($page_no=="") {$page_no=1;}
$_SESSION["Xpage"]=$page_no;
$page_f=$page_num*($page_no -1);   //开始行
$page_e=$page_f+$page_num;			//结束行
if ($page_e>mysql_num_rows($rs)) {$page_e=mysql_num_rows($rs);}
$page_t=ceil(mysql_num_rows($rs) / $page_num);  //总页数
//分页
?> 
<script language="javascript">
function checkAll(selectAllObj) {
	var checkBoxObjAry = document.getElementsByName("checkBox[]");
	var count = checkBoxObjAry.length;
	var selectAllFlg = selectAllObj.checked;
	for (var i = 0; i < count; i++) {
		checkBoxObjAry[i].checked = selectAllFlg;
	}
}
function ifChecked() 
{
   var a = document.getElementsByName("checkBox[]"); 
   var n = a.length;
   var k = 0;
   for (var i=0; i<n; i++){
        if(a[i].checked){
            k = 1;
        }
    }
        if(k==0){
        alert("请先选择条目!");
        return false;
    }
	return true;
 }
 </script>

<HTML>
<HEAD>
    
    <META content="MSHTML 6.00.2800.1276" name=GENERATOR>
    <META http-equiv=Content-Type content="text/html; charset=utf-8">
    <META http-equiv=Content-Style-Type content=text/css>
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<meta name="viewport" content="width=device-width,minimum-scale=1.0"/>
<TITLE>百立易卡--客户信息</TITLE>

<LINK href="../css/mainWin.css" type=text/css  media=screen rel=stylesheet>
<LINK href="../css/query.css" type=text/css  media=screen rel=stylesheet>
<LINK href="../css/02.css" type=text/css  media=screen rel=stylesheet>
<LINK href="../css/mainWin2.css" type=text/css  media=screen rel=stylesheet>
    <base target="_self">
</HEAD>

<body marginwidth="0" topmargin="0" leftmargin="0"  marginheight="0">
<div class="mainbackground">
<form method="post" id="actForm" name="actForm" action="">
<input type="hidden" name="lx" value="D" />

		<DIV ID=Title_bar>
			<DIV ID=Title_bar_Head>
				<DIV ID=Title_Head></DIV>
				<DIV ID=Title>
					<img border="0" width="18" height="18" src="../images/title_arrow2.gif" />
					我的客户
		    </DIV>
				<DIV ID=Title_End></DIV>
				<DIV ID=Title_bar_bg></DIV>
			</DIV>
			<DIV ID=Title_bar_Tail>
				<DIV ID=Title_FuncBar>
					<ul>
							<LI CLASS=line></LI>
							<LI CLASS=title>
							  <div onClick="window.location.href='KH_add.php';" class="Btn">
							    增加客户
						      </div>
 
</LI>
					 <LI CLASS=line></LI>
							<LI CLASS=title>
						<div onClick="if (ifChecked() && confirm('确定要删除吗？')!=false) actForm.submit();" class="Btn">
							    删除选中
						      </div>
 
</LI>
		 <LI CLASS=line></LI>
						
					</ul>
				</DIV>
			</DIV>
		</DIV>
        <input name="select" type="radio" value="1" <? if ($gp=="" or $gp=="1") echo "checked";?> onClick="window.location.href='KH_list_X.php?gp=1'">今日需联系
        <input name="select" type="radio" value="2" <? if ($gp=="2") echo "checked";?> onClick="window.location.href='KH_list_X.php?gp=2'">
        以后联系
        <input name="select" type="radio" value="5" <? if ($gp=="5") echo "checked";?> onClick="window.location.href='KH_list_X.php?gp=5'">
        成交客户
        　　
        <input name="finds" type="text" value="<? echo substr(substr($_SESSION["FINDSTR"],0,-3),strrpos(substr($_SESSION["FINDSTR"],0,-3),"%")+1)?>"><input name="find" type="button" value="查找" onClick="javascript:window.location.href='?gp=<? echo $gp?>&finds='+actForm.finds.value;"> 
		　成熟度：<select name="khcsd" id="khcsd" onChange="window.location.href='?khcsd='+this.options[this.selectedIndex].value+'&sshy='+document.getElementById('sshy').options[document.getElementById('sshy').selectedIndex].value;">
  <option value="%" <? if ($_SESSION["FINDKHCSD"]=="%") echo "selected";?>>全部</option>
  <option value="10%" <? if ($_SESSION["FINDKHCSD"]=="10%") echo "selected";?>>10%</option>
  <option value="30%" <? if ($_SESSION["FINDKHCSD"]=="30%") echo "selected";?>>30%</option>
  <option value="50%" <? if ($_SESSION["FINDKHCSD"]=="50%") echo "selected";?>>50%</option>
  <option value="60%" <? if ($_SESSION["FINDKHCSD"]=="60%") echo "selected";?>>60%</option>
  <option value="90%" <? if ($_SESSION["FINDKHCSD"]=="90%") echo "selected";?>>90%</option>
  <option value="100%" <? if ($_SESSION["FINDKHCSD"]=="100%") echo "selected";?>>100%</option>
</select>
　行业：<select name="sshy" id="sshy" onChange="window.location.href='?sshy='+this.options[this.selectedIndex].value+'&khcsd='+document.getElementById('khcsd').options[document.getElementById('khcsd').selectedIndex].value;"> 
  <option value="%" <? if ($_SESSION["FINDSSHY"]=="%") echo "selected";?>>全部</option>
  <option value="金融" <? if ($_SESSION["FINDSSHY"]=="金融") echo "selected";?>>金融</option>
  <option value="保险" <? if ($_SESSION["FINDSSHY"]=="保险") echo "selected";?>>保险</option>
  <option value="IT" <? if ($_SESSION["FINDSSHY"]=="IT") echo "selected";?>>IT</option>
  <option value="通讯" <? if ($_SESSION["FINDSSHY"]=="通讯") echo "selected";?>>通讯</option>
  <option value="批发零售" <? if ($_SESSION["FINDSSHY"]=="批发零售") echo "selected";?>>批发零售</option>
  <option value="中介" <? if ($_SESSION["FINDSSHY"]=="中介") echo "selected";?>>中介</option>
  <option value="其他" <? if ($_SESSION["FINDSSHY"]=="其他") echo "selected";?>>其他</option>
</select>
　
<input name="find2" type="button" value="联系日志" onClick="javascript:window.location.href='KH_contact_log.php';">

<? if ($gp=="5") {
	 $rsdq=mysql_query("select shareto from crm_callconfig where bh='".$_SESSION["YKOAUSER"]."'",$conn);?>
<br><strong>我的成交客户分享给：</strong><input type="text" name="wjdq" id="wjdq" value="<? echo mysql_result($rsdq,0,0);?>">
<input name="ok" type="button" value="确定" onClick="javascript:window.location.href='?gp=5&wjdq='+actForm.wjdq.value;">
<select name="sf" id="sf" onChange="if (actForm.wjdq.value=='') actForm.wjdq.value=this.options[this.selectedIndex].value; else actForm.wjdq.value=actForm.wjdq.value+';'+this.options[this.selectedIndex].value;">
 <option></option>
 <? $rsxs=mysql_query("select bh,xm from crm_callconfig where qx='100' and team like 'X%' and bh<>'".$_SESSION["YKOAUSER"]."'",$conn);
 for ($i=0;$i<mysql_num_rows($rsxs);$i++) {?>
 <option value="<? echo mysql_result($rsxs,$i,1);?>"><? echo mysql_result($rsxs,$i,0),"/",mysql_result($rsxs,$i,1);?></option>
 <? }?>
</select>

<? }
 if ($gp=="" or $gp=="1") {?>
<div style="margin:5px">
<font color=#00FF00>█</font><a href='KH_list_X.php?gp=1&lx=gc'>正常联系</a>(<span id=gc></span>) <font color=#FFFF00>█</font><a href='KH_list_X.php?gp=1&lx=nc'>到期后未联系</a>(<span id=nc></span>) <font color=#FF0000>█</font><a href='KH_list_X.php?gp=1&lx=xc'>转意向即将到三个月</a>(<span id=xc></span>)
　　　提醒：<font color=#FFFF00>█</font>超过一周将自动转入客户池 <font color=#FF0000>█</font>到三个月将自动转入客户池，请尽快处理。
</div>
<? } ?>
        <DIV ID=MainArea>
			  <TABLE WIDTH=100% BORDER=0 CELLSPACING=0 CELLPADDING=0 CLASS=TableStyle>
					<TR ALIGN=center VALIGN=middle ID=TableTitle>
						<TD WIDTH=26 STYLE="border-left: 0px solid #000;">&nbsp;</TD>
						<TD WIDTH=35>序号</TD>
						<TD WIDTH=100>客户名称</TD>
						<TD WIDTH=45>联系人</TD>
						<TD WIDTH=50>所属行业</TD>
						<TD WIDTH=60>所属地区</TD>
						<TD WIDTH=50>客户成熟度</TD>
						<TD WIDTH=50>已联系次数</TD>
						<TD WIDTH=80>客户备注</TD>
						<TD WIDTH=60>最近联系</TD>
						<TD WIDTH=60>下次联系</TD>
						<TD WIDTH=100>联系情况</TD>
					</TR>
			<tbody ID=TableData>
            <? 
			for($i=$page_f;$i<$page_e;$i++){
			$rsk=mysql_query("select group_concat(content order by czsj desc separator ';'),max(czsj) from crm_khb_contact where khbid=".mysql_result($rs,$i,"id"),$conn);
			$yikazh=substr(mysql_result($rs,$i,"yikayin_zh"),0,strpos(mysql_result($rs,$i,"yikayin_zh")."^","^"));
			$yikaxt=substr(mysql_result($rs,$i,"yikayin_zh"),strpos(mysql_result($rs,$i,"yikayin_zh")."^","^")+1);?>
					<tr onMouseOver="this.style.backgroundColor='#FFD'" onMouseOut="this.style.backgroundColor='white'"  class="TableDetail2" id="d0">
							<TD  ALIGN=CENTER STYLE="padding:0px;"><input  type="checkbox" value="<? echo mysql_result($rs,$i,"id");?>" name="checkBox[]" /></TD>
							<TD ><? if (strpos("_".mysql_result($rs,$i,"xsry"),$_SESSION["YKOAUSER"])==1) echo $i +1; else echo "<font color=#9900FF title='分享自:".mysql_result($rs,$i,"xsry")."'>",$i+1,"█<font>"?></TD>
							<TD style="width:100px;">
							<? if ($gp=='5') {?>
                            <a href="http://www.yikayin.com/pmc/checklogin.php?bs=<? echo urlencode(iconv("utf-8","gbk",$yikazh))?>&ks=<? echo md5(iconv("utf-8","gbk","hzyk".$yikazh."winner"))?>&sys=<? echo $yikaxt;?>" target="_new">[系统]</a><? if ($yikaxt=="") echo "[<a href='#' class='nav' onClick='javascript:window.open(\"http://mp.yikayin.com/YK_kf_user.php?YIKAZH=".base_encode($yikazh)."\", \"HT_dhdj\", \"toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=yes,width=850,height=800,left=580,top=100\")'>详情</a>]"; }
							if (substr(mysql_result($rs,$i,"sqycly"),0,2)=="20") 
								echo "<span title='".mysql_result($rs,$i,"sqycly")."'>{申请延长审批中}</span>";
							elseif (mb_substr(mysql_result($rs,$i,"sqycly"),0,2,"utf-8")=="不同") 
								echo "<span title='".mysql_result($rs,$i,"sqycly")."'>{申请审批不同意}</span>";
							else
							if (($gp=="" or $gp=="1") and mysql_result($rs,$i,"xc")>85) {?>
                            <a href="#" onClick="window.open('KH_ycsq.php?khmc=<? echo base_encode(mysql_result($rs,$i,"khmc"))?>', 'selectorPersonWin', 'dependent,toolbar=no,location=no,status=no,menubar=no,resizable=no,scrollbars=no,width=520px,height=300px,left=200,top=100');">[申请延长3个月]</a><? }?><br>
							<a href="KH_add.php?id=<? echo mysql_result($rs,$i,0);?>">
		<? if ($gp=="" or $gp=="1") { if (mysql_result($rs,$i,"nc")>0) {echo "<font color=#FFFF00>█</font>";} if (mysql_result($rs,$i,"xc")>85) {echo "<font color=#FF0000>█</font>";}  if (mysql_result($rs,$i,"nc")<=0 and mysql_result($rs,$i,"xc")<86) {echo "<font color=#00FF00>█</font>";} }
			echo "<font color=#000000>",mysql_result($rs,$i,"khmc"),"</font>";
			if ($gp=="5" and mysql_result($rs,$i,"dds")<1) echo "<font color=red> 未下单</font>";?>
                            </a></TD>
							<TD style="width:50px;"><? echo mysql_result($rs,$i,"lxr");?></TD>
							<TD ><? echo mysql_result($rs,$i,"sshy");?></TD>
							<TD ><? echo mysql_result($rs,$i,"province"),mysql_result($rs,$i,"city");?></TD>
							<TD ><? echo mysql_result($rs,$i,"khcsd");?></TD>
							<TD ><? echo mysql_result($rs,$i,"lxcs");?></TD>
							<TD style="width:80px; font-size:10px" title=<? echo mb_substr(mysql_result($rs,$i,"memo"),0,80,"utf-8");?>><? echo mb_substr(mysql_result($rs,$i,"memo"),0,12,"utf-8");?>...</TD>
                            <TD style="width:70px"><? echo mysql_result($rsk,0,1)?></TD>
						  	<TD style="width:70px"><? echo mysql_result($rs,$i,"nextlx")?></TD>
						  	<TD style="width:500px;font-size:10px" onMouseOver="this.style.fontSize='14px';" onMouseOut="this.style.fontSize='10px';"><? echo mysql_result($rsk,0,0)?></TD>
			  </tr>
                        <? }?>
            </tbody>
	  </TABLE>
			<DIV ID=TableTail>
				
			</DIV>
			
<DIV STYLE="width:87%; float:right;" align="right"><A <? if ($page_t>1 and $page_no>1) {echo "href=".$_SERVER["PHP_SELF"]."?pno=1&gp=".$_GET["gp"]."&lx=".$_GET["lx"];} else {echo "disabled style='color:gray;'";};?>>首页</A>　<A <? if ($page_t>1 and $page_no>1) {echo "href=".$_SERVER["PHP_SELF"]."?pno=".($page_no-1)."&gp=".$_GET["gp"]."&lx=".$_GET["lx"];} else {echo "disabled style='color:gray;'";};?>>上一页</A>　<A <? if ($page_t>1 and $page_no<$page_t) {echo "href=".$_SERVER["PHP_SELF"]."?pno=".($page_no+1)."&gp=".$_GET["gp"]."&lx=".$_GET["lx"];} else {echo "disabled style='color:gray;'";};?>>下一页</A>　<A <? if ($page_t>1 and $page_no<>$page_t) {echo "href=".$_SERVER["PHP_SELF"]."?pno=".$page_t."&gp=".$_GET["gp"]."&lx=".$_GET["lx"];} else {echo "disabled style='color:gray;'";};?>>尾页</A>　
    <INPUT name="pno" onKeyDown="" value="<? echo $page_no?>" size="3">
    <INPUT name="ZKPager1" type="button" class="menubutton" value="转到" onClick="javascript:window.location.href='<? echo $_SERVER["PHP_SELF"]?>?pno='+document.actForm.pno.value+'&gp=<? echo $_GET["gp"] ?>'">　
    第<? echo $page_no."/".$page_t?>页&nbsp;&nbsp;&nbsp;&nbsp;</DIV>
    
	</DIV>
	</form>
</div> 
</body>
</HTML>
<? if ($gp=="" or $gp=="1") {?>
<script language="javascript">
document.getElementById("gc").innerHTML=<? echo $gc;?>;
document.getElementById("xc").innerHTML=<? echo $xc;?>;
document.getElementById("nc").innerHTML=<? echo $nc;?>;
</script>
<? }?>