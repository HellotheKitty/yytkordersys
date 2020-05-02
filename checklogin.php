<noscript>
    <font color=red size="+3">对不起，你的浏览器不支持JavaScript，系统运行可能出错!请在Internet选项中的安全设置里面启用Javascript!</font>
</noscript>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<? require("inc/conn.php");
session_start();
$user=trim($_POST["user"]);
$passw=trim($_POST["passw"]);
//判断姓名登录
if (preg_match ("/^[A-Za-z]/", $user)) {
    $_tj = "bh='$user'";		//是字母开头
} else {
    $_tj = "xm='$user'";		//不是字母开头
}
//$sql="select b_ry.*,b_dwdm.ssdq from b_ry,b_dwdm where b_ry.dwdm=b_dwdm.dwdm and bh='$user' and password='$passw'";
$sql="select b_ry.*,b_dwdm.ssdq from b_ry,b_dwdm where b_ry.dwdm=b_dwdm.dwdm and $_tj and password='$passw'";
$rs = mysql_query($sql, $conn);                     //获取数据集
if(!$row = mysql_fetch_assoc($rs)){
    mysql_free_result($rs);

    ////////免登陆
    if($_GET['autolog']<>''){
        $user = $_GET['user'];
        $code = $_GET['autocode'];
        if($code = md5($user)){

            $khrs = mysql_query("select l.loginname,l.loginpwd,base_kh.gdzk,base_kh.khmc,base_kh.id,base_kh.mpzh,l.authcode,lxr,lxdh,lxdz from base_kh_login l,base_kh where l.khmc=base_kh.khmc and l.loginname='$user'");
            $info = mysql_fetch_array($khrs);

            session_unset();
            session_destroy();
            session_start();
            $_SESSION["CUSTOMER"] = "OK";
            $_SESSION["GDWDM"] = $info["gdzk"];
            $_SESSION["KHMC"] = $info["khmc"];
            $_SESSION["KHID"] = $info["id"];
            $_SESSION["MPZH"] = $info["mpzh"];
            $_SESSION["INFO"] = $info;
            $_SESSION['LOGINNAME'] = $info['loginname'];
            do{	//可以重复

                $newAuthcode1 = rand(100000,999999);
                $rs2 = mysql_query("select id from base_kh_login where authcode='$newAuthcode1'",$conn);
            }while($rs2 && mysql_num_rows($rs2) > 0);
            mysql_query("update base_kh_login set authcode='$newAuthcode1' where loginname=".$_SESSION["KHID"],$conn);
            $_SESSION["AUTHCODE"] = $newAuthcode1;
            print "<script language=JavaScript>{ parent.location.href='customer/main.php';}</script>";
            exit;
        }

    }

///////

    $khrs = mysql_query("select l.loginname,l.loginpwd,base_kh.gdzk,base_kh.khmc,base_kh.id,base_kh.mpzh,l.authcode,lxr,lxdh,lxdz from base_kh_login l,base_kh where l.khmc=base_kh.khmc and l.loginname='$user'");
    if($khrs && mysql_num_rows($khrs) == 1){
        $info = mysql_fetch_array($khrs);
        if($info["loginpwd"] == $passw){
            session_unset();
            session_destroy();
            session_start();
            $_SESSION["CUSTOMER"] = "OK";
            $_SESSION["GDWDM"] = $info["gdzk"];
            $_SESSION["KHMC"] = $info["khmc"];
            $_SESSION["KHID"] = $info["id"];
            $_SESSION["MPZH"] = $info["mpzh"];
            $_SESSION["INFO"] = $info;
            $_SESSION['LOGINNAME'] = $info['loginname'];
            do{	//可以重复

                $newAuthcode1 = rand(100000,999999);
                $rs2 = mysql_query("select id from base_kh_login where authcode='$newAuthcode1'",$conn);
            }while($rs2 && mysql_num_rows($rs2) > 0);
            mysql_query("update base_kh_login set authcode='$newAuthcode1' where loginname=".$_SESSION["KHID"],$conn);
            $_SESSION["AUTHCODE"] = $newAuthcode1;
            print "<script language=JavaScript>{ parent.location.href='customer/main.php';}</script>";
            exit;
        }
    }
    print "<script language=JavaScript>{window.alert('帐号和口令错误，不能登录，请检查!');window.location.href='lmiddle.php';}</script>";
} else {
    $_SESSION["YKOAOK"]="OK";
    $_SESSION["OK"]="OK";
    $_SESSION["YKOAUSER"]=$row["bh"];
    $_SESSION["XM"]=$row["xm"];
    $_SESSION["YKUSERNAME"]=$row["xm"];
    $_SESSION["YKMOBILE"]=$row["mobile"];
    $_SESSION["QX"]=$row["qx"];
    $_SESSION["YKOAUID"]=$row["id"];
    $_SESSION["GDWDM"]=$row["dwdm"];
    $_SESSION["GSSDQ"]=$row["ssdq"];
    $_SESSION["FBSD"]=0;
    $ip=GetIP();
    $bro = getBrowserAndVersion();
    mysql_query("insert into OA_loginlog values (0,'".$row["xm"]."',now(),'".$ip."-'".",'$bro')",$conn);
    if ($_POST["savepass"]==1) {
        setcookie('yikaoauser',$user,time()+2592000);//保存帐号1个月
        setcookie('yikaoapw',$passw,time()+2592000);
    } else {
        setcookie('yikaoauser','',time()+2592000);
        setcookie('yikaoapw','',time()+2592000);
    }

    print "<script language=JavaScript>{ parent.location.href='main.php';}</script>";
    mysql_free_result($rs);
};

function GetIP(){
    if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
        $ip = getenv("HTTP_CLIENT_IP");
    else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
        $ip = getenv("HTTP_X_FORWARDED_FOR");
    else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
        $ip = getenv("REMOTE_ADDR");
    else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
        $ip = $_SERVER['REMOTE_ADDR'];
    else
        $ip = "unknown";
    return($ip);
}

function getBrowserAndVersion(){
    $agent=$_SERVER["HTTP_USER_AGENT"];
    if(strpos($agent,'MSIE')!==false || strpos($agent,'rv:11.0')) //ie11判断
        $bro = "ie";
    else if(strpos($agent,'Firefox')!==false)
        $bro = "firefox";
    else if(strpos($agent,'Chrome')!==false)
        $bro = "chrome";
    else if(strpos($agent,'Opera')!==false)
        $bro = 'opera';
    else if((strpos($agent,'Chrome')==false)&&strpos($agent,'Safari')!==false)
        $bro = 'safari';
    else
        $bro = 'unknown';
    if($bro == 'unknown')
        return $bro;
    if (preg_match('/MSIE\s(\d+)\..*/i', $agent, $regs))
        $bro .= " ".$regs[1];
    elseif (preg_match('/FireFox\/(\d+)\..*/i', $agent, $regs))
        $bro .= " ".$regs[1];
    elseif (preg_match('/Opera[\s|\/](\d+)\..*/i', $agent, $regs))
        $bro .= " ".$regs[1];
    elseif (preg_match('/Chrome\/(\d+)\..*/i', $agent, $regs))
        $bro .= " ".$regs[1];
    elseif ((strpos($agent,'Chrome')==false)&&preg_match('/Safari\/(\d+)\..*$/i', $agent, $regs))
        $bro .= " ".$regs[1];
    return $bro;
}


function getIPLoc_QQ($queryIP){
    $url = 'http://ip.qq.com/cgi-bin/searchip?searchip1='.$queryIP;
    $ch = curl_init($url);
    curl_setopt($ch,CURLOPT_ENCODING ,'gb2312');
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回
    $result = curl_exec($ch);
    $result = mb_convert_encoding($result, "utf-8", "gb2312"); // 编码转换，否则乱码
    curl_close($ch);
    preg_match("@<span>(.*)</span></p>@iU",$result,$ipArray);
    $loc = $ipArray[1];
    return $loc;
}

/*优化var_dump数组输出格式*/
function dump($arr){
    if(is_array($arr)){
        echo "<br>array $arr:";
        foreach($arr as $key => $val)
            echo $key." => ".$val."<br>";
        echo "<br>";
    }else{
        var_dump($arr);
    }
}

?>
