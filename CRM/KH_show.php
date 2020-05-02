<? require("../inc/conn.php"); 
session_start();
if ($_SESSION["YKOAOK"]<>"OK" and $_GET["callid"]=="") { 
echo "<script language=JavaScript>{window.location.href='error.php';}</script>";
exit; }?>
<HTML>
<HEAD>
    <TITLE>易卡工坊--信息录入</TITLE>
    <META content="MSHTML 6.00.2800.1276" name=GENERATOR>
    <META http-equiv=Content-Type content="text/html; charset=utf-8">
    <META http-equiv=Content-Style-Type content=text/css>
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<meta name="viewport" content="width=device-width,minimum-scale=1.0"/>

<LINK href="../css/mainWin.css" type=text/css  media=screen rel=stylesheet>
<LINK href="../css/calendar.css" type=text/css  media=screen rel=stylesheet>
<LINK href="../css/mainWin2.css" type=text/css  media=screen rel=stylesheet>
<script src="../js/jquery-1.3.2.min.js" type="text/javascript"></script>
	
    <base target="_self">
</HEAD>
<? 
if ($_POST["khmc"]<>"") {
	if ($_POST["id"]>0) {
		$id=$_POST["id"];
		mysql_query("update crm_khb set khmc='".$_POST["khmc"]."',lxr='".$_POST["lxr"]."',lxrzw='".$_POST["lxrzw"]."',lxrmobile='".$_POST["lxrmobile"]."',lxdh='".$_POST["lxdh"]."',lxfax='".$_POST["lxfax"]."',frdb='".$_POST["frdb"]."',yzbm='".$_POST["yzbm"]."',lxdz='".$_POST["lxdz"]."',khygs='".$_POST["khygs"]."',province='".$_POST["province"]."',city='".$_POST["city"]."',fzjg='".$_POST["fzjg"]."',sshy='".$_POST["sshy"]."',zczb='".$_POST["zczb"]."',zyyw='".$_POST["zyyw"]."',memo='".$_POST["memo"]."',lxemail='".$_POST["lxemail"]."',tag='".$_POST["tag"]."' where id=".$id, $conn);
		if ($_POST["xsry"]<>"")
			mysql_query("update crm_khb set xsry='".$_POST["xsry"]."',xsryfpsj=now() where id=".$id, $conn);
		if ($_POST["dianhu"]=="1")
			mysql_query("update crm_khb set state='-1' where id=".$id, $conn);
	} else {
	$state= "NULL";
	if (substr($_SESSION["CRM_TEAM"],0,1)=="T") $state="-1";
	if (substr($_SESSION["CRM_TEAM"],0,1)=="X") $state="-2";
	$rs=mysql_query("insert into crm_khb (khmc,lxr,lxrzw,lxrmobile,lxdh,lxfax,lxEmail,lxQQ,frdb,yzbm,lxdz,khygs,datafrom,province,city,fzjg,sshy,zczb,zyyw,memo,khcsd,state,nextlx,tag,".(substr($_SESSION["CRM_TEAM"],0,1)=="D"?"datainput,datainputsj":(substr($_SESSION["CRM_TEAM"],0,1)=="T"?"callout,calloutfpsj":"xsry,xsryfpsj")).") values ('".$_POST["khmc"]."','".$_POST["lxr"]."','".$_POST["lxrzw"]."','".$_POST["lxrmobile"]."','".$_POST["lxdh"]."','".$_POST["lxfax"]."','".$_POST["lxemail"]."',null,'".$_POST["frdb"]."','".$_POST["yzbm"]."','".$_POST["lxdz"]."','".$_POST["khygs"]."','input','".$_POST["province"]."','".$_POST["city"]."','".$_POST["fzjg"]."','".$_POST["sshy"]."','".$_POST["zczb"]."','".$_POST["zyyw"]."','".$_POST["memo"]."',0,$state,now(),'".$_POST["tag"]."','".$_SESSION["YKOAUSER"]."/".$_SESSION["XM"]."',now())", $conn);
	$id=mysql_result(mysql_query("select last_insert_id()",$conn),0,0);
	}
	echo "<script>alert('保存完成!');window.location.href='?id=$id'; </script>";
	exit;
}
if ($_GET["tuihui"]<>"") {
	$id=$_GET["tuihui"];
	mysql_query("update crm_khb set state=-2,xsry=null,xsryfpsj=null where id=".$id, $conn);
	echo "<script>alert('退回操作完成!');window.location.href='?id=$id'; </script>";
	exit;
}

if ($_GET["id"]<>"" or $_GET["callid"]<>"") {
	if ($_GET["id"]<>"") {
		if (substr($_GET["id"],0,4)=="next") {
			$rs=mysql_query("select * from crm_khb where id>".substr($_GET["id"],4)." and left(callout,instr(callout,'/')-1)='".$_SESSION["YKOAUSER"]."' and (nextlx is null or datediff(now(),nextlx)>=0) and (state is null or state=-1) order by id limit 1",$conn);
		} elseif (substr($_GET["id"],0,4)=="befo") {
			$rs=mysql_query("select * from crm_khb where id<".substr($_GET["id"],4)." and left(callout,instr(callout,'/')-1)='".$_SESSION["YKOAUSER"]."' and (nextlx is null or datediff(now(),nextlx)>=0) and (state is null or state=-1) order by id desc limit 1",$conn);
		} else
			$rs=mysql_query("select * from crm_khb where id=".$_GET["id"],$conn);
	} else 
		$rs=mysql_query("select * from crm_khb where instr(lxrmobile,'".$_GET["callid"]."')>0 or instr(lxdh,'".$_GET["callid"]."')>0",$conn);
		
	if (mysql_num_rows($rs)>0) {
	$id=mysql_result($rs,0,"id");
	$khmc=mysql_result($rs,0,"khmc");
	$frdb=mysql_result($rs,0,"frdb");
	$sshy=mysql_result($rs,0,"sshy");
	$province=mysql_result($rs,0,"province");
	$city=mysql_result($rs,0,"city");
	$fzjg=mysql_result($rs,0,"fzjg");
	$khygs=mysql_result($rs,0,"khygs");
	$lxr=mysql_result($rs,0,"lxr");
	$lxrzw=mysql_result($rs,0,"lxrzw");
	$lxrmobile=mysql_result($rs,0,"lxrmobile");
	$lxdh=mysql_result($rs,0,"lxdh");
	$lxfax=mysql_result($rs,0,"lxfax");
	$lxdz=mysql_result($rs,0,"lxdz");
	$yzbm=mysql_result($rs,0,"yzbm");
	$zczb=mysql_result($rs,0,"zczb");
	$zyyw=mysql_result($rs,0,"zyyw");
	$memo=mysql_result($rs,0,"memo");
	$lxemail=mysql_result($rs,0,"lxemail");
	$tag=mysql_result($rs,0,"tag");
	$state=mysql_result($rs,0,"state");
	$khcsd=mysql_result($rs,0,"khcsd");
	$xsry=mysql_result($rs,0,"xsry");
	$zh=mysql_result($rs,0,"yikayin_zh");
	$nextlx=mysql_result($rs,0,"nextlx");
	}
}
if ($id=="") $id=0;
?>

<style>
.black_overlay{
display: none;
position: absolute;
top: 0%;
left: 0%;
width: 100%;
height: 100%;
background-color: black;
z-index:1001;
-moz-opacity: 0.3;
opacity:.30;
filter: alpha(opacity=30);
}
.white_content {
display: none;
position: absolute;
top: 10%;
left: 10%;
width: 80%;
height: 90%;
border: 16px solid lightblue;
background-color: white;
z-index:1002;
overflow: auto;
}
</style>
<script type="text/javascript">
//弹出隐藏层
function ShowDiv(show_div,bg_div){
document.getElementById(show_div).style.display='block';
document.getElementById(bg_div).style.display='block' ;
var bgdiv = document.getElementById(bg_div);
bgdiv.style.width = document.body.scrollWidth;
// bgdiv.style.height = $(document).height();
//$("#"+bgdiv).height($(document).height());
};
//关闭弹出层
function CloseDiv(show_div,bg_div)
{
document.getElementById(show_div).style.display='none';
document.getElementById(bg_div).style.display='none';
};
</script>
<body marginwidth="0" topmargin="0" leftmargin="0" marginheight="0">
<div class="mainbackground">
<form method="post" id="actForm" action="">
<input type="hidden" name="id" value="<? echo $id;?>" />
		<DIV ID=Title_bar>
			<DIV ID=Title_bar_Head>
				<DIV ID=Title_Head></DIV>
				<DIV ID=Title>
					<img border="0" width="18" height="18" src="../images/title_arrow2.gif" />
					信息录入
				</DIV>
				<DIV ID=Title_End></DIV>
				<DIV ID=Title_bar_bg></DIV>
			</DIV>
			<DIV ID=Title_bar_Tail>
				<DIV ID=Title_FuncBar>
					<ul>
						<LI CLASS=line></LI>
						
                            <LI CLASS=title>
 
    <div onClick="window.history.go(-1);" class="Btn">
        返 回
    </div>
 
</LI>
							<LI CLASS=line></LI>
								
					</ul>
				</DIV>
			</DIV>
		</DIV>
		
		<DIV ID=MainArea>
		
			<DIV CLASS=ItemBlock_Title>
				<img border="0" src="../images/item_point.gif" />
				基本信息　　　
		  </DIV>
			<DIV CLASS=ItemBlockBorder>
				<DIV CLASS=ItemBlock>
					<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0>
						<TR>
							<TD WIDTH=50 HEIGHT=27></TD>
							<TD WIDTH=80>客户名称</TD>
							<TD><input onFocus="this.select();" maxlength="100" class="InputStyle" style="width: 300px;" type="text" value="<? echo $khmc;?>" name="khmc" onChange="$.post('KH_find.php', { lx: 'duplicate', khmc: this.value},function(data){ if (data.length>0) alert('重复查找结果: \n' + data); });" /></TD>
						</TR>
						<TR>

							<TD HEIGHT=27></TD>
							<TD>所属行业</TD>
							<TD><input onFocus="this.select();" maxlength="100" class="InputStyle" style="width: 300px;" type="text" name="sshy" value="<? echo $sshy;?>" />
            <a href='void(0)' onclick='javascript:actForm.sshy.value="金融";return false;'>金融</a>            
            <a href='void(0)' onclick='javascript:actForm.sshy.value="保险";return false;'>保险</a>            
            <a href='void(0)' onclick='javascript:actForm.sshy.value="IT";return false;'>IT</a>            
            <a href='void(0)' onclick='javascript:actForm.sshy.value="通讯";return false;'>通讯</a>           
            <a href='void(0)' onclick='javascript:actForm.sshy.value="批发零售";return false;'>批发零售</a>           
            <a href='void(0)' onclick='javascript:actForm.sshy.value="中介";return false;'>中介</a>				
            <a href='void(0)' onclick='javascript:actForm.sshy.value="其他";return false;'>其他</a>				
                			</TD>
						</TR>
						<TR>

							<TD HEIGHT=27></TD>
							<TD>所属地区</TD>
							<TD><select name="province" id="province" style="width:80px"></select>
                            <select name="city" id="city"></select></TD>
						</TR>
						<TR>

							<TD HEIGHT=27></TD>
							<TD>客户标注</TD>
							<TD><input onFocus="this.select();" maxlength="100" class="InputStyle" style="width: 300px;" type="text" name="tag" value="<? echo $tag;?>" />
                    <? $rsbz=mysql_query("select tag from crm_teamdq where team='".$_SESSION["CRM_TEAM"]."'",$conn);
					if (mysql_num_rows($rsbz)>0) {
					$cs=explode(";",mysql_result($rsbz,0,0));
					foreach($cs as $k=>$tag) {
					echo "<a href='void(0)' onclick='javascript:actForm.tag.value=actForm.tag.value+\"{$tag};\";return false;'>{$tag}</a> ";
					}
					}?>
						  </TD>
						</TR>
						
					</TABLE>
				</DIV>
			</DIV>
			<DIV CLASS=ItemBlock_Title>
				<img border="0" src="../images/item_point.gif" />
				联系信息
			</DIV>
			<DIV CLASS=ItemBlockBorder>
				<DIV CLASS=ItemBlock>
					<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0>
						<TR>
							<TD WIDTH=50 HEIGHT=27></TD>
							<TD WIDTH=80>联系人</TD>
							<TD ><input onFocus="this.select();" maxlength="100" class="InputStyle" style="width: 300px;" type="text" value="<? echo $lxr;?>" name="lxr" /></TD>
						</TR>
						<TR>
							<TD HEIGHT=27></TD>
							<TD>职务</TD>
							<TD><input onFocus="this.select();" maxlength="100" class="InputStyle" style="width: 300px;" type="text" name="lxrzw" value="<? echo $lxrzw;?>" />
            <a href='void(0)' onclick='javascript:actForm.lxrzw.value="前台接待";return false;'>前台接待</a>            
            <a href='void(0)' onclick='javascript:actForm.lxrzw.value="人力经理";return false;'>人力经理</a>            
            <a href='void(0)' onclick='javascript:actForm.lxrzw.value="部门经理";return false;'>部门经理</a>            
            <a href='void(0)' onclick='javascript:actForm.lxrzw.value="公司经理";return false;'>公司经理</a>           
                            </TD>
						</TR>
						<TR>
							<TD HEIGHT=27></TD>
							<TD>手机号</TD>
							<TD><input onFocus="this.select();" maxlength="100" class="InputStyle" style="width: 300px;" type="text" name="lxrmobile" value="<? echo $lxrmobile;?>" />
						    <? if (substr($_SESSION["CRM_TEAM"],0,1)!="D") {?><input id="Button1" type="button" value="联系内容" onClick="ShowDiv('MyDiv','fade')" /><? ;if ($nextlx>date("Y-m-d")) echo "<font color=red>下次联系:",$nextlx,"</font>";}?></TD>
						</TR>
						<TR>
							<TD HEIGHT=27></TD>
							<TD>电话号</TD>
							<TD><input onFocus="this.select();"  maxlength="100" class="InputStyle" style="width: 300px;" type="text" name="lxdh" value="<? echo $lxdh;?>" /></TD>
						</TR>
						<TR>
							<TD HEIGHT=27></TD>
							<TD>传真号</TD>
							<TD><input onFocus="this.select();" maxlength="100" class="InputStyle" style="width: 300px;" type="text" name="lxfax" value="<? echo $lxfax;?>" /></TD>
						</TR>
						<TR>
							<TD HEIGHT=27></TD>
							<TD>Email</TD>
							<TD><input onFocus="this.select();" maxlength="100" class="InputStyle" style="width: 300px;" type="text" name="lxemail" value="<? echo $lxemail;?>" /></TD>
						</TR>
					</TABLE>
				</DIV>
			</DIV>
			<DIV CLASS=ItemBlock_Title>
				<img border="0" src="../images/item_point.gif" />
				其它信息
			</DIV>
			<DIV CLASS=ItemBlockBorder>
				<DIV CLASS=ItemBlock>
					<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0>
						<TR>
							<TD HEIGHT=27></TD>
							<TD>法人代表</TD>
							<TD><input onFocus="this.select();" maxlength="100" class="InputStyle" style="width: 300px;" type="text" name="frdb" value="<? echo $frdb;?>" /></TD>
						</TR>
						<TR>

							<TD HEIGHT=27></TD>
							<TD>分支机构</TD>
							<TD><input onFocus="this.select();" maxlength="100" class="InputStyle" style="width: 300px;" type="text" name="fzjg" value="<? echo $fzjg;?>" />
            <a href='void(0)' onclick='javascript:actForm.fzjg.value="跨国性";return false;'>跨国性</a>            
            <a href='void(0)' onclick='javascript:actForm.fzjg.value="全国性";return false;'>全国性</a>            
            <a href='void(0)' onclick='javascript:actForm.fzjg.value="地区性";return false;'>地区性</a>            
            <a href='void(0)' onclick='javascript:actForm.fzjg.value="城市内";return false;'>城市内</a>           
            <a href='void(0)' onclick='javascript:actForm.fzjg.value="没有";return false;'>没有</a>           
                            </TD>
						</TR>
						<TR>

							<TD HEIGHT=27></TD>
							<TD>员工数</TD>
							<TD><input onFocus="this.select();" maxlength="100" class="InputStyle" style="width: 300px;" type="text" name="khygs" value="<? echo $khygs;?>" />
            <a href='void(0)' onclick='javascript:actForm.khygs.value="50人内";return false;'>50内</a>            
            <a href='void(0)' onclick='javascript:actForm.khygs.value="51-100人";return false;'>51-100人</a>            
            <a href='void(0)' onclick='javascript:actForm.khygs.value="101-200人";return false;'>101-200人</a>            
            <a href='void(0)' onclick='javascript:actForm.khygs.value="200人以上";return false;'>200以上</a>           
						  </TD>
						</TR>
						<TR>
							<TD HEIGHT=27></TD>
							<TD>地址</TD>
							<TD><input onFocus="this.select();" maxlength="100" class="InputStyle" style="width: 500px;" type="text" name="lxdz" value="<? echo $lxdz;?>" /></TD>
						</TR>
						<TR>
							<TD HEIGHT=27></TD>
							<TD>邮编</TD>
							<TD><input onFocus="this.select();" maxlength="6" class="InputStyle" style="width: 500px;" type="text" name="yzbm" value="<? echo $yzbm;?>" /></TD>
						</TR>
						<TR>
							<TD HEIGHT=27></TD>
							<TD>注册资本</TD>
							<TD><input onFocus="this.select();" maxlength="100" class="InputStyle" style="width: 500px;" type="text" name="zczb" value="<? echo $zczb;?>" /></TD>
						</TR>
						<TR>
							<TD HEIGHT=27></TD>
							<TD>主营业务</TD>
							<TD><input onFocus="this.select();" maxlength="100" class="InputStyle" style="width: 500px;" type="text" name="zyyw" value="<? echo $zyyw;?>" /></TD>
						</TR>
						<TR>
							<TD width="50"></TD>
							<TD width="80">备注</TD>
							<TD HEIGHT=80 width="500"><textarea class="InputAreaStyle" name="memo"><? echo $memo;?></textarea></TD>
						</TR>
                        
					</TABLE>
				</DIV>
			</DIV>
		
	</CENTER>
</DIV>
				
  <DIV ID=InputDetailBar>
						<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=10 ALIGN=center>
							<TR>
								<TD> 
</TD>
								<TD> 
<div onClick="window.history.go(-1);" class="FuncBtn"><div class=FuncBtnHead></div>
        <div class=FuncBtnMemo>返回</div>
        <div class=FuncBtnTail></div></div></TD>
							</TR>
						</TABLE>
					</DIV>
				
			</CENTER>
</form>
</div>
<? if (substr($_SESSION["CRM_TEAM"],0,1)!="D") {?>
<div id="fade" class="black_overlay">
</div>
<div id="MyDiv" class="white_content">
<div style="text-align: right; cursor: default; height: 20px;">
<span style="font-size: 16px;" onClick="CloseDiv('MyDiv','fade')">关闭</span>
</div>
<form name="lxxx" id="lxxx" action="" method="post" style="margin-top:0px">
<table name="hj" border="0" width="100%"><tr><td width="45%">
<? 
	echo "客户：",$khmc." ".$lxr." ",$lxrmobile," ",$lxdh,"<br>";
?>
</td>
<td>
<input type="hidden" name="kid" value="<? echo date("Ymdhis");?>" />
<input type="hidden" name="id" value="<? echo $id;?>" />
通话/联系内容：<textarea name="lxnr" style="height: 84px;"></textarea>
客户成熟度：<select name="khcsd">
  <option value="0">0</option>
  <option value="10%" <? if ($khcsd=="10%") echo "selected";?>>10%</option>
  <option value="30%" <? if ($khcsd=="30%") echo "selected";?>>30%</option>
  <option value="50%" <? if ($khcsd=="50%") echo "selected";?>>50%</option>
  <option value="60%" <? if ($khcsd=="60%") echo "selected";?>>60%</option>
  <option value="90%" <? if ($khcsd=="90%") echo "selected";?>>90%</option>
  <option value="100%" <? if ($khcsd=="100%") echo "selected";?>>100%</option>
</select>　
<br>
<input name="zxx" type="button" value="保存信息" onClick="if (lxxx.lxnr.value!='') {$.post('KH_saveinfo.php', $('#lxxx').serialize(),function(data){alert('Data Loaded: ' + data);window.location.href='?id=<? echo $id?>';});} else alert('联系内容请输入！');">
<br>email:<input name="lxemail" type="text" value="<? echo $lxemail?>" style="width:220px">
<input name="zxx" type="button" value="发送推广Email" onClick="$.post('Sendemail.php', $('#lxxx').serialize(),function(data){alert('Data Loaded: ' + data);});">
<br><br>
<? if ($zh!="") echo "名片用户账号：",$zh," [<a href='#' class='nav' onClick='javascript:window.open(\"http://mp.yikayin.com/YK_kf_user.php?YIKAZH=".base_encode($zh)."\", \"HT_dhdj\", \"toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=yes,width=850,height=800,left=580,top=100\")'>详情</a>]"; ?>
</td></tr></table>
</form>
<hr>
<table width="100%" border="1">
  <tr>
    <td HEIGHT=25 width="60"><strong>联系人</strong></td>
    <td width="100"><strong>联系时间</strong></td>
    <td><strong>联系内容</strong></td>
  </tr>
<? $rsls=mysql_query("select * from crm_khb_contact where khbid=".$id." order by czsj desc",$conn);
for ($i=0;$i<mysql_num_rows($rsls);$i++) {?>
  <tr>
    <td HEIGHT=25><? echo mysql_result($rsls,$i,"czy");?></td>
    <td><? echo mysql_result($rsls,$i,"czsj");?></td>
    <td><? echo mysql_result($rsls,$i,"content");?></td>
  </tr>
 <? }?>
</table>
</div>
<? }?>
</body>
</HTML>
