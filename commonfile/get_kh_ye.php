<?

    $sql_czxf = "SELECT ifnull(sum((ifnull(`order_zh`.`jf`, 0) - ifnull(`order_zh`.`df`, 0))),0) AS `czxf` FROM order_zh WHERE fssj > IFNULL((SELECT sdate FROM kh_ye WHERE depart = '$khmc' LIMIT 1),'2015-01-01') AND khmc = '$khmc' GROUP BY khmc ";

    $sql_ye = "select ye from kh_ye where depart = '$khmc'";

    $resye1 = mysql_query($sql_czxf,$conn);
    $resye2 = mysql_query($sql_ye,$conn);
    if(mysql_num_rows($resye1) >0){

        $res_czxf = mysql_result($resye1 ,0,'czxf');

    }else{
        $res_czxf = 0;
    }

    if(mysql_num_rows($resye2)>0){
        $res_ye = mysql_result($resye2 ,0,'ye');

    }else{
        $res_ye=0;
    }

    $yue = round(floatval($res_czxf) + floatval($res_ye) , 2);


?>