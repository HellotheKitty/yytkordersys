<?php
/**
 * Created by PhpStorm.
 * User: GyCCo
 * Date: 8/28/15
 * Time: 3:09 PM
 */

header("charset=utf-8");
session_start();


require('inc/lib.php');

$signature = $_GET['signature'];
$nonce = $_GET['nonce'];

$state = $_GET['type'];

$returnUrl = urldecode($_GET['returnUrl']);

$check = new checkRequest();

if (!$check->checkSignature($signature, $nonce)) {
    baseActions::returnCode('Invalid Arguments');
    exit;
}

$_SESSION['returnUrl'] = $returnUrl;


$REDIRECT_URI = 'http://oa.skyprint.cn/wechat/OAuth-callback.php';
$scope = 'snsapi_base';
//$scope='snsapi_userinfo';//需要授权
$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.APP_ID.'&redirect_uri='.urlencode($REDIRECT_URI).'&response_type=code&scope='.$scope.'&state='.$state.'#wechat_redirect';



header("Location:".$url);