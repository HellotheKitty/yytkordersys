<?
session_start();
require("../inc/conn.php");

?>
<?

include '../commonfile/calc_area_ry.php';

$tss = "全部信息";

$d1 = $_POST["rq1"];
if ($d1 == "") {
    $d1 = date("Y-m-") . "01";
    $ss = "";
    $tss = "全部信息";
}
$d2 = $_POST["rq2"];
if ($d2 == "") {
    $d2 = date("Y-m-d");
}
if ($_GET["dq"] <> "") $dq = urldecode($_GET["dq"]); else $dq = "%";

if ($_POST["bt2"]) {
    header("Content-type:application/vnd.ms-excel;charset=UTF-8");
    header("Content-Disposition:filename=" . iconv("utf-8", "gb2312", "分客服销售统计导出[" . $d1 . "-" . $d2 . "].xls"));
    header("Expires:0");
    header('Pragma:   public');
    header("Cache-control:must-revalidate,post-check=0,pre-check=0");
}


?>
<?
//ajax获取收款分类数据
if ($_GET['kfid']) {

    $id = $_GET['kfid'];
    $skfs = $_GET['skfs'];
    $d1= $_GET['d1'];
    $d2 = $_GET['d2'];

//    $sql = "select sum(df) as fenje from order_zh where ddh in (select ddh from order_mainqt where xsbh='$id' and ddate >= '$d1 00:00:00' and ddate <= '$d2 23:59:59' and state <> '新建订单') and  xsbh='$skfs'";
    $sql = "select sum(df) as fenje from order_zh LEFT JOIN order_mainqt on order_mainqt.ddh =order_zh.ddh  where order_mainqt.xsbh='$id' and sksj >= '$d1 00:00:00' and sksj <= '$d2 23:59:59' and state <> '新建订单' and order_mainqt.state<>'作废订单' and locate('返工',order_mainqt.khmc) = 0 and order_zh.xsbh='$skfs'";

    $res = mysql_query($sql,$conn);

    $rs = mysql_result($res,0,'fenje');
    $data = array(
      'fenje' => $rs
    );
    echo json_encode($data);

    exit();
}
if($_GET['skfstj']){
    $skfstj = $_GET['skfstj'];
    $sql = "";
    $res = mysql_query($sql,$conn);

}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title>名片工坊-账户使用情况</title>
    <link href="../css/CITICcss.css" rel="stylesheet" type="text/css">
    <script src="../js/WdatePicker.js" type="text/javascript" language="javascript"></script>
    <script src="../js/jquery-1.8.3.min.js" type="text/javascript"></script>
    <base target="_self"/>
</head>
<style type="text/css">
    #table_total th, #table_total td{
        text-align: center;
    }
    #table_total td{
        border-bottom: 1px solid #d3d3d3;
        padding: 5px 0;
    }
</style>
<body style="overflow-x:hidden;overflow-y:auto">
<Div style="width:900px; margin:0px auto;">
    <Div id=Calendar scrolling="no"
         style="border:0px solid #EEEEEE ;position: absolute; margin-top:150px; margin-left: 5px; width: 150; height: 137; z-index: 200; filter :\'progid:DXImageTransform.Microsoft.Shadow(direction=135,color=#AAAAAA,strength=4)\' ;display: none"></Div>
</Div>
<form name="form1" method="post" action="YKcw_xstj.php?dq=<? echo $_GET["dq"] ?>" id="form1">

    <div style="width:900px">

        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:0px;">
            <tbody>
            <tr>
                <td valign="top">
                    <div style="padding:5px 32px 22px 55px; color:#58595B">
                        <div style="padding-bottom:10px; font-weight:bold;"></div>
                        <div>
                            <? if($_POST['bt2']==''){
                                ?>
                                开始日期：
                                <input onClick="WdatePicker();" maxlength="50" class="Wdate" style="width: 100px;"
                                       type="text" name="rq1" id="rq1" value="<? echo $d1; ?>"/>
                                &nbsp;&nbsp;&nbsp;&nbsp;结束日期：<input onClick="WdatePicker();" maxlength="50" class="Wdate" style="width: 100px;" type="text" name="rq2" id="rq2" value="<? echo $d2; ?>"/>&nbsp;

                                <? include '../commonfile/calc_options.php'; ?>

                                <input name="bt1" type="submit" value="查 询"/>    <input name="bt2" type="submit" value="导 出"/>

                                <?
                            } ?>

                            <br>

                            <?
                            //    $rs=mysql_query("select b_ry.bh,b_ry.xm,sum(dje+ifnull(kdje,0)),group_concat(m.ddh) ddh,count(ddh) ddsl from b_ry,order_mainqt m where b_ry.dwdm='".$_SESSION["GDWDM"]."' and m.xsbh=b_ry.bh and ddate>='$d1 00:00:00' and ddate<='$d2 23:59:59' and m.state<>'新建订单' group by m.xsbh  order by m.xsbh",$conn);

                            $sql = "select r.xm, r.bh, z.xsbh, sum(z.df) as ysje, sum(m.dje+ifnull(kdje,0)) as dje , count(m.ddh) as ddsl from b_ry r,  order_zh z left join order_mainqt m on m.ddh = z.ddh WHERE m.xsbh = r.bh  and r.dwdm in $dwdmStr and z.sksj >= '$d1 00:00:00' and z.sksj <= '$d2 23:59:59' and m.state <> '新建订单' and m.state<>'作废订单' and locate('返工',m.khmc) = 0 group by m.xsbh order by ddsl DESC ";

                            //    $sql = "select r.xm, r.bh, z.xsbh, sum(z.df) as ysje, sum(m.dje+ifnull(kdje,0)) as dje , count(m.ddh) as ddsl from b_ry r, order_mainqt m , order_zh z WHERE m.xsbh = r.bh and m.ddh = z.ddh and r.dwdm='$dwdm' and ddate >= '$d1 00:00:00' and ddate <= '$d2 $d2 23:59:59' and m.state <> '新建订单' group by m.xsbh order by ddsl DESC ";

                            $rs = mysql_query($sql, $conn);

                            ?>
                            <table id="table_total" cellspacing="0" cellpadding="0" rules="all" border="0" class="gvOrder" style="border-width:0px;width:100%;border-collapse:collapse;border:1px solid #D3D3D3; border-bottom:0px;">
                                <tbody>
                                <tr class="td_title" style="height:30px;">
                                    <th rowspan="2" scope="col">销售编号</th>
                                    <th rowspan="2" scope="col">姓名</th>
                                    <th rowspan="2" scope="col">订单数量</th>
                                    <th rowspan="2" scope="col">订单金额</th>
                                    <th colspan="6" scope="col">收款金额</th>
                                </tr>
                                <tr class="td_title" style="height:30px;">
                                    <th scope="col">现金</th>
                                    <th scope="col">支票</th>
                                    <th scope="col">POS机招行</th>
                                    <th scope="col">汇款</th>
                                    <th scope="col">预存扣款</th>
                                    <th scope="col">小计</th>
                                </tr>
                                <? $dje = 0;
                                $xj = 0;

                                $zj = 0;
                                $ddsl = 0;
                                for ($i = 0; $i < mysql_num_rows($rs); $i++) {

                                    ?>
                                    <tr>
                                        <td style="width:77px;"><? echo mysql_result($rs, $i, 1); ?></td>
                                        <td style="width:77px;"><? echo mysql_result($rs, $i, 'xm'); ?></td>
                                        <td style="width:77px;"><span style="width:77px;"><? echo mysql_result($rs, $i, "ddsl"); ?></span>
                                        </td>
                                        <td style="width:80px;"><? echo mysql_result($rs, $i, 'dje'); ?></td>
                                        <td style="width:90px;">0
                                            <input class="skleixing" style="display: none;" datatype="现金" value='<? echo mysql_result($rs, $i, 'bh') ?>'/>
                                        </td>
                                        <td style="width:90px;">0
                                            <input class="skleixing" type="hidden" datatype="支票" value='<? echo mysql_result($rs, $i, 'bh') ?>'/>
                                        </td>
                                        <td style="width:90px;">0
                                            <input class="skleixing" type="hidden" datatype="POS机招行" value='<? echo mysql_result($rs, $i, 'bh') ?>'/>
                                        </td>
                                        <td style="width:90px;">0
                                            <input class="skleixing" type="hidden" datatype="汇款" value='<? echo mysql_result($rs, $i, 'bh') ?>'/>
                                        </td>
                                        <td style="width:90px;">0
                                            <input class="skleixing" type="hidden" datatype="预存扣款" value='<? echo mysql_result($rs, $i, 'bh') ?>'/>
                                        </td>
                                        <td style="width:80px;"><? echo mysql_result($rs, $i, 'ysje')?mysql_result($rs, $i, 'ysje'):0.00; ?></td>

                                    </tr>
                                    <?

                                    $dje += mysql_result($rs, $i, 'dje');
                                    $zj +=  mysql_result($rs, $i, 'ysje');
                                    $ddsl += mysql_result($rs, $i, "ddsl");
                                }
                                ?>
                                <?
                                $sql2 = "select  z.xsbh, sum(z.df) as je from b_ry r, order_mainqt m left join order_zh z on m.ddh = z.ddh WHERE m.xsbh = r.bh  and r.dwdm in $dwdmStr and ddate >= '$d1 00:00:00' and ddate <= '$d2 23:59:59' and m.state <> '新建订单' and m.state<>'作废订单' and locate('返工',m.khmc) = 0 group by z.xsbh";
                                $res = mysql_query($sql2,$conn);

                                for($i=0;$i<mysql_num_rows($res);$i++){
                                    $fs = mysql_result($res,$i,'xsbh')?mysql_result($res,$i,'xsbh'):'';
                                    switch ($fs){
                                        case '现金':
                                            $xje = mysql_result($res,$i,'je')?mysql_result($res,$i,'je'): 0;
                                            break;
                                        case '支票':
                                            $zp = mysql_result($res,$i,'je')?mysql_result($res,$i,'je'): 0;
                                            break;
                                        case 'POS机招行':
                                            $pos = mysql_result($res,$i,'je')?mysql_result($res,$i,'je'): 0;
                                            break;
                                        case '汇款':
                                            $hk = mysql_result($res,$i,'je')?mysql_result($res,$i,'je'): 0;
                                            break;
                                        case '预存扣款':
                                            $yc = mysql_result($res,$i,'je')?mysql_result($res,$i,'je'): 0;
                                            break;
                                        default :
                                            $je = mysql_result($res,$i,'je')?mysql_result($res,$i,'je'): 0;
                                            break;

                                    }
                                }

                                ?>
                                <tr>
                                    <td colspan="2" style="width:77px;">合计：</td>
                                    <td style="width:77px;"><? echo $ddsl; ?></td>
                                    <td style="width:80px;"><? echo $dje; ?><br></td>
                                    <td style="width:90px;"><? echo $xje?$xje:0; ?></td>
                                    <td style="width:90px;"><? echo $zp?$zp:0; ?></td>
                                    <td style="width:90px;"><? echo $pos?$pos:0; ?></td>
                                    <td style="width:90px;"><? echo $hk?$hk:0; ?></td>
                                    <td style="width:90px;"><? echo $yc?$yc:0; ?></td>
                                    <td style="width:90px;"><? echo $zj; ?></td>
                                </tr>
                                </tbody>
                            </table>

                            <br>
                        </div>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>

    </div>

    </div>
</form>
<script type="text/javascript">

    function skleixing(_this,_id,_skfs,_d1,_d2) {

//        var _id = _this.val();
//        var _skfs = _this.attr('datatype');
//        var _d1 = $('#rq1').val();
//        var _d2 = $('#rq2').val();
        var sendData = "kfid=" + _id + '&skfs=' + _skfs + '&d1='+_d1 + '&d2=' + _d2;
        $.ajax({
            type: "GET",
            data: sendData,
            dataType: 'json',
            success: function (data) {
                if(data.fenje == null){
                    var _fenje = 0.00;
                }
                else var _fenje = data.fenje;
                _this.closest('td').html(_fenje);
            },
            error: function(){
//                alert('发生错误');
            }

        });

    };
    $(document).ready(function(){
        var _button =$('#table_total').find('.skleixing');

        var _l = _button.length;
        for(var i=0;i < _l;i++){

            var _cur = $(_button[i]);
            var _id = _cur.val();
            var _skfs = _cur.attr('datatype');
            var _d1 = $('#rq1').val();
            var _d2 = $('#rq2').val();

            skleixing(_cur,_id,_skfs,_d1,_d2);

        }
    });
</script>

</body>
</html>
