<?
//session_start();
require("../inc/conn.php");
//include '../commonfile/calc_area.php';

header("Content-type:text/html;charset=utf-8");

require_once '../phpExcel/Classes/PHPExcel.php';
require_once '../phpExcel/Classes/PHPExcel/IOFactory.php';
require_once '../phpExcel/Classes/PHPExcel/Reader/Excel5.php';


$objPHPExcel = new PHPExcel();
$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);


//创建人
$objPHPExcel->getProperties()->setCreator("yytk");
//修改人
$objPHPExcel->getProperties()->setLastModifiedBy("yytk");
//标题
$objPHPExcel->getProperties()->setTitle("yytk");
//题目
$objPHPExcel->getProperties()->setSubject("yytk");
//描述
$objPHPExcel->getProperties()->setDescription("yytk data back");
//关键字
$objPHPExcel->getProperties()->setKeywords("office yytk php");
//种类
$objPHPExcel->getProperties()->setCategory("Test result file");

//设置当前sheet
$objPHPExcel -> setActiveSheetIndex(0);
//设置sheet的name
$objPHPExcel -> getActiveSheet() -> setTitle('sheet1');

//data


if(!empty($_GET['seldw1'])){
    $seldw = $_GET['seldw1'];

    if($seldw <> 3400){

        $seldw = substr($seldw,0,4);
        $dwdmStr = "('$seldw')";
    }else{
        $dwdmStr = "('3401','3402','3403','3404','3405')";

    }
}


if(!empty($_GET['seldw2'])){
    $seldw = $_GET['seldw2'];

    if($seldw == 3000){

        $dwdmStr = "('3401','3402','3403','3404','3405','3301')";

    }elseif($seldw == 'bj'){

        $dwdmStr = "('3401','3402','3403','3404','3405')";
    }elseif($seldw == 'sh'){
        $dwdmStr = "('3301')";
    }
}

$dw0 = dw0($_SESSION['GDWDM']);
if(empty($_GET['seldw1'])&&empty($_GET['seldw2'])){

    if(strlen($dw0) ==4){
        //    门店级
        $seldw = substr($_SESSION['GDWDM'],0,4);
        $dwdmStr = "('$seldw')";
    }elseif(strlen($dw0) ==2){
//        区域
        if($dw0=='33'){
            $dwdmStr = "('3301')";

        }elseif($dw0=='34'){
            $dwdmStr = "('3401','3402','3403','3404','3405')";
        }

    }



}

$tj = "(gdzk in $dwdmStr) ";

if(empty($_GET['seldw1']) && empty($_GET['seldw2']) &&strlen($dw0) ==1){
//    total
    $tj = " 1=1 ";
}
if ($dwdm == '3405')
    $tj = "(gdzk='3405' or (gdzk like '340%' and khmc like '%外协%') or waixie = '3405') ";
//if ($_GET["zdm"]<>"") {$tj=$tj." and (khmc like '%".$_GET["zdm"]."%' or mpzh like '%".$_GET["zdm"]."%' or lxr like '%".$_GET["zdm"]."%')";}
if ($_GET["zdm"] <> "") {
    $tj = $tj . " and (khmc like '%" . urldecode($_GET["zdm"]) . "%' or mpzh like '%" . urldecode($_GET["zdm"]) . "%' or lxr like '%" . urldecode($_GET["zdm"]) . "%')";
}

    $res = mysql_query("select base_kh.*,user_zhjf.ye from base_kh left join user_zhjf on base_kh.khmc = user_zhjf.depart where $tj order by id", $conn);

//    $sql = "select base_kh.*,user_zhjf.ye from base_kh left join user_zhjf on base_kh.khmc = user_zhjf.depart where $tj order by id";
//$file = '../mainb/log.txt';
//    $f = file_put_contents($file,$sql,FILE_APPEND);


//设置单元格的值
$objPHPExcel -> getActiveSheet() -> setCellValue('A1','编号');
$objPHPExcel -> getActiveSheet() -> setCellValue('B1','客户名称');
$objPHPExcel -> getActiveSheet() -> setCellValue('C1','客户级别');
$objPHPExcel -> getActiveSheet() -> setCellValue('D1','联系人');
$objPHPExcel -> getActiveSheet() -> setCellValue('E1','联系电话');
$objPHPExcel -> getActiveSheet() -> setCellValue('F1','联系地址');
$objPHPExcel -> getActiveSheet() -> setCellValue('G1','账户余额');
$objPHPExcel -> getActiveSheet() -> setCellValue('H1','待付款');
//$objPHPExcel -> getActiveSheet() -> setCellValue('I1','配送要求');
//$objPHPExcel -> getActiveSheet() -> setCellValue('J1','生产地');
//$objPHPExcel -> getActiveSheet() -> setCellValue('K1','订单状态');
//$objPHPExcel -> getActiveSheet() -> setCellValue('L1','备注');

for($i=0;$i<mysql_num_rows($res);$i++) {

    $objPHPExcel->getActiveSheet()->setCellValue('A' . ($i+2), mysql_result($res, $i, 'mpzh'));
    $objPHPExcel->getActiveSheet()->setCellValue('B' . ($i+2), mysql_result($res, $i, 'khmc'));
    $objPHPExcel->getActiveSheet()->setCellValue('C' . ($i+2), mysql_result($res, $i, 'hyjb'));
    $objPHPExcel->getActiveSheet()->setCellValue('D' . ($i+2), mysql_result($res, $i, 'lxr'));
    $objPHPExcel->getActiveSheet()->setCellValue('E' . ($i+2), mysql_result($res, $i, 'lxdh'));
    $objPHPExcel->getActiveSheet()->setCellValue('F' . ($i+2), mysql_result($res, $i, 'lxdz'));
    $objPHPExcel->getActiveSheet()->setCellValue('G' . ($i+2), mysql_result($res, $i, 'ye')<0?0:mysql_result($res, $i, 'ye'));
    $objPHPExcel->getActiveSheet()->setCellValue('H' . ($i+2), mysql_result($res, $i, 'ye')>0?0:mysql_result($res, $i, 'ye'));
    $objPHPExcel->getActiveSheet()->getStyle('H' . ($i+2))->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
//    $objPHPExcel->getActiveSheet()->setCellValue('I' . ($i+2), mysql_result($res, $i, 'psfs'));
//    $objPHPExcel->getActiveSheet()->setCellValue('H' . ($i+2), mysql_result($res, $i, 'scjd'));
//    $objPHPExcel->getActiveSheet()->setCellValue('K' . ($i+2), mysql_result($res, $i, 'state'));
//    $objPHPExcel->getActiveSheet()->setCellValue('L' . ($i+2), mysql_result($res, $i, 'memo'));

}
ob_end_clean();//清除缓冲区,避免乱码
header("Prama:pubic");
header("expires:0");
header("Cache-Control:must-revalidate,post-check=0,pre-check=0");
header("Content-Type:application/force-download");
header("Content-Type:application/vnd.ms-excel");
header("Content-Type:application/octet-stream");
header("Content-Type:application/download");
header('Content-Disposition:attachment;filename="客户资料列表['.date('Y-m-d').'].xls"');
header("Content-Transfer-Encoding:binary");

$objWriter -> save("php://output");
?>