<?

$dwdm = substr($_SESSION["GDWDM"],0,4);

//        判断是哪个级别的统计
$dw0 = dw0($_SESSION['GDWDM']);

if ( $_SESSION['GDWDM']=='340000' ) {
    $dwdmStr = "('3401','3402','3403','3404','3405')";
} elseif($_SESSION['GDWDM']=='330000'){
    $dwdmStr = "('3301')";
}elseif($_SESSION['GDWDM']=='300000'){
    $dwdmStr = "('3401','3402','3403','3404','3405','3301')";
}else{
    $dwdmStr = "('" . $dwdm . "')";
}

//分门店查询
if($_POST['seldw1'] <> ''){

    $seldw = $_POST['seldw1'];

    if($seldw == '所有门店'){

        $dwdmStr = "('3401','3402','3403','3404','3405')";

    }else{
        $seldw = substr($seldw,0,4);
        $dwdmStr = "('$seldw')";
    }

}
//分区域
if($_POST['seldw2']<>''){

    $seldw = $_POST['seldw2'];

    if($seldw == '所有区域'){

        $dwdmStr = "('3401','3402','3403','3404','3405','3301')";

    }elseif($seldw == 'bj'){

        $dwdmStr = "('3401','3402','3403','3404','3405')";
    }elseif($seldw == 'sh'){
        $dwdmStr = "('3301')";
    }
}

?>