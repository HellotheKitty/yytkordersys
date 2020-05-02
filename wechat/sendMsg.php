<?php

header("Content-type: text/html; charset=utf-8");

//require('handle-events.php');
require('inc/log.php');
require('inc/lib.php');
require 'inc/PDO.php';

$temp_id = $_POST['tempid'];
$touser = $_POST['touser'];
$usertype = $_POST['usertype'];
$linkurl = $_POST['linkurl'];
$postform = json_decode($_POST['postform'],true);

$wechat = new WechatTemplateMsg();
$wechat -> sendMsg($temp_id,$usertype ,$touser,$linkurl,$postform);


class WechatTemplateMsg{

    public function getAccessToken() {
        $check = new checkRequest();
        $get_access_token_url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".APP_ID."&secret=".APP_SECRET;
        $output = $check->httpsRequest($get_access_token_url);
        $object = json_decode($output, true);
        $accessToken = $object['access_token'];
        return $accessToken;
    }

    public function sendMsg($temp_id,$usertype,$touser,$linkurl,$postform){

        $access_token = self::getAccessToken();
        $msg_send_url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=$access_token";

        $tousers = explode('|',$touser);

        foreach($tousers as $item){

            $db = new DB();

            if($usertype == 'kh'){
                $sql = "select authcode from base_kh where khmc = '$item'";
            }else{
                $sql = "select wechatOpenId from b_ry where bh = '$item'";
            }

            $res = $db -> selectOne($sql);
            if($usertype == 'kh'){
                $user_openid = $res['authcode'];
            }else{
                $user_openid = $res['wechatOpenId'];
            }


            if(!$user_openid){
                continue;
            }

            $post_data = [
                "touser" => $user_openid,
                "template_id" => $temp_id,
                "url" => $linkurl,
                "data" => [
                    "first" => ['value' => $postform['first']],
                    "keyword1" => ['value' => $postform['keyword1']],
                    "keyword2" => ["value" => $postform['keyword2']],
                    "keyword3" => ["value" => $postform['keyword3']],
                    "keyword4" => ["value" => $postform['keyword4']],
                    "keyword5" => ["value" => $postform['keyword5']],
                    "keyword6" => ["value" => $postform['keyword6']],
                    "keyword7" => ["value" => $postform['keyword7']],
                    "keyword8" => ["value" => $postform['keyword8']],
                    "remark" => ['value' => $postform['remark']]
                ]
            ];
            $post_data = json_encode($post_data);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $msg_send_url);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);

//post
            curl_setopt($ch,CURLOPT_POST,1);
            curl_setopt($ch,CURLOPT_POSTFIELDS,$post_data);

            $output = curl_exec($ch);
            curl_close($ch);
        }

    }

}
?>