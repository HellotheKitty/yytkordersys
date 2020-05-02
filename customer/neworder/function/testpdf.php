<?

require_once('../lib/fpdi.php');
header("Content-type: text/html; charset=utf-8");
$filename=$_GET["filename"];

$pdf = new FPDI();

$pageCount = $pdf->setSourceFile($filename);
touch('ck.txt');
$cmd="pdftotext.exe ".$filename." ck.txt -q";

exec($cmd, $output, $return_var);

$myfile = fopen("ck.txt", "r") or die("Unable to open ck file!");
$ss=fgets($myfile,2);
if (ord($ss)==12) echo "OK"; else echo "未转曲";
fclose($myfile);

/*
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://web.yikaba.cn/cardShop/chkpdf.php?filename=".iconv("UTF-8","gbk",$fileName));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
//拿回结果
curl_exec($ch);
$rts =curl_multi_getcontent($ch);
curl_close($ch);
if ($rts <> "OK" && $rts <> "") {
    if (strstr($rts,"compression technique")) {
        echo  $new_file_name."带有压缩，请修改文件";
        exit;
    } else {
        echo  $new_file_name.$rts;
        exit;
    }
    exit;
}*/
?>