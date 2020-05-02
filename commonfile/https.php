<?php

class https {

    public static function httpsRequest($url, $data = null) {

		$header[] = "Content-type: application/vnd.cip4-jmf+xml";      //content-type
	
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER,$header);
		
        if (!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, TRUE);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        $output = curl_exec($curl);

        //if ($output) {
            curl_close($curl);
            return $output;
        //} else {
        //    $error = curl_errno($curl);
        //    curl_close($curl);
        //    throw new Exception("curl error, errCode: $error");
        //}
    }
}
?>