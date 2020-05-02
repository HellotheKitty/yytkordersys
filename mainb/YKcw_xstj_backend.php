<?
session_start();
require("../inc/conn.php");
?>
<? $tss="全部信息";

$d1=$_POST["rq1"];if ($d1=="") {$d1=date("Y-m-")."01";$ss="";$tss="全部信息";}
$d2=$_POST["rq2"];if ($d2=="") {$d2=date("Y-m-d");}
if ($_GET["dq"]<>"") $dq=urldecode($_GET["dq"]); else $dq="%";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title>名片工坊-账户使用情况</title>
    <link href="../css/CITICcss.css" rel="stylesheet" type="text/css">
    <script src="../js/WdatePicker.js" type="text/javascript" language="javascript"></script>
    <base target="_self" />
</head>

<body style="overflow-x:hidden;overflow-y:auto">
<Div style="width:900px; margin:0px auto;"><Div id=Calendar scrolling="no" style="border:0px solid #EEEEEE ;position: absolute; margin-top:150px; margin-left: 5px; width: 150; height: 137; z-index: 200; filter :\'progid:DXImageTransform.Microsoft.Shadow(direction=135,color=#AAAAAA,strength=4)\' ;display: none"></Div></Div>
<form name="form1" method="post" action="YKcw_xstj.php?dq=<? echo $_GET["dq"]?>" id="form1">


    <div style="width:900px">

        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:0px;">
            <tbody><tr>
                <td valign="top">
                    <div style="padding:5px 32px 22px 55px; color:#58595B">
                        <div style="padding-bottom:10px; font-weight:bold;"></div>
                        <div>开始日期：
                            <input onClick="WdatePicker();" maxlength="50" class="Wdate" style="width: 100px;" type="text" name="rq1" id="rq1" value="<? echo $d1;?>" />
                            &nbsp;&nbsp;&nbsp;&nbsp;结束日期：<input onClick="WdatePicker();" maxlength="50" class="Wdate" style="width: 100px;" type="text" name="rq2" id="rq2" value="<? echo $d2;?>" />&nbsp;
                            <input name="bt1" type="submit" value="查 询" />

                            <br>

                            销售数据：
                            <? $rs=mysql_query("select b_ry.bh,b_ry.xm,sum(dje+ifnull(kdje,0)),group_concat(m.ddh) ddh,count(ddh) ddsl from b_ry,order_mainqt m where b_ry.dwdm='".$_SESSION["GDWDM"]."' and m.xsbh=b_ry.bh and ddate>='$d1 00:00:00' and ddate<='$d2 23:59:59' and m.state<>'新建订单' group by m.xsbh  order by m.xsbh",$conn);

                            ?>
                            <table cellspacing="0" cellpadding="0" rules="all" border="0" id="gvOrder" style="border-width:0px;width:100%;border-collapse:collapse;border:1px solid #D3D3D3; border-bottom:0px;">
                                <tbody><tr class="td_title" style="height:30px;">
                                    <th rowspan="2" align="center" scope="col">销售编号</th>
                                    <th rowspan="2" align="center" scope="col">姓名</th>
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
                                        <td align="center" class="td_content" style="width:77px;"><? echo mysql_result($rs,$i,1);?></td>
                                        <td align="center" class="td_content" style="width:77px;"><span class="td_content" style="width:77px;"><? echo mysql_result($rs,$i,"ddsl");?></span></td>
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
                                    <td colspan="2" align="center" class="td_content" style="width:77px;">合计：</td>
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
