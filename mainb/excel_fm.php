<?
session_start();
require("../inc/conn.php");
// 不显示错误信息，调试时候注释掉。
//error_reporting(0);

if ($_SESSION["OK"] <> "OK") {
    echo "<script language=JavaScript>{window.location.href='error.php';}</script>";
    exit;

}
header("Content-type:text/html;charset=utf-8");

include '../commonfile/calc_area_get.php';

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
<script src="../js/WdatePicker.js" type="text/javascript" language="javascript"></script>

<form method="get">
    单据日期：<input onClick="WdatePicker();" maxlength="50" class="Wdate" style="cursor:pointer;" type="text" name="rq1" id="rq1" value="<? echo $d1; ?>" size="9" readonly/>～
    <input onClick="WdatePicker();" maxlength="50" class="Wdate" style="cursor:pointer;" type="text" name="rq2" id="rq2" value="<? echo $d2; ?>" size="9" readonly/>

    <? include '../commonfile/calc_options.php'; ?>

    <input name="bt1" type="submit" value="查 询"/>
    <? if ($_GET["bt1"]) { ?>　　
        <input name="bt2" type="submit" value="导 出"/>
    <? } ?>

    <?
    $dwdm = substr($_SESSION["GDWDM"], 0, 4);

    //条件筛选
    $ddh = '';
    if($_GET['ddh'] <> ''){
        $ddh = ' AND h.ddh="'. $_GET['ddh'] .'" ';
    }

    $hdfs = '';
    if($_GET['hdfs'] <> ''){
        $hdfs = $_GET['hdfs'];
        $hdfss = " AND h.fmfs = '".$_GET['hdfs'] . "' ";
    }

    $prices = '';
    if($_GET['price'] <> ''){
        $price = $_GET['price'];
        $prices = " AND h.jg= $price ";
    }

    $ffmczys ='';
    if($_GET['fmczy']<>''){

        $fmczy = $_GET['fmczy'];
        $rsfmczybh = mysql_result(mysql_query("select bh from b_ry where xm like '%". $fmczy ."%' and qx='fm' limit 1",$conn),0,'bh');
        $ffmczys = " AND locate('$rsfmczybh',h.fmczy )>0 ";
    }

    $ses = '';
    if($_GET['minse'] <> '' || $_GET['maxse'] <> ''){
        $minse = $_GET['minse'] ? $_GET['minse'] : 0;
        $maxse = $_GET['maxse'] ? $_GET['maxse'] : 999999;

        if(is_nan($minse) || is_nan($maxse)){
            $minse = 0;
            $maxse = 99999;
        }
        $ses = " AND h.sl>=$minse AND h.sl<=$maxse ";
    }

    $jes = '';
    if($_GET['minje'] <> '' || $_GET['maxje'] <> ''){

        $minje = $_GET['minje'] ? $_GET['minje'] : 0.0;
        $maxje = $_GET['maxje'] ? $_GET['maxje'] : 999999.0;

        if(is_nan($minje) || is_nan($maxje)){
            $minje = 0.0;
            $maxje = 99999.0;
        }
        $jes = " having je >= $minje and je <= $maxje ";

    }

//    pageing
    $restotal =mysql_query("select count(*) as rowcount , sum(h.sl*h.jg) as fmjetj from order_mxqt_fm h left join order_zh z on h.ddh=z.ddh left join order_mainqt m on h.ddh=m.ddh where fssj>='$d1 00:00:00' and fssj<='$d2 23:59:59' and z.khmc like '%{$d3}%' and zy='订单结算' and m.state<>'作废订单' and df>0 and zzfy in $dwdmStr $ddh $hdfss $ses $prices $ffmczys",$conn);

    $rowcount= mysql_result($restotal,0,'rowcount');
    $fmjetj = mysql_result($restotal,0,'fmjetj');

    include '../commonfile/paging_data.php';
    //    print
    if($_GET['bt2']){
        $startrow = 0;
        $pagenum = $rowcount;
    }

    if($_GET['minje'] <> '' || $_GET['maxje'] <> ''){

        $sql = "select z.khmc,z.fssj,h.* , (h.sl*h.jg) as je ,h.fmczy,h.finishdate from order_mxqt_fm h left join order_zh z on h.ddh=z.ddh left join order_mainqt m on h.ddh=m.ddh where fssj>='$d1 00:00:00' and fssj<='$d2 23:59:59' and z.khmc like '%{$d3}%' and zy='订单结算' and m.state<>'作废订单' and df>0 and zzfy in $dwdmStr $jes $ddh $hdfss $ses $prices $ffmczys group by h.id order by fssj limit $startrow ,$pagenum";

    }else{

        $sql = "select z.khmc,z.fssj,h.* ,h.fmczy,h.finishdate from order_mxqt_fm h left join order_zh z on h.ddh=z.ddh left join order_mainqt m on h.ddh=m.ddh where fssj>='$d1 00:00:00' and fssj<='$d2 23:59:59' and z.khmc like '%{$d3}%' and zy='订单结算' and m.state<>'作废订单' and df>0 and zzfy in $dwdmStr $ddh $hdfss $ses $prices $ffmczys order by fssj limit $startrow ,$pagenum";

    }

    $rshd = mysql_query($sql, $conn);

    if ($_GET["bt2"]) {
        header("Content-type:application/vnd.ms-excel;charset=UTF-8");
        header("Content-Disposition:filename=" . iconv("utf-8", "gb2312", "覆膜统计单[" . $d1 . "-" . $d2 . "].xls"));
        header("Expires:0");
        header('Pragma:   public');
        header("Cache-control:must-revalidate,post-check=0,pre-check=0");
    }
    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
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
            .fmtb td{
                text-align: center;
                padding: 5px 0;
            }
        </style>
    </head>

    <body style="font-size:12px">

    <div class="suminfo">
        <span>覆膜总数:<? echo $rowcount; ?></span>
        <span>覆膜金额统计:<? echo $fmjetj; ?></span>
    </div>
    <span id='xxx' style="display:none"></span>
    <table class="fmtb" width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:0px;">
        <tbody>
        <tr>
            <td valign="top">
                <div style="padding:15px 4px 22px 4px; color:#58595B">
                    <div class="bot_line"></div>
                    <div class="page">


                        <div id="AspNetPager2" style="width:100%;text-align:right;">
                            <table cellspacing="0" cellpadding="0" rules="all" border="1" id="gvOrder"
                                   style="border-width:0px;width:100%;border-collapse:collapse;border:1px solid #D3D3D3; border-bottom:1px solid #D3D3D3; font-size:12px">
                                <tbody>
                                <tr class="td_title" style="height:30px;">
                                    <th scope="col">单据日期</th>
                                    <th scope="col">订单号</th>
                                    <th scope="col">客户名称</th>
                                    <th scope="col">覆膜方式</th>
                                    <th scope="col">规格</th>
                                    <th scope="col">Unit</th>
                                    <th scope="col">数量</th>
                                    <th scope="col">单位</th>
                                    <th scope="col">加工单价</th>
                                    <th scope="col">金额</th>
                                    <th scope="col">单/双</th>
                                    <th scope="col">相关尺寸</th>
                                    <th scope="col">备注</th>
                                    <th scope="col">覆膜操作员</th>
                                    <th scope="col">覆膜扫码时间</th>
                                </tr>
                                <? if (!$_GET['bt2']) { ?>
                                    <tr style="height:30px;">

                                        <td>

                                        </td>
                                        <td>
                                            <input name="ddh" type="text" placeholder="输入订单号" style="height:29px;"
                                                   value="<? echo $_GET["ddh"] ?>"/>
                                        </td>
                                        <td>
                                            <input type="text" name="fkhmc" width="15"
                                                   value="<? echo $d3 == '%' ? '' : $d3; ?>"
                                                   placeholder="输入客户名称" style="height:29px;"/>
                                        </td>

                                        <td>
                                            <select name="hdfs" style="height:29px;">
                                                <option value="">全部</option>

                                                <? $hdli = mysql_query("select DISTINCT fumo from b_fumo", $conn);
                                                while ($skrow = mysql_fetch_array($hdli)) {
                                                    echo "<option value='" . $skrow[0] . "' ";
                                                    if ($hdfs == $skrow[0])
                                                    {echo "selected";}
                                                    echo ">" . $skrow[0] . "</option>";
                                                } ?>

                                            </select>
                                        </td>
                                        <td>

                                        </td>
                                        <td>

                                        </td>

                                        <td>
                                            <input name="minse" style="height:29px;width:60px" placeholder="数额下限" value="<? echo $minse ?>"/> ~　<input name="maxse" style="height:29px;width:60px;" placeholder="数额上限" value="<? echo $maxse ?>"/>
                                        </td>
                                        <td></td>
                                        <td>
                                            <input name="price" style="height:29px" placeholder="输入单价" value="<? echo $price ?>"/>
                                        </td>
                                        <td>
                                            <input name="minje" style="height:29px;width:60px" placeholder="金额下限" class="minje" value="<? echo $minje ?>" onblur="jesx();"/> ~　<input name="maxje" style="height:29px;width:60px;" placeholder="金额上限" class="maxje" value="<? echo $maxje ?>" onblur="jesx();"/>
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>
                                            <input name="fmczy" type="text" placeholder="输入覆膜操作员" style="height:29px;" value="<? echo $fmczy ?>"/>
                                        </td>
                                        <td></td>
                                    </tr>
                                <? } ?>
                                <? $total = 0;
                                while ($hd = mysql_fetch_array($rshd)) { ?>
                                    <tr style="height:30px">
                                        <td><? echo $hd["fssj"];// 单据日期?></td>
                                        <td><? echo $hd["ddh"]; // 订单号?></td>
                                        <td><? echo $hd["khmc"];// 客户名称?></td>
                                        <td><? echo $hd["fmfs"];// 后加工方式?></td>
                                        <td><? echo $hd["cpcc"];// 规格?></td>
                                        <td><? echo $hd["jldw"];// Unit?></td>
                                        <td><? echo $hd["sl"];  // 数量?></td>
                                        <td><? // 单位?></td>
                                        <td><? echo $hd["jg"];  // 单价?></td>
                                        <td><? echo $hd["jg"] * $hd["sl"];
                                            $total += $hd["jg"] * $hd["sl"]; // 金额?></td>
                                        <td><? // 单双?></td>
                                        <td><? // 相关尺寸?></td>
                                        <td><? echo $hd["memo"];// 备注?></td>
                                        <td><?

                                            $fumoczy =$hd['fmczy'];
                                            $sqlry = "select xm from b_ry where bh = '$fumoczy'";
                                            $rs = mysql_query($sqlry,$conn);

                                            if(!empty($rs) && mysql_num_rows($rs)>0){
                                                $fumoczyxm = mysql_result($rs,0,'xm');
                                            }

                                            echo $fumoczyxm;
                                            ?></td>
                                        <td><? echo $hd['finishdate']; ?></td>
                                    </tr>
                                <? } ?>
                                <tr style="height: 30px">
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><? echo $total; ?></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <br>
                    </div>
        </tbody>
    </table>
</form>
<?
//pageing
$param = 'rq1='.$d1.'&rq2='.$d2.'&fkhmc='.$d3.'&ddh='.$_GET['ddh'].'&hdfs='.$hdfs.'&minse='.$minse.'&maxse='.$maxse.'&minje='.$minje.'&maxje='.$maxje.'&fmczy='.$fmczy;

include "../commonfile/paging_show.php";
?>
</body>
</html>
