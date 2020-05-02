<?
session_start();
require("../inc/conn.php");

if ($_SESSION["OK"] <> "OK") {
    echo "<script language=JavaScript>{window.location.href='error.php';}</script>";
    exit;
}

$dwdm = substr($_SESSION["GDWDM"], 0, 4);

include '../commonfile/calc_area_get.php';
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
    $d3 = "";
}

$dd1 = $d1 . " 00:00:00";
$dd2 = $d2 . " 23:59:59";

$skfs = $_GET["skfs"];
if ($skfs != "") {
    if ($skfs == "kong") $skfstj = " and z.xsbh='' ";
    else {
        $skfstj = " and locate('$skfs',z.xsbh)>0 ";
        if ($skfs == '划账')
            $skfstj .= " and locate('恒丰',z.xsbh)=0";
    }
} else
    $skfstj = "";

$sklx = $_GET["sklx"];
if ($sklx <> "") {
    if ($sklx == "预收款") {
        $sklxtj = " and (locate('预收款',z.xsbh)>0 or (z.zy<>'订单结算' and z.zy<>'订单订金'))";
    } else if ($sklx == "应收款") {
        $sklxtj = " and (locate('应收款',z.xsbh)>0 or (z.zy='订单结算' or z.zy='订单订金'))";
    } else if ($sklx == "预存扣款") {
        $sklxtj = "and z.xsbh='预存扣款' ";
    }
} else {
    $sklxtj = "";
}

$jetj = "(df+jf)<>0 ";

$zytj = '';
if ($_GET["zhaiyao"] <> '')
    $zytj = ' and z.zy like "%' . $_GET["zhaiyao"] . '%" ';

$ddhtj = '';
if ($_GET["ddh"] <> '')
    $ddhtj = ' and z.ddh="' . $_GET["ddh"] . '" ';

$skidtj = '';
if ($_GET["skid"] <> '')
    $skidtj = ' and z.id="' . $_GET["skid"] . '" ';

//	$dwdmStr = "('".$dwdm."')";
//	if($dwdm == '3301')
//		$dwdmStr = "('3301','3303')";
//pageing
if($dwdm =='3301'){
    $restotal = mysql_query("select count(*) as rowcount , sum(z.jf) as zjf ,sum(z.df) as zdf from order_zh z left join base_kh k on z.khmc=k.khmc where $jetj and fssj>='$dd1' and fssj<='$dd2' and (k.gdzk in $dwdmStr or k.waixie='$dwdm') and z.khmc like '%$d3%' $skfstj $sklxtj $zytj $ddhtj $skidtj ",$conn);

}else{
    $restotal = mysql_query("select count(*) as rowcount , sum(z.jf) as zjf ,sum(z.df) as zdf from order_zh z left join base_kh k on z.khmc=k.khmc where locate('返工' , z.khmc)=0 and $jetj and fssj>='$dd1' and fssj<='$dd2' and k.gdzk in $dwdmStr and z.khmc like '%$d3%' $skfstj $sklxtj $zytj $ddhtj $skidtj ",$conn);

}
$rowcount = mysql_result($restotal,0,'rowcount');
$zjf = mysql_result($restotal,0,'zjf');
$zdf = mysql_result($restotal,0,'zdf');

include '../commonfile/paging_data.php';
//打印 查询
if($_GET["bt2"]){
    $startrow = 0;
    $pagenum = $rowcount;
}

// 工厂仅可以看到恒丰店外协客户
//$sql = "select z.* from order_zh z left join base_kh k on z.khmc=k.khmc where locate('返工' , z.khmc)=0 and $jetj and fssj>='$dd1' and fssj<='$dd2' and (k.gdzk in $dwdmStr or k.waixie='$dwdm') and z.khmc like '%$d3%' $skfstj $sklxtj $zytj $ddhtj $skidtj order by fssj limit $startrow ,$pagenum";
$sql = "select z.* from order_zh z left join base_kh k on z.khmc=k.khmc  where locate('返工' , z.khmc)=0 and $jetj and fssj>='$dd1' and fssj<='$dd2' and k.gdzk in $dwdmStr and z.khmc like '%$d3%' $skfstj $sklxtj $zytj $ddhtj $skidtj order by fssj limit $startrow ,$pagenum";
if($dwdm == '3301'){
//    上海要显示返工单
    $sql = "select z.* from order_zh z left join base_kh k on z.khmc=k.khmc where $jetj and fssj>='$dd1' and fssj<='$dd2' and (k.gdzk in $dwdmStr or k.waixie='$dwdm') and z.khmc like '%$d3%' $skfstj $sklxtj $zytj $ddhtj $skidtj order by fssj limit $startrow ,$pagenum";
}

//	echo $sql;
$rs = mysql_query($sql, $conn);

?>
<script src="../js/WdatePicker.js" type="text/javascript" language="javascript"></script>
<form method="get" name="form1">
    按日期：<input onClick="WdatePicker();" maxlength="50" class="Wdate" style="cursor:pointer;" type="text" name="rq1" id="rq1" value="<? echo $d1; ?>" size="9" readonly />～<input onClick="WdatePicker();" maxlength="50" class="Wdate" style="cursor:pointer;" type="text" name="rq2" id="rq2" value="<? echo $d2; ?>" size="9" readonly />&nbsp;
    <!--客户名称：<input type="text" name="fkhmc" width="15" value="<?/* echo $d3 == "%" ? "" : $d3; */?>"/>&nbsp;	收款类型：<select name="sklx"><option value="">全部</option><option value="预收款"<?/* if ($sklx == "预收款") echo " selected" */?>>预收款</option><option value="应收款"<?/* if ($sklx == "应收款") echo " selected" */?>>应收款</option><option value="预存扣款"<?/* if ($sklx == "预存扣款") echo " selected" */?>>预存扣款</option></select>&nbsp;	收款方式：<select name="skfs"><option value="">全部</option><option value="预存扣款"<?/* if ($skfs == '预存扣款') echo " selected" */?>>预存扣款</option>
    <?/* $skrs = mysql_query("select * from b_skfs order by id", $conn);
    while ($skrow = mysql_fetch_array($skrs)) {
        echo "<option value='" . $skrow[1] . "' ";
        if ($skfs == $skrow[1])
            echo "selected";
        echo ">" . $skrow[1] . "</option>";
    } */?>
		<option value="kong" <?/* if ($skfs == "kong") echo "selected" */?>>空</option>
		</select>-->

    <? include '../commonfile/calc_options.php'; ?>

    　　<input name="bt1" type="submit" value="查 询"/>
    <input name="bt2" type="submit" value="导 出"/>
    <?


    if ($_GET["bt2"]) {
        header("Content-type:application/vnd.ms-excel;charset=UTF-8");
        header("Content-Disposition:filename=" . iconv("utf-8", "gb2312", "收款单导出[" . $d1 . "-" . $d2 . "].xls"));
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
        <script src="../js/WdatePicker.js" type="text/javascript" language="javascript"></script>
        <script src="../js/jquery-1.8.3.min.js" type="text/javascript" language="javascript"></script>

        <script>
            function del(ddh) {
                if (confirm("确认删除？删除后不可恢复！")) {
                    if (confirm("再次确认")) {
                        $.ajax({
                            type: "GET",
                            url: "../ncerp/jcsj/[删除订单的脚本].php?ddh=" + ddh,
                            async: true,
                            success: function (data) {
                                if (data == '1') {
                                    $("#ddh" + ddh).html(ddh);
                                    $("#del" + ddh).html("已删除");
                                } else {
                                    alert("delete failed,plz retry.");
                                }
                            },
                            error: function () {
                                alert("delete failed,plz retry...");
                            }
                        });
                    }
                }
            }
        </script>
        <style type="text/css">
            a {
                text-decoration: none;
                color: black;
            }

            a:hover {
                text-decoration: underline;
                color: blue;
            }
            .suminfo span{
                margin:20px;
                font-size:13px;
                font-weight:bold;
                color:#333;
            }
            .skdtb td{
                text-align: center;
                border-bottom: 1px solid #d3d3d3;
                padding: 5px 0;
            }
        </style>
    </head>

    <body style="font-size:12px">
    <div class="suminfo">
        <span>收款单总数:<? echo $rowcount; ?></span>
        <span>借方统计:<? echo $zjf; ?></span>
        <span>贷方统计:<? echo $zdf; ?></span>
    </div>
    <span id='xxx' style="display:none"></span>
    <table class="skdtb" width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:0px;">
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
                                    <th scope="col" width="10%">单据日期</th>
                                    <th scope="col" width="5%">收款编号</th>
                                    <th scope="col" width="5%">收款类型</th>
                                    <th scope="col" width="5%">收款方式</th>
                                    <th scope="col" width="10%">客户</th>
                                    <th scope="col" width="10%">增加</th>
                                    <th scope="col" width="10%">减少</th>
                                    <th scope="col" width="10%">摘要</th>
                                    <!--<th  scope="col" width="10">备注</th>-->
                                    <th scope="col" width="5%">订单号</th>
                                    <th scope="col" width="3%">操作</th>
                                </tr>
                                <? if (!($_GET["bt2"])) { ?>
                                    <tr style="height:30px;">
                                        <td><!--<input onClick="WdatePicker();"
                                                                                     maxlength="50" class="Wdate"
                                                                                     style="cursor:pointer;height:29px;"
                                                                                     type="text" name="rq1" id="rq1"
                                                                                     value="<?/* echo $d1; */?>" size="9"
                                                                                     readonly/>～<input
                                                onClick="WdatePicker();" maxlength="50" class="Wdate"
                                                style="cursor:pointer;height:29px" type="text" name="rq2" id="rq2"
                                                value="<?/* echo $d2; */?>" size="9" readonly/>--></td>
                                        <td><input name="skid" type="text" placeholder="输入收款编号" style="height:29px;" value="<? echo $_GET["skid"] ?>"/>
                                        </td>
                                        <td><select name="sklx" style="height:29px;">
                                                <option value="">收款类型</option>
                                                <option value="预收款" <? if ($sklx == "预收款") echo "selected"; ?>>预收款
                                                </option>
                                                <option value="应收款"<? if ($sklx == "应收款") echo "selected"; ?>>应收款
                                                </option>
                                            </select></td>
                                        <td><select name="skfs" style="height:29px;">
                                                <option value="">全部</option>
                                                <? $skrs = mysql_query("select * from b_skfs order by id", $conn);
                                                while ($skrow = mysql_fetch_array($skrs)) {
                                                    echo "<option value='" . $skrow[1] . "' ";
                                                    if ($skfs == $skrow[1])
                                                        echo "selected";
                                                    echo ">" . $skrow[1] . "</option>";
                                                } ?>
                                                <option value="kong" <? if ($skfs == "kong") echo "selected" ?>>空
                                                </option>
                                            </select></td>
                                        <td><input type="text" name="fkhmc" width="15" value="<? echo $d3 == "%" ? "" : $d3; ?>" placeholder="输入客户名称" style="height:29px;"/></td>
                                        <td><input name="jfjemin" style="height:29px;width:60px" placeholder="金额下限" value="<? echo $_GET["jfjemin"] ?>"/>　<input name="jfjemax" style="height:29px;width:60px;" placeholder="金额上限" value="<? echo $_GET["jfjemax"] ?>"/></td>
                                        <td><input name="dfjemin" style="height:29px;width:60px" placeholder="金额下限" value="<? echo $_GET["dfjemin"] ?>"/>　<input name="dfjemax" style="height:29px;width:60px;" placeholder="金额上限" value="<? echo $_GET["dfjemax"] ?>"/></td>
                                        <td><input name="zhaiyao" style="height:29px" placeholder="输入摘要" value="<? echo $_GET["zhaiyao"] ?>"/></td>
                                        <td><input name="ddh" style="height:29px" placeholder="输入完整订单号" value="<? echo $_GET["ddh"] ?>"/>
                                        </td>
                                        <td></td>
                                    </tr>
                                <? } ?>
                                <?

                                $t_djdf = 0;
                                $t_jsdf = 0;
                                $t_jf = 0;

                                while ($row = mysql_fetch_array($rs, MYSQL_ASSOC)) {

                                    if ($_GET['dfjemax'] <> '' || $_GET['dfjemin'] <> '') {
//                                        $je = max(abs($row['jf']), abs($row['df']));
                                        $je = abs($row['df']);
                                        if ($_GET['dfjemin'] <> '' && $_GET['dfjemax'] <> '') {
                                            if ($je > $_GET['dfjemax'] || $je < $_GET['dfjemin'])
                                                continue;
                                        } else if ($_GET['dfjemin'] <> '') {
                                            if ($je < $_GET['dfjemin'])
                                                continue;
                                        } else if ($_GET['dfjemax'] <> '') {
                                            if ($je > $_GET['dfjemax'])
                                                continue;
                                        }
                                    }

                                    if ($_GET['jfjemax'] <> '' || $_GET['jfjemin'] <> '') {
//                                        $je = max(abs($row['jf']), abs($row['df']));
                                        $je = abs($row['jf']);
                                        if ($_GET['jfjemin'] <> '' && $_GET['jfjemax'] <> '') {
                                            if ($je > $_GET['jfjemax'] || $je < $_GET['jfjemin'])
                                                continue;
                                        } else if ($_GET['jfjemin'] <> '') {
                                            if ($je < $_GET['jfjemin'])
                                                continue;
                                        } else if ($_GET['jfjemax'] <> '') {
                                            if ($je > $_GET['jfjemax'])
                                                continue;
                                        }
                                    }

                                    $tarr = explode('-', $row["xsbh"]);
                                    if (count($tarr) == 2) {
                                        $type = $tarr[0];
                                        $way = $tarr[1];
                                    } else {
                                        $type = "";
                                        $way = $tarr[0];
                                    }
                                    if ($row["zy"] == '订单结算' || $row["zy"] == '订单订金') {
                                        $type = '应收款';
                                    } else if ($type == '') {
                                        $type = "预收款";
                                    }
                                    ?>
                                    <tr style="height:30px;<? if ($type == '预收款') echo "background-color:#FFD;" ?>">
                                        <td><? echo $row["fssj"] ?></td>
                                        <td><? echo $row["id"] ?></td>
                                        <td><? echo $type
                                            ?></td>
                                        <td><? echo $way ?></td>
                                        <td><? echo $row["khmc"] ?></td>
                                        <td><? //$je = $row["df"] > 0 ? $row["df"] . " [贷方]" : $row["jf"] . " [借方]";
                                            echo $row["jf"]; $t_jf+= $row['jf']; ?></td>
                                        <td
                                        ><? echo $row["df"];
                                            if ($row["zy"] == "订单结算") $t_jsdf += $row["df"]; else if ($row["zy"] == "订单订金") $t_djdf += $row["df"]; ?>
                                        </td>
                                        <td datatype="<? echo $row['id']; ?>">
                                            <span class="zy" id="zy<? echo $row["id"] ?>"><? echo $row["zy"]; ?></span><? if (/*$row["zy"]<>"订单结算" &&*/
                                                trim($row["memo"]) <> '' && $row["zy"] <> $row["memo"]
                                            ) echo "<br>[<span class='memo' id='memo" . $row['id'] . "'>" . $row["memo"] . "</span>]"; ?>
                                        </td>
                                        <!--<td align="left" margin-left="5px" ><? if ($row["zy"] <> "订单结算") echo $row["memo"] ?></td>-->
                                        <td><a href="javascript:void(0)"
                                               onclick="javascript:window.open('../ncerp/jcsj/orderdetail.php?ddh=<? echo $row["ddh"]; ?>','OrderDetail','height=600px,width=920px,top=100px,left=200px,location=no,menubar=no,resizable=no,scrollbars=yes,status=no,titlebar=no,toolbar=no,directories=no');return false;"
                                               class="a1"><? echo strlen($row["ddh"]) > 6 ? $row["ddh"] : ""; ?></a>
                                        </td>
                                        <td datatype="<? echo $row['id']; ?>">
                                            <? if($row['zy'] <>'订单结算' && $row['zy'] <>'订单退款' && $row['zy'] <>'订单订金'){ ?>
                                            <a class="delskd" href="javascript:void(0);">删除</a>
                                            <? } ?>
                                        </td>
                                    </tr>

                                <? } ?>
                                <tr style="height:30px;">
                                    <td colspan='5'>合计</td>
                                    <td>借方统计：<? echo $t_jf; ?></td>
                                    <td>贷方结算统计：<? echo $t_jsdf; ?> <?// echo $t_djdf; ?></td>
                                    <td ><? ?></td>
                                    <? if (!$_GET["bt2"]) { ?>
                                        <td></td> <? } ?>
                                    <td></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <br>

                    </div>
            </td>
        </tr>
        </tbody>
    </table>
</form>

<script>

    $('.zy').on('click',function(){

        var id = $(this).closest('td').attr('datatype');
        var zy=$(this).html();
        var _this = $(this);

        if (zy == "订单结算" || zy == "订单订金" || zy == '订单退款'){

            return;
        }
        var newzy = prompt("填写新摘要:" , zy);
        if(newzy && newzy != zy){

            $.ajax({
                type: "GET",
                url: "editzy.php?id=" + id + "&zy=" + newzy,
                async: false,
                success: function (data) {
                    if (data == '1')
                        _this.html(newzy);
                    else if (data == '0')
                        alert("error. plz retry...");
                },
                error: function () {
                    alert("error. plz retry.");
                }

            });
        }
    });
    $('.memo').on('click',function(){

        var id = $(this).closest('td').attr('datatype');
        var memo=$(this).html();
        var _this = $(this);

        var newmemo = prompt("填写新备注:" , memo);
        if(newmemo && newmemo != memo){

            $.ajax({
                type: "GET",
                url: "editzy.php?id=" + id + "&memo=" + newmemo,
                async: false,
                success: function (data) {
                    if (data == '1')
                        _this.html(newmemo);
                    else if (data == '0')
                        alert("error. plz retry...");
                },
                error: function () {
                    alert("error. plz retry.");
                }

            });
        }
    });
    $('.delskd').on('click', function () {

        var id = $(this).closest('td').attr('datatype');
        var _this = $(this);

        var suredo = confirm('确定删除该款项？');

        if(suredo){
            $.ajax({
                type: "GET",
                url: "editzy.php?id=" + id + "&delskd=1",
                async: false,
                success: function (data) {
                    if (data == '1')
                        _this.closest('tr').remove();
                    else if (data == '0')
                        alert("error. plz retry...");
                },
                error: function () {
                    alert("error. plz retry.");
                }

            });
        }
    });
</script>
<?
//paging
$param = 'rq1='.$d1.'&rq2='.$d2.'&fkhmc='.$d3.'&ddh='.$_GET['ddh'].'&skid='.$_GET['skid'].'&sklx='.$sklx.'&skfs='.$skfs.'&jfjemin='.$_GET['jfjemin'].'&jfjemax='.$_GET['jfjemax'].'&dfjemin='.$_GET['dfjemin'].'&dfjemax='.$_GET['dfjemax'].'&zhaiyao='.$_GET['zhaiyao'];

include '../commonfile/paging_show.php';
?>
</body>
</html>
</form>
