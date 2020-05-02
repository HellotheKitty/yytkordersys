<meta charset="utf-8" />
<?
ini_set("upload_max_filesize", "15000m");
error_reporting(E_ALL ^ E_DEPRECATED&E_ALL ^ E_NOTICE);

require "function/public.php";

//拼版函数导入
require('../lib/fpdi.php');
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
    echo "<script>alert('请上传文件！');location.href='../newpiece.php?ddh=$bh&mxid=$mxid'</script>";
    exit;
}
//$filearr = scandir($unzipdir);	//  列出指定路径中的文件和目录

//$materialid = $_GET['materialid'];       //纸张大小
$dsm1 = $_GET['dsm'] ? $_GET['dsm']:'';                 //单双面

$paper1 = $_GET['material']?$_GET['material']:1;        //纸张类型
$sczzres = mysql_query("select MaterialName , memo from material where id= $paper1",$conn);
if(!strstr(mysql_result($sczzres,0,'MaterialName'),'自带')){

    $sczzbh1 = mysql_result($sczzres,0,'memo');

    $pname = mysql_result($sczzres,0,'MaterialName');

}else{

    $sczzbh1 = $_GET["pname"] ?$_GET["pname"] :'';                 //印件名 生产纸张编

    $res = mysql_query("select zzmc from base_zz_ck where scbh = '$sczzbh1' and scbh <>'' ",$connykgf);
    if(mysql_num_rows($res)>0)
        $pname = mysql_result($res,0,'zzmc');
    else
        $pname = '';

}
/*if($sczzbh1==''){
    echo "<script>alert('未找到承印物！');location.href='../newpiece.php?ddh=$bh&mxid=$mxid'</script>";
    exit;
}*/


$machine1 = $_GET["machine"]?$_GET["machine"]:'Hp10000彩色';            //机器

/*if($machine1=='Hp彩色'){
    $hzx1 = '纵向';
}elseif($hzx1=='Hp10000彩色'){
    $hzx1 = '横向';
}*/
$hzx1 = $_GET['hzx']?$_GET['hzx']: '横向';

//$pnum1 = count($filearr) - 2;             //页数
$sl1 = $_GET["sl"]?$_GET["sl"]:0;                       //数量
$jg1 = $_GET['jg']?$_GET['jg']:0;
$pnum1=$_GET['pnum']?$_GET['pnum']:0; //p数

$n2 = '';
$productname='单张';
$chicun='A3';
$color1='彩色';
$jldw1='P';


//pdf文件
$outfilename = substr($bh, -6).".pdf";
$path=substr($bh, -6);
//pdf预览
//if (!file_exists("BillFiles/".$path)) {
//    @mkdir("BillFiles/".$path);
//}

//    传的是 pdf
    /*$pdf = new fpdi();

    $pageCount = $pdf -> setSourceFile($unzipdir.$filearr[2]);

    $pnum1 = $pageCount;
    $templateId = $pdf->importPage(1);
    // get the size of the imported page
    $size = $pdf->getTemplateSize($templateId);*/

    // create a page (landscape or portrait depending on the imported page size)
//    if ($size['w'] > $size['h']) {
//        $pdf->AddPage('L', array($size['w'], $size['h']));
//    } else {
//        $pdf->AddPage('P', array($size['w'], $size['h']));
//    }

//    copy
//    $aimurl = "../server/files/".$outfilename;
//    copy($unzipdir.$filearr[2],$aimurl);


$countmx = mysql_result(mysql_query("select count(id) from order_mxqt where ddh = $bh",$conn),0,0);


$file1 = substr($bh, -6).'-'.$countmx;           //文件名
//deldir($unzipdir);
$n1="单张";
//$paper1="2";
//
//$estr="gswin\\gswin32c.exe -dBATCH -dNOPAUSE -dFirstPage=1 -dAlignToPixels=0 -dGridFitTT=0 -sDEVICE=jpeg -dTextAlphaBits=4 -dGraphicsAlphaBits=4 -sOutputFile=BillFiles/{$path}/{$outfilename}%d.jpg -dJPEGQ=100 -r100x100 -q ../server/files/{$outfilename} -c quit";
//$ss="";$si=0;
//exec($estr, $ss, $si);

//$file1=urlencode($file1);
//$file1="http://oa.skyprint.cn/customer/neworder/server/upload/".$ddh.'/'.$mxid.'/'.$file1.".pdf";
include '../../commonfunc/savefilename.php';
$sql = "update order_mxqt set file1 = '$file1' , paper1= $paper1 , machine1 = '$machine1' , dsm1 = '$dsm1' ,hzx1 = '$hzx1',pnum1=$pnum1,sl1=$sl1 ,jg1=$jg1,pname='$pname',sczzbh1='$sczzbh1' where id = '$mxid'";


@mysql_query($sql, $conn);

//$je = $pnum1*$sl1*$jg1;
//$sql2 = "update order_mainqt set dje=dje+$je where ddh='$bh'";
// 更新订单金额，构件时，只需要确定文件名的序号即可，如2345-1.pdf、2345-2.pdf
//@mysql_query($sql2, $conn);
include '../../commonfunc/syncroPrice.php';

//修改后加工和覆膜尺寸

header("Location:http://oa.skyprint.cn/customer/neworder/newpiece.php?ddh=$bh&mxid=$mxid");

?>