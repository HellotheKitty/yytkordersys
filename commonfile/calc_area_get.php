<?

$dwdm = substr($_SESSION["GDWDM"],0,4);

//        判断是哪个级别的统计
$dw0 = dw0($_SESSION['GDWDM']);

if ( $_SESSION['GDWDM']=='340000' ) {
    $dwdmStr = "('3401','3402','3403','3404','3405','3451','3452','3453','3454')";
//} elseif($_SESSION['GDWDM']=='340500'){
//    $dwdmStr = "('3401','3402','3403','3404','3405')";
} elseif($_SESSION['GDWDM']=='330000'){
    $dwdmStr = "('3301')";
}elseif($_SESSION['GDWDM']=='300000'){
    $dwdmStr = "('3401','3402','3403','3404','3405','3301','3303','3451','3452','3453','3454')";
}else{
    $dwdmStr = "('" . $dwdm . "')";
}

//上海要看火车站店之前的记录
if($_SESSION['GDWDM']=='330100'){
    $dwdmStr = "('3301','3303')";
}

//分门店查询
if($_GET['seldw1'] <> ''){

    $seldw = $_GET['seldw1'];

    if($seldw == '所有门店'){

        $dwdmStr = "('3401','3402','3403','3404','3405','3451','3452','3453','3454')";

    }else{
        $seldw = substr($seldw,0,4);
        $dwdmStr = "('$seldw')";
    }

}
//分区域
if($_GET['seldw2']<>''){

    $seldw = $_GET['seldw2'];

    if($seldw == '所有区域'){

        $dwdmStr = "('3401','3402','3403','3404','3405','3301','3451','3452','3453','3454')";

    }elseif($seldw == 'bj'){

        $dwdmStr = "('3401','3402','3403','3404','3405')";
    }elseif($seldw == 'sh'){
        $dwdmStr = "('3301')";
    }
}

?>