<? require_once("../inc/conn.php");

?>

<? session_start();
if ($_SESSION["OK"]<>"OK") {
    echo "<script language=JavaScript>{window.location.href='../error.php';}</script>";
    exit;
}?>
<?

$dwdm = substr($_SESSION["GDWDM"],0,4);


$copymx = mysql_query("select id from order_mxqt where ddh ='".$copyddh."'",$conn);
$copymxhd = mysql_query("select id from order_mxqt_hd where ddhao ='".$copyddh."'",$conn);
$copymxfm = mysql_query("select id from order_mxqt_fm where ddh ='$copyddh' ",$conn);

//    被copy的订单是否有明细
if(mysql_num_rows($copymx)>0){
    beginTransaction();
//        copy的订单有没有明细
//        if(mysql_num_rows($ifexistsmx)>0){
//
//            $copyorder[] = mysql_query("delete from order_mxqt where ddh='".$bh."'",$conn);
//
//        }
    for($i=0;$i<mysql_num_rows($copymx);$i++){

        $copyorder[] = mysql_query("insert into order_mxqt (id,ddh,productname,pname,chicun,sl,n1,file1,machine1,paper1,color1,jldw1,dsm1,hzx1,pnum1,sl1,jg1,sczzbh1,n2,file2,machine2,paper2,color2,jldw2,dsm2,hzx2,pnum2,sl2,jg2,sczzbh2) select 0,'$bh',productname,pname,chicun,sl,n1,file1,machine1,paper1,color1,jldw1,dsm1,hzx1,pnum1,sl1,jg1,sczzbh1,n2,file2,machine2,paper2,color2,jldw2,dsm2,hzx2,pnum2,sl2,jg2,sczzbh2 from order_mxqt where id='".mysql_result($copymx,$i,'id')."'",$conn);


        //                得到新插入的明细ID 降序排列 得到的第一个是最新插入的
        $newmxs = mysql_query("select id from order_mxqt where ddh='".$bh."' order by id desc limit 1",$conn);

        $newmx = mysql_result($newmxs,0,'id');

        if(mysql_num_rows($copymxhd)>0){

//                if(mysql_num_rows($ifexistshd)>0){
//
//                    $copyorder[] = mysql_query("delete from order_mxqt_hd where ddhao='".$bh."'",$conn);
//
//                }
            $copyorder[] = mysql_query("insert into order_mxqt_hd (id,mxid,jgfs,cpcc,jldw,sl,jg,memo,ddhao) select 0,'".$newmx."',jgfs,cpcc,jldw,sl,jg,memo,$bh from order_mxqt_hd where mxid='".mysql_result($copymx,$i,'id')."'",$conn);


        }
        if(mysql_num_rows($copymxfm)>0){

//                if(mysql_num_rows($ifexistsfm)>0){
//
//                    $copyorder[] = mysql_query("delete from order_mxqt_fm where ddh='".$bh."'",$conn);
//                }

            $copyorder[] = mysql_query("insert into order_mxqt_fm (id,mxid,fmfs,cpcc,jldw,sl,jg,memo,ddh) select 0,'$newmx',fmfs,cpcc,jldw,sl,jg,memo,$bh from order_mxqt_fm where mxid='".mysql_result($copymx,$i,'id')."'",$conn);

        }

    }
//        update订单金额
    $dje = mysql_result(mysql_query("select dje from order_mainqt where ddh = '$copyddh'",$conn) , 0,'dje');
    $kdje = mysql_result(mysql_query("select kdje from order_mainqt where ddh = '$copyddh'",$conn) , 0,'kdje');

    $copyorder[] = mysql_query("update order_mainqt set dje = $dje,kdje = $kdje where ddh = $bh",$conn);

    if(!transaction($copyorder)){

        echo "<script>alert('拷贝订单失败！请重新操作');</script>";
    }
}

?>