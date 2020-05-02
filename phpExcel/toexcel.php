<?
//session_start();
require("../inc/conn.php");

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

//data 按照客户分组
$gp= $_GET['state'];

$dd1= urldecode($_GET['dd1']);
$dd2= urldecode($_GET['dd2']);
$filetj = " and order_mainqt.ddate>='$dd1' and order_mainqt.ddate<='$dd2' ";
$dwdmStr = urldecode($_GET['dwdmstr']);


//    更多状态
$gpmore ='';
if($_GET['producedetail']<>''){

    $gpmore = $_GET['producedetail'];

}

if($_GET['trouble_order'] <>''){

    if($_GET['trouble_order'] == '1'){
        $troublesql = " and (to_days(now()) - to_days(order_mainqt.sdate)) > 4 and order_mainqt.state <>'订单完成' ";

    }elseif($_GET['trouble_order'] =='2'){

        $troublesql = " and order_mainqt.state ='作废订单' ";

    }elseif($_GET['trouble_order'] == '3'){
        $troublesql = " and order_mainqt.dje = 0 ";

    }

}else{
    $troublesql = " ";

}

if($_GET['psfs']<>''){

    $psfs = $_GET['psfs'];
    $psfssql = " and locate('$psfs',order_mainqt.psfs)>0 ";
}else{
    $psfssql = '';
}

if ($_GET["fdd"] <> "") {
    //待结算每个客户合计
    if($gp == '待结算'){

        $res = mysql_query("select order_mainqt.khmc ,order_mainqt.state , group_concat(order_mainqt.ddh) as ddhs ,count(order_mainqt.ddh) ddsl,sum(order_mainqt.dje) ddzje, xs.xm from b_ry xs,order_mainqt left join order_zh on order_mainqt.ddh=order_zh.ddh and zy<>'订单订金' where order_mainqt.xsbh=xs.bh and (order_mainqt.khmc like '%" . $_GET["fdd"] . "%' or order_mainqt.ddh like '%" . $_GET["fdd"] . "%')  and (order_mainqt.state = '$gp') and zzfy  in $dwdmStr $filetj $troublesql $psfssql group by order_mainqt.khmc order by khmc", $conn);


    }else{
        $res = mysql_query("select order_mainqt.*,count(order_mxqt.id) mxsl,group_concat(order_mxqt.productname) cpms,xs.xm,sksj from b_ry xs,order_mainqt left join order_mxqt on order_mainqt.ddh=order_mxqt.ddh left join order_zh on order_mainqt.ddh=order_zh.ddh and zy<>'订单订金' where order_mainqt.xsbh=xs.bh and (order_mainqt.khmc like '%" . $_GET["fdd"] . "%' or order_mainqt.ddh like '%" . $_GET["fdd"] . "%')  and (order_mainqt.state like '$gp') and zzfy  in $dwdmStr  $filetj $troublesql $psfssql group by order_mainqt.ddh order by khmc", $conn);

    }
} else {
    //待结算每个客户合计
    if($gp == '待结算'){

        $res = mysql_query("select order_mainqt.khmc,order_mainqt.state , group_concat(order_mainqt.ddh) as ddhs ,count(order_mainqt.ddh) ddsl,sum(order_mainqt.dje) ddzje, xs.xm from b_ry xs,order_mainqt left join order_zh on order_mainqt.ddh=order_zh.ddh and zy<>'订单订金' where order_mainqt.xsbh=xs.bh and (order_mainqt.state like '$gp') and zzfy  in $dwdmStr  $filetj $troublesql $psfssql group by order_mainqt.khmc order by khmc", $conn);
        

    }else {
        $res = mysql_query("select order_mainqt.*,count(order_mxqt.id) mxsl,group_concat(order_mxqt.productname) cpms,xs.xm,sksj from b_ry xs,order_mainqt left join order_mxqt on order_mainqt.ddh=order_mxqt.ddh left join order_zh on order_mainqt.ddh=order_zh.ddh and zy<>'订单订金' where order_mainqt.xsbh=xs.bh and (order_mainqt.state like '$gp') and zzfy  in $dwdmStr  $filetj $troublesql $psfssql group by order_mainqt.ddh order by khmc", $conn);
//    $sql = "select order_mainqt.*,count(order_mxqt.id) mxsl,group_concat(order_mxqt.productname) cpms,xs.xm,sksj from b_ry xs,order_mainqt left join order_mxqt on order_mainqt.ddh=order_mxqt.ddh left join order_zh on order_mainqt.ddh=order_zh.ddh and zy<>'订单订金' where order_mainqt.xsbh=xs.bh and (order_mainqt.state like '$gp') and zzfy  in $dwdmStr  $filetj group by order_mainqt.ddh order by ddate desc";
//
//    $file = '../mainb/log.txt';
//    $f = file_put_contents($file,$sql,FILE_APPEND);
    }
}

//设置单元格的值
if($gp == '待结算'){

    $objPHPExcel -> getActiveSheet() -> setCellValue('A1','客服');
    $objPHPExcel -> getActiveSheet() -> setCellValue('B1','订单编号');
    $objPHPExcel -> getActiveSheet() -> setCellValue('C1','订单数量');
    $objPHPExcel -> getActiveSheet() -> setCellValue('D1','订单总金额');
    $objPHPExcel -> getActiveSheet() -> setCellValue('E1','订单状态');
 $objPHPExcel -> getActiveSheet() -> setCellValue('F1','客户名称');
    for($i=0;$i<mysql_num_rows($res);$i++){

        $objPHPExcel->getActiveSheet()->setCellValue('A' . ($i+2), mysql_result($res, $i, 'xm'));
        $objPHPExcel->getActiveSheet()->setCellValue('B' . ($i+2), mysql_result($res, $i, 'ddhs'));
        $objPHPExcel->getActiveSheet()->setCellValue('C' . ($i+2), mysql_result($res, $i, 'ddsl'));
        $objPHPExcel->getActiveSheet()->setCellValue('D' . ($i+2), mysql_result($res, $i, 'ddzje'));
        $objPHPExcel->getActiveSheet()->setCellValue('E' . ($i+2), mysql_result($res, $i, 'state'));
		$objPHPExcel->getActiveSheet()->setCellValue('F' . ($i+2), mysql_result($res, $i, 'khmc'));
    }
}else{
    $objPHPExcel -> getActiveSheet() -> setCellValue('A1','客服');
    $objPHPExcel -> getActiveSheet() -> setCellValue('B1','订单编号');
    $objPHPExcel -> getActiveSheet() -> setCellValue('C1','客户名称');
    $objPHPExcel -> getActiveSheet() -> setCellValue('D1','订购时间');
    $objPHPExcel -> getActiveSheet() -> setCellValue('E1','要求完成');
    $objPHPExcel -> getActiveSheet() -> setCellValue('F1','订单金额');
    $objPHPExcel -> getActiveSheet() -> setCellValue('G1','预付定金');
    $objPHPExcel -> getActiveSheet() -> setCellValue('H1','配送金额');
    $objPHPExcel -> getActiveSheet() -> setCellValue('I1','配送要求');
    $objPHPExcel -> getActiveSheet() -> setCellValue('J1','生产地');
    $objPHPExcel -> getActiveSheet() -> setCellValue('K1','订单状态');
    $objPHPExcel -> getActiveSheet() -> setCellValue('L1','备注');

    for($i=0;$i<mysql_num_rows($res);$i++) {

        $objPHPExcel->getActiveSheet()->setCellValue('A' . ($i+2), mysql_result($res, $i, 'xm'));
        $objPHPExcel->getActiveSheet()->setCellValue('B' . ($i+2), mysql_result($res, $i, 'ddh'));
        $objPHPExcel->getActiveSheet()->setCellValue('C' . ($i+2), mysql_result($res, $i, 'khmc'));
        $objPHPExcel->getActiveSheet()->setCellValue('D' . ($i+2), mysql_result($res, $i, 'ddate'));
        $objPHPExcel->getActiveSheet()->setCellValue('E' . ($i+2), mysql_result($res, $i, 'yqwctime'));
        $objPHPExcel->getActiveSheet()->setCellValue('F' . ($i+2), mysql_result($res, $i, 'dje'));
        $objPHPExcel->getActiveSheet()->setCellValue('G' . ($i+2), mysql_result($res, $i, 'djje'));
        $objPHPExcel->getActiveSheet()->setCellValue('H' . ($i+2), mysql_result($res, $i, 'kdje'));
        $objPHPExcel->getActiveSheet()->setCellValue('I' . ($i+2), mysql_result($res, $i, 'psfs'));
        $objPHPExcel->getActiveSheet()->setCellValue('H' . ($i+2), mysql_result($res, $i, 'scjd'));
        $objPHPExcel->getActiveSheet()->setCellValue('K' . ($i+2), mysql_result($res, $i, 'state'));
        $objPHPExcel->getActiveSheet()->setCellValue('L' . ($i+2), mysql_result($res, $i, 'memo'));

    }
}

ob_end_clean();//清除缓冲区,避免乱码
header("Prama:pubic");
header("expires:0");
header("Cache-Control:must-revalidate,post-check=0,pre-check=0");
header("Content-Type:application/force-download");
header("Content-Type:application/vnd.ms-excel");
header("Content-Type:application/octet-stream");
header("Content-Type:application/download");
header('Content-Disposition:attachment;filename="订单列表.xls"');
header("Content-Transfer-Encoding:binary");

$objWriter -> save("php://output");
?>