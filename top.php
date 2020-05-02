<? 
session_start();

require 'inc/conn.php';
if ($_SESSION["YKOAOK"]<>"OK") { 
echo "<script language=JavaScript>{window.location.href='error.php';}</script>";
exit; }?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<!--<meta http-equiv="refresh" content="180" /> -->
<script src="js/jquery-1.3.2.min.js" type="text/javascript"></script>
<head>

	<style type="text/css">
	body {
		
		font-family:"\5FAE\8F6F\96C5\9ED1", "Helvetica Neue", helvetica, sans-serif, arial, verdana, tahoma;
		    font-variant: normal;
		    font-weight: normal;
		    font-style: normal;
		    min-width: 320px;
		    font-size: 14px;
		    -webkit-text-size-adjust: 100%;
	}
	body,img {
		margin:0;
		padding:0;
		border:0;
	}
	.page {
		left:0px;
		top:0px;
		min-width:900px;
	}
	.topline {
	  background-color: rgb( 49, 49, 49 );
	  position: absolute;
	  left: 0px;
	  top: 0px;
	  width: 100%;
	  height: 25px;
	}
	.exit {
		position:absolute;
		right:20px;
		top:0px;
		*margin-top: -1px;
		width:63px;
		height:25px;
		z-index:10;
	}
  .logo {
 		position:absolute;
 		left:10px;
 		top:32px;
 		width:470px;
 		height:49px;
		z-index:10;
 	}

	.searchbutton {
	  border-width: 1.389px;
	  border-color: rgb( 71, 135, 237 );
	  border-style: solid;
	  background-image: -moz-linear-gradient( -90deg, rgb(77,144,254) 0%, rgb(72,136,238) 100%);
	  background-image: -webkit-linear-gradient( -90deg, rgb(77,144,254) 0%, rgb(72,136,238) 100%);
	  background-image: -ms-linear-gradient( -90deg, rgb(77,144,254) 0%, rgb(72,136,238) 100%);
	  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#4d8ffd', endColorstr='#4888ee');
	  position: absolute;
	  right:107px;
	  top: 42px;
	  width: 40px;
	  height: 22px;
	  z-index: 10;
	  text-align:center;
	}
	.searchtextfield {
	  border-width: 1.389px;
	  border-color: rgb( 193, 193, 193 );
	  border-style: solid;
	  background-color: rgb( 255, 255, 255 );
	  position: absolute;
	  right:150px;
	  top: 42px;
	  width: 238.222px;
	  height: 22px;
	  z-index: 10;
	}
	.searchicon {
		margin-left:14px;
		margin-top:5px;
		width:13px;
		height:13px;
		z-index:20;
	}
	
	.headerbg {
	  background: #fefefe;
	  position: absolute;
	  left: 0px;
	  top: 25px;
	  width: 100%;
	  height: 65px;
	}
	.navleft {
	  background-color: rgb( 54, 131, 255 );
	  position: absolute;
	  left: 0px;
	  top: 80px;
	  width: 210px;
	  height: 35px;
	  z-index: 10;
	}
	.navleftbot {
		position: absolute;
		left: 0px;
		top: 115px;
		width: 200px;
		height: 5px;
		*border-top: 1px solid #cccccc;
		background-color: #f3f3f7;
		z-index: 5;
	}
	.navmid {
	  background-image: -moz-linear-gradient( 0deg, rgb(52,126,245) 0%, rgb(54,131,255) 100%);
	  background-image: -webkit-linear-gradient( 0deg, rgb(52,126,245) 0%, rgb(54,131,255) 100%);
	  background-image: -ms-linear-gradient( 0deg, rgb(52,126,245) 0%, rgb(54,131,255) 100%);
	  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#347ef6', endColorstr='#3683ff',gradientType=1);
	  position: absolute;
	  left: 200px;
	  top: 85px;
	  width: 100px;
	  height: 35px;	
	  z-index:5;  
	}
	.navright {
	  background-color: rgb( 54, 131, 255 );
	  position: absolute;
	  left: 300px;
	  right:0px;
	  top: 85px;
	  height: 35px;
	  z-index:5;
	  color:#FFF;
	  line-height:35px;
	}
	.navshadow {
		position:absolute;
		left:200px;
		top:115px;
		width:10px;
		height:5px;
		background: url("images/navshadow.gif");
		z-index:10;
	}
	.shouye {
		position:absolute;
		left:0px;
		top:80px;
		width:130px;
		height:35px;
		z-index:20;
	}
	.mail {
		position:absolute;
		right:230px;
		top:85px;
		width:98px;
		height:35px;
		z-index:10;
	}
	.Schedule {
		position:absolute;
		right:107px;
		top:85px;
		width:118px;
		height:35px;
		z-index:10;
		
	}
	.contacts {
		position:absolute;
		right:20px;
		top:85px;
		width:82px;
		height:35px;
		z-index:10;
		
	}
	

	.leftsidebg {
	  background-color: rgb( 243, 243, 247 );
	  position: absolute;
	  left: 0px;
	  top: 115px;
	  width: 230px;
	  height: 100%;
	}
	.shadow1 {
	  background-color: rgb( 233, 233, 233 );
	  position: absolute;
	  left: 230px;
	  top: 120px;
	  width: 1px;
	  height: 99.4%;
	}
	.shadow2 {
	  background-color: rgb( 247, 247, 247 );
	  position: absolute;
	  left: 231px;
	  top: 120px;
	  width: 1px;
	  height: 99.4%;
	}
	.mainbackground {
	  border-style: solid;
	  border-width: 1px;
	  border-color: rgb( 204, 204, 204 );
	  border-radius: 5px;
	  background-color: rgb( 255, 255, 255 );
	  position: absolute;
	  left: 242px;
	  right:11px;
	  top: 132px;
	  bottom:11px;
	  height: 96%;
	  z-index: 10;
	}
	#seehello {
		float:right;
		color:#FFF; 
		font-size:12px;
		height:25px;
		line-height:25px;
		margin-right:100px;
		*+margin-top:-25px;
	}
	#seehello ul {
		height:25px;
		margin:2px 0px 0px 0px;
	}
	.welcome {
		display:block;
		list-style:none;
		float:left;
		margin-left:5px;
	}
	#hello {
		margin-left:25px;
	}
	.wishes {
			position: relative;
			float: right;
			z-index: 1000;
			margin: 39px 80px 0px 0px;
			font-size: 14px;
			color: #aaa;
			text-align: center;
	}
	.wishes p {
			color: #444;
			margin: 0;
			font-size: 16px;
	}
	.wishes p .R {
			color: #F85C31;
	}
	.wishes p .G {
			color: #7ED321;
	}
	.wishes p .B {
			color: #4A90E2;
	}
	.wishes1 {
			position: relative;
			float: right;
			z-index: 1000;
			margin: 34px 40px 0px 0px;
	}
        .logout{
            color: #cccccc;
        }
	.text-logo{
		display: inline-block;
		*display:inline;
		zoom: 1;
		font-size:32px;
		font-family:"microsoft yahei";
		color: #333;
		text-indent: 10px;
		font-weight:bold;
	}
	</style>
<SCRIPT language=JavaScript>
function logout(){
	if(!confirm('<? echo $_SESSION["XM"];?> 您确定要退出吗？')){
		return false;
	}
	else {
		window.parent.location.replace("index.php");
	}
	return true;
}
</script>


</head>

<body>

<div class="page">		
	<div class="topline">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<iframe width="400" scrolling="no" height="22" frameborder="0" allowtransparency="true" src="http://i.tianqi.com/index.php?c=code&id=1&color=%23FFFFFF&bgc=%23&py=<? echo $_SESSION["GSSDQ"]=="上海"?"shanghai":"beijing";?>&icon=1&wind=1&num=1"></iframe>
    <div id="seehello">
		<ul>
			<li class="welcome"><a style="color:#FFF;margin-right:20px" href='http://down.tech.sina.com.cn/page/40975.html' target="_blank">OA推荐浏览器下载</a></li>
            <li class="welcome"><? echo date("Y-m-d")?></li>
			<li id="hello" class="welcome"><div id="sayhello">您好，</div></li>
			<li class="welcome"><? echo $_SESSION["XM"];?></li>
            <li class="welcome">
                <!--<a href="javascript:void(0)" onclick="javascript:window.open('changepwd.php?user=<?/* echo $_SESSION['YKOAUSER'] */?>&from=list','OrderDetail','height=400px,width=300px,top=100px,left=1000px,location=no,menubar=no,resizable=no,scrollbars=yes,status=no,titlebar=no,toolbar=no,directories=no');return false;"  class="logout">修改密码</a>-->
            </li>
        </ul>
		</div>
    </div>
	
    <div class="exit">
		<a onClick="logout()" title="退出本系统" href="top.php">
				<img src="images/exit.gif" align=middle border=0 width="63" height="25" alt="退出系统"></a>
</div>
	<div class="logo">
<!--		<img src="images/logo.png" height="42" alt="">-->
		<span class="text-logo">印艺天空</span>
	</div>
	
	<div class="navleft"></div>
	<div class="navleftbot"></div>
	<div class="navmid"></div>
	<div class="navright">
		<marquee direction="left" width="800" scrollamount="2" onmouseover=this.stop(); onmouseout=this.start() >
		<span id="mqs">印艺天空办公系统</span>
		</marquee>
	</div>
	<div class="headerbg"></div>
	<div class="shouye"><a href="home.php" target="main">
		<img border="0" onclick="parent.menu.Ddown('');" src="images/shouye.gif" width="130" height="35" alt=""></a>
	</div>
	<div class="mail"><a href="OAfile/msg_recvok.php" target="main">
		<img border="0" onclick="parent.menu.Ddown('A');parent.menu.$('#ysyj').click();" src="images/mail.gif" width="98" height="35" alt=""></a>
        <div style="position:absolute; float:right; width:20px; height:20px; left: 69px; top: 10px; color:#FFF; text-align:center; font-size:12px;" id="yjs"></div>
	</div>
	<div class="Schedule"><a href="OAfile/OA_gotodo.php" target="main">
		<img border="0" onclick="parent.menu.Ddown('');" src="images/Schedule.gif" width="118" height="35" alt=""></a>
        <div style="position:absolute; float:right; width:20px; height:20px; left: 89px; top: 10px; color:#FFF; text-align:center; font-size:12px;" id="dbs"></div>
</div>
	<div class="contacts"><a href="OAfile/txl_dw.php" target="main">
	<img border="0" onclick="parent.menu.Ddown('F');parent.menu.$('#dwtxl').click();" src="images/contacts.gif" width="82" height="35" alt=""></a>
	</div>
	<div class="navshadow">
<!-- 		<img src="images/navshadow.gif" width="10" height="5" alt=""> -->
	</div>
	
	</div>
    <script language="javaScript"> 
now = new Date();
hour = now.getHours();
if(hour < 5){document.getElementById("sayhello").innerHTML="凌晨好，";} 
else if (hour < 9){document.getElementById("sayhello").innerHTML="早上好，";} 
else if (hour < 12){document.getElementById("sayhello").innerHTML="上午好，";} 
else if (hour < 14){document.getElementById("sayhello").innerHTML="中午好，";} 
else if (hour < 18){document.getElementById("sayhello").innerHTML="下午好，";} 
else {document.getElementById("sayhello").innerHTML="晚上好，";} 


function update() {
    $.post("top_b.php",{from:"step"},function(data) {
		var o = eval("("+data+")");
		$("#yjs").html(o.rs0);$("#dbs").html(o.rs1);
		$("#mqs").html(o.mqs);
    });
};

var intervalId=window.setInterval(update, 10000);
update();

</script>
</body>
</html>
