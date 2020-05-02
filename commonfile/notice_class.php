<?
class notification {

    const XTOKEN = 'GYCCO@namecardio#88382383#WINNER';

    public static function sendText($toUsers, $content) {

        $data = [
            'type' => 'text',
            'toUsers' => $toUsers,
            'content' => $content
        ];

        self::httpsRequest(self::getPostUrl(), $data);
    }

    public static function sendTextCard($toUsers, $title, $content, $url) {

        $data = [
            'type' => 'textcard',
            'toUsers' => $toUsers,
            'title' => $title,
            'content' => $content,
            'url' => $url
        ];

        self::httpsRequest(self::getPostUrl(), $data);
    }

    public static function getPostUrl() {

        $signatures = self::createSignature();

        return 'http://api.yikayin.com/notifications/workWechat?signature=' . $signatures['signature'] . '&nonce=' . $signatures['nonce'];
    }

    public static function createSignature() {

        $nonce = self::getNonceStr();
        $signature = sha1(md5(self::XTOKEN) . $nonce);

        return [
            'nonce' => $nonce,
            'signature' => $signature
        ];
    }

    public static function getNonceStr() {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $noceStr = "";
        for ($i = 0; $i < 32; $i++) {
            $noceStr .= $chars[ mt_rand(0, strlen($chars) - 1) ];
        }
        return $noceStr;
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