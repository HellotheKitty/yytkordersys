<meta charset="utf-8" />
<?

ini_set("upload_max_filesize", "15000m");
//error_reporting(E_ALL ^ E_DEPRECATED&E_ALL ^ E_NOTICE);
//屏蔽错误
require "function/public.php";
require "function/func_pinban.php";
include '../../../commonfile/log.php';

//拼版函数导入
require('../lib/fpdi.php');
require "../JDF/function/conn.php";
$bh=$_GET['ddh'];
$double=$_POST['dsm'];
$dir = '../server/upload/';


//post
$ddh = $bh;                               //单号
$mxid = $_POST['mxid']; //明细id
$materialid = $_POST['material'] ?  $_POST['material'] : 1;       //纸张种类
$dsm1 = $_POST['dsm'];                 //单双面
$pname = $_POST["pname"];                 //印件名
$machine1 = $_POST["machine"];            //机器
//$pnum1 = count($filearr) - 2;             //页数
$sl1 = $_POST["sl"] ? intval($_POST["sl"]) : 0;       //数量
$jg1 = $_POST['jg'] ? floatval($_POST['jg']) : 0;
$n1="单张";
$productname='单张';
//$chicun='A3';
//$color1='彩色';
$jldw1='P';
$hzx1="";

$cx = intval($_POST['bleed']);

//生产纸张编号
$sczzbh1 = $_GET['selfpaper_zzbh'];
if($sczzbh1 ==''){

    $res_zzbh = mysql_query("select memo from material where MaterialCode = $materialid",$conn);

    if(mysql_num_rows($res_zzbh)>0){

        $sczzbh1 = mysql_result($res_zzbh , 0 ,'memo');
    }else{
        $logfile = new Log();
        $logfile ->NOTICE($materialid .'没有承印物');
    }
}

$page_num=1;

$unzipdir = $dir.$bh.'/';
if(!is_dir($unzipdir)){
    echo "<script type='text/javascript'>alert('请上传文件！');location.href='../newpb.php?ddh=$bh'</script>";
    exit;
}
$filearr = scandir($unzipdir);	// 列出指定路径中的文件和目录

$c=count($filearr);

//默认上传的文件都是同一个尺寸的
//第一个是否PDF格式 pdf 只单拼
if(substr($filearr[2],-3) =='pdf'){     //    pdf拼版

//    判断压缩和转曲
    for($i=2;$i<$c;$i++){

        $fileName = $unzipdir.$filearr[$i];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://oa.skyprint.cn/customer/neworder/function/testpdf.php?filename=".iconv("UTF-8","gbk",$fileName));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        $rts =curl_multi_getcontent($ch);
        curl_close($ch);

        if ($rts <> "OK" && $rts <> "") {
            if (strstr($rts,"compression technique")) {

//                echo  "请按照pdf/X -3标准保存文件";
//                exit();
                $outpdf_clear =$unzipdir.$i.'clear.pdf';

                $comand = "pdftk $fileName output $outpdf_clear" . " uncompress";
//
                exec($comand);

               unlink($fileName);

            } else {
                echo  $filearr[$i].$rts;
                exit;
            }
        }
    }

//单独拼还是一起拼

    if($_POST['pb_type1'] == 'together'){

        pdf_together_pb_1w($unzipdir,$bh, $pname.$bh , $dsm1 , $machine1 ,$cx, $conn);

    }else{

        pdf_seperate_pb_1w($unzipdir,$bh, $pname.$bh , $dsm1,$machine1 ,$cx, $conn);
    }

    deldir($unzipdir);

}else{ //    图片拼版

    $cp_width = $_POST['cp_width'] ? $_POST['cp_width'] : 0;
    $cp_height = $_POST['cp_height'] ? $_POST['cp_height'] : 0;

    //单独拼还是一起拼
    if($_POST['pb_type1'] == 'together'){

        pic_pinban_together($unzipdir,$bh, $pname.$bh, $machine1,$dsm1 ,$cp_width , $cp_height ,$cx, $conn);

    }else{

        pic_pinban_seperate($unzipdir,$bh, $pname.$bh, $machine1,$dsm1 ,$cp_width , $cp_height,$cx ,$conn);

    }
    deldir($unzipdir);
}

//pinbanend


//savemx
if($layout == 'L'){
    $hzx1 = '横向';
}else{
    $hzx1 = '纵向';
}
//mysql_query("update order_mxqt set hzx1='$hzx1' , pnum1= $page_num1 , pnum2 = $page_num2 where ddh= $bh ",$conn);


$outfilename = substr($bh, -6).".pdf";

$path=substr($bh, -6);

if (!file_exists("BillFiles/".$path)) {

    @mkdir("BillFiles/".$path);
}

$file1 = substr($bh, -6);           //文件名

$estr="gswin\\gswin32c.exe -dBATCH -dNOPAUSE -dFirstPage=1 -dAlignToPixels=0 -dGridFitTT=0 -sDEVICE=jpeg -dTextAlphaBits=4 -dGraphicsAlphaBits=4 -sOutputFile=BillFiles/{$path}/{$outfilename}%d.jpg -dJPEGQ=100 -r100x100 -q ../server/files/{$outfilename} -c quit";
$ss="";$si=0;
exec($estr, $ss, $si);

$file1=urlencode($file1);
if($_SESSION['GDWDM']=='330100'){
    $file1="http://59.110.17.13/ordersys/customer/neworder/server/files/".$file1.".pdf";
}else{
    $file1="http://oa.skyprint.cn/customer/neworder/server/files/".$file1.".pdf";

}

/*$sql = "INSERT INTO `ordersys`.`order_mxqt`
		(`id`, `ddh`, `productname`, `pname`, `chicun`, `sl`, `n1`, `file1`, `machine1`,
		`paper1`, `color1`, `jldw1`, `dsm1`, `hzx1`, `pnum1`, `sl1`, `jg1`, `n2`, `file2`,
		`machine2`, `paper2`, `color2`, `jldw2`, `dsm2`, `hzx2`, `pnum2`, `sl2`, `jg2`, `n3`,
		`file3`, `machine3`, `paper3`, `color3`, `jldw3`, `dsm3`, `hzx3`, `pnum3`, `sl3`, `jg3`,
		`down_type`, `user`, `jdf1`, `jdf2`, `jdf3`, `sczzbh1`, `sczzbh2`, `sczzbh3`)
		VALUES
		(NULL, '$ddh', '$productname', '$pname', '$chicun', '$sl', '$n1', '$file1', '$machine1',
		'$paper1', '$color1', '$jldw1', '$dsm1', '$hzx1', '$pnum1', '$sl1', '$jg1', NULL, NULL, NULL,
		'1', NULL, NULL, NULL, NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL,
		NULL, NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, NULL, NULL)";*/




$sql = "update order_mxqt set file1='$file1',dsm1='$dsm1' , sl1 = $sl1 ,paper1 = $materialid , jg1 = $jg1 ,machine1= '$machine1' , pname = '$pname' , sczzbh1='$sczzbh1' where id=$mxid";

@mysql_query($sql, $conn);


//    update dje
include '../../commonfunc/syncroPrice.php';

header("Location:http://oa.skyprint.cn/customer/neworder/newpb.php?ddh=$bh");

?>