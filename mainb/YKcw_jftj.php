<?
session_start();
require("../inc/conn.php");
?>
<? $tss="全部信息";

include '../commonfile/calc_area_ry.php';

$d1=$_POST["rq1"];if ($d1=="") {$d1=date("Y-m-")."01";$ss="";$tss="全部信息";}
$d2=$_POST["rq2"];if ($d2=="") {$d2=date("Y-m-d");}
if ($_GET["dq"]<>"") $dq=urldecode($_GET["dq"]); else $dq="%";

if ($_POST["bt2"]) {
    header("Content-type:application/vnd.ms-excel;charset=UTF-8");
    header("Content-Disposition:filename=" . iconv("utf-8", "gb2312", "机房操作员订单统计导出[" . $d1 . "-" . $d2 . "].xls"));
    header("Expires:0");
    header('Pragma:   public');
    header("Cache-control:must-revalidate,post-check=0,pre-check=0");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title>名片工坊-账户使用情况</title>
    <link href="../css/CITICcss.css" rel="stylesheet" type="text/css">
    <script src="../js/WdatePicker.js" type="text/javascript" language="javascript"></script>
    <script type="text/javascript" src="../js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="../js/layer/layer.js"></script>
    <base target="_self" />
    <style type="text/css">
        .ptb td{
            text-align: center;
        }
        .detail{
            display: inline-block;
            color: darkgreen;
            font-weight: bold;
            font-size: 14px;
            margin: 5px 20px;
            text-decoration: underline;
        }
    </style>
</head>

<body style="overflow-x:hidden;overflow-y:auto">
<Div style="width:900px; margin:0px auto;"><Div id=Calendar scrolling="no" style="border:0px solid #EEEEEE ;position: absolute; margin-top:150px; margin-left: 5px; width: 150; height: 137; z-index: 200; filter :\'progid:DXImageTransform.Microsoft.Shadow(direction=135,color=#AAAAAA,strength=4)\' ;display: none"></Div></Div>
<form name="form1" method="post" action="YKcw_jftj.php?dq=<? echo $_GET["dq"]?>" id="form1">


    <div style="width:900px">

        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:0px;">
            <tbody><tr>
                <td valign="top">
                    <div style="padding:5px 32px 22px 55px; color:#58595B">
                        <div style="padding-bottom:10px; font-weight:bold;"></div>
                        <div>开始日期：
                            <input onClick="WdatePicker();" maxlength="50" class="Wdate" style="width: 100px;" type="text" name="rq1" id="rq1" value="<? echo $d1;?>" />
                            &nbsp;&nbsp;&nbsp;&nbsp;结束日期：<input onClick="WdatePicker();" maxlength="50" class="Wdate" style="width: 100px;" type="text" name="rq2" id="rq2" value="<? echo $d2;?>" />&nbsp;

                            <? include '../commonfile/calc_options.php'; ?>

                            <input name="bt1" type="submit" value="查 询" />
                            <input name="bt2" type="submit" value="导 出"/>
                            <a class="detail" href="excel_machine.php" target="_blank" >机器打印数量 >></a>
                            <br><br>

                            <?
//                            $sql = "select count(DISTINCT m.ddh) AS ddsl,r.xm,r.bh,sum(x.jg1 * x.pnum1 * x.sl1 + x.jg2 * x.pnum2 * x.sl2) as dyje from b_ry r, order_mxqt x left join order_mainqt m on m.ddh = x.ddh where r.dwdm in $dwdmStr and r.qx='sc' and locate(r.bh , m.pczy)>0 and pendtime>='$d1 00:00:00' and pendtime<='$d2 23:59:59' and m.state<>'作废订单' group by r.xm order by m.pendtime";
                            $sql = "select count(DISTINCT x.ddh) AS ddsl,r.xm,r.bh,sum(x.jg1 * x.pnum1 * x.sl1 + x.jg2 * x.pnum2 * x.sl2) as dyje from b_ry r ,order_mxqt x WHERE  r.dwdm in $dwdmStr and r.qx='sc' and (locate(r.bh , x.operator1)>0 or locate(r.bh , x.operator2)>0) AND x.sdate>='$d1 00:00:00' AND x.sdate<='$d2 23:59:59' group by r.xm order by x.sdate";
                            
                            $rs = mysql_query($sql,$conn);
                            ?>
                            <table id="czytb" class="ptb" cellspacing="0" cellpadding="0" rules="all" border="0" id="gvOrder" style="border-width:0px;width:100%;border-collapse:collapse;border:1px solid #D3D3D3; border-bottom:0px;">
                                <thead>
                                <tr class="td_title" style="height:30px;">
                                    <th  align="center" scope="col">操作员</th>
                                    <th  align="center" scope="col">订单数</th>
                                    <th  align="center" scope="col">打印总金额</th>
                                    <th  align="center" scope="col">查看详情</th>
                                </tr>
                                </thead>
                                <tbody>
                                <? for($i=0;$i<mysql_num_rows($rs);$i++ ){ ?>
                                <tr style="height:30px;">
                                    <td ><? echo mysql_result($rs , $i ,'xm') ?></td>
                                    <td><? echo mysql_result($rs , $i ,'ddsl') ?></td>
                                    <td><? echo mysql_result($rs , $i ,'dyje') ?></td>
                                    <td><a href="javascript:void(0)" onclick="javascript:window.open('YKcw_jfczydetail.php?id=<? echo mysql_result($rs ,$i,'bh') ?>&from=list&rq1=<? echo $d1; ?>&rq2=<? echo $d2; ?>','OrderDetail','height=600px,width=920px,top=100px,left=200px,location=no,menubar=no,resizable=no,scrollbars=yes,status=no,titlebar=no,toolbar=no,directories=no');return false;"  class="a1 czydetail">详情</a>
                                    </td>
                                </tr>

                                <? } ?>
                                </tbody>
                            </table>
                            <br>
                        </div>
                    </div>
                </td>
            </tr>
            </tbody></table>

    </div>

    </div>
</form>
<script type="text/javascript">
    $('#czytb').on('click','.czydetail', function () {
        _czyid = $(this).attr('data-type');


    })
</script>
</body></html>
