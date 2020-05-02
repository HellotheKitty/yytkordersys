<?php
require("../../inc/conn.php");
//require("inc/connection.php");

$openid = $_GET['openid'];
if($_GET['userzh'] <>''){
    $userzh = $_GET['userzh'];
    $pwd = $_GET['pwd'];
    $res = mysql_query("select id from b_ry where bh='$userzh' and password='$pwd'");
    if(mysql_num_rows($res)>0){
        $userid = mysql_result($res,0,'id');
        $openid = $_GET['openid'];
        $res = mysql_query("update b_ry set wechatOpenId='$openid' where id='$userid'");
        if($res){
            echo "<h1 style='margin:150px auto;text-align: center;font-size: 60px;color: #022e81;'>绑定成功O(∩_∩)O~</h1>";
            exit;
        }
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style type="text/css">
        .main{
            font-size:30px;
            width:100%;
            text-align: center;
            display: block;
        }
        .zh_input{
            width:50%;
            height:80px;
            font-size:30px;
        }
        .button{
            width:300px;
            height:50px;
            line-height:25px;
            font-size:25px;
            margin: 50px auto;
            /*border-radius:3px;*/
            /*border:1px solid #00A8E9;*/
            /*background-color: #00A8E9;*/
            display: block;
        }
        .box{
            width:50%;
            display: block;
            margin: 0 auto;
        }
    </style>
</head>
<body>
<div class="main">
    <div class="box"></div>
    <h2>请填写您的印艺天空oa账号和密码</h2>
    <form action="" method="get">
        <label>
            账号:
            <input type="text" class="zh_input" name="userzh"/>
        </label>
        <br>
        <br>
        <label>
            密码:
            <input type="password" class="zh_input" name="pwd"/>
        </label>
        <input type="hidden" name="openid" value="<?php echo $openid; ?>"/>
        <br>
        <input type="submit" value="提交" class="button"/>
    </form>

</div>
</body>
<script src="../../js/jquery-1.3.2.min.js" type="text/javascript"></script>
<script type="text/javascript">

</script>
</html>


