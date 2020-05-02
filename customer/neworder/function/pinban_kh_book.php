<meta charset="utf-8" />
<?

ini_set("upload_max_filesize", "15000m");
error_reporting(E_ALL ^ E_DEPRECATED&E_ALL ^ E_NOTICE);
//屏蔽错误
require "function/public.php";
require "function/func_pinban.php";
include '../../../commonfile/log.php';

//拼版函数导入
require('../lib/fpdi.php');
require "../JDF/function/conn.php";
$bh=$_GET['ddh'] ? $_GET['ddh'] : 'aaa';
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
$sl1 = $_POST["sl"]?$_POST["sl"]:0;       //数量
$jg1 = $_POST['jg']?$_POST['jg']:0;
$n1="封面";
$productname='书本';
//$chicun='A3';
//$color1='彩色';
$jldw1='P';


$materialid2 = $_POST['material2'] ?  $_POST['material2'] : 1;       //纸张种类
$dsm2 = $_POST['dsm2'];                    //单双面
$machine2 = $_POST["machine2"];            //机器
$sl2 = $_POST["sl2"]?$_POST["sl2"]:0;      //数量
$jg2 = $_POST['jg2']?$_POST['jg2']:0;
$n2="内页";
$jldw2='P';

//bleed
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
    echo "<script type='text/javascript'>alert('请上传文件！');location.href='../newbook.php?ddh=$bh'</script>";
    exit;
}
$filearr = scandir($unzipdir);	// 列出指定路径中的文件和目录

$c=count($filearr);


//书本骑马钉拼版
if($_POST['pb_type1'] == 'staple'){

    $is_binding = false;
    $code = book_staple_pb($unzipdir, $bh, $pname.$bh ,$machine1 , $is_binding , $cx ,$conn);

    if($code != 'done'){

        echo "<script type = 'text/javascript'>alert('$code');location.href='../newbook.php?ddh=$bh'</script>";
        deldir($unzipdir);
        exit;
    }
    deldir($unzipdir);

}elseif($_POST['pb_type1'] == 'binding'){ //书本拼版胶装staple

    $is_binding = true;
    $code = book_staple_pb($unzipdir,$bh,  $pname.$bh ,$machine1 , $is_binding , $cx ,$conn);

    if($code != 'done'){


        echo "<script type = 'text/javascript'>alert('$code');location.href='../newbook.php?ddh=$bh'</script>";
        deldir($unzipdir);
        exit;
    }
    deldir($unzipdir);

}elseif($_POST['pb_type1'] == 'bind_normal'){ //书本拼版胶装normal

    $code = book_binding_pb($unzipdir,$bh,  $pname.$bh ,$machine1 ,$cx, $conn);

    if($code != 'done'){

        echo "<script type = 'text/javascript'>alert('$code');location.href='../newbook.php?ddh=$bh'</script>";
        deldir($unzipdir);
        exit;
    }
    deldir($unzipdir);

}else{//单张


}


//pinbanend

$outfilename1 = substr($bh, -6) . "-1.pdf";
$outfilename2 = substr($bh, -6) . "-2.pdf";

$path=substr($bh, -6);

if (!file_exists("BillFiles/".$path)) {

    @mkdir("BillFiles/".$path);
}

$file1 = substr($bh, -6) . '-1';           //文件名
$file2 = substr($bh, -6) . '-2';           //文件名

$estr="gswin\\gswin32c.exe -dBATCH -dNOPAUSE -dFirstPage=1 -dAlignToPixels=0 -dGridFitTT=0 -sDEVICE=jpeg -dTextAlphaBits=4 -dGraphicsAlphaBits=4 -sOutputFile=BillFiles/{$path}/{$outfilename1}%d.jpg -dJPEGQ=100 -r100x100 -q ../server/files/{$outfilename1} -c quit";
$ss="";$si=0;
exec($estr, $ss, $si);

$estr="gswin\\gswin32c.exe -dBATCH -dNOPAUSE -dFirstPage=1 -dAlignToPixels=0 -dGridFitTT=0 -sDEVICE=jpeg -dTextAlphaBits=4 -dGraphicsAlphaBits=4 -sOutputFile=BillFiles/{$path}/{$outfilename2}%d.jpg -dJPEGQ=100 -r100x100 -q ../server/files/{$outfilename2} -c quit";
$ss="";$si=0;
exec($estr, $ss, $si);


$file1=urlencode($file1);
$file2=urlencode($file2);
if($_SESSION['GDWDM']=='330100'){
    $file1="http://59.110.17.13/ordersys/customer/neworder/server/files/".$file1.".pdf";
    $file2="http://59.110.17.13/ordersys/customer/neworder/server/files/".$file2.".pdf";
}else{
    $file1="http://oa.skyprint.cn/customer/neworder/server/files/".$file1.".pdf";
    $file2="http://oa.skyprint.cn/customer/neworder/server/files/".$file2.".pdf";
}





$sql = "update order_mxqt set file1='$file1', dsm1='$dsm1' , sl1 = $sl1 ,paper1 = $materialid , machine1= '$machine1' , pname = '$pname' , file2='$file2' , dsm2='$dsm2' , sl2 = $sl2 ,paper2 = $materialid2 , machine2= '$machine2'  where id=$mxid";
/*$loge = new Log();
$loge -> INFO($sql);*/


@mysql_query($sql, $conn);


//    update dje
include '../../commonfunc/syncroPrice.php';

header("Location:http://oa.skyprint.cn/customer/neworder/newbook.php?ddh=$bh");

?>