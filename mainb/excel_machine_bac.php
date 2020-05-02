<?
// 印前统计
session_start();
require("../inc/conn.php");
header("Content-type:text/html;charset=utf-8");

include '../commonfile/calc_area_ry_get.php';

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
    $d3 = "%";
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>机器打印数量</title>
    <style type="text/css">
        .topopt,.suminfo{margin:15px auto;
            width:70%;}
        .suminfo span{
            margin:20px;
            font-size:13px;
            font-weight:bold;
            color:#333;
        }
        .yqtb td{
            text-align: center;
            font-size:13px;
            height:25px;
            line-height:25px;
        }
        .yqtb{
            width:60%;
            margin:0 auto;
            border-collapse:collapse;
            border:1px solid #D3D3D3;
            border-bottom:1px solid #D3D3D3;
            font-size:12px
        }
    </style>
    <script src="../js/WdatePicker.js" type="text/javascript" language="javascript"></script>
</head>
<body>
<form name ='form1' method="get">
    <div class="topopt">
    按日期:<input onClick="WdatePicker();" maxlength="50" class="Wdate" style="cursor:pointer;" type="text" name="rq1" id="rq1" value="<? echo $d1; ?>" size="9" readonly/>～
    <input onClick="WdatePicker();" maxlength="50" class="Wdate" style="cursor:pointer;" type="text" name="rq2" id="rq2" value="<? echo $d2; ?>" size="9" readonly/>
<!--选择门店-->
        <? if (strlen($dw0) ==2){ //城市级  ?>

            <select name="seldw1" onchange="form1.submit();">
                <option value="所有门店">所有门店</option>
                <?

                $dw0.='0';
                $dwlist = mysql_query("select dwdm, dwmc, ssdq from b_dwdm where locate('$dw0',dwdm)>0 and locate('0000',dwdm)=0",$conn);

                while($dwitem = mysql_fetch_assoc($dwlist)){
                    ?>
                    <option value="<? echo $dwitem['dwdm'];?>" <? if($seldw == $dwitem['dwdm']){ echo "selected";} ?>><? echo $dwitem['dwmc'];?></option>

                <? } ?>
            </select>

        <? }elseif(strlen($dw0)== 1){  //中国区 ?>

            <select name="seldw2" onchange="form1.submit();">
                <option value="所有区域">所有区域</option>
                <option value="bj" <? if($seldw == 'bj'){ echo "selected";} ?>>北京区</option>
                <option value="sh" <? if($seldw == 'sh'){ echo "selected";} ?>>上海区</option>

            </select>
        <? }  ?>
        <!--end选择门店-->

        <input name="bt1" type="submit" value="查 询"/>

    <input name="bt2" type="submit" value="导 出"/>


    </div>
    <?
    //    if (!$_GET["bt1"] && !$_GET["bt2"]) {
    //        echo "查询的时间为结算收款时间，查找的结果为已结算的订单，这些订单可能未生成配送单或暂未配送。";
    //        exit;
    //    }
    $dwdm = substr($_SESSION["GDWDM"], 0, 4);

    //条件筛选
    $ddh = '';
    if($_GET['ddh'] <> ''){
        $ddh = ' AND mx.ddh like "%'. $_GET['ddh'] .'%" ';
    }

    $machines = '';
    if($_GET['machine'] <> ''){
        $machine = $_GET['machine'];
        $machines = " AND locate('$machine' ,mx.machine) >0 ";
    }


    $fpczys = '';
    if($_GET['fpczy'] <> ''){
        $fpczy = $_GET['fpczy'];
//        $rspczybh = mysql_result(mysql_query("select bh from b_ry where xm like '%". $fpczy ."%' limit 1",$conn),0,'bh');
        $fpczys = " AND locate('$fpczy',mx.czy)>0 ";
    }

    //    分页
    //    总条数 统计数据

        $restotal = mysql_query("select count(*) as rowcount, count(distinct mx.ddh) as ddcount,sum(dmx.sl1*dmx.pnum1 + ifnull(dmx.sl2*dmx.pnum2,0)) as zps from order_mxqt dmx LEFT JOIN order_mainqt_readcode mx on mx.ddh=dmx.ddh left join b_ry r on mx.czy = r.bh where r.dwdm in $dwdmStr and mx.sdate>='$d1 00:00:00' and mx.sdate<='$d2 23:59:59' $ddh $machines $fpczys ",$conn);
//        $restotal = mysql_query("select count(distinct mx.ddh) as rowcount,sum(dmx.sl1*dmx.pnum1 + ifnull(dmx.sl2*dmx.pnum2,0)) as zps from order_mxqt dmx LEFT JOIN order_mainqt_readcode mx on mx.ddh=dmx.ddh left join b_ry r on mx.czy = r.bh where r.dwdm in $dwdmStr and mx.sdate>='$d1 00:00:00' and mx.sdate<='$d2 23:59:59' $ddh $machines $fpczys ",$conn);

    $rowcount = mysql_result($restotal,0,'rowcount');
    $zps = mysql_result($restotal,0,'zps');

    include '../commonfile/paging_data.php';

    //打印daochu
    if($_GET["bt2"]){
        $startrow = 0;
        $pagenum = $rowcount;
    }

        $sql = "select mx.*,r.xm,sum(dmx.sl1*dmx.pnum1 + ifnull(dmx.sl2*dmx.pnum2,0)) as ps from  order_mxqt dmx LEFT JOIN order_mainqt_readcode mx on mx.ddh=dmx.ddh left join b_ry r on mx.czy = r.bh where r.dwdm in $dwdmStr and mx.sdate>='$d1 00:00:00' and mx.sdate<='$d2 23:59:59' $ddh $machines $fpczys group by mx.ddh order by mx.sdate desc limit $startrow ,$pagenum ";

    //    $file = 'log.txt';
    //    file_put_contents($file,$sql,FILE_APPEND);

    $rsyq = mysql_query($sql, $conn);


    if ($_GET["bt2"]) {
        header("Content-type:application/vnd.ms-excel;charset=UTF-8");
        header("Content-Disposition:filename=" . iconv("utf-8", "gb2312", "直印印刷统计单[" . $d1 . "-" . $d2 . "].xls"));
        header("Expires:0");
        header('Pragma:   public');
        header("Cache-control:must-revalidate,post-check=0,pre-check=0");
    }
    ?>
    <div class="suminfo">
        <span>订单数量:<? echo $rowcount; ?></span>
        <span>打印总P数:<? echo $zps; ?></span>
    </div>

        <table cellspacing="0" cellpadding="0" rules="all" border="1" id="gvOrder" class="yqtb">
            <thead>
            <tr class="td_title" style="height:30px;">
                <th scope="col">订单号</th>
                <th scope="col">签单日期</th>
                <th scope="col">机型及颜色</th>
                <th scope="col">总P数</th>
                <th scope="col">打印操作员</th>
            </tr>
            <? if(!$_GET['bt2']){ ?>
                <tr style="height:30px;">
                    <td><input name="ddh" type="text" placeholder="输入订单号" style="height:29px;" value="<? echo $_GET["ddh"] ?>"/></td>
                    <td></td>
                    <td>
                        <select name="machine" style="height:29px;">
                            <option value="">全部</option>
                            <option value="10000" <? if($machine == '10000') echo "selected"; ?>>Hp10000</option>
                            <option value="7600" <? if($machine == '7600') echo "selected"; ?>>Hp7600</option>
                            <option value="7500" <? if($machine == '7500') echo "selected"; ?>>Hp7500</option>
                            <option value="5600" <? if($machine == '5600') echo "selected"; ?>>Hp5600</option>
                        </select>
                    </td>
                    <td></td>
                    <td>
                        <select name="fpczy" style="height:29px;">
                            <option value="">全部</option>
                            <?
                            $rspczy = mysql_query("select bh,xm from b_ry where qx = 'sc' and dwdm='330100'",$conn);

                            while($itemczy = mysql_fetch_array($rspczy)){
                                ?>
                                <option value="<? echo $itemczy['bh']; ?>" <? if($fpczy == $itemczy['bh']) echo "selected"; ?>><? echo $itemczy['xm']; ?></option>

                                <?
                            }
                            ?>

                        </select>
                    </td>
                </tr>
            <? } ?>
            </thead>
            <tbody>
            <? while($item = mysql_fetch_array($rsyq)){

                ?>
                <tr>
                    <td><? echo $item['ddh']; ?></td>
                    <td><? echo $item['sdate']; ?></td>
                    <td><? echo $item['machine']; ?></td>
                    <td><? echo $item['ps']; ?></td>
                    <td><? echo $item['xm']; ?></td>
                </tr>
                <?
            } ?>
            </tbody>
    </table>
</form>
<?
//paging
$param = 'rq1='.$d1.'&rq2='.$d2.'&ddh='.$ddh.'&machine='.$machine.'&fpczy='.$fpczy;

include '../commonfile/paging_show.php';
?>
</body>
</html>
