<? 
session_start();
require("inc/conn.php"); 
?>
<HTML>
<HEAD>
    <TITLE>易卡工坊--信息录入</TITLE>
    <META content="MSHTML 6.00.2800.1276" name=GENERATOR>
    <META http-equiv=Content-Type content="text/html; charset=utf-8">
    <META http-equiv=Content-Style-Type content=text/css>
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<meta name="viewport" content="width=device-width,minimum-scale=1.0"/>

<LINK href="css/mainWin.css" type=text/css  media=screen rel=stylesheet>
<LINK href="css/mainWin2.css" type=text/css  media=screen rel=stylesheet>
<script src="jsp/jquery-1.3.2.min.js" type="text/javascript"></script>
	
    <base target="_self">
</HEAD>
<? 
if ($_POST["khmc"]<>"") {
	if ($_POST["id"]>0) {
		$id=$_POST["id"];
		mysql_query("update yikaoa.crm_khb set khmc='".$_POST["khmc"]."',lxr='".$_POST["lxr"]."',lxrzw='".$_POST["lxrzw"]."',lxrmobile='".$_POST["lxrmobile"]."',lxdh='".$_POST["lxdh"]."',lxqq='".$_POST["lxqq"]."',lxfax='".$_POST["lxfax"]."',frdb='".$_POST["frdb"]."',yzbm='".$_POST["yzbm"]."',lxdz='".$_POST["lxdz"]."',khygs='".$_POST["khygs"]."',province='".$_POST["province"]."',city='".$_POST["city"]."',fzjg='".$_POST["fzjg"]."',sshy='".$_POST["sshy"]."',zczb='".$_POST["zczb"]."',zyyw='".$_POST["zyyw"]."',memo='".$_POST["memo"]."',lxemail='".$_POST["lxemail"]."',tag='".$_POST["tag"]."' where id=".$id, $conn);
		if ($_POST["xsry"]<>"")
			mysql_query("update yikaoa.crm_khb set state=-2,xsry='".$_POST["xsry"]."',xsryfpsj=now() where id=".$id, $conn);
		if ($_POST["jzy"]=="1") {
			mysql_query("update yikaoa.crm_khb set zyrequesttime=now() where id=".$id, $conn);
		} 
	} 
	echo "<script>alert('保存完成!');window.location.href='?id=$id'; </script>";
	exit;
}

if ($_GET["id"]<>"") {
	$rs=mysql_query("select * from yikaoa.crm_khb where id=".$_GET["id"],$conn);
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
	$lxqq=mysql_result($rs,0,"lxqq");
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
function khcsdc(obj) {
	var vv=obj.options[obj.selectedIndex].value;
	lxxx.xclx.value=parseInt(vv.substr(0,2));
	lxxx.lxnr.value+=vv.substr(2);
}
</script>
<body marginwidth="0" topmargin="0" leftmargin="0" marginheight="0">
<div class="mainbackground">
<form method="post" id="actForm" name="actForm" action="" onSubmit="">
<input type="hidden" name="id" value="<? echo $id;?>" />
		<DIV ID=Title_bar>
			<DIV ID=Title_bar_Head>
				<DIV ID=Title_Head></DIV>
				<DIV ID=Title>
					<img border="0" width="18" height="18" src="../images/title_arrow2.gif" />
					客户信息
				</DIV>
				<DIV ID=Title_End></DIV>
				<DIV ID=Title_bar_bg></DIV>
			</DIV>
			
		</DIV>
		
		<DIV ID=MainArea>
		
			<DIV CLASS=ItemBlock_Title>
				<img border="0" src="../images/item_point.gif" />
			基本信息　　　</DIV>
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
                    <? $rsbz=mysql_query("select tag from yikaoa.crm_teamdq where team='".$_SESSION["CRM_TEAM"]."'",$conn);
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
							<TD><input onFocus="this.select();" maxlength="100" class="InputStyle" style="width: 300px;" type="text" name="lxrmobile" value="<? echo $lxrmobile;?>" /></TD>
						</TR>
						<TR>
							<TD HEIGHT=27></TD>
							<TD>电话号</TD>
							<TD><input onFocus="this.select();"  maxlength="100" class="InputStyle" style="width: 300px;" type="text" name="lxdh" value="<? echo $lxdh;?>" /></TD>
						</TR>
						<TR>
							<TD HEIGHT=27></TD>
							<TD>QQ号</TD>
							<TD><input onFocus="this.select();" maxlength="100" class="InputStyle" style="width: 300px;" type="text" name="lxqq" value="<? echo $lxqq;?>" /></TD>
						</TR>
						<TR>
							<TD HEIGHT=27></TD>
							<TD>Email</TD>
							<TD><input onFocus="this.select();" maxlength="100" class="InputStyle" style="width: 300px;" type="text" name="lxemail" value="<? echo $lxemail;?>" /></TD>
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
							<TD>传真号</TD>
							<TD><input onFocus="this.select();" maxlength="100" class="InputStyle" style="width: 300px;" type="text" name="lxfax" value="<? echo $lxfax;?>" /></TD>
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
                       
						<TR>
							<TD width="50"></TD>
							<TD width="80">所属销售</TD>
							<TD HEIGHT=27 ><select name="xsry" id="xsry">
                            <option value=""></option>
          <? $rsxs=mysql_query("select bh,xm,team from yikaoa.crm_callconfig where team like 'X%' and qx='100' order by team,bh",$conn);
		  for ($i=0;$i<mysql_num_rows($rsxs);$i++) {?>
          <option value="<? echo mysql_result($rsxs,$i,0)."/".mysql_result($rsxs,$i,1);?>" <? if (mysql_result($rsxs,$i,0)."/".mysql_result($rsxs,$i,1)==$xsry or mysql_result($rsxs,$i,0)."/"==$xsry) echo "selected";?>><? echo mysql_result($rsxs,$i,2),mysql_result($rsxs,$i,1)?></option>
          <? }?>
        </select>
							客户有意向，需要跟进，则选择销售，本客户成为他的意向客户。
        </TD>
						</TR>
                       <TR>
							<TD width="50"></TD>
							<TD width="80">邮寄纸样</TD>
							<TD HEIGHT=27 width="500">
                            <? if (mysql_result($rs,0,"zyrequesttime")=="") {?>
                            <input name="jzy" type="checkbox" id="jzy" value="1">
                            <? } else echo "已申请：",mysql_result($rs,0,"zyrequesttime"),",寄出：",mysql_result($rs,0,"zyposttime");?>
						    </TD>
					  </TR>
                       <TR>
							<TD width="50"></TD>
							<TD width="80">&nbsp;</TD>
							<TD HEIGHT=27 width="500">
                            <input name="zxx7" type="button" value="新建名片账号" onClick="window.open('newUser.php?uu=<? echo $_SESSION["YKOAUSER"]?>&cks=<? echo md5("hzyk".$_SESSION["YKOAUSER"]."winner")?>&khmc=<? echo urlencode($khmc)?>&lxr=<? echo urlencode($lxr)?>&mobile=<? echo $lxrmobile?>&lxdh=<? echo $lxdh;?>&lxdz=<? echo urlencode($lxdz)?>','HT_dhdj', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=yes,width=900,height=700,left=300,top=100'); return false">

<input name="zxx9" type="button" value="新建非标订单" onClick="window.open('http://60.191.88.122/nc_erp/jcsj/NS_new.php?lx=1&oabh=<? echo $_SESSION["YKOAUSER"]?>&khmc=<? echo base_encode($khmc);?>&lxr=<? echo base_encode($lxr);?>&lxrdh=<? echo base_encode($lxrmobile);?>&lxdz=<? echo base_encode($lxdz);?>', 'OrderDetail', 'height=600px,width=920px,top=100px,left=200px,location=no,menubar=no,resizable=no,scrollbars=yes,status=no,titlebar=no,toolbar=no,directories=no'); return false">

<input name="zxx7" type="button" value="新建文印店账号" onClick="window.open('http://www.yikaba.cn/yikaba/createUser.php?uu=<? echo $_SESSION["YKOAUSER"]?>&cks=<? echo md5("hzyk".$_SESSION["YKOAUSER"]."winner")?>&khmc=<? echo urlencode($khmc)?>&lxr=<? echo urlencode($lxr)?>&mobile=<? echo $lxrmobile?>&lxdh=<? echo $lxdh;?>&lxdz=<? echo urlencode($lxdz)?>','HT_dhdj', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=yes,width=900,height=700,left=300,top=100'); return false">
						</TD>
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
<input type="button" value="保 存" onClick="if (actForm.khmc.value!='') {actForm.submit();} else alert('客户名称请输入！');" class="FuncBtn" /></TD>
								<TD> 
							</TD>
							</TR>
						</TABLE>
					</DIV>
				
			</CENTER>
</form>
</div>

</body>
</HTML>
<script>

var CITYS = { "北京": ["北京"], "上海": ["上海"], "天津": ["天津", "塘沽"], "重庆": ["重庆", "涪陵", "江津", "巫山"], "河北": ["石家庄", "张家口", "承德", "秦皇岛", "唐山", "廊坊", "保定", "沧州", "衡水", "邢台", "邯郸", "张北", "蔚县", "丰宁", "围场", "怀来", "遵化", "青龙", "坝县", "乐亭", "饶阳", "黄骅", "南宫"], "山西": ["太原", "大同", "朔州", "阳泉", "长治", "晋城", "忻州", "晋中", "临汾", "运城", "吕梁", "右玉", "河曲", "五台山", "五寨", "兴县", "原平", "离石", "榆社", "隰县", "介休", "候马", "阳城"], "内蒙古": ["呼和浩特", "包头", "乌海", "赤峰", "通辽", "呼伦贝尔", "鄂尔多斯", "乌兰察布", "巴彦淖尔", "兴安盟", "锡林郭勒盟", "阿拉善盟", "额尔古纳右旗", "图里河", "满州里", "海拉尔", "小二沟", "新巴尔虎右旗", "新巴尔虎左旗", "博克图", "扎兰屯", "科前旗阿尔山", "索轮", "乌兰浩特", "东乌珠穆沁旗", "额济纳旗", "拐子湖", "巴音毛道", "阿拉善右旗", "二连浩特", "那仁宝力格", "满都拉", "阿巴嘎旗", "苏尼特左旗", "海力素", "朱日和", "乌拉特中旗", "百灵庙", "四子王旗", "化德", "集宁", "吉兰太", "临河", "鄂托克旗", "东胜", "伊金霍洛旗", "阿拉善左旗", "西乌珠穆沁旗", "扎鲁特旗", "巴林左旗", "锡林浩特", "林西", "开鲁", "多伦", "翁牛特旗", "宝国图"], "辽宁": ["沈阳", "朝阳", "阜新", "铁岭", "抚顺", "本溪", "辽阳", "鞍山", "丹东", "大连", "营口", "盘锦", "锦州", "葫芦岛", "彰武", "开原", "清原", "叶柏寿", "新民", "黑山", "章党", "桓仁", "绥中", "兴城", "岫岩", "宽甸", "瓦房店", "庄河"], "吉林": ["长春", "白城", "松原", "吉林", "四平", "辽源", "通化", "白山", "延吉", "乾安", "前郭尔罗斯", "通榆", "长岭", "三岔河", "双辽", "蛟河", "敦化", "汪清", "梅河口", "桦甸", "靖宇", "东岗", "松江", "临江", "集安", "长白"], "黑龙江": ["哈尔滨", "齐齐哈尔", "黑河", "大庆", "伊春", "鹤岗", "佳木斯", "双鸭山", "七台河", "鸡西", "牡丹江", "绥化", "大兴安岭", "漠河", "塔河", "新林", "呼玛", "嫩江", "孙吴", "北安", "克山", "富裕", "海伦", "明水", "富锦", "泰来", "安达", "铁力", "依兰", "宝清", "肇州", "通河", "尚志", "虎林", "绥芬河"], "江苏": ["南京", "徐州", "连云港", "宿迁", "淮安", "盐城", "扬州", "泰州", "南通", "镇江", "常州", "无锡", "苏州", "赣榆", "盱眙", "淮阴", "射阳", "高邮", "东台", "吕泗", "溧阳", "吴县东山"], "浙江": ["杭州", "湖州", "嘉兴", "舟山", "宁波", "绍兴", "衢州", "金华", "台州", "温州", "丽水", "平湖", "慈溪", "嵊泗", "定海", "嵊县", "鄞县", "龙泉", "洪家", "玉环"], "安徽": ["合肥", "宿州", "淮北", "阜阳", "亳州", "蚌埠", "淮南", "滁州", "马鞍山", "芜湖", "铜陵", "安庆", "黄山", "六安", "巢湖", "池州", "宣城", "砀山", "宿县", "寿县", "霍山", "桐城", "芜湖县", "宁国", "屯溪"], "福建": ["福州", "南平", "三明", "莆田", "泉州", "厦门", "漳州", "龙岩", "宁德", "邵武", "武夷山市", "浦城", "建瓯", "福鼎", "泰宁", "长汀", "上杭", "永安", "屏南", "平潭", "崇武", "东山"], "江西": ["南昌", "九江", "景德镇", "鹰潭", "新余", "萍乡", "赣州", "上饶", "抚州", "宜春", "吉安", "修水", "宁冈", "遂川", "庐山", "波阳", "樟树", "贵溪", "玉山", "南城", "广昌", "寻乌"], "山东": ["济南", "聊城", "德州", "东营", "淄博", "潍坊", "烟台", "威海", "青岛", "日照", "临沂", "枣庄", "济宁", "泰安", "莱芜", "滨州", "菏泽", "惠民县", "羊角沟", "长岛", "龙口", "成山头", "朝城", "泰山", "沂源", "莱阳", "海阳", "石岛", "兖州", "莒县"], "河南": ["郑州", "三门峡", "洛阳", "焦作", "新乡", "鹤壁", "安阳", "濮阳", "开封", "商丘", "许昌", "漯河", "平顶山", "南阳", "信阳", "周口", "驻马店", "济源", "卢氏", "孟津", "栾川", "西峡", "宝丰", "西华", "固始"], "湖北": ["武汉", "十堰", "襄樊", "荆门", "孝感", "黄冈", "鄂州", "黄石", "咸宁", "荆州", "宜昌", "随州", "仙桃", "天门", "潜江", "神农架", "恩施", "郧西", "房县", "老河口", "枣阳", "巴东", "钟祥", "广水", "麻城", "五峰", "来风", "嘉鱼", "英山"], "湖南": ["长沙", "张家界", "常德", "益阳", "岳阳", "株洲", "湘潭", "衡阳", "郴州", "永州", "邵阳", "怀化", "娄底", "吉首", "桑植", "石门", "南县", "沅陵", "安化", "沅江", "平江", "芷江", "双峰", "南岳", "通道", "武冈", "零陵", "常宁", "道县"], "广东": ["广州", "清远", "韶关", "河源", "梅州", "潮州", "汕头", "揭阳", "汕尾", "惠州", "东莞", "深圳", "珠海", "中山", "江门", "佛山", "肇庆", "云浮", "阳江", "茂名", "湛江", "南雄", "连县", "佛冈", "连平", "广宁", "增城", "五华", "惠来", "南澳", "信宜", "罗定", "台山", "电白", "徐闻"], "广西": ["南宁", "桂林", "柳州", "梧州", "贵港", "玉林", "钦州", "北海", "防城港", "崇左", "百色", "河池", "来宾", "贺州", "融安", "凤山", "都安", "蒙山", "那坡", "靖西", "平果", "桂平", "龙州", "灵山", "东兴", "涠洲岛"], "海南": ["海口", "三亚", "文昌", "琼海", "万宁", "东方", "澄迈", "定安", "儋县", "琼中", "陵水", "西沙", "昌江", "乐东", "白沙", "临高"], "四川": ["成都", "广元", "绵阳", "德阳", "南充", "广安", "遂宁", "内江", "乐山", "自贡", "泸州", "宜宾", "攀枝花", "巴中", "达川", "资阳", "眉山", "雅安", "阿坝", "甘孜", "西昌", "石渠", "若尔盖", "德格", "色达", "道孚", "马尔康", "红原", "小金", "松潘", "都江堰", "平武", "巴塘", "新龙", "理塘", "稻城", "康定", "峨眉山", "木里", "九龙", "越西", "昭觉", "雷波", "盐源", "会理", "万源", "阆中", "奉节", "梁平", "万县市", "叙永", "酉阳"], "贵州": ["贵阳", "六盘水", "遵义", "安顺", "毕节", "铜仁", "凯里", "都匀", "兴义", "威宁", "盘县", "桐梓", "习水", "湄潭", "思南", "黔西", "三穗", "兴仁", "望谟", "罗甸", "独山", "榕江"], "云南": ["昆明", "曲靖", "玉溪", "保山", "昭通", "丽江", "思茅", "临沧", "德宏", "怒江", "迪庆", "大理", "楚雄", "红河", "文山州", "德钦", "贡山", "中甸", "维西", "华坪", "会泽", "腾冲", "元谋", "沾益", "瑞丽", "景东", "泸西", "耿马", "澜沧", "景洪", "元江", "勐腊", "江城", "蒙自", "屏边", "广南", "勐海"], "西藏": ["拉萨", "那曲", "昌都", "林芝", "山南", "日喀则", "阿里", "狮泉河", "改则", "班戈", "安多", "普兰", "申扎", "当雄", "拉孜", "尼木", "泽当", "聂拉木", "定日", "江孜", "错那", "隆子", "帕里", "索县", "丁青", "嘉黎", "洛隆", "波密", "左贡", "察隅"], "陕西": ["西安", "延安", "铜川", "渭南", "咸阳", "宝鸡", "汉中", "榆林", "安康", "商洛", "定边", "吴旗", "横山", "绥德", "长武", "洛川", "武功", "华山", "略阳", "佛坪", "镇安", "石泉"], "甘肃": ["兰州", "嘉峪关", "金昌", "白银", "天水", "武威", "酒泉", "张掖", "庆阳", "安西", "陇南", "临夏", "甘南", "马鬃山", "敦煌", "玉门镇", "金塔", "高台", "山丹", "永昌", "民勤", "景泰", "靖远", "榆中", "临洮", "环县", "平凉", "西峰镇", "玛曲", "夏河合作", "岷县", "定西"], "青海": ["西宁", "海东", "海北", "海南", "黄南", "果洛", "玉树", "海西", "茫崖", "冷湖", "祁连", "大柴旦", "德令哈", "刚察", "门源", "格尔木", "都兰", "共和县", "贵德", "民和", "兴海", "同德", "同仁", "杂多", "曲麻莱", "玛多", "清水河", "达日", "河南", "久治", "囊谦", "班玛"], "宁夏": ["银川", "石嘴山", "吴忠", "固原", "中卫", "惠农", "陶乐", "中宁", "盐池", "海源", "同心", "西吉"], "新疆": ["乌鲁木齐", "克拉玛依", "石河子", "阿拉尔", "喀什", "阿克苏", "和田", "吐鲁番", "哈密", "克孜勒", "博尔塔拉", "昌吉", "库尔勒", "伊犁", "塔城", "阿勒泰", "哈巴河", "吉木乃", "福海", "富蕴", "和布克赛尔", "青河", "阿拉山口", "托里", "北塔山", "温泉", "精河", "乌苏", "蔡家湖", "奇台", "昭苏", "巴仑台", "达板城", "七角井", "库米什", "巴音布鲁克", "焉耆", "拜城", "轮台", "库车", "吐尔尕特", "乌恰", "阿合奇", "巴楚", "柯坪", "铁干里克", "若羌", "塔什库尔干", "莎车", "皮山", "民丰", "且末", "于田", "巴里坤", "伊吾", "伊宁"], "香港": ["香港"], "澳门": ["澳门"], "台湾": ["台北", "台中", "高雄"] };

$(function () {
    creatCity('#province', '#city');
});

function creatCity($province, $city) {
    $province = $($province);
    $city = $($city);
    var province = null,
        provinceHtml = [];
    for (province in CITYS) {
		if (province=="<? echo $province;?>") 
    	    provinceHtml.push('<option selected>' + province + '</option>');
		else 
			provinceHtml.push('<option>' + province + '</option>');
    }
    $province.html(provinceHtml.join('')).change(function () {
        city($(this).val());
    }).trigger('change');

    function city(province) {
        var i = 0,
            citys = CITYS[province],
            city_len = citys.length,
            cityHtml = ['<option> ---- </option>'];
        for (; i < city_len ; i++) {
			if (citys[i]=="<? echo $city;?>") 
            	cityHtml.push('<option selected>' + citys[i] + '</option>');
			else
            	cityHtml.push('<option>' + citys[i] + '</option>');
        }
        $city.html(cityHtml.join(''));
    }
};
</script>