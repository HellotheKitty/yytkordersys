
<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<head>
	<style type="text/css">
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
}
.bg2 {
  background-color: rgb( 230, 230, 230 );
  position:absolute;
  left: 5px;
  top: 108px;
  width: 895px;
  height: 302px;
  z-index: 22;
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
  font-family: "Adobe Heiti Std";
  color: rgb( 112, 112, 112 );
  line-height: 1.429;
  position: absolute;
  left: 590px;
  top: 148px;
  z-index: 61;
}
.password {
  font-size: 14px;
  font-family: "Adobe Heiti Std";
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
  line-height:39px;
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
  line-height:39px;
  z-index: 56;
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
  width: 120px;
  height: 42px;
  text-align:center;
  cursor:hand;
  line-height:42px;
  font-family:"黑体";
  color:#FFFFFF;
  z-index: 53;
}
</style>
</head>

<body>
<form action="checklogin.php" method="post" name="forml" id="form1">
<div style="position:absolute;top:30%;left:50%;margin:-153px 0 0 -450px;width:900px;height:306px;">
	<div class="bg1"></div>
		<div class="bg2"></div>
		<div class="line"></div>
		<div class="user">用户名：</div>
		<div class="password">密  码：</div>
		<div class="login"></div>
		<div class="button" onClick="document.form1.submit();">登&nbsp;&nbsp;录</div>
		<input class="text_field" name="user" onmouseover="this.style.borderColor='#4d4d4d'"; onmouseout="this.style.borderColor='#e2e2e2'"
></input>
		<input class="text_field1" name="passw" onmouseover="this.style.borderColor='#4d4d4d'"; onmouseout="this.style.borderColor='#e2e2e2'"></input>
</div>
</form>
</body>
</html>
