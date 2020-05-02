<? 
session_start();
require("../../inc/conn.php");
if ($_SESSION["OK"]<>"OK") { 
echo "<script language=JavaScript>{window.location.href='../error.php';}</script>";
exit;
}

$dwdm = substr($_SESSION["GDWDM"],0,4);
$rs=mysql_query("select ddh,lxr,lxdh,lxdz,djje,dje+kdje je,kpyq,sjpsfs,kydh,order_mainqt.memo,order_mainqt.khmc,state from order_mainqt left join base_kh on order_mainqt.khmc=base_kh.khmc where ddh='".$_GET["ddh"]."'");

$khmc = mysql_result($rs,0,"khmc");

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
/*$yers = mysql_query("select ye from user_zhjf where depart='$khmc'",$conn);
if($yers && mysql_num_rows($yers)>0)
	$yue = mysql_result($yers,0,0);*/

$zhrs = mysql_query("select * from order_zh where ddh='".$_GET["ddh"]."' and zy<>'订单定金'",$conn);
if($zhrs && mysql_num_rows($zhrs)>0){
	$jsje = mysql_result($zhrs,0,'df');
	$jsfs = mysql_result($zhrs,0,'xsbh');
}
?>
<html><head><title>skyprint</title>
<META http-equiv=Content-Type content="text/html; charset=utf-8">
<meta content="MSHTML 6.00.3790.1830" name=GENERATOR>
<style TYPE="text/css">
<!--
A:link{text-decoration:none}
A:visited{text-decoration:none}
A:hover {color: #EF6D21;text-decoration:underline}
.style11 {font-size: 14px}
.STYLE14 {font-size: 22px; margin-bottom:5px;}
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

<div align="center"><span class="STYLE14"><strong>订单详情</strong></span></div>
<table width="100%" border="0" class="title_tab">
  <tr>
    <td width="25%">订单号：<? echo mysql_result($rs,0,"ddh");?></td>
    <td width="25%">联系人：<? echo mysql_result($rs,0,"lxr");?></td>
      <td width="25%">结款方式：<? echo $jsfs;?></td>
      <!--<td width="25%">出货时间：<? echo date('Y-m-d H:i');?></td>-->
  </tr>
  <tr>
      <td>客户名称：<?echo mysql_result($rs,0,"khmc");?></td>
      <td>联系方式：<?echo mysql_result($rs,0,"lxdh");?></td>
      <td>定金：<? echo mysql_result($rs,0,"djje")==0?"":mysql_result($rs,0,"djje");?></td>
      <!--<td>余额：<?echo $yue;?></td>-->
  </tr>
    <tr>
        <td colspan="4">送货地址：<?echo mysql_result($rs,0,"lxdz")?></td>
    </tr>
   <tr>
    <td colspan="4">备注：<? echo mysql_result($rs,0,"memo");?></td>
    <td></td>
  </tr>
</table>
<br>
<table cellspacing="0" cellpadding="0" width="100%" border="1"   bordercolor="#111" style="border-collapse:collapse;font-size:12px">
  <tbody>
    <tr>
      <!--<td height=28 align="center"><B>序号</B></td>-->
      <td align="center" height="30" width="18%">印件名称</td>
      <td align="center" width="5%" >类型</td>
      <td align="center" width="7%">构件</td>
      <td align="center" width="12%">机器颜色</td>
      <td align="center" width="15%">纸张名称</td>
      <td align="center" width="5%">单/双</td>
            <td  align="center" width="5%">横/纵</td>
            <td  align="center" width="5%">P数</td>
            <td  align="center" width="5%">份数</td>
            <td   align="center" width="6%">总数</td>
            <td  align="center" width="8%">单价</td>
            <td  align="center" width="9%">小计</td>
    </tr>
    <? $je=0;
       $_mxrs = mysql_query("select * from order_mxqt where ddh='".$_GET["ddh"]."'",$conn);
    $i=0;$_mxidStr="(";
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
        <td class="td_content" align="center" ><? echo $_arr["jg1"];?></td>
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
        <td class="td_content" align="center" ><? echo $_arr["jg2"];?></td>
        <td class="td_content" align="center" ><? echo $_arr["pnum2"]*$_arr["sl2"]*$_arr["jg2"];?></td>
    </tr>
        <?}?>
    <? $je+=$_arr["pnum1"]*$_arr["sl1"]*$_arr["jg1"]+$_arr["pnum2"]*$_arr["sl2"]*$_arr["jg2"];
	 }?>  </tbody></table><br>


    <?$_mxidStr=substr($_mxidStr,0,-1).")";$_hdrs = mysql_query("select * from order_mxqt_hd where mxid in $_mxidStr",$conn); if($_hdrs && mysql_num_rows($_hdrs)>0){?>
    <table cellspacing="0" cellpadding="0" width="100%" border="1"   bordercolor="#111" style="border-collapse:collapse;font-size:12px">
     <tbody>
     <tr>
           <td height=28 align="center" width="27%">后加工方式</td>
          <td align="center" width="11%">相关尺寸</td>
          <td align="center" width="8%" >数量</td>
          <td align="center" width="7%">单价</td>
          <td align="center" width="11%">加工费小计</td>
          <td align="center" width="36%">备注</td>
    </tr>
      <?  while($_hdarr = mysql_fetch_assoc($_hdrs)) {?>
          <tr>
              <td height=28 align="center"><? echo $_hdarr["jgfs"]?></td>
              <td align="center"><? echo $_hdarr["cpcc"]?></td>
              <td align="center"  ><? echo $_hdarr["sl"]?></td>
              <td align="center" ><? echo $_hdarr["jg"]?></td>
              <td align="center" ><? echo $_hdarr["sl"]*$_hdarr["jg"]?></td>
              <td align="center" ><? echo $_hdarr["memo"]?></td>
          </tr>
    <? $je+=$_hdarr["sl"]*$_hdarr["jg"];}?>
     </tbody></table><br>
    <?}?>

<?$_mxidStr=substr($_mxidStr,0,-1).")";$_fmrs = mysql_query("select * from order_mxqt_fm where mxid in $_mxidStr",$conn); if($_fmrs && mysql_num_rows($_fmrs)>0){?>
    <table cellspacing="0" cellpadding="0" width="100%" border="1"   bordercolor="#111" style="border-collapse:collapse;font-size:12px">
        <tbody>
        <tr>
            <td height=28 align="center" width="27%">覆膜方式</td>
            <td align="center" width="11%">相关尺寸</td>
            <td align="center" width="8%" >数量</td>
            <td align="center" width="7%">单价</td>
            <td align="center" width="11%">加工费小计</td>
            <td align="center" width="36%">备注</td>
        </tr>
        <?  while($_fmarr = mysql_fetch_assoc($_fmrs)) {?>
            <tr>
                <td height=28 align="center"><? echo $_fmarr["fmfs"]?></td>
                <td align="center"><? echo $_fmarr["cpcc"]?></td>
                <td align="center"  ><? echo $_fmarr["sl"]?></td>
                <td align="center" ><? echo $_fmarr["jg"]?></td>
                <td align="center" ><? echo $_fmarr["sl"]*$_fmarr["jg"]?></td>
                <td align="center" ><? echo $_fmarr["memo"]?></td>
            </tr>
            <? $je+=$_fmarr["sl"]*$_fmarr["jg"];}?>
        </tbody></table><br>
<?}?>

<table cellspacing="0" cellpadding="0" width="100%" border="1"   bordercolor="#111" style="border-collapse:collapse;font-size:12px">
    <tbody>

    <tr>
      <td align="center" height="30" width="15%">其他费用： </td><td align="center" width="37%"></td><td align="center" width="7%">合计：</td><td align="center" width="15%"><? echo "￥".number_format($je,2);?></td><td align="center" width="6%">大写：</td><td width="27%"><?echo rmb_format($je)?></td>
    </tr>
  </tbody>
</table><br><br>
</body>
</html>
<?
$rs=null;
unset($rs);
$rsnd=null;
unset($rsnd);
mysql_close();
?>

<?

function rmb_format($money = 0, $int_unit = '元', $is_round = true, $is_extra_zero = false) {
    // 将数字切分成两段
    $parts = explode ( '.', $money, 2 );
    $int = isset ( $parts [0] ) ? strval ( $parts [0] ) : '0';
    $dec = isset ( $parts [1] ) ? strval ( $parts [1] ) : '';
    // 如果小数点后多于2位，不四舍五入就直接截，否则就处理
    $dec_len = strlen ( $dec );
    if (isset ( $parts [1] ) && $dec_len > 2) {
    $dec = $is_round ? substr ( strrchr ( strval ( round ( floatval ( "0." . $dec ), 2 ) ), '.' ), 1 ) : substr ( $parts [1], 0, 2 );
    }
    // 当number为0.001时，小数点后的金额为0元
    if (empty ( $int ) && empty ( $dec )) {
    return '零';
    }
    // 定义
    $chs = array ('0', '壹', '贰', '叁', '肆', '伍', '陆', '柒', '捌', '玖' );
    $uni = array ('', '拾', '佰', '仟' );
    $dec_uni = array ('角', '分' );
    $exp = array ('', '万' );
    $res = '';
    // 整数部分从右向左找
    for($i = strlen ( $int ) - 1, $k = 0; $i >= 0; $k ++) {
    $str = '';
    // 按照中文读写习惯，每4个字为一段进行转化，i一直在减
    for($j = 0; $j < 4 && $i >= 0; $j ++, $i --) {
    $u = $int {$i} > 0 ? $uni [$j] : ''; // 非0的数字后面添加单位
    $str = $chs [$int {$i}] . $u . $str;
    }
    $str = rtrim ( $str, '0' ); // 去掉末尾的0
    $str = preg_replace ( "/0+/", "零", $str ); // 替换多个连续的0
    if (! isset ( $exp [$k] )) {
    $exp [$k] = $exp [$k - 2] . '亿'; // 构建单位
    }
    $u2 = $str != '' ? $exp [$k] : '';
    $res = $str . $u2 . $res;
    }
    // 如果小数部分处理完之后是00，需要处理下
    $dec = rtrim ( $dec, '0' );
    //var_dump ( $dec );
    // 小数部分从左向右找
    if (! empty ( $dec )) {
    $res .= $int_unit;
    // 是否要在整数部分以0结尾的数字后附加0，有的系统有这要求
    if ($is_extra_zero) {
    if (substr ( $int, - 1 ) === '0') {
    $res .= '零';
    }
    }
    for($i = 0, $cnt = strlen ( $dec ); $i < $cnt; $i ++) {
    $u = $dec {$i} > 0 ? $dec_uni [$i] : ''; // 非0的数字后面添加单位
    $res .= $chs [$dec {$i}] . $u;
    if ($cnt == 1)
    $res .= '整';
    }
    $res = rtrim ( $res, '0' ); // 去掉末尾的0
    $res = preg_replace ( "/0+/", "零", $res ); // 替换多个连续的0
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
/*
function num_to_rmb($num){
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
            $n = substr($num, strlen($num)-1, 1);
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
            $j = $j-3;
            $slen = $slen-3;
        }
        $j = $j + 3;
    }
    //这个是为了去掉类似23.0中最后一个“零”字
    if (substr($c, strlen($c)-3, 3) == '零') {
        $c = substr($c, 0, strlen($c)-3);
    }
    //将处理的汉字加上“整”
    if (empty($c)) {
        return "零元整";
    }else{
        return $c . "整";
    }
}
*/
?>
