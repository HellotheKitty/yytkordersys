<?php
/**
 * Created by PhpStorm.
 * User: GyCCo.
 * Date: 18/10/2016
 * Time: 2:51 PM
 */

header("Content-type: text/html; charset=utf-8");


//require('handle-events.php');
require('inc/log.php');
require('inc/lib.php');

$wechatObj = new wechatCallbackapi();

if (isset($_GET['echostr'])) {
//      $wechatObj->responseMsg();
 }else{
//     $wechatObj->valid();
}
$wechatObj->responseMsg();
//$wechatObj->createMenu();

//$wechatObj->responseMsg();
//var_dump($wechatObj->getQRSCENE());


//echo $wechatObj->getAccessToken();

class wechatCallbackapi
{

    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        if ( $tmpStr == $signature ) {
            return true;
        } else {
            return false;
        }
    }

    public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if ($this->checkSignature()) {
            echo $echoStr;
            exit;
        }
    }

    public function getAccessToken() {
        $check = new checkRequest();
        $get_access_token_url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".APP_ID."&secret=".APP_SECRET;
        $output = $check->httpsRequest($get_access_token_url);
        $object = json_decode($output, true);
        $accessToken = $object['access_token'];

        return $accessToken;
    }

    public function getQRSCENE() {
        $check = new checkRequest();
        $url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".self::getAccessToken();
        $data = '{"action_name": "QR_LIMIT_STR_SCENE", "action_info": {"scene": {"scene_str": "ENTERINTOECARDPOD"}}}';

        $qrData = $check->httpsRequest($url, $data);
        $callBack = json_decode($qrData, true);
        $ticket = $callBack['ticket'];

        if ($ticket == '') {
            self::getAccessToken();
            $qrData = $check->httpsRequest($url, $data);
            $callBack = json_decode($qrData, true);
            $ticket = $callBack['ticket'];
        }

        //获取二维码ticket后，开发者可用ticket换取二维码图片。无须登录态即可调用。
        //HTTPS GET请求说明（TICKET必需UrlEncode）
        //https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=TICKET
        return $ticket;
    }

    public function createMenu() {

        $check = new checkRequest();
        $ACCESS_TOKEN = $this->getAccessToken();
        $nonce = $check->getNonceStr();
        $signature = sha1(md5(XTOKEN).$nonce);
        //$ACCESS_TOKEN = "-5O8mZUUcX1cIWhVYYcO4KZV2KiwB1oQx5dTA9eLfQ9NA3jmB63MfZkZenTLo5sFWVvuKP24JqwcmPmk2FQOKx3_mZ272Vn5eG4HPCRUiNY";

        $data = '{
            "button":[
            {
                "name":"关于我们",
                "sub_button":[
                {
                    "type":"view",
                    "name":"品牌梦想",
                    "url":"https://mp.weixin.qq.com/mp/appmsg/show?__biz=MjM5NDExMDgzNQ==&appmsgid=10001004&itemidx=1&sign=4f1923a9051e776144ad444625b23a13&scene=18&uin=MjM3NzgzODYwMQ%3D%3D&key=c86cca22442d18b885b2cc1abc144fac37ec1e971d8aa028b27ff566aba5111625466a02892fb4fd4a6ee742032dcc3d72f42ba1244db9a6ae19276bbafcf01c9a32ba15b6adfcc2ac1f8b66b6c73184&ascene=1&devicetype=Windows+7&version=6203005d&lang=zh_CN&winzoom=1"
                },
                {
                    "type":"click",
                    "name":"企业宣传",
                    "key":"publish"
                },
                {
                    "type":"click",
                    "name":"印艺品牌",
                    "key":"brand"
                }
                ]
            },
            {
                "name":"我的印艺",
                "sub_button":[
                    {
                        "type":"view",
                        "name":"我的账户",
                        "url":"http://oa.skyprint.cn/WXS/per_center.php"
                    },
                    {
                        "type":"view",
                        "name":"我的订单",
                        "url":"http://oa.skyprint.cn/WXS/dd_list.php"
                    },
                    {
                        "type":"click",
                        "name":"联系我们",
                        "key":"contact"
                    }
                ]
            }]
        }';


        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token={$ACCESS_TOKEN}";

        $menuData = $check->httpsRequest($url, $data);
        var_dump($menuData);
    }

    public function responseMsg()
    {

        //get post data, May be due to the different environments
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        //$postStr = file_get_contents("php://input");

        //extract post data
        if (!empty($postStr)) {

            libxml_disable_entity_loader(true);
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $RX_TYPE = trim($postObj->MsgType);

            switch($RX_TYPE) {
                case "text":
                    $resultStr = $this->handleText($postObj);
                    break;
//                case "image":
//                    $resultStr = $this->transmitService($postObj);
//                    break;
                case "event":
                    $resultStr = $this->receiveEvent($postObj);
                    break;
                default:

//                    require 'customerService.php';

//                    $cs = new customerService();

//                    $cs->logMessage('yikab', $postObj->FromUserName, $postObj->ToUserName);

//                    $resultStr = $this->transmitService($postObj);
                    $resultStr = "Unknow msg type: ".$RX_TYPE;
                    break;
            }

            echo $resultStr;

        }else {
            echo "";
            exit;
        }

    }

    public function handleText($postObj)
    {
        $fromUsername = $postObj->FromUserName;
        $toUsername = $postObj->ToUserName;
        $keyword = trim($postObj->Content);
        $time = time();
        $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[%s]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                    <FuncFlag>0</FuncFlag>
                    </xml>";

        if(!empty( $keyword )) {


//            if (strstr($keyword, 'kf') || strstr($keyword, '客服') || strstr($keyword, '你好') || strstr($keyword, '在吗') || strstr($keyword, '名片') || strstr($keyword, '印') ) {
//
////            $result = $this->transmitService($postObj);
//
//            } else {

            $msgType = "text";

            switch($keyword) {
                case "绑定":
                    $userOpenId = $postObj->FromUserName;

                    $contentStr = "内部消息推送\n\n<a href='http://oa.skyprint.cn/wechat/staticPage/wechatSign.php?openid=$userOpenId'>点此绑定oa账号</a>";
                    break;

                default:
                    $contentStr = "有问题，找客服 : )\n\n1. 客服热线: 021-51096119     021- 51098805 \n\n2. 客服QQ: 800066272";

                    break;
            }

            $result = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);

//            }

            return $result;
        } else {
            return "Input something...";
        }
    }

    private function receiveEvent($object)
    {
        //$check = new checkRequest();
        //$act = new wechatActions('pod');
        //$contentStr = "";
        $userOpenId = $object->FromUserName;

        switch ($object->Event)
        {
            case "subscribe":

//                $contentStr = "感谢关注易卡吧服务号\n\n<a href='http://m.yikaba.cn/applySamples'>戳我申请样品</a>";
                $contentStr = "欢迎您关注印艺天空官方服务号\n在这里您可以进行如下操作\n1.查看你的个人账户\n2.修改您的个人密码\n3.查看您最近的订单详情\n4.获得更多关于我们的资讯\n祝您使用愉快：）";
//                header("Location : http://oa.yikayin.com/wechatSign.php?openid=$userOpenId");
                break;

            case "unsubscribe":

                $contentStr = '';
                break;
            case "SCAN":

                $contentStr = '';
                break;
            case "CLICK":
                switch ($object->EventKey)
                {
                    case "trouble":
                        $contentStr = "有问题，找客服 : )\n\n客服QQ: 800066272";
                        break;
                    case "contact":
                        $contentStr = "遇到问题请联系我们的客服人员: ）\n客服人员是时常在线的哦: ）\n\n联系电话：021-51096119     021- 51098805";
                        break;
                    case "publish":
                        $contentStr = [
                            0=>[
                                "Title"=>"一点一滴做服务 全心全意为客户（三）",
                                "Description" => "9月5日的北京，秋雨飒飒，印艺天空举行一场有关\"客户服务和生产品质管理等\"的系列培训课程，培训课程由总部运营中心：高级经理助理李雪融女士和王亚女士主讲。",
                                "PicUrl"=>"http://oa.skyprint.cn/images/640.jpg",
                                "Url" => "http://oa.skyprint.cn/wechat/staticPage/service.php"
                            ]
                        ];
                        break;
                    default:
                        $contentStr = "有问题，找客服 : )\n\n客服QQ: 800066272";
                        break;
                }
                break;
            default:
                $contentStr = "Unknow Event.";
                break;

        }

        if (is_array($contentStr)) {
            $resultStr = $this->responseNews($object, $contentStr);
        }else {
            $resultStr = $this->responseText($object, $contentStr);
        }

        return $resultStr;
    }

    private function receiveImage($object)
    {
//        $contentStr = "/:ok收到！\n \n亲，请先完成注册，方便我们为您服务。\n \n名片制作完成后我们将通知您，然后点击《我的易卡》确认您的模板。\n \n看得不够清楚？\n通过电脑访问http://www.yikayin.com";
//        $userOpenId = $object->FromUserName;
//        $userImageId = $object->MediaId;
//        global $ACCESS_TOKEN;
//        $userInfoOrigin = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$ACCESS_TOKEN}&openid={$userOpenId}&lang=zh_CN";
//        $userInfoContent = file_get_contents($userInfoOrigin);
//        $userInfo = json_decode($userInfoContent);
//        print_r($userInfo);
//        echo $userInfo->nickname;
//        $userNickname = $userInfo->nickname;
//
//        //$imageUrl = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token={$ACCESS_TOKEN}&media_id={$userImageId}";
//
//
//        //require_once "wechatToMysql.php";
//        $resultStr = $this->responseText($object, $contentStr);
        //return $resultStr;
    }

    private function transmitImage($object, $imageArray)
    {
        $itemTpl = "<Image>
                    <MediaId><![CDATA[%s]]></MediaId>
                    </Image>";

        $item_str = sprintf($itemTpl, $imageArray['MediaId']);

        $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[image]]></MsgType>
                    $item_str
                    </xml>";

        $resultStr = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time());
        return $resultStr;
    }

    private function transmitService($object)
    {
        $xmlTpl = "<xml>
                   <ToUserName><![CDATA[%s]]></ToUserName>
                   <FromUserName><![CDATA[%s]]></FromUserName>
                   <CreateTime>%s</CreateTime>
                   <MsgType><![CDATA[transfer_customer_service]]></MsgType>
                   </xml>";
        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    private function responseText($object, $content, $flag = 0)
    {
        $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[text]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                    <FuncFlag>%d</FuncFlag>
                    </xml>";
        $resultStr = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content, $flag);
        return $resultStr;
    }

    private function responseNews($object, $newsArray)
    {
        if(!is_array($newsArray)) {
            return false;
        }
        $itemTpl = "<item>
                    <Title><![CDATA[%s]]></Title>
                    <Description><![CDATA[%s]]></Description>
                    <PicUrl><![CDATA[%s]]></PicUrl>
                    <Url><![CDATA[%s]]></Url>
                    </item>";
        $item_str = "";
        foreach ($newsArray as $item) {
            $item_str .= sprintf($itemTpl, $item['Title'], $item['Description'], $item['PicUrl'], $item['Url']);
        }
        $newsTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[news]]></MsgType>
                    <Content><![CDATA[]]></Content>
                    <ArticleCount>%s</ArticleCount>
                    <Articles>$item_str</Articles>
                    </xml>";

        $resultStr = sprintf($newsTpl, $object->FromUserName, $object->ToUserName, time(), count($newsArray));
        return $resultStr;
    }

}