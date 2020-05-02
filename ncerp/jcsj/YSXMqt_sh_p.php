<?
session_start();
require("../../inc/conn.php");
if ($_SESSION["OK"] <> "OK") {
    echo "<script language=JavaScript>{window.location.href='../error.php';}</script>";
    exit;
}
$dwdm = substr($_SESSION["GDWDM"], 0, 4);
if ($_GET["jg"] <> "") {
    mysql_query("update order_mainqt set state='订单完成',sdate=now() where ddh='" . $_GET["ddh"] . "'", $conn);
}
$dwdm = substr($_SESSION["GDWDM"], 0, 4);
$rs = mysql_query("select ddh,lxr,lxdh,lxdz,djje,dje+kdje je,kpyq,sjpsfs,kydh,order_mainqt.psfs,order_mainqt.memo,order_mainqt.khmc,state,hideprice from order_mainqt left join base_kh on order_mainqt.khmc=base_kh.khmc where ddh='" . $_GET["ddh"] . "'");
if (mysql_result($rs, 0, "sjpsfs") == "送货单") {
    $sbh = mysql_result($rs, 0, "kydh");
} else {
    $_ym = date("Ym", time());
    if ($dwdm == '3301') {
        $rsbh = mysql_query("select max(kydh) from order_mainqt where sjpsfs='送货单' and left(kydh,6)=$_ym and zzfy='3301'", $conn);
        if (mysql_result($rsbh, 0, 0) <> "") $sbh = mysql_result($rsbh, 0, 0) + 1; else $sbh = date("Ym") . "0001";
        mysql_query("update order_mainqt set sjpsfs='送货单',kydh='$sbh',sjpssj=now() where ddh='" . $_GET["ddh"] . "'", $conn);
    } else {
        $_ym = substr($_ym, 2, 4);
        $rsbh = mysql_query("select max(kydh) from order_mainqt where sjpsfs='送货单' and left(kydh,4)=$_ym and zzfy='$dwdm'", $conn);
        if (mysql_result($rsbh, 0, 0) <> "") $sbh = mysql_result($rsbh, 0, 0) + 1; else $sbh = substr(date("Ym"), 2, 4) . substr($dwdm, 2, 2) . "0001";
        mysql_query("update order_mainqt set sjpsfs='送货单',kydh='$sbh',sjpssj=now() where ddh='" . $_GET["ddh"] . "'", $conn);
    }
}
$khmc = mysql_result($rs, 0, "khmc");
//发货单上是否显示价格
$hideprice = mysql_result($rs, 0 , "hideprice");


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

//$djrs = mysql_query("select * from order_zh where ddh='".$_GET["ddh"]."' and zy='订单定金'",$conn);
//if($djrs && mysql_num_rows($djrs)>0)
//	$djje = mysql_result($djrs,0,'df');
//else
//	$djje = 0;
$zhrs = mysql_query("select * from order_zh where ddh='" . $_GET["ddh"] . "' and zy<>'订单定金'", $conn);
if ($zhrs && mysql_num_rows($zhrs) > 0) {
    $jsje = mysql_result($zhrs, 0, 'df');
    $jsfs = mysql_result($zhrs, 0, 'xsbh');
}
?>
<html>
<head><title>skyprint</title>
    <META http-equiv=Content-Type content="text/html; charset=utf-8">
    <meta content="MSHTML 6.00.3790.1830" name=GENERATOR>
    <style TYPE="text/css">
        <!--
        A:link {
            text-decoration: none
        }

        A:visited {
            text-decoration: none
        }

        A:hover {
            color: #EF6D21;
            text-decoration: underline
        }

        .style11 {
            font-size: 14px
        }

        .STYLE14 {
            font-size: 22px;
            margin-bottom: 5px;
        }

        -->

        .title_tab tr td {
            font-size: 11px;
        }

        .beizhu {
            font-size: 11px;
        }
    </style>
</head>
<body topMargin=0>
<? if ($_GET["lx"] <> "show") { ?>
    <P><img src='getean.php?size=40&text=<? echo mysql_result($rs, 0, "ddh"); ?>&<? echo rand(10, 1000) ?>'>
    </P>
<? } else {
    if (mysql_result($rs, 0, "state") == "待配送") { ?>
        <input type="button" style="margin-left:20px; height:45px;" value="配送完成"
               onClick="javascript:window.location.href='?jg=ok&lx=show&ddh=<? echo mysql_result($rs, 0, "ddh") ?>'"/>
    <? } else echo "配送已完成";
} ?>
<div align="center"><span class="STYLE14"><strong>印艺天空送货单</strong></span></div>
<table width="100%" border="0" class="title_tab">
    <!--<tr>
      <td colspan="4" align="center">客服：</td>
    </tr>-->
    <tr>
        <td width="75%" colspan="3">&nbsp;</td>
        <td width="25%" colspan="1" align="left"><strong class="STYLE14"
                                                         style="font-size:18px">编号：<? echo "SKY" . $sbh; ?></strong>
        </td>
    </tr>
    <tr>
        <td width="25%">订单号：<? echo mysql_result($rs, 0, "ddh"); ?></td>
        <td width="25%">联系人：<? echo mysql_result($rs, 0, "lxr"); ?></td>
        <td width="25%">结款方式：<? echo $jsfs; ?></td>
        <td width="25%">
            出货时间：<? echo mysql_result(mysql_query("select fssj from order_zh where ddh='" . mysql_result($rs, 0, "ddh") . "'", $conn), 0, "fssj"); ?></td>
    </tr>
    <tr>
        <td>客户名称：<? echo mysql_result($rs, 0, "khmc"); ?></td>
        <td>联系方式：<? echo mysql_result($rs, 0, "lxdh"); ?></td>
        <td>定金：<? echo mysql_result($rs, 0, "djje") == 0 ? "" : mysql_result($rs, 0, "djje"); ?></td>
        <td>出货方式：<? echo mysql_result($rs, 0, "psfs"); ?></td>
    </tr>
    <tr>
        <td colspan="2">送货地址：<? echo mysql_result($rs, 0, "lxdz") ?></td>
        <td>余额：<? echo $yue; ?></td>
        <td><!--欠款（含本单）：--></td>
    </tr>
    <tr>
        <td colspan="4">备注：<? echo mysql_result($rs, 0, "memo"); ?></td>
        <td></td>
    </tr>
</table>
<br>
<table cellspacing="0" cellpadding="0" width="100%" border="1" bordercolor="#111"
       style="border-collapse:collapse;font-size:12px">
    <tbody>
    <tr>
        <!--<td height=28 align="center"><B>序号</B></td>-->
        <td align="center" height="30" width="18%">印件名称</td>
        <td align="center" width="5%">类型</td>
        <td align="center" width="7%">构件</td>
        <td align="center" width="12%">机器颜色</td>
        <td align="center" width="15%">纸张名称</td>
        <td align="center" width="5%">单/双</td>
        <td align="center" width="5%">横/纵</td>
        <td align="center" width="5%">P数</td>
        <td align="center" width="5%">份数</td>
        <td align="center" width="6%">总数</td>
        <td align="center" width="8%">单价</td>
        <td align="center" width="9%">小计</td>
    </tr>
    <? $je = 0;
    //$rsmx=mysql_query("select order_mxqt.*,m1.materialname mm1,m1.specs ms1,m2.materialname mm2,m2.specs ms2,group_concat(hd.jgfs) hd,sum(hd.jg*hd.sl) hdje,hd.jg hdjg,hd.sl hdsl,hd.jldw hdjldw from order_mxqt left join order_mxqt_hd hd on order_mxqt.id=hd.mxid,material m1,material m2 where ddh='".mysql_result($rs,$i,"ddh")."' and m1.materialcode=paper1 and m2.materialcode=paper2 group by order_mxqt.id",$conn);
    //for($i=0;$i<mysql_num_rows($rsmx);$i++)
    $_mxrs = mysql_query("select * from order_mxqt where ddh='" . $_GET["ddh"] . "'", $conn);
    $i = 0;
    $_mxidStr = "(";
    while($_arr = mysql_fetch_assoc($_mxrs)){$i++;$_mxidStr.=$_arr["id"].",";if($_arr["n2"] == ""){$height=28;$rows=1;}else{$height=56;$rows=2;}?>
    <tr>
      <!--<td height="30"  align="center" rowspan="<? //if($_arr["n2"] == "") echo 1;else echo 2;?>"><? //echo $i?></td>-->
      <td height="<?echo $height?>"  align="center" rowspan="<? echo $rows;?>"><? echo $_arr["pname"]?></td>
      <td height="<?echo $height?>"  align="center" rowspan="<? echo $rows;?>"><? echo $_arr["productname"]?></td>
      <td align="center"><? echo $_arr["n1"];?></td>
      <td class="td_content" align="center" ><? echo $_arr["machine1"];?></td>
      <td class="td_content" align="center" style="word-wrap: break-word;word-break:break-all;"><?
          $_zzmc = mysql_query("select MaterialName from material where id=".$_arr["paper1"],$conn);if($_zzmc && mysql_num_rows($_zzmc)>0)echo mysql_result($_zzmc,0,"MaterialName");else{$_zzmc = mysql_query("select MaterialName from material1 where MaterialCode='".$_arr["paper1"]."'",$conn);echo mysql_result($_zzmc,0,"MaterialName"); }
          ?></td>
       <td class="td_content" align="center" ><? echo $_arr["dsm1"];?></td>
       <td class="td_content" align="center" ><? echo $_arr["hzx1"];?></td>
       <td class="td_content" align="center" ><? echo $_arr["pnum1"];?></td>
       <td class="td_content" align="center" ><? echo $_arr["sl1"];?></td>
        <td class="td_content" align="center" ><? echo $_arr["pnum1"]*$_arr["sl1"];?></td>

        <td class="td_content" align="center" ><? if($hideprice != '1'){echo $_arr["jg1"];} ?></td>

        <td class="td_content" align="center" ><? echo $_arr["pnum1"]*$_arr["sl1"]*$_arr["jg1"];?></td>
    </tr>
        <?if($_arr["n2"]<>""){?>
      <td align="center"><? echo $_arr["n2"];?></td>
      <td class="td_content" align="center" ><? echo $_arr["machine2"];?></td>
      <td class="td_content" align="center" style="word-wrap: break-word;word-break:break-all;"><?
           $_zzmc = mysql_query("select MaterialName from material where id=".$_arr["paper2"],$conn);if(mysql_num_rows($_zzmc)>0)echo mysql_result($_zzmc,0,"MaterialName");else{$_zzmc = mysql_query("select MaterialName from material1 where MaterialCode='".$_arr["paper2"]."'",$conn);echo mysql_result($_zzmc,0,"MaterialName"); }
          ?></td>
       <td class="td_content" align="center" ><? echo $_arr["dsm2"];?></td>
       <td class="td_content" align="center" ><? echo $_arr["hzx2"];?></td>
       <td class="td_content" align="center" ><? echo $_arr["pnum2"];?></td>
       <td class="td_content" align="center" ><? echo $_arr["sl2"];?></td>
        <td class="td_content" align="center" ><? echo $_arr["pnum2"]*$_arr["sl2"];?></td>

        <td class="td_content" align="center" ><? if($hideprice != '1'){ echo $_arr["jg2"];} ?></td>

        <td class="td_content" align="center" ><? if($hideprice != '1'){ echo $_arr["pnum2"]*$_arr["sl2"]*$_arr["jg2"]; } ?></td>
    </tr>
        <?}?>
    <? $je+=$_arr["pnum1"]*$_arr["sl1"]*$_arr["jg1"]+$_arr["pnum2"]*$_arr["sl2"]*$_arr["jg2"];
	 } ?>  </tbody>
</table>
<br>


<? $_mxidStr = substr($_mxidStr, 0, -1) . ")";
$_hdrs = mysql_query("select * from order_mxqt_hd where mxid in $_mxidStr", $conn);
if ($_hdrs && mysql_num_rows($_hdrs) > 0) { ?>
    <table cellspacing="0" cellpadding="0" width="100%" border="1" bordercolor="#111"
           style="border-collapse:collapse;font-size:12px">
        <tbody>
        <tr>
            <td height=28 align="center" width="27%">后加工方式</td>
            <td align="center" width="11%">相关尺寸</td>
            <td align="center" width="8%">数量</td>
            <td align="center" width="7%">单价</td>
            <td align="center" width="11%">加工费小计</td>
            <td align="center" width="36%">备注</td>
        </tr>
        <? while ($_hdarr = mysql_fetch_assoc($_hdrs)) { ?>
            <tr>
                <td height=28 align="center"><? echo $_hdarr["jgfs"] ?></td>
                <td align="center"><? echo $_hdarr["cpcc"] ?></td>
                <td align="center"><? echo $_hdarr["sl"] ?></td>
                <td align="center"><? if($hideprice != '1'){ echo $_hdarr["jg"];} ?></td>
                <td align="center"><? if($hideprice != '1'){ echo $_hdarr["sl"] * $_hdarr["jg"];} ?></td>
                <td align="center"><?  echo $_hdarr["memo"]; ?></td>
            </tr>
            <? $je += $_hdarr["sl"] * $_hdarr["jg"];
        } ?>
        </tbody>
    </table><br>
<? } ?>

<? $_mxidStr = substr($_mxidStr,0,-1);$_mxidStr .= ")";
$_fmrs = mysql_query("select * from order_mxqt_fm where mxid in $_mxidStr order by id asc",$conn);
if($_fmrs && mysql_num_rows($_fmrs)>0) {

    ?>
    <br><table cellspacing="0" cellpadding="0" bordercolor="#111" border="1" width="100%" style="border-collapse:collapse;font-size:12px">

    <thead>
        <tr class="td_title" style="height:30px;">

            <td height=28 align="center" width="27%">覆膜方式</td>
            <td align="center" width="11%">成品尺寸</td>
            <td align="center" width="8%">单位</td>
            <td align="center" width="11%">数量</td>
            <td align="center" width="7%">单价</td>
            <td align="center" width="11%">加工费小计</td>
            <td  align="center" width="36%">备注</td>
        </tr>
        </thead>
        <tbody>
        <?
        while($_arr = mysql_fetch_assoc($_fmrs)) {?>
            <tr class="td_title" style="height:30px;">

                <td class="td_content" align="center" ><? echo $_arr["fmfs"];?></td>
                <td align="center" class="td_content" ><? echo $_arr["cpcc"];?></td>
                <td class="td_content" align="center" ><? echo $_arr["jldw"];?></td>
                <td class="td_content" align="center" ><? echo $_arr["sl"];?></td>
                <td class="td_content" align="center" ><? echo $_arr["jg"];?></td>
                <td class="td_content" align="center" ><? echo $_arr["jg"]*$_arr["sl"];?></td>

                <td class="td_content" align="center" ><? echo $_arr["memo"];?></td>
            </tr>
        <?
            $je += $_arr["sl"] * $_arr["jg"];

        }?>
        </tbody>
    </table>
<? }?>
<br><table cellspacing="0" cellpadding="0" width="100%" border="1" bordercolor="#111"
       style="border-collapse:collapse;font-size:12px">
    <tbody>

    <tr>
        <td align="center" height="30" width="15%">其他费用：</td>
        <td align="center" width="37%"></td>
        <td align="center" width="7%">合计：</td>
        <td align="center" width="15%"><? if($hideprice != '1'){ echo "￥" . number_format($je, 2);} ?></td>
        <td align="center" width="6%">大写：</td>
        <td width="27%"><? if($hideprice != '1'){ echo rmb_format($je);} ?></td>
    </tr>
    </tbody>
</table>
<br>
<?
switch ($dwdm) {
    case '3301':
        $beizhu = "公司地址：上海市闸北区恒丰路610号不夜城工业园4号楼201室。电话：021-51096119，传真：021-51096119-814<br>上海中心店：上海市普陀区绥德路175弄7号楼底楼。电话：021-51098805，传真：021-51098805";
        break;
    case '3302':
        $beizhu = "公司地址：北京市丰台区丰台科技园海鹰路6号院总部国际1号楼底商。电话：010-51662172";
        break;
}
?>
<span class="beizhu"><? echo $beizhu; ?><br>说明：1、来搞请备份，如有遗失本公司只赔偿拷贝介质；2、如有质量问题，请于24小时内提出，否则视为认可接受。</span>
<br><br>
<table width="100%">
    <tr>
        <td width="25%">开单员：<? echo $_SESSION["YKUSERNAME"] ?></td>
        <td width="25%">收银员：<? echo $_SESSION["YKUSERNAME"] ?></td>
        <td width="50%">客户签名：</td>
    </tr>
</table>
　　　　　
<p>
</body>
</html>
<?
$rs = null;
unset($rs);
$rsnd = null;
unset($rsnd);
mysql_close();
?>
<? if ($_GET["lx"] <> "show") { ?>
    <SCRIPT LANGUAGE="JavaScript">
        <
        !--Begin
        if (window.print) {
            window.print();
        }
        else {
            alert('No printer driver in your PC');
        }
        //End -- >
    </script>
<? } ?>
<?

function rmb_format($money = 0, $int_unit = '元', $is_round = true, $is_extra_zero = false)
{
    // 将数字切分成两段
    $parts = explode('.', $money, 2);
    $int = isset ($parts [0]) ? strval($parts [0]) : '0';
    $dec = isset ($parts [1]) ? strval($parts [1]) : '';
    // 如果小数点后多于2位，不四舍五入就直接截，否则就处理
    $dec_len = strlen($dec);
    if (isset ($parts [1]) && $dec_len > 2) {
        $dec = $is_round ? substr(strrchr(strval(round(floatval("0." . $dec), 2)), '.'), 1) : substr($parts [1], 0, 2);
    }
    // 当number为0.001时，小数点后的金额为0元
    if (empty ($int) && empty ($dec)) {
        return '零';
    }
    // 定义
    $chs = array('0', '壹', '贰', '叁', '肆', '伍', '陆', '柒', '捌', '玖');
    $uni = array('', '拾', '佰', '仟');
    $dec_uni = array('角', '分');
    $exp = array('', '万');
    $res = '';
    // 整数部分从右向左找
    for ($i = strlen($int) - 1, $k = 0; $i >= 0; $k++) {
        $str = '';
        // 按照中文读写习惯，每4个字为一段进行转化，i一直在减
        for ($j = 0; $j < 4 && $i >= 0; $j++, $i--) {
            $u = $int{$i} > 0 ? $uni [$j] : ''; // 非0的数字后面添加单位
            $str = $chs [$int{$i}] . $u . $str;
        }
        $str = rtrim($str, '0'); // 去掉末尾的0
        $str = preg_replace("/0+/", "零", $str); // 替换多个连续的0
        if (!isset ($exp [$k])) {
            $exp [$k] = $exp [$k - 2] . '亿'; // 构建单位
        }
        $u2 = $str != '' ? $exp [$k] : '';
        $res = $str . $u2 . $res;
    }
    // 如果小数部分处理完之后是00，需要处理下
    $dec = rtrim($dec, '0');
    //var_dump ( $dec );
    // 小数部分从左向右找
    if (!empty ($dec)) {
        $res .= $int_unit;
        // 是否要在整数部分以0结尾的数字后附加0，有的系统有这要求
        if ($is_extra_zero) {
            if (substr($int, -1) === '0') {
                $res .= '零';
            }
        }
        for ($i = 0, $cnt = strlen($dec); $i < $cnt; $i++) {
            $u = $dec{$i} > 0 ? $dec_uni [$i] : ''; // 非0的数字后面添加单位
            $res .= $chs [$dec{$i}] . $u;
            if ($cnt == 1)
                $res .= '整';
        }
        $res = rtrim($res, '0'); // 去掉末尾的0
        $res = preg_replace("/0+/", "零", $res); // 替换多个连续的0
    } else {
        $res .= $int_unit . '整';
    }
    return $res;
}

/**
 *这个不靠谱~~~~
 *用上面那个~~~~
 *
 *数字金额转换成中文大写金额的函数
 *String Int  $num  要转换的小写数字或小写字符串
 *return 大写字母
 *小数位为两位
 **/
function num_to_rmb($num)
{
    $c1 = "零壹贰叁肆伍陆柒捌玖";
    $c2 = "分角元拾佰仟万拾佰仟亿";
    //精确到分后面就不要了，所以只留两个小数位
    $num = round($num, 2);
    //将数字转化为整数
    $num = $num * 100;
    if (strlen($num) > 10) {
        return "金额太大，请检查";
    }
    $i = 0;
    $c = "";
    while (1) {
        if ($i == 0) {
            //获取最后一位数字
            $n = substr($num, strlen($num) - 1, 1);
        } else {
            $n = $num % 10;
        }
        //每次将最后一位数字转化为中文
        $p1 = substr($c1, 3 * $n, 3);
        $p2 = substr($c2, 3 * $i, 3);
        if ($n != '0' || ($n == '0' && ($p2 == '亿' || $p2 == '万' || $p2 == '元'))) {
            $c = $p1 . $p2 . $c;
        } else {
            $c = $p1 . $c;
        }
        $i = $i + 1;
        //去掉数字最后一位了
        $num = $num / 10;
        $num = (int)$num;
        //结束循环
        if ($num == 0) {
            break;
        }
    }
    $j = 0;
    $slen = strlen($c);
    while ($j < $slen) {
        //utf8一个汉字相当3个字符
        $m = substr($c, $j, 6);
        //处理数字中很多0的情况,每次循环去掉一个汉字“零”
        if ($m == '零元' || $m == '零万' || $m == '零亿' || $m == '零零') {
            $left = substr($c, 0, $j);
            $right = substr($c, $j + 3);
            $c = $left . $right;
            $j = $j - 3;
            $slen = $slen - 3;
        }
        $j = $j + 3;
    }
    //这个是为了去掉类似23.0中最后一个“零”字
    if (substr($c, strlen($c) - 3, 3) == '零') {
        $c = substr($c, 0, strlen($c) - 3);
    }
    //将处理的汉字加上“整”
    if (empty($c)) {
        return "零元整";
    } else {
        return $c . "整";
    }
}

?>
