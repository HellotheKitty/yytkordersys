<? require("../inc/conn.php"); 
session_start();
if ($_SESSION["CUSTOMER"]<>"OK") { 
echo "<script language=JavaScript>{window.location.href='../error.php';}</script>";
exit; }?>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <title></title>
<script language="javascript">
var endPointTop='../OAapp/bfapp';
var hiddenImg='../images/hidden.gif';
var showImg='../images/show.gif';
var resizeOldWidth;
var showLeftMenuFlg = true;

function onLoad() {
	
	//取得左侧菜单原有宽度
	resizeOldWidth = parent.document.getElementById("resize").cols;
	if (resizeOldWidth == "0, *") resizeOldWidth = "175,*";
	
}


//取得用户数


//打开便笺画面，便于用户录入
function openText() {
	
}


//更改左侧菜单显示方式
function changeLeftMenu() {
	var resizeObj = parent.document.getElementById("resize");
	if (showLeftMenuFlg) {
		resizeObj.cols = "0, *";
		document.getElementById("leftMenu").src = hiddenImg;
		document.getElementById("leftMenu").alt = "title_menu_expand";
	} else {
		resizeObj.cols = "204, *";
		document.getElementById("leftMenu").src = showImg;
		document.getElementById("leftMenu").alt = "title_menu_folded";
	}
	showLeftMenuFlg = !showLeftMenuFlg;
}
</script>
<LINK href="../css/statusbar.css" type=text/css rel=stylesheet>

</head>

<body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0 onLoad="onLoad()">
<div id="StatusBar">
    <div onClick="changeLeftMenu()" id="Online">
<img  border="0" alt="收起菜单" id="leftMenu" src="../images/show.gif" /><div class="onlinetext">菜单收放</div></div>
    <div id="Info">
    </div>
    <div id="Info">
    </div>
    <div id="Info">
    </div>
    <DIV id=DesktopText>
        
        <SPAN ID=TryoutInfo></SPAN><span id="Version">
            <a onClick="window.open('home.php','null','dependent, toolbar=no,location=no,status=no,menubar=no,resizable=no,scrollbars=auto,width=900,height=480,left=382.5,top=292.0'); return false" href=""><img border="0" width="11" height="11" src="../images/ver.gif" />版本信息
</a>
        </span>
    </DIV>
</div>


</body>
</html>
