<?
require '../inc/conn.php';
if ($_SESSION["OK"]<>"OK") {
    echo "<script language=JavaScript>{window.location.href='../error.php';}</script>";
    exit;
}

$ddh = $_GET['ddh'];
$sql = "select m.pczy,m.pendtime,m.hdczy,m.hdendtime,m.fumoczy,m.fmendtime from order_mainqt m where ddh = $ddh";
$res = mysql_query($sql,$conn);

$pczys = explode(';',mysql_result($res,0,'pczy'));
$hdczys = explode(';',mysql_result($res,0,'hdczy'));
$fumoczys = explode(';',mysql_result($res,0,'fumoczy'));

$pczyxm = '';$hdczyxm = '';$fumoczyxm = '';
foreach($pczys as $pczy){

    $sqlry = "select xm from b_ry where bh = '$pczy'";
    $rs = mysql_query($sqlry,$conn);

    if(!empty($rs) && mysql_num_rows($rs)>0){
        $pczyxm .= mysql_result($rs,0,'xm');
        $pczyxm .= '; ';
    }
}
foreach($hdczys as $hdczy){

    $sqlry = "select xm from b_ry where bh = '$hdczy'";
    $rs = mysql_query($sqlry,$conn);

    if(!empty($rs) && mysql_num_rows($rs)>0){
        $hdczyxm .= mysql_result($rs,0,'xm');
        $hdczyxm .= '; ';
    }
}
foreach($fumoczys as $fumoczy){

    $sqlry = "select xm from b_ry where bh = '$fumoczy'";
    $rs = mysql_query($sqlry,$conn);

    if(!empty($rs) && mysql_num_rows($rs)>0){
        $fumoczyxm .= mysql_result($rs,0,'xm');
        $fumoczyxm .= '; ';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>订单操作员</title>
    <style>
        .styletr span{
            color:#0080ff;
        }
        .czytb td,.czytb th{
            border-right:1px solid #aaa;
            border-bottom:1px solid #aaa;
        }
        .czytb{
            border-top:1px solid #aaa;
            border-left:1px solid #aaa;
            width:100%;
        }
    </style>
</head>
<body>
<div class="main">
    <h4>订单操作员-<? echo $ddh; ?></h4>
    <table class="czytb">
        <colgroup>
            <col width="33%">
            <col width="33%">
        </colgroup>
        <thead>
        <tr>
            <th>打印</th>
            <th>覆膜</th>
            <th>后道</th>
        </tr>
        </thead>
        <tbody>
        <tr class="styletr">
            <td><? echo "<span>操作员: </span>" . $pczyxm ."<br> <span>签单时间: </span>" . mysql_result($res,0,'pendtime');  ?></td>
            <td><? echo "<span>操作员: </span>" . $hdczyxm ." <br> <span>签单时间: </span>" . mysql_result($res,0,'hdendtime');  ?></td>
            <td><? echo "<span>操作员: </span>" . $fumoczyxm ." <br> <span>签单时间: </span>" . mysql_result($res,0,'fmendtime');  ?></td>
        </tr>
        </tbody>
    </table>
</div>

</body>
</html>

