<?php
require("../../inc/conn.php");
$dwdm = substr($_SESSION['GDWDM'],0,4);
if($_GET["type"] == "1"){	//打印单价

    $selkh = mysql_query("select khmc from order_mainqt where ddh='".$_GET["ddh"]."'",$conn);
    $khmc = mysql_result($selkh,0,"khmc");

    $selhyjb = mysql_query("select hyjb from base_kh where khmc = '$khmc'",$conn);

    $hyjb = mysql_result($selhyjb,0,"hyjb");

    $zsl = $_GET['zsl'];
    $price = 0;

//	特定客户协议价
    $pricers = mysql_query("select price from price_of_print where khmc='".$khmc."' and locate('".$_GET["machine"]."',machine)>0 and dsm='".$_GET["dsm"]."' and materialid='".$_GET["paper"]."' and unit='".$_GET["jldw"]."' order by id desc limit 1",$conn);

    if($pricers && mysql_num_rows($pricers) > 0){

        $price = mysql_result($pricers,0,"price");

    }else{//	VIP价格3301 北京中心店不要

        if($_SESSION['GDWDM']=='340500'){
            echo 0;
            exit();
        }

        $price_vip = mysql_query("select price from printprice_of_vip where locate('".$_GET["machine"]."',machine)>0 and materialCode='".$_GET["paper"]."' and unit='".$_GET["jldw"]."' and hyjb = '$hyjb' order by id desc",$conn);

        if($price_vip && mysql_num_rows($price_vip) >0 ){

            $price_dsm = explode('/',mysql_result($price_vip,0,'price'));

            if(count($price_dsm)>1){

                if($_GET['dsm']=='单面'){
                    $price = $price_dsm[0];
                }elseif($_GET['dsm']=='双面'){
                    $price = $price_dsm[1];
                }
            }else{
//单双面只有一个价格
                $price = $price_dsm[0];
            }

        }else{ //	门市价 北京中心店不要

            if($_SESSION['GDWDM']=='340500'){
                echo 0;
                exit();
            }

            $price_normal = mysql_query("select price from printprice_of_normal where locate('".$_GET["machine"]."',machine)>0 and materialCode='".$_GET["paper"]."' and unit='".$_GET["jldw"]."' and minsl<=$zsl and maxsl > $zsl and depart='$dwdm' order by id desc",$conn);


            if($price_normal && mysql_num_rows($price_normal)>0){

                $price_dsm = explode('/',mysql_result($price_normal,0,'price'));

                if(count($price_dsm)>1){

                    if($_GET['dsm']=='单面'){
                        $price = $price_dsm[0];
                    }elseif($_GET['dsm']=='双面'){
                        $price = $price_dsm[1];
                    }
                }else{  //单双面只有一个价格

                    $price = $price_dsm[0];
                }

            }
        }
    }
    echo $price;

}else if($_GET["type"] == 2){	//后加工单价
    $khmc = mysql_result(mysql_query("select khmc from order_mainqt where ddh='".$_GET["ddh"]."'",$conn),0,"khmc");

    $pricers = mysql_query("select price from price_of_afterprocess where khmc='".$khmc."' and afterprocess='".$_GET["jgfs"]."' and locate('". $_GET["cpcc"] ."',chicun)>0 ",$conn);

    $price = 0;
    if($pricers && mysql_num_rows($pricers) > 0)
        $price = mysql_result($pricers,0,"price");
    echo $price;
}elseif($_GET["type"] == 4){
//	覆膜价格
    $khmc = mysql_result(mysql_query("select khmc from order_mainqt where ddh='".$_GET["ddh"]."'",$conn),0,"khmc");

    $pricers = mysql_query("select price from price_of_fumo where khmc='".$khmc."' and fumo='".$_GET["jgfs"]."' and chicun='".$_GET["cpcc"]."' and unit='".$_GET["jldw"]."'",$conn);
//	echo "select price from price_of_afterprocess where khmc='".$khmc."' and afterprocess='".$_GET["jgfs"]."' and chicun='".$_GET["cpcc"]."' and unit='".$_GET["jldw"]."'";

    $price = 0;
    if($pricers && mysql_num_rows($pricers) > 0)
        $price = mysql_result($pricers,0,"price");
    echo $price;

} else if($_GET["type"] == 3){	//订单金额与客户余额
    $khmc = mysql_result(mysql_query("select khmc from order_mainqt where ddh='".$_GET["ddh"]."'",$conn),0,"khmc");
    echo $khmc."<br>";
//	echo "select ye from user_zhjf where depart ='$khmc'"."<br>";
    echo "select ye from kh_ye where depart ='$khmc'"."<br>";
    $yers = mysql_query("select ye from kh_ye where depart ='$khmc'",$conn);
    if($yers && mysql_num_rows($yers) == 1){
        $ye = mysql_result($yers,0,"ye");
        $djers = mysql_query("select dje,kdje,djje from order_mainqt where ddh='".$_GET["ddh"]."'",$conn);
        $jearr = mysql_fetch_array($djers,MYSQL_ASSOC);
        print_r($jearr);
        $need = $jearr["dje"]+$jearr["kdje"]-$jearr["djje"];
        echo $need."<br>".$ye."<br>";
        if($ye >= $need){
            echo "1";
        }else{
            echo "此单金额大于客户的余额，无法提交生产。请联系客户充值。";
        }
    }else{
        echo "0";
    }
}
