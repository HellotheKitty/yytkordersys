<?
require("../../inc/conn.php");
require("../../commonfile/log.php");
require("../../commonfile/https.php");
require("../../commonfile/wechat_notice_class.php");

?>
<? session_start();
if ($_SESSION["OK"] <> "OK") {
    session_unset();
    session_destroy();
    echo "<script>{windows.location.href='../../error.php';}</script>";
    exit;
}

?>
<?
header("Content-type:text/html;charset=utf-8");
$dwdm = substr($_SESSION["GDWDM"], 0, 4);
$localfileurl = "http://192.168.1.71:99";
$log = new Log();
$log1 = new Log();
//订单结算
if ($_POST["button3"] ==2) {
    $je = $_POST["je"];
    $skfs = $_POST["butt"];
    $skbz = $_POST["skbz"];

    beginTransaction();

    $_skinfo = mysql_query("select id from order_zh where zy='订单结算' and ddh='" . $_POST["ddh"] . "'", $conn);


    if (!mysql_fetch_array($_skinfo)) {
        $ressk3[]= mysql_query("update order_mainqt set state='待配送',skbz='$skbz',sdate=now() where ddh='" . $_POST["ddh"] . "'");
        if ($_POST["butt"] == "预存扣款")
            $ressk3[]=mysql_query("insert into order_zh select 0,'$skfs',now(),0,$je,'订单结算',skbz,now(),khmc,ddh from order_mainqt where ddh='" . $_POST["ddh"] . "'");
        else
            $ressk3[]=mysql_query("insert into order_zh select 0,'$skfs',now(),$je,$je,'订单结算',skbz,now(),khmc,ddh from order_mainqt where ddh='" . $_POST["ddh"] . "'");
    }

    if(!transaction($ressk3)){
        $arr['info'] ="收款fail";
        echo json_encode($arr);
        exit;
    }

//    header("location:NS_new.php?ddh=" . $_POST["ddh"]);
    $arr['info'] ="收款成功le";
    echo json_encode($arr);
    exit;
}


//收款完成，进入配送
if ($_POST['skfinish']) {
    mysql_query("update order_mainqt set state='待配送',skbz='$skbz',sdate=now() where ddh='" . $_POST["ddh"] . "'");
    header("location:NS_new.php?ddh=" . $_POST["ddh"]);
    exit;
}
//生产前订单结算
if ($_POST["button_sk"] <>'') {

    $je = $_POST["je"];
    $skfs = $_POST["butt"];
    $skbz = $_POST["skbz"];

    if($je == 0){
        $arr['info'] ="收款金额不能为0";
        echo json_encode($arr);
        exit;
    }

    beginTransaction();

    $_skinfo = mysql_query("select id from order_zh where zy='订单结算' and ddh='" . $_POST["ddh"] . "'", $conn);


    if (!mysql_fetch_array($_skinfo)) {
        $ressk2[]= mysql_query("update order_mainqt set skbz='$skbz',sdate=now() where ddh='" . $_POST["ddh"] . "'");
        if ($_POST["butt"] == "预存扣款")
            $ressk2[]=mysql_query("insert into order_zh select 0,'$skfs',now(),0,$je,'订单结算',skbz,now(),khmc,ddh from order_mainqt where ddh='" . $_POST["ddh"] . "'");
        else
            $ressk2[]= mysql_query("insert into order_zh select 0,'$skfs',now(),$je,$je,'订单结算',skbz,now(),khmc,ddh from order_mainqt where ddh='" . $_POST["ddh"] . "'");
    }

    if(!transaction($ressk2)){
        $arr['info'] ="收款failsk";
        echo json_encode($arr);
        exit;
    }else{


//    wechat notice guest
        $res_notice = mysql_query("select khmc,fssj from order_zh where ddh = '" . $_POST['ddh'] . "' and zy='订单结算'");

        if(mysql_num_rows($res_notice)>0){
            $touser = mysql_result($res_notice,0,'khmc');
            $ddate = mysql_result($res_notice,0,'fssj');
            $first = $touser . ',您的订单已结算，即将进入生产';
            $ye = '';
            $remark='感谢您的使用';
            $linkurl = 'http://59.110.17.13/ordersys/WXS/dd_list.php';
            wechat_notice::send_temp_balancesettle($touser,$first,$_POST['ddh'],$je,$ye,$ddate,$remark,$linkurl);

        }

        $arr['info'] ="收款成功";
        echo json_encode($arr);
        exit;
    }

}

//ajax查看明细价格是否有0 数量不为零单价为0的不能结算
if($_GET['iszeroprice']){

    $isZeroPrice = mysql_query("select mx.jg1,mx.sl1,mx.jg2,mx.sl2,hd.jg as hdjg,hd.sl as hdsl,fm.jg as fmjg , fm.sl as fmsl from order_mxqt mx left join order_mxqt_fm fm on mx.id = fm.mxid left join order_mxqt_hd hd on mx.id = hd.mxid where mx.ddh = '".$_GET["ddhiszero"]."'",$conn);

    while($item = mysql_fetch_array($isZeroPrice)){

        if($item['sl1']>0 && $item['jg1'] ==0){

            $arr['info'] ="构件一单价为0，不能结算!";
            echo json_encode($arr);
            exit();
        }elseif($item['sl2']>0 && $item['jg2'] ==0){

            $arr['info'] ="构件二单价为0，不能结算!";
            echo json_encode($arr);
            exit();

        }elseif($item['hdsl']>0 && $item['hdjg'] ==0){
            $arr['info'] ="后加工单价为0，确定结算？";
            $arr['jgtype']='hd';
            echo json_encode($arr);
            exit();

        }elseif($item['fmsl']>0 && $item['fmjg'] ==0){
            $arr['info'] ="覆膜单价为0，确定结算？";
            $arr['jgtype']='fm';
            echo json_encode($arr);
            exit();

        }

        $arr['info'] ="succ";
        echo json_encode($arr);
        exit();
    }
}

//预付定金，需要担保人签字
if ($_POST['button_qz'] <> '') {


    mysql_query("update order_mainqt set needsign = '1' WHERE ddh = '" . $_POST['ddh'] . "'", $conn);
//    echo "<script type='text/javascript'>alert('操作成功，请担保人到生产单上签字;');/script>";

//    $log -> INFO('|担保下单-订单号'. $_POST["ddh"] . '操作人:' .$_SESSION["YKUSERNAME"]."\r\n");

    header("location:NS_new.php?ddh=" . $_POST["ddh"]);
    exit();
}
//取消订金签字
if ($_POST['button_qxqz'] <> '') {
    mysql_query("update order_mainqt set needsign = null WHERE ddh = '" . $_POST['ddh'] . "'", $conn);
//    echo "<script type='text/javascript'>alert('取消成功;');/script>";

//    $log -> INFO('|取消担保下单-订单号'. $_POST["ddh"] . '操作人:' .$_SESSION["YKUSERNAME"]."\r\n");

    header("location:NS_new.php?ddh=" . $_POST["ddh"]);
    exit();
}
//重新收款
if ($_POST["button_0"] <> "") {

    $ddh = $_POST["ddh"];

    $res_ddxq = mysql_query("select khmc,dje,state from order_mainqt where ddh=$ddh" ,$conn);
    $khmc = mysql_result($res_ddxq, 0 ,'khmc');
    $state = mysql_result($res_ddxq,0,'state');


    $res_ddxq2 =mysql_query("select jf,df,fssj from order_zh where ddh='$ddh' and zy='订单结算'",$conn);
    if(mysql_num_rows($res_ddxq2)>0){

        $jf = floatval(mysql_result($res_ddxq2,0,'jf'));
        $df = floatval(mysql_result($res_ddxq2,0,'df'));
        $sk_fssj = mysql_result($res_ddxq2 ,0 , 'fssj');

    }else{

        $jf = 0;
        $df = 0;
        $sk_fssj = '2088-01-01';
//        $arr['info'] ="未收款";
//        echo json_encode($arr);
//        exit();
    }

    beginTransaction();

    $ressk_0[]= mysql_query("delete from order_zh where ddh='$ddh' and zy='订单结算' ", $conn);

//    更新账户余额
    $res_khye = mysql_query("select id,sdate from kh_ye where depart = '$khmc' ",$conn);

    if (mysql_num_rows($res_khye) > 0) {

        $ye_sdate = mysql_result($res_khye, 0, 'sdate');

        if(strtotime($ye_sdate) > strtotime($sk_fssj)) {

            $ressk_0[] = mysql_query("update kh_ye set ye = ( ye + $df - $jf )  where depart = '$khmc' ", $conn);

        }

    } else {

        if(strtotime($ye_sdate) > strtotime($sk_fssj)) {

            $ressk_0[] = mysql_query("insert into kh_ye (id,zh,xm,mobile,depart,ye,xsbh,sdate) values (0,0,0,0,'$khmc', $df , 0,now())", $conn);

        }
    }


    if($state == '待配送' || $state=='订单完成'|| $state=='已发货'|| $state=='已打印'){

        $ressk_0[]=mysql_query("update order_mainqt set state='待结算',skbz='',sjpsfs=NULL,sjpssj=NULL where ddh='$ddh' ", $conn);

    }
//    是否合并收款
//    $rsu= mysql_query("select id,ddhs from union_sk where locate('$ddh',ddhs)>0",$conn);
    $rsu= mysql_query("select id,ddh from union_sk where ddh=$ddh ",$conn);


    if(mysql_num_rows($rsu)>0){

//        $changeskid = mysql_result($rsu,0,'id');
//        $delddhitem = $ddh.',';
//        $ddhs = mysql_result($rsu,0,'ddhs');
//        $newddhstr = str_replace($delddhitem,'',$ddhs);
//        $ressk_0[]=mysql_query("update union_sk set ddhs='$newddhstr' where id=$changeskid",$conn);

          $ressk_0[]=mysql_query("delete from union_sk where ddh=$ddh",$conn);

    }

    if(!transaction($ressk_0)){
        $arr['info'] ="收款fail_r0";
        echo json_encode($arr);
        exit;
    }
    $arr['info'] ="重新收款成功0";
    echo json_encode($arr);

    $log1 ->INFO('|生产后重新收款-订单号:' . $ddh . '，操作人:' . $_SESSION["YKUSERNAME"]."\r\n");
//    header("location:NS_new.php?ddh=" . $_POST["ddh"]);
    exit;
}
//生产前重新收款
if ($_POST["button_re"] <> "") {
    $ddh = $_POST["ddh"];

    $res_ddxq = mysql_query("select khmc,dje,state from order_mainqt where ddh=$ddh" ,$conn);
    $khmc = mysql_result($res_ddxq, 0 ,'khmc');
    $state = mysql_result($res_ddxq,0,'state');

    $res_ddxq2 =mysql_query("select jf,df,fssj from order_zh where ddh='$ddh' and zy='订单结算'",$conn);

    if(mysql_num_rows($res_ddxq2)>0){

        $jf = floatval(mysql_result($res_ddxq2,0,'jf'));
        $df = floatval(mysql_result($res_ddxq2,0,'df'));
        $sk_fssj = mysql_result($res_ddxq2 ,0 , 'fssj');

    }else{
        $jf=0;
        $df=0;
        $sk_fssj = '2088-01-01';
    }

    beginTransaction();
    $ressk_re[]=mysql_query("delete from order_zh where ddh='$ddh' and zy='订单结算' ", $conn);
    $ressk_re[]=mysql_query("update order_mainqt set skbz='',sjpsfs=NULL,sjpssj=NULL where ddh='$ddh' ", $conn);

    //    更新账户余额
    $res_khye = mysql_query("select id,sdate from kh_ye where depart = '$khmc' ",$conn);

    $ye_sdate = mysql_result($res_khye, 0, 'sdate');

    if (mysql_num_rows($res_khye) > 0) {

        if(strtotime($ye_sdate) > strtotime($sk_fssj)) {

            $ressk_re[] = mysql_query("update kh_ye set ye = ( ye + $df - $jf )  where depart = '$khmc' ", $conn);

        }

    } else {

        if(strtotime($ye_sdate) > strtotime($sk_fssj)) {

            $ressk_re[] = mysql_query("insert into kh_ye (id,zh,xm,mobile,depart,ye,xsbh,sdate) values (0,0,0,0,'$khmc', $df , 0,now())", $conn);

        }
    }


    //    是否合并收款
    $rsu= mysql_query("select id,ddh from union_sk where ddh=$ddh ",$conn);

    if(mysql_num_rows($rsu)>0){
        $ressk_re[]=mysql_query("delete from union_sk where ddh=$ddh",$conn);

    }

//    header("location:NS_new.php?ddh=" . $_POST["ddh"]);
    if(!transaction($ressk_re)){
        $arr['info'] ="收款fail";
        echo json_encode($arr);
        exit;
    }
    $arr['info'] ="重新收款成功";
    echo json_encode($arr);
    $log1 ->INFO('|生产前重新收款-订单号:' . $ddh . '，操作人:' . $_SESSION["YKUSERNAME"]."\r\n");

    exit;
}

//ajax确定生产jdf
if ($_GET["did"]<>"") {

    //	已收款确定生产
    $sfsk = mysql_query("select id from order_zh WHERE ddh = '".$_GET['did']."' and zy='订单结算'",$conn);
    $sfqz = mysql_query("select needsign from order_mainqt WHERE ddh ='".$_GET['did']."'");
    if(mysql_num_rows($sfsk)<>0 || mysql_result($sfqz,0,'needsign')=='1'){
        mysql_query("update order_mainqt set state='进入生产',sdate=now() where ddh='".$_GET["did"]."'");

        $arr['info'] ="succ";

        if( $_SESSION['GDWDM']=='340500'){
            include 'make_jdf.php';
            makejdf($_GET['did'],$conn);
        }

        echo json_encode($arr);
        exit();
    }else{
        $arr['info'] ="nocharge";
        $arr['message'] = '未收款订单不能进入生产';
        echo json_encode($arr);
        exit;
    }

}

//save dd
if ($_POST["button"] <> "") {
    $bzyq = $_POST["bzyq"];
    $kpyq = $_POST["kpyq"];
    $memo = $_POST["memo"];
    $scqkbz = $_POST["scqkbz"];
    $scjd = $_POST["scjd"];
    $khmc = $_POST["khmc"];
    $skfs = $_POST["butt2"];
	if ($khmc=='') {
		$arr['info'] ="nouser";
        $arr['message'] = '客户名称不能为空';
        echo json_encode($arr);
        exit;
	}
    mysql_query("update order_mainqt set khmc='$khmc',psfs='" . $_POST["psfs"] . "',kdje='" . $_POST["kdje"] . "',shr='" . $_POST["shr"] . "',shdh='" . $_POST["shdh"] . "',shdz='" . $_POST["shdz"] . "',scjd='$scjd',bzyq='$bzyq',kpyq='$kpyq',memo='$memo',scqkbz='$scqkbz',yqwctime='" . $_POST["yqwctime"] . "',djje='" . $_POST["djje"] . "',kpyq='" . $_POST["butt2"] . "' where ddh='" . $_POST["ddh"] . "'", $conn);

    if(floatval($_POST['djje']) >0){

        mysql_query("delete from order_zh where ddh='" . $_POST["ddh"] . "'");

        if ($skfs == "预存扣款")
            mysql_query("insert into order_zh select 0,kpyq,now(),0,djje,'订单订金',memo,now(),khmc,ddh from order_mainqt where ddh='" . $_POST["ddh"] . "' and djje>0");
        else
            mysql_query("insert into order_zh select 0,kpyq,now(),djje,djje,'订单订金',memo,now(),khmc,ddh from order_mainqt where ddh='" . $_POST["ddh"] . "' and djje>0");

    }else{

        mysql_query("delete from order_zh where ddh='" . $_POST["ddh"] . "' and zy = '订单订金'");

    }

//    发货信息保存在fh_info表里面
    if( $_POST['fhr'] <>'' ||  $_POST['fhlxdh']<>'' ||  $_POST['fhdz'] <>''){

        mysql_query("delete from fh_info where ddh = '" . $_POST['ddh'] . "'" ,$conn);

        mysql_query("insert into fh_info (id,ddh,fhr,fhlxdh,fhdz) VALUES (0, '" . $_POST["ddh"] . "' , '" . $_POST['fhr'] . "','" . $_POST['fhlxdh'] . "' , '" . $_POST['fhdz'] . "' )" ,$conn);

    }

    header("location:NS_new.php?ddh=" . $_POST["ddh"]);
    exit;
}

if ($_GET["deleid"] <> "") {

    $ddh = $_GET["ddh"];
    //    已收款
    $issk = mysql_query("select id from order_zh where ddh=$ddh and zy='订单结算'",$conn);

    if(mysql_num_rows($issk)==0){

        mysql_query("delete from order_mxqt_hd where mxid='" . $_GET["deleid"] . "'", $conn);
        mysql_query("delete from order_mxqt_fm where mxid='" . $_GET["deleid"] . "'", $conn);
        mysql_query("delete from order_mxqt where id='" . $_GET["deleid"] . "'", $conn);
        $rs = mysql_query("select sum(jg1*pnum1*sl1+jg2*pnum2*sl2) from order_mxqt mx where mx.ddh='$ddh'", $conn);
        $rshd = mysql_query("select sum(jg*sl) from order_mxqt_hd where mxid in (select id from order_mxqt where ddh='$ddh')", $conn);

        $rsfm = mysql_query("select sum(jg*sl) from order_mxqt_fm where mxid in (select id from order_mxqt where ddh='$ddh')", $conn);


        mysql_query("update order_mainqt set dje=" . (mysql_result($rs, 0, 0) + mysql_result($rshd, 0, 0) + mysql_result($rsfm, 0, 0)) . " where ddh='$ddh'", $conn);
        if($_SESSION['FBCW']=='1'){
            mysql_query("update order_mainqt set memo = concat(memo,'|财务明细作废') where ddh='$ddh'",$conn);
        }
        header("location:NS_new.php?ddh=" . $ddh);
        exit;


    }else{
        echo "<script type='text/javascript'>alert('订单已收款，请点重新收款再操作！')</script>";
        echo "<script type='text/javascript'>window.close();</script>";

        exit;

    }
}


if ($_GET["BH6"] <> "") {
    mysql_query("update nc_erp.order_mxqt set file=replace(file,'" . $_GET["BH6"] . ";','') where id=" . $_GET["id"], $conn);
}

if ($_GET["ddh"] == "") {
    if ($_GET["taskid"] <> "") {
        $rss = mysql_query("select ddh from order_mainqt where xjdid=" . $_GET["taskid"], $conn);
        if (mysql_num_rows($rss) > 0) $bh = mysql_result($rss, 0, 0);
        else {
            $rss = mysql_query("select khmc,lxr,lxdh,lxdz,task_list.taskdescribe from base_kh,task_list where task_list.fromuser=base_kh.mpzh and task_list.id=" . $_GET["taskid"], $conn);
            if (mysql_num_rows($rss) > 0) {
                $khmc = mysql_result($rss, 0, "khmc");
                $lxr = mysql_result($rss, 0, "lxr");
                $lxdh = mysql_result($rss, 0, "lxdh");
                $lxdz = mysql_result($rss, 0, "lxdz");
                $memo = mysql_result($rss, 0, "taskdescribe");
            }
        }
    }
    if ($bh == "") {  //没有订单 生成
        /*
            $bh=date("ymdhis",time()).rand(10,99)."5";
            $rs=mysql_query("select ddh from order_mainqt where ddh='".$bh."'",$conn);
            while (mysql_num_rows($rs)>0) {
                $bh=date("ymdhis",time()).rand(10,99)."5";
                $rs=mysql_query("select ddh from order_mainqt where ddh='".$bh."'",$conn);
            }
         */
        $t = $dwdm;
        if ($_GET["type"] == 'wx') {
            if (substr($dwdm, 0, 2) == '34') {
                $dwdm = '3405';
            }elseif(substr($dwdm,0,2) == '33'){
                $dwdm = '3301';
            }
        }
        $_ddh = mysql_query("select ddh from order_mainqt where zzfy='$dwdm' order by id desc limit 1", $conn);
        $_arr = mysql_fetch_array($_ddh);
        $_last = $_arr[0];
        if ($dwdm == '3301') {
            $_lastYM = substr($_last, 0, 6);
            $_nowYM = date("Ym", time());
            if ($_lastYM == $_nowYM)
                $bh = $_last + 1;
            else
                $bh = $_nowYM . "00001";
        } else {
            $_lastYM = substr($_last, 0, 4);//前四位
            $_nowYM = substr(date("Ym", time()), 2, 4);
            if ($_lastYM == $_nowYM)
                $bh = $_last + 1;
            else
                $bh = $_nowYM . substr($dwdm, 2, 2) . "00001";
        }
        $waixie = 0;
        if ($_GET["type"] == 'wx') $waixie = $t;//waixie保存新建订单的单位编号

        $xsbh = $_GET["xsbh"];
        if($_GET['copyddh']<>''){
//            是否是刷新页面已经copy
            $alreadycopy = mysql_query("select id from order_waixie where copyddh = '".$_GET['copyddh']."'");
            if(mysql_num_rows($alreadycopy)>0){
                echo "<script>alert('已经委托');</script>";
                exit();
            }

            //        外协单子客户名用门店外协
            $khmc = $_GET['wxdkhmc'];

            //委托生产保存到外协订单表
            $sqlcopy = "insert into order_waixie (ddh,copyddh,fssj) VALUES ('".$bh."','".$_GET['copyddh']."',now())";
            $rskhwx = mysql_query($sqlcopy,$conn);

//            门店外协客户资料
            if($_GET['wxdkhmc']<>''){
                $rswxkhinfo=mysql_query("select khmc,lxr,lxdh,lxdz,mpzh,qq from base_kh where khmc = '".$_GET['wxdkhmc']."'");
                $lxr = mysql_result($rswxkhinfo, 0, "lxr");
                $lxdh = mysql_result($rswxkhinfo, 0, "lxdh");
                $lxdz = mysql_result($rswxkhinfo, 0, "lxdz");
            }

//            拷贝备注
            $memo = mysql_result(mysql_query("select memo from order_mainqt where ddh= '".$_GET['copyddh']."'",$conn),0,'memo');

        }
		
        mysql_query("insert into order_mainqt (ddh,khmc,xsbh,ddate,state,sdate,shr,shdh,shdz,memo,scjd,xjdid,zzfy,waixie) values ('" . $bh . "','" . $khmc . "','" . $xsbh . "',now(),'新建订单',now(),'$lxr','$lxdh','$lxdz','$memo','" . $_SESSION["GSSDQ"] . "','" . $_GET["taskid"] . "','$dwdm','$waixie')", $conn);
        $dwdm = $t;
        if($_GET['copyddh']<>''){
            //            订单同步
                require("syncroorder.php");

        }
    }
} else $bh = $_GET["ddh"];


//$rs=mysql_query("select order_mainqt.*,order_zh.sksj,base_kh.lxr,lxdh from order_mainqt left join order_zh on order_zh.ddh=order_mainqt.ddh and order_zh.zy<>'订单订金' left join base_kh on order_mainqt.khmc=base_kh.khmc where  order_mainqt.ddh='".$bh."' and order_mainqt.zzfy='$dwdm'",$conn);
$rs = mysql_query("select order_mainqt.*,order_zh.sksj,order_zh.zy,order_zh.df , base_kh.lxr,lxdh from order_mainqt left join order_zh on order_zh.ddh=order_mainqt.ddh and order_zh.zy<>'订单订金' left join base_kh on order_mainqt.khmc=base_kh.khmc where  order_mainqt.ddh='" . $bh . "'", $conn);
$xjzje = mysql_result($rs, 0, "dje");
$state = mysql_result($rs, 0, "state");
//纸张表用到material1
//$rsmx=mysql_query("(select order_mxqt.*,m1.materialname mm1,m1.specs ms1,m2.materialname mm2,m2.specs ms2,group_concat(hd.jgfs) hd,sum(hd.jg*hd.sl) hdje,group_concat(hd.jg) hdjg from order_mxqt left join order_mxqt_hd hd on order_mxqt.id=hd.mxid,material m1,material m2 where ddh='".$bh."' and m1.materialcode=paper1 and m2.materialcode=paper2 group by order_mxqt.id) union (select order_mxqt.*,m1.materialname mm1,m1.specs ms1,m2.materialname mm2,m2.specs ms2,group_concat(hd.jgfs) hd,sum(hd.jg*hd.sl) hdje,group_concat(hd.jg) hdjg from order_mxqt left join order_mxqt_hd hd on order_mxqt.id=hd.mxid,material1 m1,material1 m2 where ddh='".$bh."' and m1.materialcode=paper1 and m2.materialcode=paper2 group by order_mxqt.id)",$conn);
//只用到新的material表

//$rsmx=mysql_query("select order_mxqt.*,m1.materialname mm1,m1.specs ms1,m2.materialname mm2,m2.specs ms2,group_concat(hd.jgfs) hd,sum(hd.jg*hd.sl) hdje,group_concat(hd.jg) hdjg from order_mxqt left join order_mxqt_hd hd on order_mxqt.id=hd.mxid,material m1,material m2 where ddh='".$bh."' and m1.materialcode=paper1 and m2.materialcode=paper2 group by order_mxqt.id",$conn);

//$rsmx=mysql_query("select order_mxqt.*,m1.materialname mm1,m1.specs ms1,m2.materialname mm2,m2.specs ms2,group_concat(hd.jgfs) hd,sum(hd.jg*hd.sl) hdje,group_concat(hd.jg) hdjg, group_concat(fm.fmfs) fm,sum(fm.jg*fm.sl) fmje,group_concat(fm.jg) fmjg from order_mxqt left join order_mxqt_hd hd on order_mxqt.id=hd.mxid left join order_mxqt_fm fm on order_mxqt.id=fm.mxid,material m1,material m2 where ddh='".$bh."' and m1.materialcode=paper1 and m2.materialcode=paper2 group by order_mxqt.id",$conn);

$rsmx = mysql_query("select order_mxqt.*,m1.materialname mm1,m1.specs ms1,m2.materialname mm2,m2.specs ms2,group_concat(hd.jgfs) hd,sum(hd.jg*hd.sl) hdje,group_concat(hd.jg) hdjg, group_concat(fm.fmfs) fmfs,sum(fm.jg*fm.sl) fmje,group_concat(fm.jg) fmjg from order_mxqt left join order_mxqt_hd hd on order_mxqt.id=hd.mxid left join order_mxqt_fm fm on order_mxqt.id=fm.mxid LEFT JOIN material m1 on m1.id=paper1 LEFT JOIN material m2 on  m2.id=paper2 where order_mxqt.ddh='" . $bh . "' group by order_mxqt.id", $conn);


$rskh = mysql_query("select * from order_mxqt where ddh='" . $bh . "' and (zj is null or zj=0)", $conn);
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>订单信息</title>
    <!--<SCRIPT language=JavaScript src="../form.js"></SCRIPT>-->
    <SCRIPT language=JavaScript src="../../js/jquery-1.8.3.min.js"></SCRIPT>
    <script language="JavaScript">
        <!--
        <?php
            $_checkVal = 15;
        ?>
        var ff = false;
        function suredo(src, q) {

            if (ff) {
                return;
            }
            var ret;
            var flag = false;
            ret = confirm(q);
            if (ret != false) {
                 if (src.indexOf('did') == <?php echo $_checkVal;?>) {
                    ff = true;
                    ddh = src.substr(19, 11);
//                    console.log("http://192.168.1.71:88/skyserver/isfileexists.php?ddh=" + ddh);

                    flag = true;

                    $.ajax({
                        type: "GET",
//                        url: "http://192.168.1.130/isfileexists.php?ddh=" + ddh,
                        url: "http://192.168.1.71:88/skyserver/copyfiles.php?ddh=" + ddh,
                        dataType: "jsonp",
                        jsonp: "jsoncallback",
                        async: false,
                        success: function (data) {
                            console.log(data);
                            if (data.error == 1) {
                                window.location = src;

                            }
                        }
                    });
//
                }

                window.location = src;
            }
        }

//        jdf test
        function suredo_new(src, q,_this) {
            _this.hide();

            if (ff) {
                return;
            }
            var ret;
            var flag = false;
            ret = confirm(q);
            if (ret != false) {

                if (src.indexOf('did') == <?php echo $_checkVal;?>) {
                    ff = true;
                    ddh = src.substr(19, 11);

                    flag = true;

//                    $.ajax({
//                        type: "GET",
//                        url: "http://192.168.1.71:88/skyserver/make_jdf.php?ddh=" + ddh,
//                        dataType: "jsonp",
//                        jsonp: "jsoncallback",
//                        async: false,
//                        success: function (data) {
//
////                            alert('copy succ');
//
//                        },
//                        error: function () {
//                            alert("error, plz retry.==");
//                        }
//                    });
//                    window.location = src;

                    $.ajax({
                        type:"GET",
                        dataType:"json",
                        url:"NS_new.php?did="+ddh,
                        success:function(data){
                            if(data.info=='nocharge'){
                                alert(data.message);
                                _this.show();
                            }else if(data.info=='succ'){

//                                window.open("http://192.168.1.71:88/skyserver/make_jdf.php?ddh="+ddh ,'newwindow',"toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,copyhistory=yes,width=100,height=100,left=400,top=150");

//                                $('#openwin').submit();
                                <? if($_SESSION['GDWDM'] == '330100'){
                                ?>
                                window.location = "http://192.168.1.71:88/skyserver/make_jdf.php?ddh="+ddh;
                                <?
                                } ?>
                            }
                        }
                    });

                    if (flag) {

                    } else {

                        return 0;
                    }
                }

//                window.location = src;
            }
        }
        //        jdf test
        $(document).on('click','#suredo_new',function(){
            var _this = $(this);
            _this.val('处理中');
            _this.attr('disabled','disabled');
            var _ddh = form1.ddh.value;
            $.ajax({
                type:"GET",
                dataType:"json",
                url:"NS_new.php?did="+_ddh,
                success:function(data){
                    if(data.info=='nocharge'){
                        alert(data.message);
                        _this.hide();
                    }else if(data.info=='succ'){

//                                window.open("http://192.168.1.71:88/skyserver/make_jdf.php?ddh="+ddh ,'newwindow',"toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,copyhistory=yes,width=100,height=100,left=400,top=150");

//                                $('#openwin').submit();
                        <? if($_SESSION['GDWDM'] == '330100' and !empty(mysql_result($rs,0,'filefy'))){
                        ?>
                        window.location = "http://192.168.1.71:88/skyserver/make_jdf.php?ddh="+_ddh;
                        <?
                        }else{
                        ?>
                        window.location.href = '?ddh=' + _ddh;
                        <?
                        } ?>

                    }
                }
            });
//            suredo_new('YSXMqt_del.php?did=' +form1.ddh.value+ '&jdf=1&lx=new','确定生产后不能修改数据',_this);
        });
//        end jdf test
//        结算单价为零提示
        function checkform(){

//            单价为零结算提示
            var _self = $('#button_sk');
            _self.val('处理中');
            _self.attr('disabled','disabled');
            var _senddata = 'ddhiszero='+ form1.ddh.value +'&iszeroprice=1';
            $.ajax({
                type:'GET',
                dataType:'json',
                data:_senddata,
                success:function(data){

                    var _ignzero = false;
                    if(data.info != 'succ'){
                        if(data.jgtype =='hd'||data.jgtype =='fm'){

                            _ignzero = confirm(data.info);
                        }else{
                            alert(data.info);
                            _ignzero = false;
                        }
                    }else{
                        _ignzero = true;
                    }
                    if(_ignzero){

                        var _je = form1.je.value;
                        var _butt = form1.butt.value;
                        var _skbz= form1.skbz.value;
                        var _ddh = form1.ddh.value;

                        var _senddata1 = {ddh:_ddh,je:_je,button_sk:1,butt:_butt,skbz:_skbz};
                        $.ajax({
                            type:'POST',
                            url:'NS_new.php',
                            dataType:'json',
                            data:_senddata1,
                            success:function(data){
                                alert(data.info);
                                window.location.reload();
                            },
                            error:function(){
                                alert('收款失败，请重新操作');
                            }
                        });
                    }
                },
                error:function(){
                    alert("error, plz retry.==");
                }
            });
        }
        function checkform_sh(){

//            单价为零结算提示

            var _senddata = 'ddhiszero='+ form1.ddh.value +'&iszeroprice=1';
            $.ajax({
                type:'GET',
                dataType:'json',
                data:_senddata,
                success:function(data){
                    if(data.info != 'succ'){
                        if(data.jgtype =='hd'||data.jgtype =='fm'){

                            var _ignzero = confirm(data.info);
                        }else{
                            alert(data.info);
                            var _ignzero = false;
                        }
                    }else{
                        var _ignzero = true;
                    }
                    if(_ignzero){

                        var _je = form1.je.value;
                        var _butt = form1.butt.value;
                        var _skbz= form1.skbz.value;
                        var _ddh = form1.ddh.value;

                        var _senddata1 = {ddh:_ddh,je:_je,button3:2,butt:_butt,skbz:_skbz};
                        $.ajax({
                            type:'POST',
                            url:'NS_new.php',
                            dataType:'json',
                            data:_senddata1,
                            success:function(data){

                                alert(data.info);
                                window.location.reload();

                            },
                            error:function(){
                                alert('收款失败，请重新操作');
                            }
                        });
                    }
                },
                error:function(){
                    alert("error, plz retry.==");
                }


            });
        }

        function checkform_re(){

//            重新收款
            var _ddh = form1.ddh.value;

            var _senddata = {ddh:_ddh,button_re:1};

            $.ajax({
                type:'POST',
                url:'NS_new.php',
                dataType:'json',
                data:_senddata,
                success:function(data){

                    alert(data.info);
                    window.location.reload();

                },
                error:function(){
                    alert('操作失败，请重新操作');
                }

            });

        }

        function checkform_re_0(){

//            重新收款
            var _ddh = form1.ddh.value;

            var _senddata = {ddh:_ddh,button_0:1};

            $.ajax({
                type:'POST',
                url:'NS_new.php',
                dataType:'json',
                data:_senddata,
                success:function(data){

                    alert(data.info);
                    window.location.reload();

                },
                error:function(){
                    alert('操作失败，请重新操作');
                }


            });

        }
        //-->
        // 判断订单总金额是否超过客户的余额
        // 如果余额不足，无法提交生产
        function submitprint(ddh) {
            if (form1.scjd.value == '' || form1.khmc.value == '') {
                alert('客户名称和生产地不能为空，请输入并保存！');
                return false;
            }
            isok = 1;
            $.ajax({
                type: 'get',
                url: 'getprice.php?type=3&ddh=' + ddh,
                async: false,
                success: function (data) {
                    if (data != "1")
                        alert(data);
                    isok = 0;
                },
                error: function () {
                    isok = 2;
                }
            });
            if (isok == 0) {
                return false;
            } else if (isok == 2) {
                alert("发生错误，请重试。");
                return false;
            }
            if (confirm("确定提交生产？")) {
                window.location = "YSXMqt_del.php?lx=new&BH2=" + ddh;
            }
//	alert(ddh);

//	echo "<a href='#' onClick=\"javascript:if (form1.scjd.value=='' || form1.khmc.value=='') {alert('客户名称和生产地不能为空，请输入并保存！');return false;} else suredo('YSXMqt_del.php?lx=new&BH2=".mysql_result($rs,0,"ddh")."','确定提交生产?');\">【提交生产】</a>&nbsp;&nbsp;";
        }
    </script>
    <style type="text/css">
        <!--
        body {
            background-color: #A5CBF7;
        }

        .style11 {
            font-size: 14px
        }

        .STYLE13 {
            font-size: 12px
        }

        -->
    </style>
</head>

<body>
<form name='form1' method="post" action="#">
    <input type="hidden" name="ddh" value="<? echo $bh ?>">
    <table width="100%" border="1" cellpadding="4" cellspacing="1" bordercolor="#000099" bgcolor="#f6f6f6">
        <tr>
            <td height="222" valign="top">
                <table width="80%" border="1" align="center" cellspacing="0" cellpadding="0"
                       style="border-width:0px;border-collapse:collapse;border:1px solid #D3D3D3; border-bottom:1px solid #D3D3D3; font-size:12px">
                    <tr>
                        <td height="28" class="STYLE11" width="10%" align="center">订单编号</td>
                        <td width="32%" class="STYLE13">
                            <? echo mysql_result($rs, 0, "ddh");

//                            已经委托不用打印
                            $iswaixie = mysql_query("select id from order_waixie where copyddh = '$bh'");
                            if(mysql_num_rows($iswaixie)>0){
                                echo '<span style="color:#FA8072;">已经委托中心店生产</span>';
                            }

                            ?>
                        </td>
                        <td width="23%" align="left" class="STYLE11">下单时间：<span class="STYLE13"><? echo mysql_result($rs, 0, "ddate"); ?></span></td>
                        <td width="35%" align="left" class="STYLE11">要求完成：<span class="STYLE13">
                <input name="yqwctime" type="text" value="<? echo mysql_result($rs, 0, "yqwctime") == "" ? date("Y-m-d H:i:s", strtotime("+1 day", strtotime(mysql_result($rs, 0, "ddate")))) : mysql_result($rs, 0, "yqwctime") ?>"
                       size="14" <? if ((mysql_result($rs, 0, "state") != "新建订单" and $_SESSION["FBSD"] != "1") or mysql_result($rs, 0, "state") == "进入生产") echo "readonly" ?>>
              </span></td>
                    </tr>
                    <tr>
                        <td height="24" class="STYLE11" align="center">客户名称</td>
                        <td colspan="2" class="STYLE13"><? if ((mysql_result($rs, 0, "state") == "新建订单" or $_SESSION["FBSD"] == "1") and mysql_result($rs, 0, "state") != "进入生产") { ?>
                                <input name="khmc" type="text"
                                       value="<? echo mysql_result($rs, 0, "khmc") == "" ? $khmc : mysql_result($rs, 0, "khmc") ?>"
                                       size="20" readonly><input name="zxx6" type="button" value="选择"
                                                                 onClick="window.open('KH_select.php','actSwfUploadOpenWin1','dependent, toolbar=no,location=no,status=no,menubar=no,resizable=yes,scrollbars=auto,width=700,height=480,left=335.0,top=242.0'); return false">

                            <?
                            } else { ?>
                                <input name="khmc" type="text" value="<? echo mysql_result($rs, 0, "khmc") == "" ? $khmc : mysql_result($rs, 0, "khmc") ?>" size="20" readonly>
                                <? echo ' 联系人：', mysql_result($rs, 0, "lxr"), '/', mysql_result($rs, 0, "lxdh");
                            } ?> </td>
                        <td class="STYLE13"><font color="red">账户余额：</font><?
                            $khmc = mysql_result($rs, 0, "khmc");

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

                           /* $rsye = mysql_query("select ye from user_zhjf where depart='$khmc'");
                            if ($rsye && mysql_num_rows($rsye) > 0) {
                                $yue = mysql_result($rsye, 0, "ye");
                                echo $yue . "元";
                            } else
                                echo "0元";*/
                            ?></td>
                    </tr>
                    <tr>
                        <td height="24" class="STYLE11" align="center">订单金额</td>
                        <td colspan="2" class="STYLE13"><input id="ddzjet" name="dje" type="text"
                                                               value="<? echo mysql_result($rs, 0, "dje") ?>"
                                                               size="6" readonly>
                            元， 配送费:<input name="kdje" type="text" value="<? echo mysql_result($rs, 0, "kdje") ?>"
                                          size="6" <? if ((mysql_result($rs, 0, "state") != "新建订单" and $_SESSION["FBSD"] != "1") or mysql_result($rs, 0, "state") == "进入生产") echo "readonly" ?>>
                            元。<? echo "合计：", mysql_result($rs, 0, "dje") + mysql_result($rs, 0, "kdje"), "元。"; ?></td>
                        <td class="STYLE13">订金：
                            <input name="djje" type="text" readonly value="<? echo mysql_result($rs, 0, "djje") ?>"
                                   size="5" <? if ((mysql_result($rs, 0, "state") != "新建订单" and $_SESSION["FBSD"] != "1") or mysql_result($rs, 0, "state") == "进入生产") echo "readonly" ?>>
                            元,
                            <select name="butt2">
                                <? if ($yue > 0) { ?>
                                    <option value="预存扣款" <? echo mysql_result($rs, 0, "kpyq") == "预存扣款" ? "selected" : "" ?>>
                                        预存扣款
                                    </option>
                                <? } ?>
                                <option value="现金" <? echo mysql_result($rs, 0, "kpyq") == "现金" ? "selected" : "" ?>>
                                    现金
                                </option>
                                <option value="支付宝" <? echo mysql_result($rs, 0, "kpyq") == "支付宝" ? "selected" : "" ?>>
                                    支付宝
                                </option>
                                <option value="支票" <? echo mysql_result($rs, 0, "kpyq") == "支票" ? "selected" : "" ?>>
                                    支票
                                </option>
                                <option value="POS机招行" <? echo mysql_result($rs, 0, "kpyq") == "POS机招行" ? "selected" : "" ?>>
                                    POS机招行
                                </option>
                                <option value="汇款" <? echo mysql_result($rs, 0, "kpyq") == "汇款" ? "selected" : "" ?>>
                                    汇款
                                </option>


                            </select></td>
                    </tr>
                    <tr>
                        <td height="24" class="STYLE11" align="center">配送信息</td>
                        <td colspan="3" class="STYLE13">
                            <span style="display: inline-block;width: 150px; float: left;margin-top: 5px;margin-left: 5px;">
                                 配送：
                            <select name="psfs" id="psfs">
                                <option value="上门自取" <? if (mysql_result($rs, 0, "psfs") == "上门自取") echo "selected"; ?>>
                                    上门自取
                                </option>
                                <option value="快递配送" <? if (mysql_result($rs, 0, "psfs") == "快递配送") echo "selected"; ?>>
                                    快递配送
                                </option>
                                <option value="物流配送" <? if (mysql_result($rs, 0, "psfs") == "物流配送") echo "selected"; ?>>
                                    物流配送
                                </option>
                                <option value="送货" <? if (mysql_result($rs, 0, "psfs") == "送货") echo "selected"; ?>>送货
                                </option>

                            </select>
                            </span>

                            <span style="display: inline-block; float: left;margin-top: 5px;margin-left: 5px;">
                                收货人：<input name="shr" type="text" value="<? echo mysql_result($rs, 0, "shr") == "" ? $lxr : mysql_result($rs, 0, "shr") ?>" size="6">&nbsp;&nbsp;
                                电话：<input name="shdh" type="text" value="<? echo mysql_result($rs, 0, "shdh") == "" ? $lxdh : mysql_result($rs, 0, "shdh") ?>" size="12">
                                收货地址：<input name="shdz" type="text" value="<? echo mysql_result($rs, 0, "shdz") == "" ? $lxdz : mysql_result($rs, 0, "shdz") ?>" size="35">

                            </span>
                            <!--<span  style="display: inline-block; float: left;margin-top: 5px;margin-left: 5px;">
                                <input id="show_fh_btn" type="button" value="填写发货信息" >
                            </span>-->
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
                        <td colspan="3" class="STYLE13"><textarea name="kpyq" cols="50" rows="3"><? echo mysql_result($rs, 0, "kpyq"); ?></textarea>
                        </td>
                    </tr>
                    <tr style=" display:none">
                        <td height="24" class="STYLE11" align="center">包装要求</td>
                        <td colspan="3" class="STYLE13"><textarea name="bzyq" cols="50" rows="3"><? echo mysql_result($rs, 0, "bzyq"); ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td height="24" class="STYLE11" align="center">订单备注</td>
                        <td colspan="3" class="STYLE13"><textarea name="memo" cols="50"
                                                                  rows="3"><? echo mysql_result($rs, 0, "memo"); ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td height="24" class="STYLE11" align="center">生产备注</td>
                        <td colspan="3" class="STYLE13"><textarea name="scqkbz" cols="50" rows="3"><? echo mysql_result($rs, 0, "scqkbz"); ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td height="24" class="STYLE11" align="center">生产地</td>
                        <td colspan="3" class="STYLE13"><input name="scjd" id="scjd" type="text" value="<? echo mysql_result($rs, 0, "scjd"); ?>" size="12" maxlength="4">
                            <? if (mysql_result($rs, 0, "state") != "订单完成" and mysql_result($rs,0,"state")!="作废订单"  and mysql_result($rs, 0, "state") != "待配送") { ?>
                                <input type="submit" name="button" id="button" value="保存"><? } ?>

                            <a href='void(0)' onclick='javascript:document.getElementById("scjd").value="上海";return false;'>上海</a>
                            <a href='void(0)' onclick='javascript:document.getElementById("scjd").value="北京";return false;'>北京</a>
                        </td>
                    </tr>
                    <tr>
                        <td height="34" colspan="3" align="left" valign="bottom"><font size="+0.5">
                                <? if (mysql_result($rs, 0, "state") == "新建订单") {
                                    echo "<a href='#' class='nav' onClick='javascript:window.open(\"YSXMqt_mxdjs.php?ddh=" . mysql_result($rs, 0, "ddh") . "\", \"HT_dhdj\", \"toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=yes,width=850,height=610,left=300,top=100\")'>【增加明细】</a>&nbsp;&nbsp;";

                                    echo "<a href='#' onClick=\"javascript:suredo('YSXMqt_del.php?lx=new&BH=" . mysql_result($rs, 0, "ddh") . "','确定删除本订单?');\">【删除订单】</a>&nbsp;&nbsp;";

                                    if (mysql_num_rows($rsmx) > 0) {
                                        //	判断余额的提交生产按钮。取消注释即可。
//                                        	echo "<a href='#' onclick=submitprint(".mysql_result($rs,0,"ddh").")>【测试按钮 勿点】</a>&nbsp;&nbsp;&nbsp;&nbsp;";
                                        echo "<a href='#' onClick=\"javascript:if (form1.scjd.value=='' || form1.khmc.value=='') {alert('客户名称和生产地不能为空，请输入并保存！');return false;} else suredo('YSXMqt_del.php?lx=new&BH2=" . mysql_result($rs, 0, "ddh") . "','确定提交生产?');\">【提交生产】</a>&nbsp;&nbsp;";
                                    }
                                }
                                if ( $_SESSION["FBSD"] == "1") {
                                    if(mysql_result($rs, 0, "state") == "待生产" ||mysql_result($rs, 0, "state") == "进入生产" ){
                                        if(mysql_result($rs, 0, "sksj")==''){

                                            echo "<a href='#' class='nav' onClick='javascript:window.open(\"YSXMqt_mxdjs.php?ddh=" . mysql_result($rs, 0, "ddh") . "\", \"HT_dhdj\", \"toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=yes,width=850,height=610,left=300,top=100\")'>【增加明细】</a>&nbsp;&nbsp;";
                                        }

                                    }
                                }
//                              是否收款
                                if(mysql_result($rs, 0, "state") <> "订单完成"){
                                    if (mysql_result($rs, 0, "sksj") <> '' && mysql_result($rs,0,'zy')=='订单结算'){
                                        echo "<span style='font-size:12px;color:#777;'> [已收款：", mysql_result($rs, 0, "sksj"), " <span ";
                                        if(mysql_result($rs, 0, "dje")<>mysql_result($rs, 0, "df")){
                                            echo "style='color:red' ";
                                        }
                                        echo ">实收金额:",mysql_result($rs, 0, "df"),"</span> ]</span>";
                                    }

                                }

                                if ($_SESSION["FBSD"] == "1" || $_SESSION['FBCW']) {
                                    if (mysql_result($rs, 0, "state") == '待生产') {
                                        if (mysql_result($rs, 0, "sksj") <> ''){}else {
                                            $yingshouje = floatval(mysql_result($rs, 0, "dje") + mysql_result($rs, 0, "kdje") - mysql_result($rs, 0, "djje"));
                                            ?>
                                            　　<br>收款金额：
                                            <input name="je" type="text" value="<? echo $yingshouje; ?>" size="5">元
                                            <select name="butt">
                                                <? if ($yue >= $yingshouje) { ?>
                                                    <option value= "预存扣款"> 预存扣款</option><? } ?>
                                                <option value= "现金"> 现金</option>
                                                <option value= "支票"> 支票</option>
                                                <option value= "POS机招行"> POS机招行</option>
                                                <option value= "汇款"> 汇款</option>
                                                <option value= "微信"> 微信</option>
                                            </select>
<!--                                            <input type="submit" name="button_sk" id="button_sk" value="收款" >-->
                                            <input type="button"  name="button_sk" id="button_sk" value="收款" onClick="checkform();">
                                            <?
                                            $rsisshowqz = mysql_query("select needsign from order_mainqt WHERE ddh ='" . $_GET['ddh'] . "'", $conn);
                                            $isshowqz = mysql_result($rsisshowqz, 0, 'needsign');
                                            if ($isshowqz == '1') {
                                                ?>
                                                <br>
                                                <span style="color:#f00;">请担保人到订单上签字</span>
                                                <input type="submit" name="button_qxqz" id="button_qxqz" value="取消担保">

                                                <?
                                            } else {

                                                if($_SESSION['YKOAUSER'] == 'ym' || $_SESSION['YKOAUSER'] == 'huangly' || $_SESSION['YKOAUSER'] == 'laoyue' || $_SESSION['YKOAUSER'] == 'wangying') {

                                                    ?>
                                                    <input type="submit" name="button_qz" id="button_qz" value="担保下单">
                                                    <?
                                                }
                                            }
                                            ?>
                                            <br>备注：<input type="text" name="skbz" id="skbz" value=""/>
                                            <?
                                        }
                                        echo "<br>";
                                        if(empty(mysql_result($rs,0,'filefy')) || !empty(strstr(mysql_result($rs ,0,'khmc') , '艾影像')) ){
                                            if($_SESSION['GDWDM'] <> '330100'){

                                                echo "<a href='#' onclick=suredo('YSXMqt_del.php?did=" . mysql_result($rs, 0, "ddh") . "&lx=new','确定生产后不能修改数据！')>【确定生产】</a>&nbsp;&nbsp;&nbsp;&nbsp;";

                                            }

                                        }

                                        if(!empty(mysql_result($rs,0,'filefy')) || $_SESSION['GDWDM'] == '330100'){
//                                            || $_SESSION['GDWDM'] == '330100'
                                            echo "<input type='button' class='jdf' id='suredo_new' value='确定生产'/>&nbsp;&nbsp;&nbsp;&nbsp;";

                                        }

                                        echo "<input type=button onclick='javascript:window.open(\"YSXMqt_tuihui.php?didth=" . mysql_result($rs, 0, "id") . "\", \"HT_dhdj\", \"toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=yes,width=650,height=340,left=300,top=100\");' value='退回'>";
                                    } elseif (mysql_result($rs, 0, "state") != '退回') {
                                        echo mysql_result($rs, 0, "state");
                                        if (mysql_result($rs, 0, "sjpsfs") != '') echo "[", mysql_result($rs, 0, "sjpsfs"), "]";
                                    }
                                    if (mysql_result($rs, 0, "state") == "待生产" && substr(mysql_result($rs, 0, "sksj"), 0, 10) == date("Y-m-d", time())) {
                                        ?>
                                        <input type="button" name="button_re" id="button_re" value="重新收款" onClick="checkform_re();"/>
                                        <?
                                    }
                                }
                                echo " </font>";


                                if (mysql_result($rs, 0, "state") == '待结算') {
                                    if (mysql_result($rs, 0, "sksj") <> ''&& mysql_result($rs,0,'zy')=='订单结算') {
                                        echo " [已收款：", mysql_result($rs, 0, "sksj"), "] ";
                                        echo "<input type='submit' name='skfinish' value='完成'>";
                                    } else { ?>
                                        　　收款金额：
                                        <input name="je" type="text"
                                               value="<? echo mysql_result($rs, 0, "dje") + mysql_result($rs, 0, "kdje") - mysql_result($rs, 0, "djje") ?>" size="5">元
                                        <select name="butt">
                                            <? if ($yue >= floatval(mysql_result($rs, 0, "dje") + mysql_result($rs, 0, "kdje") - mysql_result($rs, 0, "djje"))) { ?>
                                                <option value="预存扣款">预存扣款</option>
                                            <? } ?>
                                            <option value="现金">现金</option>
                                            <option value="支票">支票</option>
                                            <option value="POS机招行">POS机招行</option>
                                            <option value="汇款">汇款</option>
                                            <option value="微信">微信</option>
                                        </select>
<!--                                        ssssssss-->
<!--                                        <input type="submit" name="button2" id="button2" value="收款"/>-->
                                        <input type="button"  name="button3" id="button3" value="收款" onClick="checkform_sh();"><br>备注：<input type="text" name="skbz" id="skbz" value=""/>

                                        <?
                                    }
                                }

                                if ((mysql_result($rs, 0, "state") == "已打印"|| mysql_result($rs, 0, "state") == "待配送" ||  mysql_result($rs, 0, "state") == "已发货" || mysql_result($rs, 0, "state") == "订单完成")  && (substr(mysql_result($rs, 0, "sksj"), 0, 10) == date("Y-m-d", time()) || $_SESSION['YKOAUSER'] == 'yuexiaoniu' || $_SESSION['YKOAUSER'] == 'admin' || $_SESSION['YKOAUSER'] == 'ym' || $_SESSION['YKOAUSER'] == 'wangying')) {
//                                    && substr(mysql_result($rs, 0, "sksj"), 0, 10) == date("Y-m-d", time())
                                    ?>
                                    <input type="button" name="button_0" id="button_0" value="重新收款" onClick="checkform_re_0();"/>
                                    <?
                                }

                                if(($_SESSION['YKOAUSER'] == 'lixr' || $_SESSION['YKOAUSER'] == 'liusj' || $_SESSION['YKOAUSER'] == 'dongsh' || $_SESSION['YKOAUSER'] == 'ym') && mysql_result($rs , 0,'sdate') > date("Y-m-d H:i:s" , strtotime("-1 month"))){

                                ?>
                                    <input style="display: none;" type="button" name="button_0" id="button_0_re" value="!重新收款"  onclick="checkform_re_0();"/>

                                <?  }

//                                cw作废订单
                                if(($dwdm <> '3301' && ($_SESSION['FBCW'] == '1' || $_SESSION['FBSD'] == '1')) ||($dwdm == '3301' && $_SESSION['FBCW'] == '1' && $_SESSION['YKOAUSER'] != 'hudan' && $_SESSION['YKOAUSER'] != 'rff')){

                                    if((mysql_result($rs,0,'state') == '进入生产' ||mysql_result($rs,0,'state') == '已打印' || mysql_result($rs,0,'state') == '待结算' || mysql_result($rs,0,'state') == '已发货' ||  mysql_result($rs,0,'state') == '待配送') && ($dwdm=='3301' || $_SESSION['YKOAUSER'] == 'huangly'|| $_SESSION['YKOAUSER'] == 'sudan'|| $_SESSION['YKOAUSER'] == 'yuexiaoniu' || $_SESSION['YKOAUSER'] == 'wangying')){
                                        ?>
                                        <a href="javascript:void(0);" onClick="window.open('YSXMqt_tuihui.php?didzf=<? echo mysql_result($rs,0,'id'); ?>','','dependent, toolbar=no,location=no,status=no,menubar=no,resizable=yes,scrollbars=auto,width=700,height=480,left=335.0,top=242.0');">订单作废</a>
                                        <?
                                    }
                                }

//                                上海 工厂确认生产
                                if($_SESSION['GDWDM'] == '330100' && $_SESSION['FBPD']){

//                                    echo "<input type='button' class='jdf' id='suredo_new' value='确定生产'/>&nbsp;&nbsp;&nbsp;&nbsp;";
                                }
                                ?>

                        </td>
                        <td height="24"><span style="float:left;color:red"><? echo mysql_result($rs, 0, "state") ?></span><span style="float:right">明细合计金额：<span id="zje" style="color:#F00"></span>元</span></td>
                    </tr>
                </table>

            </td>
        </tr>
        <tr>
            <td>
                <table cellspacing="0" cellpadding="0" rules="all" border="1" id="gvOrder" style="border-width:0px;width:100%;border-collapse:collapse;border:1px solid #D3D3D3; border-bottom:1px solid #D3D3D3; font-size:12px">
                    <tbody>
                    <tr class="td_title" style="height:30px;">

                        <th width="132" align="center" scope="col">产品</th>
                        <th width="22" align="center" scope="col">规格</th>
                        <th width="35" align="center" scope="col">数量</th>
                        <th width="64" align="center" scope="col">构件1</th>
                        <th width="64" align="center" scope="col">生产文件</th>
                        <th width="93" align="center" scope="col">信息</th>
                        <th width="93" align="center" scope="col">单价</th>
                        <th width="64" align="center" scope="col">构件2</th>
                        <th width="64" align="center" scope="col">生产文件</th>
                        <th width="93" align="center" scope="col">信息</th>
                        <th width="93" align="center" scope="col">单价</th>
                        <th width="45" align="center" scope="col">金额</th>
                        <th width="93" align="center" scope="col">后加工方式</th>
                        <th width="93" align="center" scope="col">单价</th>
                        <th width="45" align="center" scope="col">加工金额</th>
                        <th width="93" align="center" scope="col">覆膜方式</th>
                        <th width="93" align="center" scope="col">单价</th>
                        <th width="45" align="center" scope="col">加工金额</th>
                    </tr>
                    <?
                    for ($i = 0; $i < mysql_num_rows($rsmx); $i++) { ?>
                        <tr class="td_title" style="height:30px;">

                            <td class="td_content" align="center"><? echo mysql_result($rsmx, $i, "productname");
                                if (($state == "新建订单") or ($_SESSION["FBCW"] == "1" and date('m', strtotime(mysql_result($rs, 0, "ddate"))) == date('m'))) echo "<a href='#' class='nav' onClick='javascript:window.open(\"YSXMqt_mxdjs.php?ddh=" . mysql_result($rs, 0, "ddh") . "&mxsid=" . mysql_result($rsmx, $i, "id") . "\", \"HT_dhdj\", \"toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=yes,width=850,height=610,left=300,top=100\")'> [修改]</a>"; else echo "<a href='#' class='nav' onClick='javascript:window.open(\"YSXMqt_mxdjs.php?lx=show&ddh=" . mysql_result($rs, 0, "ddh") . "&mxsid=" . mysql_result($rsmx, $i, "id") . "\", \"HT_dhdj\", \"toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=yes,width=850,height=610,left=300,top=100\")'> [查看]</a>";

                                if($_SESSION['FBCW'] == '1' || $_SESSION['FBSD'] == '1'){
                                    if ($state == "新建订单" || $state == '进入生产' || $state =='待结算') {
                                        ?>
<!--                                        <a href='?deleid=--><?// echo mysql_result($rsmx, $i, "id"); ?><!--&ddh=--><?// echo mysql_result($rs, 0, "ddh"); ?><!--'>[删除]</a>-->

                                    <? }
                                }
                                if($state == "新建订单" || (($_SESSION['FBCW'] == '1' || $_SESSION['FBSD'] == '1') && ($state == '进入生产' || $state =='待结算'))){
                                    ?>
                                    <a href='?deleid=<? echo mysql_result($rsmx, $i, "id"); ?>&ddh=<? echo mysql_result($rs, 0, "ddh"); ?>'>[删除]</a>

                                    <?
                                }
                                ?>
                            </td>
                            <td class="td_content" align="center"><? echo mysql_result($rsmx, $i, "chicun"); ?></td>
                            <td align="center" class="td_content"><? echo mysql_result($rsmx, $i, "sl"); ?></td>
                            <td class="td_content" align="center"><? echo mysql_result($rsmx, $i, "n1"); ?></td>
                            <td class="td_content" align="center"><?
                                $aaa = array_unique(explode(";", mysql_result($rsmx, $i, "file1")));
                                foreach ($aaa as $key => $a1){
                                    if(stristr($a1,'http')&&mysql_result($rs,0,'tbsj')<>'pbxd'){
                                        $a1arr = explode('/',$a1);
                                        $a1= end($a1arr);
                                    }
                                    if ($a1 <> ""){
                                        if(mysql_result($rs,0,'tbsj')=='pbxd'){

                                            $filestr = explode('/',$a1);
                                            echo "<a href='{$a1}?" . rand(10, 1000) . "' target='_blank'>".end($filestr)."</a>  ", "<br>";

                                        }else
                                            echo "<a href='{$localfileurl}/{$a1}?" . rand(10, 1000) . "' target='_blank'>{$a1}</a>  ", "<br>";

                                    }

                                }

                                if(!empty(mysql_result($rsmx,$i,"jdf1"))){
                                    echo '<br>';
                                    echo mysql_result($rsmx,$i,"jdf1");
                                }
                                if(!empty(mysql_result($rsmx,$i,"sczzbh1"))){
                                    echo '<br>';
                                    echo mysql_result($rsmx,$i,"sczzbh1");
                                }
                                ?>
                            </td>
                            <td class="td_content" align="center"><? echo mysql_result($rsmx, $i, "machine1"), "/<font color=red>", mysql_result($rsmx, $i, "mm1"), "[", mysql_result($rsmx, $i, "ms1"), "]</font>/", mysql_result($rsmx, $i, "jldw1"), "/", mysql_result($rsmx, $i, "dsm1"), "/", mysql_result($rsmx, $i, "hzx1"), "/P:", mysql_result($rsmx, $i, "pnum1"), "/SL:", mysql_result($rsmx, $i, "sl1"); ?></td>
                            <td class="td_content" align="center"><? echo mysql_result($rsmx, $i, "jg1"); ?> </td>
                            <td class="td_content" align="center"><? echo mysql_result($rsmx, $i, "n2"); ?></td>
                            <td align="center" class="td_content"><?
                                if(!empty(mysql_result($rsmx, $i, "n2"))){
                                    $aaa = array_unique(explode(";", mysql_result($rsmx, $i, "file2")));
                                    foreach ($aaa as $key => $a1){
                                        if(stristr($a1,'http')){
                                            $a1arr = explode('-',$a1);
                                            $a1= ''.$a1arr[1];
                                        }
                                        if ($a1 <> "") echo "<a href='{$localfileurl}/{$a1}?" . rand(10, 1000) . "' target='_blank'>{$a1}</a>  ", "<br>";

                                    }


                                    if(!empty(mysql_result($rsmx,$i,"jdf2"))){
                                        echo '<br>';
                                        echo mysql_result($rsmx,$i,"jdf2");
                                    }

                                    if(!empty(mysql_result($rsmx,$i,"sczzbh2"))){
                                        echo '<br>';
                                        echo mysql_result($rsmx,$i,"sczzbh2");
                                    }
                                }

                                ?>
                            </td>
                            <td align="center" class="td_content">
                                <? if (mysql_result($rsmx, $i, "n2") <> "") echo mysql_result($rsmx, $i, "machine2"), "/", mysql_result($rsmx, $i, "mm2"), "[", mysql_result($rsmx, $i, "ms2"), "]/", mysql_result($rsmx, $i, "jldw2"), "/", mysql_result($rsmx, $i, "dsm2"), "/", mysql_result($rsmx, $i, "hzx2"), "/P:", mysql_result($rsmx, $i, "pnum2"), "/SL:", mysql_result($rsmx, $i, "sl2"); ?></td>
                            <td class="td_content" align="center">
                                <? if (mysql_result($rsmx, $i, "n2") <> "") echo mysql_result($rsmx, $i, "jg2"); ?> </td>
                            <td align="center" class="td_content">
                                <? echo mysql_result($rsmx, $i, "sl1") * mysql_result($rsmx, $i, "pnum1") * mysql_result($rsmx, $i, "jg1") + mysql_result($rsmx, $i, "sl2") * mysql_result($rsmx, $i, "pnum2") * mysql_result($rsmx, $i, "jg2"); ?>
                            </td>

                            <?
                            $mxid = mysql_result($rsmx, $i, 'id');

                            $sql = "select group_concat(jgfs) as hd , group_concat(jg) as hdjg ,sum(jg*sl) as hdje from order_mxqt_hd where mxid = $mxid group by mxid";

                            $rshd = mysql_query($sql, $conn);

                            if (mysql_num_rows($rshd) > 0) {
                                ?>
                                <td align="center" class="td_content"><? echo mysql_result($rshd, 0, "hd"); ?></td>
                                <td class="td_content" align="center"><? echo mysql_result($rshd, 0, "hdjg"); ?> </td>
                                <td align="center" class="td_content"><? echo mysql_result($rshd, 0, "hdje"); ?></td>
                            <? } else {
                                ?>
                                <td></td>
                                <td></td>
                                <td></td>
                                <?
                            } ?>


                            <?
                            $sql2 = "select group_concat(fmfs) as fmfs , group_concat(jg) as fmjg ,sum(jg*sl) as fmje from order_mxqt_fm where mxid = $mxid group by mxid";

                            $rsfm = mysql_query($sql2, $conn);

                            if (mysql_num_rows($rsfm) > 0) {
                                ?>
                                <td align="center" class="td_content"><? echo mysql_result($rsfm, 0, "fmfs"); ?></td>
                                <td class="td_content" align="center"><? echo mysql_result($rsfm, 0, "fmjg"); ?> </td>
                                <td align="center" class="td_content"><? echo mysql_result($rsfm, 0, "fmje"); ?>
                                </td>
                            <? } else {
                                ?>
                                <td></td>
                                <td></td>
                                <td></td>
                                <?
                            } ?>

                        </tr>
                        <?

                        $zje = $zje + mysql_result($rsmx, $i, "sl1") * mysql_result($rsmx, $i, "pnum1") * mysql_result($rsmx, $i, "jg1") + mysql_result($rsmx, $i, "sl2") * mysql_result($rsmx, $i, "pnum2") * mysql_result($rsmx, $i, "jg2") + (mysql_num_rows($rshd) > 0 ? mysql_result($rshd, 0, "hdje") : 0) + (mysql_num_rows($rsfm) > 0 ? mysql_result($rsfm, 0, "fmje") : 0);

                    } ?>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>
    <br>
    <? if ((mysql_result($rs, 0, "state") == "进入生产") and $_SESSION["FBSD"] == "1") { ?>
        <div align="center"><input name="b1" value="打印生产单" type="button"
                                   onClick="window.open('YSXMqt_show_p.php?ddh=<? echo $bh; ?>')"></div><? } ?>
    <?

    if (mysql_result($rs, 0, "state") == "待配送" || mysql_result($rs, 0, "state") == "订单完成") { ?>
        <div align="center">
        <input type="button" onClick="window.open('YSXMqt_sh_p.php?ddh=<? echo $bh; ?>','new');" value="生成配送单"/>
        </div><? }

    ?>
</form>
<!--<form id="openwin" method="get" target="_blank" action="http://192.168.1.71:88/skyserver/make_jdf.php?ddh=--><?// echo $bh; ?><!--"></form>-->
</body>
</html>
<script type="text/javascript">
    document.getElementById("zje").innerHTML = '<? echo $zje;?>';
//    window.opener.location.reload();
    //    document.getElementById('ddzjet').value='<?// echo $zje; ?>//';


</script>
<?
$rs = null;
unset($rs);
$rss = null;
unset($rss);
?>
