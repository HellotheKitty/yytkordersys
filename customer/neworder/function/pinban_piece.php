<meta charset="utf-8" />
<?
ini_set("upload_max_filesize", "15000m");
error_reporting(E_ALL ^ E_DEPRECATED&E_ALL ^ E_NOTICE);
//屏蔽错误
require "function/public.php";
require "function/pinban_double_double.php";
require "function/pinban_double_same.php";
require "function/pinban_double.php";
require "function/pinban_one.php";
//拼版函数导入
require('../lib/fpdi.php');
require "../JDF/function/conn.php";
$bh=$_GET['ddh'];
$double=$_POST['dsm'];
$dir = '../server/upload/';

//post
$ddh = $bh;                               //单号

$page_num=1;
$unzipdir = $dir.$bh.'/';
if(!is_dir($unzipdir)){
    echo "<script>alert('请上传文件！');location.href='../newpiece.php?ddh=$bh'</script>";
    exit;
}
$filearr = scandir($unzipdir);	// P数 = count($filearr) - 2  列出指定路径中的文件和目录


$mxid = $_POST['mxid'];
//$materialid = $_POST['materialid'];       //纸张大小
$dsm1 = $_POST['dsm'];                 //单双面
$pname = $_POST["pname"];                 //印件名
$machine1 = $_POST["machine"];            //机器
$paper1 = $_POST['material'];        //纸张类型
$pnum1 = count($filearr) - 2;             //页数
$sl1 = $_POST["sl"];                       //数量
$jg1 = $_POST['jg'];
$n2 = '';
$productname='单张';
$chicun='A3';
$color1='彩色';
$jldw1='P';
$hzx1=$_POST["hzx"];


//拼版之后的pdf文件
$outfilename = substr($bh, -6).".pdf";
$path=substr($bh, -6);
//pdf预览
if (!file_exists("BillFiles/".$path)) {
    @mkdir("BillFiles/".$path);
}


if(list($width, $height, $type, $attr) = getimagesize($unzipdir.$filearr[2])){

    $pdfd=new fpdi();
    // 设置
    $pdfd->SetMargins(0,0,0);
    $pdfd->SetAutoPageBreak(1,1);
    $pdfd->AddGBFont();
    $pdfd->Open();
    $pdfd->SetFont('GB','B',8);
    $pdfd->AliasNbPages();

    $width = $width*25.4/300;               // 英寸
    $height= $height*25.4/300;

    if($sl1!='1'){
        if($width<=220&$height<=300){      //多份双拼
            $c=count($filearr);
            for($i = 2; $i < $c; $i++) {     //单拼
                $x=$c-2;
                pinban_two_double($unzipdir, $filearr[$i], $pdfd,$pname.$machine1.$bh.$dsm1.$color1."第：".$page_num."页"."共：".$x."页");
                $page_num++;
                deldir($unzipdir);
            }

        }else{                             //多份单拼
            for($i = 2; $i < count($filearr); $i++) {     //单拼
                $x=count($filearr)+1;
                pinban($unzipdir, $filearr[$i], $pdfd,$pname.$machine1.$bh.$dsm1.$color1."第：".$page_num."页"."共".$x."页");
                $page_num++;

            }
            deldir($unzipdir);
        }
    }else if($sl1=='1'){
        if($width<=220&$height<=300){                    //双拼
            $c=count($filearr);
            $b=(int)($c/2-0.5);
            $b_b=(int)($c/2);
            $b_c=(int)($c/4)+1;
            if($double=='单面'){
                for($i = 1; $i <=$b; $i++) {
                    $a=$i+1;                  //单面
                    $x=$b;
                    if($c%2=='0'){      //偶数
                        pinban_double($unzipdir, $filearr[$a]."A".$filearr[$i+$b_b], $pdfd,$pname.$machine1.$bh.$dsm1.$color1."第：".$page_num."页"."共".$x."页");
                        $page_num++;
                    }else if($c%2=='1'){
                        if($a==$b+1){
                            $a='';
                        }
                        pinban_double($unzipdir, $filearr[$a]."A".$filearr[$i+$b_b], $pdfd,$pname.$machine1.$bh.$dsm1.$color1."第：".$page_num."页"."共".$x."页");
                        $page_num++;
                    }
                }

                deldir($unzipdir);
            }else if($double='双面'){
                $a=0;
                for($i = 1; $i <$b_c+1; $i++) {                  //双面
                    $x1=$b_c;
                    $x2=$b_c+1;
                    pinban_double_double($unzipdir,
                        $filearr[$c-$i*$a]."A".$filearr[2*$i],
                        $filearr[2*$i+1]."B".$filearr[$c-$a*$i-1],
                        $pdfd,$pname.$machine1.$bh.$dsm1.$color1."第：".$page_num."页",
                        "第".$x1."页","第".$x2."页"
                    );
                    $a++;
                    $page_num++;
                }
                deldir($unzipdir);
            }

        }else{
            for($i = 2; $i < count($filearr); $i++) {     //单拼
                $x=count($filearr)-2;
                pinban($unzipdir, $filearr[$i], $pdfd,$pname.$machine1.$bh.$dsm1.$color1."第：".$page_num."页"."共：".$x."页");
                $page_num++;
            }
            deldir($unzipdir);

        }
    }

    outputpdf("../server/files/".$outfilename, $pdfd);

}else{

//    传的是 pdf
    $pdf = new fpdi();

    $pageCount = $pdf -> setSourceFile($unzipdir.$filearr[2]);

    $pnum1 = $pageCount;
    $templateId = $pdf->importPage(1);
    // get the size of the imported page
    $size = $pdf->getTemplateSize($templateId);

    // create a page (landscape or portrait depending on the imported page size)
//    if ($size['w'] > $size['h']) {
//        $pdf->AddPage('L', array($size['w'], $size['h']));
//    } else {
//        $pdf->AddPage('P', array($size['w'], $size['h']));
//    }

//    copy
    $aimurl = "../server/files/".$outfilename;
    copy($unzipdir.$filearr[2],$aimurl);
}


$file1 = substr($bh, -6);           //文件名
//deldir($unzipdir);
$n1="单张";
//$paper1="2";

$estr="gswin\\gswin32c.exe -dBATCH -dNOPAUSE -dFirstPage=1 -dAlignToPixels=0 -dGridFitTT=0 -sDEVICE=jpeg -dTextAlphaBits=4 -dGraphicsAlphaBits=4 -sOutputFile=BillFiles/{$path}/{$outfilename}%d.jpg -dJPEGQ=100 -r100x100 -q ../server/files/{$outfilename} -c quit";
$ss="";$si=0;
exec($estr, $ss, $si);

$file1=urlencode($file1);
$file1="http://oa.skyprint.cn/customer/neworder/server/files/".$file1.".pdf";
$sql = "update order_mxqt set file1 = '$file1' , paper1= '$paper1' , machine1 = '$machine1' , dsm1 = '$dsm1' ,hzx1 = '$hzx1',pnum1=$pnum1,sl1=$sl1 ,jg1=$jg1 where id = '$mxid'";

$filelog = 'log.txt';
file_put_contents($filelog,$sql,FILE_APPEND);
@mysql_query($sql, $conn);

//$je = $pnum1*$sl1*$jg1;
//$sql2 = "update order_mainqt set dje=dje+$je where ddh='$bh'";
// 更新订单金额，构件时，只需要确定文件名的序号即可，如2345-1.pdf、2345-2.pdf
//@mysql_query($sql2, $conn);
include '../../commonfunc/syncroPrice.php';

//修改后加工和覆膜尺寸

header("Location:http://oa.skyprint.cn/customer/neworder/newpiece.php?ddh=$bh")

?>