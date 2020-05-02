<?

$jldw1= 'P';
$productname = '单张';
$n1 = '单张';
//$machine1 = 'Hp10000彩色';
$machine1 = 'Hp彩色';
//$chicun = '750*530';
$chicun = '464*320';
//$paper1 = 64;
$paper1 = 7;
//$dsm1 = '双面';
$hzx1 = '';
$pnum1 = 0;
$sl1 =0;
$jg1 = 0;

$insmx = "insert into order_mxqt (id,ddh,productname,n1,machine1,paper1,jldw1,dsm1,hzx1,pnum1,sl1,jg1) values (0,'$bh','$productname','$n1','$machine1','$paper1','$jldw1','$dsm1','$hzx1','$pnum1','$sl1','$jg1')";

mysql_query($insmx,$conn);
//include '../../commonfile/log.php';

?>