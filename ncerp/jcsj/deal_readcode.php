<? require("../../inc/conn.php");
require("../../commonfile/wechat_notice_class.php");

header("Content-type: text/html; charset=utf-8");

if($_GET['idwithtype']<>''){



    $workplace = $_GET['workplace'];
    $operator = $_GET['operator'];
    $idwithtype = $_GET['idwithtype'];
    $checkstr = $_GET['checkstr'];

    $inner = $_GET['inner'];

    $code = md5($workplace . $operator . $idwithtype);

    if($checkstr <> $code && $inner <> 1){

//    echo json_encode(['info' => 'not valid']);
    echo 'not valid';
//        echo '错误,参数有误';
        exit;
    }
    //        微信公众号通知客户
    if(substr($idwithtype,0,2) == 'fh'){

        $ddha = substr($idwithtype,3);
        $res_notice = mysql_query("select khmc,sdate from order_mainqt where ddh = '$ddha'",$conn);

        if(mysql_num_rows($res_notice)>0){
            $touser = mysql_result($res_notice,0,'khmc');
            $ddate = mysql_result($res_notice,0,'sdate');
            $first = $touser . ',您的订单已发货';
            $remark='感谢您的使用';
            $linkurl = 'http://oa.skyprint.cn/WXS/dd_list.php';
            wechat_notice::send_temp_ordercomplecated($touser,$first,$ddha ,$ddate,$remark);

        }
    }
//微信公众号通知客户 end

    $sql = "CALL finish_work('$workplace','$operator','$idwithtype')";

    $res = mysql_result(mysql_query($sql),0,'@backcode');

//echo json_encode(['info' => $res]);
    if($res == 200){

        echo 'OK';

    }
    elseif($res == 201){
//    echo "print task finished";
        echo 'OK';

    }
    elseif($res == 101){
    echo 'state error';
//        echo '错误，订单不是待配送状态';
    }
    elseif($res == 102){
    echo 'order is not finished';
//        echo '订单未完成';
    }

    exit;
}
if($_GET['finduser']==1){
    $operator = $_GET['operator'];
    $sql = "SELECT xm,qx,dwdm FROM b_ry WHERE bh='$operator' LIMIT 1";
    $res = mysql_query($sql);
    $ret = [
        'xm'=>mysql_result($res,0,'xm'),
        'qx'=>mysql_result($res,0,'qx'),
        'dwdm'=>mysql_result($res,0,'dwdm')
    ];
    echo json_encode($ret);
    exit;
}
?>