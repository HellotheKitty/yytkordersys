<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<head>

</head>
<body >

<form action="index.php?m=user&a=login" method="post"  name="form1"  target="_new">
	<fieldset>
    <input type="hidden" value="<? echo $_GET["id"]?>" name="id" id="id" />
			用户名：
            <input type="text" name="name" id="name" value="<? echo $_GET["user"]?>"><br/>
			密 &nbsp;&nbsp; 码：
            <input type="password" name="password" id="password" value="<? echo $_GET["pass"]?>">
			<input name="submit"  type="submit" value="CRM登录" id="But_Login"  onclick="saveit();" />
     </fieldset>
</form>
</body>
<? if ($_GET["pass"]<>"") {?>
正在自动登录CRM，请稍候...
<script language="javascript" type="text/javascript">
    window.onload = function() {
        // 加载后自动提交Submit登录
        document.getElementById("But_Login").click();
    }
</script>
<? }?>
<script language="javascript">
function saveit() {
 		var user= document.getElementById("name").value;
 		var pass= document.getElementById("password").value;
 		var id= document.getElementById("id").value;
 			var xmlHttpReq;
            if (typeof (XMLHttpRequest) != "undefined")
                xmlHttpReq = new XMLHttpRequest();
            else if (window.ActiveXObject)
                xmlHttpReq = new ActiveXObject("MSXML2.XMLHTTP.3.0");
            xmlHttpReq.open("POST", "Savecrm.php?jid=" + Math.round(Math.random() * 10000), false);
            xmlHttpReq.setRequestHeader("Content-Type","application/x-www-form-urlencoded;charset=UTF-8");
            xmlHttpReq.send("ID=" + id + "&user=" + user+ "&pass=" + pass);
            if (xmlHttpReq.status == 200) {
                var data = xmlHttpReq.responseText;
				//alert(data);     //测试返回数据
                if (data.indexOf("Error") == 0) {
                    alert(data.replace("Error:",""));
                } else {
                    isOk = true;
                }
            }
}
</script>