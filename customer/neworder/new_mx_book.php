<?

$jldw1= 'P';
$productname = '书本';
$n1 = '封面';
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

$n2 = '内页';
$machine2 = 'Hp彩色';
$paper2 = 7;
$hzx2 = '';
$pnum2 = 0;
$sl2 =0;
$jg2 = 0;

$insmx = "insert into order_mxqt (id,ddh,productname,n1,machine1,paper1,jldw1,dsm1,hzx1,pnum1,sl1,jg1,n2,machine2,paper2,hzx2,pnum2,sl2,jg2) values (0,'$bh','$productname','$n1','$machine1','$paper1','$jldw1','$dsm1','$hzx1','$pnum1','$sl1','$jg1','$n2','$machine2','$paper2','$hzx2','$pnum2','$sl2','$jg2')";
//        echo $insmx;exit;
mysql_query($insmx,$conn);
?>