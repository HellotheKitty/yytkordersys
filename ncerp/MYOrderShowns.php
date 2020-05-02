
<?
session_start();
require("../inc/conn.php");
if ($_SESSION["OK"] <> "OK") {
    echo "<script language=JavaScript>{window.location.href='error.php';}</script>";
    exit;
} ?>

<?
$dwdm = substr($_SESSION["GDWDM"], 0, 4);
include '../commonfile/calc_area_get.php';

//每次进入页面之前将所有已收款再打印的订单状态从待结算改成待配送

//	$sqldjs = "select m.ddh from order_zh z, order_mainqt m where  m.ddh=z.ddh and m.state='待结算' and (m.needsign is null or m.needsign<>'1') and z.zy='订单结算'";
$sel_ysk_sql = "select m.ddh from order_mainqt m where m.state='待结算' and (m.needsign is null or m.needsign<>'1') and ddh in (select ddh from order_zh z where z.zy='订单结算') ";
$sel_ysk_order = mysql_query($sel_ysk_sql , $conn);
if(mysql_num_rows($sel_ysk_order) > 10){
//    $yskddhs = "";
//    while($item_yskdd = mysql_fetch_array($sel_ysk_order)){
//        $yskddhs .= $item_yskdd['ddh'] . ',';
//    }
    mysql_query("update order_mainqt m set state = '待配送' where m.state='待结算' and (m.needsign is null or m.needsign<>'1') and ddh in (select ddh from order_zh z where z.zy='订单结算') ");

//echo "update order_mainqt set state = '待配送' where ddh in ( $yskddhs ) ";
//    mysql_query("update order_mainqt set state = '待配送' where ddh in ($sel_ysk_order)" ,$conn );
}

//每次进入页面之前将所有已打印的订单改成已打印状态
$sel_ydy_sql = "SELECT DISTINCT m.ddh FROM	order_mxqt x LEFT JOIN order_mainqt m ON x.ddh = m.ddh WHERE m.state = '进入生产' AND ((n1 <> '' AND operator1 IS NOT NULL	AND operator1 <> '') AND ((n2 <> ''	AND operator2 IS NOT NULL AND operator2 <> '') OR (n2 is NULL OR n2 = '')))";
$res_ydy = mysql_query($sel_ydy_sql,$conn);
if(mysql_num_rows($res_ydy)>0){
    mysql_query("update order_mainqt m set m.state='已打印' WHERE m.state='进入生产' AND m.ddh in (SELECT t.ddh from (SELECT DISTINCT m.ddh FROM order_mxqt x LEFT JOIN order_mainqt m ON x.ddh = m.ddh WHERE m.state = '进入生产' AND ((n1 <> '' AND operator1 IS NOT NULL AND operator1 <> '') AND ((n2 <> '' AND operator2 IS NOT NULL AND operator2 <> '') OR (n2 is NULL OR n2 = '')))) as t)");
}

if ($_GET["bt2"]) {
    header("Content-type:application/vnd.ms-excel;charset=UTF-8");
    header("Content-Disposition:filename=" . iconv("utf-8", "gb2312", "订单列表[" . $d1 . "-" . $d2 . "].xls"));
    header("Expires:0");
    header('Pragma:   public');
    header("Cache-control:must-revalidate,post-check=0,pre-check=0");
}
if ($_GET["buttonprint"]) {

}
////
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

$dd1 = $d1 . " 00:00:00";
$dd2 = $d2 . " 23:59:59";
$filetj = " and order_mainqt.ddate>='$dd1' and order_mainqt.ddate<='$dd2' ";

if ($_SESSION["FBSD"] == 1 ||$_SESSION["FBCW"] == 1) {//前台 财务

    if ($_GET["gp"] <> "") $gp = $_GET["gp"]; else $gp = '待生产';

    $filename = $_GET["filename"];

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

    $dd1 = $d1 . " 00:00:00";
    $dd2 = $d2 . " 23:59:59";
    $filetj = " and order_mainqt.ddate>='$dd1' and order_mainqt.ddate<='$dd2' ";

    if ($filename <> "") $filetj .= " and (order_mainqt.memo like '%$filename%' or order_mainqt.scqkbz like '%$filename%') ";

//    更多状态
    $gpmore ='';
    if($_GET['producedetail']<>''){

        $gpmore = $_GET['producedetail'];

    }

    if($_GET['trouble_order'] <>''){

        if($_GET['trouble_order'] == '1'){
            $troublesql = " and (to_days(now()) - to_days(order_mainqt.sdate)) > 4 and order_mainqt.state <>'订单完成' ";

        }elseif($_GET['trouble_order'] =='2'){

            $troublesql = " and order_mainqt.state ='作废订单' ";

        }elseif($_GET['trouble_order'] == '3'){
            $troublesql = " and order_mainqt.dje = 0 ";

        }elseif($_GET['trouble_order'] == '4'){

            $troublesql = " and (order_zh.ddh is null and (order_mainqt.state = '订单完成' or order_mainqt.state = '待配送' or order_mainqt.state = '已打印'))";
        }

    }else{
        $troublesql = " ";

    }

    if($_GET['psfs']<>''){

        $psfs = $_GET['psfs'];
        $psfssql = " and locate('$psfs',order_mainqt.psfs)>0 ";
    }else{
        $psfssql = '';
    }
//    page

    if ($_GET["fdd"] <> "") {

        $restotal = mysql_query("select count(DISTINCT order_mainqt.ddh) as rowcount from b_ry xs,order_mainqt left join order_mxqt on order_mainqt.ddh=order_mxqt.ddh left join order_zh on order_mainqt.ddh=order_zh.ddh and zy<>'订单订金' where order_mainqt.xsbh=xs.bh and (order_mainqt.khmc like '%" . $_GET["fdd"] . "%' or order_mainqt.ddh like '%" . $_GET["fdd"] . "%')  and (order_mainqt.state like '%$gp%') and zzfy in $dwdmStr $filetj $troublesql $psfssql",$conn);

    } else {

        $restotal = mysql_query("select count(DISTINCT order_mainqt.ddh) as rowcount from b_ry xs,order_mainqt left join order_mxqt on order_mainqt.ddh=order_mxqt.ddh left join order_zh on order_mainqt.ddh=order_zh.ddh and zy<>'订单订金' where order_mainqt.xsbh=xs.bh and (order_mainqt.state like '$gp') and zzfy in $dwdmStr $filetj $troublesql $psfssql", $conn);
    }
	$rowcount = mysql_result($restotal,0,'rowcount');

//   pagedata

    $p = $_GET['page'] ? $_GET['page'] :1;
    $curpage = $p;
    $pagenum = 20;

    $totalpage = ceil($rowcount/$pagenum); //总页数
    $showpage = 5; //显示页数
    $offsetpage = ($showpage-1)/2;
    $startpage = $curpage>$offsetpage ? $curpage-$offsetpage : 1; //起始页码
    $endpage = $totalpage>$curpage+$offsetpage ? $curpage+$offsetpage : $totalpage;//结尾页码

    $startrow = ($p-1)*$pagenum;
//    pagedata end

//printout
    if($_GET["bt2"] || $_GET['fdd']<>'' || $_GET['filename']<>''){
        $startrow = 0;
        $pagenum = $rowcount;
    }

//    endpage

    if ($_GET["fdd"] <> "") {

        $rs = mysql_query("select order_mainqt.*,count(order_mxqt.id) mxsl,group_concat(order_mxqt.productname) cpms,xs.xm,sksj from b_ry xs,order_mainqt left join order_mxqt on order_mainqt.ddh=order_mxqt.ddh left join order_zh on order_mainqt.ddh=order_zh.ddh and zy<>'订单订金' where order_mainqt.xsbh=xs.bh and (order_mainqt.khmc like '%" . $_GET["fdd"] . "%' or order_mainqt.ddh like '%" . $_GET["fdd"] . "%')  and (order_mainqt.state like '$gp') and zzfy in $dwdmStr $filetj $troublesql $psfssql group by order_mainqt.ddh order by ddate desc limit $startrow ,$pagenum", $conn);
		//echo "select order_mainqt.*,count(order_mxqt.id) mxsl,group_concat(order_mxqt.productname) cpms,xs.xm,sksj from b_ry xs,order_mainqt left join order_mxqt on order_mainqt.ddh=order_mxqt.ddh left join order_zh on order_mainqt.ddh=order_zh.ddh and zy<>'订单订金' where order_mainqt.xsbh=xs.bh and (order_mainqt.khmc like '%" . $_GET["fdd"] . "%' or order_mainqt.ddh like '%" . $_GET["fdd"] . "%')  and (order_mainqt.state like '$gp') and zzfy in $dwdmStr $filetj $troublesql $psfssql group by order_mainqt.ddh order by ddate desc limit $startrow ,$pagenum";

    } else {

        $rs = mysql_query("select order_mainqt.*,count(order_mxqt.id) mxsl,group_concat(order_mxqt.productname) cpms,xs.xm,sksj from b_ry xs,order_mainqt left join order_mxqt on order_mainqt.ddh=order_mxqt.ddh left join order_zh on order_mainqt.ddh=order_zh.ddh and zy<>'订单订金' where order_mainqt.xsbh=xs.bh and (order_mainqt.state like '$gp') and zzfy in $dwdmStr $filetj $troublesql $psfssql group by order_mainqt.ddh order by ddate desc limit $startrow ,$pagenum", $conn);

    }
} elseif ($_SESSION["FBPD"] == 1) //生产
{
    $restotal = mysql_query("select count(DISTINCT order_mainqt.ddh) as rowcount from b_ry xs,order_mainqt left join order_mxqt on order_mainqt.ddh=order_mxqt.ddh left join order_zh on order_mainqt.ddh=order_zh.ddh and zy<>'订单订金' where order_mainqt.xsbh=xs.bh and (order_mainqt.state='进入生产' or order_mainqt.state='生产完成') and zzfy in $dwdmStr",$conn);

    $rowcount = mysql_result($restotal,0,'rowcount');

    include '../commonfile/paging_data.php';

    $rs = mysql_query("select order_mainqt.*,count(order_mxqt.id) mxsl,group_concat(order_mxqt.productname) cpms,xs.xm,sksj from b_ry xs,order_mainqt left join order_mxqt on order_mainqt.ddh=order_mxqt.ddh left join order_zh on order_mainqt.ddh=order_zh.ddh and zy<>'订单订金' where order_mainqt.xsbh=xs.bh and (order_mainqt.state='进入生产' or order_mainqt.state='生产完成') and zzfy in $dwdmStr group by order_mainqt.ddh order by ddate desc limit $startrow ,$pagenum", $conn);

}

elseif ($_SESSION["FBHD"] == 1) //生产后加工
{
    $restotal = mysql_query("select count(DISTINCT order_mainqt.ddh) as rowcount from b_ry xs,order_mainqt left join order_mxqt on order_mainqt.ddh=order_mxqt.ddh left join order_zh on order_mainqt.ddh=order_zh.ddh and zy<>'订单订金' where order_mainqt.xsbh=xs.bh and (order_mainqt.state='进入生产' or order_mainqt.state='已打印' or order_mainqt.state='生产完成') and zzfy in $dwdmStr ", $conn);

    $rowcount = mysql_result($restotal,0,'rowcount');

    include '../commonfile/paging_data.php';

    $rs = mysql_query("select order_mainqt.*,count(order_mxqt.id) mxsl,group_concat(order_mxqt.productname) cpms,xs.xm,sksj from b_ry xs,order_mainqt left join order_mxqt on order_mainqt.ddh=order_mxqt.ddh left join order_zh on order_mainqt.ddh=order_zh.ddh and zy<>'订单订金' where order_mainqt.xsbh=xs.bh and (order_mainqt.state='进入生产' or order_mainqt.state='已打印' or order_mainqt.state='生产完成') and zzfy in $dwdmStr  group by order_mainqt.ddh order by field(order_mainqt.state, '已打印','进入生产'), ddate desc limit $startrow ,$pagenum", $conn);

}

elseif ($_SESSION["FBFH"] == 1) { //配送

    if ($_GET["_khmc"] <> "")

        $tj = " and order_mainqt.khmc like '%" . $_GET["_khmc"] . "%' ";
    else
        $tj = "";

    if($_GET['psfs']<>''){

        $psfs = $_GET['psfs'];
        $psfssql = " and locate('$psfs',order_mainqt.psfs)>0 ";

    }else{
        $psfssql = '';
    }

    $restotal = mysql_query("select count(DISTINCT order_mainqt.ddh) as rowcount from b_ry xs,order_mainqt left join order_mxqt on order_mainqt.ddh=order_mxqt.ddh left join order_zh on order_mainqt.ddh=order_zh.ddh and zy<>'订单订金' where order_mainqt.xsbh=xs.bh $tj and order_mainqt.state='待配送' and zzfy  in $dwdmStr $psfssql ", $conn);

    $rowcount = mysql_result($restotal,0,'rowcount');

    include '../commonfile/paging_data.php';

    $rs = mysql_query("select order_mainqt.*,count(order_mxqt.id) mxsl,group_concat(order_mxqt.productname) cpms,xs.xm,sksj from b_ry xs,order_mainqt left join order_mxqt on order_mainqt.ddh=order_mxqt.ddh left join order_zh on order_mainqt.ddh=order_zh.ddh and zy<>'订单订金' where order_mainqt.xsbh=xs.bh $tj and order_mainqt.state='待配送' and zzfy  in $dwdmStr $psfssql group by order_mainqt.ddh order by ddate desc limit $startrow ,$pagenum", $conn);

}  else {

    $restotal = mysql_query("select count(DISTINCT order_mainqt.ddh) as rowcount from b_ry xs,order_mainqt left join order_mxqt on order_mainqt.ddh=order_mxqt.ddh left join order_zh on order_mainqt.ddh=order_zh.ddh and zy<>'订单订金' where order_mainqt.xsbh=xs.bh and xs.bh='" . $_SESSION["YKOAUSER"] . "' $filetj", $conn);

    $rowcount = mysql_result($restotal,0,'rowcount');

    include '../commonfile/paging_data.php';

    $rs = mysql_query("select order_mainqt.*,count(order_mxqt.id) mxsl,group_concat(order_mxqt.productname) cpms,xs.xm,sksj from b_ry xs,order_mainqt left join order_mxqt on order_mainqt.ddh=order_mxqt.ddh left join order_zh on order_mainqt.ddh=order_zh.ddh and zy<>'订单订金' where order_mainqt.xsbh=xs.bh and xs.bh='" . $_SESSION["YKOAUSER"] . "' $filetj group by order_mainqt.ddh order by ddate desc limit $startrow ,$pagenum", $conn);
	
}


?>
<!DOCTYPE html >
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <script src="../js/WdatePicker.js" type="text/javascript" language="javascript"></script>
    <script src="../js/jquery-1.8.3.min.js" type="text/javascript" language="javascript"></script>
    <title>名片工坊-业务管理</title>
    <!--/*<link href="mycss.css" rel="stylesheet" type="text/css">*/-->

</head>
<script language="JavaScript">
    <!--
    function suredo(src, q) {
        var ret;
        var flag = false;
        ret = confirm(q);
        if (ret != false) {
            // 判断文件是否上传到scfiles目录。
            // 参数：订单号
            // suredo('jcsj/YSXMqt_del.php?did=20160112345','确定生产后不能修改数据!')

            if (src.indexOf('did') == 20) {
                ddh = src.substring(24);
                $.ajax({
                    type: "GET",
                    url: "http://192.168.1.71:88/isfileexists.php?ddh=" + ddh,
                    dataType: "jsonp",
                    jsonp: "jsoncallback",
                    async: false,
                    success: function (data) {
                        if (data.error == 1) {
                            flag = true;
                            $.ajax({
                                type: "GET",
                                url: "http://192.168.1.71:88/copyfiles.php?ddh=" + ddh,
                                dataType: "jsonp",
                                jsonp: "jsoncallback",
                                async: true,	// 异步
                                success: function () {
                                },
                                error: function () {
                                }
                            });
                            window.location = src;
                        } else {
                            alert(data.error);
                        }
                    },
                    error: function () {
                        alert("error, plz retry.");
                    }
                });

                // flag为true，再异步ajax调用copy
                if (flag) {
                    // 这个部分不会被执行
                    /*
                     $.ajax({
                     type: "GET",
                     url : "http://192.168.1.130/copyfiles.php?ddh="+ddh,
                     dataType: "jsonp",
                     jsonp: "jsoncallback",
                     async: true,	// 异步
                     success:function(){},
                     error:function(){}
                     });
                     */
                } else {
                    return;		// 有文件不存在直接返回报错
                }
            }

            window.location = src;

        }
    }

    function keyboardEvent(event) {
        var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;//解决浏览器之间的差异问题
        document.getElementById("xxx").innerHTML += keyCode;
        var allkey = document.getElementById("xxx").innerHTML;
        if ((allkey.substr(-4, 4) == "9799" && keyCode != 99) || keyCode == 13) {
            <? if ($_SESSION["FBFH"]=="1") {?>window.location.href = "jcsj/YSXMqt_sh_p.php?lx=show&getin=ok&ddh=" + document.getElementById("checkNum").value;
            <?}elseif($_SESSION["FBPD"]=="1" || $_SESSION["FBHD"]=="1"){?>
            window.location.href = "jcsj/YSXMqt_show_p.php?lx=show&getin=ok&ddh=" + document.getElementById("checkNum").value;
            <?}?>
        }
    }

    function searchorder(event){

        var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;//解决浏览器之间的差异问题
        document.getElementById("xxx").innerHTML += keyCode;
        var allkey = document.getElementById("xxx").innerHTML;
        if ((allkey.substr(-4, 4) == "9799" && keyCode != 99) || keyCode == 13) {
            <? if ($_SESSION["FBSD"]=="1") {?>

            window.location.href='?fdd='+document.form1.fdd.value+'&filename='+document.form1.filename.value+'&rq1='+document.form1.rq1.value+'&rq2='+document.form1.rq2.value+'&gp='+document.form1.state.options[document.form1.state.selectedIndex].value;
            <?}?>
        }
    }
    function formsub() {
        document.getElementById("form1").submit();
    }

    //-->
</script>
<script src="../js/WdatePicker.js" type="text/javascript" language="javascript"></script>
<style type="text/css">
    .ordertb th ,.ordertb td{
        text-align: center;

    }
</style>
<body style="font-size:12px">
<form name="form1" method="GET" action="" id="form1">
    <? if (/*$_SESSION["FBSD"]!=1 and */$_SESSION["FBPD"]!=1 and $_SESSION["FBFH"]!=1 and $_SESSION["FBCW"]!=1 and $_SESSION["FBHD"]!=1) {?>

   当前<?if($_SESSION["FBSD"] == 1) echo "前台"; else {echo "客服"?>：<? echo $_SESSION["YKOAUSER"];
   ?>　下单时间：
        <input onClick="WdatePicker();" maxlength="50" class="Wdate" style="cursor:pointer;" type="text" name="rq1" id="rq1" value="<? echo $d1; ?>" size="8" readonly/>～
        <input onClick="WdatePicker();" maxlength="50" class="Wdate" style="cursor:pointer;" type="text" name="rq2" id="rq2" value="<? echo $d2; ?>" size="8" readonly/>
             <input type="submit" value="查找"/>

        <? } ?>
   　
   <?if(substr($_SESSION["GDWDM"],0,4)){?></select> <? if(1/*$dwdm != '3303'*/) { ?>

    <a href="#" class="nav" onClick="javascript:window.open('jcsj/NS_new.php?lx=1&xsbh='+encodeURI('<? echo $_SESSION["YKOAUSER"]?>'), 'OrderDetail', 'height=600px,width=920px,top=100px,left=200px,location=no,menubar=no,resizable=no,scrollbars=yes,status=no,titlebar=no,toolbar=no,directories=no')">新建订单</a>

    <? } ?>

　　<?} if($_SESSION["FBSD"]==1) echo "<br>"?>
<? }

    if($_SESSION['FBCW'] ==1){
        include '../commonfile/calc_options.php';
    }
    if ($_SESSION["FBCW"] == 1 || $_SESSION['FBSD'] == 1) {

        ?>
        订单状态：<select name="gp" onchange="form1.submit();">

            <option value="待生产" <? if ($gp == '待生产') echo "selected"; ?> >待生产</option>
            <option value="进入生产" <? if ($gp == '进入生产') echo "selected"; ?> >进入生产</option>
            <option value="已打印" <? if ($gp == '已打印') echo "selected"; ?> >已打印</option>
            <option value="待结算" <? if ($gp == '待结算') echo "selected"; ?> >待结算</option>
            <option value="待配送" <? if ($gp == '待配送') echo "selected"; ?> >待配送</option>
            <option value="已发货" <? if ($gp == '已发货') echo "selected"; ?> >已发货</option>
            <option value="订单完成" <? if ($gp == '订单完成') echo "selected"; ?> >订单完成</option>
            <option value="作废订单" <? if ($gp == '作废订单') echo "selected"; ?> >作废订单</option>
            <option value="%" <? if ($gp == '%') echo "selected"; ?> >全部</option>
        </select>

        <?

        if($_SESSION["FBCW"] == 1 || $_SESSION["FBSD"] == 1 ||$_SESSION["FBFH"] == 1){
            ?>
            配送方式:
            <select name="psfs" id="psfs" onchange="form1.submit();">
                <option value=""></option>
                <option value="上门自取" <? if ($psfs == "上门自取") echo "selected"; ?>>
                    上门自取
                </option>
                <option value="快递配送" <? if ($psfs == "快递配送") echo "selected"; ?>>
                    快递配送
                </option>
                <option value="物流配送" <? if ($psfs == "物流配送") echo "selected"; ?>>
                    物流配送
                </option>
                <option value="送货" <? if ($psfs == "送货") echo "selected"; ?>>
                    送货
                </option>
            </select>
            <?
        }
        ?>
        查找订单：<input type="text" name="fdd" placeholder="订单号或客户名" value="<? echo $_GET['fdd'] ?>"/>　　订单或生产备注：
        <input type="text" name="filename" placeholder="备注中的信息" value="<? echo $_GET['filename']; ?>"/>　　下单时间：
        <input onClick="WdatePicker();" maxlength="50" class="Wdate" style="cursor:pointer;" type="text" name="rq1" id="rq1" value="<? echo $d1; ?>" size="8" readonly/>～
        <input onClick="WdatePicker();" maxlength="50" class="Wdate" style="cursor:pointer;" type="text" name="rq2" id="rq2" value="<? echo $d2; ?>" size="8" readonly/>
        问题订单:
        <select name="trouble_order" onchange="form1.submit();">
            <option value=""> </option>
            <option value="1"  <? if($_GET['trouble_order'] == '1') echo 'selected' ?>>状态异常订单</option>
            <!--            <option value="2"  --><?// if($_GET['trouble_order'] == '2') echo 'selected' ?><!-->作废订单</option>-->
            <option value="3" <? if($_GET['trouble_order'] == '3') echo 'selected' ?>>0金额订单</option>
            <option value="4" <? if($_GET['trouble_order'] == '4') echo 'selected' ?>>漏收款订单</option>
        </select>
        <input type="submit" value="查找"/>

        <input type="button" onclick="javascript:window.open('../phpExcel/toexcel.php?state=<? echo $gp ?>&fdd=<? echo $_GET['fdd']; ?>&dd1=<? echo urlencode($dd1); ?>&dd2=<? echo urlencode($dd2); ?>&dwdmstr=<? echo urlencode($dwdmStr) ?>&trouble_order=<? echo $_GET['trouble_order']; ?>&psfs=<? echo $psfs; ?>&producedetail=<? echo $gpmore; ?>')" name="buttonprint" value="导出">

    <? }

    if ($_SESSION["FBPD"] == "1" or $_SESSION["FBHD"] == "1" or $_SESSION["FBFH"] == "1") { ?>
        <input type="text" style="display:none;"/>
        订单号：<input type="text" class="txt" id="checkNum" name="checkNum" maxlength="15" onkeydown="keyboardEvent(event);"/>
        <!--<input type="button" value="确定" onclick="search()" /> -->
        请扫描<? echo $_SESSION["FBFH"] == "1" ? "配送单" : "生产单"; ?>上条码或手动输入完整订单号，按回车提交查询
        <br><? if ($_SESSION["FBFH"] == 1) { ?><br>
            客户名称<input type="text" name="_khmc" id="_khmc" value=""/>
            <input type="button" value="查找" onclick="formsub()"/><? } ?>
    <? } ?>
    <? if ($gp == "待结算") { ?>
        <input name="bt8" id="bt8" type="button" value="结算选中订单" alt="" onclick="javascript:window.open('jcsj/YSXMqt_ddjs.php?ddh='+this.alt,'Orderjs','height=400px,width=720px,top=100px,left=200px,location=no,menubar=no,resizable=no,scrollbars=yes,status=no,titlebar=no,toolbar=no,directories=no');return false;"/>

    <? } ?>


</form>
<span id='xxx' style="display:none;"></span>
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:0px;">
    <tbody>
    <tr>
        <td valign="top">
            <div style="padding:15px 4px 22px 4px; color:#58595B">
                <div class="bot_line"></div>
                <div class="page">
                    <div id="AspNetPager2" style="width:100%;text-align:right;">
                        <table width="100%" border="0" cellpadding="0" cellspacing="0">
                            <tbody>
                            <tr>
                                <td valign="bottom" align="left" nowrap="true" style="width:40%;"></td>
                                <td valign="bottom" align="right" nowrap="true" class="" style="width:60%;"></td>
                            </tr>
                            </tbody>
                        </table>
                        <table cellspacing="0" cellpadding="0" rules="all" border="1" id="gvOrder" style="border-width:0px;width:100%;border-collapse:collapse;border:1px solid #D3D3D3; border-bottom:1px solid #D3D3D3; font-size:12px" class="ordertb">
                            <thead>
                            <tr class="td_title" style="height:30px;">
                                <th scope="col">客服</th>
                                <th scope="col">订单编号</th>
                                <th scope="col">客户名称</th>
                                <th scope="col">订购时间</th>
                                <th scope="col">要求完成</th>
                                <th scope="col">订单金额</th>
                                <th scope="col">预付定金</th>
                                <th scope="col">配送金额</th>
                                <th scope="col">配送要求</th>
                                <th scope="col">生产地</th>
                                <th scope="col">订单状态</th>
                                <th align="left" scope="col" width="10%">备注</th>
                                <th scope="col">操作</th>
                            </tr>
                            </thead>
                            <tbody>

                            <? for ($i = 0; $i < mysql_num_rows($rs); $i++) {


                                ?>
                                <tr class="td_title" style="height:30px;"
                                    onMouseOver="this.style.backgroundColor='#FFD'"
                                    onMouseOut="this.style.backgroundColor='white'">
                                    <td style="text-align: left"><? echo mysql_result($rs, $i, "xsbh"), '-', mysql_result($rs, $i, "xm"); ?></td>
                                    <td style="width:100px"><input name="checkBox[]" type="checkbox" value="<? echo mysql_result($rs, $i, "ddh"); ?>" onchange="javascript:if (this.checked) document.getElementById('bt8').alt+=',<? echo mysql_result($rs, $i, "ddh"); ?>'; else document.getElementById('bt8').alt=document.getElementById('bt8').alt.replace(/,<? echo mysql_result($rs, $i, "ddh"); ?>/g, '');"/><? echo mysql_result($rs, $i, "ddh"); ?>
                                        <br>
                                        <? echo "[明细：", mysql_result($rs, $i, "mxsl"), "]";
                                        if ($_SESSION["FBPD"] != 1) { ?>
                                            <a href="javascript:void(0)" onclick="javascript:window.open('jcsj/NS_new.php?ddh=<? echo mysql_result($rs, $i, "ddh"); ?>&from=list','OrderDetail','height=600px,width=920px,top=100px,left=200px,location=no,menubar=no,resizable=no,scrollbars=yes,status=no,titlebar=no,toolbar=no,directories=no');return false;" class="a1">
                                                详情
                                            </a>
                                        <? } ?>
                                    </td>
                                    <td><span><? echo mysql_result($rs, $i, "khmc"); ?><br><font color="#FFCCCC"><? echo "[", mysql_result($rs, $i, "cpms"), "]"; ?></font></span>
                                    </td>
                                    <td style="width:80px"><? echo mysql_result($rs, $i, "ddate"); ?></td>
                                    <td style="width:80px"><? echo mysql_result($rs, $i, "yqwctime"); ?></td>
                                    <td><span style="color:Red;"><? echo mysql_result($rs, $i, "dje"); ?></span>元
                                    </td>
                                    <td><span style="color:Red;"><? echo mysql_result($rs, $i, "djje"); ?></span>元
                                    </td>
                                    <td><span style="color:Red;"><? echo mysql_result($rs, $i, "kdje"); ?></span>元
                                    </td>
                                    <td
                                    ><? echo mysql_result($rs, $i, "psfs"); ?></td>
                                    <td><? echo mysql_result($rs, $i, "scjd"); ?></td>
                                    <td>
                                        <?
                                        echo  mysql_result($rs, $i, "state");


                                        if (mysql_result($rs, $i, "sksj") <> '') echo "<br>[已收款：", mysql_result($rs, $i, "sksj"), "]"; ?>
                                    </td>

                                    <td align="left" scope="col"><? echo mysql_result($rs, $i, "memo") ?></td>

                                    <td>
                                        <?
                                        //                                  显示是否委托
                                        $bh = mysql_result($rs,$i,'ddh');

                                        $iswx = mysql_query("select * from order_waixie where copyddh='".$bh."'",$conn);
                                        if($dwdm<>'3301' && $dwdm <>'3405'&& $dwdm <>'3309' ){
                                            if(mysql_num_rows($iswx)){

                                                echo '已委托修改请联系中心店';

                                            }
                                        }
                                        ?>
                                        <? if (mysql_result($rs, $i, "state") == "新建订单" and $_SESSION["FBSD"] != 1) {
                                            //if (mysql_result($rs,$i,"xjdid")==0)
                                            //echo "<a href='#' class='nav' onClick='javascript:window.open(\"jcsj/YSXMqt_mxdjs.php?from=list&ddh=".mysql_result($rs,$i,"ddh")."\", \"HT_dhdj\", \"toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=yes,width=850,height=410,left=300,top=100\")'>增加明细</a>&nbsp;&nbsp;";

                                            echo "<a href='#' onClick=\"javascript:suredo('jcsj/YSXMqt_del.php?lx=new2&BH=" . mysql_result($rs, $i, "ddh") . "','确定删除?')\">删除</a>&nbsp;&nbsp;";

                                            if (mysql_result($rs, $i, "mxsl") > 0){
                                                echo "<a href='#' onClick=\"javascript:suredo('jcsj/YSXMqt_del.php?BH2=" . mysql_result($rs, $i, "ddh") . "','确定提交生产?');\">提交生产</a>&nbsp;&nbsp;";

//                                                    委托生产,是否已经委托
                                                $bh = mysql_result($rs,$i,'ddh');

                                                $iswx = mysql_query("select * from order_waixie where copyddh='".$bh."'",$conn);
                                                if($dwdm=='3401' ||$dwdm=='3402'||$dwdm=='3403' ||$dwdm=='3404'||$dwdm=='3308'||$dwdm=='3451'||$dwdm=='3452'||$dwdm=='3453'||$dwdm=='3454'){
                                                    if(empty(mysql_fetch_array($iswx))){
                                                        $sqlwx = "select * from base_kh where waixie = $dwdm limit 1";
                                                        $reswx = mysql_query($sqlwx,$conn);
														if (mysql_num_rows($reswx)>0) {
                                                        $wxd_xsbh=mysql_result($reswx,0,'xsbh');
                                                        $wxd_khmc = mysql_result($reswx,0,'khmc');
														}
                                                        ?>
                                                        <a href="#" class="nav" onClick="javascript:window.open('jcsj/NS_new.php?lx=1&type=wx&copyddh='+<? echo $bh;?> +'&wxdkhmc='+encodeURI('<? echo $wxd_khmc; ?>')+'&xsbh='+encodeURI('<? echo $wxd_xsbh; ?>'), 'OrderDetail', 'height=600px,width=920px,top=100px,left=200px,location=no,menubar=no,resizable=no,scrollbars=yes,status=no,titlebar=no,toolbar=no,directories=no')">
                                                            委托中心店生产
                                                        </a>
                                                        <?
                                                    }
                                                }
                                            }
                                        }

                                        //                                            生产中和待生产的订单也可以委托
                                        if((mysql_result($rs,$i,'state') == '进入生产' || mysql_result($rs,$i,'state') == '待生产') && $_SESSION['FBSD'] != 1){

//                                                    委托生产,是否已经委托
                                            $bh = mysql_result($rs,$i,'ddh');

                                            $iswx = mysql_query("select * from order_waixie where copyddh='".$bh."'",$conn);
                                            if($dwdm=='3401' ||$dwdm=='3402'||$dwdm=='3403' ||$dwdm=='3404'||$dwdm=='3308'||$dwdm=='3451'||$dwdm=='3452'||$dwdm=='3453'||$dwdm=='3454'){
                                                if(empty(mysql_fetch_array($iswx))){
//                                                        找到中心店对应的门店外协客户账号
                                                    $sqlwx = "select * from base_kh where waixie = '$dwdm' limit 1";
                                                    $reswx = mysql_query($sqlwx,$conn);
													if (mysql_num_rows($reswx)>0) {
                                                    $wxd_xsbh=mysql_result($reswx,0,'xsbh');
                                                    $wxd_khmc = mysql_result($reswx,0,'khmc');
													}
//                                                        操作员不能操作
                                                    if($_SESSION['FBPD']!=1 && $_SESSION['FBFM']!=1 && $_SESSION['FBHD']!=1  ){

                                                        ?>

                                                        <a href="#" class="nav" onClick="javascript:window.open('jcsj/NS_new.php?lx=1&type=wx&copyddh='+<? echo $bh;?> +'&wxdkhmc='+encodeURI('<? echo $wxd_khmc; ?>')+'&xsbh='+encodeURI('<? echo $wxd_xsbh; ?>'), 'OrderDetail', 'height=600px,width=920px,top=100px,left=200px,location=no,menubar=no,resizable=no,scrollbars=yes,status=no,titlebar=no,toolbar=no,directories=no')">
                                                            委托中心店生产
                                                        </a>
                                                        <?
                                                    }
                                                }
                                            }
                                        }

                                        if ($_SESSION["FBSD"] == "1" || $_SESSION['FBCW'] == '1') {
                                            if (mysql_result($rs, $i, "state") == '待生产') {
//                                                echo "<a href='#' onclick=suredo('jcsj/YSXMqt_del.php?did=" . mysql_result($rs, $i, "ddh") . "','确定生产后不能修改数据!')>确定生产</a>&nbsp;&nbsp;";
                                                echo "<input type=button onclick='javascript:window.open(\"jcsj/YSXMqt_tuihui.php?didth=" . mysql_result($rs, $i, "id") . "\", \"HT_dhdj\", \"toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=yes,width=650,height=340,left=300,top=100\");' value='退回'>";
                                            } elseif (mysql_result($rs, $i, "state") != '退回') {
                                                //echo "<a href='#' class='nav' onClick='javascript:window.open(\"jcsj/YSXMqt_zx.php?ddh=".mysql_result($rs,$i,"ddh")."\", \"HT_dhdj\", \"toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=yes,width=950,height=540,left=300,top=100\")'>生产配送情况</a>";
                                                if (mysql_result($rs, $i, "sjpsfs") != '') echo "[", mysql_result($rs, $i, "sjpsfs"), "]";
                                            }
                                        }
                                        if (($_SESSION["FBPD"] == "1" || $_SESSION["FBHD"] == "1" || $_SESSION["FBCW"] == "1" || $_SESSION["FBSD"] == "1") and (mysql_result($rs, $i, "state") == "进入生产" || mysql_result($rs, $i, "state") == "已打印")) {
                                            echo "<a href='#' onclick=window.location.href='jcsj/YSXMqt_show_p.php?ddh=" . mysql_result($rs, $i, "ddh") . "&getin=ok&lx=show' >生产单</a>";
                                        }
                                        if (($_SESSION["FBFH"] == "1") and mysql_result($rs, $i, "state") == "待配送") {
                                            echo "<a href='#' onclick=window.location.href='jcsj/YSXMqt_sh_p.php?ddh=" . mysql_result($rs, $i, "ddh") . "&lx=show' >发货单</a>";
                                        }
                                        ?></td>
                                </tr>
                            <? } ?>
                            </tbody>
                        </table>
                    </div>

                    <br>

                    <div class="page1">
                        <?
                        //printout
                        if(!($_GET["bt2"]<>'' || $_GET['fdd']<>'' || $_GET['filename']<>'')){

                            //paging
                            $param =  "gp=" . $_GET["gp"] . "&rq1=" . $d1 . "&rq2=" . $d2 . "&trouble_order=" .$_GET['trouble_order'] . "&psfs=" .$psfs ."&producedetail=" .$gpmore;

                            include '../commonfile/paging_show.php';
                        }

                        ?>

                    </div>
                </div>

        </td>
    </tr>
    </tbody>
</table>
</body>
<script>
    function sel_detail(){
        var selv = form1.producedetail.value;
        var _trs = $('#gvOrder').find('tbody').find('tr');

        if(selv == '更多状态'){

            $.each(_trs,function(i,item) {
                $(item).show();
            });
        }else{

            $.each(_trs,function(i,item){

                var _issel = $(item).find('td')[10];

                if($(_issel).attr('dataType')!=selv){

                    $(_issel).closest('tr').hide();

                }else{
                    $(_issel).closest('tr').show();

                }
            } );

        }

    }
</script>

</html>
<? if ($_SESSION["FBPD"] == "1" or $_SESSION["FBHD"] == "1" or $_SESSION["FBFH"] == "1") { ?>
    <script type="text/javascript">document.getElementById("checkNum").focus();</script>
<? } ?>
