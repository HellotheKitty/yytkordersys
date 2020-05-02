<?php
/**
 * Created by PhpStorm.
 * User: GyCCo
 * Date: 7/21/15
 * Time: 10:53 AM
 */

define('XTOKEN', 'GYCCO@namecardio#88382383#WINNER');

define("TOKEN", "skyprintoanotice");

define("APP_ID", "wxcf4e8ccae60b2de7");

define("APP_SECRET", "9a4a3de32aecad2bb7c17b431be44fde");

define("YIKA_APP_ID", "wx454b621efd02da72");
define("YIKA_APP_SECRET", "e6fd0bc6399ebbc88ff185b6b747f4c6");

define('OSS_PATH', 'http://www.yikayin.com/pmc/ossimg.php?object=');
define('YIKAYIN_ROOTPATH', 'http://www.yikayin.com/');
define('YIKABA_IMG_PATH', 'http://www.yikaba.cn/yikaba/showfile/');



class checkRequest {

    public function checkSignature($signature, $nonce) {
        $temp = sha1(md5(XTOKEN).$nonce);
        if ($temp == $signature) {
            return true;
        } else {
            return false;
        }
    }

    public static function getNonceStr() {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $noceStr = "";
        for ($i = 0; $i < 32; $i++) {
            $noceStr .= $chars[ mt_rand(0, strlen($chars) - 1) ];
        }
        return $noceStr;
    }

    public function trimAll($str) {
        $newStr = trim($str);
        $newStr = ltrim($newStr);
        return $newStr;
    }

    public function httpsRequest($url, $data = null) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, TRUE);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        $output = curl_exec($curl);

        if($output) {
            curl_close($curl);
            return $output;
        } else {
            $error = curl_errno($curl);
            curl_close($curl);
            throw new wechatException("curl error, errCode: $error");
        }

    }

    public static function getIP() {

        if (isset($_SERVER)) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $realip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $realip = $_SERVER['HTTP_CLIENT_IP'];
            } else {
                $realip = $_SERVER['REMOTE_ADDR'];
            }
        } else {
            if (getenv("HTTP_X_FORWARDED_FOR")) {
                $realip = getenv( "HTTP_X_FORWARDED_FOR");
            } elseif (getenv("HTTP_CLIENT_IP")) {
                $realip = getenv("HTTP_CLIENT_IP");
            } else {
                $realip = getenv("REMOTE_ADDR");
            }
        }

        return $realip;
    }
}


class baseActions {
    public static function returnCode($msg, $sign = null) {

        if (!$msg) {
            throw new wechatException("code not set");
        }

        if ($sign) {
            $data = '{"msg":"'.$msg.'","sign":"'.$sign.'"}';
        } else {
            $data = '{"msg":"'.$msg.'"}';
        }

        return $data;
    }
}



class wechatException extends Exception {
    public function errorMessage()
    {
        return $this->getMessage();
    }
}




