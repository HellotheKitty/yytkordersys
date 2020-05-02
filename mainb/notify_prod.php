<?php
session_start();
require("../inc/conn.php");
date_default_timezone_set('PRC');

//state query
//state1 待生产
$sql = "select count(id) as dt from order_mainqt m where state = '待生产' and to_days(now()) = to_days(sdate) and zzfy = 3301";
$res = mysql_query($sql,$conn);
$today_state1 = mysql_result($res,0,'dt');

$sql = "select count(id) as dt from order_mainqt m where state = '待生产' and to_days(now()) - to_days(sdate) =1 and zzfy = 3301";
$res = mysql_query($sql,$conn);
$yesterday_state1 = mysql_result($res,0,'dt');

$sql = "select count(id) as dt from order_mainqt m where state = '待生产' and to_days(now()) - to_days(sdate) >=2 and zzfy = 3301";
$res = mysql_query($sql,$conn);
$twodays_ago_state1 = mysql_result($res,0,'dt');

//state2 打印
$sql = "select count(id) as dt from order_mainqt m where state = '进入生产' and to_days(now()) - to_days(sdate) =0 and (pczy is null or pczy = '') and zzfy = 3301";
$res = mysql_query($sql,$conn);
$today_state2 = mysql_result($res,0,'dt');

$sql = "select count(id) as dt from order_mainqt m where state = '进入生产' and to_days(now()) - to_days(sdate) =1 and (pczy is null or pczy = '') and zzfy = 3301";
$res = mysql_query($sql,$conn);
$yesterday_state2 = mysql_result($res,0,'dt');

$sql = "select count(id) as dt from order_mainqt m where state = '进入生产' and to_days(now()) - to_days(sdate) >=2 and (pczy is null or pczy = '') and zzfy = 3301";
$res = mysql_query($sql,$conn);
$twodays_ago_state2 = mysql_result($res,0,'dt');

//state3 覆膜
//$sql = "SELECT count(DISTINCT m.id) as dt FROM order_mainqt m,order_mxqt_fm fm WHERE m.ddh = fm.ddh AND m.state = '进入生产' AND fm.fmfs is not null AND (fumoczy is null or fumoczy = '' ) AND pczy is not null AND pczy <>'' AND to_days(now()) = to_days(m.sdate) AND m.zzfy = 3301";
$sql = "SELECT count(DISTINCT m.id) as dt FROM order_mainqt m,order_mxqt_fm fm WHERE m.ddh = fm.ddh AND (m.state = '进入生产' OR m.state = '已打印') AND fm.fmfs is not null AND (fm.fmczy is null or fm.fmczy = '' ) AND to_days(now()) = to_days(m.sdate) AND m.zzfy = 3301";
$res = mysql_query($sql,$conn);
$today_state3 = mysql_result($res,0,'dt');

//$sql = "SELECT count(DISTINCT m.id) as dt FROM order_mainqt m,order_mxqt_fm fm WHERE m.ddh = fm.ddh AND m.state = '进入生产' AND fm.fmfs is not null AND (fumoczy is null or fumoczy = '' ) AND pczy is not null AND pczy <>'' AND to_days(now()) - to_days(m.sdate) =1 AND m.zzfy = 3301";
$sql = "SELECT count(DISTINCT m.id) as dt FROM order_mainqt m,order_mxqt_fm fm WHERE m.ddh = fm.ddh AND (m.state = '进入生产' OR m.state = '已打印') AND fm.fmfs is not null AND (fm.fmczy is null or fm.fmczy = '' ) AND to_days(now()) - to_days(m.sdate) =1 AND m.zzfy = 3301";

$res = mysql_query($sql,$conn);
$yesterday_state3 = mysql_result($res,0,'dt');

//$sql = "SELECT count(DISTINCT m.id) as dt FROM order_mainqt m,order_mxqt_fm fm WHERE m.ddh = fm.ddh AND m.state = '进入生产' AND fm.fmfs is not null AND (fumoczy is null or fumoczy = '' ) AND pczy is not null AND pczy <>'' AND to_days(now()) - to_days(m.sdate)>=2 AND m.zzfy = 3301";
$sql = "SELECT count(DISTINCT m.id) as dt FROM order_mainqt m,order_mxqt_fm fm WHERE m.ddh = fm.ddh AND (m.state = '进入生产' OR m.state = '已打印') AND fm.fmfs is not null AND (fm.fmczy is null or fm.fmczy = '' ) AND to_days(now()) - to_days(m.sdate) >= 2 AND m.zzfy = 3301";
$res = mysql_query($sql,$conn);
$twodays_ago_state3 = mysql_result($res,0,'dt');


//state4 后加工
//$sql = " SELECT COUNT(id) as dt FROM order_mainqt where ddh in (select ddhao from order_mxqt_hd) and state='进入生产' AND pczy is not null AND pczy <>'' AND  to_days(now()) = to_days(sdate) AND zzfy = 3301";
$sql = " SELECT COUNT(hd.id) as dt FROM order_mainqt m,order_mxqt_hd hd where m.ddh = hd.ddhao and (m.state = '进入生产' OR m.state = '已打印') AND hd.hdczy is null AND to_days(now()) = to_days(sdate) AND zzfy = 3301";
$res = mysql_query($sql,$conn);
$today_state4 = mysql_result($res,0,'dt');

//$sql = " SELECT COUNT(id) as dt FROM order_mainqt where ddh in (select ddhao from order_mxqt_hd) and state='进入生产' AND pczy is not null AND pczy <>'' AND to_days(now()) - to_days(sdate)=1 AND zzfy = 3301";
$sql = " SELECT COUNT(hd.id) as dt FROM order_mainqt m,order_mxqt_hd hd where m.ddh = hd.ddhao and (m.state = '进入生产' OR m.state = '已打印') AND hd.hdczy is null AND to_days(now()) - to_days(sdate)=1 AND zzfy = 3301";
$res = mysql_query($sql,$conn);
$yesterday_state4 = mysql_result($res,0,'dt');

//$sql = " SELECT COUNT(id) as dt FROM order_mainqt where ddh in (select ddhao from order_mxqt_hd) and state='进入生产' AND pczy is not null AND pczy <>'' AND  to_days(now()) - to_days(sdate)<=2 AND zzfy = 3301";
$sql = " SELECT COUNT(hd.id) as dt FROM order_mainqt m,order_mxqt_hd hd where m.ddh = hd.ddhao and (m.state = '进入生产' OR m.state = '已打印') AND hd.hdczy is null AND to_days(now()) - to_days(sdate)<=2 AND zzfy = 3301";
$res = mysql_query($sql,$conn);
$twodays_ago_state4 = mysql_result($res,0,'dt');

//state5 待配送 自取
$sql = "SELECT count(id) as dt FROM order_mainqt WHERE state = '待配送' AND psfs = '上门自取' AND TO_DAYS(NOW()) = TO_DAYS(sdate) AND zzfy =3301";
$res = mysql_query($sql,$conn);
$today_state5 = mysql_result($res,0,'dt');

$sql = "SELECT count(id) as dt FROM order_mainqt WHERE state = '待配送' AND psfs = '上门自取' AND TO_DAYS(NOW()) - TO_DAYS(sdate)=1 AND zzfy =3301";
$res = mysql_query($sql,$conn);
$yesterday_state5 = mysql_result($res,0,'dt');

$sql = "SELECT count(id) as dt FROM order_mainqt WHERE state = '待配送' AND psfs = '上门自取' AND TO_DAYS(NOW()) - TO_DAYS(sdate)>=2 AND zzfy =3301";
$res = mysql_query($sql,$conn);
$twodays_ago_state5 = mysql_result($res,0,'dt');

//state6 待配送 快递
$sql = "SELECT count(id) as dt FROM order_mainqt WHERE state = '待配送' AND psfs = '快递配送' AND TO_DAYS(NOW()) = TO_DAYS(sdate) AND zzfy =3301";
$res = mysql_query($sql,$conn);
$today_state6 = mysql_result($res,0,'dt');

$sql = "SELECT count(id) as dt FROM order_mainqt WHERE state = '待配送' AND psfs = '快递配送' AND TO_DAYS(NOW()) - TO_DAYS(sdate)=1 AND zzfy =3301";
$res = mysql_query($sql,$conn);
$yesterday_state6 = mysql_result($res,0,'dt');

$sql = "SELECT count(id) as dt FROM order_mainqt WHERE state = '待配送' AND psfs = '快递配送' AND TO_DAYS(NOW()) - TO_DAYS(sdate)>=2 AND zzfy =3301";
$res = mysql_query($sql,$conn);
$twodays_ago_state6 = mysql_result($res,0,'dt');

//state7 待配送 送货
$sql = "SELECT count(id) as dt FROM order_mainqt WHERE state = '待配送' AND psfs = '送货' AND TO_DAYS(NOW()) = TO_DAYS(sdate) AND zzfy =3301";
$res = mysql_query($sql,$conn);
$today_state7 = mysql_result($res,0,'dt');

$sql = "SELECT count(id) as dt FROM order_mainqt WHERE state = '待配送' AND psfs = '送货' AND TO_DAYS(NOW()) - TO_DAYS(sdate)=1 AND zzfy =3301";
$res = mysql_query($sql,$conn);
$yesterday_state7 = mysql_result($res,0,'dt');

$sql = "SELECT count(id) as dt FROM order_mainqt WHERE state = '待配送' AND psfs = '送货' AND TO_DAYS(NOW()) - TO_DAYS(sdate)>=2 AND zzfy =3301";
$res = mysql_query($sql,$conn);
$twodays_ago_state7 = mysql_result($res,0,'dt');

//customer query
//注册客户
$sql="SELECT COUNT(id) as dt FROM base_kh where gdzk = 3301";
$res = mysql_query($sql,$conn);
$total_customer = mysql_result($res,0,'dt');

//有联系方式的客户
$sql="SELECT COUNT(id) as dt FROM base_kh WHERE gdzk = 3301 AND lxdh is not null AND lxdh <>'' AND LENGTH(lxdh) >5";
$res = mysql_query($sql,$conn);
$cus_with_connection = mysql_result($res,0,'dt');

//一周内活跃客户
$sql = "SELECT count(khmc) as dt FROM base_kh WHERE gdzk = 3301 AND khmc in (SELECT DISTINCT khmc from order_mainqt where TO_DAYS(NOW()) - TO_DAYS(ddate) <=7 )";
$res = mysql_query($sql,$conn);
$cus_week_active = mysql_result($res,0,'dt');

//30天活跃客户
$sql = "SELECT count(khmc) as dt FROM base_kh WHERE gdzk = 3301 AND khmc in (SELECT DISTINCT khmc from order_mainqt where TO_DAYS(NOW()) - TO_DAYS(ddate) <=30 )";
$res = mysql_query($sql,$conn);
$cus_30_active = mysql_result($res,0,'dt');

//90天活跃客户
$sql = "SELECT count(khmc) as dt FROM base_kh WHERE gdzk = 3301 AND khmc in (SELECT DISTINCT khmc from order_mainqt where TO_DAYS(NOW()) - TO_DAYS(ddate) <=90 )";
$res = mysql_query($sql,$conn);
$cus_90_active = mysql_result($res,0,'dt');

//machine query
//today
$today_1w_pages=0;$today_76_pages=0;$today_75_pages=0;$today_56_pages=0;
$sql="SELECT SUM(IFNULL(pnum1*sl1,0) + IFNULL(pnum2*sl2,0)) as dt, r.machine FROM order_mxqt mx LEFT JOIN order_mainqt_readcode r ON mx.ddh= r.ddh WHERE TO_DAYS(NOW()) = TO_DAYS(r.sdate) GROUP BY r.machine ORDER BY machine";

//$sql1="SELECT SUM(IFNULL(pnum1*sl1,0)) as dt1, mx.workplace1 FROM order_mxqt mx WHERE TO_DAYS(NOW()) = TO_DAYS(mx.sdate) GROUP BY mx.workplace1 ORDER BY mx.workplace1";
//$sql2="SELECT SUM(IFNULL(pnum1*sl1,0)) as dt1, mx.workplace1 FROM order_mxqt mx WHERE TO_DAYS(NOW()) = TO_DAYS(mx.sdate) GROUP BY mx.workplace1 ORDER BY mx.workplace1";
//
//$res1 = mysql_query($sql1,$conn);
//$res2 = mysql_query($sql2,$conn);


$res = mysql_query($sql,$conn);

for($i=0;$i<mysql_num_rows($res);$i++){

    $machine = mysql_result($res,$i,'machine');
    if(strpos($machine,'10000')!==false){

        $today_1w_pages += intval(mysql_result($res,$i,'dt'));

    }elseif(strpos($machine,'5600')!==false){

        $today_56_pages += intval(mysql_result($res,$i,'dt'));

    }elseif(strpos($machine,'7500')!==false){

        $today_75_pages += intval(mysql_result($res,$i,'dt'));
    }elseif(strpos($machine,'7600')!==false){

        $today_76_pages += intval(mysql_result($res,$i,'dt'));
    }

}
//1w重新算
$sql1 = "select SUM(IFNULL(pnum1*sl1,0)) as dt from order_mxqt mx , order_mainqt m where mx.ddh=m.ddh and m.zzfy=3301 and  TO_DAYS(NOW()) = TO_DAYS(m.pendtime) and locate('Hp10000',mx.machine1 ) > 0";
$sql2 = "select SUM(IFNULL(pnum2*sl2,0)) as dt from order_mxqt mx , order_mainqt m where mx.ddh=m.ddh and m.zzfy=3301 and  TO_DAYS(NOW()) = TO_DAYS(m.pendtime) and locate('Hp10000',mx.machine2 ) > 0";

$res1 = mysql_query($sql1,$conn);
$res2 = mysql_query($sql2,$conn);

$today_1w_pages = intval(mysql_result($res1,0,'dt')) + intval(mysql_result($res2,0,'dt'));


//yesterday
$yesterday_1w_pages=0;$yesterday_76_pages=0;$yesterday_75_pages=0;$yesterday_56_pages=0;

$sql="SELECT SUM(IFNULL(pnum1*sl1,0) + IFNULL(pnum2*sl2,0)) as dt, r.machine FROM order_mxqt mx LEFT JOIN order_mainqt_readcode r ON mx.ddh= r.ddh WHERE TO_DAYS(NOW()) - TO_DAYS(r.sdate)=1 GROUP BY r.machine ORDER BY machine";

$res = mysql_query($sql,$conn);

for($i=0;$i<mysql_num_rows($res);$i++){


    $machine = mysql_result($res,$i,'machine');
    if(strpos($machine,'10000')!==false){

        $yesterday_1w_pages += intval(mysql_result($res,$i,'dt'));

    }elseif(strpos($machine,'5600')!==false){

        $yesterday_56_pages += intval(mysql_result($res,$i,'dt'));

    }elseif(strpos($machine,'7500')!==false){

        $yesterday_75_pages += intval(mysql_result($res,$i,'dt'));

    }elseif(strpos($machine,'7600')!==false){

        $yesterday_76_pages += intval(mysql_result($res,$i,'dt'));
    }

}
//1w重新算
$sql1 = "select SUM(IFNULL(pnum1*sl1,0)) as dt from order_mxqt mx , order_mainqt m where mx.ddh=m.ddh and m.zzfy=3301 and  TO_DAYS(NOW()) - TO_DAYS(m.pendtime)=1 and locate('Hp10000',mx.machine1 ) > 0";
$sql2 = "select SUM(IFNULL(pnum2*sl2,0)) as dt from order_mxqt mx , order_mainqt m where mx.ddh=m.ddh and m.zzfy=3301 and  TO_DAYS(NOW()) - TO_DAYS(m.pendtime)=1 and locate('Hp10000',mx.machine2 ) > 0";

$res1 = mysql_query($sql1,$conn);
$res2 = mysql_query($sql2,$conn);

$yesterday_1w_pages = intval(mysql_result($res1,0,'dt')) + intval(mysql_result($res2,0,'dt'));

//this week
$thisweek_1w_pages=0;$thisweek_76_pages=0;$thisweek_75_pages=0;$thisweek_56_pages=0;

$sql="SELECT SUM(IFNULL(pnum1*sl1,0) + IFNULL(pnum2*sl2,0)) as dt, r.machine FROM order_mxqt mx LEFT JOIN order_mainqt_readcode r ON mx.ddh= r.ddh WHERE yearweek(date_format(r.sdate,'%Y-%m-%d')) = yearweek(now()) GROUP BY r.machine ORDER BY machine";

$res = mysql_query($sql,$conn);

for($i=0;$i<mysql_num_rows($res);$i++){

    $machine = mysql_result($res,$i,'machine');
    if(strpos($machine,'10000')!==false){

        $thisweek_1w_pages += intval(mysql_result($res,$i,'dt'));

    }elseif(strpos($machine,'5600')!==false){

        $thisweek_56_pages += intval(mysql_result($res,$i,'dt'));

    }elseif(strpos($machine,'7500')!==false){

        $thisweek_75_pages += intval(mysql_result($res,$i,'dt'));
    }elseif(strpos($machine,'7600')!==false){

        $thisweek_76_pages += intval(mysql_result($res,$i,'dt'));
    }

}

//1w重新算
$sql1 = "select SUM(IFNULL(pnum1*sl1,0)) as dt from order_mxqt mx , order_mainqt m where mx.ddh=m.ddh and m.zzfy=3301 and yearweek(date_format(m.pendtime,'%Y-%m-%d')) = yearweek(now()) and locate('Hp10000',mx.machine1 ) > 0";
$sql2 = "select SUM(IFNULL(pnum2*sl2,0)) as dt from order_mxqt mx , order_mainqt m where mx.ddh=m.ddh and m.zzfy=3301 and yearweek(date_format(m.pendtime,'%Y-%m-%d')) = yearweek(now()) and locate('Hp10000',mx.machine2 ) > 0";

$res1 = mysql_query($sql1,$conn);
$res2 = mysql_query($sql2,$conn);

$thisweek_1w_pages = intval(mysql_result($res1,0,'dt')) + intval(mysql_result($res2,0,'dt'));

//this month
$thismonth_1w_pages=0;$thismonth_76_pages=0;$thismonth_75_pages=0;$thismonth_56_pages=0;

$sql="SELECT SUM(IFNULL(pnum1*sl1,0) + IFNULL(pnum2*sl2,0)) as dt, r.machine FROM order_mxqt mx LEFT JOIN order_mainqt_readcode r ON mx.ddh= r.ddh WHERE date_format(r.sdate,'%Y-%m') = date_format(now(),'%Y-%m') GROUP BY r.machine ORDER BY machine";
$res = mysql_query($sql,$conn);

for($i=0;$i<mysql_num_rows($res);$i++){


    $machine = mysql_result($res,$i,'machine');
    if(strpos($machine,'10000')!==false){

        $thismonth_1w_pages += intval(mysql_result($res,$i,'dt'));

    }elseif(strpos($machine,'5600')!==false){

        $thismonth_56_pages += intval(mysql_result($res,$i,'dt'));

    }elseif(strpos($machine,'7500')!==false){

        $thismonth_75_pages += intval(mysql_result($res,$i,'dt'));
    }elseif(strpos($machine,'7600')!==false){

        $thismonth_76_pages += intval(mysql_result($res,$i,'dt'));
    }

}
//1w重新算
$sql1 = "select SUM(IFNULL(pnum1*sl1,0)) as dt from order_mxqt mx , order_mainqt m where mx.ddh=m.ddh and m.zzfy=3301 and date_format(m.pendtime,'%Y-%m') = date_format(now(),'%Y-%m') and locate('Hp10000',mx.machine1 ) > 0";
$sql2 = "select SUM(IFNULL(pnum2*sl2,0)) as dt from order_mxqt mx , order_mainqt m where mx.ddh=m.ddh and m.zzfy=3301 and date_format(m.pendtime,'%Y-%m') = date_format(now(),'%Y-%m') and locate('Hp10000',mx.machine2 ) > 0";

$res1 = mysql_query($sql1,$conn);
$res2 = mysql_query($sql2,$conn);

$thismonth_1w_pages = intval(mysql_result($res1,0,'dt')) + intval(mysql_result($res2,0,'dt'));

//end query

$_html = '
<table class="tb-main tb-production" cellpadding="0" cellspacing="0">
        <tr>
            <th class="pro-left-title backcol-lgray th-left-col" rowspan="2"><span class="tb-title-text">Real-Time 印艺天空上海</span><span class="s-notice"></span></th>
            <th class="pro-head-title fill-pink" rowspan="2">待生产</th>
            <th class="pro-head-title fill-llgray" colspan="3">进入生产</th>
            <th class="pro-head-title fill-llgray" colspan="3">待配送</th>
        </tr>
        <tr>
            <th class="pro-head-title fill-green">打印队列</th>
            <th class="pro-head-title fill-blue">覆膜队列</th>
            <th class="pro-head-title fill-lpurple">工艺队列</th>
            <th class="pro-head-title fill-orange">自取</th>
            <th class="pro-head-title fill-ldgray">快递</th>
            <th class="pro-head-title fill-yellow">送货</th>
        </tr>
        <tr>
            <th class="pro-left-title">今天 '. date('m-d') .'</th>
            <td class="text-pink">' . $today_state1 . '</td>
            <td class="text-green fill-ord">' . $today_state2 . '</td>
            <td class="text-blue">' . $today_state3 . '</td>
            <td class="text-lpurple fill-ord">' . $today_state4 .'</td>
            <td class="text-orange">' . $today_state5 .'</td>
            <td class="text-ldgray fill-ord">' . $today_state6 .'</td>
            <td class="text-yellow">' . $today_state7 .'</td>
        </tr>
        <tr>
            <th class="pro-left-title">昨天 ' . date('m-d',strtotime('-1 day')) . '</th>
            <td class="text-pink">' . $yesterday_state1 .'</td>
            <td class="text-green fill-ord">' . $yesterday_state2 .'</td>
            <td class="text-blue">' . $yesterday_state3 .'</td>
            <td class="text-lpurple fill-ord">' . $yesterday_state4 .'</td>
            <td class="text-orange">' . $yesterday_state5 .'</td>
            <td class="text-ldgray fill-ord">' . $yesterday_state6 .'</td>
            <td class="text-yellow">' . $yesterday_state7 .'</td>
        </tr>
        <tr>
            <th class="pro-left-title">两天前</th>
            <td class="text-pink">' .$twodays_ago_state1. '</td>
            <td class="text-green fill-ord">' .$twodays_ago_state2. '</td>
            <td class="text-blue">' .$twodays_ago_state3. '</td>
            <td class="text-lpurple fill-ord">' .$twodays_ago_state4. '</td>
            <td class="text-orange">' .$twodays_ago_state5. '</td>
            <td class="text-ldgray fill-ord">' .$twodays_ago_state6. '</td>
            <td class="text-yellow">' .$twodays_ago_state7. '</td>
        </tr>
    </table>
    <table class="tb-main tb-customer" cellspacing="0" cellpadding="0">
        <tr>
            <th class="th-cus-head" colspan="10">活跃客户</th>
        </tr>
        <tr>
            <th>注册客户</th>
            <td>' . $total_customer . '</td>
            <th>有联系方式的客户</th>
            <td>' . $cus_with_connection . '</td>
            <th>一周内活跃客户</th>
            <td>' . $cus_week_active . '</td>
            <th>30天内活跃客户</th>
            <td>' . $cus_30_active . '</td>
            <th>90天内活跃客户</th>
            <td>' . $cus_90_active . '</td>

        </tr>
    </table>
    <table class="tb-main tb-machine" cellpadding="0" cellspacing="0">
        <tr>
            <th class="machine-title border-dgray">机器印量(P)</th>
            <th class="text-sbrown fill-ord border-sbrown">HP10000</th>
            <th class="text-green border-green">HP7600</th>
            <th class="text-blue fill-ord border-blue">HP7500</th>
            <th class="text-lpurple border-lpurple">HP5600</th>
        </tr>
        <tr>
            <th class="pro-left-title">今天 '. date('m-d') .'</th>
            <td class="text-sbrown fill-ord">' . $today_1w_pages . '</td>
            <td class="text-green">' . $today_76_pages . '</td>
            <td class="text-blue fill-ord">' . $today_75_pages . '</td>
            <td class="text-lpurple">' . $today_56_pages . '</td>
        </tr>
        <tr>
            <th class="pro-left-title">昨天 ' . date('m-d',strtotime('-1 day')) . '</th>
            <td class="text-sbrown fill-ord">' . $yesterday_1w_pages . '</td>
            <td class="text-green">' . $yesterday_76_pages . '</td>
            <td class="text-blue fill-ord">' . $yesterday_75_pages . '</td>
            <td class="text-lpurple">' . $yesterday_56_pages . '</td>
        </tr>
        <tr>
            <th class="pro-left-title">本周</th>
            <td class="text-sbrown fill-ord">' . $thisweek_1w_pages . '</td>
            <td class="text-green">' . $thisweek_76_pages . '</td>
            <td class="text-blue fill-ord">' . $thisweek_75_pages . '</td>
            <td class="text-lpurple">' . $thisweek_56_pages . '</td>
        </tr>
        <tr>
            <th class="pro-left-title">本月</th>
            <td class="text-sbrown fill-ord">' . $thismonth_1w_pages . '</td>
            <td class="text-green">' . $thismonth_76_pages . '</td>
            <td class="text-blue fill-ord">' . $thismonth_75_pages . '</td>
            <td class="text-lpurple">' . $thismonth_56_pages . '</td>
        </tr>
    </table>
';

echo json_encode([
    'list' => $_html,
    'cssReload' => 0,
    'reload' => 0
]);
?>

