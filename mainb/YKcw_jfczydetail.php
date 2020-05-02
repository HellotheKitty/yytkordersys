<?
session_start();
require("../inc/conn.php");
?>
<? $tss="全部信息";
$dwdm = substr($_SESSION["GDWDM"], 0, 4);
if($_GET['rq1']<>''){
    $d1 = $_GET['rq1'];
}
if($_GET['rq2']<>''){
    $d2 = $_GET['rq2'];
}
if($_POST['rq1']<>'' || $_POST['rq2']<>''){
    $d1=$_POST["rq1"];if ($d1=="") {$d1=date("Y-m-")."01";$ss="";$tss="全部信息";}
    $d2=$_POST["rq2"];if ($d2=="") {$d2=date("Y-m-d");}
}

if ($_GET["dq"]<>"") $dq=urldecode($_GET["dq"]); else $dq="%";
$czyid = urldecode($_GET['id']);

// 获取物料
if(substr($dwdm,0,2) == '33'){
    $sqlm = "select * from material where zzfy='3301'";
}else if(substr($dwdm,0,2) == '34'){
    $sqlm = "select * from material where zzfy='3405'";
}else{
    $sqlm = "select * from material where zzfy='3301'";
}

$mars = mysql_query($sqlm, $conn);
$m = [];
while ($ma = mysql_fetch_array($mars)) {

    $m[$ma['id']]['mid'] = $ma['id'];
    $m[$ma["id"]]["name"] = $ma["MaterialName"];
    $m[$ma["id"]]["spec"] = $ma["Specs"];
}

$tjddh='';
if($_POST['ddh']<>''){
    $ddh = $_POST['ddh'];
    $tjddh = " and x.ddh = '$ddh' ";
}
$tjgoujian = '';
if($_POST['goujian']<>''){
    $goujian = $_POST['goujian'];
    $tjgoujian = " and (locate('$goujian',x.n1) >0 or locate( '$goujian',x.n2 )>0) ";
}
$machines = '';
if($_POST['machine'] <> ''){
    $machine = $_POST['machine'];
    $machines = " AND ((locate('$machine' ,x.machine1) >0 and x.n1<>'') OR (locate('$machine' , x.machine2) >0 and x.n2<>'')) ";

}
$materials ='';
if($_POST['material'] <> ''){
    $material = $_POST['material'];
    /*$sqlmm = "select id from material WHERE zzfy='$dwdm' AND MaterialName ='$material'";
    $res = mysql_query($sqlmm , $conn);
    $res = $res[0]['id'];*/
    $materials = " AND ((x.paper1 = $material and x.n1<>'') OR  (x.paper2 = $material and x.n2<>'')) ";
}

$prices = '';
if($_POST['price'] <> ''){
    $price = $_POST['price'];
    $prices = " AND (x.jg1= $price OR x.jg2=$price) ";
}

$ses = '';
if($_POST['minse'] <> '' || $_POST['maxse'] <> ''){
    $minse = $_POST['minse'] ? $_POST['minse'] : 0;
    $maxse = $_POST['maxse'] ? $_POST['maxse'] : 999999;

    if(is_nan($minse) || is_nan($maxse)){
        $minse = 0;
        $maxse = 99999;
    }
    $ses = " having ((sum(x.sl1*x.pnum1) > $minse and sum(x.sl1*x.pnum1) < $maxse) or (sum(x.sl2*x.pnum2) > $minse and sum(x.sl2*x.pnum2) < $maxse)) ";

}

$jes = '';
if($_POST['minje'] <> '' || $_POST['maxje'] <> ''){
    $minje = $_POST['minje'] ? $_POST['minje'] : 0.0;
    $maxje = $_POST['maxje'] ? $_POST['maxje'] : 999999.0;

    if(is_nan($minje) || is_nan($maxje)){
        $minje = 0.0;
        $maxje = 99999.0;
    }
}

//$sql = "select order_mainqt_readcode.machine,m.ddh,m.pczy,m.dje, m.pendtime,x.*,r.xm,r.bh from order_mxqt x left join order_mainqt m on x.ddh=m.ddh LEFT JOIN order_zh z on x.ddh = z.ddh LEFT JOIN order_mainqt_readcode on x.ddh= order_mainqt_readcode.ddh, b_ry r where locate('$czyid' , m.pczy)>0 and r.bh = '$czyid' and m.pendtime>='$d1 00:00:00' and m.pendtime<='$d2 23:59:59' and m.state<>'作废订单' $tjddh $tjgoujian $ses $machines $materials $prices group by x.id";
$sql = "select x.ddh,x.operator1,x.operator2,sum(x.jg1 * x.pnum1 * x.sl1 + x.jg2 * x.pnum2 * x.sl2) as dje, x.sdate, x.n1,x.n2,x.machine1,x.sl1,x.sl2,x.jg1,x.jg2,x.pnum1,x.pnum2,x.machine2,r.xm,r.bh,x.paper1,x.paper2 from order_mxqt x , b_ry r where (locate('$czyid' , x.operator1)>0 or locate('$czyid' , x.operator2)>0) and r.bh = '$czyid' and x.sdate>='$d1 00:00:00' and x.sdate<='$d2 23:59:59' $tjddh $tjgoujian $ses $machines $materials $prices group by x.id";

$rs = mysql_query($sql,$conn);

if ($_POST["bt2"]) {
    header("Content-type:application/vnd.ms-excel;charset=UTF-8");
    header("Content-Disposition:filename=" . iconv("utf-8", "gb2312", $czyid."操作员统计导出[" . $d1 . "-" . $d2 . "].xls"));
    header("Expires:0");
    header('Pragma:   public');
    header("Cache-control:must-revalidate,post-check=0,pre-check=0");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title>名片工坊-账户使用情况</title>
    <link href="../css/CITICcss.css" rel="stylesheet" type="text/css">
    <script src="../js/WdatePicker.js" type="text/javascript" language="javascript"></script>
    <script type="text/javascript" src="../js/jquery-1.8.3.min.js"></script>
    <style type="text/css">
        body{
            margin:20px;
        }
        .ptb td{
            text-align: center;
            line-height:25px;
        }
        #totalnum{
            display: block;
            height:40px;
            line-height:40px;
            padding-left:10px;
        }
        .detb td,.detb th{
            text-align: center;
            line-height:22px;
            height:24px;
        }
    </style>
</head>

<body style="overflow-x:hidden;overflow-y:auto">
<div>
    <form method="post">
        <div style="padding:10px; font-weight:bold; text-align: right;">
            <input name="bt1" type="submit" value="查 询" onclick="changeje()"/>
            <input name="bt2" type="submit" value="导 出"/>
        </div>

    <span id="totalnum">总金额</span>
    <table class="detb"  cellspacing="0" cellpadding="0" rules="all" border="0" id="gvOrder" style="border-width:0px;width:100%;border-collapse:collapse;border:1px solid #D3D3D3; border-bottom:0px;">
        <thead>
        <tr  class="td_title" style="height:30px;">
            <th>姓名</th>
            <th>订单号</th>
            <th>构件</th>
            <th>机型及颜色</th>
            <th>物料名称</th>
            <th>数量</th>
            <th>价格</th>
            <th>打印金额</th>
            <th>完成时间</th>
        </tr>
        <tr  class="td_title" style="height:30px;">
            <th></th>
            <th><input name="ddh" style="height:29px" placeholder="输入完整订单号" value="<? echo $_POST["ddh"] ?>"/></th>
            <th>
                <select style="height:29px" name="goujian" placeholder="请输入构件名称" >
                    <option value="">全部</option>
                    <option value="单张" <? if($goujian == '单张') echo 'selected'; ?>>单张</option>
                </select>
            </th>
            <th><select name="machine" style="height:29px;"><option value="">机型及颜色</option>
                <? $machiners = mysql_query("select * from b_machine",$conn);
                    while($row=mysql_fetch_assoc($machiners)){
                        ?>
                        <option value="<? echo $row['machine'] ?>" <? if ($machine == $row['machine']) echo "selected"; ?>><? echo $row['machine'] ?></option>
                    <? } ?>
<!--
                    <option value="5600" <?/* if ($machine == '5600') echo "selected"; */?>>5600</option>
                    <option value="7600" <?/* if ($machine == '7600') echo "selected"; */?>>7600</option>
                    <option value="7500" <?/* if ($machine == '7500') echo "selected"; */?>>7500</option>
                    <option value="10000" <?/* if ($machine == '10000') echo "selected"; */?>>10000</option>-->
                </select>
            </th>
            <th class="td_content" align="center">
                <select name="material" style="height:29px;">
                    <option value="">全部</option>

                    <?

                    /*$skrs = mysql_query("select * from material where zzfy=$dwdm order by id", $conn);
                    while ($skrow = mysql_fetch_array($skrs)) {
                        echo "<option value='" . $skrow[1] . "' ";
                        if ($material == $skrow[1])
                            echo "selected";
                        echo ">" . $skrow[2] . "</option>";
                    }*/
                    foreach($m as $item){
                        echo "<option value='" . $item['mid'] . "' ";
                        if($material == $item['mid'])
                            echo "selected";
                        echo ">" . $item['name'] . "</option>";
                    }
                    ?>

                </select>
            </th>
            <th class="td_content" align="center">
                <!--<input name="minse"
                       style="height:29px;width:60px"
                       placeholder="数额下限"
                       value="<?/* echo $minse */?>"/> ~　<input
                    name="maxse" style="height:29px;width:60px;" placeholder="数额上限"
                    value="<?/* echo $maxse  */?>"/>-->
            </th>
            <th class="td_content" align="center">
                <input name="price" style="height:29px" placeholder="输入单价" value="<? echo $price ?>"/>
            </th>
            <th class="td_content" align="center">
                <!--<input name="minje"
                       style="height:29px;width:60px"
                       placeholder="金额下限"
                       value="<?/* echo $minje */?>"/> ~　<input
                    name="maxje" style="height:29px;width:60px;" placeholder="金额上限"
                    value="<?/* echo $maxje */?>"/>-->
            </th>
            <th><input onClick="WdatePicker();" maxlength="50" class="Wdate" style="width: 100px;" type="text" name="rq1" id="rq1" value="<? echo $d1;?>" />~<input onClick="WdatePicker();" maxlength="50" class="Wdate" style="width: 100px;" type="text" name="rq2" id="rq2" value="<? echo $d2;?>" /></th>
        </tr>
        </thead>
        <tbody>
        <? for($i = 0;$i<mysql_num_rows($rs);$i++){

            if($_POST['machine']<>'' || $_POST['material']<>''){

                if($_POST['machine']<>'' && $_POST['material']<>''){

                    if( mysql_result($rs,$i,'machine1')==$_POST['machine'] && mysql_result($rs,$i,'paper1')==$_POST['material']){

                        ?>
                        <tr>
                            <td><? echo mysql_result($rs,$i,'xm') ?></td>
                            <td><? echo mysql_result($rs,$i,'ddh') ?></td>
                            <td><? echo mysql_result($rs,$i,'n1') ?></td>
                            <td><? echo mysql_result($rs,$i,'machine1');// 机型颜色?></td>
                            <td align="center"><? echo $m[mysql_result($rs,$i,'paper1')]["name"];// 物料名称?></td>
                            <td><? echo mysql_result($rs,$i,'pnum1')* mysql_result($rs,$i,'sl1'); ?></td>
                            <td><? echo mysql_result($rs,$i,'jg1') ?></td>
                            <td class="jetd"><? echo  mysql_result($rs,$i,'pnum1')* mysql_result($rs,$i,'sl1')*mysql_result($rs,$i,'jg1'); ?></td>
                            <td width="150px;"><? echo mysql_result($rs,$i,'sdate') ?></td>
                        </tr>
                        <?

                    }
                    if( mysql_result($rs,$i,'machine2')==$_POST['machine'] && mysql_result($rs,$i,'paper2')==$_POST['material']){

                        ?>
                        <tr>
                            <td><? echo mysql_result($rs,$i,'xm') ?></td>
                            <td><? echo mysql_result($rs,$i,'ddh') ?></td>
                            <td><? echo mysql_result($rs,$i,'n2') ?></td>
                            <td><? echo mysql_result($rs,$i,'machine2');// 机型颜色?></td>
                            <td align="center"><? echo $m[mysql_result($rs,$i,'paper2')]["name"];// 物料名称?></td>
                            <td><? echo mysql_result($rs,$i,'pnum2')* mysql_result($rs,$i,'sl2'); ?></td>
                            <td><? echo mysql_result($rs,$i,'jg2') ?></td>
                            <td class="jetd"><? echo mysql_result($rs,$i,'pnum2')* mysql_result($rs,$i,'sl2')*mysql_result($rs,$i,'jg2'); ?></td>
                            <td width="150px;"><? echo mysql_result($rs,$i,'sdate') ?></td>
                        </tr>
                        <?

                    }

                }elseif($_POST['machine']<>''){

                    if(mysql_result($rs,$i,'machine1')==$_POST['machine']){

                        ?>
                        <tr>
                            <td><? echo mysql_result($rs,$i,'xm') ?></td>
                            <td><? echo mysql_result($rs,$i,'ddh') ?></td>
                            <td><? echo mysql_result($rs,$i,'n1') ?></td>
                            <td><? echo mysql_result($rs,$i,'machine1');// 机型颜色?></td>
                            <td align="center"><? echo $m[mysql_result($rs,$i,'paper1')]["name"];// 物料名称?></td>
                            <td><? echo mysql_result($rs,$i,'pnum1')* mysql_result($rs,$i,'sl1'); ?></td>
                            <td><? echo mysql_result($rs,$i,'jg1') ?></td>
                            <td class="jetd"><? echo  mysql_result($rs,$i,'pnum1')* mysql_result($rs,$i,'sl1')*mysql_result($rs,$i,'jg1'); ?></td>
                            <td width="150px;"><? echo mysql_result($rs,$i,'sdate') ?></td>
                        </tr>
                        <?

                    }
                    if( mysql_result($rs,$i,'machine2')==$_POST['machine']){

                        ?>
                        <tr>
                            <td><? echo mysql_result($rs,$i,'xm') ?></td>
                            <td><? echo mysql_result($rs,$i,'ddh') ?></td>
                            <td><? echo mysql_result($rs,$i,'n2') ?></td>
                            <td><? echo mysql_result($rs,$i,'machine2');// 机型颜色?></td>
                            <td align="center"><? echo $m[mysql_result($rs,$i,'paper2')]["name"];// 物料名称?></td>
                            <td><? echo mysql_result($rs,$i,'pnum2')* mysql_result($rs,$i,'sl2'); ?></td>
                            <td><? echo mysql_result($rs,$i,'jg2') ?></td>
                            <td class="jetd"><? echo mysql_result($rs,$i,'pnum2')* mysql_result($rs,$i,'sl2')*mysql_result($rs,$i,'jg2'); ?></td>
                            <td width="150px;"><? echo mysql_result($rs,$i,'sdate') ?></td>
                        </tr>
                        <?

                    }

                }elseif($_POST['material']<>''){

                    if(mysql_result($rs,$i,'paper1')==$_POST['material']){

                        ?>
                        <tr>
                            <td><? echo mysql_result($rs,$i,'xm') ?></td>
                            <td><? echo mysql_result($rs,$i,'ddh') ?></td>
                            <td><? echo mysql_result($rs,$i,'n1') ?></td>
                            <td><? echo mysql_result($rs,$i,'machine1');// 机型颜色?></td>
                            <td align="center"><? echo $m[mysql_result($rs,$i,'paper1')]["name"];// 物料名称?></td>
                            <td><? echo mysql_result($rs,$i,'pnum1')* mysql_result($rs,$i,'sl1'); ?></td>
                            <td><? echo mysql_result($rs,$i,'jg1') ?></td>
                            <td class="jetd"><? echo  mysql_result($rs,$i,'pnum1')* mysql_result($rs,$i,'sl1')*mysql_result($rs,$i,'jg1'); ?></td>
                            <td width="150px;"><? echo mysql_result($rs,$i,'sdate') ?></td>
                        </tr>
                        <?

                    }
                    if(mysql_result($rs,$i,'paper2')==$_POST['material']){

                        ?>
                        <tr>
                            <td><? echo mysql_result($rs,$i,'xm') ?></td>
                            <td><? echo mysql_result($rs,$i,'ddh') ?></td>
                            <td><? echo mysql_result($rs,$i,'n2') ?></td>
                            <td><? echo mysql_result($rs,$i,'machine2');// 机型颜色?></td>
                            <td align="center"><? echo $m[mysql_result($rs,$i,'paper2')]["name"];// 物料名称?></td>
                            <td><? echo mysql_result($rs,$i,'pnum2')* mysql_result($rs,$i,'sl2'); ?></td>
                            <td><? echo mysql_result($rs,$i,'jg2') ?></td>
                            <td class="jetd"><? echo mysql_result($rs,$i,'pnum2')* mysql_result($rs,$i,'sl2')*mysql_result($rs,$i,'jg2'); ?></td>
                            <td width="150px;"><? echo mysql_result($rs,$i,'sdate') ?></td>
                        </tr>
                        <?

                    }

                }

            }else{
                //            构件一
                if(mysql_result($rs,$i,'n1')<>''){

                    /*if ($_POST['minse'] <> '' || $_POST['maxse'] <> '') {
                        $sl =mysql_result($rs,$i,'pnum1')* mysql_result($rs,$i,'sl1');
                        if ($_POST['minse'] <> '' && $_POST['maxse'] <> '') {
                            if ($sl > $_POST['maxse'] || $sl < $_POST['minse'])
                                continue;
                        } else if ($_POST['minse'] <> '') {
                            if ($sl < $_POST['minse'])
                                continue;
                        } else if ($_POST['maxse'] <> '') {
                            if ($sl > $_POST['maxse'])
                                continue;
                        }
                    }
                    if ($_POST['minje'] <> '' || $_POST['maxje'] <> '') {
                        $je = mysql_result($rs,$i,'pnum1')* mysql_result($rs,$i,'sl1')*mysql_result($rs,$i,'jg1');
                        if ($_POST['minje'] <> '' && $_POST['maxje'] <> '') {
                            if ($je > $_POST['maxje'] || $je < $_POST['minje'])
                                continue;
                        } else if ($_POST['minje'] <> '') {
                            if ($je < $_POST['minje'])
                                continue;
                        } else if ($_POST['maxje'] <> '') {
                            if ($je > $_POST['maxje'])
                                continue;
                        }
                    }*/

                    ?>
                    <tr>
                        <td><? echo mysql_result($rs,$i,'xm') ?></td>
                        <td><? echo mysql_result($rs,$i,'ddh') ?></td>
                        <td><? echo mysql_result($rs,$i,'n1') ?></td>
                        <td><? echo mysql_result($rs,$i,'machine1');// 机型颜色?></td>
                        <td align="center"><? echo $m[mysql_result($rs,$i,'paper1')]["name"];// 物料名称?></td>
                        <td><? echo mysql_result($rs,$i,'pnum1')* mysql_result($rs,$i,'sl1'); ?></td>
                        <td><? echo mysql_result($rs,$i,'jg1') ?></td>
                        <td class="jetd"><? echo  mysql_result($rs,$i,'pnum1')* mysql_result($rs,$i,'sl1')*mysql_result($rs,$i,'jg1'); ?></td>
                        <td width="150px;"><? echo mysql_result($rs,$i,'sdate') ?></td>
                    </tr>
                <? }
                if(mysql_result($rs,$i,'n2')<>''){
//                    构件二
                    /*if($_POST['price']<>'' && $price <> mysql_result($rs,$i,'jg2')){
                        continue;
                    }
                    if ($_POST['minse'] <> '' || $_POST['maxse'] <> '') {
                        $sl =mysql_result($rs,$i,'pnum2')* mysql_result($rs,$i,'sl2');
                        if ($_POST['minse'] <> '' && $_POST['maxse'] <> '') {
                            if ($sl > $_POST['maxse'] || $sl < $_POST['minse'])
                                continue;
                        } else if ($_POST['minse'] <> '') {
                            if ($sl < $_POST['minse'])
                                continue;
                        } else if ($_POST['maxse'] <> '') {
                            if ($sl > $_POST['maxse'])
                                continue;
                        }
                    }
                    if ($_POST['minje'] <> '' || $_POST['maxje'] <> '') {
                        $je = mysql_result($rs,$i,'pnum2')* mysql_result($rs,$i,'sl2')*mysql_result($rs,$i,'jg2');
                        if ($_POST['minje'] <> '' && $_POST['maxje'] <> '') {
                            if ($je > $_POST['maxje'] || $je < $_POST['minje'])
                                continue;
                        } else if ($_POST['minje'] <> '') {
                            if ($je < $_POST['minje'])
                                continue;
                        } else if ($_POST['maxje'] <> '') {
                            if ($je > $_POST['maxje'])
                                continue;
                        }
                    }*/
                    ?>
                    <tr>
                        <td><? echo mysql_result($rs,$i,'xm') ?></td>
                        <td><? echo mysql_result($rs,$i,'ddh') ?></td>
                        <td><? echo mysql_result($rs,$i,'n2') ?></td>
                        <td><? echo mysql_result($rs,$i,'machine2');// 机型颜色?></td>
                        <td align="center"><? echo $m[mysql_result($rs,$i,'paper2')]["name"];// 物料名称?></td>
                        <td><? echo mysql_result($rs,$i,'pnum2')* mysql_result($rs,$i,'sl2'); ?></td>
                        <td><? echo mysql_result($rs,$i,'jg2') ?></td>
                        <td class="jetd"><? echo mysql_result($rs,$i,'pnum2')* mysql_result($rs,$i,'sl2')*mysql_result($rs,$i,'jg2'); ?></td>
                        <td width="150px;"><? echo mysql_result($rs,$i,'sdate') ?></td>
                    </tr>
                <? }

            }

             ?>

        <? } ?>
        </tbody>
    </table>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        changeje();

    });
    function changeje(){
        var jetds = $('.jetd');
        var _l = jetds.length;
        var totalje = 0;
        for(var i =0; i < _l;i++){
            var _everyje = parseFloat(jetds[i].innerHTML);
            totalje += _everyje;
        }
        $('#totalnum').text('打印总金额：￥'+totalje.toFixed(2)+'元');
    }


</script>
</body>

</html>
