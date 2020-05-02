<?php
/**
 * Created by PhpStorm.
 * User: GyCCo
 * Date: 8/28/15
 * Time: 3:09 PM
 */

header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
header("charset=utf-8");
session_start();

require('inc/lib.php');


$code = $_GET["code"];
$state = $_GET["state"];
$get_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.APP_ID.'&secret='.APP_SECRET.'&code='.$code.'&grant_type=authorization_code';


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $get_token_url);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
$output = curl_exec($ch);
curl_close($ch);
$object = json_decode($output, true);



$openId = $object['openid'];

$returnUrl = $_SESSION['returnUrl'];

if ($state == 'GET') {

    if (strpos($returnUrl, '?') !== false) {

        $returnUrl .= '&openId=' . $openId;
    } else {

        $returnUrl .= '?openId=' . $openId;
    }

} else {

    setcookie("YIKA_USER_OPENID", $openId, time() + 3600, "/", ".yikayin.com");
}


header("Location:".$returnUrl);

