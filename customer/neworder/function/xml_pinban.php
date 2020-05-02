<meta charset="utf-8" />
<?
ini_set("upload_max_filesize", "15000m");
error_reporting(E_ALL ^ E_DEPRECATED&E_ALL ^ E_NOTICE);

require "function/public.php";

require "../JDF/function/conn.php";
require '../../inc/connykgf.php';
$bh=$_GET['ddh'];
$double=$_GET['dsm'];
$dir = '../server/upload/';
//GET
$ddh = $bh;                               //单号
$mxid = $_GET['mxid'];

$page_num=1;
$unzipdir = $dir.$bh.'/'.$mxid.'/';
if(!is_dir($unzipdir)){
    echo "<script>alert('请上传文件！');location.href='../newupload.php?ddh=$bh&mxid=$mxid'</script>";
    exit;
}
$filearr = scandir($unzipdir);	//  列出指定路径中的文件和目录

$dsm1 = $_GET['dsm'] ? $_GET['dsm']:'';                 //单双面
$sided = 'single';
if($dsm1=='单面'){
    $sided = 'single';
}elseif($dsm1=='双面'){
    $sided = 'double';

}
$bindtype = $_GET['bindtype'];

$machine1 = $_GET["machine"]?$_GET["machine"]:'Hp10000彩色';            //机器
if($machine1=='Hp彩色'){
    $specs = '464*320';
}elseif($machine1 == 'Hp10000彩色'){
    $specs = '750*530';
}

//$paper1 = $_GET['material']?$_GET['material']:1;        //纸张类型
//$pnum1 = count($filearr) - 2;             //页数
$sl1 = $_GET["sl"]?$_GET["sl"]:0;                       //数量
//$jg1 = $_GET['jg']?$_GET['jg']:0;
//$pnum1=$_GET['pnum']?$_GET['pnum']:0; //p数

$n2 = '';
$productname='单张';
$chicun='A3';
$color1='彩色';
$jldw1='P';


//pdf文件
$outfilename = substr($bh, -6).".pdf";
$path=substr($bh, -6);


$countmx = mysql_result(mysql_query("select count(id) from order_mxqt where ddh = $bh",$conn),0,0);


$file1 = substr($bh, -6).'-'.$countmx;           //文件名
//deldir($unzipdir);
$n1="单张";
$filexml = $unzipdir.'xml/'.$file1.'.xml';

$file1=urlencode($file1);
$file1="http://oa.skyprint.cn/customer/neworder/server/upload/".$ddh.'/'.$mxid.'/xml/'.$file1.".xml";

//----生成xml
$filedir = "http://oa.skyprint.cn/customer/neworder/server/upload/".$ddh.'/'.$mxid .'/';

$filenamelist = "";

for($a = 2;$a<count($filearr);$a++){

    if($filearr[$a]=='xml'){
        continue;
    }
    $filenamelist .= "<f".($a-1).">".$filearr[$a]."</f".($a-1).">";
}
/*
 * <files>
        <fileDir url=\"$filedir\">
        </fileDir>
        <fileNameList>
            ".$filenamelist."
        </fileNameList>
    </files>*/
$text = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<configs>

    <specs value=\"$specs\">
    </specs>
    <amount value=\"$sl1\">
    </amount>
    <sided value=\"$sided\">
    </sided>
    <binding type=\"$bindtype\">
    </binding>
</configs>";

file_put_contents($filexml,$text,FILE_APPEND);

//---xml end

$sql = "update order_mxqt set file1 = '$file1'  , machine1 = '$machine1' , dsm1 = '$dsm1' where id = '$mxid'";


@mysql_query($sql, $conn);

include '../../commonfunc/syncroPrice.php';

//修改后加工和覆膜尺寸

header("Location:http://oa.skyprint.cn/customer/neworder/newupload.php?ddh=$bh&mxid=$mxid");

?>