<meta charset="utf-8" />
<?
require "public.php";
$ddh=$_GET['ddh'];
$path=substr($ddh, -6);
$path="../BillFiles/".$path;
if(file_exists($path)==TRUE){
    deldir($path);
    header("Location:http://oa.skyprint.cn/customer/neworder/newpiece.php?ddh=$ddh");
}else{
    echo "<script>alert('预览图已经删除或未生成');location.href='../../newpiece.php?ddh=$ddh'</script>";
}
?>