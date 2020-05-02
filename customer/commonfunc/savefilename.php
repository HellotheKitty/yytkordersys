<?

$file1=urlencode($file1);
if($_SESSION['GDWDM'] == '330100'){
    $file1="http://59.110.17.13/ordersys/customer/neworder/server/upload/".$ddh.'/'.$mxid.'/'.$file1.".pdf";

}else{
    $file1="http://oa.skyprint.cn/customer/neworder/server/upload/".$ddh.'/'.$mxid.'/'.$file1.".pdf";

}

?>