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

    }
    else {

    }

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
        .table_main{
            width:100%;
        }
        .table_main td,.table_main th{
            border-color: #111;
            padding:7px;
            border-top:1px solid;
            border-left:1px solid;
        }
        .table_main .bottomtd{
            border-bottom:1px solid;
        }
        .table_main .righttd{
            border-right:1px solid;
        }
        .table_main .nobottomline{
            border-bottom:none;
        }
        .table_main .notopline{
            border-top:none;
        }
        p{
            margin:5px;
        }
    </style>
</head>

<body>
<? if ($_GET["lx"]<>"show") {?>
    <span class="STYLE13">此生产单请妥善保管，生产完成交付管理人员留存。<br><? echo date("Y-m-d");?></span>

<? } else {
    if (mysql_result($rs,0,"state")=="进入生产" ) {?>



    <? } else echo "订单生产完成"; } if(mysql_result($rs,0,"pczy")==""  && $_GET["lx"]=="show"){?>
    <input type="button" style="margin-right:20px;height:45px;float:right;" value="打印生产单" onClick="window.open('?ddh=<?echo mysql_result($rs,0,"ddh")?>')" />
<?}?>

<div align="center"><span class="STYLE14"><strong>印艺天空生产单</strong></span></div>
<br>
<table width="100%"  cellspacing="0" cellpadding="0" border="1" style="font-size:12px" align="center" >
    <tr>
        <td height="34" class="STYLE11" width="94" align="center">订单编号</td>
        <td width="120" class="STYLE13">
            SKY<? echo mysql_result($rs,0,"ddh");?>
        </td>
        <td width="180" align="left" class="STYLE11">下单时间：<span ><? echo mysql_result($rs,0,"ddate");?></span></td>
        <td width="180" align="left" class="STYLE11">要求完成：<span ><? echo mysql_result($rs,0,"yqwctime");?></span></td>
    </tr>
    <tr>
        <td  height="34" class="STYLE11" align="center">客户名称</td>
        <td colspan="2" class="STYLE13"><? echo mysql_result($rs,0,"khmc"),'<span id="lxr">　　联系人：',mysql_result($rs,0,"lxr"),'/',mysql_result($rs,0,"lxdh"),'</span>';?></td>
        <td class="STYLE13">定金金额：<? echo mysql_result($rs,0,"djje");?>元</td>
    </tr>


    <tr>
        <td height="24" class="STYLE11" align="center"><!--订单备注-->客户要求</td>
        <td colspan="3" class="STYLE13"><? echo mysql_result($rs,0,"memo");?></td>
    </tr>
</table>
<br>
<table class="table_main" cellspacing="0" cellpadding="0" width="100%"  style="font-size:14px">
    <colgroup>
        <col width="15%">
    </colgroup>
    <thead>
    <tr class="td_title" style="height:30px; font-weight:bold">
        <th  align="center" scope="col">印件名称</th>
        <th  align="center" scope="col">印刷工艺</th>
        <th  align="center" scope="col">印后工艺</th>
        <th  align="center" scope="col" class="righttd">装订工艺</th>
    </tr>
    </thead>
    <tbody>
    <?

    for($i=0;$i<mysql_num_rows($rsmx);$i++){   ?>
        <tr class="td_title" >
            <td rowspan="2" align="center" class="td_content" ><? echo mysql_result($rsmx,$i,"pname");?></td>

            <td align="center" class="td_content" rowspan="<? if (mysql_result($rsmx,$i,"n2")<>"") echo 1;else echo 2; ?>">
                <? echo mysql_result($rsmx,$i,"machine1"),
                ' | ', mysql_result($rsmx,$i,"mm1"),
                ' | ', mysql_result($rsmx,$i,"dsm1"),
                '/' , mysql_result($rsmx,$i,"hzx1"),
                ' | ' ,mysql_result($rsmx,$i,"pnum1"),
                'P*',mysql_result($rsmx,$i,"sl1") . '份='.mysql_result($rsmx,$i,"pnum1") * mysql_result($rsmx,$i,"sl1");
                ?>

                <P>
                    <img src='getean.php?size=32&text=<? echo 'pr-'.mysql_result($rsmx,$i,"id") . '-1';?>&<? echo rand(10,1000)?>'>
                    <? if ($_GET["lx"] == "show"){
                        if(mysql_result($rsmx,$i,"operator1") == ''){
                            ?>
                            <input type="button" class="finish-btn pr-finish" datatype="<? echo 'pr-'.mysql_result($rsmx,$i,"id") . '-1';?>" style="" value="打印完成" />
                            <?
                        }else{
                            echo '<input type="button" value="已完成" disabled="disabled"/>';
                        } ?>

                    <? } ?>
                </P>
                <?
                $aaa=array_unique(explode(";",mysql_result($rsmx,$i,"file1")));
                foreach ($aaa as $key=>$a1)
                    if ($a1<>"") {
                        if(stristr($a1,'http')){
                            $a1arr = explode('-',$a1);
//                            $a1= '拼版号:'.$a1arr[1];
                            $a1 = '';
                        }
                        echo $a1;

                    }
                if(!empty(mysql_result($rsmx,$i,"jdf1"))){

                    echo mysql_result($rsmx,$i,"jdf1");
                }
                if(!empty(mysql_result($rsmx,$i,"sczzbh1"))){
                    echo '<br>';
                    echo '纸张编号:'.substr(mysql_result($rsmx,$i,"sczzbh1"),0,3);
                }
                ?>

            </td>
            <td  class="nobottomline" align="center">
                <? $_fmrs = mysql_query("select * from order_mxqt_fm where mxid = " . mysql_result($rsmx,$i,"id") . " order by id asc",$conn);

                if($_fmrs && mysql_num_rows($_fmrs)>0) {
                    while($_fmarr = mysql_fetch_assoc($_fmrs)) {

                        echo $_fmarr["fmfs"] ,'[' , $_fmarr['sl'],$_fmarr["jldw"] , ']',$_fmarr['memo'];

                        echo '<p><img src="getean.php?size=32&text=fm-' . $_fmarr['id'] . '&' . rand(10,100) . '"/></p>';
                        if($_GET["lx"] == "show"){
                            if($_fmarr['fmczy'] == '')
                                echo '<input type="button" class="finish-btn fm-finish" datatype="fm-' . $_fmarr['id'] . '" value="覆膜完成"/>';
                            else
                                echo '<input type="button" value="已完成" disabled="disabled"/>';

                        }
                    }
                }
                ?>
            </td>
            <td class="righttd nobottomline" >&nbsp;</td>

        </tr>
        <? if (mysql_result($rsmx,$i,"n2")<>"") {?>
            <tr class="td_title" >

                <td align="center" class="td_content">
                    <? echo mysql_result($rsmx,$i,"machine2"),
                    ' | ', mysql_result($rsmx,$i,"mm2"),
                    ' | ', mysql_result($rsmx,$i,"dsm2"),
                    '/' , mysql_result($rsmx,$i,"hzx2"),
                    ' | ' ,mysql_result($rsmx,$i,"pnum2"),
                    'P*',mysql_result($rsmx,$i,"sl2") . '份=' . mysql_result($rsmx,$i,"pnum2") * mysql_result($rsmx,$i,"sl2");
                    ?>

                    <P>
                        <img src='getean.php?size=32&text=<? echo 'pr-'. mysql_result($rsmx,$i,"id").'-2';?>&<? echo rand(10,1000)?>'>
                        <?  if ($_GET["lx"] == "show"){
                            if(mysql_result($rsmx,$i,"operator2") == ''){
                                ?>
                                <input type="button" class="finish-btn pr-finish" datatype="<? echo 'pr-'.mysql_result($rsmx,$i,"id") . '-2';?>" style="" value="打印完成" />
                                <?
                            }else{
                                echo '<input type="button" value="已完成" disabled="disabled"/>';
                            }

                        } ?>
                    </P>
                    <?
                    $aaa=array_unique(explode(";",mysql_result($rsmx,$i,"file2")));
                    foreach ($aaa as $key=>$a1)
                        if ($a1<>"") {
                            if(stristr($a1,'http')){
                                $a1arr = explode('-',$a1);
                                $a1 = '';
                            }
                            echo $a1;
                        }
                    if(!empty(mysql_result($rsmx,$i,"jdf2"))){

                        echo mysql_result($rsmx,$i,"jdf2");
                    }
                    if(!empty(mysql_result($rsmx,$i,"sczzbh2"))){
                        echo '<br>';
                        echo '纸张编号:'.substr(mysql_result($rsmx,$i,"sczzbh2"),0,3);
                    }
                    ?>

                </td>
                <td class="notopline">&nbsp;</td>
                <td class="righttd notopline" align="center"><? $_hdrs = mysql_query("select * from order_mxqt_hd where mxid = " . mysql_result($rsmx,$i,"id") . " order by id asc",$conn);
                    if($_hdrs && mysql_num_rows($_hdrs)>0) {
                        while($_arr = mysql_fetch_assoc($_hdrs)) {

                            echo $_arr["jgfs"] ,'[' , $_arr['sl'],$_arr["jldw"] , ']',$_arr["memo"];

                            echo '<p><img src="getean.php?size=32&text=hd-' . $_arr['id'] . '&' . rand(10,100) . '"/></p>';
                            if($_GET["lx"] == "show"){
                                if($_arr['hdczy'] == '')
                                    echo '<input type="button" class="finish-btn hd-finish" datatype="hd-' . $_arr['id'] . '" value="后加工完成"/>';
                                else
                                    echo '<input type="button" value="已完成" disabled="disabled"/>';
                            }
                        }
                    }
                    ?>

                </td>
            </tr>
        <? }else{
            ?>
            <tr>
                <td class="notopline">&nbsp;</td>
                <td class="righttd notopline" align="center">
                    <? $_hdrs = mysql_query("select * from order_mxqt_hd where mxid = " . mysql_result($rsmx,$i,"id") . " order by id asc",$conn);
                    if($_hdrs && mysql_num_rows($_hdrs)>0) {
                        while($_arr = mysql_fetch_assoc($_hdrs)) {

                            echo $_arr["jgfs"] ,'[' , $_arr['sl'],$_arr["jldw"] , ']' ,$_arr["memo"];

                            echo '<p><img src="getean.php?size=32&text=hd-' . $_arr['id'] . '&' . rand(10,100) . '"/></p>';

                            if($_GET["lx"] == "show"){
                                if($_arr['hdczy'] == '')
                                    echo '<input type="button" class="finish-btn hd-finish" datatype="hd-' . $_arr['id'] . '" value="后加工完成"/>';
                                else
                                    echo '<input type="button" value="已完成" disabled="disabled"/>';

                            }
                        }
                    }
                    ?>
                </td>
            </tr>
            <?
        }?>

    <? }?>
    <tr>
        <td class="nobottomline" style="border-left: none;" colspan="4">&nbsp;</td>
    </tr>
    </tbody>
</table>
<table cellpadding="0" cellspacing="0" style="width: 100%;">
    <tr>
        <td style="text-align: center;">
            <img src="getean.php?size=35&text=<? echo 'wc-'.mysql_result($rs,0,"ddh"); ?>">
            <? if($_GET["lx"] == "show"){
                if($state == '待配送'){
                    echo '<input type="button" value="已完成" disabled="disabled"/>';
                }else{
                    ?>
                    <input type="button" class="finish-btn wc-finish" datatype="<? echo 'wc-'.mysql_result($rs,0,"ddh"); ?>" value="订单完成"/>
                    <?
                }

            } ?>
            <p>生产完成请扫码</p>
        </td>
        <td style="width: 30%;"></td>
        <td style="text-align: center;">
            <p>配送方式：<? echo mysql_result($rs,0,"psfs"); ?></p>
            <img src="getean.php?size=35&text=<? echo 'fh-'.mysql_result($rs,0,"ddh"); ?>">
            <? if($_GET["lx"] == "show"){
                if($state == '待配送'){
                    ?>
                    <input type="button" class="finish-btn fh-finish" datatype="<? echo 'fh-'.mysql_result($rs,0,"ddh"); ?>" value="配送"/>

                    <?
                }elseif($state == '已发货'){
                    echo '<input type="button" value="已发货" disabled/>';
                }
            }  ?>
            <p>配送请扫码</p>

        </td>
    </tr>

</table>
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
<script type="text/javascript">

    $('body').on('click','.finish-btn',function(e){

        e.preventDefault();
        var _this = $(this);
        var _datatype = _this.attr('datatype');
        var sendData = 'inner=1&operator=<? echo $_SESSION['YKOAUSER']; ?>&idwithtype='+_datatype;

        _this.attr('disabled',true);

        $.ajax({
            url : 'deal_readcode.php',
            method : 'GET',
            dataType : 'text',
            data : sendData,
            success : function(data){
                if(data != 'OK'){
                    _this.removeAttr('disabled');
                    alert(data);
                }else{
                    _this.val('已完成');
                }
            }
        });

    });
</script>
</body>
</html>
<? if ($_GET["getin"]=="ok" and mysql_result($rs,0,"xjdid")<>-1) {
    mysql_query("update order_mainqt set xjdid=-1 where ddh='".$_GET["ddh"]."'");}
if ($_GET["lx"]<>"show") {?>
    <SCRIPT LANGUAGE="JavaScript">

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
