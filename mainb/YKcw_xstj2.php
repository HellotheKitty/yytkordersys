<?
session_start();
require("../inc/conn.php");
?>
<? $tss = "全部信息";

include '../commonfile/calc_area.php';
//重新赋值
//if ( $_SESSION['GDWDM']=='340000' ) {
//    $seldw = '3405';
//    $dwdmStr = "('3405')";
//} elseif($_SESSION['GDWDM']=='330000'){
//    $seldw = '3301';
//    $dwdmStr = "('3301')";
//}elseif($_SESSION['GDWDM']=='300000'){
//    $seldw = 'sh';
//    $dwdmStr = "('3301')";
//}else{
//    $dwdmStr = "('" . $dwdm . "')";
//}


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
$khmc = $_POST["xstjkhmc"];
if ($khmc != "") $tj = " and base_kh.khmc like '%$khmc%' "; else $tj = "";
$khbh = $_POST['xstjkhbh'];
if($khbh != ''){$khbhsel = " and base_kh.mpzh like '%$khbh%' ";}else{$khbhsel="";}
$lxr = $_POST['xstjlxr'];
if($lxr != ''){$lxrsel = " and base_kh.lxr like '%$lxr%' ";}else{$lxrsel="";}
$lxdh = $_POST['xstjlxdh'];
if($lxdh != ''){$lxdhsel = " and base_kh.lxdh like '%$lxdh%' ";}else{$lxdhsel="";}
$xsbh = $_POST['xstjxsbh'];
if($xsbh != ''){$xsbhsel = " and base_kh.xsbh like '%$xsbh%' ";}else{$xsbhsel="";}

$pxfs = $_POST["pxfs"];
switch ($pxfs) {
    case 'ddje':
        $order = " order by tdje desc";
        break;
    case 'ddsl':
        $order = " order by ddsl desc";
        break;
    default:
        $order = " order by tdje desc";
}
if ($_GET["dq"] <> "") $dq = urldecode($_GET["dq"]); else $dq = "%";

if ($_POST["bt2"]) {
    header("Content-type:application/vnd.ms-excel;charset=UTF-8");
    header("Content-Disposition:filename=" . iconv("utf-8", "gb2312", "客户消费导出[" . $d1 . "-" . $d2 . "].xls"));
    header("Expires:0");
    header('Pragma:   public');
    header("Cache-control:must-revalidate,post-check=0,pre-check=0");
}
//ajax获取分类收款数据
if ($_GET['khmc']) {

    $khmc = $_GET['khmc'];

    $d1= $_GET['d1'];
    $d2 = $_GET['d2'];
//    $sql = "select sum(df) as fenje , order_zh.xsbh as skfs,order_zh.ddh from order_zh where ddh in (select ddh from order_mainqt where khmc='$khmc' and ddate>='$d1 00:00:00' and ddate<='$d2 23:59:59' and state <> '新建订单') group by order_zh.xsbh";
    $sql = "select sum(df) as fenje , z.xsbh as skfs,z.ddh from order_zh z , order_mainqt m where z.ddh=m.ddh and m.state <>'作废订单' and z.khmc='$khmc' and sksj>='$d1 00:00:00' and sksj<='$d2 23:59:59' group by z.xsbh";

    $res = mysql_query($sql,$conn);

    for($i = 0; $i<mysql_num_rows($res); $i++){

        $data[$i]['skfs'] = mysql_result($res,$i,'skfs');
        $data[$i]['fenje'] = mysql_result($res,$i,'fenje');
    }


    echo json_encode($data);

    exit();
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
    <style type="text/css">
        .table_total td{
            text-align: center;
            border-bottom: 1px solid #d3d3d3;
            padding: 5px 0;
            width:77px;
        }
    </style>
</head>


<Div style="width:900px; margin:0px auto;">
    <Div id=Calendar scrolling="no" style="border:0px solid #EEEEEE ;position: absolute; margin-top:150px; margin-left: 5px; width: 150; height: 137; z-index: 200; filter :\'progid:DXImageTransform.Microsoft.Shadow(direction=135,color=#AAAAAA,strength=4)\' ;display: none"></Div>
</Div>
<form name="form1" method="post" action="YKcw_xstj2.php?dq=<? echo $_GET["dq"] ?>" id="form1">


    <div style="width:90%">

        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:0px;">
            <tbody>
            <tr>
                <td valign="top">
                    <div style="padding:5px 32px 22px 55px; color:#58595B">
                        <div style="padding-bottom:10px; font-weight:bold;"></div>
                        <div>开始日期：
                            <input onClick="WdatePicker();" maxlength="50" class="Wdate" style="width: 100px;"
                                   type="text" name="rq1" id="rq1" value="<? echo $d1; ?>"/>
                            &nbsp;&nbsp;&nbsp;&nbsp;结束日期：<input onClick="WdatePicker();" maxlength="50" class="Wdate"
                                                                style="width: 100px;" type="text" name="rq2" id="rq2"
                                                                value="<? echo $d2; ?>"/>&nbsp;&nbsp;&nbsp;&nbsp;按<select name="pxfs">
                                <option value="ddje">订单金额</option>
                                <option value="ddsl" <? if ($pxfs == 'ddsl') echo "selected" ?>>订单数量</option>
                            </select>递减排序&nbsp;&nbsp;&nbsp;

                            <? include '../commonfile/calc_options.php'; ?>

                            <input name="bt1" type="submit" value="查 询"/>　　<input type="submit" name="bt2" value="导 出"/>

                            <br>

                            销售数据：
                            <?

//                            $rs = mysql_query("select base_kh.mpzh,base_kh.khmc,sum(dje+ifnull(kdje,0)) tdje,ifnull(sum(z.df),0) as ysje,(ifnull(user_zhjf.ye,0)) as ye ,count(m.ddh) ddsl,base_kh.lxr,base_kh.lxdh,base_kh.lxdz,base_kh.xsbh from base_kh left join user_zhjf on base_kh.khmc = user_zhjf.depart,order_mainqt m LEFT JOIN order_zh z on  m.ddh=z.ddh where base_kh.gdzk in $dwdmStr $tj $khbhsel $lxrsel $lxdhsel $xsbhsel and m.khmc=base_kh.khmc and z.fssj>='$d1 00:00:00' and z.fssj<='$d2 23:59:59' and m.state<>'新建订单' and m.state<>'作废订单' group by m.khmc  $order", $conn);
                            $rs = mysql_query("select base_kh.mpzh,base_kh.khmc,sum(dje+ifnull(kdje,0)) tdje,ifnull(sum(z.df),0) as ysje,count(m.ddh) ddsl,base_kh.lxr,base_kh.lxdh,base_kh.lxdz,base_kh.xsbh from base_kh ,order_mainqt m LEFT JOIN order_zh z on  m.ddh=z.ddh where base_kh.gdzk in $dwdmStr $tj $khbhsel $lxrsel $lxdhsel $xsbhsel and m.khmc=base_kh.khmc and z.fssj>='$d1 00:00:00' and z.fssj<='$d2 23:59:59' and m.state<>'新建订单' and m.state<>'作废订单' group by m.khmc  $order", $conn);

                            ?>
                            <body style="overflow-x:hidden;overflow-y:auto">
                            <table id="table_total" class="table_total" cellspacing="0" cellpadding="0" rules="all" style="border-width:0px;width:100%;border-collapse:collapse;border:1px solid #D3D3D3; border-bottom:0px;">
                                <tbody>
                                <tr class="td_title" style="height:30px;">
                                    <th rowspan="2"  scope="col">排名</th>
                                    <th rowspan="2"  scope="col">客户编号</th>
                                    <th rowspan="2"  scope="col">客户名称</th>
                                    <th rowspan="2"  scope="col">联系人</th>
                                    <th rowspan="2"  scope="col">联系电话</th>
                                    <th rowspan="2"  scope="col">地址</th>
                                    <th rowspan="2"  scope="col">销售编号</th>
<!--                                    <th rowspan="2"  scope="col">账户余额</th>-->
                                    <th rowspan="2"  scope="col">订单数量</th>
                                    <th rowspan="2"  scope="col">订单金额</th>
                                    <th colspan="6"  scope="col">收款金额</th>
                                </tr>
                                <tr class="td_title" style="height:30px;">
                                    <th  scope="col">现金</th>
                                    <th  scope="col">支票</th>
                                    <th  scope="col">POS机招行</th>
                                    <th  scope="col">汇款</th>
                                    <th  scope="col">预存扣款</th>
                                    <th  scope="col">小计</th>
                                </tr>
                                <tr class="td_title searchtr" style="height:30px;">
                                    <th   scope="col"></th>
                                    <th   scope="col"><input style="height:26px;" type="text" name="xstjkhbh" placeholder="输入客户编号" value="<? echo $khbh; ?>"/></th>
                                    <th   scope="col"><input style="height:26px;" type="text" name="xstjkhmc" placeholder="输入客户名称" value="<? echo $khmc; ?>"/></th>
                                    <th   scope="col"><input style="height:26px;" type="text" name="xstjlxr" placeholder="输入联系人" value="<? echo $lxr; ?>"/></th>
                                    <th  scope="col"><input  style="height:26px;" type="text" name="xstjlxdh" placeholder="输入联系电话" value="<? echo $lxdh; ?>"/></th>
                                    <th   scope="col"></th>
                                    <th  scope="col"><input style="height:26px;" type="text" name="xstjxsbh" placeholder="输入客服编号" value="<? echo $xsbh; ?>"/></th>
<!--                                    <th   scope="col">-->
<!--                                        <input style="height:26px;width:60px;" type="text" name="xstjyemin" placeholder="金额下限" value="--><?// echo $_POST['xstjyemin']; ?><!--"/>~<input style="height:26px;width:60px;" type="text" name="xstjyemax" placeholder="金额上限" value="--><?// echo $_POST['xstjyemax']; ?><!--"/>-->
<!--                                    </th>-->
                                    <th   scope="col">
                                        <input style="height:26px;width:60px;" type="text" name="xstjddslmin" placeholder="数量下限" value="<? echo $_POST['xstjddslmin']; ?>"/>~<input style="height:26px;width:60px;" type="text" name="xstjddslmax" placeholder="数量上限" value="<? echo $_POST['xstjddslmax']; ?>"/>
                                    </th>
                                    <th   scope="col">
                                        <input style="height:26px;width:60px;" type="text" name="xstjddjemin" placeholder="金额下限" value="<? echo $_POST['xstjddjemin']; ?>"/>~<input  style="height:26px;width:60px;" type="text" name="xstjddjemax" placeholder="金额上限" value="<? echo  $_POST['xstjddjemax']; ?>"/>
                                    </th>
                                    <th colspan="6"  scope="col">
                                        <input type="text" name="xstjskjemin" placeholder="金额下限"  style="height:26px;" value="<? echo $_POST['xstjskjemin']; ?>"/>~<input  style="height:26px;" type="text" name="xstjskjemax" placeholder="金额下限" value="<? echo $_POST['xstjskjemax']; ?>"/>
                                    </th>
                                </tr>

                                <? $dje = 0;
                                $xje = 0;
                                $zp = 0;
                                $pos = 0;
                                $hk = 0;
                                $yc = 0;
                                $zj = 0;
                                $ddsl = 0;
                                for($i=0;$i<mysql_num_rows($rs);$i++){
                                $xj=0;
                                    if ($_POST['xstjyemax'] <> '' || $_POST['xstjyemin'] <> '') {
                                        $je = mysql_result($rs,$i,'ye');
                                        if ($_POST['xstjyemin'] <> '' && $_POST['xstjyemax'] <> '') {
                                            if ($je > $_POST['xstjyemax'] || $je < $_POST['xstjyemin'])
                                                continue;
                                        } else if ($_POST['xstjyemin'] <> '') {
                                            if ($je < $_POST['xstjyemin'])
                                                continue;
                                        } else if ($_POST['xstjyemax'] <> '') {
                                            if ($je > $_POST['xstjyemax'])
                                                continue;
                                        }
                                    }

                                    if ($_POST['xstjddslmax'] <> '' || $_POST['xstjddslmin'] <> '') {
                                        $je = mysql_result($rs,$i,'ddsl');
                                        if ($_POST['xstjddslmin'] <> '' && $_POST['xstjddslmax'] <> '') {
                                            if ($je > $_POST['xstjddslmax'] || $je < $_POST['xstjddslmin'])
                                                continue;
                                        } else if ($_POST['xstjddslmin'] <> '') {
                                            if ($je < $_POST['xstjddslmin'])
                                                continue;
                                        } else if ($_POST['xstjddslmax'] <> '') {
                                            if ($je > $_POST['xstjddslmax'])
                                                continue;
                                        }
                                    }

                                    if ($_POST['xstjddjemax'] <> '' || $_POST['xstjddjemin'] <> '') {
                                        $je = mysql_result($rs,$i,'tdje');
                                        if ($_POST['xstjddjemin'] <> '' && $_POST['xstjddjemax'] <> '') {
                                            if ($je > $_POST['xstjddjemax'] || $je < $_POST['xstjddjemin'])
                                                continue;
                                        } else if ($_POST['xstjddjemin'] <> '') {
                                            if ($je < $_POST['xstjddjemin'])
                                                continue;
                                        } else if ($_POST['xstjddjemax'] <> '') {
                                            if ($je > $_POST['xstjddjemax'])
                                                continue;
                                        }
                                    }

                                    if ($_POST['xstjskjemax'] <> '' || $_POST['xstjskjemin'] <> '') {
                                        $je = mysql_result($rs,$i,'ysje');
                                        if ($_POST['xstjskjemin'] <> '' && $_POST['xstjskjemax'] <> '') {
                                            if ($je > $_POST['xstjskjemax'] || $je < $_POST['xstjskjemin'])
                                                continue;
                                        } else if ($_POST['xstjskjemin'] <> '') {
                                            if ($je < $_POST['xstjskjemin'])
                                                continue;
                                        } else if ($_POST['xstjskjemax'] <> '') {
                                            if ($je > $_POST['xstjskjemax'])
                                                continue;
                                        }
                                    }
                                ?>
                            <tr class="skleixing" id='<? echo mysql_result($rs, $i, 'khmc') ?>'>
                                <td  style="width:90px;"><? echo $i+1; ?></td>
                                <td   ><? echo mysql_result($rs,$i,'mpzh');?></td>
                                    <td   style="width:157px;text-align:left"><? echo mysql_result($rs,$i,'khmc');?></td>
                                    <td><? echo mysql_result($rs,$i,"lxr");?></td>
                                    <td><? echo mysql_result($rs,$i,"lxdh");?></td>
                                    <td style="width:107px;"><? echo mysql_result($rs,$i,"lxdz");?></td>
                                    <td  style="width:57px;"><? echo mysql_result($rs,$i,"xsbh");?></td>
<!--                                    <td  style="width:57px;">--><?// echo mysql_result($rs,$i,"ye");?><!--</td>-->

                                    <td ><? echo mysql_result($rs,$i,"ddsl");?></td>
                                    <td  style="width:80px;"><? if (mysql_result($rs,$i,'tdje')<>0) echo mysql_result($rs,$i,2); else echo "&nbsp;";?></td>

                                        <td class="xj"  style="width:90px;">0.00</td>
                                        <td class="zp"  style="width:90px;">0.00</td>
                                        <td class="pos"  style="width:90px;">0.00</td>
                                        <td class="hk"  style="width:90px;">0.00</td>
                                        <td class="yc"  style="width:90px;">0.00</td>
                                        <td style="width:80px;"><? echo mysql_result($rs, $i, 'ysje')?mysql_result($rs, $i, 'ysje'):0.00; ?></td>

                                    </tr>
                                        <?

                                        $dje += mysql_result($rs, $i, 'tdje');
                                        $zj +=  mysql_result($rs, $i, 'ysje');
                                        $ddsl += mysql_result($rs, $i, "ddsl");
//                                        $zye += mysql_result($rs,$i,'ye');
                                }
                                ?>
<!--                                开始-->
<!--                                --><?//
//                                while($row = mysql_fetch_array($rs,MYSQL_ASSOC)){
//                                $xj=0;
//                                ?>
<!--                                <tr class="skleixing" id='--><?// echo mysql_result($rs, $i, 'khmc') ?><!--'>-->
<!--                                    <td   >--><?// echo $row['mpzh'];?><!--</td>-->
<!--                                    <td   style="width:157px;text-align:left">--><?// echo $row['khmc'];?><!--</td>-->
<!--                                    <td   >--><?// echo $row['lxr'];?><!--</td>-->
<!--                                    <td   >--><?// echo $row['lxdh'];?><!--</td>-->
<!--                                    <td   style="width:107px;">--><?// echo $row['lxdz'];?><!--</td>-->
<!--                                    <td   style="width:57px;">--><?// echo $row['xsbh'];?><!--</td>-->
<!--                                    <td   style="width:57px;">--><?// echo $row['ye'];?><!--</td>-->
<!---->
<!--                                    <td   >--><?// echo $row['ddsl'];?><!--</td>-->
<!--                                    <td   style="width:80px;">--><?// if ($row['tdje']<>0) echo $row['tdje']; else echo "&nbsp;";?><!--</td>-->
<!---->
<!--                                    <td class="td_content xj"  style="width:90px;">0.00-->
<!--                                    </td>-->
<!--                                    <td class="td_content zp"  style="width:90px;">0.00-->
<!--                                    </td>-->
<!--                                    <td class="td_content pos"  style="width:90px;">0.00-->
<!--                                    </td>-->
<!--                                    <td class="td_content hk"  style="width:90px;">0.00-->
<!--                                    </td>-->
<!--                                    <td class="td_content yc"  style="width:90px;">0.00-->
<!--                                    </td>-->
<!--                                    <td   style="width:80px;">--><?// echo $row['ysje'] ?$row['ysje']:0.00; ?><!--</td>-->
<!---->
<!--                                </tr>-->
<!--                                --><?//
//
//                                $dje += $row['tdje'];
//                                $zj +=  $row['ysje'];
//                                $ddsl += $row['ddsl'];
//                                $zye += $row['ye'];
//                                }
//                                ?>
<!--                                end-->
                                <tr id="zjtr">
                                    <td></td>
                                    <td colspan="6"   >合计：</td>
                                    <td   ><? echo $zye; ?></td>
                                    <td   ><? echo $ddsl; ?></td>
                                    <td   style="width:80px;"><? echo $dje; ?><br></td>
                                    <td  class="zxj" style="width:90px;"></td>
                                    <td  class="zzp" style="width:90px;"></td>
                                    <td  class="zpos" style="width:90px;"></td>
                                    <td  class="zhk" style="width:90px;"></td>
<!--                                    <td  class="zyc" style="width:90px;"></td>-->
                                    <td   style="width:90px;"><? echo $zj; ?></td>
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
    zxj = 0;zpos=0 ;zhk=0;zyc = 0; zzp=0;
    function skleixingtj(_id,_this,d1,d2){

            var sendData = "khmc=" + _id + "&d1=" + d1 +"&d2=" + d2;
            $.ajax({
                type: "GET",
                data: sendData,
                dataType: 'json',
                success: function (data) {
                    if(data){
                        // 返回的数据不一定是所有类型都有的

                        for(var i = 0;i<data.length;i++){

                            var skfs = data[i]['skfs'];
                            var _fenje = data[i]['fenje'];

                            switch (skfs){
                                case '现金':
                                    $(_this).find('td.xj').html(_fenje);
//                                    zxj += parseFloat(_fenje);
                                    break;
                                case '支票':
                                    $(_this).find('td.zp').html(_fenje);
//                                    zzp += parseFloat(_fenje);
                                    break;
                                case 'POS机招行':
                                    $(_this).find('td.pos').html(_fenje);
//                                    zpos += parseFloat(_fenje);
                                    break;
                                case '汇款':
                                    $(_this).find('td.hk').html(_fenje);
//                                    zhk += parseFloat(_fenje);
                                    break;
                                case '预存扣款':
                                    $(_this).find('td.yc').html(_fenje);
//                                    zyc += parseFloat(_fenje);
                                    break;

                            }
                        }
                    }

                },
                error: function () {
//                    alert('发生错误');
                }

            });

        }

    $(document).ready(function () {
        var _button = $('#table_total').find('.skleixing');

        var _d1 = $('#rq1').val();
        var _d2 = $('#rq2').val();

        var _l = _button.length;
        for (var i = 0; i <_l; i++) {

            var _cur = _button[i];
            var khmc = _cur.id;
            skleixingtj(khmc,_cur,_d1,_d2);

        }
        $('#zjtr').find('td.zxj').html(zxj);
        $('#zjtr').find('td.zzp').html(zzp);
        $('#zjtr').find('td.zpos').html(zpos);
        $('#zjtr').find('td.zhk').html(zhk);
        $('#zjtr').find('td.zyc').html(zyc);

    });
</script>
</body></html>
