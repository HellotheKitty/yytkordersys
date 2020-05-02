<? require("../inc/conn.php"); 
session_start();
if ($_SESSION["YKOAOK"]<>"OK") { 
echo "<script language=JavaScript>{window.location.href='error.php';}</script>";
exit; }?>
<? 
if ($_GET["team"]<>"") {
	$_SESSION["CRM_TEAM"]=$_GET["team"];$_SESSION["CRM_QX"]="000";
}
if ($_GET["finds"]!==null) $_SESSION["FINDSTR"]=" and (crm_khb.khmc like '%".$_GET["finds"]."%' or crm_khb.lxr like '%".$_GET["finds"]."%')";
if ($_GET["khcsd"]!==null) {$_SESSION["FINDKHCSD"]=$_GET["khcsd"];$_SESSION["FINDSSHY"]=$_GET["sshy"];}
if ($_GET["wjdq"]!==null) mysql_query("update crm_teamdq set dq='".$_GET["wjdq"]."' where team='".$_SESSION["CRM_TEAM"]."'",$conn);

//sale
$gp=$_GET["gp"];if ($gp=="") $gp="%";
if ($gp=="delete") {   //删除客户
	$rs=mysql_query("select crm_khb.*,count(crm_khb_contact.id) lxcs from crm_khb left join crm_khb_contact on crm_khb.id=crm_khb_contact.khbid where instr((select group_concat(bh,';') from crm_callconfig where team='".$_SESSION["CRM_TEAM"]."'),left(xsry,instr(xsry,'/')-1))>0 and (state=-100) group by crm_khb.id order by xsryfpsj desc", $conn); 
} elseif ($gp=="sp") {
	$rs=mysql_query("select crm_khb.*,count(crm_khb_contact.id) lxcs from crm_khb left join crm_khb_contact on crm_khb.id=crm_khb_contact.khbid where instr((select group_concat(bh,';') from crm_callconfig where team='".$_SESSION["CRM_TEAM"]."'),left(xsry,instr(xsry,'/')-1))>0 and (state=-2) and substr(sqycly,1,2)='20' group by crm_khb.id order by xsryfpsj desc", $conn); 
} else {
if ($_SESSION["FINDKHCSD"]=="%" or $_SESSION["FINDKHCSD"]=="")
	$rs=mysql_query("select crm_khb.*,count(crm_khb_contact.id) lxcs from crm_khb left join crm_khb_contact on crm_khb.id=crm_khb_contact.khbid where left(xsry,instr(xsry,'/')-1) like '".$gp."' and instr((select group_concat(bh,';') from crm_callconfig where team='".$_SESSION["CRM_TEAM"]."'),left(xsry,instr(xsry,'/')-1))>0 and not xsryfpsj is null and (state=-2 or state>0) ".$_SESSION["FINDSTR"]." and (sshy like '".$_SESSION["FINDSSHY"]."%' or sshy is null) group by crm_khb.id order by xsryfpsj desc", $conn); 
else 
	$rs=mysql_query("select crm_khb.*,count(crm_khb_contact.id) lxcs from crm_khb left join crm_khb_contact on crm_khb.id=crm_khb_contact.khbid where left(xsry,instr(xsry,'/')-1) like '".$gp."' and instr((select group_concat(bh,';') from crm_callconfig where team='".$_SESSION["CRM_TEAM"]."'),left(xsry,instr(xsry,'/')-1))>0 and not xsryfpsj is null and (state=-2 or state>0) ".$_SESSION["FINDSTR"]." and (khcsd = '".$_SESSION["FINDKHCSD"]."' or khcsd is null) and (sshy like '".$_SESSION["FINDSSHY"]."%' or sshy is null) group by crm_khb.id order by xsryfpsj desc", $conn); 
}
//分页
if ($tj<>"") {$page_num=mysql_num_rows($rs)+1;} else {$page_num=15;}     //每页行数
$page_no=$_GET["pno"];     //当前页
if ($page_no=="") {$page_no=1;}
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
<form method="post" id="actForm" name="actForm" action="msg_send_del.php">
<input type="hidden" name="lx" value="<? echo $_GET["lx"];?>" />

		<DIV ID=Title_bar>
			<DIV ID=Title_bar_Head>
				<DIV ID=Title_Head></DIV>
				<DIV ID=Title>
					<img border="0" width="18" height="18" src="../images/title_arrow2.gif" />
					客户管理
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
							  <div onClick="window.location.href='KH_contact_tj.php'" class="Btn">
							    销售业务统计
						      </div>
 
</LI>
					  <LI CLASS=line></LI>
						
					</ul>
				</DIV>
			</DIV>
		</DIV>
        所属销售：
        <select name="gp" id="gp" onChange="window.location.href='?gp='+this.options[this.selectedIndex].value;">
          <option value="%" >全部</option>
          <? $rsxs=mysql_query("select bh,xm from crm_callconfig where team='".$_SESSION["CRM_TEAM"]."' and qx='100'",$conn);
		  for ($i=0;$i<mysql_num_rows($rsxs);$i++) {?>
          <option value="<? echo mysql_result($rsxs,$i,0);?>" <? if (mysql_result($rsxs,$i,0)==$gp) echo "selected";?>><? echo mysql_result($rsxs,$i,1)?></option>
          <? }?>
          <option value="sp" <? if ($gp=="sp") echo "selected";?>>待审批客户</option>
          <option value="delete" <? if ($gp=="delete") echo "selected";?>>被删除客户</option>
        </select>
总记录数:<? echo mysql_num_rows($rs)?>　　
<input name="finds" type="text" value="<? echo substr(substr($_SESSION["FINDSTR"],0,-3),strrpos(substr($_SESSION["FINDSTR"],0,-3),"%")+1)?>"><input name="find" type="button" value="查找" onClick="javascript:window.location.href='?finds='+actForm.finds.value;"> 
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
<? $rsdq=mysql_query("select dq from crm_teamdq where team='".$_SESSION["CRM_TEAM"]."'",$conn);?>
<strong>本组挖掘客户地域：</strong><input type="text" name="wjdq" id="wjdq" value="<? echo mysql_result($rsdq,0,0);?>">
<input name="ok" type="button" value="确定" onClick="javascript:window.location.href='?wjdq='+actForm.wjdq.value;">
<select name="sf" id="sf" onChange="if (actForm.wjdq.value=='') actForm.wjdq.value=this.options[this.selectedIndex].value; else actForm.wjdq.value=actForm.wjdq.value+';'+this.options[this.selectedIndex].value;">
 <option></option><option>浙江</option><option>福建</option><option>江西</option><option>湖北</option><option>上海</option><option>江苏</option><option>安徽</option><option>北京</option><option>河北</option><option>山东</option><option>四川</option><option>重庆</option><option>辽宁</option><option>河南</option><option>河北</option><option>黑龙江</option><option>天津</option><option>山西</option><option>陕西</option><option>甘肃</option><option>内蒙古</option><option>青海</option><option>新疆</option><option>西藏</option><option>宁夏</option><option>吉林</option><option>广东</option><option>广西</option><option>湖南</option><option>海南</option><option>云南</option><option>贵州</option><option>香港</option><option>澳门</option><option>台湾</option>
</select>
<DIV ID=MainArea>
				<TABLE WIDTH=100% BORDER=0 CELLSPACING=0 CELLPADDING=0 CLASS=TableStyle>
					<TR ALIGN=center VALIGN=middle ID=TableTitle>
						<TD WIDTH=26 STYLE="border-left: 0px solid #000;">&nbsp;</TD>
						<TD WIDTH=25>序号</TD>
						<TD WIDTH=100>客户名称</TD>
						<TD WIDTH=45>联系人</TD>
						<TD WIDTH=50>所属行业</TD>
						<TD WIDTH=60>所属地区</TD>
						<TD WIDTH=50>客户成熟度</TD>
						<TD WIDTH=50>已联系次数</TD>
						<TD WIDTH=80>客户备注</TD>
						<TD WIDTH=60>最近联系</TD>
						<TD WIDTH=60>下次联系</TD>
						<TD style="width:100px">联系情况</TD>
					</TR>
			<tbody ID=TableData>
            <? for($i=$page_f;$i<$page_e;$i++){  
			$rsk=mysql_query("select group_concat(content order by czsj desc separator ';'),max(czsj) from crm_khb_contact where khbid=".mysql_result($rs,$i,"id"),$conn);?>
					<tr onMouseOver="this.style.backgroundColor='#FFD'" onMouseOut="this.style.backgroundColor='white'"  class="TableDetail2" id="d0">
							<TD  ALIGN=CENTER STYLE="padding:0px;"><input  type="checkbox" value="" name="checkBox[]" /></TD>
							<TD ><? echo $i +1;?></TD>
							<TD style="cursor:pointer;width:100px" >
                      <a href='KH_add.php?id=<? echo mysql_result($rs,$i,0);?>'><? echo mysql_result($rs,$i,"nextlx")>date('Y-m-d')?mysql_result($rs,$i,"khmc"):((mysql_result($rs,$i,"state")<0 and mysql_result($rs,$i,"state")>-100)?"<font color='#0000FF'>".mysql_result($rs,$i,"khmc")."</font>":mysql_result($rs,$i,"khmc"));?></a> <? if ($gp=="sp") {?><a href="#" onClick="window.open('KH_ycsq.php?khmc=<? echo base_encode(mysql_result($rs,$i,"khmc"))?>&lx=sp', 'selectorPersonWin', 'dependent,toolbar=no,location=no,status=no,menubar=no,resizable=no,scrollbars=no,width=520px,height=300px,left=200,top=100');" title="<? echo mysql_result($rs,$i,"sqycly");?>">[审批]</a><? }?></TD>
							<TD style="width:50px;"><? echo mysql_result($rs,$i,"lxr");?></TD>
							<TD ><? echo mysql_result($rs,$i,"sshy");?></TD>
							<TD ><? echo mysql_result($rs,$i,"province"),mysql_result($rs,$i,"city");?></TD>
							<TD ><? echo mysql_result($rs,$i,"khcsd");?></TD>
							<TD ><? echo mysql_result($rs,$i,"lxcs");?></TD>
							<TD style="width:80px;" title=<? echo mb_substr(mysql_result($rs,$i,"memo"),0,80,"utf-8");?>><? echo mb_substr(mysql_result($rs,$i,"memo"),0,12,"utf-8");?>...</TD>
						  	<TD style="width:70px"><? echo mysql_result($rsk,0,1)?></TD>
						  	<TD style="width:70px"><? echo mysql_result($rs,$i,"nextlx")?></TD>
						  	<TD style="width:500px;font-size:10px" onMouseOver="this.style.fontSize='14px';" onMouseOut="this.style.fontSize='10px';"><? echo mysql_result($rsk,0,0)?></TD>
			  </tr>
                        <? }?>
            </tbody>
	  </TABLE>
			<DIV ID=TableTail>
			<font color='#0000FF'>蓝色</font>客户需要及时联系，请尽快沟通联系。	
			</DIV>
			
<DIV STYLE="width:87%; float:right;" align="right"><A <? if ($page_t>1 and $page_no>1) {echo "href=".$_SERVER["PHP_SELF"]."?pno=1&gp=".$_GET["gp"];} else {echo "disabled style='color:gray;'";};?>>首页</A>　<A <? if ($page_t>1 and $page_no>1) {echo "href=".$_SERVER["PHP_SELF"]."?pno=".($page_no-1)."&gp=".$_GET["gp"];} else {echo "disabled style='color:gray;'";};?>>上一页</A>　<A <? if ($page_t>1 and $page_no<$page_t) {echo "href=".$_SERVER["PHP_SELF"]."?pno=".($page_no+1)."&gp=".$_GET["gp"];} else {echo "disabled style='color:gray;'";};?>>下一页</A>　<A <? if ($page_t>1 and $page_no<>$page_t) {echo "href=".$_SERVER["PHP_SELF"]."?pno=".$page_t."&gp=".$_GET["gp"];} else {echo "disabled style='color:gray;'";};?>>尾页</A>　
    <INPUT name="pno" onKeyDown="" value="<? echo $page_no?>" size="3">
    <INPUT name="ZKPager1" type="button" class="menubutton" value="转到" onClick="javascript:window.location.href='<? echo $_SERVER["PHP_SELF"]?>?pno='+document.actForm.pno.value+'&gp=<? echo $_GET["gp"] ?>'">　
    第<? echo $page_no."/".$page_t?>页&nbsp;&nbsp;&nbsp;&nbsp;</DIV>
    
	</DIV>
	</form>
</div> 
</body>
</HTML>