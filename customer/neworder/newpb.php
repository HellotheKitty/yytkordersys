<?
header('Content-type:text/html;charset:utf-8');

ini_set("allow_call_time_pass_reference","true");
error_reporting(E_ALL ^ E_DEPRECATED&E_ALL ^ E_NOTICE);
require("../../inc/conn.php");
require '../inc/connykgf.php';

//require('lib/fpdi.php');
session_start();
$_SESSION["OK"]="OK";
$dwdm = substr($_SESSION["GDWDM"],0,4);
$khmc = $_SESSION["KHMC"];

//$machiners = mysql_query("select machine from b_machine", $conn);	// Hp彩色。
//ajax保存明细
if($_GET['savemx']<>''){

    $mxid = $_GET['savemx'];
    $materialid = $_GET['material'] ?  $_GET['material'] : 1;       //纸张种类
    $dsm1 = $_GET['dsm'];                 //单双面
    $pname = $_GET["pname"];                 //印件名
    $machine1 = $_GET["machine"];            //机器
    $pnum1 =$_GET["pnum"] ;             //页数
    $sl1 = $_GET["sl"]?$_GET["sl"]:0;       //数量
    $jg1 = $_GET["jg1"]?$_GET["jg1"]:0;       //jg


//生产纸张编号
    $sczzbh1 = $_GET['selfpaper_zzbh'];
    if($sczzbh1 ==''){

        $res_zzbh = mysql_query("select memo from material where MaterialCode = $materialid",$conn);

        if(mysql_num_rows($res_zzbh)>0){

            $sczzbh1 = mysql_result($res_zzbh , 0 ,'memo');
        }else{
            $logfile = new Log();
            $logfile ->NOTICE($materialid .'没有承印物');
        }
    }

    $sql = "update order_mxqt set dsm1='$dsm1' , sl1 = $sl1 ,paper1 = $materialid , machine1= '$machine1',pnum1=$pnum1 ,jg1 = $jg1, pname = '$pname' , sczzbh1='$sczzbh1' where id=$mxid";

    if(mysql_query($sql,$conn)){
//  update dje
        $ddh = mysql_result(mysql_query("select ddh from order_mxqt where id = $mxid limit 1" , $conn) , 0 ,'ddh');

        include '../commonfunc/syncroPrice.php';
        $arr = array(
            "info" => 'succ',
            "dje"=> $dje
        );
        echo json_encode($arr);
        exit;
    }else{
        echo 0;
        exit;
    }

}
//ajax增加后加工
if($_GET['addhd']<>''){

    $ddh = $_GET['ddh'];
//    $mxid = $_GET['mxid'];
//    $hdfs = $_GET['hdfs'];
//    $hdjg = $_GET['hdjg'];
//    $hdmemo = $_GET['hdmemo'];
//    $chicun = $_GET['chicun'];

    beginTransaction();

    if($_GET['hdid']<>''){

        $hdid= $_GET['hdid'];
        $sqladdhd[] = mysql_query("update order_mxqt_hd set jgfs='".$_GET["hdfs"]."',cpcc='".$_GET["chicun"]."',jldw='P',sl='".$_GET["hdsl"]."',jg='".$_GET["hdjg"]."',memo='".$_GET["hdmemo"]."' where id=$hdid",$conn);

    }else{
        $sqladdhd[] = mysql_query("insert into order_mxqt_hd (id,mxid,jgfs,cpcc,jldw,sl,jg,memo,ddhao) values (0,'".$_GET["mxid"]."','".$_GET["hdfs"]."','".$_GET["chicun"]."','P','".$_GET["hdsl"]."','".$_GET["hdjg"]."','".$_GET["hdmemo"]."','$ddh')",$conn);

    }

//    update dje
    include '../commonfunc/syncroPrice.php';

//    get updated hdfs hdjg
    $hdfs='';
    $hdjedet = '';

    $sql = "select group_concat(concat(jgfs,'|',id)) as hd , group_concat(jg) as hdjg ,sum(jg*sl) as hdje,group_concat(concat(jg,'*',sl,'|',id)) as hdjedet from order_mxqt_hd where mxid = ". $_GET['mxid']. " group by mxid";
    $reshd = mysql_query($sql,$conn);
    if(mysql_num_rows($reshd)>0){
        $hdfs = mysql_result($reshd,0,'hd');
        $hdjedet = mysql_result($reshd,0,'hdjedet');
    }

    if(transaction($sqladdhd)){
        $arr = array(
            "info" => 'succ',
            "fs" => $hdfs,
            "jedet" => $hdjedet,
            "dje"=> $dje,
        );
        echo json_encode($arr);
    }else{
        $arr['info'] = 'fail';
        echo json_encode($arr);
    }

    exit();
}

//ajax增加覆膜
if($_GET['addfm']<>''){
    $ddh = $_GET['ddh'];

    beginTransaction();

    if($_GET['fmid']<>''){

        $fmid = $_GET['fmid'];
        $sqladdfm[] = mysql_query("update order_mxqt_fm set fmfs='".$_GET["fmfs"]."',cpcc='".$_GET["chicun"]."',jldw='P',sl='".$_GET["fmsl"]."',jg='".$_GET["fmjg"]."',memo='".$_GET["fmmemo"]."' where id=$fmid",$conn);

    }else{
        $sqladdfm[] = mysql_query("insert into order_mxqt_fm (id,mxid,fmfs,cpcc,jldw,sl,jg,memo,ddh) values (0,'".$_GET["mxid"]."','".$_GET["fmfs"]."','".$_GET["chicun"]."','P','".$_GET["fmsl"]."','".$_GET["fmjg"]."','".$_GET["fmmemo"]."','$ddh')",$conn);

    }

//    update dje
    include '../commonfunc/syncroPrice.php';

// get updated fmfs
    $fmjedet = '';
    $fmfs = '';
    $sql = "select group_concat(concat(fmfs,'|',id)) as fmfs , group_concat(jg) as fmjg ,sum(jg*sl) as fmje,group_concat(concat(jg,'*',sl,'|',id)) as fmjedet from order_mxqt_fm where mxid = ".$_GET['mxid']." group by mxid";

    $resfm = mysql_query($sql,$conn);
    if(mysql_num_rows($resfm)>0){
        $fmfs = mysql_result($resfm,0,'fmfs');
        $fmjedet = mysql_result($resfm,0,'fmjedet');

    }

    if(transaction($sqladdfm)){
        $arr = array(
            "info" => 'succ',
            "fs" => $fmfs,
            "jedet" => $fmjedet,
            "dje" => $dje,
        );
        echo json_encode($arr);
    }else{
        $arr['info'] = 'fail';
        echo json_encode($arr);
    }

    exit();
}
//删除明细
if($_GET['delmx']<>''){

    $delmxid = $_GET['delmxid'];
    $rsdelmx = mysql_query("delete from order_mxqt WHERE id = $delmxid",$conn);
    //    update dje
    include '../commonfunc/syncroPrice.php';

    if($rsdelmx){
        $arr['info'] = 'succ';
        echo json_encode($arr);
    }
    exit();
}

//删除后加工
if($_GET['delhd']<>''){

    $delhdid = $_GET['delid'];
    $ddh = mysql_result(mysql_query("select ddhao from order_mxqt_hd where id=$delhdid",$conn),0,'ddhao');

    mysql_query("delete from order_mxqt_hd where id = $delhdid",$conn);
//    update dje
    include '../commonfunc/syncroPrice.php';

    echo json_encode(array("info" => "succ","dje"=>$dje,));
    exit();
}
//删除覆膜
if($_GET['delfm']<>''){

    $delfmid = $_GET['delid'];
    $ddh = mysql_result(mysql_query("select ddh from order_mxqt_fm where id=$delfmid",$conn),0,'ddh');

    mysql_query("delete from order_mxqt_fm where id = $delfmid",$conn);
    //    update dje
    include '../commonfunc/syncroPrice.php';

    echo json_encode(array("info" => "succ","dje"=>$dje,));
    exit();
}



//保存
if ($_POST["button"]<>"") {
//    $authRequestModify = $_POST["authcode"];
//    $authToCheckModify = md5($_SESSION["KHID"]."-".$_SESSION["AUTHCODE"]."-".$_POST["ddh"]."-"."新建订单");
//    if($authRequestModify != $authToCheckModify){
//        echo "<script>alert('参数错误！订单未修改！');</script>";
//        exit;
//    }

    $bzyq=$_POST["bzyq"];
    $kpyq=$_POST["kpyq"];
    $memo=$_POST["memo"];
    $scjd=$_POST["scjd"];
    $khmc=$_SESSION["KHMC"];//$_POST["khmc"];
    $shr = $_POST["shr"];//!=""?$_POST["shr"]:$_SESSION["INFO"]["lxr"];
    $shdh = $_POST["shdh"];//!=""?$_POST["shdh"]:$_SESSION["INFO"]["lxdh"];
    $shdz = $_POST["shdz"];//!=""?$_POST["shdz"]:$_SESSION["INFO"]["lxdz"];
//    $skfs=$_POST["butt2"];

    mysql_query("update order_mainqt set khmc='$khmc',psfs='".$_POST["psfs"]."',kdje='".$_POST["kdje"]."',shr='".$shr."',shdh='".$shdh."',shdz='".$shdz."',scjd='$scjd',bzyq='$bzyq',kpyq='$kpyq',memo='$memo',yqwctime='".$_POST["yqwctime"]."',djje='".$_POST["djje"]."' where ddh='".$_POST["ddh"]."'",$conn);


//    发货信息保存在fh_info表里面
    if( $_POST['fhr'] <>'' ||  $_POST['fhlxdh']<>'' ||  $_POST['fhdz'] <>''){

        mysql_query("delete from fh_info where ddh = '" . $_POST['ddh'] . "'" ,$conn);

        mysql_query("insert into fh_info (id,ddh,fhr,fhlxdh,fhdz) VALUES (0, '" . $_POST["ddh"] . "' , '" . $_POST['fhr'] . "','" . $_POST['fhlxdh'] . "' , '" . $_POST['fhdz'] . "' )" ,$conn);

    }

    $mxid = mysql_result(mysql_query("select id from order_mxqt WHERE ddh=".$_POST['ddh']." ORDER BY id DESC LIMIT 1",$conn),0,'id');

    header("location:newpb.php?ddh=".$_POST["ddh"]."&mxid=".$mxid);
    exit;
}

//删除订单
if ($_GET["deleid"]<>"") {
    $ddh=$_GET["ddh"];
    mysql_query("delete from order_mxqt_hd where mxid='".$_GET["deleid"]."'",$conn);
    mysql_query("delete from order_mxqt where id='".$_GET["deleid"]."'",$conn);
    $rs=mysql_query("select sum(jg1*pnum1*sl1+jg2*pnum2*sl2) from order_mxqt mx where mx.ddh='$ddh'",$conn);
    $rshd=mysql_query("select sum(jg*sl) from order_mxqt_hd where mxid in (select id from order_mxqt where ddh='$ddh')",$conn);
    mysql_query("update order_mainqt set dje=".(mysql_result($rs,0,0)+mysql_result($rshd,0,0))." where ddh='$ddh'",$conn);
    header("location:newpb.php?ddh=".$ddh."&auth=".md5($_SESSION["KHID"]."-".$_SESSION["AUTHCODE"]."-".$ddh."-"."新建订单"));
    exit;
}

if ($_GET["ddh"]=="") {


    $rss=mysql_query("select khmc,lxr,lxdh,lxdz from base_kh where base_kh.khmc= '$khmc'",$conn);
    if (mysql_num_rows($rss)>0) {
        $khmc=mysql_result($rss,0,"khmc");
        $lxr=mysql_result($rss,0,"lxr");
        $lxdh=mysql_result($rss,0,"lxdh");
        $lxdz=mysql_result($rss,0,"lxdz");
    }


    $_ddh = mysql_query("select ddh from order_mainqt where zzfy='$dwdm' order by id desc limit 1",$conn);
    $_arr = mysql_fetch_array($_ddh);
    $_last = $_arr[0];
    if($dwdm == '3301'){
        $_lastYM = substr($_last,0,6);
        $_nowYM = date("Ym",time());
        if($_lastYM == $_nowYM)
            $bh = $_last + 1;
        else
            $bh = $_nowYM."00001";
    }else{
        $_lastYM = substr($_last,0,4);//前四位
        $_nowYM = substr(date("Ym",time()),2,4);
        if($_lastYM == $_nowYM)
            $bh = $_last + 1;
        else
            $bh = $_nowYM.substr($dwdm,2,2)."00001";
    }

//        tbsj : 单张下单
    $memo = '客户拼版下单';
    mysql_query("insert into order_mainqt (ddh,khmc,xsbh,ddate,state,sdate,shr,shdh,shdz,memo,scjd,xjdid,zzfy,filefy,tbsj) values ('".$bh."','".$khmc."','".$xsbh."',now(),'新建订单',now(),'$lxr','$lxdh','$lxdz','$memo','".$_SESSION["GSSDQ"]."','','$dwdm','".$_SESSION["INFO"]["loginname"]."' , 'pbxd')",$conn);

//增加一条default明细
    include('new_mx.php');

//        $selje = mysql_query("select ifnull(sum(jg1*pnum1*sl1+jg2*pnum2*sl2),0) as mxje from order_mxqt mx where mx.ddh='$bh'",$conn);
//
//        $mxje = mysql_result($selje,0,'mxje');
//        $hdje = 0;
//        $fmje = 0;
//        $je = $mxje + $hdje+ $fmje;
//        $updje = mysql_query("update order_mainqt set dje = '$je' WHERE ddh = $bh",$conn);

    $mxid = mysql_result(mysql_query("select id from order_mxqt WHERE ddh=$bh ORDER BY id DESC LIMIT 1",$conn),0,'id');

    header("location:newpb.php?ddh=".$bh."&mxid=".$mxid);
    exit();

} elseif($_GET['ddh']<>'') {
    $bh=$_GET["ddh"];


//    $authRequest = $_GET["auth"];
//    $authToCheck = md5($_SESSION["KHID"]."-".$_SESSION["AUTHCODE"]."-".$bh."-"."新建订单");
////	echo $authRequest."  ".$authToCheck;
//    if($authToCheck != $authRequest){
//        echo "<script>alert('验证失败！');history.go(-1);</script>";
//        exit;
//    }

}

$rs=mysql_query("select order_mainqt.*,order_zh.sksj,base_kh.lxr,lxdh from order_mainqt left join order_zh on order_zh.ddh=order_mainqt.ddh and order_zh.zy<>'订单订金' left join base_kh on order_mainqt.khmc=base_kh.khmc where  order_mainqt.ddh='".$_GET['ddh']."'",$conn);

$xjzje=mysql_result($rs,0,"dje");
$state=mysql_result($rs,0,"state");
$rsmx = mysql_query("select order_mxqt.*,m1.materialname mm1,m1.specs ms1,m2.materialname mm2,m2.specs ms2,group_concat(hd.jgfs) hd,sum(hd.jg*hd.sl) hdje,group_concat(hd.jg) hdjg, group_concat(fm.fmfs) fmfs,sum(fm.jg*fm.sl) fmje,group_concat(fm.jg) fmjg from order_mxqt left join order_mxqt_hd hd on order_mxqt.id=hd.mxid left join order_mxqt_fm fm on order_mxqt.id=fm.mxid LEFT JOIN material m1 on  m1.id=paper1 LEFT JOIN material m2 on m2.id=paper2 where order_mxqt.ddh='" . $_GET['ddh'] . "'  group by order_mxqt.id", $conn);
//$rsmx=mysql_query("(select order_mxqt.*,m1.materialname mm1,m1.specs ms1,m2.materialname mm2,m2.specs ms2,group_concat(hd.jgfs) hd,sum(hd.jg*hd.sl) hdje from order_mxqt left join order_mxqt_hd hd on order_mxqt.id=hd.mxid,material m1,material m2 where ddh='".$bh."' and m1.materialcode=paper1 and m2.materialcode=paper2 group by order_mxqt.id) union (select order_mxqt.*,m1.materialname mm1,m1.specs ms1,m2.materialname mm2,m2.specs ms2,group_concat(hd.jgfs) hd,sum(hd.jg*hd.sl) hdje from order_mxqt left join order_mxqt_hd hd on order_mxqt.id=hd.mxid,material1 m1,material1 m2 where ddh='".$bh."' and m1.materialcode=paper1 and m2.materialcode=paper2 group by order_mxqt.id)",$conn);

require  "function/js.php";

?>
<!doctype html>
<html>
<head>

    <meta http-equiv=Content-Type content="text/html; charset=utf-8">
    <title>拼版下单</title>
    <script src="diyUpload/js/jquery.js"></script>
    <script src="../js/jquery-1.8.3.min.js"></script>
    <script src="../js/jquery-3.1.1.min.js"></script>
    <link rel="stylesheet" type="text/css" href="diyUpload/css/webuploader.css">
    <link rel="stylesheet" type="text/css" href="diyUpload/css/diyUpload.css">
    <link rel="stylesheet" type="text/css" href="../css/uploadex.css">
    <script type="text/javascript" src="diyUpload/js/plupload.full.min.js"></script>
    <script language="JavaScript">
        <!--
        function suredo(src,q)
        {
            var ret;
            ret = confirm(q);
            if(ret!=false) {
                window.location=src;
            }
        }

        //-->
        window.opener.location.reload();
        lastv = "上门自取";
        function change(v){
            if(v!="上门自取" && lastv == "上门自取"){
                alert("如选物流配送，请务必填写收货信息");
            }
            lastv = v;
        }
    </script>
    <?
    require  "function/js.php";
    ?>
</head>
<style type="text/css">

    .preview{
        float: left;
        padding-left:10px;
        padding-top:10px;
        width:calc(80% - 10px);
        border-left:1px solid #777;
    }
    .noteinfo{
        font-size:12px;
    }
    #pnum,#sl,#jg,#hdjg,#fmjg,#hdsl,#fmsl{width:40px;}
    #jg,#hdjg,#fmjg,#pnum{color:#888}
    .ordermxtb {
        border-width:0px;width:100%;border-collapse:collapse;border:1px solid #D3D3D3; border-bottom:1px solid #D3D3D3; font-size:12px
    }
    .ordermxtb td,.ordermxtb th{
        text-align: center;
    }
    .editlink{display:inline;}
    .preview_zm,.preview_fm,.preview_img{
        background-color: #fff;
        width:47%;
        float:left;
        margin-bottom:15px;
        margin-right:15px;
    }

    .hdfm_outter{margin-top:15px;
        margin-left:30px;}
    .memotext{
        width:150px;}
    .failinfo{color:#900000}
    .succinfo{color:#006600}
    .clearfix{overflow:hidden;zoom:1;}
    #ddje{color:red;
        font-size:14px;
    }
    .delmask{
        border: 1px solid;
        display: inline-block;
        padding: 2px 12px 2px 2px;
        position: relative;
    }
    .delicon{
        border-bottom: 1px solid;
        border-left: 1px solid;
        font-size: 17px;
        height: 11px;
        line-height: 7px;
        position: absolute;
        right: 0;
        top: -1px;
        width: 10px;
        cursor: pointer;
    }
    .mxeditbox{
        margin-right:10px;
        padding-bottom:10px;
    }
    *{ margin:0; padding:0;}
    #box{ margin:5px; width:540px; min-height:400px; background:#FF9}
    .pinban_view{
        width:calc(100% - 4px);
        border:1px solid #777;
        margin-bottom:5px;
    }
    .fileupbox{
        float: left;
        padding-left:18px;
        padding-top:20px;
        width:calc(20% - 20px);
    }
    .preview{
        float: left;
        padding-left:25px;
        padding-top:20px;
        background-color: #F2F2F2;
        width:calc(80% - 30px);
        border-left:1px solid #777;
    }
    .mxopt{
        margin:15px;
    }
    #sl{width:40px;}
    .titletd{
        text-align: right;
        display: inline;
        margin-left:15px;}
    .webuploader-pick{
        background-color:#337ab7;
        border: medium none;
        color: white;
        height: 30px;
        margin: 10px;
        width: 110px;
        border-radius:4px;
    }
    .preimg{
        margin: 15px 0 15px 5px;
        width: calc(50% - 20px);
    }
    .btntd{
        text-align: center;vertical-align: bottom;
        height:34px;
        font-size:15px;}
    .mx-outter{
        display: block;
        width:96%;
        margin:0 auto;
        border-bottom:1px solid #777;}
</style>
<body>
<!--<div id="box">
	<div id="test" ></div>
</div>-->
<form name=form1 method="post" action="#">
    <input type="hidden" name="ddh" value="<? echo $_GET['ddh']; ?>">
    <!--    <input type="hidden" name="authcode" value="--><?// echo md5($_SESSION["KHID"]."-".$_SESSION["AUTHCODE"]."-".$bh."-".$state)?><!--" />-->
    <table width="100%" border="1" cellpadding="4" cellspacing="1" bordercolor="#000099" bgcolor="#f6f6f6" >
        <tr>
            <td height="222" valign="top">
                <table width="80%"  border="1" align="center" cellspacing="0" cellpadding="0" style="border-width:0px;border-collapse:collapse;border:1px solid #D3D3D3; border-bottom:1px solid #D3D3D3; font-size:12px">
                    <tr>
                        <td height="28" class="STYLE11" width="10%" align="center">订单编号</td>
                        <td width="10%" class="STYLE13"><? echo mysql_result($rs,0,"ddh");?></td>
                        <td width="30%" align="left" class="STYLE11">下单时间：<span class="STYLE13"><? echo mysql_result($rs,0,"ddate");?></span></td>
                        <td width="30%" align="left" class="STYLE11">要求完成：<span class="STYLE13">
                <input name="yqwctime" type="text" value="<? echo mysql_result($rs,0,"yqwctime")==""?date("Y-m-d H:i:s",strtotime("+1 day",strtotime(mysql_result($rs,0,"ddate")))):mysql_result($rs,0,"yqwctime")?>" size="15"></span>
                        </td>
                    </tr>
                    <tr>
                        <td  height="24" class="STYLE11" align="center">客户名称</td>
                        <td colspan="2" class="STYLE13"><font size="3"><?echo $_SESSION["KHMC"]?></font><br><?echo $_SESSION["INFO"]["lxr"]."/".$_SESSION["INFO"]["lxdh"]?></td>
                        <td class="STYLE13"><font color="red">账户余额：</font><?
                            $khmc = mysql_result($rs,0,"khmc");
                            $sql_czxf = "SELECT ifnull(sum((ifnull(`order_zh`.`jf`, 0) - ifnull(`order_zh`.`df`, 0))),0) AS `czxf` FROM order_zh WHERE fssj > IFNULL((SELECT sdate FROM kh_ye WHERE depart = '$khmc' LIMIT 1),'2015-01-01') AND khmc = '$khmc' GROUP BY khmc ";

                            $sql_ye = "select ye from kh_ye where depart = '$khmc'";

                            $resye1 = mysql_query($sql_czxf,$conn);
                            $resye2 = mysql_query($sql_ye,$conn);
                            if(mysql_num_rows($resye1) >0){

                                $res_czxf = mysql_result($resye1 ,0,'czxf');

                            }else{
                                $res_czxf = 0;
                            }

                            if(mysql_num_rows($resye2)>0){
                                $res_ye = mysql_result($resye2 ,0,'ye');

                            }else{
                                $res_ye=0;
                            }

                            $yue = round(floatval($res_czxf) + floatval($res_ye) , 2);
                            echo $yue . '元';
                            ?></td>
                    </tr>
                    <tr>
                        <td height="24" class="STYLE11" align="center">配送信息</td>
                        <td colspan="3" class="STYLE13">
                            <span style="display: inline-block;width: 150px; float: left;margin-top: 5px;margin-left: 5px;">

                                配送：
                                <select name="psfs" id="psfs" onChange="change(this.value)" >
                                    <option value="上门自取" <? if (mysql_result($rs,0,"psfs")=="上门自取") echo "selected";?>>上门自取</option>
                                    <option value="快递配送" <? if (mysql_result($rs,0,"psfs")=="快递配送") echo "selected";?>>快递配送</option>
                                    <option value="物流配送" <? if (mysql_result($rs,0,"psfs")=="物流配送") echo "selected";?>>物流配送</option>
                                    <option value="送货" <? if (mysql_result($rs,0,"psfs")=="送货") echo "selected";?>>送货</option>
                                    <option value="其他" <? if (mysql_result($rs,0,"psfs")=="其他") echo "selected";?>>其他</option>
                                </select>
                            </span>

                            <span style="display: inline-block; float: left;margin-top: 5px;margin-left: 5px;">
                                收货人：<input name="shr" type="text" value="<? echo mysql_result($rs,0,"shr")==""?$lxr:mysql_result($rs,0,"shr")?>" size="6">&nbsp;&nbsp;
                                电话：<input name="shdh" type="text" value="<? echo mysql_result($rs,0,"shdh")==""?$lxdh:mysql_result($rs,0,"shdh")?>" size="12">
                                地址：<input name="shdz" type="text" value="<? echo mysql_result($rs,0,"shdz")==""?$lxdz:mysql_result($rs,0,"shdz")?>" size="57">

					        </span>
                            <span id="fh_info" style=" float: left;margin-top: 5px;margin-left: 5px;">
                                <? $res_fhinfo = mysql_query("select * from fh_info where ddh = " . $bh , $conn);
                                if(mysql_num_rows($res_fhinfo) >0){

                                    $fhr = mysql_result($res_fhinfo ,0,'fhr');
                                    $fhlxdh = mysql_result($res_fhinfo,0,'fhlxdh');
                                    $fhdz = mysql_result($res_fhinfo,0,'fhdz');
                                }

                                ?>
                                发货人：<input name="fhr" type="text" value="<? echo $fhr; ?>" size="6">&nbsp;&nbsp;
                                电话：<input name="fhlxdh" type="text" value="<? echo $fhlxdh; ?>" size="12">
                                发货地址：<input name="fhdz" type="text" value="<? echo $fhdz; ?>" size="35">
                            </span>
                        </td>
                    </tr>
                    <tr style=" display:none">
                        <td height="24" class="STYLE11" align="center">开票信息</td>
                        <td colspan="3" class="STYLE13"><textarea name="kpyq" cols="50" rows="3"><? echo mysql_result($rs,0,"kpyq");?></textarea></td>
                    </tr>
                    <tr style=" display:none">
                        <td height="24" class="STYLE11" align="center">包装要求</td>
                        <td colspan="3" class="STYLE13"><textarea name="bzyq" cols="50" rows="3"><? echo mysql_result($rs,0,"bzyq");?></textarea></td>
                    </tr>
                    <tr>
                        <td height="24" class="STYLE11" align="center">订单备注</td>
                        <td colspan="3" class="STYLE13"><textarea name="memo" cols="50" rows="3" style="width:100%;height:50px"><? echo mysql_result($rs,0,"memo");?></textarea></td>
                    </tr>
                    <tr>
                        <td height="24" class="STYLE11" align="center">生产地</td>
                        <td colspan="3" class="STYLE13">
                            <select name="scjd">
                                <option value="上海" <? if(mysql_result($rs,0,"scjd")=='上海') echo 'selected'; ?>>上海</option>
                                <option value="北京" <? if(mysql_result($rs,0,"scjd")=='北京') echo 'selected'; ?>>北京</option>
                            </select>
                            <input type="submit" name="button" id="button" value="保存订单信息"></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="btntd">
                                <?
                                if (mysql_result($rs,0,"state")=="新建订单") {

//                                    echo "<a href='?ddh=$bh&newmx=1' id='btn_newmx'>【增加明细】</a>&nbsp;&nbsp;";

                                    echo "<a href='#' onClick=\"javascript:suredo('YSXMqt_del.php?lx=new&BH=".mysql_result($rs,0,"ddh")."&auth=".md5($_SESSION["KHID"]."-".$_SESSION["AUTHCODE"]."-".mysql_result($rs,0,"ddh")."-".mysql_result($rs,0,"state"))."','确定删除本订单?');\">【删除订单】</a>&nbsp;&nbsp;";

                                    if (mysql_num_rows($rsmx)>0)
                                        echo "<a href='#' onClick=\"javascript:suredo('YSXMqt_del.php?lx=new&BH2=".mysql_result($rs,0,"ddh")."&auth=".md5($_SESSION["KHID"]."-".$_SESSION["AUTHCODE"]."-".mysql_result($rs,0,"ddh")."-".mysql_result($rs,0,"state"))."','确定提交生产?');\">【提交生产】</a>&nbsp;&nbsp;";

                                }

                                ?>
                        </td>
                        <td>
                            订单金额小计:
                            <span id="ddje"><? echo $xjzje; ?></span>

                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table cellspacing="0" cellpadding="0" rules="all" border="1" id="gvOrder" class="ordermxtb">
                    <thead>
                    <tr style="height:30px;">
                        <th width="93"  scope="col">产品</th>
                        <!--                        <th width="22"  align="center" scope="col">尺寸</th>-->
                        <th width="93" scope="col">生产文件</th>
                        <th width="120" scope="col">信息</th>
                        <th width="35"  scope="col">数量</th>

                        <th width="93" scope="col">后加工方式</th>
                        <th width="50"  scope="col">单价</th>
                        <th width="93" scope="col">覆膜方式</th>
                        <th width="50"  scope="col">单价</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?
                    if(mysql_num_rows($rsmx)==0){exit();}
                    for($i=0;$i<mysql_num_rows($rsmx);$i++){  ?>
                        <tr style="height:30px;">

                            <td>
                                <? echo mysql_result($rsmx,$i,"productname"); ?>
                            </td>
                            <td><?
                                $aaa=array_unique(explode(";",mysql_result($rsmx,$i,"file1")));
                                foreach ($aaa as $key=>$a1){

                                    $nameofa1 = explode('/',$a1);
                                    $nameofa1 = end($nameofa1);
                                    if ($a1<>"") echo "<a href='{$a1}?".rand(10,1000)."' target='_blank'>{$nameofa1}</a>  ","<br>";

                                }
                                if(!empty(mysql_result($rsmx,$i,"jdf1"))){
                                    echo '<br>';
                                    echo 'jdf文件:'.mysql_result($rsmx,$i,"jdf1");
                                }

                                if(!empty(mysql_result($rsmx,$i,"sczzbh1"))){
                                    echo '<br>';
                                    echo '纸张编号:'.mysql_result($rsmx,$i,"sczzbh1");
                                }
                                ?>

                            </td>
                            <td>
                                <? $machine1 = mysql_result($rsmx,$i,"machine1");
                                $material1 =mysql_result($rsmx,$i,"mm1");
                                $dsm1 = mysql_result($rsmx,$i,"dsm1");
                                $hzx1 = mysql_result($rsmx,$i,"hzx1");
                                $pnum1 = mysql_result($rsmx,$i,"pnum1");
                                $sl1 = mysql_result($rsmx,$i,"sl1");
                                $jg1 = mysql_result($rsmx,$i,'jg1');
                                $chicun1 = mysql_result($rsmx,$i,"ms1");
                                $paper1 = mysql_result($rsmx,$i,'paper1');
                                $pname = mysql_result($rsmx,$i,'pname');

                                echo $machine1,
                                "/<font color=red>",$material1,
                                "[",$chicun1,"]</font>/",
                                mysql_result($rsmx,$i,"jldw1"),"/",
                                $dsm1,"/",
                                $hzx1,"/P:",
                                $pnum1,"/SL:",
                                $sl1
                                ;?>
                            </td>

                            <td><? echo mysql_result($rsmx,$i,"sl1");?></td>

                            <?
                            $mxid = mysql_result($rsmx, $i, 'id');

                            //                            $sql = "select group_concat(jgfs) as hd , group_concat(jg) as hdjg ,sum(jg*sl) as hdje,group_concat(concat(jg,'*',sl)) as hdjedet from order_mxqt_hd where mxid = $mxid group by mxid";
                            $sql = "select id,jgfs , sl ,jg from order_mxqt_hd where mxid = $mxid ";

                            $rshd = mysql_query($sql, $conn);

                            if (mysql_num_rows($rshd) > 0) { ?>
                                <td id="hdfs_show">
                                    <?
                                    while($itemhd = mysql_fetch_array($rshd)){
                                        ?>
                                        <span class="delmask" datatype="<? echo $itemhd['id'] ?>">
                                            <? echo $itemhd['jgfs'] ?>
                                            <span class="delicon">x</span>
                                        </span>
                                        <?
                                    }
                                    ?>
                                </td>
                                <td id="hdje_show">
                                    <? for($a=0;$a<mysql_num_rows($rshd);$a++){
                                        ?>
                                        <span class="<? echo mysql_result($rshd,$a,'id'); ?>">
                                            <? echo mysql_result($rshd,$a,'jg').'*'.mysql_result($rshd,$a,'sl') . ';'; ?>
                                        </span>
                                        <?
                                    } ?>
                                </td>

                                <?
                            }else{
                                ?>
                                <td id="hdfs_show"></td>
                                <td id="hdje_show"></td>
                                <?
                            }
                            //                            $sql2 = "select group_concat(fmfs) as fmfs , group_concat(jg) as fmjg ,sum(jg*sl) as fmje,group_concat(concat(jg,'*',sl)) as fmjedet from order_mxqt_fm where mxid = $mxid group by mxid";
                            $sql2 = "select id,jg,sl,fmfs from order_mxqt_fm where mxid = $mxid";
                            $rsfm = mysql_query($sql2, $conn);

                            if (mysql_num_rows($rsfm) > 0) {
                                ?>
                                <td id="fmfs_show">
                                    <?
                                    while($itemfm = mysql_fetch_array($rsfm)){
                                        ?>
                                        <span class="delmask" datatype="<? echo $itemfm['id'] ?>">
                                            <? echo $itemfm['fmfs'] ?>
                                            <span class="delicon">x</span>
                                        </span>
                                        <?
                                    }
                                    ?>
                                </td>
                                <td id="fmje_show">
                                    <? for($a=0;$a<mysql_num_rows($rsfm);$a++){
                                        ?>
                                        <span class="<? echo mysql_result($rsfm,$a,'id'); ?>">
                                            <? echo mysql_result($rsfm,$a,'jg').'*'.mysql_result($rsfm,$a,'sl') . ';'; ?>
                                        </span>
                                        <?

                                    } ?>
                                </td>

                            <? } else {
                                ?>
                                <td id="fmfs_show"></td>
                                <td id="fmje_show"></td>
                                <?
                            } ?>
                        </tr>
                        <? $zje=$zje+mysql_result($rsmx,$i,"sl1")*mysql_result($rsmx,$i,"pnum1")*mysql_result($rsmx,$i,"jg1")+mysql_result($rsmx,$i,"sl2")*mysql_result($rsmx,$i,"pnum2")*mysql_result($rsmx,$i,"jg2")+mysql_result($rsmx,$i,"hdje");
                    }?>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>
    <? if ((mysql_result($rs,0,"state")=="进入生产") and $_SESSION["FBSD"]=="1") {?><div align="center"><input name="b1" value="打印生产单" type="button" onClick="window.open('YSXMqt_show_p.php?ddh=<? echo $bh;?>')"></div><? }?>
    <? if (mysql_result($rs,0,"state")=="待配送") {?><div align="center"><input type="button" onClick="window.open('YSXMqt_sh_p.php?ddh=<? echo $bh;?>','new');" value="生成配送单" /></div><? }?>
</form>
<!---------订单信息拼版信息分界线---------->
<form action="function/pinban_kh.php?ddh=<? echo $bh?>" method="post" enctype="multipart/form-data">

    <div class="mx-outter options" id="optionsaaaa" datatype="printopt">
    <table class="mxopt">
        <tr>
            <td>
                <div class="options mxeditbox" id="options" datatype="printopt">
                    <span>打印方式:</span>
                    <input type="hidden" value="<? echo $_GET['ddh']; ?>" name="ddh"/>
                    <input type="hidden" value="<? echo $mxid; ?>" name="mxid" id="mxid"/>
                    <input type="hidden" value="<? echo $chicun1; ?>" name="chicun" id="chicun"/>
                    <select id="machine" name="machine" class="selmm setjg">
                        <option value="">请选择机型</option>
                        <option class="hp10000machine" value="Hp10000彩色" <? if($machine1=='Hp10000彩色') echo 'selected';  ?>>Hp10000</option>
                        <option class="hp7600machine" value="Hp彩色" <? if($machine1=='Hp彩色') echo 'selected';  ?>>Hp7600</option>
                    </select><span style="color:red;display:inline;">*</span>
                    <select id="material" name="material" class="selmm setjg">
                        <option value="">请选择纸张</option>
                        <? $resmaterial = mysql_query("select MaterialName,MaterialCode, Specs from material where zzfy = $dwdm and locate('A3',MaterialName)=0 and locate('A4',MaterialName) = 0" ,$conn);

                        while($item = mysql_fetch_array($resmaterial)){
                            ?>
                            <option class="<? if($item['Specs'] == '750*530') echo 'hp10000'; elseif($item['Specs']=='464*320') echo 'hp7600'; ?>" value="<? echo $item['MaterialCode'];?>" <?  if($paper1 ==$item['MaterialCode']) echo 'selected'; ?>><? echo $item['MaterialName'] ?></option>
                            <?
                        }
                        ?>
                    </select><span style="color:red;display:inline;">*</span>

                    <span id="pnameoutter" style="display: none;">
                        <span>具体纸张名:<span style="color:red;display:inline;">*</span></span>
                        <select id="" name="selfpaper_zzbh">
                            <option value="">请选择...</option>
                            <? $respname = mysql_query("select zzmc,scbh from base_zz_ck order by convert(zzmc using gbk)",$connykgf);
                            if(mysql_num_rows($respname)>0){

                                while($pnameitem = mysql_fetch_array($respname)){

                                    ?>
                                    <option value="<? echo $pnameitem['scbh'] ?>"><? echo $pnameitem['zzmc'] ?></option>
                                    <?
                                }
                            }
                            ?>
                        </select>
                    </span>
                    <select id="dsm" name="dsm" class="setjg">
                        <option value="">请选择单双面</option>
                        <option value="单面" <? if($dsm1 == '单面') echo 'selected'; ?>>单面</option>
                        <option value="双面" <? if($dsm1 == '双面') echo 'selected'; ?>>双面</option>
                    </select><span style="color:red;display:inline;">*</span>

                    <span>P数</span>
                    <input type="text" id="pnum" name="pnum" value="<? echo $pnum1; ?>" />
                    <span>份数<span style="color:red;display:inline;">*</span></span>
                    <input type="text" id="sl" class="setjgblur" name="sl" value="<? echo $sl1; ?>"/>

                    <span>单价</span>
                    <input type="text" id="jg" readonly="readonly" name="jg" value="<? echo $jg1; ?>"/>

                    <span>印件名</span>
                    <input type="text" style="width:250px;height:24px;" value="<? echo $pname; ?>" name="pname"/>

                    <input type="button" id="savemx" value="保存明细"/>

                    <span id="savemx_notice">
                        <span style="display: none;" class="succinfo">保存成功！</span>
                        <span style="display: none;" class="failinfo">保存失败，请重新操作！</span>
                    </span>


                </div>
            </td>

        </tr>
    </table>
    </div>
    <!--hd fm-->

    <div class="hdfm_outter" id="hdfm_outter">

        <div datatype="hdopt" class="options">
            <table cellpadding="0" cellspacing="0" >
                <colgroup>
                    <col width="100px"/>
                    <col width="110px"/>
                    <col width="150px"/>
                    <col width="100px"/>
                    <col width="100px"/>
                    <col width="200px"/>
                    <col width="50px"/>
                </colgroup>
                <tr style="height: 50px;">
                    <td class="tdfirst">后加工方式:</td>
                    <td><select name="hdfs" id="hdfs" class="setjg">
                            <option value="-1">请选择...</option>
                            <? $rs1=mysql_query("select distinct afterprocess,unit from b_afterprocess order by afterprocess",$conn);
                            if($rs1){
                                for ($i=0;$i<mysql_num_rows($rs1);$i++) {?>
                                    <option value="<? echo mysql_result($rs1,$i,'afterprocess');?>" ><? echo mysql_result($rs1,$i,'afterprocess'),'-',mysql_result($rs1,$i,'unit');?></option>
                                <? }
                            } ?>
                        </select>
                    </td>
                    <td>
                        <span>尺寸</span>
                        <select name="hdchicun" id="hdchicun">
                            <? $rschicun = mysql_query("select psize from b_psize",$conn);
                            while($chicunopt = mysql_fetch_assoc($rschicun)){
                                echo '<option value="' . $chicunopt['psize'] . '">'. $chicunopt['psize'] .'</option>';
                            };
                            ?>
                        </select>
                    </td>
                    <td><span>数量</span><input type="text" name="hdsl" id="hdsl"/></td>
                    <td><span>单价</span><input type="text" name="hdjg" id="hdjg" readonly="readonly"/></td>
                    <td><span>备注</span><input type="text" name="hdmemo" id="hdmemo" class="memotext"/></td>
                    <td><input type="button" value="添加" datatype="addhd" class="btnadd"/></td>
                    <td>
                        <span style="display: none;" class="succinfo">添加成功！</span>
                        <span style="display: none;" class="failinfo">添加失败，请重新操作！</span>
                    </td>
                </tr>
            </table>
        </div>

        <div datatype="fmopt" class="options">
            <table cellpadding="0" cellspacing="0">
                <colgroup>
                    <col width="100px"/>
                    <col width="110px"/>
                    <col width="150px"/>
                    <col width="100px"/>
                    <col width="100px"/>
                    <col width="200px"/>
                    <col width="50px"/>
                </colgroup>
                <tr style="height:50px;">
                    <td class="tdfirst" >覆膜方式:</td>
                    <td><select name="fmfs" id="fmfs" class="setjg">
                            <option value="-1">请选择...</option>
                            <? $rs1=mysql_query("select distinct fumo from b_fumo order by fumo",$conn);
                            if($rs1)
                                for ($i=0;$i<mysql_num_rows($rs1);$i++) {?>
                                    <option value="<? echo mysql_result($rs1,$i,0);?>" ><? echo mysql_result($rs1,$i,0);?></option>
                                <? }?>
                        </select>
                    </td>
                    <td>
                        <span>尺寸</span>

                        <select name="fmchicun" id="fmchicun">
                            <option value="464*320" <? if($chicun1=='464*320') echo 'selected';?>>464*320</option>
                            <option value="750*530" <? if($chicun1=='750*530') echo 'selected';?>>750*530</option>
                        </select>
                    </td>
                    <td><span>数量</span><input type="text" name="fmsl" id="fmsl"/></td>
                    <td><span>单价</span><input type="text" name="fmjg" id="fmjg" readonly="readonly"/></td>
                    <td><span>备注</span><input type="text" name="fmmemo" id="fmmemo" class="memotext"/></td>
                    <td><input type="button" value="添加" datatype="addfm" class="btnadd" /></td>
                    <td>
                        <span style="display: none;" class="succinfo">添加成功！</span>
                        <span style="display: none;" class="failinfo">添加失败，请重新操作！</span>
                    </td>
                </tr>
            </table>
        </div>

    </div>
<hr>
    <div class="pinban_view">


        <div id="demo"  class="fileupbox">
            <div id="test"></div>
<!--            <div id="filelist">您的浏览器不支持flash和html5，请下载flash插件或者更换浏览器</div>-->
            <br />
            <? if(is_dir('server/upload/' . $_GET['ddh'] . '/')) echo '<span style="color:#555;">已上传文件</span>'; ?>
            <div id="container">
<!--                <a id="pickfiles" href="javascript:;">[选择文件]</a>-->
<!--                <a id="uploadfiles" href="javascript:;">[上传文件]</a>-->
<!--                begin of uploadex-->
                <form id="form1">
                    <!--offset:<input type="text" id="offset"-->
                    <input type="text" id="userid" value="<? echo $bh; ?>" style="display:none;">
                    <input type="text" id="blocklen" value="1000000" style="display:none;">
                </form>

                <input type="button" id="upload" class="cnupload upbtn" value="上传">
                <input type="button" id="selfile" class="upbtn" value="选择文件"><label id="selfilesinfo"></label>
                <input type="file" multiple id="file1" style="display:none;">

                <hr />
                <textarea id="range" style="display:none;">
                </textarea>
                <textarea id="content" style="display:none;">
                </textarea>
                <textarea id="result" style="display:none;">
                </textarea>
                <!--<div id="upload"></div>-->

                <br />
                <div id="list1" style="width:100%; " class="uploadify-queue"></div>

                <!--hr />
                <div id="drag" style="width:400px; height:400px; border:#000 1 solid;"></div>
                <hr /-->

                <div id="list2" style="width:100%; "></div>
                <!--                end of uploadex-->
                <br><br>
                <br>
                <span>请选择出血:
                    <select name="bleed">
                        <option value="0" selected>0</option>
                        <option value="1">1mm</option>
                        <option value="2">2mm</option>
                        <option value="3">3mm</option>
                    </select>
                </span>
                <br>
                <br>
                <span style="margin:10px 0;display: inline-block;">
                    请选择拼版方式:
                    <select name="pb_type1" style="width:80px;height:25px;">
                        <option value="seperate">单独拼</option>
                        <option value="together">一起拼</option>
                    </select>
                </span>
                <br>
                <span style="margin:10px 0;display: inline-block;"><span style="font-weight: bold;">图片格式</span>请填写成品尺寸(含出血):</span>
                <br>
                <span style="margin:10px 0;display: inline-block;">
                    <input type="text" name="cp_width" style="width: 80px;height: 25px;" placeholder="成品长度"/>毫米(mm)
                </span>
                <br>

                <span style="margin:10px 0;display: inline-block;">
                    <input type="text" name="cp_height" style="width: 80px;height: 25px;" placeholder="成品宽度"/>毫米(mm)
                </span>
                <br>
                 <span style="display: inline-block;font-size: 12px;color:#555;">
                    (不填则按 300dpi 的分辨率拼版)
                </span>
                <input type="submit" onclick="$(this).hide();" name="" id="" value="点击生成 ->" class="webuploader-pick"/>
                <br><span>格式:jpg, png, gif, pdf</span><br>
                <span>要求:1、一起拼的一次上传的所有文件尺寸要一致。<br>
                2、PDF和图片格式分开拼。
                </span><br>
                <span>3、单面拼的pdf文件只能有一页;双面拼的正反面放在一个pdf文件里面的第一二页。</span><br>
                <span></span>
            </div>

            <br />
            <pre id="console"></pre>

        </div>

        <div id="show"  class="preview clearfix">
            <p>
                拼版预览
                <input type="button" value="删除预览" onclick="window.location.href='function/function/delete_preview_pb.php?ddh=<?echo $bh?>'"/>
<!--                <small>--关闭本页之前请将预览删除</small>-->
            </p>
            <?
            $path="function/BillFiles/".substr($bh, -6)."/";

            if(is_dir($path)){
                $filearr = scandir($path);
                unset($filearr[0]);
                unset($filearr[1]);
                foreach($filearr as $image_url){
                    ?>
                    <img src="<?echo $path.$image_url . '?' . rand(1,10); ?>"  class="preimg"/>
                    <?
                }
            }else{
                ?>
                <img class="preimg" src="function/BillFiles/nonepreviewzm.png" />
                <img class="preimg" src="function/BillFiles/nonepreviewfm.png" />

                <?
            }
            ?>
        </div>
        <div style="clear: both;"></div>

    </div>
</form>

</body>
<script type="text/javascript">


    var selfiles = [];
    var browser;
    var verinfo;
    var transtate = "idle";
    var curindex = 0;
    var cur_file;
    var cur_start;
    var cur_blocklen;
    var cur_userid;

    function onload()
    {
    }
    $(function()
    {
        //browser = getBrowserInfo() ;
        //verinfo = (browser+"").replace(/[^0-9.]/ig,"");

        var t = 0;
        /*
         $("#file1").on("change",function(e){
         fileSelect1(e);
         });
         */
        t = t+1;

        var sf = isSupportFileApi();

        //alert("sf:"+sf);

        $("#selfile").click(function(){
            document.getElementById('file1').click();
        });

        $(document).on("click",".delfilebtn",function(){
            var fid = $(this).attr("fid");

            for(var i=0;i<selfiles.length;i++)
            {
                var tff = selfiles[i];
                if(tff.id==fid)
                {
                    selfiles.splice(i,1);
                    $('#'+fid).fadeOut();
                    break;
                }
            }

        });
        $("#upload").click(function(){

            /*
             var files = document.getElementById('file1').files;
             if(!files.length) {
             alert('请选择文件');
             return false;
             }
             */

            if(!selfiles.length)
            {
                alert('请选择文件');
                return false;
            }

            document.getElementById("upload").disabled=true;

            var userid = $("#userid").val();
            if(userid =='')
            {
                userid = 0;
            }

            //var off = $("#offset").val();
            var off = 0;
            var blocklen = $("#blocklen").val();

            //var file = files[0];
            //init_upload(file,blocklen,userid);
            //$.ajaxSetup({async: false});

            curindex = 0;
            transtate = "transfer";
            StartNext();
            /*
             for(var i=0;i<selfiles.length;i++)
             {
             var tf = selfiles[i];
             init_upload(tf,blocklen,userid);
             }

             document.getElementById("upload").disabled=false;
             */



        });


        document.getElementById('file1').onchange = fileSelect1;
        /*
         var drag = document.getElementById('drag');
         drag.addEventListener('drop', dropHandler, false);
         drag.addEventListener('dragover', dragOverHandler, false);
         */
    });

    function StartNextEx()
    {
        setTimeout(StartNext,500);
    }

    function StartNext()
    {
        if(curindex < selfiles.length)
        {

            var blocklen = $("#blocklen").val();

            var userid = $("#userid").val();
            if(userid =='')
            {
                userid = 0;
            }

            var tf = selfiles[curindex++];
            if(tf.ustatus != "uploaded")
            {
                tf.ustatus = 'transfering';
                init_upload(tf,blocklen,userid);

            }
            else
            {
                StartNext();
            }

            //curindex++;
        }
        else
        {
            curindex = 0;
            document.getElementById("upload").disabled=false;
            this.transtate = "idle";
        }
    }

    function fileSelect1(e)
    {
        var html=[];
        var files = this.files;
        var fileID;
        for(var i = 0, len = files.length; i < len; i++) 	{
            var f = files[i];


            fileID = genFileID();
            f.id = fileID;
            f.ustatus = "selected";
            selfiles.push(f);
            html.push(
                '<div id="' + fileID +'" fname="'+f.name+ '" class="uploadify-queue-item" style="width:100%;"><div class="uploadify-progress" style="display:none;"><div class="uploadify-progress-bar"></div></div>'+
                '<span class="upload_percent" style="display:none;">0%</span> <span class="totalsize">文件大小：' + f.size + 'bytes</span>'+'<span class="up_filename">'+f.name + '</span><span class="delfilebtn" fid="'+fileID+'">X</span>',
                '</div>'
            );
        }

        var oldhtml = document.getElementById('list1').innerHTML;
        document.getElementById('list1').innerHTML = oldhtml + html.join('');
        if(transtate == "idle")
        {
            document.getElementById("upload").disabled=false;
        }
        //$("#selfilesinfo").innerHTML ='Selected Files' +selfiles.length;


    }

    function genFileID()
    {
        var dt = new Date();
        var rdmstr = Math.random()+'';
        rdmstr = rdmstr.substr(2,rdmstr.length-2);
        //var dtstr = dt.getFullYear()+'_'+dt.getMonth()+'_'+ + dt.getDay() + '_' + dt.getHours() + '_'+dt.getMinutes()+'_'+dt.getSeconds()+'_'+dt.getMilleSeconds();
        var dtstr = dt.getTime()+'_'+ rdmstr;
        //var dtstr = dt.getTime();

        return dtstr;


    }

    function getBrowserInfo()
    {
        var agent = navigator.userAgent.toLowerCase() ;

        var regStr_ie = /msie [\d.]+;/gi ;
        var regStr_ff = /firefox\/[\d.]+/gi
        var regStr_chrome = /chrome\/[\d.]+/gi ;
        var regStr_saf = /safari\/[\d.]+/gi ;
        //IE
        if(agent.indexOf("msie") > 0)
        {
            return agent.match(regStr_ie) ;
        }

        //firefox
        if(agent.indexOf("firefox") > 0)
        {
            return agent.match(regStr_ff) ;
        }

        //Chrome
        if(agent.indexOf("chrome") > 0)
        {
            return agent.match(regStr_chrome) ;
        }

        //Safari
        if(agent.indexOf("safari") > 0 && agent.indexOf("chrome") < 0)
        {
            return agent.match(regStr_saf) ;
        }

    }

    function isSupportFileApi()
    {
        if(window.File && window.FileList && window.FileReader && window.Blob) {
            return true;
        }
        return false;
    }

    function start_uploadex()
    {
        start_upload(cur_file,cur_start,cur_blocklen,cur_userid);
    }

    function start_upload(upfile,start,blocklen,userid)
    {

        var file = upfile;
        var tsize = file.size;

        if(start == tsize)
        {
            upfile.ustatus = "uploaded";
            StartNext();
            return 1;
        }

        var len = blocklen;

        if((start * 1 + blocklen*1)*1 >= tsize)
        {
            len = tsize-start;
        }


        var end = (start * 1 + len * 1) * 1;
        var start = parseInt(start, 10) || 0;
        end = parseInt(end, 10) || (file.size - 1);




        var blob;
        var reader = new FileReader();
        reader.onloadend = function(e) {
            if(this.readyState == FileReader.DONE) {

                sendblob(file.name,blob,tsize,len,start,blocklen,userid,file,"transfer");
            }
        };



        if(file.webkitSlice) {
            blob = file.webkitSlice(start, end );
        } else if(file.mozSlice) {
            blob = file.mozSlice(start, end );
        } else if(file.slice) {
            blob = file.slice(start, end );
        }
        blob.type = file.type;

        if (!FileReader.prototype.readAsBinaryString)
        {

            reader.readAsArrayBuffer(blob);
        }
        else
        {

            reader.readAsBinaryString(blob);
        }


    }

    function init_upload(upfile,blocklen,userid)
    {

        var file = upfile;
        var tsize = file.size;
        var len = 1;


        var end = (start * 1 + len * 1) * 1;
        var userid = $("#userid").val();

        var start = 0;
        end = 1;




        var blob;
        var reader = new FileReader();
        reader.onloadend = function(e) {
            if(this.readyState == FileReader.DONE) {

                sendblob(file.name,blob,tsize,len,start,blocklen,userid,file,"transferinit");
            }
        };




        if(file.webkitSlice) {
            blob = file.webkitSlice(start, end );
        } else if(file.mozSlice) {
            blob = file.mozSlice(start, end );
        } else if(file.slice) {
            blob = file.slice(start, end );
        }

        blob.type = file.type;

        //extend FileReader
        if (!FileReader.prototype.readAsBinaryString)
        {

            reader.readAsArrayBuffer(blob);
        }
        else
        {

            reader.readAsBinaryString(blob);
        }

    }




    function sendblob(name,blb,tsize,blen,off,blocklen,userid,tf,taction) {
        //var form=document.getElementById("form1");
        var charset = document.characterSet?document.characterSet:document.charset;
        var formData=new FormData();
        formData.append("tsize",tsize);
        formData.append("len",blen);
        formData.append("name",name);
        formData.append("offset",off);
        formData.append("blocklen",blocklen);
        formData.append("userid",userid);
        formData.append("taction",taction);
        formData.append("chst",charset);
        formData.append("file",blb);

        $.ajax({
            url: "server/uploadex.php",
            type: "POST",
            data: formData,
            processData: false,  // 告诉jQuery不要去处理发送的数据
            contentType: false,   // 告诉jQuery不要去设置Content-Type请求头
            success: function(response,status,xhr){

                var result = $('#result').val()+"\r\n"+response;
                $('#result').val(result);

                if(taction == "transfer")
                {
                    var itemctl = $('.uploadify-queue-item[fname="'+name+'"]');
                    var delfbtn = itemctl.children('.delfilebtn');
                    delfbtn.css('display','none');
                    var percent = ((off*1+blen*1) / tsize * 100).toFixed(2) +'%';
                    itemctl.children('.uploadify-progress').css('display','inline-block');
                    itemctl.children('.uploadify-progress').children('.uploadify-progress-bar').css('width',percent);
                    itemctl.children('.upload_percent').css('display','inline-block');
                    itemctl.children('.upload_percent').text(percent);
                    cur_file = tf;
                    cur_start = (off*1+blen*1)*1;
                    cur_blocklen = blocklen;
                    cur_userid = userid;

                    setTimeout(start_uploadex,300);
                    //start_upload(tf,(off*1+blen*1)*1,blocklen,userid);
                }
                else
                {
                    cur_file = tf;
                    cur_start = response;
                    cur_blocklen = blocklen;
                    cur_userid = userid;

                    setTimeout(start_uploadex,300);
                    //start_upload(tf,response,blocklen,userid);
                }
            },
            error: function(){
                alert("Transfer Fail,Please click Upload again!");
                document.getElementById("upload").disabled=false;
                this.transtate = "idle";
            }
        });


        return;
    }

    function test()
    {


    }

    function readBlob(start, len,blocklen) {
        var files = document.getElementById('file1').files;

        var end = (start * 1 + len * 1) * 1;
        var userid = $("#userid").val();

        if(userid =='')
        {
            userid = 0;
        }

        if(!files.length) {
            alert('请选择文件');
            return false;
        }

        var file = files[0],
            start = parseInt(start, 10) || 0,
            end = parseInt(end, 10) || (file.size - 1);

        var r = document.getElementById('range'),
            c = document.getElementById('content');


        var blob;
        var reader = new FileReader();
        reader.onloadend = function(e) {
            if(this.readyState == FileReader.DONE) {
                var rlt = this.result;
                c.textContent = this.result;
                r.textContent = "Read bytes: " + (start ) + " - " + (end -1) + " of " + file.size + " bytes; type:" + blob.type;
                //sendblob(file.name,file,file.size,len);
                sendblob(file.name,blob,file.size,len,start,blocklen,userid);
            }
        };



        if(file.webkitSlice) {
            blob = file.webkitSlice(start, end );
        } else if(file.mozSlice) {
            blob = file.mozSlice(start, end );
        } else if(file.slice) {
            blob = file.slice(start, end );
        }

        blob.type = file.type;
        //blob.name = file.name;
        reader.readAsBinaryString(blob);



        return blob;
    }


</script>
<script type="text/javascript">
    //    document.getElementById("zje").innerHTML='<?// echo $zje;?>//';
    //    window.opener.location.reload();

    $('.selmm').on('change',function(){

        var _this = $(this);
        var _cur = _this.attr('name');

        if(_cur == 'material'){


        }else if(_cur == 'machine'){

            var _zzchicun = $(this).val();

            var _machinetype10000 = _zzchicun.indexOf('Hp10000');
            var _machinetype76 = _zzchicun.indexOf('Hp彩色');

            var _zzopts =  _this.siblings('#material');

            if(_machinetype10000 >= 0){
                _zzopts.find('.hp7600').hide();
                _zzopts.find('.hp10000').show();
            }
            if(_machinetype76>=0){
                _zzopts.find('.hp7600').show();
                _zzopts.find('.hp10000').hide();
            }

        }
    });

    //        $('#btn_newmx').on('click',function(){
    //
    //            var _senddata = "newmx=1";
    //            $.ajax({
    //                type:'GET',
    //                dataType:'json',
    //                data:_senddata,
    //                success:function(data){
    //
    //                    var _html = "<tr><td>单张<a class='editlink' href='#'>编辑</a></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
    //
    //                    $('#gvOrder').find('tbody').append(_html);
    //                },
    //                error:function(){
    //                    alert('error,plz retry');
    //                }
    //            });
    //        });

    //    获取价格

    //ajax删除明细
    $('.deletemxbtn').on('click',function(){
        var _this = $(this);
        var delmxid = _this.attr('datatype');
        var _senddata = 'delmx=1&delmxid='+delmxid;
        $.ajax({

            type:'GET',
            dataType:'json',
            data:_senddata,
            success:function(){

                _this.closest('tr').remove();
            },
            error:function(){
                alert('err,plz rty');
            }
        });
    });

    //    ajax 增加覆膜后道
    function addhdfm(_this){

        var _addtype = _this.attr('datatype');
        if(_addtype=='addhd'){

            var _senddata="addhd=1&mxid="+ $('#mxid').val() + "&hdfs=" + $('#hdfs').val() + "&hdsl=" + $('#hdsl').val() + "&hdjg=" + Number($('#hdjg').val()) +"&hdmemo="+$('#hdmemo').val() + "&chicun=" + $('#hdchicun').val();


        }else if(_addtype=='addfm'){

            var _senddata="addfm=1&mxid="+ $('#mxid').val() + "&fmfs=" + $('#fmfs').val() + "&fmsl=" + $('#fmsl').val() + "&fmjg=" + Number($('#fmjg').val()) +"&fmmemo="+$('#fmmemo').val() + "&chicun=" + $('#fmchicun').val();

        }

        _this.closest('tr').find('.succinfo').hide();
        _this.closest('tr').find('.failinfo').hide();

        $.ajax({
            type:'GET',
            dataType:'json',
            data:_senddata,
            success:function(data){
                _this.closest('tr').find('.succinfo').show();

//                update mx table
                var arr = data['fs'].split(',');
                var _fshtml = '';
                $.each(arr,function(i,item){

                    var itemarr = item.split('|');
                    _fshtml += "<span class='delmask' datatype='"+ itemarr[1] +"'>"+ itemarr[0] +"<span class='delicon'>x</span></span>"
                });

                var jgarr = data['jedet'].split(',');
                var _jghtml = '';
                $.each(jgarr,function(i,item){

                    var itemarr = item.split('|');
                    _jghtml += "<span class='"+ itemarr[1] +"'>"+itemarr[0]+";</span>";
                });

                if(_addtype=='addhd'){

                    $('#hdfs_show').html(_fshtml);
                    $('#hdje_show').html(_jghtml);
                    $('#ddje').html(data.dje);

                }else if(_addtype == 'addfm'){

                    $('#fmfs_show').html(_fshtml);
                    $('#fmje_show').html(_jghtml);
                    $('#ddje').html(data.dje);

                }

//                update dje
            },
            error:function(){
                _this.closest('tr').find('.failinfo').show();

            }
        });
    }
    $('.btnadd').on('click',function(){

        var _this = $(this);
        addhdfm(_this);
    });

    //  ajax  删除后道覆膜
    function delhdfm(_this){

        var _deltype = _this.closest('td').attr('id');
        var _delid = _this.closest('.delmask').attr('datatype');
        var _senddata ='';
        if(_deltype == 'hdfs_show'){

            _senddata = 'delhd=1&delid=' + _delid;

        }else if(_deltype == 'fmfs_show'){

            _senddata = 'delfm=1&delid=' + _delid;

        }

        $.ajax({

            type:'GET',
            dataType:'json',
            data:_senddata,
            success:function(data){

                _this.closest('tr').find('.'+_delid).remove();
                _this.closest('span.delmask').remove();

//                订单金额更新
                $('#ddje').html(data.dje);

            },
            error:function(){

                alert("error,please retry;");
            }
        });

    }
    $('#gvOrder').on('click','.delicon',function(){

        var _this = $(this);
        delhdfm(_this);
    });

    //  ajax  获取价格
    function setjg(_this){

        var ptype = _this.closest('div.options').attr('datatype');

        var type = '';
        var _senddata ='ddh=' + form1.ddh.value;

        if(ptype == 'printopt'){
            type = '1';
            _senddata +='&type=' + type +'&machine=' + $('#machine').val() +'&paper=' +$('#material').val()
                + '&dsm=' +$('#dsm').val() +'&zsl='+ (parseInt($('#pnum').val()) * parseInt($('#sl').val())) + '&jldw=p';


        }else if(ptype == 'hdopt'){
            type = '2';
            _senddata += '&type='+type+'&jgfs='+$('#hdfs').val() + '&cpcc=' +$('#hdchicun').val();

        }else if(ptype == 'fmopt'){
            type = '4';
            _senddata +='&type=' +type+'&jgfs=' +$('#fmfs').val() + '&cpcc=' + $('#fmchicun').val() +'&jldw=p';
        }


        $.ajax({

            type:"GET",
            url:'getprice.php',
//            dataType:'json',
            data:_senddata,
            success:function(data){

                if(type=='1'){$('#jg').val(data)}
                if(type=='2'){$('#hdjg').val(data)}
                if(type=='4'){$('#fmjg').val(data)}
            },
            error:function(){

                alert('获取价格失败');
            }
        });

    }
    $('#options').on('change','.setjg',function(){

        var _this = $(this);
        setjg(_this);
    });
    $('#options').on('blur','.setjgblur',function(){

        var _this = $(this);
        setjg(_this);
    });
    $('#hdfm_outter').on('change','.setjg',function(){

        var _this = $(this);
        setjg(_this);
    });

    //    自带纸选择印件名
    $('#material').on('change',function(){

        var _text = $(this).find('option:selected').html();

        if(_text.indexOf('自带') >=0){

            $('#pnameoutter').show();

        }else{
            $('#pnameoutter').hide();
        }
    });

    $('#savemx').on('click',function(){

        $('#savemx_notice').children('span').hide();
        var _machine = $('#machine').val();
        var _material = $('#material').val();
        var _dsm = $('#dsm').val();
        var _pnum = Number($('#pnum').val());
        var _sl = $('#sl').val();
        var _mxid = $('#mxid').val();
        var _jg = Number($('#jg').val());


        var _senddata = "savemx=" + _mxid + "&machine=" + _machine + "&material=" + _material + "&dsm=" + _dsm + "&pnum=" + _pnum + "&sl="+ _sl  + "&jg1=" + _jg;

        $.ajax({

            type:"GET",
            dataType:'json',
            data:_senddata,
            success:function(data){

                $('#savemx_notice').find('.succinfo').show();
                $('#savemx_notice').find('.failinfo').hide();

//                订单金额更新
                $('#ddje').html(data.dje);

            },
            fail:function(){
                $('#savemx_notice').find('.succinfo').hide();
                $('#savemx_notice').find('.failinfo').show();
            }
        });

    });

    $(document).ready(
        function(){
            $('#machine').trigger('change');
        }
    );
</script>


</html>
