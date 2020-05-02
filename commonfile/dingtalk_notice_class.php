<?
class ding_notice {

    public static function send_text($toUsers, $content) {

        $data = [
            'token' => md5('yika999'),
            'event' => 'send_text',
            'agentid' => '54127277',
            'touser' => $toUsers,
            'content' => $content
        ];

        $return = self::httpsRequest(self::getPostUrl(), $data);
        return $return;
    }

    public static function send_card($tousers , $linkurl , $title, $text){

        $data = [
            'token' => md5('yika999'),
            'event' => 'send_card',
            'agentid' => '54127277',
            'link_url' => $linkurl,
            'touser' => $tousers,
            'title' => $title,
            'text' => $text
        ];

        $return = self::httpsRequest(self::getPostUrl(),$data);
        return $return;
    }


    public static function send_oa($tousers ,$msgurl ,$headtext,$bodyform,$bodytitle,$bodycontent){


        $bodyform = json_encode($bodyform);

        $data = [
            'token' => md5('yika999'),
            'event' => 'send_oa',
            'agentid' => '54127277',
            'touser' => $tousers,
            'message_url' => $msgurl,
            'bgcolor' => 'FFFFAEB9',
            'headtext' => $headtext,
            'bodyform' => $bodyform,
            'bodycontent' => $bodycontent,
            'bodytitle' => $bodytitle
        ];

        $return = self::httpsRequest(self::getPosturl(),$data);
        return $return;
    }

    public static function getPostUrl() {

        return 'http://ding.yikayin.com/corp/sendMsg.php';
    }

    public static function httpsRequest($url, $data = null) {
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
            return false;
        }

    }

}
?>