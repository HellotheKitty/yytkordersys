<?
require("../../inc/conn.php");

if($_GET['didth']<>''){
    $didth = $_GET['didth'];
}
if($_GET['didzf']<>''){
    $didzf=$_GET['didzf'];
    //            是否有操作员签单
    $rsqd =  mysql_query("select pczy,hdczy,fumoczy from order_mainqt where id = '$didzf'",$conn);
    if(mysql_result($rsqd,0,'pczy')<>'' || mysql_result($rsqd,0,'hdczy')<>''|| mysql_result($rsqd,0,'fumoczy')<>''){

//        echo json_encode(array('info' => '订单已经开始生产，是否确定作废？'));
        echo "<script type='text/javascript'> var suredo = confirm('订单已经生产，是否确定作废?'); if(!suredo) window.close();</script>";
//        echo "<script type='text/javascript'>window.close();</script>";
//        exit();
    }
}

session_start();
if ($_SESSION["OK"] <> "OK") {
    session_unset();
    session_destroy();
    echo "<script>{windows.location.href='../../error.php';}</script>";
    exit;
}
header("Content-type:text/html;charset=utf-8");

?>
<!DOCTYPE html>

<html>
<head>
    <meta charset="UTF-8">
    <title>订单处理</title>
    <style type="text/css">
        body{margin:20px;}
    </style>
</head>
<body>
<h3><? if($didth<>'') echo '订单退回'; if($didzf<>'') echo '订单作废'; ?></h3>
<div>
    <form name="formth" method="post" >
    <span>请选择<? if($didth<>'') echo '退回'; if($didzf<>'') echo '作废'; ?>原因:</span>

        <? if($didth<>''){
            ?>
            <input type="hidden" value="<? echo $didth ?>" name="didth" id="didth"/>
            <select name="thyy" id="thyy">
                <? $rescode = mysql_query("select * from base_wtdd_code ORDER BY id DESC",$conn);

                while($codeitem = mysql_fetch_array($rescode,MYSQL_ASSOC)){
                    ?>
                    <option value="<? echo $codeitem['code'] ?>"><? echo $codeitem['description']; ?></option>

                <? } ?>
            </select>
            <?
        }

        if($didzf<>''){

            ?>
            <input type="hidden" value="<? echo $didzf ?>" name="didzf" id="didzf"/>
            <select name="zfyy" id="zfyy">
                <? $rescode = mysql_query("select * from base_wtdd_code ORDER BY id DESC",$conn);

                while($codeitem = mysql_fetch_array($rescode,MYSQL_ASSOC)){
                    ?>
                    <option value="<? echo $codeitem['code'] ?>"><? echo $codeitem['description']; ?></option>

                <? } ?>
            </select>
            <?
            $resje = mysql_query("select * from order_zh where ddh = (select ddh from order_mainqt where id = '$didzf') and zy = '订单结算'",$conn);
            if(mysql_num_rows($resje)>0){
                $je = mysql_result($resje,0,df);
                ?>
                <br><br>退款金额：
                <input id="tkje" name="tkje" type="text" value="<? echo $je; ?>" size="5">元 &nbsp;&nbsp;退款方式:
                <select name="tkfs" id="tkfs">
                    <option value="预存款">退到预存款</option>
                    <option value="现金">现金</option>
                    <option value="支票">支票</option>
                    <option value="POS机招行">POS机招行</option>
                    <option value="汇款">汇款</option>
                    <option value="微信">微信</option>
                </select>
                <!--            <input type="submit" name="button_tk" id="buttontk" value="退款">-->

                <br>备注：<input type="text" name="tkbz" id="tkbz" placeholder="退款备注" value=""/>
                <?
            }else{
                $je = 0;
                ?>
                <br><br>未结算订单
                <input style="display: none;" id="tkje" name="tkje" type="text" value="<? echo $je; ?>" size="5">
                <select name="tkfs" id="tkfs" style="display: none;">
                    <option value=""></option>
                    <option value="预存扣款">退到预存款</option>
                    <option value="现金">现金</option>
                    <option value="支票">支票</option>
                    <option value="POS机招行">POS机招行</option>
                    <option value="汇款">汇款</option>
                    <option value="微信">微信</option>
                </select>
                <!--            <input type="submit" name="button_tk" id="buttontk" value="退款">-->

                <input style="display: none;" type="text" name="tkbz" id="tkbz" placeholder="退款备注" value=""/>
                <?
            }

        }
        ?>

        <br><br>
        <textarea name="demo" id="memo" placeholder="作废订单备注"></textarea>
        <br><br>
        <? if($didth<>''){
            ?>
            <input type="button" id="btn-th" onclick="thdd();" value="退回"/>
            <?
        }

        if($didzf<>''){
            ?>
            <input type="button" id="btn-zf" onclick="zfdd();" value="作废"/>
            <?
        } ?>
    </form>

</div>
</body>
<SCRIPT language=JavaScript src="../../js/jquery-1.8.3.min.js"></SCRIPT>
<script type="text/javascript">
    function thdd(){

        $('#btn-th').val('处理中');
        $('#btn-th').attr('disabled','disabled');
        var _senddata ='didth='+ $('#didth').val() +'&thyy='+ $('#thyy').val() +'&memo='+ $('#memo').val();

        $.ajax({
            type:'POST',
            url: 'YSXMqt_del.php',
            data:_senddata,
            dataType: 'json',
            success: function (data) {

                confirm(data['code']);
                window.opener.location.reload();
                window.close();
            },
            error: function (data) {
                alert('th error plz retry!');
            }
        });
    }

    function zfdd(){

        $('#btn-zf').val('处理中');
        $('#btn-zf').attr('disabled','disabled');
            var _senddata ='didzf='+ $('#didzf').val() +'&zfyy='+ $('#zfyy').val() +'&memo='+ $('#memo').val() + '&tkje=' + $('#tkje').val() + '&tkfs=' + $('#tkfs').val() + '&tkbz = ' + $('#tkbz').val();


        $.ajax({
            type:'POST',
            url: 'YSXMqt_del.php',
            data:_senddata,
            dataType: 'json',
            success: function (data) {

                confirm(data['code']);
                window.opener.location.reload();
                window.close();
            },
            error: function (data) {
                alert('th error plz retry!');
            }
        });
    }
</script>
</html>
