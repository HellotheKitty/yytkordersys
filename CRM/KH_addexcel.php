<? require("../inc/conn.php"); 
session_start();
if ($_SESSION["YKOAOK"]<>"OK") { 
echo "<script language=JavaScript>{window.location.href='error.php';}</script>";
exit; }?>

<HTML>
<HEAD>
    <TITLE>易卡工坊--信息导入</TITLE>
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
if ($_FILES['userfile']['name']<>"") {
$uploaddir= '../upload/';//设置上传的文件夹地址 
$FILES_NAME=$_FILES['userfile']['name']; 
$FILES_EXT=array('.xls');//设置允许上传文件的类型 
$MAX_SIZE = 40000000;//设置文件上传文件20000000byte=2M 
$file_ext=substr($FILES_NAME,strrpos($FILES_NAME,"."));
//取出文件后缀名，strrpos()从标记开始前字节个数(不算标记),substr()显示从第strrpos()之后的字符 
if($_FILES['userfile']['size']>$MAX_SIZE){//检查文件大小 
echo "文件大小超程序允许范围！"; 
exit; 
} 

if(in_array($file_ext, $FILES_EXT)){//检查文件类型 
$_FILES['userfile']['name']=date("YmdHis").rand().$_FILES['userfile']['name']; 
$uploadfile = $uploaddir. $_FILES['userfile']['name'];//上传后文件的路径及文件名 
$uploadfile = iconv('utf-8','gb2312',$uploadfile);
if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {//用move函数生成临时文件名，并按照 $_FILES['userfile']['name']上传到$uploaddir下 
require_once '../Excel/reader.php';
$data = new Spreadsheet_Excel_Reader();
$data->setOutputEncoding('utf-8');
$data->read($uploadfile);
error_reporting(E_ALL ^ E_NOTICE);
print $data->sheets[0]['numRows'];

mysql_query("insert into crm_czlog values ('".$_SESSION["USER"]."',now(),'导入$uploadfile，覆盖:".$_POST["chong"]."'",$conn);
$cf=0;$zs=0;
for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
	if ($data->sheets[0]['cells'][$i][1]<>"") {
		$zs++;
		$khmc=$data->sheets[0]['cells'][$i][1];
		//$khmc=str_replace("有限公司","%",$khmc);
		//$khmc=str_replace("股份","%",$khmc);
		//$khmc=str_replace("有限责任公司","%",$khmc);
		//$khmc=str_replace("门市部","%",$khmc);
		//$khmc=str_replace(" ","",$khmc);
		$chong="";
		$rsid=mysql_query("select count(1) from crm_khb where khmc='{$khmc}'",$conn);
		if (mysql_result($rsid,0,0)>0 and $_POST["chong"]=="1") {
			mysql_query("delete from crm_khb where khmc='$khmc'",$conn);
			$chong="1";
		}
		if (mysql_result($rsid,0,0)==0 or $chong=="1") {   //没有重复或需要覆盖
			
	$sql="insert into crm_khb (khmc,lxr,lxrzw,lxrmobile,lxdh,lxfax,lxEmail,lxQQ,frdb,yzbm,lxdz,khygs,datafrom,province,city,fzjg,sshy,zczb,zyyw,memo,khcsd,state,datainput) values ('";
	$sql=$sql.$data->sheets[0]['cells'][$i][1]."','";
	$sql=$sql.$data->sheets[0]['cells'][$i][2]."','";
	$sql=$sql.$data->sheets[0]['cells'][$i][3]."','";
	$sql=$sql.$data->sheets[0]['cells'][$i][4]."','";
	$sql=$sql.$data->sheets[0]['cells'][$i][5]."','";
	$sql=$sql.$data->sheets[0]['cells'][$i][6]."','";
	$sql=$sql.$data->sheets[0]['cells'][$i][7]."','";
	$sql=$sql.$data->sheets[0]['cells'][$i][8]."','";
	$sql=$sql.$data->sheets[0]['cells'][$i][9]."','";
	$sql=$sql.$data->sheets[0]['cells'][$i][10]."','";
	$sql=$sql.$data->sheets[0]['cells'][$i][11]."','";
	$sql=$sql.$data->sheets[0]['cells'][$i][12]."','";
	$sql=$sql.$data->sheets[0]['cells'][$i][13]."','";
	
	$sql=$sql.($data->sheets[0]['cells'][$i][14]==""?$_POST["province"]:$data->sheets[0]['cells'][$i][14])."','";
	$sql=$sql.($data->sheets[0]['cells'][$i][15]==""?$_POST["city"]:$data->sheets[0]['cells'][$i][15])."','";
	
	$sql=$sql.$data->sheets[0]['cells'][$i][16]."','";
	$sql=$sql.$data->sheets[0]['cells'][$i][17]."','";
	$sql=$sql.$data->sheets[0]['cells'][$i][18]."','";
	$sql=$sql.$data->sheets[0]['cells'][$i][19]."','";
	$sql=$sql.$data->sheets[0]['cells'][$i][20]."','0','-1','".$_SESSION["YKOAUSER"]."/".$_SESSION["XM"]."')";

	//echo $sql;
	mysql_query($sql,$conn);
	$rsid=mysql_query("select last_insert_id()",$conn);
	if ($data->sheets[0]['cells'][$i][22]<>"") mysql_query("update crm_khb set state=-1,callout='".$data->sheets[0]['cells'][$i][22]."',calloutfpsj='2000-01-01' where id=".mysql_result($rsid,0,0),$conn);
	if ($data->sheets[0]['cells'][$i][23]<>"") mysql_query("update crm_khb set state=-2,xsry='".$data->sheets[0]['cells'][$i][23]."',xsryfpsj='2000-01-01' where id=".mysql_result($rsid,0,0),$conn);
	if ($data->sheets[0]['cells'][$i][24]<>"") mysql_query("update crm_khb set nextlx='".$data->sheets[0]['cells'][$i][24]."' where id=".mysql_result($rsid,0,0),$conn);
	if ($data->sheets[0]['cells'][$i][21]<>"") mysql_query("insert into crm_khb_contact values (0,".mysql_result($rsid,0,0).",'excel',now(),'".$data->sheets[0]['cells'][$i][21]."','')",$conn);
		} else {$cf++;}
	}
}

if ($i==2) {
	print "上传数据为空! 请检查模板文件。";
}
} else { 
print "上传错误! 以下是上传的信息:\n"; 
print_r($_FILES); 
} 
} 
else{ 
echo $file_ext." 不是允许导入的文件类型，必须使用.xls格式！"; 
exit; 
} 
}
if ($_POST["save"]=="ok") {
	mysql_query("update crm_khb set datainputsj=now() where datainputsj is null and datainput='".$_SESSION["YKOAUSER"]."/".$_SESSION["XM"]."'",$conn);
	echo "<script>alert('保存完成！');window.location.href='KH_list_D.php';</script>";
	exit;
}
if ($_POST["save"]=="error") {
	mysql_query("delete from crm_khb where datainputsj is null and datainput='".$_SESSION["YKOAUSER"]."/".$_SESSION["XM"]."'",$conn);
	echo "<script>alert('数据已经清空！');</script>";
}
?>
<body marginwidth="0" topmargin="0" leftmargin="0" marginheight="0">
<div class="mainbackground">
<form method="post" id="actForm" action="" ENCTYPE="multipart/form-data">
<input type="hidden" name="save" value="">
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
 
    <div onClick="window.location.href='crmData.xls'" class="Btn">
        EXCEL模板下载
    </div>
 
</LI>
							
							<LI CLASS=line></LI>
                            <LI CLASS=title>
 
    <div onClick="window.history.go(-1);" class="Btn">
        返 回
    </div>
 
</LI>
							<LI CLASS=line></LI>
						
							
							<LI CLASS=line></LI>
						
					</ul>
				</DIV>
			</DIV>
		</DIV>
		
		<DIV ID=MainArea>
		
			<DIV CLASS=ItemBlock_Title>
				<img border="0" src="../images/item_point.gif" />
				EXCEL文件导入(<font color="red">一定要把格式转成模板格式再导入，切记！最好不要用WPS表格，用EXCEL软件</font>)
  </DIV>
			<DIV CLASS=ItemBlockBorder>
				<DIV CLASS=ItemBlock>
					<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0>
						<TR>
							<TD WIDTH=50 HEIGHT=27></TD>
							<TD WIDTH=80>&nbsp;</TD>
							<TD colspan="2">&nbsp;</TD>
						</TR>
                        <TR>
							<TD WIDTH=50 HEIGHT=27></TD>
							<TD WIDTH=80>数据所属</TD>
							<TD colspan="2"><select name="province" id="province" style="width:80px"></select>
                            <select name="city" id="city"></select>
                            Excel上如果省份为空，则以此为准。</TD>
						</TR>
                        <TR>
							<TD WIDTH=50 HEIGHT=27></TD>
							<TD WIDTH=80>&nbsp;</TD>
							<TD colspan="2">&nbsp;</TD>
						</TR>
						<TR>
							<TD HEIGHT=27></TD>
							<TD>选择文件</TD>
							<TD width="263">
                            <INPUT TYPE="FILE" NAME="userfile" SIZE="24" MAXLENGTH="80">
                            <br><input name="chong" type="checkbox" id="chong" value="11" disabled>
                            重复数据自动覆盖(不打勾丢弃,<span class="PageSelectorSelected">慎用</span>)。 </TD>
							<TD width="254"><div onClick="actForm.submit();" class="FuncBtn"><div class=FuncBtnHead></div>
        <div class=FuncBtnMemo>导入</div>
        <div class=FuncBtnTail></div></div><? if ($zs>0) echo '共导入',$zs,'条，重复',$cf,'条';?></TD>
						</TR>
						
						<TR>

							<TD HEIGHT=27></TD>
							<TD>&nbsp;</TD>
							<TD colspan="2">&nbsp;</TD>
						</TR>
						
						
					</TABLE>
				</DIV>
			</DIV>
		  <TABLE WIDTH=100% BORDER=1 CELLSPACING=0 CELLPADDING=0 CLASS=TableStyle>
					<TR ALIGN=center VALIGN=middle ID=TableTitle>
						<TD style="width:25px">序号</TD>
						<TD style="width:100px">客户名称</TD>
						<TD style="width:40px">联系人</TD>
						<TD style="width:50px">职位</TD>
						<TD WIDTH=60>手机</TD>
						<TD WIDTH=50>座机</TD>
						<TD WIDTH=50>传真</TD>
						<TD WIDTH=50>Email</TD>
						<TD WIDTH=50>法人</TD>
						<TD WIDTH=50>邮编</TD>
						<TD WIDTH=100>地址</TD>
						<TD WIDTH=50>员工人数</TD>
						<TD WIDTH=50>数据来源</TD>
						<TD WIDTH=50>省份</TD>
						<TD WIDTH=50>城市</TD>
						<TD WIDTH=50>分支机构</TD>
						<TD WIDTH=50>所属行业</TD>
						<TD WIDTH=50>注册资本</TD>
						<TD WIDTH=50>主营业务</TD>
						<TD WIDTH=100>备注</TD>
					</TR>
			<tbody ID=TableData>
            <? 
			$rs=mysql_query("select crm_khb.* from crm_khb where datainput='".$_SESSION["YKOAUSER"]."/".$_SESSION["XM"]."' and datainputsj is null", $conn);  //新数据
			for($i=0;$i<mysql_num_rows($rs);$i++){  ?>
					<tr onMouseOver="this.style.backgroundColor='#FFD'" onMouseOut="this.style.backgroundColor='white'"  class="TableDetail2" id="d0">
							<TD ><? echo $i +1;?></TD>
							<TD ><? echo mysql_result($rs,$i,"khmc");?></TD>
							<TD style="width:40px"><? echo mysql_result($rs,$i,"lxr");?></TD>
							<TD style="width:50px"><? echo mysql_result($rs,$i,"lxrzw");?></TD>
							<TD ><? echo mysql_result($rs,$i,"lxrmobile");?></TD>
							<TD ><? echo mysql_result($rs,$i,"lxdh");?></TD>
							<TD ><? echo mysql_result($rs,$i,"lxfax");?></TD>
							<TD ><? echo mysql_result($rs,$i,"lxemail");?></TD>
							<TD ><? echo mysql_result($rs,$i,"frdb");?></TD>
						  	<TD><? echo mysql_result($rs,$i,"yzbm");?></TD>
						  	<TD><? echo mysql_result($rs,$i,"lxdz");?></TD>
						  	<TD style="width:50px"><? echo mysql_result($rs,$i,"khygs");?></TD>
						  	<TD><? echo mysql_result($rs,$i,"datafrom");?></TD>
						  	<TD><? echo mysql_result($rs,$i,"province");?></TD>
						  	<TD><? echo mysql_result($rs,$i,"city");?></TD>
						  	<TD><? echo mysql_result($rs,$i,"fzjg");?></TD>
						  	<TD><? echo mysql_result($rs,$i,"sshy");?></TD>
						  	<TD><? echo mysql_result($rs,$i,"zczb");?></TD>
						  	<TD><? echo mysql_result($rs,$i,"zyyw");?></TD>
						  	<TD><? echo mysql_result($rs,$i,"memo");?></TD>
						</tr>
                        <? }?>
            </tbody>
	</TABLE>
</DIV>
				
  <DIV ID=InputDetailBar>
						<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=10 ALIGN=center>
							<TR>
								<TD> 
<div onClick="actForm.save.value='ok';actForm.submit();" class="FuncBtn"><div class=FuncBtnHead></div>
        <div class=FuncBtnMemo>保存</div>
        <div class=FuncBtnTail></div></div></TD>
								<TD> 
<div onClick="actForm.save.value='error';actForm.submit();" class="FuncBtn"><div class=FuncBtnHead></div>
        <div class=FuncBtnMemo>清空</div>
        <div class=FuncBtnTail></div></div></TD>
							</TR>
						</TABLE>
					</DIV>
				
			</CENTER>
</form>
</div>
</body>
</HTML>

<script language="javascript">
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
            cityHtml.push('<option>' + citys[i] + '</option>');
        }
        $city.html(cityHtml.join(''));
    }
};
</script>