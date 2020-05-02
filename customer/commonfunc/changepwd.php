<?
session_start();

require '../inc/conn.php';
if ($_SESSION["CUSTOMER"]<>"OK") {
    echo "<script language=JavaScript>{window.location.href='error.php';}</script>";
    exit; }?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--<meta http-equiv="refresh" content="180" /> -->
<script src="js/jquery-1.8.3.min.js" type="text/javascript"></script>
<head>
    <style type="text/css">

        .btnsub{
            background-color: #00a1f1;
            border: medium none;
            border-radius: 3px;
            color: white;
            height: 25px;
            text-align: center;
            width: 80px;
        }
        .pwdtext{
            width:200px;
            height:24px;
        }
    </style>
</head>
<?
if($_POST['user']!==null){

    $bh = $_POST['user'];
    $pwd1 = trim($_POST['pwd1']);
    $pwd2 = trim($_POST['pwd2']);
    $oldpwd = trim($_POST['oldpwd']);

    $sql = "select id, khmc from base_kh_login WHERE khmc='$bh' and loginpwd = '$oldpwd' limit 1";

    $res = mysql_query($sql,$conn);
    if(mysql_num_rows($res)){

        if($pwd1==$pwd2){
            $khid = mysql_result($res,0,'id');
            $sql1 = "update base_kh_login set loginpwd = '$pwd2' WHERE id = '$khid'";
            $rs = mysql_query($sql1,$conn);
            if($rs){
                echo "<div style='color:#0f0;'>密码修改成功!</div>";
                exit();
            }
        }else{
            echo "<div style='color:#f00;'>两次输入的密码不一致</div>";

        }
    }else{
        echo "<div style='color:#f00;'>旧密码输入不正确!</div>";
    }


}

?>
<body>

<div style="margin: 20px;">
    <form name="form" method="post" onsubmit="return checkformat();">

        <input type="hidden" name="user" value="<? echo $_GET['user'] ?>">

        <span>请输入旧密码：&nbsp;&nbsp;</span>
        <input type="password" class="pwdtext" name="oldpwd"/>
        <br>
        <br>

        <span>请输入新密码：&nbsp;&nbsp;</span>
        <input type="password" class="pwdtext" name="pwd1" id="mInput"/>
        <span style="color:#777;font-size:12px;">(密码不能包含特殊字符)</span>
        <br>
        <br>

        <span>请再次输入新密码：</span>
        <input type="password" class="pwdtext" name="pwd2"/>
        <br>
        <p><input type="submit" class="btnsub" value="提交"/></p>
    </form>


</div>
<script language="javaScript">

    function checkformat(){
        var reg = /^[0-9a-zA-Z]+$/;
        var str = document.getElementById("mInput").value;

        if(!reg.test(str)){
            alert("你输入的字符不是数字或者字母");

            return false;
        }else{
            return true;
        }
    }

</script>
</body>
</html>
