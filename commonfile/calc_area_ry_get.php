<?

$dwdm = $_SESSION['GDWDM'];

//        判断是哪个级别的统计
$dw0 = dw0($_SESSION['GDWDM']);

if ( $_SESSION['GDWDM']=='340000' ) {
    $seldw = '3405';
    $dwdmStr = "('340500')";
} elseif($_SESSION['GDWDM']=='330000'){
    $seldw = '3301';
    $dwdmStr = "('330100')";
}elseif($_SESSION['GDWDM']=='300000'){
    $seldw = 'sh';
    $dwdmStr = "('330100','330300')";
}else{
    $dwdmStr = "('" . $dwdm . "')";
}

//上海要看火车站店之前的记录
if($_SESSION['GDWDM']=='330100'){
    $dwdmStr = "('330100','330300')";
}
//分门店查询
if($_GET['seldw1'] <> ''){

    $seldw = $_GET['seldw1'];

    if($seldw == '所有门店' && $dwdm =='340000'){

        $dwdmStr = "(select dwdm from b_dwdm where locate('340',dwdm)>0 and locate('0000',dwdm)=0)";

    }elseif($seldw == '所有门店' && $dwdm =='330000'){

        $dwdmStr = "(select dwdm from b_dwdm where locate('330',dwdm)>0 and locate('0000',dwdm)=0)";

    }else{
//        $seldw = substr($seldw,0,4);
        $dwdmStr = "('$seldw')";
//        $seldw = substr($seldw,0,4);
    }

}
//分区域
if($_GET['seldw2']<>''){

    $seldw = $_GET['seldw2'];

    if($seldw == '所有区域'){

        $dwdmStr = "(select dwdm from b_dwdm where locate('0000',dwdm)=0)";

    }elseif($seldw == 'bj'){

        $dwdmStr = "(select dwdm from b_dwdm where locate('340',dwdm)>0 and locate('0000',dwdm)=0)";
    }else{
        $dwdmStr = "(select dwdm from b_dwdm where locate('330',dwdm)>0 and locate('0000',dwdm)=0)";
    }
}

?>