<?
//session_start();
header("Content-type:text/html;charset=utf-8");

require_once 'excel_back/Classes/PHPExcel.php';
require_once 'excel_back/Classes/PHPExcel/IOFactory.php';
require_once 'excel_back/Classes/PHPExcel/Reader/Excel5.php';


$objPHPExcel = new PHPExcel();
$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);

$objWriter -> save("xxx.xlsx");

header("Prama:pubic");
header("expires:0");
header("Cache-Control:must-revalidate,post-check=0,pre-check=0");
header("Content-Type:application/force-download");
header("Content-Type:application/vnd.ms-excel");
header("Content-Type:application/octet-stream");
header("Content-Type:application/download");;
header('Content-Disposition:attachment;filename="resume.xls"');
header("Content-Transfer-Encoding:binary");
 $objWriter -> save('');

//创建人
$objPHPExcel->getProperties()->setCreator("zhuxiujuan");
//修改人
$objPHPExcel->getProperties()->setLastModifiedBy("zhuxiujuan");
//标题
$objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Test Document");
//题目
$objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Test Document");
//描述
$objPHPExcel->getProperties()->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.");
//关键字
$objPHPExcel->getProperties()->setKeywords("office 2007 openxml php");
//种类
$objPHPExcel->getProperties()->setCategory("Test result file");

//设置当前sheet

?>