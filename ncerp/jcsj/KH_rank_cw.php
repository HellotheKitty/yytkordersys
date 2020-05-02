<?
session_start();
header("Content-Type:text/html;charset=UTF-8");
require("../../inc/conn.php");
if ($_SESSION["OK"]<>"OK") {
    echo "<script language=JavaScript>{window.location.href='../error.php';}</script>";
    exit; }?>

<?
$dwdm = substr($_SESSION["GDWDM"],0,4);

include '../../commonfile/calc_area_get.php';

//$tj = "(gdzk='$dwdm') ";
//if($dwdm == '3405')
//    $tj = "(gdzk='3405' or (gdzk like '340%' and khmc like '%外协%') or waixie = '3405') ";
////if ($_GET["zdm"]<>"") {$tj=$tj." and (khmc like '%".$_GET["zdm"]."%' or mpzh like '%".$_GET["zdm"]."%' or lxr like '%".$_GET["zdm"]."%')";}
//if ($_GET["zdm"]<>"") {$tj=$tj." and (khmc like '%".$_GET["zdm"]."%' or mpzh like '%".$_GET["zdm"]."%' or lxr like '%".$_GET["zdm"]."%')";}
//$rs=mysql_query("select * from base_kh where $tj order by id",$conn);


$d1=$_GET["rq1"];if ($d1=="") {$d1=date("Y-m-")."01";$ss="";$tss="全部信息";}
$d2=$_GET["rq2"];if ($d2=="") {$d2=date("Y-m-d");}
$fkhmc = $_GET['fkhmc'] ? $_GET['fkhmc'] : '';
$dwdm = substr($_SESSION["GDWDM"],0,4);

if($_GET["btout"]){
    header("Content-type:application/vnd.ms-excel;charset=UTF-8");
    header("Content-Disposition:filename=".iconv("utf-8","gb2312","客户订购排行导出[".$d1."-".$d2."].xls"));
    header("Expires:0");
    header('Pragma:   public'   );
    header("Cache-control:must-revalidate,GET-check=0,pre-check=0");
}
//pageing
$restotal = mysql_query("select count(DISTINCT m.khmc) as rowcount from order_mainqt m LEFT JOIN order_zh h ON m.ddh = h.ddh WHERE m.state <>'作废订单' and h.df>0 and (h.zy ='订单结算' or h.zy='订单订金') and h.fssj >= '$d1 00:00:00' and h.fssj <= '$d2 23:59:59' and m.khmc like '%$fkhmc%' and zzfy in $dwdmStr ",$conn);


$rowcount= mysql_result($restotal,0,'rowcount');

include '../../commonfile/paging_data.php';
//    print
if($_GET["btout"]){
    $startrow = 0;
    $pagenum = $rowcount;
}
//pageing end

$sql = "select m.khmc,count(m.ddh) as ddsl, sum(m.dje) as zje ,sum(h.df) as ysje , base_kh.lxdh from order_mainqt m LEFT JOIN order_zh h ON m.ddh = h.ddh left join base_kh on m.khmc = base_kh.khmc WHERE m.state <>'作废订单' and h.df>0 and (h.zy ='订单结算' or h.zy='订单订金') and h.fssj >= '$d1 00:00:00' and h.fssj <= '$d2 23:59:59' and m.khmc like '%$fkhmc%' and zzfy in $dwdmStr group by m.khmc order by zje DESC  limit $startrow ,$pagenum ";
$rs = mysql_query($sql,$conn);

?>
<!doctype html>
<html><head>
    <META http-equiv=Content-Type content="text/html; charset=utf-8">
    <LINK href="../../css/content.css" type=text/css rel=stylesheet>
    <SCRIPT language=JavaScript src="../../js/form.js"></SCRIPT>
    <SCRIPT language=JavaScript>
        function checkForm(){
            var charBag = "0123456789";
            if (!checkNotNull(form2.mpzh, "客户编号")) return false;
            if (!checkNotNull(form2.khmc, "客户名称")) return false;
            return true; }
    </SCRIPT>
    <script src="../../js/WdatePicker.js" type="text/javascript" language="javascript"></script>
<!--    <script type="text/javascript" src="../js/jquery-1.8.3.min.js"></script>-->
    <meta content="MSHTML 6.00.3790.1830" name=GENERATOR>
    <style type="text/css">
        .head{
            background-image: url('../images/nabg1.gif')
        }
    </style>

    <script language="JavaScript">
        <!--
        function suredo(src,q)
        {
            var ret;
            ret = confirm(q);
            if(ret!=false) window.location=src;
        }
        //-->
    </script>
</head>
<body text=#000000 bgColor=#ffffff topMargin=0>
<form method="GET" name="form1">

    <div style="padding-bottom:10px; font-weight:bold;"></div>
    开始日期：
    <input onClick="WdatePicker();" maxlength="50" class="Wdate" style="width: 100px;" type="text" name="rq1" id="rq1" value="<? echo $d1;?>" />
    &nbsp;&nbsp;&nbsp;&nbsp;结束日期：<input onClick="WdatePicker();" maxlength="50" class="Wdate" style="width: 100px;" type="text" name="rq2" id="rq2" value="<? echo $d2;?>" />&nbsp;

    客户名称:<input type="text" name="fkhmc" value="<? echo $fkhmc; ?>" />
    <? include '../../commonfile/calc_options.php'; ?>

    <input style="height:28px;" name="bt1" type="submit" value="查 询" onclick="changeje()" />

    <input style="height:28px;" name="btout" type="submit" value="导 出" />

    <br>

</form>
<form name="form2" method="GET" >
    <table cellSpacing=0 cellPadding=0 width="100%" border=0>
        <tbody>
        <tr>
            <td width="57%" height=13 class=guide style="background-image: url('../images/main_guide_bg2.gif')">
                <img src="../images/guide.gif"
                     align=absMiddle>客户信息列表</td>
            <td width="43%" align=right class=guide style="background-image: url('../images/main_guide_bg2.gif')">
                <img
                    src="../images/main_r.gif"></td>
        </tr>
        </tbody>
    </table><br>

    <span style="font-weight: bold;">活跃客户数量：<? echo $rowcount; ?></span>
    <table class=maintable cellSpacing=1 cellPadding=1 width="100%" border=0>
        <tbody>
        <tr>
            <td class=head  width="63">排名</td>
            <td class=head  width="103">客户名称</td>
            <td class=head  width="103">联系方式</td>
            <td class="head" width="80">余额</td>
            <td class=head  width="63">订单数</td>
            <td class=head  width="63">消费总金额(元)</td>
            <td class=head  width="63">折扣金额(元)</td>
            <td class=head  width="63">百分比</td>
        </tr>
        <? for($i=0;$i<mysql_num_rows($rs);$i++){

            $zje = mysql_result($rs , $i , 'zje');
            $ysje = mysql_result($rs , $i , 'ysje');
            $zkje = round($zje -$ysje , 2);

            if($ysje==0){
                $percent = '0.00%';
            }else{
                $percent =($zje==0 ? 0 : abs(round($zkje/$zje*100 , 2)) . '%') ;
            }
            ?>
            <tr>
                <td><? echo $startrow + $i+1 ?></td>
                <td><? $khmc = mysql_result($rs, $i, "khmc"); echo $khmc; ?></td>
                <td><? echo mysql_result($rs, $i, "lxdh"); ?></td>
                <td><?
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

                    echo $yue ; ?></td>
                <td><? echo mysql_result($rs , $i , 'ddsl') ?></td>
                <td><? echo $zje; ?></td>
                <td><? echo $zkje == 0 ? '0' : ($zkje >0 ?'减免'.$zkje:'多收'.abs($zkje)); ?></td>
                <td><? echo $percent; ?></td>
            </tr>

        <?
            $zddsl += intval(mysql_result($rs , $i , 'ddsl'));
            $totaldje += round($zje,2);
            $totalysje += round($ysje,2);
            $totalzkje += $zkje;
        }
        if($totalysje==0){
            $zpercent = '0.00%';
        }else{
            $zpercent = abs(number_format($totalzkje/$totaldje*100 , 2)) . '%';
        }
        ?>
        <tr>
            <td colspan="4" style="text-align: center;">总计:</td>
            <td><? echo $zddsl; ?></td>
            <td><? echo $totaldje; ?></td>
            <td>总折扣金额<? echo $totalzkje == 0 ? '0' : ($totalzkje >0 ?'减免'.$totalzkje:'多收'.abs($totalzkje)); ?></td>
            <td>平均百分比：<? echo $zpercent; ?></td>
        </tr>
        </tbody>
    </table>
    <?
    //    pageing
    $param = "fkhmc=$khmc";

    include '../../commonfile/paging_show.php'; ?>

</form>
</body>
</html>
