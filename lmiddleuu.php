<? session_start();

$url = "http://www.zjqg.gov.cn/wwwroot/201171150710.htm"; 
$contents = file_get_contents($url); 
//如果出现中文乱码使用下面代码 
//$contents = iconv("gb2312", "utf-8",$contents); 
$contents=substr($contents,strpos($contents,"05.资讯列表/productStyle/87_列表样式47/pic/list_title_1.gif) no-repeat left center; padding-left:12px;")); 
$contents=substr($contents,0,strpos($contents,"NewsListFoot_htm_div"));
?> 
<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<head>
	<style type="text/css">
a {
	text-decoration: none;
	color: #434343;
}
a:hover {
	color: #347ff6;
}
.midbg {
	position: absolute;
	top:10px;
	z-index: 20;
	border: 0px;
	background:url('images/loginbg.jpg');
	width: 900px;
	height: 500px;
}
.bg1 {
  border-style: solid;
  border-width: 1px;
  border-color: rgb( 211, 211, 211 );
  background-color: rgb( 255, 255, 255 );
  position:absolute;
  left: -2px;
  top: 99px;
  width: 898px;
  height: 306px;
  z-index: 23;
/*
  padding-top: 17px;
  padding-left: 25px;
*/
/*
  font-size: 15px;
  color: #ff0000;
*/
}
.bg2 {
  background-color: rgb( 230, 230, 230 );
  position:absolute;
  left: 6px;
  top: 108px;
  width: 895px;
  height: 302px;
  z-index: 22;
}
.newsicon {
	position: absolute;
	left: 20px;
	top:119px;
	font-size: 15px;
	color: #ff0000;
	z-index: 30;
}
.news {
	position: absolute;
	left: 15px;
	top: 118px;
	width: 519px;
	height: 272px;
	z-index: 30;
	font-size: 14px;
	padding-left: 25px;
	line-height: 24px;
	padding-top: 26px;
	letter-spacing: 1.5px;
}
.line {
  background-color: rgb( 234, 234, 234 );
  position: absolute;
  left: 549px;
  top: 141px;
  width: 3px;
  height: 224px;
  z-index: 24;
}
.user {
  font-size: 14px;
  font-family:"宋体";
  color: rgb( 112, 112, 112 );
  line-height: 1.429;
  position: absolute;
  left: 590px;
  top: 148px;
  z-index: 61;
}
.password {
  font-size: 14px;
  font-family: "宋体";
  color: rgb( 112, 112, 112 );
  line-height: 1.429;
  position: absolute;
  left: 590px;
  top: 227px;
  z-index: 58;
}
.text_field {
  border-style: solid;
  border-width: 1px;
  border-color: rgb( 226, 226, 226 );
  border-radius: 2px;
  background-color: rgb( 255, 255, 255 );
  position: absolute;
  left: 589px;
  top: 168px;
  width: 269px;
  height: 39px;
  z-index: 60;
  padding: 10px;
  font-size: 16px;
  outline: none;
}

.text_field1 {
  border-style: solid;
  border-width: 1px;
  border-color: rgb( 226, 226, 226 );
  border-radius: 2px;
  background-color: rgb( 255, 255, 255 );
  position: absolute;
  left: 589px;
  top: 247px;
  width: 269px;
  height: 39px;
  z-index: 56;
  padding: 10px;
  font-size: 14px;
  outline: none;
}
.text_field:focus, .text_field1:focus {
	background-color: #fafafa;
}

.login {
  font-size: 14px;
  font-family: "Hiragino Sans GB";
  color: rgb( 255, 255, 255 );
  line-height: 1.429;
  position: absolute;
  left: 622.479px;
  top: 341.974px;
  z-index: 54;
}
.button {
  border-radius: 3px;
  background-image: -moz-linear-gradient( -90deg, rgb(80,191,255) 0%, rgb(67,161,255) 100%);
  background-image: -webkit-linear-gradient( -90deg, rgb(80,191,255) 0%, rgb(67,161,255) 100%);
  background-image: -ms-linear-gradient( -90deg, rgb(80,191,255) 0%, rgb(67,161,255) 100%);
  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#50bfff', endColorstr='#43a1ff');  /*IE*/
  position: absolute;
  left: 588px;
  top: 310px;
  border-top:0 px;
  border-right:0 px;
  border-bottom: 0 px;
  border-left: 0 px;
  -webkit-border-start-color: rgb(80,191,255);
  -webkit-border-end-color: rgb(67,161,255);
  -webkit-border-before-color: rgb(80,191,255);
  -webkit-border-after-color: rgb(67,161,255);
  width: 120px;
  height: 42px;
  font-family: "宋体";
  font-size:14px;
  color: #ffffff;
  z-index: 53;
}
.buttonover {
  border-radius: 3px;
  background-color: rgb( 54, 131, 255 );
   border-top:0 px;
  border-right:0 px;
  border-bottom: 0 px;
  border-left: 0 px;
    -webkit-border-start-color: rgb( 54, 131, 255 );
  -webkit-border-end-color: rgb( 54, 131, 255 );
  -webkit-border-before-color: rgb( 54, 131, 255 );
  -webkit-border-after-color: rgb( 54, 131, 255 );
  position: absolute;
  left: 588px;
  top: 310px;
  width: 120px;
  height: 42px;
  font-family: "宋体";
  font-size:14px;
  color: #ffffff;
  z-index: 53;
}

</style>
</head>

<body>
<form action="checklogin.php" method="post" name="forml" id="form1">
<div style="position:absolute;top:30%;left:50%;margin:-153px 0 0 -450px;width:900px;height:306px;">
    <div class="midbg"></div>
	<div class="bg1"></div>
		<div class="bg2"></div>
		<div class="newsicon">最新动态</div>
		<div class="news">
        <? while (strpos($contents,'<a href="/wwwroot')>0) {
			$contents=substr($contents,strpos($contents,'<a href="/wwwroot'));
			$ss=substr($contents,0,strpos($contents,'</a>'));
			echo "?&nbsp;&nbsp;".str_replace("/wwwroot","http://www.zjqg.gov.cn/wwwroot",$ss)."</a><br>";
			$contents=substr($contents,strpos($contents,'</a>'));
		}
		?>
        </div>
		<div class="line"></div>
		<div class="user">用户名：</div>
		<div class="password">密  码：</div>
		<div class="login"></div>
        <button  class="button" onClick="document.form1.submit();" onMouseOver="this.className='buttonover'" onMouseOut="this.className='button'">登&nbsp;&nbsp;录</button>
		<input id="name" class="text_field" name="user" autofocus="autofocus" onMouseOver="this.style.borderColor='#959595'"; onMouseOut="this.style.borderColor='#e2e2e2'"
></input>
		<input class="text_field1" type="password" name="passw" onMouseOver="this.style.borderColor='#959595'"; onMouseOut="this.style.borderColor='#e2e2e2'" onKeyDown="sendMsgKey();"></input>
</div>
</form>
<script type="text/javascript"> 
document.getElementById ('name').focus()
function sendMsgKey(){
	if (window.event.keyCode == 13) {
		document.all.form1.submit();
	}
}
</script>
</body>
</html>
