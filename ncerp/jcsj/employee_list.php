<?
session_start();
header("Content-Type:text/html;charset=UTF-8");
require("../../inc/conn.php");
if ($_SESSION["OK"]<>"OK") {
    echo "<script language=JavaScript>{window.location.href='../error.php';}</script>";
    exit; }?>

<?
$dwdm = $_SESSION['GDWDM'];
//        判断是哪个级别的统计
$dw0 = dw0($_SESSION['GDWDM']);

if ( $_SESSION['GDWDM']=='340000' ) {
//    $seldw = '3405';
//    $dwdmStr = "('340500')";
    $dwdmStr = "(select dwdm from b_dwdm where locate('340',dwdm)>0 and locate('0000',dwdm)=0)";
} elseif($_SESSION['GDWDM']=='330000'){
//    $seldw = '3301';
//    $dwdmStr = "('330100')";
    $dwdmStr = "(select dwdm from b_dwdm where locate('330',dwdm)>0 and locate('0000',dwdm)=0)";

}elseif($_SESSION['GDWDM']=='300000'){
//    $seldw = 'sh';
//    $dwdmStr = "('330100')";
    $dwdmStr = "(select dwdm from b_dwdm where locate('0000',dwdm)=0)";

}else{
    $dwdmStr = "('" . $dwdm . "')";
}

//分门店查询
if($_POST['seldw1'] <> ''){

    $seldw = $_POST['seldw1'];

    if($seldw == '所有门店' && $dwdm =='340000'){

        $dwdmStr = "(select dwdm from b_dwdm where locate('340',dwdm)>0 and locate('0000',dwdm)=0)";

    }elseif($seldw == '所有门店' && $dwdm =='330000'){

        $dwdmStr = "(select dwdm from b_dwdm where locate('330',dwdm)>0 and locate('0000',dwdm)=0)";

    }else{
//        $seldw = substr($seldw,0,4);
        $dwdmStr = "('$seldw')";
        $seldw = substr($seldw,0,4);
    }

}
//分区域
if($_POST['seldw2']<>''){

    $seldw = $_POST['seldw2'];

    if($seldw == '所有区域'){

        $dwdmStr = "(select dwdm from b_dwdm where locate('0000',dwdm)=0)";

    }elseif($seldw == 'bj'){

        $dwdmStr = "(select dwdm from b_dwdm where locate('340',dwdm)>0 and locate('0000',dwdm)=0)";
    }else{
        $dwdmStr = "(select dwdm from b_dwdm where locate('330',dwdm)>0 and locate('0000',dwdm)=0)";
    }
}



$dwdm = substr($_SESSION["GDWDM"],0,4);
$tj="dwdm in $dwdmStr ";

$tj .= "and locate('离职',xm)=0";
//$tj="dwdm='".$_SESSION["GDWDM"]."'";
//"xsbh='".$_SESSION["YKOAUSER"]."'";
if ($_GET["bh"]<>"") {$tj.=" and bh like '%".$_GET["bh"]."%'";}
if ($_GET["xm"]<>"") {$tj.=" and xm like '%".$_GET["xm"]."%'";}
if ($_GET["xb"]<>"") {$tj.=" and xb='".$_GET["xb"]."'";}
if ($_GET["zw"]<>"") {$tj.=" and zw='".$_GET["zw"]."'";}
//if ($_GET["bm"]<>"") {$tj.=" and bm = '".$_GET["bm"]."'";}
if ($_GET["qx"]<>"") {$tj.=" and qx='".$_GET["qx"]."'";}
if ($_GET["dw"]<>"") {$tj.=" and dwdm='".$_GET["dw"]."'";}
if ($_GET["mobile"]<>"") {$tj.=" and mobile like '%".$_GET["mobile"]."%'";}
if ($_GET["qq"]<>"") {$tj.=" and qq like '%".$_GET["qq"]."%'";}
if ($_GET["txaddress"]<>"") {$tj.=" and txaddress like '%".$_GET["txaddress"]."%'";}
//$rs=mysql_query("select * from base_kh where $tj order by id",$conn);
$rs=mysql_query("select * from b_ry where $tj order by id",$conn);

//echo "select * from b_ry where $tj order by id";
//var_dump(mysql_num_rows($rs));
$dwrs=mysql_query("select * from b_dwdm");

$qxArr = array(
    "kf" => "客服",
    "ch" => "前台",
    "sc" => "生产",
    "hd" => "后加工",
    "fm" => "覆膜",
    "fh" => "配送",
    "cw" => "财务"

);



//分页
//if ($tj<>"1=1") {$page_num=mysql_num_rows($rs)+1;} else {$page_num=15;}     //每页行数
$totalRecord = mysql_num_rows($rs);
if (isset($tj[4]) || $totalRecord<15) {$page_num=$totalRecord+1;} else {$page_num=15;}     //每页行数
$page_no=$_GET["pno"];     //当前页
if ($page_no=="") {$page_no=1;}
$page_f=$page_num*($page_no -1);   //开始行
$page_e=$page_f+$page_num;			//结束行
if ($page_e>mysql_num_rows($rs)) {$page_e=mysql_num_rows($rs);}
$page_t=ceil(mysql_num_rows($rs) / $page_num);  //总页数
//分页
?>
<html><head><title></title>
    <META http-equiv=Content-Type content="text/html; charset=utf-8">
    <LINK href="../../css/content.css" type=text/css rel=stylesheet>
    <SCRIPT language=JavaScript src="../../js/form.js"></SCRIPT>
    <SCRIPT language=JavaScript src="../../js/jquery-1.8.3.min.js"></SCRIPT>
    <SCRIPT language=JavaScript>
        function checkForm(){
            var charBag = "0123456789";
            if (!checkNotNull(form1.bh, "员工编号")) return false;
            if (!checkNotNull(form1.xm, "员工姓名")) return false;
            return true; }
    </SCRIPT>

    <meta content="MSHTML 6.00.3790.1830" name=GENERATOR>
    <style type="text/css">
        <!--
        .STYLE1 {color: #000000}
        -->
    </style>
    <style TYPE="text/css">
        <!--
        A:link{text-decoration:none}
        A:visited{text-decoration:none}
        A:hover {color: #EF6D21;text-decoration:underline}
        .STYLE4 {color: #FF0000}
        .STYLE13 {font-size: 12px}
        -->
    </style>
    <script language="JavaScript">
        <!--
        function suredo(src,q)
        {
            var ret;
            ret = confirm(q);
//	alert(src);
            if(ret!=false) window.location=src;
        }
        function resetpwd(id,bh)
        {
            if(confirm("确认重置编号为"+bh+"的员工的密码？"))
                $.ajax({
                    type: "GET",
                    url: "employee_add_edit.php?repwd=1&id="+id,
                    success: function(data){alert(data);},
                    error: function(){alert("retry plz");}
                });
        }
        //-->
    </script>
</head>
<body text=#000000 bgColor=#ffffff topMargin=0>
<form name="form1" method="post" action="employee_add_edit.php" onSubmit="return checkForm()">
    <table cellSpacing=0 cellPadding=0 width="100%" border=0>
        <tbody>
        <tr>
            <td width="57%" height=13 class=guide style="background-image: url('../images/main_guide_bg2.gif')">
                <img src="../images/guide.gif"
                     align=absMiddle>员工信息列表</td>
            <td width="43%" align=right class=guide style="background-image: url('../images/main_guide_bg2.gif')">
                <img
                    src="../images/main_r.gif"></td>
        </tr></tbody></table><br>
    <? if ($_GET["ID"]=="") {$id="";$zdm="";}
    else
    { $id=$_GET["ID"];
        $rss=mysql_query("select * from b_ry where id='".$id."'",$conn);
        $_bh=mysql_result($rss,0,"bh");
        $_xm=mysql_result($rss,0,"xm");
        $_xb=mysql_result($rss,0,"xb");
        $_zw=mysql_result($rss,0,"zw");
//$_bm=mysql_result($rss,0,"bm");
        $_dw=mysql_result($rss,0,"dwdm");
        $_qx=substr(mysql_result($rss,0,"qx"),0,2);
        $_mobile=mysql_result($rss,0,"mobile");
        $_qq=mysql_result($rss,0,"QQno");
        $_txaddress=mysql_result($rss,0,"txaddress");

    }?>
    <input name="ID" id="zdm" type="hidden" class="STYLE13" value="<? echo $id?>" size=25>
    <input name="nowPage" id="nowPage" type="hidden" class="STYLE13" value="<? echo $page_no; ?>" size=25>
    <table width="100%" border="0">
        <tr>
            <td  >员工编号：
                <input name="bh" type="text" class="STYLE13"  id="bh" value="<? echo $_bh?>"  size=15>
                　　员工姓名：
                <input name="xm" id="xm" type="text" class="STYLE13" value="<? echo $_xm?>" size=15>
                　　性　　别：
                <select name="xb"><option value="" ></option><option value="男" <? if ($_xb == "男") echo "selected"?> >男</option><option value="女" <? if ($_xb == "女") echo "selected"?> >女</option></select>
            </td>
            <td colspan="-3" >
                <input name="Submit" type="submit" class="button" value="<? if ($_GET["ID"]=="") {echo "添 加";} else {echo "保 存";}?>">
                <input name="Submit" type="button" class="button" value="查 询" onClick="javascript:window.location.href='<? echo $_SERVER["PHP_SELF"]?>?bh='+form1.bh.value+'&xm='+form1.xm.value+'&xb='+form1.xb.value+'&zw='+form1.zw.value/*+'&bm='+form1.bm.value*/+'&qx='+form1.qx.value+'&dw='+form1.dw.value+'&mobile='+form1.mobile.value+'&qq='+form1.qq.value+'&txaddress='+form1.txaddress.value"></td>
        </tr>
        <tr>
            <td colspan="2" >单　　位：
                <select name="dw"><option value="">　　　　　 </option><? while($tempArr=mysql_fetch_array($dwrs)){{if($_dw == $tempArr['dwdm']) $sel=" selected";$_dwdm=$tempArr['dwdm'];$_dwmc=$tempArr['dwmc'];echo "<option value='$_dwdm'$sel>$_dwmc</option>";$sel="";} $danwei[$tempArr["dwdm"]] = $tempArr["dwmc"];}?></select>
                　　职　　务：
                <input name="zw" id="zw" type="text" class="STYLE13" value="<? echo $_zw?>" size=15>
                　　权　　限：
                <select name="qx" id="qx"><option value=""></option><? foreach($qxArr as $qxKey => $qxVal){if ($qxKey==$_qx) $qxSel=" selected";echo "<option value='$qxKey'$qxSel>$qxVal</option>";$qxSel="";}?></select>
            </td>
        </tr>
        <tr>
            <td colspan="2" >联系电话：
                <input name="mobile" id="mobile" type="text" class="STYLE13" value="<? echo $_mobile?>" size=15>
                　　ＱＱ号码：
                <input name="qq" id="qq" type="text" class="STYLE13" value="<? echo $_qq?>" size=15>
                　　联系地址：
                <input name="txaddress" id="txaddress" type="text" class="STYLE13" value="<? echo $_txaddress?>" size=25></td>
        </tr>
    </table>
    <table class=maintable cellSpacing=1 cellPadding=1 width="100%" border=0>
        <tbody>
        <tr>
            <td class=head style="background-image: url('../images/nabg1.gif')" width="6%">员工编号</td>
            <td class=head style="background-image: url('../images/nabg1.gif')" width="10%">员工姓名</td>
            <td class=head style="background-image: url('../images/nabg1.gif')" width="5%">性别</td>
            <td class=head style="background-image: url('../images/nabg1.gif')" width="10%">单位</td>
            <td class=head style="background-image: url('../images/nabg1.gif')" width="10%">职务</td>
            <td class=head style="background-image: url('../images/nabg1.gif')" width="10%">联系电话</td>
            <td class=head style="background-image: url('../images/nabg1.gif')" width="10%">QQ</td>
            <td height="26" colspan="2" class=head style="background-image: url('../images/nabg1.gif')">联系地址</td>
        </tr>
        <? for($i=$page_f;$i<$page_e;$i++){ ?>
            <tr>
                <td><? echo mysql_result($rs,$i,"bh")?></td>
                <td><? echo mysql_result($rs,$i,"xm")?></td>
                <td><? echo mysql_result($rs,$i,"xb")?></td>
                <td><? echo $danwei[mysql_result($rs,$i,"dwdm")];?></td>
                <td><? echo mysql_result($rs,$i,"zw")?></td>
                <td><? echo mysql_result($rs,$i,"mobile")?></td>
                <td><? echo mysql_result($rs,$i,"QQno")?></td>
                <td><? echo mysql_result($rs,$i,"txaddress")?></td>
                <td width="100" align="center"><a href="employee_list.php?ID=<? echo mysql_result($rs,$i,"id")?>&pno=<? echo $page_no?>"><img src="../images/func_edit.gif" title="修改" alt="修改" width="18" height="17" border="0"></a> <a href="javascript:resetpwd(<?echo mysql_result($rs,$i,"id").",'".mysql_result($rs,$i,"bh")."'"?>)"><img src="../images/func_resetpwd.gif" alt="重置密码" title="重置密码为123456" width="18" height="17" border="0"></a> <a href="javascript::void" onClick="javascript:suredo('employee_del.php?ID=<? echo mysql_result($rs,$i,"id")."&pno=".$page_no?>','确定删除?')"><img src="../images/func_delete.gif" width="15" height="17" title="删除" alt="删除"></a></td>

            </tr>
            <?
        } ?>
        </tbody>
    </table>

    <table class=maintable cellSpacing=1 cellPadding=1 width="100%" border=0 id="table1" height="19">
        <tr>
            <td height="16" background="../images/nabg1.gif" class=alert><span class="STYLE4">·员工信息管理。</span></TD>
        </tr>
    </table>
    <div align="right"><A <? if ($page_t>1 and $page_no>1) {echo "href=".$_SERVER["PHP_SELF"]."?pno=1";} else {echo "disabled";};?>>首页</A>　<A <? if ($page_t>1 and $page_no>1) {echo "href=".$_SERVER["PHP_SELF"]."?pno=".($page_no-1);} else {echo "disabled";};?>>上一页</A>　<A <? if ($page_t>1 and $page_no<$page_t) {echo "href=".$_SERVER["PHP_SELF"]."?pno=".($page_no+1);} else {echo "disabled";};?>>下一页</A>　<A <? if ($page_t>1 and $page_no<>$page_t) {echo "href=".$_SERVER["PHP_SELF"]."?pno=".$page_t;} else {echo "disabled";};?>>尾页</A>　
        <INPUT name="pno" onKeyDown="" value="1" size="3">
        <INPUT name="ZKPager1" type="button" class="menubutton" value="转到" onClick="javascript:window.location.href='<? echo $_SERVER["PHP_SELF"]?>?pno='+document.form1.pno.value">　
        第<? echo $page_no."/".$page_t?>页
    </div>
</form>
<?/*if (!isset($GLOBALS["test"])) $GLOBALS["test"] = "new set"; else $GLOBALS["test"] = "exists"; echo $GLOBALS["test"].$GLOBALS['taskFPtime']."<br>"*/;?>
</body>
</html>
