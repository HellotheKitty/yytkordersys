<?
 require("../../inc/conn.php");
session_start();
header("Content-Type:text/html;charset=utf-8");
if($_SESSION['YKOAUSER'] =='hudan'){


    if($_GET['remainkh']<>'' && $_GET['delkh']<>''){
//保留的客户
        $remainkhs = explode(';',$_GET['remainkh']);

        $delkh = explode(';',$_GET['delkh']);

        $remainkhmc = $remainkhs[0];

        foreach($delkh as $delkhmc){

            $rsdd = mysql_query("update order_mainqt set khmc = '$remainkhmc' where khmc = '$delkhmc' ",$conn);

            $rszh = mysql_query("update order_zh set khmc = '$remainkhmc' where khmc = '$delkhmc'",$conn);

//            删除的客户资料备份到文件
//            $info = mysql_query()
            $file = 'delete_khinfo.txt';

            $lxdh = mysql_result(mysql_query("select lxdh from base_kh where khmc = '$delkhmc'",$conn),0,'lxdh');
            $content = $delkhmc.','.$lxdh.'|';

            $f = file_put_contents($file,$content,FILE_APPEND);
//            delete kh
            mysql_query("delete from base_kh where khmc = '$delkhmc'",$conn);


        }
        exit();
    }

    if($_GET['remainkh']<>'' && $_GET['delkh']==''){
        exit();
    }
//    一个都不保留
    if($_GET['delkh']<>'' && $_GET['remainkh'] == ''){

        $delkh = explode(';',$_GET['delkh']);

        foreach($delkh as $delkhmc){

            $file = 'delete_khinfo.txt';

            $lxdh = mysql_result(mysql_query("select lxdh from base_kh where khmc = '$delkhmc'",$conn),0,'lxdh');
            $content = $delkhmc.','.$lxdh.'|';

            $f = file_put_contents($file,$content,FILE_APPEND);
//            delete kh
            mysql_query("delete from base_kh where khmc = '$delkhmc'",$conn);
        }
        exit();

    }

    if($_GET['searchbt']<>''){
        $key = $_GET['keyv'];
        $rs = mysql_query("select khmc,ye,lxr,lxdh,gdzk,lxdz from base_kh left join user_zhjf on base_kh.khmc = user_zhjf.depart where khmc like '%$key%' and gdzk = '3301'",$conn);

    }


}

?>
<!DOCTYPE html>
<html>
<head>
    <script type="text/javascript" src="../../js/jquery-1.8.3.min.js"></script>
</head>
<body style="padding: 20px;">
<form method="get">
    <input type="text" name="keyv" placeholder="输入用户名关键词"/>
    <input type="submit" name="searchbt" value="搜索"/>


</form>
<h3>找到如下相关客户：<span style="color:red;">请选择保留的客户名</span></h3>

<div id="ddhs">

    <? if($_GET['keyv']<>''){
        while($item = mysql_fetch_assoc($rs)){
        $result = mysql_query("select khmc,count('id') as ddsl from order_mainqt where khmc ='".$item['khmc']."'",$conn);

        $res0 = mysql_fetch_assoc($result); ?>

    <lable>
        <input name="sss[]" class="aaaa" type="checkbox" checked="checked" value="<? echo $item['khmc']?>"><? echo $item['khmc'] ?>
        <br>余额：<? echo $item['ye']?>元
    </lable><br>
        订单数量：<? echo $res0['ddsl'] ?>
        <br>
        联系电话：<? echo $item['lxdh'] ?>
        <br>
        单位:<? echo $item['gdzk'] ?>
        <br>
        地址:<? echo $item['lxdz'] ?>
            <br><br>
    <? }
    }else{
        echo '未找到相关客户';
    } ?>

</div>
<span style="color:lightsalmon;display: block;">删除的用户订单及余额将转入保留的客户账号下</span>
<input class='remainbtn' name="remain" type='button' value='保留'/>
</body>
<script type="text/javascript">

    $('.remainbtn').on('click',function(){

        var remainkh= $('#ddhs').find('input:checkbox.aaaa:checked');

        var delkh = $('#ddhs').find('input:checkbox.aaaa').not('input:checked');

        var _senddatare = '';
        var _senddatade = '';

        $.each(remainkh,function(i,item){

            var value = $(item).val();
            _senddatare += (value + ';');

        });

        $.each(delkh,function(i,item){

            var value = $(item).val();
            _senddatade += (value + ';');
        });

//        删除最后一个分号
        _senddatade = _senddatade.substring(0,_senddatade.length-1);
        _senddatare = _senddatare.substring(0,_senddatare.length-1);

        var _senddata = 'remainkh='+_senddatare + '&delkh=' +_senddatade;

        $.ajax({
            dataType:'json',
            method:'GET',
            async:false,
            data:_senddata,
            success:function(data){

                console.log(data);
//                window.reload();
                history.go(0);

            },

            error:function(){
                alert('union error!plz retry')

            }


        })

    });

</script>
</html>
