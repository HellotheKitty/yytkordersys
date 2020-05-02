<?
// 印前统计
session_start();
require("../inc/conn.php");
header("Content-type:text/html;charset=utf-8");

include '../commonfile/calc_area_get.php';

// require("inc/conn.php");
// 不显示错误信息，调试时候注释掉。
error_reporting(0);
/*
if ($_SESSION["OK"]<>"OK") {
echo "<script language=JavaScript>{window.location.href='error.php';}</script>";
exit;

}*/
@$d1 = $_GET["rq1"];
if ($d1 == "") {
    $d1 = date("Y-m-") . "01";
    $ss = "";
    $tss = "全部信息";
}
@$d2 = $_GET["rq2"];
if ($d2 == "") {
    $d2 = date("Y-m-d");
}
@$d3 = $_GET["fkhmc"];
if ($d3 == "") {
    $d3 = "";
}
?>
<!DOCTYPE html>
<html>
<script src="../js/WdatePicker.js" type="text/javascript" language="javascript"></script>
<form name ='form1' method="get">
    按日期:<input onClick="WdatePicker();" maxlength="50" class="Wdate" style="cursor:pointer;" type="text" name="rq1"
               id="rq1" value="<? echo $d1; ?>" size="9" readonly/>～
    <input onClick="WdatePicker();" maxlength="50" class="Wdate" style="cursor:pointer;" type="text" name="rq2"
           id="rq2" value="<? echo $d2; ?>" size="9" readonly/>

    <? include '../commonfile/calc_options.php'; ?>

    <input name="bt1" type="submit" value="查 询"/>

    　　<input name="bt2" type="submit" value="导 出"/>

    <?
    //    if (!$_GET["bt1"] && !$_GET["bt2"]) {
    //        echo "查询的时间为结算收款时间，查找的结果为已结算的订单，这些订单可能未生成配送单或暂未配送。";
    //        exit;
    //    }
    $dwdm = substr($_SESSION["GDWDM"], 0, 4);


    //$sql = "select z.khmc,z.fssj,h.* from order_mxqt_hd h left join order_zh z on h.ddhao=z.ddh left join order_mainqt m on h.ddhao=m.ddh where fssj>='$d1 00:00:00' and fssj<='$d2 23:59:59' and z.khmc like '%{$d3}%' and df>0 and zzfy='$dwdm' order by fssj";
    //echo $sql;
    // 获取物料
    if(substr($dwdm,0,2) == '33'){
        $sqlm = "select * from material where zzfy='3301'";
    }elseif(substr($dwdm,0,2) == '34'){
        $sqlm = "select * from material where zzfy='3405'";
    }else{
        $sqlm = "select * from material where zzfy='3405'";

    }

    $mars = mysql_query($sqlm, $conn);
    while ($ma = mysql_fetch_array($mars)) {
        $m[$ma["id"]]["name"] = $ma["MaterialName"];
        $m[$ma["id"]]["spec"] = $ma["Specs"];
    }

    //条件筛选
    $ddh = '';
    if($_GET['ddh'] <> ''){
        $ddh = ' AND mx.ddh="'. $_GET['ddh'] .'" ';
    }

    $machines = '';
    if($_GET['machine'] <> ''){
        $machine = $_GET['machine'];
//        $machines = " AND ((locate('$machine' ,mx.machine1) >0 and mx.n1<>'') OR (locate('$machine' , mx.machine2) >0 and mx.n2<>'')) ";
        $machines = " AND (mx.workplace1 = '$machine' OR mx.workplace2 = '$machine') ";
    }

    $machinecolors= '';
    if($_GET['machinecolor'] <>''){
        $machinecolor = $_GET['machinecolor'];
        $machinecolors = " AND ((mx.machine1='$machinecolor' and mx.n1<>'') OR (mx.machine2='$machinecolor' and mx.n2<>'')) ";

    }
    $materials ='';
    if($_GET['material'] <> ''){
        $material = $_GET['material'];
        /*$sqlmm = "select id from material WHERE zzfy='$dwdm' AND MaterialName ='$material'";
        $res = mysql_query($sqlmm , $conn);
        $res = $res[0]['id'];*/
        $materials = " AND ((mx.paper1 = $material and mx.n1<>'') OR (mx.paper2 = $material and mx.n2<>'')) ";
    }

    $prices = '';
    if($_GET['price'] <> ''){
        $price = $_GET['price'];
        $prices = " AND (mx.jg1= $price OR mx.jg2=$price) ";
    }

    $fpczys = '';
    if($_GET['fpczy'] <> ''){
        $fpczy = $_GET['fpczy'];
        $rspczybh = mysql_result(mysql_query("select bh from b_ry where xm like '%". $fpczy ."%' limit 1",$conn),0,'bh');
        $fpczys = " AND (locate('$rspczybh',mx.operator1)>0 OR locate('$rspczybh',mx.operator2)>0) ";
    }

    $ses = '';
    if($_GET['minse'] <> '' || $_GET['maxse'] <> ''){
        $minse = $_GET['minse'] ? $_GET['minse'] : 0;
        $maxse = $_GET['maxse'] ? $_GET['maxse'] : 999999;

        if(is_nan($minse) || is_nan($maxse)){
            $minse = 0;
            $maxse = 99999;
        }
        $ses = " group by mx.id HAVING sum(mx.sl1*mx.pnum1+mx.sl2*mx.pnum2) >= $minse AND sum(mx.sl1*mx.pnum1+mx.sl2*mx.pnum2) <= $maxse";
    }

    $jes = '';
    if($_GET['minje'] <> '' || $_GET['maxje'] <> ''){
        $minje = $_GET['minje'] ? $_GET['minje'] : 0.0;
        $maxje = $_GET['maxje'] ? $_GET['maxje'] : 999999.0;

        if(is_nan($minje) || is_nan($maxje)){
            $minje = 0.0;
            $maxje = 99999.0;
        }
        $jes = " AND m.dje>=$minje AND m.dje<=$maxje ";
    }

    //    分页
    //    总条数 统计数据
    if($_GET['minse'] <> '' || $_GET['maxse'] <> ''){

        $restotal = mysql_query("select count(*) as rowcount ,count(DISTINCT mx.ddh) as ddcount , sum(mx.sl1*mx.pnum1 + ifnull(mx.sl2*mx.pnum2,0)) as zps , sum(mx.sl1*mx.pnum1*mx.jg1 + ifnull(mx.sl2*mx.pnum2*mx.jg2,0)) as zddje from order_mxqt mx left join order_mainqt m on mx.ddh=m.ddh left join order_zh z on mx.ddh=z.ddh where fssj>='$d1 00:00:00' and fssj<='$d2 23:59:59' and z.khmc like '%{$d3}%'  and zy='订单结算' and m.state<>'作废订单' and zzfy in $dwdmStr $ddh $machines $machinecolors $materials $jes $prices $fpczys $ses",$conn);

    }else{
        $restotal = mysql_query("select count(*) as rowcount,count(DISTINCT mx.ddh) as ddcount , sum(mx.sl1*mx.pnum1 + ifnull(mx.sl2*mx.pnum2,0)) as zps , sum(mx.sl1*mx.pnum1*mx.jg1 + ifnull(mx.sl2*mx.pnum2*mx.jg2,0)) as zddje from order_mxqt mx left join order_mainqt m on mx.ddh=m.ddh left join order_zh z on mx.ddh=z.ddh where fssj>='$d1 00:00:00' and fssj<='$d2 23:59:59' and z.khmc like '%{$d3}%'  and zy='订单结算' and m.state<>'作废订单' and zzfy in $dwdmStr $ddh $machines $machinecolors $materials $jes $prices $fpczys ",$conn);
    }
    $rowcount = mysql_result($restotal,0,'rowcount');
    $zddje = mysql_result($restotal,0,'zddje');
    $zps = mysql_result($restotal,0,'zps');

    include '../commonfile/paging_data.php';

    //打印daochu
    if($_GET["bt2"]){
        $startrow = 0;
        $pagenum = $rowcount;
    }

    $sql = "select mx.productname,mx.chicun , mx.sl, mx.n1,mx.machine1 , mx.paper1, mx.dsm1, mx.jldw1 , mx.pnum1,mx.sl1,mx.jg1,mx.n2, mx.machine2 , mx.paper2 , mx.dsm2 , mx.jldw2,mx.pnum2,mx.sl2,mx.jg2,mx.operator1,mx.operator2,mx.workplace1,mx.workplace2,mx.sdate,m.ddh,m.ddate,m.khmc,m.memo
            from order_mxqt mx left join order_mainqt m on mx.ddh=m.ddh left join order_zh z on mx.ddh=z.ddh
            where fssj>='$d1 00:00:00' and fssj<='$d2 23:59:59' and z.khmc like '%{$d3}%' and zy='订单结算' and m.state<>'作废订单'
            and zzfy in $dwdmStr $ddh $machines $machinecolors $materials $jes $prices $fpczys $ses order by fssj limit $startrow ,$pagenum ";

    $rsyq = mysql_query($sql, $conn);


    if ($_GET["bt2"]) {
        header("Content-type:application/vnd.ms-excel;charset=UTF-8");
        header("Content-Disposition:filename=" . iconv("utf-8", "gb2312", "直印印刷统计单[" . $d1 . "-" . $d2 . "].xls"));
        header("Expires:0");
        header('Pragma:   public');
        header("Cache-control:must-revalidate,post-check=0,pre-check=0");
    }
    ?>


    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>名片工坊-业务管理</title>
        <style type="text/css">
            .suminfo span{
                margin:20px;
                font-size:13px;
                font-weight:bold;
                color:#333;
            }
            .yqtb td{
                text-align: center;
            }
            .detail{
                display: block;
                color: darkgreen;
                font-weight: bold;
                font-size: 14px;
                margin: 5px auto;
            }
        </style>
    </head>

    <body style="font-size:12px">


    <div class="suminfo">
        <span>打印订单数:<? echo mysql_result($restotal,0,'ddcount'); ?></span>
        <span>打印总金额:<? echo $zddje; ?></span>
        <span>打印总P数:<? echo $zps; ?></span>
    </div>
    <span id='xxx' style="display:none"></span>
    <table class="yqtb" width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:0px;">
        <tbody>
        <tr>
            <td valign="top">
                <div style="padding:15px 4px 22px 4px; color:#58595B">
                    <div class="bot_line"></div>
                    <div class="page">
                            <table cellspacing="0" cellpadding="0" rules="all" border="1" id="gvOrder"
                                   style="border-width:0px;width:100%;border-collapse:collapse;border:1px solid #D3D3D3; border-bottom:1px solid #D3D3D3; font-size:12px">
                                <tbody>
                                <tr class="td_title" style="height:30px;">
                                    <th scope="col">订单号</th>
                                    <th scope="col">单据日期</th>
                                    <th scope="col">客户名称</th>
                                    <th scope="col">机型</th>
                                    <th scope="col">颜色</th>
                                    <th scope="col">物料名称</th>
                                    <th scope="col">规格</th>
                                    <th scope="col">单/双</th>
                                    <th scope="col">单位</th>
                                    <!--       <th  scope="col">数量</th>-->
                                    <th scope="col">总P数</th>
                                    <th scope="col">单价</th>
                                    <th scope="col">总金额</th>
                                    <th scope="col">备注</th>
                                    <th scope="col">打印操作员</th>
                                </tr>
                                <? if(!$_GET['bt2']){ ?>
                                    <tr style="height:30px;">
                                        <td class="td_content"><input name="ddh" type="text" placeholder="输入订单号" style="height:29px;" value="<? echo $_GET["ddh"] ?>"/>
                                        </td>
                                        <td class="td_content">

                                        </td>
                                        <td class="td_content">
                                            <input type="text" name="fkhmc" width="15" value="<? echo $d3 == "%" ? "" : $d3; ?>"
                                                   placeholder="输入客户名称" style="height:29px;"/>
                                        </td>

                                        <td class="td_content">
                                            <select name="machine" style="height:29px;">
                                                <option value="">全部</option>
                                                <option value="10000" <? if($machine == '10000') echo 'selected'; ?>>Hp10000</option>
                                                <option value="7600" <? if($machine == '7600') echo 'selected'; ?>>Hp7600</option>
                                                <option value="7500" <? if($machine == '7500') echo 'selected'; ?>>Hp7500</option>
                                                <option value="5600" <? if($machine == '5600') echo 'selected'; ?>>Hp5600</option>
                                                <option value="奥西黑白" <? if($machine == '奥西黑白') echo 'selected'; ?>>奥西黑白</option>
                                            </select>
                                            <!--  <a href="excel_machine.php" target="_blank" class="detail">查看详情</a>-->
                                        </td>
                                        <td>
                                            <select name="machinecolor" style="height:29px;">
                                                <option value="">全部</option>
                                                <? $machiners = mysql_query("select * from b_machine",$conn);
                                                while($row=mysql_fetch_assoc($machiners)){
                                                    ?>
                                                    <option value="<? echo $row['machine'] ?>" <? if ($machinecolor == $row['machine']) echo "selected"; ?>><? echo $row['machine'] ?></option>
                                                <? } ?>
                                            </select>
                                        </td>
                                        <td class="td_content">
                                            <select name="material" style="height:29px;">
                                                <option value="">全部</option>

                                                <? $skrs = mysql_query($sqlm, $conn);
                                                while ($skrow = mysql_fetch_array($skrs)) {
                                                    echo "<option value='" . $skrow[1] . "' ";
                                                    if ($material == $skrow[1])
                                                        echo "selected";
                                                    echo ">" . $skrow[2] . "</option>";
                                                } ?>

                                            </select>
                                        </td>
                                        <td class="td_content">
                                            <!--<select name="gg" style="height:29px;">
                                                <option value="">全部</option>
                                                <?/* $size = mysql_query("select * from base_zz_gg order by id", $conn);
                                                while ($skrow = mysql_fetch_array($size)) {
                                                    echo "<option value='" . $skrow[1] . "' ";
                                                    if ($gg == $skrow[1])
                                                        echo "selected";
                                                    echo ">" . $skrow[1] . "</option>";
                                                } */?>
                                            </select>-->
                                        </td>
                                        <td class="td_content">

                                        </td>
                                        <td class="td_content"></td>
                                        <td class="td_content">
                                            <input name="minse" style="height:29px;width:60px" placeholder="数额下限" value="<? echo $minse ?>"/> ~　<input name="maxse" style="height:29px;width:60px;" placeholder="数额上限" value="<? echo $maxse  ?>"/>
                                        </td>
                                        <td class="td_content">
                                            <input name="price" style="height:29px" placeholder="输入单价" value="<? echo $price ?>"/>
                                        </td>
                                        <td class="td_content">
                                            <input name="minje" style="height:29px;width:60px" placeholder="金额下限" value="<? echo $minje ?>"/> ~　<input name="maxje" style="height:29px;width:60px;" placeholder="金额上限" value="<? echo $maxje ?>"/>
                                        </td>
                                        <td class="td_content"></td>
                                        <td>
                                            <input type="text" name="fpczy" placeholder="输入打印操作员" value="<? echo $fpczy; ?>"/>
                                        </td>
                                    </tr>
                                <? } ?>
                                <? $total = 0;
                                while ($yq = mysql_fetch_array($rsyq)) {

                                    if($_GET['machine']<>'') {

                                        if ($yq["workplace1"] == $_GET['machine']) {
                                            ?>

                                            <tr style="height:30px">
                                                <td><a title="订单操作员" href="javascript:void(0);" onclick="window.open('../ncerp/jcsj/orderdetail.php?ddh=<? echo $yq['ddh'] ?>','签单操作员','height=400px,width=620px,top=100px,left=200px,location=no,menubar=no,resizable=no,scrollbars=yes,status=no,titlebar=no,toolbar=no,directories=no');"><? echo $yq["ddh"]; // 订单号
                                                        ?></a></td>
                                                <td><? echo $yq["fssj"];// 单据日期
                                                    ?></td>
                                                <td><? echo $yq["khmc"];// 客户名称
                                                    ?></td>
                                                <td><? echo $yq["workplace1"] ;// 机型
                                                    ?></td>
                                                <td><? echo $yq["machine1"] ;// 颜色
                                                    ?></td>
                                                <td><? echo $m[$yq["paper1"]]["name"];// 物料名称
                                                    ?></td>
                                                <td><? echo $m[$yq["paper1"]]["spec"];// 规格
                                                    ?></td>
                                                <td><? echo $yq["dsm1"];  // 单双
                                                    ?></td>
                                                <td><? echo $yq["jldw1"];  // 单位
                                                    ?></td>
                                                <!--	<td><? echo $yq["sl1"];  // 数量
                                                ?></td>-->
                                                <td><? echo $yq["pnum1"] * $yq["sl1"]; // P数
                                                    ?></td>
                                                <td><? echo $yq["jg1"];  // 单价
                                                    ?></td>
                                                <td><? echo $yq["jg1"] * $yq["sl1"] * $yq["pnum1"];
                                                    $total += $yq["jg1"] * $yq["sl1"] * $yq["pnum1"]; // 总金额
                                                    ?></td>
                                                <td><? // 备注 ?></td>
                                                <td><?  $pczys = explode(';',$yq['operator1']);
                                                    $pczyxm = '';
                                                    foreach($pczys as $pczy){

                                                        $sqlry = "select xm from b_ry where bh = '$pczy'";
                                                        $rspczy = mysql_query($sqlry,$conn);

                                                        if(!empty($rspczy) && mysql_num_rows($rspczy)>0){
                                                            $pczyxm .= mysql_result($rspczy,0,'xm').';';
                                                        }
                                                    }
                                                    echo substr($pczyxm,0,strlen($pczyxm)-1); ?></td>
                                            </tr>
                                        <? }

                                        if ($yq["workplace2"] == $_GET['machine']) {
                                            ?>

                                            <tr style="height:30px">
                                                <td><a title=""  href="javascript:void(0);" onclick="window.open('../ncerp/jcsj/orderdetail.php?ddh=<? echo $yq['ddh'] ?>','签单操作员','height=400px,width=620px,top=100px,left=200px,location=no,menubar=no,resizable=no,scrollbars=yes,status=no,titlebar=no,toolbar=no,directories=no');"><? echo $yq["ddh"]; // 订单号?></a></td>
                                                <td><? echo $yq["fssj"];// 单据日期?></td>
                                                <td><? echo $yq["khmc"];// 客户名称?></td>
                                                <td><? echo $yq["workplace2"];// 机型颜色?></td>
                                                <td><? echo $yq["machine2"];// 机型颜色?></td>
                                                <td><? echo $m[$yq["paper2"]]["name"];// 物料名称?></td>
                                                <td><? echo $m[$yq["paper2"]]["spec"];// 规格?></td>
                                                <td><? echo $yq["dsm2"];  // 单双?></td>
                                                <td><? echo $yq["jldw2"];  // 单位?></td>
                                                <!--	<td><? echo $yq["sl2"];  // 数量?></td>-->
                                                <td><? echo $yq["pnum2"] * $yq["sl2"]; // P数?></td>
                                                <td><? echo $yq["jg2"];  // 单价?></td>
                                                <td><? echo $yq["jg2"] * $yq["sl2"] * $yq["pnum2"];
                                                    $total += $yq["jg2"] * $yq["sl2"] * $yq["pnum2"]; // 总金额?></td>
                                                <td><?// 备注?></td>
                                                <td><?
                                                    $pczys = explode(';',$yq['operator2']);
                                                    $pczyxm = '';
                                                    foreach($pczys as $pczy){

                                                        $sqlry = "select xm from b_ry where bh = '$pczy'";
                                                        $rspczy = mysql_query($sqlry,$conn);

                                                        if(!empty($rspczy) && mysql_num_rows($rspczy)>0){
                                                            $pczyxm .= mysql_result($rspczy,0,'xm').';';

                                                        }
                                                    }
                                                    echo substr($pczyxm,0,strlen($pczyxm)-1);
                                                    ?></td>

                                            </tr>
                                            <?

                                        }

                                    }else{


                                        ?>
                                        <tr style="height:30px">
                                            <td><a title="" href="javascript:void(0);" onclick="window.open('../ncerp/jcsj/orderdetail.php?ddh=<? echo $yq['ddh'] ?>','签单操作员','height=400px,width=620px,top=100px,left=200px,location=no,menubar=no,resizable=no,scrollbars=yes,status=no,titlebar=no,toolbar=no,directories=no');"><? echo $yq["ddh"]; // 订单号?></a></td>
                                            <td><? echo $yq["fssj"];// 单据日期?></td>
                                            <td><? echo $yq["khmc"];// 客户名称?></td>
                                            <td><? echo $yq["workplace1"];// 机型颜色?></td>
                                            <td><? echo $yq["machine1"];// 颜色?></td>
                                            <td><? echo $m[$yq["paper1"]]["name"];// 物料名称?></td>
                                            <td><? echo $m[$yq["paper1"]]["spec"];// 规格?></td>
                                            <td><? echo $yq["dsm1"];  // 单双?></td>
                                            <td><? echo $yq["jldw1"];  // 单位?></td>
                                            <!--	<td><? echo $yq["sl1"];  // 数量?></td>-->
                                            <td><? echo $yq["pnum1"] * $yq["sl1"]; // P数?></td>
                                            <td><? echo $yq["jg1"];  // 单价?></td>
                                            <td><? echo $yq["jg1"] * $yq["sl1"] * $yq["pnum1"];
                                                $total += $yq["jg1"] * $yq["sl1"] * $yq["pnum1"]; // 总金额?></td>
                                            <td><? // 备注?></td>
                                            <td><?  $pczys = explode(';',$yq['operator1']);
                                                $pczyxm = '';
                                                foreach($pczys as $pczy){

                                                    $sqlry = "select xm from b_ry where bh = '$pczy'";
                                                    $rspczy = mysql_query($sqlry,$conn);

                                                    if(!empty($rspczy) && mysql_num_rows($rspczy)>0){
                                                        $pczyxm .= mysql_result($rspczy,0,'xm').';';
                                                    }
                                                }
                                                echo substr($pczyxm,0,strlen($pczyxm)-1); ?></td>

                                        </tr>
                                        <?
                                        if ($yq["n2"] <> "") { ?>
                                            <tr style="height:30px">
                                                <td><a title="" href="javascript:void(0);" onclick="window.open('../ncerp/jcsj/orderdetail.php?ddh=<? echo $yq['ddh'] ?>','签单操作员','height=400px,width=620px,top=100px,left=200px,location=no,menubar=no,resizable=no,scrollbars=yes,status=no,titlebar=no,toolbar=no,directories=no');"><? echo $yq["ddh"]; // 订单号?></a></td>
                                                <td><? echo $yq["fssj"];// 单据日期?></td>
                                                <td><? echo $yq["khmc"];// 客户名称?></td>
                                                <td><? echo $yq["workplace2"];// 机型颜色?></td>
                                                <td><? echo $yq["machine2"];// 颜色?></td>
                                                <td><? echo $m[$yq["paper2"]]["name"];// 物料名称?></td>
                                                <td><? echo $m[$yq["paper2"]]["spec"];// 规格?></td>
                                                <td><? echo $yq["dsm2"];  // 单双?></td>
                                                <td><? echo $yq["jldw2"];  // 单位?></td>
                                                <!--	<td><? echo $yq["sl2"];  // 数量?></td>-->
                                                <td><? echo $yq["pnum2"] * $yq["sl2"]; // P数?></td>
                                                <td><? echo $yq["jg2"];  // 单价?></td>
                                                <td><? echo $yq["jg2"] * $yq["sl2"] * $yq["pnum2"];
                                                    $total += $yq["jg2"] * $yq["sl2"] * $yq["pnum2"]; // 总金额?></td>
                                                <td><?// 备注?></td>
                                                <td><?  $pczys = explode(';',$yq['operator2']);
                                                    $pczyxm = '';
                                                    foreach($pczys as $pczy){

                                                        $sqlry = "select xm from b_ry where bh = '$pczy'";
                                                        $rspczy = mysql_query($sqlry,$conn);

                                                        if(!empty($rspczy) && mysql_num_rows($rspczy)>0){
                                                            $pczyxm .= mysql_result($rspczy,0,'xm').';';
                                                        }
                                                    }
                                                    echo substr($pczyxm,0,strlen($pczyxm)-1); ?></td>

                                            </tr>
                                            <?
                                        }
                                    }
                                } ?>
                                <tr style="height: 30px">
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td ></td>
                                    <td></td>
                                    <td></td>
                                    <td><? echo $total; ?></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                </tbody>
                            </table>


                        <br>

                    </div>
        </tbody>
    </table>
</form>
<?
//paging
$param = 'rq1='.$d1.'&rq2='.$d2.'&fkhmc='.$d3.'&ddh='.$ddh.'&machine='.$machine.'&machinecolor='.$machinecolor.'&material='.$material.'&price='.$price.'&fpczy='.$fpczy.'&minse='.$minse.'&maxse='.$maxse.'&minje='.$minje.'&maxje='.$maxje;

include '../commonfile/paging_show.php';
?>
</body>
</html>
