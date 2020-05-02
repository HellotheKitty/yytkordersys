<?
session_start();
$dwdm = substr($_SESSION["GDWDM"], 0, 4);
require("../inc/conn.php");
if ($_GET["lx"] == "out") {
    $_SESSION["CWUSER"] = "";
//    header("Location:YKcw.php");
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

include '../commonfile/calc_area_ry_get.php';

$ssales = '';
$fxsmc = '';

if($_GET['sales']<>''){

    $ssales = $_GET['sales'];

    $fxsmc = " and r.bh = '$ssales'";
}

//    pageing
$restotal = mysql_query("select count(*) as rowcount from base_kh k left join b_ry r on k.xsbh = r.bh where r.dwdm in $dwdmStr and k.khmc like '%" . $_GET["t2"] . "%' $fxsmc ");

$rowcount= mysql_result($restotal,0,'rowcount');

include '../commonfile/paging_data.php';

//    print
if($_GET['outlog']){
    $startrow = 0;
    $pagenum = $rowcount;
}

$res = mysql_query("select k.mpzh,k.khmc,k.lxdh,k.lxr,r.bh,r.xm  from base_kh k left join b_ry r on k.xsbh = r.bh where r.dwdm in $dwdmStr and k.khmc like '%" . $_GET["t2"] . "%' $fxsmc limit $startrow,$pagenum", $conn);

?>
<?
if ($_GET["outlog"]) {
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
    <style type="text/css">
        .khtb th{
        text-align: center;
        }
        .khtb td{
            border-bottom: 1px solid #d3d3d3;
            padding: 5px 0;
            text-align: left;
        }
        .khtb a{
            color: #004a7d;
            text-decoration: underline;
        }
        .cztd a{
            display: inline-block;
        }
    </style>
</head>
<body style="overflow-x:hidden;overflow-y:auto">
<form name="form1" method="get" action="" id="form1">


    <div style="padding:0px 10px 0px 10px;">
        <br/>
        <?
        include '../commonfile/calc_options.php';
        echo $_SESSION["CWUSER"]; ?>,您好！　　　　查找客户：
         <input name="t2" type="text" id="t2" size="15"  placeholder="输入客户名称" value="<? echo $_GET['t2']; ?>" />
        <input type="submit" name="button2" id="button2" value="查找"/> <a href='YKcw_xstj.php'>销售统计</a>
        <a style="color:#f60; margin-left:12px;" href='javascript:'
            onclick='window.open("Userzh_zfbmx.php",window,"width=800,height=485,top=100,left=100,toolbar=no,menubar=no,scrollbars=no, resizable=yes,location=no, status=no");'>支付宝清单</a>
        <a style="color:#022e81;margin-left:12px;" href='javascript:'
           onclick='window.open("Userzh_chinapay.php",window,"width=800,height=485,top=100,left=100,toolbar=no,menubar=no,scrollbars=no, resizable=yes,location=no, status=no");'>银联支付清单</a>
        <a style="color:#4fbc51;margin-left:12px;" href='javascript:'
           onclick='window.open("Userzh_wechatpay.php",window,"width=800,height=485,top=100,left=100,toolbar=no,menubar=no,scrollbars=no, resizable=yes,location=no, status=no");'>微信支付清单</a>
        <a style="margin-left:12px;" href='javascript:'
           onclick='window.open("Userzh_kpall.php",window,"width=800,height=485,top=100,left=100,toolbar=no,menubar=no,scrollbars=no, resizable=yes,location=no, status=no");'>开票申请清单</a>
        　　　<input type="submit" name="outlog" value="excel导出"/>

        <div>

            <table class="khtb" cellspacing="0" cellpadding="0" rules="all" border="0" id="gvOrder" style="border-width:0px;width:100%;border-collapse:collapse;border:1px solid #D3D3D3; border-bottom:0px;">
                <tbody>
                <tr class="td_title" style="height:30px;">
                    <th width="44" scope="col">地区</th>
                    <th width="84" scope="col">所属客服<br>
                        <?php if ($_SESSION["GLDQ"] == "%" || $_SESSION["CWUSER"] == 'hzcw'):

                            $rsSales = mysql_query("SELECT bh,xm from b_ry where dwdm in $dwdmStr and qx like '%kf%'", $conn);

                            ?>
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
                    <th width="78" scope="col">客户编号</th>
                    <th width="241" scope="col">客户名称</th>
                    <th width="75" scope="col">联系人</th>
                    <th width="75" scope="col">联系电话</th>
                    <th scope="col">操作</th>
                </tr>
                <?
                while ($row = mysql_fetch_assoc($res)) {
                    ?>
                    <tr onMouseOver="this.style.backgroundColor='#FFD'" onMouseOut="this.style.backgroundColor='white'">
                        <td><? if(substr($dwdm ,0 ,2) == '34') echo '北京'; elseif(substr($dwdm ,0,2)=='33') echo '上海'; elseif(substr($dwdm , 0,1)==4) echo '华杰印务'; ?></td>
                        <td><? echo $row['bh'], "-", $row['xm']; ?></td>
                        <td><? echo $row['mpzh']; ?></td>
                        <td><? echo $row['khmc']; ?></td>
                        <td><? echo $row['lxr']; ?></td>
                        <td><? if (!$_GET["outlog"] ) echo $row['lxdh']; ?></td>

                        <td class="cztd">
                            <a href="#" onclick='javascript:window.open("../ncerp/jcsj/Userzh_mxfb.php?khmc=<? echo base_encode($row['khmc']); ?>",window,"width=800,height=485,top=100,left=100,toolbar=no,resizable=yes,location=no,scrollbars=yes, status=no");'>账务详情</a>　　

                            <a href='#' class='nav' onClick='javascript:window.open("../ncerp/jcsj/YK_cwmodifb.php?khmc=<? echo urlencode($row['khmc']); ?>", "HT_dhdj", "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=yes,width=700,height=350,left=300,top=100")'>数据处理</a>　　

                        </td>
                    </tr>
                <? } ?>

                </tbody>
            </table>

        </div>


        <div class="c_t22">
            <?
            //pageing
            $param = 'sales=' . $ssales . '&t2=' . $_GET['t2'];

            include "../commonfile/paging_show.php";
            ?>
        </div>
    </div>


</form>
</body>
</html>
