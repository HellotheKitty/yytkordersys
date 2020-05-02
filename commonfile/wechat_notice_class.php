<?
class wechat_notice {

    public static function send_temp_gongdan($toUsers,$gongdantype, $content,$remark=null,$first=null,$linkurl=null) {

        $dataarr = [
            'keyword1' => $gongdantype,
            'keyword2' => $content,
            'remark' => $remark
        ];
        $postform = json_encode($dataarr);

        $data = [
            'token' => md5('yika999'),
            'tempid' =>'BZMa691ug5N-FTJgmbrOvJO2p4fk5gzzpk_Q80aGJLw',
            'first' => $first,
            'touser' => $toUsers,
            'linkurl' => $linkurl,
            'postform' => $postform

        ];

        $return = self::httpsRequest(self::getPostUrl(), $data);
        return $return;
    }
    public static function send_temp_neworder($tousers ,$first,$ddh,$ddate,$remark, $linkurl=null ){

        $dataarr = [
            'first' => $first,
            'keyword1' => $ddh,
            'keyword2' => $ddate,
            'remark' => $remark
        ];
        $postform = json_encode($dataarr);
        $data = [
            'token' => md5('yika999'),
            'tempid' => 's9ddKtOqG5TfUz1ST8dmd7zYkIOaDWHVJ2Ue2-wg69Q',
            'linkurl' => $linkurl,
            'touser' => $tousers,
            'postform' => $postform

        ];

        $return = self::httpsRequest(self::getPostUrl(),$data);
        return $return;
    }

    public static function send_temp_balancesettle($touser,$first,$ddh,$dje,$ye,$sdate,$remark=null,$linkurl=null){

        $dataarr = [
            'first' => $first,
            'keyword1' => $ddh,
            'keyword2' => $dje,
            'keyword3' => $sdate,
            'remark' => $remark
        ];

        $postform = json_encode($dataarr);

        $data = [
            'tempid' => 'wPcenAFZ7dem4PVTl0zcGEmDc4SldPA_jAIRmnrRUu4',
            'linkurl' => $linkurl,
            'touser' => $touser,
            'usertype' => 'kh',
            'postform' => $postform
        ];

        $return = self::httpsRequest(self::getPostUrl(),$data);
        return $return;
    }

    public static function send_temp_consume($touser,$first,$ddh,$dje,$ye,$ddate,$remark=null,$linkurl=null){

        $dataarr = [
            'first' => $first,
            'keyword1' => $ddate,
            'keyword2' => $dje,
            'keyword3' => $ddh,
            'keyword4' => $ye,
            'remark' => $remark
        ];

        $postform = json_encode($dataarr);

        $data = [
            'tempid' => 'oohdh6t80kdEeDlocgEV-znPMUckLGYMZqkNGkKEovE',
            'linkurl' => $linkurl,
            'touser' => $touser,
            'usertype' => 'kh',
            'postform' => $postform
        ];

        $return = self::httpsRequest(self::getPostUrl(),$data);
        return $return;
    }

    public static function send_temp_topup($touser,$first,$czje,$ye,$remark){

        $dataarr = [
            'first' => $first,
            'keyword1' => $czje,
            'keyword2' => $ye,
            'remark' => $remark
        ];

        $postform = json_encode($dataarr);

        $data = [
            'tempid' => 'S0L8iJ7KMU8gI6CjyR6-JgM9sO4xnZVUn9BAnZxvjak',
            'touser' => $touser,
            'usertype' => 'kh',
            'postform' => $postform
        ];

        $return = self::httpsRequest(self::getPostUrl(),$data);
        return $return;
    }

    public static function send_temp_finance_change($touser,$first,$ddate,$czje,$ye,$bdyy,$remark){

        $dataarr = [
            'first' => $first,
            'keyword1' => $touser,
            'keyword2' => $ddate,
            'keyword3' => $czje,
            'keyword4' => $ye,
            'keyword5' => $bdyy,
            'remark' => $remark
        ];

        $postform = json_encode($dataarr);

        $data = [
            'tempid' => 'Mq2H2kg6tsaRIz8QEM3POn1vVSyH7VtFPdSmCFOKFTg',
            'touser' => $touser,
            'usertype' => 'kh',
            'postform' => $postform
        ];

        $return = self::httpsRequest(self::getPostUrl(),$data);
        return $return;
    }

    public static function send_temp_statistic($tousers,$first){


    }

    public static function send_temp_diynotice(){

    }

    public static function send_temp_ordernotice(){


    }


    public static function send_temp_ordercomplecated($touser,$first,$ddh,$ddate,$remark){

        $dataarr = [
            'first' => $first,
            'keyword1' => $ddh,
            'keyword2' => $ddate,
            'remark' => $remark
        ];

        $postform = json_encode($dataarr);

        $data = [
            'tempid' => 'bqIUllEK74McOheSv-gFgVgXG7E-J7VQ9yTrhvs_kb0',
            'touser' => $touser,
            'usertype' => 'kh',
            'postform' => $postform
        ];

        $return = self::httpsRequest(self::getPostUrl(),$data);
        return $return;
    }

    public static function send_temp_approval(){

    }

    public static function getPostUrl() {

        return 'http://oa.skyprint.cn/wechat/sendMsg.php';
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
