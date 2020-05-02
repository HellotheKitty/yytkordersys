<? require("../../inc/conn.php");
?>
<? session_start();

if($_SESSION["YKUSERNAME"]=="") {
    echo "<script language=JavaScript>{window.location.href='../../error.php';}</script>";
    exit;
}?>
<?
$dwdm = substr($_SESSION["GDWDM"],0,4);
if ($_GET["jg"]<>"") {

}
$rs=mysql_query("select order_mxqt_fm.* from order_mxqt_fm where order_mxqt_fm.ddh='".$_GET["ddh"]."'",$conn);

    if(mysql_result($rs,0,'fmczy') == '')
        echo '<input type="button" style="padding:20px" class="finish-btn fm-finish" datatype="fm-' . mysql_result($rs,0,'id') . '" value="覆膜完成"/><br/>';
    else
        echo '<input type="button" style="padding:20px" value="已完成" disabled="disabled"/><br/>';


?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>订单信息</title>
    <script src="../../js/jquery-1.8.3.min.js" language="JavaScript"></script>
    <style type="text/css">
        td,th{
            border-right:1px solid #000;
            border-bottom:1px solid #000;
            line-height:40px;
        }
        table{
            border-left:1px solid #000;
            border-top:1px solid #000;
            margin-top:20px;
        }
        .STYLE14{  text-align: center;display: block;}

    </style>
</head>

<body>


<div align="center"><span class="STYLE14"><strong>印艺天空生产单</strong></span></div>


<?
//$_fmrs = mysql_query("select * from order_mxqt_fm where mxid in $_mxidStr order by id asc",$conn);


    ?>
    <table style="width: 90%;" cellpadding="0" cellspacing="0">
        <thead>
        <tr class="td_title" style="height:30px;">

            <th   align="center" scope="col">覆膜方式</th>
            <th   align="center" scope="col">成品尺寸</th>
            <th  align="center" scope="col">单位</th>
            <th align="center" scope="col">数量</th>
            <th   align="center" scope="col">备注</th>
        </tr>
        </thead>
        <tbody>
        <? if(mysql_num_rows($rs)>0){ ?>
        <tr>
            <td align="center" scope="col"><? echo mysql_result($rs,0,'fmfs') ?></td>
            <td  align="center" scope="col"><? echo mysql_result($rs,0,'cpcc') ?></td>
            <td   align="center" scope="col"><? echo mysql_result($rs,0,'jldw') ?></td>
            <td  align="center" scope="col"><? echo mysql_result($rs,0,'sl') ?></td>
            <td  align="center" scope="col"><? echo mysql_result($rs,0,'memo') ?></td>

        </tr>
        <? } ?>
        </tbody>
    </table>

<script type="text/javascript">

    $('.finish-btn').on('click',function(e){

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
                    if(_datatype.substring(0,2) == 'wc'){
                        window.location.href='../MYOrderShowns.php';
                    }
                }
            }
        });

    });
</script>
</body>
</html>

<?
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
