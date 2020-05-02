<?
session_start();
require("../inc/conn.php");
if ($_SESSION["OK"] <> "OK") {
    echo "<script language=JavaScript>{window.location.href='error.php';}</script>";
    exit;
} ?>

<?
$dwdm = substr($_SESSION["GDWDM"], 0, 4);
//每次进入页面之前将所有已收款再打印的订单状态从待结算改成待配送
if (substr($dwdm,0,2) == '34') {
//	$sqldjs = "select m.ddh from order_zh z, order_mainqt m where  m.ddh=z.ddh and m.state='待结算' and (m.needsign is null or m.needsign<>'1') and z.zy='订单结算'";
    mysql_query("update order_mainqt m set state = '待配送' where m.state='待结算' and (m.needsign is null or m.needsign<>'1') and ddh in (select ddh from order_zh z where z.zy='订单结算') ");
}
if ($_POST["bt2"]) {
    header("Content-type:application/vnd.ms-excel;charset=UTF-8");
    header("Content-Disposition:filename=" . iconv("utf-8", "gb2312", "订单列表[" . $d1 . "-" . $d2 . "].xls"));
    header("Expires:0");
    header('Pragma:   public');
    header("Cache-control:must-revalidate,post-check=0,pre-check=0");
}
//导出
if ($_POST["buttonprint"]) {

}
if ($_SESSION["FBSD"] == 1) {//前台
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
    if ($filename <> "") $filetj .= " and order_mainqt.memo like '%$filename%' ";
    if ($_GET["fdd"] <> "") {

        $rs = mysql_query("select order_mainqt.*,count(order_mxqt.id) mxsl,group_concat(order_mxqt.productname) cpms,xs.xm,sksj from b_ry xs,order_mainqt left join order_mxqt on order_mainqt.ddh=order_mxqt.ddh left join order_zh on order_mainqt.ddh=order_zh.ddh and zy<>'订单订金' where order_mainqt.xsbh=xs.bh and (order_mainqt.khmc like '%" . $_GET["fdd"] . "%' or order_mainqt.ddh like '%" . $_GET["fdd"] . "%')  and (order_mainqt.state like '$gp') and zzfy='" . substr($_SESSION["GDWDM"], 0, 4) . "' $filetj group by order_mainqt.ddh order by ddate desc", $conn);
    } else {

        $rs = mysql_query("select order_mainqt.*,count(order_mxqt.id) mxsl,group_concat(order_mxqt.productname) cpms,xs.xm,sksj from b_ry xs,order_mainqt left join order_mxqt on order_mainqt.ddh=order_mxqt.ddh left join order_zh on order_mainqt.ddh=order_zh.ddh and zy<>'订单订金' where order_mainqt.xsbh=xs.bh and (order_mainqt.state like '$gp') and zzfy='" . substr($_SESSION["GDWDM"], 0, 4) . "' $filetj group by order_mainqt.ddh order by ddate desc", $conn);
    }
} elseif ($_SESSION["FBPD"] == 1) //生产
    $rs = mysql_query("select order_mainqt.*,count(order_mxqt.id) mxsl,group_concat(order_mxqt.productname) cpms,xs.xm,sksj from b_ry xs,order_mainqt left join order_mxqt on order_mainqt.ddh=order_mxqt.ddh left join order_zh on order_mainqt.ddh=order_zh.ddh and zy<>'订单订金' where order_mainqt.xsbh=xs.bh and (order_mainqt.state='进入生产' or order_mainqt.state='生产完成') and pczy is null and zzfy='" . substr($_SESSION["GDWDM"], 0, 4) . "' group by order_mainqt.ddh order by ddate desc", $conn);

elseif ($_SESSION["FBHD"] == 1) //生产后加工

    $rs = mysql_query("select order_mainqt.*,count(order_mxqt.id) mxsl,group_concat(order_mxqt.productname) cpms,xs.xm,sksj from b_ry xs,order_mainqt left join order_mxqt on order_mainqt.ddh=order_mxqt.ddh left join order_zh on order_mainqt.ddh=order_zh.ddh and zy<>'订单订金' where order_mainqt.xsbh=xs.bh and (order_mainqt.state='进入生产' or order_mainqt.state='生产完成') and not pczy is null and hdczy is null and zzfy='" . substr($_SESSION["GDWDM"], 0, 4) . "' group by order_mainqt.ddh order by ddate desc", $conn);
elseif ($_SESSION["FBFH"] == 1) { //配送
    if ($_POST["_khmc"] <> "")
        $tj = " and order_mainqt.khmc like '%" . $_POST["_khmc"] . "%' ";
    else
        $tj = "";
    $rs = mysql_query("select order_mainqt.*,count(order_mxqt.id) mxsl,group_concat(order_mxqt.productname) cpms,xs.xm,sksj from b_ry xs,order_mainqt left join order_mxqt on order_mainqt.ddh=order_mxqt.ddh left join order_zh on order_mainqt.ddh=order_zh.ddh and zy<>'订单订金' where order_mainqt.xsbh=xs.bh $tj and order_mainqt.state='待配送' and zzfy='" . substr($_SESSION["GDWDM"], 0, 4) . "' group by order_mainqt.ddh order by ddate desc", $conn);
} elseif ($_SESSION["FBCW"] == 1) { //财务
//	$d1=$_POST["rq1"];if ($d1=="") {$d1=date("Y-m-")."01";$ss="";$tss="全部信息";}
//	$d2=$_POST["rq2"];if ($d2=="") {$d2=date("Y-m-d");}
//	$d3=$_POST["fkhmc"];if ($d3=="") {$d3="%";}
//	$rs=mysql_query("select order_mainqt.*,count(order_mxqt.id) mxsl,group_concat(order_mxqt.productname) cpms,xs.xm,sksj from b_ry xs,order_mainqt left join order_mxqt on order_mainqt.ddh=order_mxqt.ddh left join order_zh on order_mainqt.ddh=order_zh.ddh and zy<>'订单订金' where order_mainqt.xsbh=xs.bh and order_mainqt.state='订单完成' and ddate>='$d1 00:00:00' and ddate<='$d2 23:59:59' and order_mainqt.khmc like '%{$d3}%' and zzfy='".substr($_SESSION["GDWDM"],0,4)."' group by order_mainqt.ddh order by ddate desc",$conn);

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
    if ($filename <> "") $filetj .= " and order_mainqt.memo like '%$filename%' ";
    if ($_GET["fdd"] <> "") {

        $rs = mysql_query("select order_mainqt.*,count(order_mxqt.id) mxsl,group_concat(order_mxqt.productname) cpms,xs.xm,sksj from b_ry xs,order_mainqt left join order_mxqt on order_mainqt.ddh=order_mxqt.ddh left join order_zh on order_mainqt.ddh=order_zh.ddh and zy<>'订单订金' where order_mainqt.xsbh=xs.bh and (order_mainqt.khmc like '%" . $_GET["fdd"] . "%' or order_mainqt.ddh like '%" . $_GET["fdd"] . "%')  and (order_mainqt.state like '$gp') and zzfy='" . substr($_SESSION["GDWDM"], 0, 4) . "' $filetj group by order_mainqt.ddh order by ddate desc", $conn);
    } else {
        $rs = mysql_query("select order_mainqt.*,count(order_mxqt.id) mxsl,group_concat(order_mxqt.productname) cpms,xs.xm,sksj from b_ry xs,order_mainqt left join order_mxqt on order_mainqt.ddh=order_mxqt.ddh left join order_zh on order_mainqt.ddh=order_zh.ddh and zy<>'订单订金' where order_mainqt.xsbh=xs.bh and (order_mainqt.state like '$gp') and zzfy='" . substr($_SESSION["GDWDM"], 0, 4) . "' $filetj group by order_mainqt.ddh order by ddate desc", $conn);
    }

} else
//	if($_SESSION["YKOAUSER"] == "zhangfl")
//	$rs=mysql_query("select order_mainqt.*,count(order_mxqt.id) mxsl,group_concat(order_mxqt.productname) cpms,xs.xm,sksj from b_ry xs,order_mainqt left join order_mxqt on order_mainqt.ddh=order_mxqt.ddh left join order_zh on order_mainqt.ddh=order_zh.ddh and zy<>'订单订金' group by order_mainqt.ddh order by ddate desc",$conn);
//	else
    $rs = mysql_query("select order_mainqt.*,count(order_mxqt.id) mxsl,group_concat(order_mxqt.productname) cpms,xs.xm,sksj from b_ry xs,order_mainqt left join order_mxqt on order_mainqt.ddh=order_mxqt.ddh left join order_zh on order_mainqt.ddh=order_zh.ddh and zy<>'订单订金' where order_mainqt.xsbh=xs.bh and xs.bh='" . $_SESSION["YKOAUSER"] . "'  group by order_mainqt.ddh order by ddate desc", $conn);


//分页
if ($_GET["fdd"] <> "" || $_POST["_khmc"] <> "") {
    $page_num = mysql_num_rows($rs) + 1;
} else {
    $page_num = 15;
}     //每页行数
$page_no = $_GET["pno"];     //当前页
if ($page_no == "") {
    $page_no = 1;
}
$page_f = $page_num * ($page_no - 1);   //开始行
$page_e = $page_f + $page_num;            //结束行
if ($page_e > mysql_num_rows($rs)) {
    $page_e = mysql_num_rows($rs);
}
$page_t = ceil(mysql_num_rows($rs) / $page_num);  //总页数
//分页
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
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
            <?}else{?>
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

            window.location.href='MYOrderShowns.php?fdd='+document.form1.fdd.value+'&filename='+document.form1.filename.value+'&rq1='+document.form1.rq1.value+'&rq2='+document.form1.rq2.value+'&gp='+document.form1.state.options[document.form1.state.selectedIndex].value;
            <?}?>
        }
    }
    function formsub() {
        document.getElementById("form1").submit();
    }

    //-->
</script>
<script src="../js/WdatePicker.js" type="text/javascript" language="javascript"></script>
<body style="font-size:12px">
<form name="form1" method="post" action="" id="form1">
    <? if (/*$_SESSION["FBSD"]!=1 and */$_SESSION["FBPD"]!=1 and $_SESSION["FBFH"]!=1 and $_SESSION["FBCW"]!=1 and $_SESSION["FBHD"]!=1) {?>
当前<?if($_SESSION["FBSD"] == 1) echo "前台"; else echo "客服"?>：<? echo $_SESSION["YKOAUSER"];?>　　
<?if(substr($_SESSION["GDWDM"],0,4)){?></select> <? if(1/*$dwdm != '3303'*/) { ?>
<a href="#" class="nav" onClick="javascript:window.open('jcsj/NS_new.php?lx=1&xsbh='+encodeURI('<? echo $_SESSION["YKOAUSER"]?>'), 'OrderDetail', 'height=600px,width=920px,top=100px,left=200px,location=no,menubar=no,resizable=no,scrollbars=yes,status=no,titlebar=no,toolbar=no,directories=no')">新建订单</a>
<? } ?>
　　<?} if($_SESSION["FBSD"]==1) echo "<br>"?>
<? }
    if ($_SESSION["FBSD"] == 1) { ?>
        订单状态：<select name="state"
                     onchange="javascript:window.location.href='MYOrderShowns.php?gp='+document.form1.state.options[document.form1.state.selectedIndex].value;">
            <? if ($dwdm == '3301' || $dwdm == '3303' || $dwdm == '3401' || $dwdm == '3402' || $dwdm == '3403' || $dwdm == '3404' || $dwdm == '3405') { ?>
            <option value="待生产" <? if ($gp == '待生产') echo "selected"; ?> >待生产</option><?
            } ?>
            <option value="进入生产" <? if ($gp == '进入生产') echo "selected"; ?> >生产中</option>
            <option value="待结算" <? if ($gp == '待结算') echo "selected"; ?> >待结算</option>
            <option value="待配送" <? if ($gp == '待配送') echo "selected"; ?> >待配送</option>
            <option value="%" <? if ($gp == '%') echo "selected"; ?> >全部</option>
        </select>

        <? if ($gp == '进入生产'){  ?>

            <select name = 'producedetail' onchange="sel_detail();">
                <option value="更多状态" >更多状态</option>
                <option value="打印生产中" <? if ($gpmore == 'printing') echo "selected"; ?> >打印生产中</option>
                <option value="后加工中" <? if ($gpmore == 'hding') echo "selected"; ?> >后加工中</option>
            </select>
        <? } ?>

        查找订单：<input type="text" name="fdd" width="15" placeholder="完整或部分的订单号或客户名" onkeydown="searchorder(event);"/>　　文件名：<input type="text"
                                                                                                                                name="filename"
                                                                                                                                width="15"
                                                                                                                                placeholder="备注中的文件名信息"/>　　下单时间：
        <input onClick="WdatePicker();" maxlength="50" class="Wdate" style="cursor:pointer;" type="text" name="rq1"
               id="rq1" value="<? echo $d1; ?>" size="8" readonly/>～
        <input onClick="WdatePicker();" maxlength="50" class="Wdate" style="cursor:pointer;" type="text" name="rq2"
               id="rq2" value="<? echo $d2; ?>" size="8" readonly/> 　　<input type="button" value="查找"
                                                                             onclick="javascript:window.location.href='MYOrderShowns.php?fdd='+document.form1.fdd.value+'&filename='+document.form1.filename.value+'&rq1='+document.form1.rq1.value+'&rq2='+document.form1.rq2.value+'&gp='+document.form1.state.options[document.form1.state.selectedIndex].value;"/>
        <!--        <input type="submit" name="bt2" value="导出"/>-->
    <? }


    if ($_SESSION["FBCW"] == 1) {
        ?>
        订单状态：<select name="state"
                     onchange="javascript:window.location.href='MYOrderShowns.php?gp='+document.form1.state.options[document.form1.state.selectedIndex].value;">
            <? if ($dwdm == '3301' || $dwdm == '3401' || $dwdm == '3402' || $dwdm == '3403' || $dwdm == '3404' || $dwdm == '3405') {
                ?>
                <option value="待生产" <? if ($gp == '待生产') echo "selected"; ?> >待生产</option>
                <?
            } ?>
            <option value="进入生产" <? if ($gp == '进入生产') echo "selected"; ?> >生产中</option>
            <option value="待结算" <? if ($gp == '待结算') echo "selected"; ?> >待结算</option>
            <option value="待配送" <? if ($gp == '待配送') echo "selected"; ?> >待配送</option>
            <option value="订单完成" <? if ($gp == '订单完成') echo "selected"; ?> >订单完成</option>
            <option value="%" <? if ($gp == '%') echo "selected"; ?> >全部</option>
        </select>

        <? if ($gp == '进入生产'){  ?>

            <select name = 'producedetail' onchange="sel_detail();">
                <option value="更多状态" >更多状态</option>
                <option value="打印生产中" <? if ($gpmore == 'printing') echo "selected"; ?> >打印生产中</option>
                <option value="后加工中" <? if ($gpmore == 'hding') echo "selected"; ?> >后加工中</option>
            </select>
            <? } ?>
        查找订单：<input type="text" name="fdd" width="15" placeholder="完整或部分的订单号或客户名"/>　　文件名：<input type="text"
                                                                                                name="filename"
                                                                                                width="15"
                                                                                                placeholder="备注中的文件名信息"/>　　下单时间：
        <input onClick="WdatePicker();" maxlength="50" class="Wdate" style="cursor:pointer;" type="text" name="rq1"
               id="rq1" value="<? echo $d1; ?>" size="8" readonly/>～
        <input onClick="WdatePicker();" maxlength="50" class="Wdate" style="cursor:pointer;" type="text" name="rq2"
               id="rq2" value="<? echo $d2; ?>" size="8" readonly/> 　　<input type="button" value="查找"
                                                                             onclick="javascript:window.location.href='MYOrderShowns.php?fdd='+document.form1.fdd.value+'&filename='+document.form1.filename.value+'&rq1='+document.form1.rq1.value+'&rq2='+document.form1.rq2.value+'&gp='+document.form1.state.options[document.form1.state.selectedIndex].value;"/>
    <? }


    if ($_SESSION["FBPD"] == "1" or $_SESSION["FBHD"] == "1" or $_SESSION["FBFH"] == "1") { ?>
        订单号：<input type="text" class="txt" id="checkNum" name="checkNum" maxlength="15"
                   onkeydown="keyboardEvent(event);"/>
        <!--<input type="button" value="确定" onclick="search()" /> -->
        请扫描<? echo $_SESSION["FBFH"] == "1" ? "配送单" : "生产单"; ?>上条码或手动输入完整订单号，按回车提交查询
        <br><? if ($_SESSION["FBFH"] == 1) { ?><br>
            <form method="post" id="khform">客户名称<input type="text" name="_khmc" id="_khmc" value=""/>
                <input type="button" value="查找" onclick="formsub()"/></form><? } ?>
    <? } ?>
    <? if ($gp == "待结算") { ?>
        <input name="bt8" id="bt8" type="button" value="结算选中订单" alt=""
               onclick="javascript:window.open('jcsj/YSXMqt_ddjs.php?ddh='+this.alt,'Orderjs','height=400px,width=720px,top=100px,left=200px,location=no,menubar=no,resizable=no,scrollbars=yes,status=no,titlebar=no,toolbar=no,directories=no');return false;"/>

        <input type="button" onclick="javascript:window.open('../phpExcel/toexcel.php?state=<? echo $gp ?>&fdd=<? echo $_GET['fdd']; ?>&dd1=<? echo $dd1; ?>&dd2=<? echo $dd2; ?>')" name="buttonprint" value="导出">
    <? } ?>
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
                            <table cellspacing="0" cellpadding="0" rules="all" border="1" id="gvOrder"
                                   style="border-width:0px;width:100%;border-collapse:collapse;border:1px solid #D3D3D3; border-bottom:1px solid #D3D3D3; font-size:12px">
                                <thead>
                                <tr class="td_title" style="height:30px;">
                                    <th align="center" scope="col">客服</th>
                                    <th align="center" scope="col">订单编号</th>
                                    <th align="center" scope="col">客户名称</th>
                                    <th align="center" scope="col">订购时间</th>
                                    <th align="center" scope="col">要求完成</th>
                                    <th align="center" scope="col">订单金额</th>
                                    <th align="center" scope="col">预付定金</th>
                                    <th align="center" scope="col">配送金额</th>
                                    <th align="center" scope="col">配送要求</th>
                                    <th align="center" scope="col">生产地</th>
                                    <th align="center" scope="col">订单状态</th>
                                    <th align="left" scope="col" width="10%">备注</th>
                                    <th align="center" scope="col">操作</th>
                                </tr>
                                </thead>
                                <tbody>

                                <? for ($i = $page_f; $i < $page_e; $i++) { ?>
                                    <tr class="td_title" style="height:30px;"
                                        onMouseOver="this.style.backgroundColor='#FFD'"
                                        onMouseOut="this.style.backgroundColor='white'">
                                        <td class="td_content"
                                            align="left"><? echo mysql_result($rs, $i, "xsbh"), '-', mysql_result($rs, $i, "xm"); ?></td>
                                        <td align="center" class="td_content" style="width:100px"><input
                                                name="checkBox[]" type="checkbox"
                                                value="<? echo mysql_result($rs, $i, "ddh"); ?>"
                                                onchange="javascript:if (this.checked) document.getElementById('bt8').alt+=',<? echo mysql_result($rs, $i, "ddh"); ?>'; else document.getElementById('bt8').alt=document.getElementById('bt8').alt.replace(/,<? echo mysql_result($rs, $i, "ddh"); ?>/g, '');"/><? echo mysql_result($rs, $i, "ddh"); ?>
                                            <br>
                                            <? echo "[明细：", mysql_result($rs, $i, "mxsl"), "]";
                                            if ($_SESSION["FBPD"] != 1) { ?>
                                                <a href="javascript:void(0)"
                                                   onclick="javascript:window.open('jcsj/NS_new.php?ddh=<? echo mysql_result($rs, $i, "ddh"); ?>&from=list','OrderDetail','height=600px,width=920px,top=100px,left=200px,location=no,menubar=no,resizable=no,scrollbars=yes,status=no,titlebar=no,toolbar=no,directories=no');return false;"
                                                   class="a1">详情</a>
                                            <? } ?>
                                        </td>
                                        <td align="center" class="td_content"><span
                                                class="td_content"><? echo mysql_result($rs, $i, "khmc"); ?><br><font
                                                    color="#FFCCCC"><? echo "[", mysql_result($rs, $i, "cpms"), "]"; ?></font></span>
                                        </td>
                                        <td align="center" class="td_content"
                                            style="width:80px"><? echo mysql_result($rs, $i, "ddate"); ?></td>
                                        <td align="center" class="td_content"
                                            style="width:80px"><? echo mysql_result($rs, $i, "yqwctime"); ?></td>
                                        <td align="center" class="td_content"><span
                                                style="color:Red;"><? echo mysql_result($rs, $i, "dje"); ?></span>元
                                        </td>
                                        <td align="center" class="td_content"><span
                                                style="color:Red;"><? echo mysql_result($rs, $i, "djje"); ?></span>元
                                        </td>
                                        <td align="center" class="td_content"><span
                                                style="color:Red;"><? echo mysql_result($rs, $i, "kdje"); ?></span>元
                                        </td>
                                        <td align="center"
                                            class="td_content"><? echo mysql_result($rs, $i, "psfs"); ?></td>
                                        <td class="td_content"
                                            align="center"><? echo mysql_result($rs, $i, "scjd"); ?></td>
                                        <td class="td_content" align="center" dataType="<? if(mysql_result($rs,$i,'state') == '进入生产'){
                                            if(empty(mysql_result($rs,$i,'pczy'))){
                                                echo '打印生产中';
                                            }elseif(!empty(mysql_result($rs,$i,'pczy')) && empty(mysql_result($rs,$i,'hdczy'))){
                                                echo '后加工中';
                                            }else{
                                                echo '生产中';
                                            }
                                        } ?>">
                                            <? //echo mysql_result($rs, $i, "state");
                                            if(mysql_result($rs,$i,'state') == '进入生产'){
                                                if(empty(mysql_result($rs,$i,'pczy'))){
                                                    echo '打印生产中';
                                                }elseif(!empty(mysql_result($rs,$i,'pczy')) && empty(mysql_result($rs,$i,'hdczy'))){
                                                    echo '后加工中';
                                                }else{
                                                    echo '生产中';
                                                }
                                            }else{
                                                echo  mysql_result($rs, $i, "state");
                                            }

                                            if (mysql_result($rs, $i, "sksj") <> '') echo "<br>[已收款：", mysql_result($rs, $i, "sksj"), "]"; ?>
                                        </td>

                                        <td align="left" scope="col"><? echo mysql_result($rs, $i, "memo") ?></td>

                                        <td class="td_content" align="center">
                                            <?
//                                            显示是否委托
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
                                                    if(substr($dwdm,0,3)=='340' && substr($dwdm,3,1) <>'5' && substr($dwdm,3,1)<>'0' ){
                                                        if(empty(mysql_fetch_array($iswx))){
                                                            $sqlwx = "select * from base_kh where waixie = $dwdm limit 1";
                                                            $reswx = mysql_query($sqlwx,$conn);
                                                            $wxd_xsbh=mysql_result($reswx,0,'xsbh');
                                                            $wxd_khmc = mysql_result($reswx,0,'khmc');
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
                                                if(substr($dwdm,0,3)=='340' && substr($dwdm,3,1) <>'5' && substr($dwdm,3,1)<>'0' ){
                                                    if(empty(mysql_fetch_array($iswx))){
                                                        $sqlwx = "select * from base_kh where waixie = $dwdm limit 1";
                                                        $reswx = mysql_query($sqlwx,$conn);
                                                        $wxd_xsbh=mysql_result($reswx,0,'xsbh');
                                                        $wxd_khmc = mysql_result($reswx,0,'khmc');

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
                                                    echo "<a href='#' onclick=suredo('jcsj/YSXMqt_del.php?did=" . mysql_result($rs, $i, "ddh") . "','确定生产后不能修改数据!')>确定生产</a>&nbsp;&nbsp;";
                                                    echo "<input type=button onclick='javascript:window.open(\"jcsj/YSXMqt_tuihui.php?didth=" . mysql_result($rs, $i, "id") . "\", \"HT_dhdj\", \"toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=yes,width=650,height=340,left=300,top=100\");' value='退回'>";
                                                } elseif (mysql_result($rs, $i, "state") != '退回') {
                                                    //echo "<a href='#' class='nav' onClick='javascript:window.open(\"jcsj/YSXMqt_zx.php?ddh=".mysql_result($rs,$i,"ddh")."\", \"HT_dhdj\", \"toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=yes,width=950,height=540,left=300,top=100\")'>生产配送情况</a>";
                                                    if (mysql_result($rs, $i, "sjpsfs") != '') echo "[", mysql_result($rs, $i, "sjpsfs"), "]";
                                                }
                                            }
                                            if (($_SESSION["FBPD"] == "1" || $_SESSION["FBHD"] == "1" || $_SESSION["FBCW"] == "1" || $_SESSION["FBSD"] == "1") and mysql_result($rs, $i, "state") == "进入生产") {
                                                echo "<a href='#' onclick=window.location.href='jcsj/YSXMqt_show_p.php?ddh=" . mysql_result($rs, $i, "ddh") . "&getin=ok&lx=show' >生产单</a>";
                                                if ($dwdm = 3301) echo "　　<a href='192.168.1.71:88/YSXMqt_show_p.php?ddh=" . mysql_result($rs, $i, "ddh") . "&getin=ok&lx=show&dwdm=" . $dwdm . "'>生产单</a>";
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


                            <DIV STYLE="width:87%; float:right;" align="right"><A <? if ($page_t > 1 and $page_no > 1) {
                                    echo "href=" . $_SERVER["PHP_SELF"] . "?pno=1&gp=" . $_GET["gp"] . "&rq1=" . $d1 . "&rq2=" . $d2;
                                } else {
                                    echo "disabled style='color:gray;'";
                                }; ?>>首页</A>　<A <? if ($page_t > 1 and $page_no > 1) {
                                    echo "href=" . $_SERVER["PHP_SELF"] . "?pno=" . ($page_no - 1) . "&gp=" . $_GET["gp"] . "&rq1=" . $d1 . "&rq2=" . $d2;
                                } else {
                                    echo "disabled style='color:gray;'";
                                }; ?>>上一页</A>　<A <? if ($page_t > 1 and $page_no < $page_t) {
                                    echo "href=" . $_SERVER["PHP_SELF"] . "?pno=" . ($page_no + 1) . "&gp=" . $_GET["gp"] . "&rq1=" . $d1 . "&rq2=" . $d2;
                                } else {
                                    echo "disabled style='color:gray;'";
                                }; ?>>下一页</A>　<A <? if ($page_t > 1 and $page_no <> $page_t) {
                                    echo "href=" . $_SERVER["PHP_SELF"] . "?pno=" . $page_t . "&gp=" . $_GET["gp"] . "&rq1=" . $d1 . "&rq2=" . $d2;
                                } else {
                                    echo "disabled style='color:gray;'";
                                }; ?>>尾页</A>　
                                <INPUT name="pno" onKeyDown="" value="<? echo $page_no ?>" size="3">
                                <INPUT name="ZKPager1" type="button" class="menubutton" value="转到"
                                       onClick="javascript:window.location.href='<? echo $_SERVER["PHP_SELF"] ?>?pno='+document.form1.pno.value+'&gp=<? echo $_GET["gp"] . "&rq1=" . $d1 . "&rq2=" . $d2; ?>'">　
                                第<? echo $page_no . "/" . $page_t ?>页&nbsp;&nbsp;&nbsp;&nbsp;</DIV>


                        </div>
                    </div>

            </td>
        </tr>
        </tbody>
    </table>

</form>

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
    <script>document.getElementById("checkNum").focus();</script>
<? } ?>
