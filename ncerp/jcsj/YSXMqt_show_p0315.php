<? require("../../inc/conn.php");
?>
<? session_start();
//if ($_SESSION["OK"]<>"OK")
if($_SESSION["YKUSERNAME"]=="") {
    echo "<script language=JavaScript>{window.location.href='../../error.php';}</script>";
    exit;
}?>
<?
$dwdm = substr($_SESSION["GDWDM"],0,4);
if ($_GET["jg"]<>"") {

    if (urldecode($_GET["jg"])=="打印完成"){

        mysql_query("update order_mainqt set pczy='".$_SESSION["YKOAUSER"]."',pendtime=now() where ddh='".$_GET["ddh"]."'",$conn);
        $_hd = mysql_query("select hd.id from order_mxqt_hd as hd inner join order_mxqt as mx on hd.mxid=mx.id and mx.ddh='".$_GET["ddh"]."'",$conn);

        $_fm = mysql_query("select fm.id from order_mxqt_fm as fm inner join order_mxqt as mx on fm.mxid=mx.id and mx.ddh='".$_GET["ddh"]."'",$conn);

        if((!$_hd || mysql_num_rows($_hd)==0) && (!$_fm || mysql_num_rows($_fm)==0) ){
            mysql_query("update order_mainqt set state='待结算',sdate=now() where ddh='" . $_GET["ddh"] . "'", $conn);
            //生产完成转入前台收款打印配送单
            //$info = json_decode(file_get_contents("http://oa.skyprint.cn/mainb/Getkfry.php?tasktype=15&user="));
            //$zzry=$info->kfry;
            //mysql_query("INSERT INTO task_list (taskcreatetime, tasktype, fromuser, fromorder, taskmemo, taskstate,statetime,taskrecver,taskrecvtime,taskdescribe,taskfile1,taskfile2,taskparam,srcid) select now(),'15',khmc,'',concat('".$_SESSION["YKUSERNAME"]."创建的工单,订单号：',ddh),'排队中',now(),'$zzry',now(),'请结算并打印配送单','','','gongdan',1 from order_mainqt where  ddh='".$_GET["ddh"]."'", $conn);
        }
    }
    else {

        mysql_query("update order_mainqt set state='待结算',sdate=now(),hdczy='".$_SESSION["YKOAUSER"]."',hdendtime=now() where ddh='".$_GET["ddh"]."'",$conn);
        //生产完成转入前台收款打印配送单
        //$info = json_decode(file_get_contents("http://oa.skyprint.cn/mainb/Getkfry.php?tasktype=15&user="));
        //$zzry=$info->kfry;
        //mysql_query("INSERT INTO task_list (taskcreatetime, tasktype, fromuser, fromorder, taskmemo, taskstate,statetime,taskrecver,taskrecvtime,taskdescribe,taskfile1,taskfile2,taskparam,srcid) select now(),'15',khmc,'',concat('".$_SESSION["YKUSERNAME"]."创建的工单,订单号：',ddh),'排队中',now(),'$zzry',now(),'请结算并打印配送单','','','gongdan',1 from order_mainqt where  ddh='".$_GET["ddh"]."'", $conn);

    }
    echo "<script>window.location.href='../MYOrderShowns.php';</script>";
    exit;
}

if($_GET['ddh']==''){
    exit();
}

$rs=mysql_query("select order_mainqt.*,lxr,lxdh,b_ry.xm from order_mainqt left join base_kh on order_mainqt.khmc=base_kh.khmc left join b_ry on order_mainqt.xsbh=b_ry.bh where ddh='".$_GET["ddh"]."'",$conn);

if(mysql_num_rows($rs)<=0){
    exit();
}
$xjzje=mysql_result($rs,0,"dje");
$state=mysql_result($rs,0,"state");
//$rsmx=mysql_query("(select order_mxqt.*,m1.materialname mm1,m1.specs ms1,m2.materialname mm2,m2.specs ms2 from order_mxqt,material m1,material m2 where ddh='".$_GET["ddh"]."' and m1.materialcode=paper1 and m2.materialcode=paper2 order by order_mxqt.id) union (select order_mxqt.*,m1.materialname mm1,m1.specs ms1,m2.materialname mm2,m2.specs ms2 from order_mxqt,material1 m1,material1 m2 where ddh='".$_GET["ddh"]."' and m1.materialcode=paper1 and m2.materialcode=paper2 order by order_mxqt.id )",$conn);
//$rsmx=mysql_query("select order_mxqt.*,m1.materialname mm1,m1.specs ms1,m2.materialname mm2,m2.specs ms2 from order_mxqt,material m1,material m2 where ddh='".$_GET["ddh"]."' and m1.materialcode=paper1 and m2.materialcode=paper2 order by order_mxqt.id",$conn);
$rsmx=mysql_query("select order_mxqt.*,m1.materialname mm1,m1.specs ms1,m2.materialname mm2,m2.specs ms2 from order_mxqt LEFT JOIN material m1 on m1.materialcode=paper1 LEFT JOIN material m2 on m2.materialcode=paper2 where ddh='".$_GET["ddh"]."' order by order_mxqt.id",$conn);

?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>订单信息</title><!--
<script language="JavaScript" src="../htgl/Mymodify.js"></script>
<SCRIPT language=JavaScript src="../form.js"></SCRIPT>
-->
    <script src="../../js/jquery-1.8.3.min.js" language="JavaScript"></script>
    <style type="text/css">
        <!--

        .style11 {font-size: 14px}
        .STYLE13 {font-size: 12px}
        .STYLE10 {font-size: 8px}
        .STYLE14 {font-size: 20px; font-weight:bold}
        -->
    </style>
</head>

<body>
<? if ($_GET["lx"]<>"show") {?>
    <span class="STYLE13">此生产单请妥善保管，生产完成交付管理人员留存。<br><? echo date("Y-m-d");?></span>
    <P><img src='getean.php?size=40&text=<? echo mysql_result($rs,0,"ddh");?>&<? echo rand(10,1000)?>'>
    </P>
<? } else {
    if (mysql_result($rs,0,"state")=="进入生产" ) {?>


            <input type="button" style="margin-left:20px; height:45px;" value="<? echo mysql_result($rs,0,"pczy")==""?"打印完成":"后加工完成" ?>" onClick="javascript:window.location.href='?ddh=<? echo mysql_result($rs,0,"ddh")?>&jg=<? echo urlencode(mysql_result($rs,0,"pczy")==""?"打印完成":"后加工完成")?>'" />



    <? } else echo "打印已完成"; } if(mysql_result($rs,0,"pczy")==""  && $_GET["lx"]=="show"){?>
    <input type="button" style="margin-right:20px;height:45px;float:right;" value="打印生产单" onClick="window.open('YSXMqt_show_p.php?ddh=<?echo mysql_result($rs,0,"ddh")?>')" />
<?}?>

<div align="center"><span class="STYLE14"><strong>印艺天空生产单</strong></span></div>
<br>
<table width="100%"  cellspacing="0" cellpadding="0" border="1"   bordercolor="#111" style="border-collapse:collapse;font-size:12px" align="center" >
    <tr>
        <td height="34" class="STYLE11" width="94" align="center">订单编号</td>
        <td width="120" class="STYLE13">
            SKY<? echo mysql_result($rs,0,"ddh");?>
        </td>
        <td width="180" align="left" class="STYLE11">下单时间：<span class="STYLE10"><? echo mysql_result($rs,0,"ddate");?></span></td>
        <td width="180" align="left" class="STYLE11">要求完成：<span class="STYLE10"><? echo mysql_result($rs,0,"yqwctime");?></span></td>
    </tr>
    <tr>
        <td  height="34" class="STYLE11" align="center">客户名称</td>
        <td colspan="2" class="STYLE13"><? echo mysql_result($rs,0,"khmc"),'<span id="lxr">　　联系人：',mysql_result($rs,0,"lxr"),'/',mysql_result($rs,0,"lxdh"),'</span>';?></td>
        <td class="STYLE13">定金金额：<? echo mysql_result($rs,0,"djje");?>元</td>
    </tr>
    <tr>
        <td height="34" class="STYLE11" align="center">配送信息</td>
        <td colspan="3" class="STYLE13" id="peisong"><? echo "配送：",mysql_result($rs,0,"psfs"),"&nbsp;&nbsp;收货人：",mysql_result($rs,0,"shr"),"&nbsp;&nbsp;电话：",mysql_result($rs,0,"shdh"),"<br>地址：",mysql_result($rs,0,"shdz");?></td>
    </tr>

    <tr>
        <td height="24" class="STYLE11" align="center"><!--订单备注-->客户要求</td>
        <td colspan="3" class="STYLE13"><? echo mysql_result($rs,0,"memo");?></td>
    </tr>
</table>
<br>
<table cellspacing="0" cellpadding="0" width="100%" border="1"   bordercolor="#111" style="border-collapse:collapse;font-size:12px">
    <tbody>
    <tr class="td_title" style="height:30px; font-weight:bold">
        <!--<th width="10%"  align="center" scope="col">印件名称</th>-->
        <th width="30"  align="center" scope="col">印件名称</th>
        <th width="30"  align="center" scope="col">构件</th>
        <th width="10%"  align="center" scope="col">机器/颜色</th>
        <th width="10%" align="center" scope="col">文件名</th>
        <th width="25%"  align="center" scope="col">纸张</th>
        <th width="5%"  align="center" scope="col">单位</th>
        <th width="5%" align="center" scope="col">单双</th>
        <th width="5%"  align="center" scope="col">横纵</th>
        <th width="5%"  align="center" scope="col">P数</th>
        <th width="5%"  align="center" scope="col">份数</th>
        <th width="5%"  align="center" scope="col">总数</th>
    </tr>

    <?
    $_mxidStr = "(";
    for($i=0;$i<mysql_num_rows($rsmx);$i++){    $_mxidStr .= mysql_result($rsmx,$i,"id").",";?>
        <tr class="td_title" style="height:30px;">
            <td rowspan="<? if (mysql_result($rsmx,$i,"n2")<>"") echo "2"; else echo "1";?>" align="center" class="td_content" ><? echo mysql_result($rsmx,$i,"pname");?></td>
            <td align="center" class="td_content" ><? echo mysql_result($rsmx,$i,"n1");?></td>
            <td class="td_content" align="center" ><? echo mysql_result($rsmx,$i,"machine1");?></td>
            <td align="center" class="td_content" ><?
                $aaa=array_unique(explode(";",mysql_result($rsmx,$i,"file1")));
                foreach ($aaa as $key=>$a1)
                    if ($a1<>"") {
                        //echo "<a href='{$localftp}/dfile.php?dfile={$a1}' target='_blank'>{$a1}</a>  ";
                        if(stristr($a1,'http')){
                            $a1arr = explode('-',$a1);
//                            $a1= '拼版号:'.$a1arr[1];
                            $a1 = '';
                        }
                        echo $a1;

                        // 拷贝文件的脚本在本地
                        //echo $localftp."/scfiles/".$a1;
                        //if ($_GET["getin"]=="ok" and mysql_result($rs,0,"xjdid")<>-1) {
                        //	@$ss=file_get_contents("{$localftp}/filecopy.php?fn={$a1}");
                        //}
                    }
                if(!empty(mysql_result($rsmx,$i,"jdf1"))){
                    echo '<br>';
                    echo mysql_result($rsmx,$i,"jdf1");
                }
                if(!empty(mysql_result($rsmx,$i,"sczzbh1"))){
                    echo '<br>';
                    echo '纸张编号:'.substr(mysql_result($rsmx,$i,"sczzbh1"),0,3);
                }
                ?>

            </td>
            <td align="center" class="td_content" ><? echo mysql_result($rsmx,$i,"mm1"),'[',mysql_result($rsmx,$i,"ms1"),']';?></td>
            <td class="td_content" align="center" ><? echo mysql_result($rsmx,$i,"jldw1");?></td>
            <td class="td_content" align="center"><? echo mysql_result($rsmx,$i,"dsm1");?></td>
            <td class="td_content" align="center"><? echo mysql_result($rsmx,$i,"hzx1");?></td>
            <td class="td_content" align="center" ><? echo mysql_result($rsmx,$i,"pnum1");?></td>
            <td align="center" class="td_content"><? echo mysql_result($rsmx,$i,"sl1");?></td>
            <td align="center" class="td_content"><? echo mysql_result($rsmx,$i,"pnum1")*mysql_result($rsmx,$i,"sl1");?></td>
        </tr>
        <? if (mysql_result($rsmx,$i,"n2")<>"") {?>
            <tr class="td_title" style="height:30px;">
                <td align="center" class="td_content" ><? echo mysql_result($rsmx,$i,"n2");?></td>
                <td class="td_content" align="center" ><? echo mysql_result($rsmx,$i,"machine2");?></td>
                <td align="center" class="td_content" ><?
                    $aaa=array_unique(explode(";",mysql_result($rsmx,$i,"file2")));
                    foreach ($aaa as $key=>$a1)
                        if ($a1<>"") {
                            //echo "<a href='{$localftp}/dfile.php?dfile={$a1}' target='_blank'>{$a1}</a>  ";

//                            if(stristr($a1,'http')){
//                                $a1arr = explode('-',$a1);
//                                $a1= '拼版号:'.$a1arr[1];
//                            }

                            echo $a1;
                            //if ($_GET["getin"]=="ok" and mysql_result($rs,0,"xjdid")<>-1) {
                            //	@$ss=file_get_contents("{$localftp}/filecopy.php?fn={$a1}");
                            //}
                        }

                    if(!empty(mysql_result($rsmx,$i,"jdf2"))){
                        echo '<br>';
                        echo mysql_result($rsmx,$i,"jdf2");
                    }

                    if(!empty(mysql_result($rsmx,$i,"sczzbh2"))){
                        echo '<br>';
                        echo '纸张编号:'.substr(mysql_result($rsmx,$i,"sczzbh2"),0,3);
                    }
                    ?> </td>
                <td align="center" class="td_content" ><? echo mysql_result($rsmx,$i,"mm2"),'[',mysql_result($rsmx,$i,"ms2"),']';?></td>
                <td class="td_content" align="center" ><? echo mysql_result($rsmx,$i,"jldw2");?></td>
                <td class="td_content" align="center"><? echo mysql_result($rsmx,$i,"dsm2");?></td>
                <td class="td_content" align="center"><? echo mysql_result($rsmx,$i,"hzx2");?></td>
                <td class="td_content" align="center" ><? echo mysql_result($rsmx,$i,"pnum2");?></td>
                <td align="center" class="td_content"><? echo mysql_result($rsmx,$i,"sl2");?></td>
                <td align="center" class="td_content"><? echo mysql_result($rsmx,$i,"pnum2")*mysql_result($rsmx,$i,"sl2");?></td>
            </tr>
        <? }?>
    <? }?>
    </tbody></table><br>
<? $_mxidStr = substr($_mxidStr,0,-1);$_mxidStr .= ")";
$_hdrs = mysql_query("select * from order_mxqt_hd where mxid in $_mxidStr order by id asc",$conn);
if($_hdrs && mysql_num_rows($_hdrs)>0) {

    ?>

    <table cellspacing="0" cellpadding="0" width="100%" border="1"   bordercolor="#111" style="border-collapse:collapse;font-size:12px">
        <tbody>
        <tr class="td_title" style="height:30px;">

            <th width="90"  align="center" scope="col">后加工方式</th>
            <th width="90"  align="center" scope="col">成品尺寸</th>
            <th width="50"  align="center" scope="col">单位</th>
            <th width="50"  align="center" scope="col">数量</th>

            <th   align="center" scope="col">备注</th>
        </tr>
        <?
        while($_arr = mysql_fetch_assoc($_hdrs)) {?>
            <tr class="td_title" style="height:30px;">

                <td class="td_content" align="center" ><? echo $_arr["jgfs"];?></td>
                <td align="center" class="td_content" ><? echo $_arr["cpcc"];?></td>
                <td class="td_content" align="center" ><? echo $_arr["jldw"];?></td>
                <td class="td_content" align="center" ><? echo $_arr["sl"];?></td>

                <td class="td_content" align="center" ><? echo $_arr["memo"];?></td>
            </tr>
        <?}?>
        </tbody></table>
<? }?>

<? $_mxidStr = substr($_mxidStr,0,-1);$_mxidStr .= ")";
$_fmrs = mysql_query("select * from order_mxqt_fm where mxid in $_mxidStr order by id asc",$conn);
if($_fmrs && mysql_num_rows($_fmrs)>0) {

?>

<br><table cellspacing="0" cellpadding="0" bordercolor="#111" border="1" width="100%" style="border-collapse:collapse;font-size:12px">
    <thead>
    <tr class="td_title" style="height:30px;">

        <th width="90"  align="center" scope="col">覆膜方式</th>
        <th width="90"  align="center" scope="col">成品尺寸</th>
        <th width="50"  align="center" scope="col">单位</th>
        <th width="50"  align="center" scope="col">数量</th>
        <th   align="center" scope="col">备注</th>
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

            <td class="td_content" align="center" ><? echo $_arr["memo"];?></td>
        </tr>
    <?}?>
    </tbody>
</table>
<? }?>

<? if ($_GET["lx"]<>"show") {?>

    <p>
        <B>制单人：<? echo mysql_result($rs,0,"xm");?>　　　机房人员：　　　　　　　装订人员：</B>




    </p><!--<br>--><br> <B>生产情况备注：<?echo mysql_result($rs, 0, "scqkbz");?></B>
<? }?>
<!--预付款担保人签字-->
<?
$isneedsign = mysql_query("select needsign from order_mainqt WHERE ddh = '".$_GET['ddh']."'");
if(mysql_result($isneedsign,0,'needsign') == '1'){
?>
   <br> <span>预付定金担保人签字：</span>
<? } ?>

<!--北京中心店 收货人签字-->
<? if($_SESSION['GDWDM'] == '340500'){
    ?>
    <br><span>收货人签字:</span>
    <?
} ?>

</body>
</html>
<? if ($_GET["getin"]=="ok" and mysql_result($rs,0,"xjdid")<>-1) {
    mysql_query("update order_mainqt set xjdid=-1 where ddh='".$_GET["ddh"]."'");}
if ($_GET["lx"]<>"show") {?>
    <SCRIPT LANGUAGE="JavaScript">
        <!-- Begin
        if (window.print) {
            <? if($dwdm=='3301') { ?>
            $("#lxr").html("");
            //$("#peisong").html("******");
            <? } ?>
            window.print();
        }
        else {
            alert('No printer driver in your PC');
        }
        //End -- >
    </script >
<? }
//检测远程文件是否存在，传入文件url
function file_exists_d($url) {
    $ch = curl_init();
    $timeout = 10;
    curl_setopt ($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

    $contents = curl_exec($ch);
    curl_close($ch);
    echo $contents;
    if (preg_match("/404/", $contents)){
        return 0;
    }else{
        return 1;
    }
}
?>
