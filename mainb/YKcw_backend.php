<?
session_start();
$dwdm = substr($_SESSION["GDWDM"], 0, 4);
require("../inc/conn.php");
if ($_GET["lx"] == "out") {
    $_SESSION["CWUSER"] = "";
    header("Location:YKcw.php");
    exit;
}
$dwdm = substr($_SESSION["GDWDM"], 0, 4);
if ($_GET["uu"] <> "") {
    if ($_GET["cks"] == md5("hzyk" . $_GET["uu"] . "winner")) {  //验证通过
        $rs = mysql_query("select * from b_ry where bh='" . $_GET["uu"] . "'", $conn);
        $_SESSION["CWUSER"] = $_GET["uu"];
        $_SESSION["OK"] = "OK";
        $_SESSION["CWMB"] = mysql_result($rs, 0, "mobile");
        $_SESSION["CWJB"] = mysql_result($rs, 0, "jb");
        $_SESSION["GLDQ"] = "%";
        print "<script language=JavaScript>{ window.location.href='YKcw.php';}</script>";
        mysql_free_result($rs);
        exit;
    }
}

?>
<? if ($_GET["sl"] == "all") $sl = 1000; else $sl = 20;
if ($_SESSION["GDWDM"] == '330100') {
    $dwdmStr = "('330100','330300')";
    //$dwdmStr = "('330100')";
} else
    $dwdmStr = "('" . $_SESSION["GDWDM"] . "')";
if ($_SESSION["GLDQ"] == "%" || $_SESSION["CWUSER"] == 'hzcw') {
    $rsSales = mysql_query("SELECT bh,xm from b_ry where dwdm='" . $_SESSION["GDWDM"] . "' and qx like '%kf%'", $conn);
    $ssales = $_GET['sales'];
    if ($_GET['sales'] == '' or $_GET['sales'] == '%' or $_POST["t1"] <> "" or $_POST["t2"] <> "") {
        $ssales = '';
        //要求上海工厂店3301可以看到 工厂店3301和火车站店3303的所有客户。
        //$rs=mysql_query("select zh,user_zhjf.xm,user_zhjf.mobile,ye,0,depart,0,xs.bh,xs.xm,'' ssdq,ifnull(sum(dje+ifnull(kdje,0)),0) from user_zhjf left join order_mainqt on khmc=depart and state='待付款',b_ry xs where xs.dwdm='".$_SESSION["GDWDM"]."' and user_zhjf.xsbh=xs.bh and zh like '%".$_POST["t1"]."%' and depart like '%".$_POST["t2"]."%' group by zh order by ssdq,xs.bh,ye limit $sl",$conn);
        $rs = mysql_query("select zh,user_zhjf.xm,user_zhjf.mobile,ye,0,depart,0,xs.bh,xs.xm,'' ssdq,ifnull(sum(dje+ifnull(kdje,0)),0) from user_zhjf left join order_mainqt on khmc=depart and state='待付款',b_ry xs where xs.dwdm in $dwdmStr and user_zhjf.xsbh=xs.bh and zh like '%" . $_POST["t1"] . "%' and depart like '%" . $_POST["t2"] . "%' group by zh order by ssdq,xs.bh,ye limit $sl", $conn);
    } else {
        //$rs=mysql_query("select zh,user_zhjf.xm,user_zhjf.mobile,ye,0,depart,0,xs.bh,xs.xm,'' ssdq,ifnull(sum(dje+ifnull(kdje,0)),0) from user_zhjf left join order_mainqt on khmc=depart and state='待付款',b_ry xs where xs.dwdm='".$_SESSION["GDWDM"]."' and  user_zhjf.xsbh=xs.bh and user_zhjf.xsbh='{$ssales}' and zh like '%".$_POST["t1"]."%' and depart like '%".$_POST["t2"]."%' group by zh order by ssdq,xs.bh,ye",$conn);
        $rs = mysql_query("select zh,user_zhjf.xm,user_zhjf.mobile,ye,0,depart,0,xs.bh,xs.xm,'' ssdq,ifnull(sum(dje+ifnull(kdje,0)),0) from user_zhjf left join order_mainqt on khmc=depart and state='待付款',b_ry xs where xs.dwdm in $dwdmStr and  user_zhjf.xsbh=xs.bh and user_zhjf.xsbh='{$ssales}' and zh like '%" . $_POST["t1"] . "%' and depart like '%" . $_POST["t2"] . "%' group by zh order by ssdq,xs.bh,ye", $conn);
    }

} else {
    //$rs=mysql_query("select zh,user_zhjf.xm,user_zhjf.mobile,ye,0,depart,0,xs.bh,xs.xm,'' ssdq,ifnull(sum(dje+ifnull(kdje,0)),0) from user_zhjf left join order_mainqt on khmc=depart and state='待付款',b_ry xs where xs.dwdm='".$_SESSION["GDWDM"]."' and user_zhjf.xsbh=xs.bh and zh like '%".$_POST["t1"]."%' and depart like '%".$_POST["t2"]."%' and instr('".$_SESSION["GLDQ"]."',xs.ssdq)>0 group by zh order by ssdq,xs.bh,ye limit $sl",$conn);
    $rs = mysql_query("select zh,user_zhjf.xm,user_zhjf.mobile,ye,0,depart,0,xs.bh,xs.xm,'' ssdq,ifnull(sum(dje+ifnull(kdje,0)),0) from user_zhjf left join order_mainqt on khmc=depart and state='待付款',b_ry xs where xs.dwdm in $dwdmStr and user_zhjf.xsbh=xs.bh and zh like '%" . $_POST["t1"] . "%' and depart like '%" . $_POST["t2"] . "%' and instr('" . $_SESSION["GLDQ"] . "',xs.ssdq)>0 group by zh order by ssdq,xs.bh,ye limit $sl", $conn);
}

?>
<?
if ($_POST["outlog"]) {
    header("Content-type:application/vnd.ms-excel;charset=UTF-8");
    header("Content-Disposition:filename=" . iconv("utf-8", "gb2312", "财务单导出.xls"));
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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="renderer" content="webkit">

    <title></title>
    <link href="../css/CITICcss.css" rel="stylesheet" type="text/css">
</head>
<body style="overflow-x:hidden;overflow-y:auto">
<form name="form1" method="post" action="" id="form1">


    <div style="padding:0px 10px 0px 10px;">
        <br/>
        <? echo $_SESSION["CWUSER"]; ?>,您好！　　　　查找客户：
        客户编号
        <input name="t1" type="text" id="t1" size="10"/> 客户名称<input name="t2" type="text" id="t2" size="15"/>
        <input type="submit" name="button2" id="button2" value="查找"/> <a href='YKcw_xstj.php'>销售统计</a> <a
            style="color:#f60; margin-left:12px;" href='javascript:'
            onclick='window.open("Userzh_zfbmx.php",window,"width=800,height=485,top=100,left=100,toolbar=no,menubar=no,scrollbars=no, resizable=yes,location=no, status=no");'>支付宝清单</a>
        <a style="color:#022e81;margin-left:12px;" href='javascript:'
           onclick='window.open("Userzh_chinapay.php",window,"width=800,height=485,top=100,left=100,toolbar=no,menubar=no,scrollbars=no, resizable=yes,location=no, status=no");'>银联支付清单</a>
        <a style="color:#4fbc51;margin-left:12px;" href='javascript:'
           onclick='window.open("Userzh_wechatpay.php",window,"width=800,height=485,top=100,left=100,toolbar=no,menubar=no,scrollbars=no, resizable=yes,location=no, status=no");'>微信支付清单</a>
        <a style="margin-left:12px;" href='javascript:'
           onclick='window.open("Userzh_kpall.php",window,"width=800,height=485,top=100,left=100,toolbar=no,menubar=no,scrollbars=no, resizable=yes,location=no, status=no");'>开票申请清单</a>
        　　　<input type="submit" name="outlog" value="excel导出"/>

        <div>

            <table cellspacing="0" cellpadding="0" rules="all" border="0" id="gvOrder"
                   style="border-width:0px;width:100%;border-collapse:collapse;border:1px solid #D3D3D3; border-bottom:0px;">
                <tbody>
                <tr class="td_title" style="height:30px;">
                    <th width="44" align="center" scope="col">地区</th>
                    <th width="84" align="center" scope="col">所属客服<br>
                        <?php if ($_SESSION["GLDQ"] == "%" || $_SESSION["CWUSER"] == 'hzcw'): ?>
                            <select name="" id="" onchange="window.location.href='YKcw.php?sales=' + this.value">
                                <option value="%">全部</option>
                                <?php while ($rowSales = mysql_fetch_row($rsSales)): ?>
                                    <option <? if ($rowSales[0] == $ssales) echo 'selected' ?>
                                        value="<? echo $rowSales[0] ?>"><? echo $rowSales[1] ?></option>
                                <?php endwhile ?>
                            </select>
                        <?php else: ?>
                            销售
                        <?php endif ?>
                    </th>
                    <th width="78" align="center" scope="col">客户编号</th>
                    <th width="241" align="center" scope="col">客户名称</th>
                    <th width="75" align="center" scope="col">联系人</th>
                    <th width="75" align="center" scope="col">联系电话</th>
                    <!--			<th width="55" align="center" scope="col">余额</th>-->
                    <th width="55" align="center" scope="col">待付款</th>
                    <th width="35" align="center" scope="col">赠点</th>
                    <th width="35" align="center" scope="col">积分</th>
                    <th align="center" scope="col">操作</th>
                </tr>
                <? $total_ye = 0;
                while ($row = mysql_fetch_row($rs)) {
                    ?>
                    <tr onMouseOver="this.style.backgroundColor='#FFD'" onMouseOut="this.style.backgroundColor='white'">
                        <td class="td_content" style="text-align:left"><? echo $row[9]; ?></td>
                        <td class="td_content" style="text-align:left"><? echo $row[7], "-", $row[8]; ?></td>
                        <td class="td_content" style="text-align:left"><? echo $row[0]; ?></td>
                        <td class="td_content" style="text-align:left"><? echo $row[5]; ?></td>
                        <td class="td_content" style="text-align:left"><? echo $row[1]; ?></td>
                        <td class="td_content" style="text-align:left"><? echo $row[2]; ?></td>
                        <!--			<td  class="td_content" style="text-align:right">-->
                        <? // if ($row[3]<0) echo "<font color=red>".$row[3]."</font>"; elseif ($row[3]>0) echo $row[3]; $total_ye+=$row[3];?><!--</td>-->
                        <td class="td_content" style="text-align:right"><? echo $row[10]; ?></td>
                        <td class="td_content" style="text-align:right"><? echo $row[6]; ?></td>
                        <td class="td_content" style="text-align:right"><? echo $row[4]; ?></td>
                        <td class="td_content" style="text-align:left"><a href="#"
                                                                          onclick='javascript:window.open("../ncerp/jcsj/Userzh_mxfb.php?khmc=<? echo base_encode($row[5]); ?>",window,"width=800,height=485,top=100,left=100,toolbar=no,menubar=no,resizable=yes,location=no, status=no");'>账务详情</a>　　<a
                                href='#' class='nav'
                                onClick='javascript:window.open("../ncerp/jcsj/YK_cwmodifb.php?khmc=<? echo urlencode($row[5]); ?>", "HT_dhdj", "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=yes,width=700,height=350,left=300,top=100")'>数据处理</a>　　

                        </td>
                    </tr>
                <? } ?>
                <tr>
                    <td class="td_content" style="text-align:left"></td>
                    <td class="td_content" style="text-align:left"></td>
                    <td class="td_content" style="text-align:left"></td>
                    <td class="td_content" style="text-align:left"></td>
                    <td class="td_content" style="text-align:left"></td>
                    <td class="td_content" style="text-align:left"></td>
                    <!--	    <td class="td_content" style="text-align:left">--><? //echo $total_ye?><!--</td>-->
                    <td class="td_content" style="text-align:left"></td>
                    <td class="td_content" style="text-align:left"></td>
                    <td class="td_content" style="text-align:left"></td>
                    <td class="td_content" style="text-align:left"></td>
                </tr>
                </tbody>
            </table>

        </div>


        <div class="c_t22">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                <tr>
                    <td style="padding-top:15px; padding-bottom:15px;">
                        <span id="lblPriceDescription">最多显示<? echo $sl ?>条，建议通过用户名和单位查找用户。<a
                                href="<? echo $_SERVER["PHP_SELF"] . "?sl=all"; ?>">
                                <点击显示更多>
                            </a></span></td>
                </tr>
                </tbody>
            </table>

        </div>
    </div>


    </div>

    </div>
</form>
</body>
</html>
