<?
session_start();
require("../inc/conn.php");
?>
<? $tss="全部信息";

$d1=$_POST["rq1"];if ($d1=="") {$d1=date("Y-m-")."01";$ss="";$tss="全部信息";}
$d2=$_POST["rq2"];if ($d2=="") {$d2=date("Y-m-d");}
$khmc=$_POST["xstjkhmc"];if ($khmc!="") $tj = " and base_kh.khmc like '%$khmc%' "; else $tj="";
$pxfs = $_POST["pxfs"];switch($pxfs){
    case 'ddje':
        $order = " order by tdje desc";
        break;
    case 'ddsl':
        $order = " order by ddsl desc";
        break;
    default:
        $order = " order by tdje desc";
}
if ($_GET["dq"]<>"") $dq=urldecode($_GET["dq"]); else $dq="%";

if($_POST["bt2"]) {
    header("Content-type:application/vnd.ms-excel;charset=UTF-8");
    header("Content-Disposition:filename=".iconv("utf-8","gb2312","客户消费导出[".$d1."-".$d2."].xls"));
    header("Expires:0");
    header('Pragma:   public'   );
    header("Cache-control:must-revalidate,post-check=0,pre-check=0");
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title>名片工坊-账户使用情况</title>
    <link href="../css/CITICcss.css" rel="stylesheet" type="text/css">
    <script src="../js/WdatePicker.js" type="text/javascript" language="javascript"></script>
    <base target="_self" />
</head>


<Div style="width:900px; margin:0px auto;"><Div id=Calendar scrolling="no" style="border:0px solid #EEEEEE ;position: absolute; margin-top:150px; margin-left: 5px; width: 150; height: 137; z-index: 200; filter :\'progid:DXImageTransform.Microsoft.Shadow(direction=135,color=#AAAAAA,strength=4)\' ;display: none"></Div></Div>
<form name="form1" method="post" action="YKcw_xstj2.php?dq=<? echo $_GET["dq"]?>" id="form1">


    <div style="width:90%">

        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:0px;">
            <tbody><tr>
                <td valign="top">
                    <div style="padding:5px 32px 22px 55px; color:#58595B">
                        <div style="padding-bottom:10px; font-weight:bold;"></div>
                        <div>开始日期：
                            <input onClick="WdatePicker();" maxlength="50" class="Wdate" style="width: 100px;" type="text" name="rq1" id="rq1" value="<? echo $d1;?>" />
                            &nbsp;&nbsp;&nbsp;&nbsp;结束日期：<input onClick="WdatePicker();" maxlength="50" class="Wdate" style="width: 100px;" type="text" name="rq2" id="rq2" value="<? echo $d2;?>" />&nbsp;
                            客户名称：<input type="text" name="xstjkhmc" value="<? echo $khmc; ?>" />&nbsp;&nbsp;&nbsp;按<select name="pxfs"><option value="ddje">订单金额</option><option value="ddsl" <? if ($pxfs=='ddsl') echo "selected"?>>订单数量</option></select>递减排序&nbsp;&nbsp;&nbsp;<input name="bt1" type="submit" value="查 询" />　　<input type="submit" name="bt2" value="导 出" />

                            <br>

                            销售数据：
                            <? $rs=mysql_query("select base_kh.mpzh,base_kh.khmc,sum(dje+ifnull(kdje,0)) tdje,group_concat(m.ddh) ddh,count(ddh) ddsl,base_kh.lxr,base_kh.lxdh,base_kh.lxdz,base_kh.xsbh from base_kh,order_mainqt m where base_kh.gdzk='".substr($_SESSION["GDWDM"],0,4)."' $tj and m.khmc=base_kh.khmc and ddate>='$d1 00:00:00' and ddate<='$d2 23:59:59' and m.state<>'新建订单' group by m.khmc  $order",$conn);
                            ?>
                            <body style="overflow-x:hidden;overflow-y:auto">
                            <table cellspacing="0" cellpadding="0" rules="all" id="gvOrder" style="border-width:0px;width:100%;border-collapse:collapse;border:1px solid #D3D3D3; border-bottom:0px;">
                                <tbody><tr class="td_title" style="height:30px;">
                                    <th rowspan="2" align="center" scope="col">客户编号</th>
                                    <th rowspan="2" align="center" scope="col">客户名称</th>
                                    <th rowspan="2" align="center" scope="col">联系人</th>
                                    <th rowspan="2" align="center" scope="col">联系电话</th>
                                    <th rowspan="2" align="center" scope="col">地址</th>
                                    <th rowspan="2" align="center" scope="col">销售编号</th>
                                    <th rowspan="2" align="center" scope="col">订单数量</th>
                                    <th rowspan="2" align="center" scope="col">订单金额</th>
                                    <th colspan="6" align="center" scope="col">收款金额</th>
                                </tr>
                                <tr class="td_title" style="height:30px;">
                                    <th align="center" scope="col">现金</th>
                                    <th align="center" scope="col">支票</th>
                                    <th align="center" scope="col">POS机招行</th>
                                    <th align="center" scope="col">汇款</th>
                                    <th align="center" scope="col">预存扣款</th>
                                    <th align="center" scope="col">小计</th>
                                </tr>
                                <? $dje=0;$xj=0;$xje=0;$zp=0;$pos=0;$hk=0;$yc=0;$zj=0;$ddsl=0;
                                for($i=0;$i<mysql_num_rows($rs);$i++){
                                    $xj=0;
                                    ?>
                                    <tr>
                                        <td align="center" class="td_content" style="width:77px;"><? echo mysql_result($rs,$i,0);?></td>
                                        <td  class="td_content" style="width:157px;text-align:left"><? echo mysql_result($rs,$i,1);?></td>
                                        <td align="center" class="td_content" style="width:77px;"><? echo mysql_result($rs,$i,"lxr");?></td>
                                        <td align="center" class="td_content" style="width:77px;"><? echo mysql_result($rs,$i,"lxdh");?></td>
                                        <td align="center" class="td_content" style="width:107px;"><? echo mysql_result($rs,$i,"lxdz");?></td>
                                        <td align="center" class="td_content" style="width:57px;"><? echo mysql_result($rs,$i,"xsbh");?></td>
                                        <td align="center" class="td_content" style="width:77px;"><? echo mysql_result($rs,$i,"ddsl");?></td>
                                        <td align="center" class="td_content" style="width:80px;"><? if (mysql_result($rs,$i,2)<>0) echo mysql_result($rs,$i,2); else echo "&nbsp;";?></td>
                                        <td class="td_content" align="center" style="width:90px;">
                                            <? $rs2=mysql_query("select sum(df) from order_zh where instr('".mysql_result($rs,$i,"ddh")."',ddh)>0 and ddh<>'' and xsbh='现金'");
                                            if (mysql_result($rs2,0,0)<>0) {echo mysql_result($rs2,0,0);$xje+=mysql_result($rs2,0,0);$xj+=mysql_result($rs2,0,0);} else echo "&nbsp;";?></td>
                                        <td align="center" class="td_content" style="width:80px;"><? $rs2=mysql_query("select sum(df) from order_zh where instr('".mysql_result($rs,$i,"ddh")."',ddh)>0 and xsbh='支票'");
                                            if (mysql_result($rs2,0,0)<>0) {echo mysql_result($rs2,0,0);$zp+=mysql_result($rs2,0,0);$xj+=mysql_result($rs2,0,0);} else echo "&nbsp;";?></td>
                                        <td align="center" class="td_content" style="width:80px;"><? $rs2=mysql_query("select sum(df) from order_zh where instr('".mysql_result($rs,$i,"ddh")."',ddh)>0 and xsbh='POS机招行'");
                                            if (mysql_result($rs2,0,0)<>0) {echo mysql_result($rs2,0,0);$pos+=mysql_result($rs2,0,0);$xj+=mysql_result($rs2,0,0);} else echo "&nbsp;";?></td>
                                        <td align="center" class="td_content" style="width:80px;"><? $rs2=mysql_query("select sum(df) from order_zh where instr('".mysql_result($rs,$i,"ddh")."',ddh)>0 and xsbh='汇款'");
                                            if (mysql_result($rs2,0,0)<>0) {echo mysql_result($rs2,0,0);$hk+=mysql_result($rs2,0,0);$xj+=mysql_result($rs2,0,0);} else echo "&nbsp;";?></td>
                                        <td align="center" class="td_content" style="width:80px;"><? $rs2=mysql_query("select sum(df) from order_zh where instr('".mysql_result($rs,$i,"ddh")."',ddh)>0 and xsbh='预存扣款'");
                                            if (mysql_result($rs2,0,0)<>0) {echo mysql_result($rs2,0,0);$yc+=mysql_result($rs2,0,0);$xj+=mysql_result($rs2,0,0);} else echo "&nbsp;";?></td>
                                        <td align="center" class="td_content" style="width:80px;"><? echo $xj;?></td>
                                    </tr>
                                    <?

                                    $dje+=mysql_result($rs,$i,2);$zj+=$xj;$ddsl+=mysql_result($rs,$i,"ddsl");
                                }
                                ?>
                                <tr>
                                    <td colspan="6" align="center" class="td_content" style="width:77px;">合计：</td>
                                    <td align="center" class="td_content" style="width:77px;"><? echo $ddsl;?></td>
                                    <td class="td_content" align="center" style="width:80px;"><? echo $dje;?><br></td>
                                    <td align="center" class="td_content" style="width:90px;"><? echo $xje;?></td>
                                    <td align="center" class="td_content" style="width:90px;"><? echo $zp;?></td>
                                    <td align="center" class="td_content" style="width:90px;"><? echo $pos;?></td>
                                    <td align="center" class="td_content" style="width:90px;"><? echo $hk;?></td>
                                    <td align="center" class="td_content" style="width:90px;"><? echo $yc;?></td>
                                    <td align="center" class="td_content" style="width:90px;"><? echo $zj;?></td>
                                </tr>
                                </tbody></table>


                            <br>
                        </div>
                    </div>
                </td>
            </tr>
            </tbody></table>

    </div>

    </div>
</form>

</body></html>
