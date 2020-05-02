<? require("../../inc/conn.php");?>
<? session_start();
if ($_SESSION["OK"]<>"OK") {
    echo "<script language=JavaScript>{window.location.href='../error.php';}</script>";
    exit;
}

    $localftp = "http://oa.skyprint.cn/fileupload";

if($_SESSION["MPZH"] == 'AYX1' || $_SESSION["MPZH"] == 'AYXZZD'){

    $localftp = "http://192.168.1.71:88/skyserver";
}
?>
<?
$dwdm = substr($_SESSION["GDWDM"],0,4);
$colors = array('彩色', '黑白', '三色');
if ($_GET["deleid"]<>"" && $_GET["deletype"] == 1) {

    $ddh=$_GET["ddh"];
    mysql_query("delete from order_mxqt_hd where id='".$_GET["deleid"]."'",$conn);
    $rs=mysql_query("select sum(jg1*pnum1*sl1+jg2*pnum2*sl2) from order_mxqt mx where mx.ddh='$ddh'",$conn);
    $rshd=mysql_query("select sum(jg*sl) from order_mxqt_hd where mxid in (select id from order_mxqt where ddh='$ddh')",$conn);
    $rsfm=mysql_query("select sum(jg*sl) from order_mxqt_fm where mxid in (select id from order_mxqt where ddh='$ddh')",$conn);

    mysql_query("update order_mainqt set dje=".(mysql_result($rs,0,0)+mysql_result($rshd,0,0)+mysql_result($rsfm,0,0))." where ddh='$ddh'",$conn);
    header("location:YSXMqt_mxdjs.php?ddh=".$ddh."&mxsid=".$_GET["mxsid"]);
    exit;
}elseif($_GET["deleid"]<>"" && $_GET["deletype"] == 2){
    $ddh=$_GET["ddh"];

    mysql_query("delete from order_mxqt_fm where id='".$_GET["deleid"]."'",$conn);
    $rs=mysql_query("select sum(jg1*pnum1*sl1+jg2*pnum2*sl2) from order_mxqt mx where mx.ddh='$ddh'",$conn);
    $rshd=mysql_query("select sum(jg*sl) from order_mxqt_fm where mxid in (select id from order_mxqt where ddh='$ddh')",$conn);
    $rsfm=mysql_query("select sum(jg*sl) from order_mxqt_fm where mxid in (select id from order_mxqt where ddh='$ddh')",$conn);

    mysql_query("update order_mainqt set dje=".(mysql_result($rs,0,0)+mysql_result($rshd,0,0)+mysql_result($rsfm,0,0))." where ddh='$ddh'",$conn);
    header("location:YSXMqt_mxdjs.php?ddh=".$ddh."&mxsid=".$_GET["mxsid"]);
    exit;
}



$bh=$_GET["ddh"];$state="新建";
if ($_GET["lx"]=="show") $state="查看";
$staters = mysql_query("select state from order_mainqt where ddh='".$bh."'",$conn);
$orderstate = mysql_result($staters,0,"state");
if($orderstate == '待配送' or $orderstate == '订单完成')
    $state="查看";
else
    $state="新建";
$rsjg=mysql_query("select jg from base_kh,order_mainqt qt where qt.khmc=base_kh.khmc and qt.ddh='$bh'");
if (mysql_num_rows($rsjg)>0) $jg=mysql_result($rsjg,0,0); else $jg=0.0;
$dj=0;$n1="";$n2="";
if ($_GET["pn"]<>"" ) {
    $pn=$_GET["pn"];
    $rs1=mysql_query("select * from printclasscomponent where printclass='".$_GET["pn"]."'",$conn);
    if (mysql_num_rows($rs1)==1) {$n1=mysql_result($rs1,0,2);}
    if (mysql_num_rows($rs1)==2) {$n1=mysql_result($rs1,0,2);$n2=mysql_result($rs1,1,2);}
}
if ($_GET["mxsid"]<>"" ) {
    $id=$_GET["mxsid"];
    $rs=mysql_query("select * from order_mxqt where id=".$_GET["mxsid"]);
    $pn=mysql_result($rs,0,"productname");
    $pname=mysql_result($rs,0,"pname");
    $sl=mysql_result($rs,0,"sl");$chicun=mysql_result($rs,0,"chicun");
    $n1=mysql_result($rs,0,"n1");$n2=mysql_result($rs,0,"n2");
    $file1=mysql_result($rs,0,"file1");$file2=mysql_result($rs,0,"file2");
    $machine1=mysql_result($rs,0,"machine1");$machine2=mysql_result($rs,0,"machine2");
    $color1=mysql_result($rs,0,"color1");$color2=mysql_result($rs,0,"color2");
    $paper1=mysql_result($rs,0,"paper1");$paper2=mysql_result($rs,0,"paper2");
    $jldw1=mysql_result($rs,0,"jldw1");$jldw2=mysql_result($rs,0,"jldw2");
    $dsm1=mysql_result($rs,0,"dsm1");$dsm2=mysql_result($rs,0,"dsm2");
    $hzx1=mysql_result($rs,0,"hzx1");$hzx2=mysql_result($rs,0,"hzx2");
    $pnum1=mysql_result($rs,0,"pnum1");$pnum2=mysql_result($rs,0,"pnum2");
    $sl1=mysql_result($rs,0,"sl1");$sl2=mysql_result($rs,0,"sl2");
    $jg1=mysql_result($rs,0,"jg1");$jg2=mysql_result($rs,0,"jg2");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>订单明细</title>
    <SCRIPT type="text/javascript" src="../../js/form.js"></SCRIPT>
    <SCRIPT  type="text/javascript" src="../../js/jquery-1.8.3.min.js"></SCRIPT>
    <SCRIPT  type="text/javascript">
        function checkForm(){
            //          有数量的价格不能为零
            if((form1.sl.value != 0 || form1.pnum1.value !=0 || form1.sl1.value != 0 ) && parseFloat(form1.jg1.value) == 0 ){
                form1.jg1.focus();
                confirm('构件一单价为零，确定提交？');

                if((form1.pnum2.value !=0 || form1.sl2.value != 0 ) && parseFloat(form1.jg2.value) == 0 ){
                    form1.jg2.focus();
                    return confirm('构件二单价为零，确定提交？');

                }
            }else{
                if((form1.pnum2.value !=0 || form1.sl2.value != 0 ) && parseFloat(form1.jg2.value) == 0 ){
                    form1.jg2.focus();
                    return confirm('构件二单价为零，确定提交？');

                }
            }


//            if(!($("#jg1").val()>0)) setjg(1);
//            if(!($("#jg2").val()>0)) setjg(2);
            var tmpFrm = document.forms[0];
            var charBag = "-0123456789.";
            if (!checkNotNull(form1.mc, "")) return false;
            if (!checkNotNull(form1.bm, "")) return false;
            if (!checkNotNull(form1.je, "")) return false;
            if (!checkStrLegal(form1.je, "", charBag)) return false;
            return true; }
        function slc(obj) {
            var js=0,cs=1,xdj;
            var dj="<? echo $dj?>";
            var zz=form1.zz.value;
            if (zz.indexOf("-")>0) js=zz.substr(zz.indexOf("-")+1);
            var chicun=form1.chicun.value;
            if (chicun.indexOf("-")>0) cs=chicun.substr(chicun.indexOf("-")+1);
            if (dj.indexOf("-")>0) {
                var i;
                var aa=dj.split(";");
                if (parseFloat(aa[aa.length-1].substr(0,aa[aa.length-1].indexOf("-")))<=obj.value)
                    xdj=aa[aa.length-1].substr(aa[aa.length-1].indexOf("-")+1);
                else {
                    for (i = 0; i < aa.length-1; i++) {
                        if (parseFloat(aa[i].substr(0,aa[i].indexOf("-")))<=obj.value && parseFloat(aa[i+1].substr(0,aa[i+1].indexOf("-")))>obj.value) xdj=aa[i].substr(aa[i].indexOf("-")+1);
                    }
                }
                xdj=(parseFloat(xdj)+parseFloat(js))*parseFloat(cs)
                document.getElementById("dj").innerHTML=xdj;
                form1.zj.value=formatCurrency(xdj*obj.value);
            } else {
                xdj=(parseFloat(dj)+parseFloat(js))*parseFloat(cs)
                document.getElementById("dj").innerHTML=xdj;
                form1.zj.value=formatCurrency(xdj*obj.value);
            }
        }
        function formatCurrency(num) {
            if(isNaN(num))
                num = "0";
            num = num.toString().replace(/\$|\,/g,'');
            sign = (num == (num = Math.abs(num)));
            num = Math.floor(num*100+0.50000000001);
            cents = num%100;
            num = Math.floor(num/100).toString();
            if(cents<10)
                cents = "0" + cents;
            for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
                num = num.substring(0,num.length-(4*i+3))+','+
                    num.substring(num.length-(4*i+3));
            return (((sign)?'':'-') + num + '.' + cents);
        }

        window.addEventListener('message',function(e){
            var data=e.data;
            if (data.substr(0,1)=="3")
                if (data.substr(1)=="del") document.getElementById('file1').value=""; else document.getElementById('file1').value+=data.substr(1)+";";
            else
            if (data.substr(1)=="del") document.getElementById('file2').value=""; else document.getElementById('file2').value+=data.substr(1)+";";
        },false);

        var hzx = new Array();
        hzx[1] = "";
        hzx[2] = "";
        function set(n,v) {
            id = "hzx"+n;
            obj = document.getElementById(id);
            if(v == "单面"){
                hzx[n] = obj.value;
                obj.value="";
                obj.disabled=true;
            }else{
                obj.disabled=false;
                obj.value = hzx[n];
            }
        }

        function setjg(n)
        {
            ddh = $("#ddh").val();
            machine = $("#machine"+n).val();
            paper = $("#paper"+n).val();
            jldw = $("#jldw"+n).val();
            dsm = $("#dsm"+n).val();
            zsl = $("#zs"+n).val();
            jgid = "jg"+n;
//$("#test").html("getprice.php?type=1&ddh="+ddh+"&machine="+machine+"&paper="+paper+"&jldw="+jldw+"&dsm="+dsm);
            $.ajax({
                type: "get",
                url: "getprice.php?type=1&ddh="+ddh+"&machine="+machine+"&paper="+paper+"&jldw="+jldw+"&dsm="+dsm+"&zsl="+zsl,
                async : false,
                success: function(data){
                    data = parseFloat(data);
                    $("#"+jgid).val(data);
                },
                error: function(){
                    alert("价格获取失败");
                }
            });
        }

    </SCRIPT>
    <style type="text/css">
        body {
            background-color: #A5CBF7;
        }
        .style11 {font-size: 14px}
        .STYLE13 {font-size: 12px}
        .tstyle1 { background-color:#DDD; text-align:center}
    </style>
</head>

<body>

<form action="YSXMqt_mxdj_save.php" method="post" name="form1" id="form1" onSubmit="return checkForm()">
    <input type="button" onclick="javascript:window.opener.location.href='NS_new.php?ddh=<? echo $bh?>';window.close();" value="关闭返回" />
    <table width="100%" border="1" cellpadding="4" cellspacing="1" bordercolor="#000099" bgcolor="f6f6f6">
        <tr>
            <td height="222" valign="top">
                <table width="95%" height="153" border="0" align="center">
                    <tr>
                        <td height="27" class="STYLE13">订单号</td>
                        <td width="161" class="STYLE13"> <? echo $bh?> </td>
                        <input type="hidden" name="ddh" id="ddh" value="<? echo $bh?>" />
                        <input type="hidden" name="id" value="<? echo $id?>" />
                        <td width="394" align="right" class="STYLE13">&nbsp;</td>
                    </tr>

                    <tr>
                        <td width="83" height="27" class="STYLE13">产品</td>
                        <td class="STYLE13">
                            <select name="productname" onChange="window.location.href='YSXMqt_mxdjs.php?ddh=<? echo $bh?>&mxsid=<? echo $_GET["mxsid"]?>&pn='+this.options[this.selectedIndex].value;" >
                                <option value="-1">请选择...</option>
                                <? $rs1=mysql_query("select * from productnameset order by productname");
                                if($rs1)
                                    for ($i=0;$i<mysql_num_rows($rs1);$i++) {?>
                                        <option value="<? echo mysql_result($rs1,$i,1);?>" <? if ($pn==mysql_result($rs1,$i,1)) echo "selected";?> ><? echo mysql_result($rs1,$i,1);?></option>
                                    <? }?>
                            </select>
                        </td>
                        <td class="STYLE13">印件名称:
                            <input  type="text" size="25" name="pname" id="pname" value="<? echo $pname;?>" /></td>
                    </tr>

                    <tr>
                        <td width="83" height="27" class="STYLE13">数量</td>
                        <td class="STYLE13"><input  type="text" size=6 name="sl" id="sl" value="<? echo $sl==""?0:$sl;?>" onchange="slc(this);" /></td>
                        <td class="STYLE13">规格

                            <select name="chicun" >
                                <option value="A3" <? if ($chicun=="A3") echo "selected";?>>A3</option>
                                <option value="A4" <? if ($chicun=="A4") echo "selected";?>>A4</option>
                            </select></td>
                    </tr>
                    <tr>
                        <td height="27" colspan="3" class="STYLE13">

                            <div id="n1" style="display:<? if ($n1=="") echo "none";?>">
                                <input type="hidden" name="n1" value="<? echo $n1?>" />
                                <table width="100%" border="1" align="center" cellspacing="0" cellpadding="0" style="border-width:0px;border-collapse:collapse;border:1px solid #D3D3D3; border-bottom:1px solid #D3D3D3; font-size:12px">
                                    <tr>
                                        <td width="10%" class="tstyle1">构件</td>
                                        <td colspan="3" bgcolor="#FFFFCC" ><strong><? echo $n1?></strong></td>
                                        <td width="10%" class="tstyle1">文件上传</td>
                                        <td colspan="3"><input name="file1" type="text" id="file1" size=25 value="<? echo $file1?>" />
                                            <? if ($state=="新建") {?><a href="javascript:void(0)" onclick="javascript:window.open('<? echo $localftp?>/fileupdex.php?mxid=3&lx=file&machine=7600','<?//echo $bh.time().rand(100,999);?>','height=300px,width=400px,top=100px,left=100px,location=no,menubar=no,resizable=no,scrollbars=yes,status=no,titlebar=no,toolbar=no,directories=no');return false;"  class="a1">上传<!--至hp76--></a><!--<a href="javascript:void(0)" onclick="javascript:window.open('<? echo $localftp?>/fileup.php?mxid=3&lx=file&machine=10000','<?//echo $bh.time().rand(100,999);?>','height=300px,width=400px,top=100px,left=100px,location=no,menubar=no,resizable=no,scrollbars=yes,status=no,titlebar=no,toolbar=no,directories=no');return false;"  class="a1">上传至hp10000</a>--><? }?>
                                        </td>


                                    </tr>
                                    <tr>
                                        <td class="tstyle1">机器/颜色</td>
                                        <td colspan="3"><select name="machine1" id="machine1" onchange="setjg(1)" >
                                                <? $rs1=mysql_query("select * from b_machine order by machine");
                                                if($rs1)
                                                    for ($i=0;$i<mysql_num_rows($rs1);$i++) {?>
                                                        <option value="<? echo mysql_result($rs1,$i,1);?>" <? if ($machine1==mysql_result($rs1,$i,1)) echo "selected";?> ><? echo mysql_result($rs1,$i,1);?></option>
                                                    <? }?>
                                            </select></td>
                                        <!--
		  <td class="tstyle1">颜色</td>
		  <td><select name="color1" id="color1"><?
                                        //			foreach($colors as $color) {
                                        //				echo "<option value='".$color."' ";
                                        //				if ($color1 == $color) echo "selected";
                                        //				echo ">".$color."</option>";
                                        //			}?></select></td>
-->
                                        <td class="tstyle1">纸张</td>
                                        <td colspan="3"><select name="paper1" id="paper1" onchange="setjg(1)" >
                                                <?
                                                if(substr($dwdm,0,2) == '34'){
                                                    $rs2=mysql_query("select materialcode,materialname,specs from material where zzfy='3405' order by id");
                                                }else{
                                                    $rs2=mysql_query("select materialcode,materialname,specs from material where zzfy='$dwdm' order by id");
                                                }
                                                if($rs2)
                                                    for ($i=0;$i<mysql_num_rows($rs2);$i++) {?>
                                                        <option value="<? echo mysql_result($rs2,$i,0);?>" <? if ($paper1==mysql_result($rs2,$i,0)) echo "selected";?> ><? echo mysql_result($rs2,$i,1);if($dwdm<>'3301') echo '['.mysql_result($rs2,$i,2).']';?></option>
                                                    <? } ?>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td class="tstyle1">单位</td>
                                        <td width="15%"><select name="jldw1" id="jldw1" onchange="setjg(1)" >
                                                <option value="P" <? if ($jldw1=="P") echo "selected";?>>P</option>
                                                <option value="本" <? if ($jldw1=="本") echo "selected";?>>本</option>
                                                <option value="根" <? if ($jldw1=="根") echo "selected";?>>根</option>
                                            </select></td>
                                        <td width="10%" class="tstyle1">单双面</td>
                                        <td width="15%"><select name="dsm1" id="dsm1" onchange="set(1,this.value);setjg(1);">
                                                <option value="双面" <? if ($dsm1=="双面") echo "selected";?>>双面</option>
                                                <option value="单面" <? if ($dsm1=="单面") echo "selected";?>>单面</option>
                                            </select></td>
                                        <td class="tstyle1">横纵向</td>
                                        <td width="15%"><!--<select name="hzx1" id="hzx1">
                    <option value="横向" <? //if ($hzx1=="横向") echo "selected";?>>横向</option>
                    <option value="纵向" <? //if ($hzx1=="纵向") echo "selected";?>>纵向</option>
		  </select>-->
                                            <input name="hzx1" id="hzx1" value="<? echo $hzx1; ?>" list="hzx" size="8" />
                                            <datalist id="hzx">
                                                <option value="横向">
                                                <option value="纵向">
                                            </datalist>
                                        </td>
                                        <td width="10%" class="tstyle1">单价</td>
                                        <td width="15%"><input name="jg1" type="text" id="jg1" size="4" onchange="form1.je1.value=form1.pnum1.value*form1.sl1.value*form1.jg1.value;" value="<? echo $jg1==""?$jg:$jg1?>" /></td>
                                    </tr>
                                    <tr>
                                        <td class="tstyle1">P数</td>
                                        <td><input name="pnum1" type="text" id="pnum1" size="4" onchange="form1.zs1.value=form1.pnum1.value*form1.sl1.value;setjg(1);form1.je1.value=form1.pnum1.value*form1.sl1.value*form1.jg1.value;" value="<? echo $pnum1?>"/></td>
                                        <td class="tstyle1">份数</td>
                                        <td><input name="sl1" type="text" id="sl1" size="4" onchange="form1.zs1.value=form1.pnum1.value*form1.sl1.value;setjg(1);form1.je1.value=form1.pnum1.value*form1.sl1.value*form1.jg1.value;" value="<? echo $sl1?>"/></td>
                                        <td class="tstyle1">总数</td>
                                        <td><input name="zs1" type="text" id="zs1" size="4" readonly="readonly"  value="<? echo $sl1*$pnum1?>"/></td>
                                        <td class="tstyle1">金额</td>
                                        <td><input name="je1" type="text" id="je1" size="8" readonly="readonly" value="<? echo $sl1*$pnum1*$jg1?>"/></td>
                                    </tr>
                                </table>
                            </div>
                            <div id="n2" style="display:<? if ($n2=="") echo "none";?>"><br>
                                <input type="hidden" name="n2" value="<? echo $n2?>" />
                                <table width="100%" border="1" align="center" cellspacing="0" cellpadding="0" style="border-width:0px;border-collapse:collapse;border:1px solid #D3D3D3; border-bottom:1px solid #D3D3D3; font-size:12px">
                                    <tr>
                                        <td width="10%" class="tstyle1">构件</td>
                                        <td colspan="3" bgcolor="#FFFFCC"><strong><? echo $n2?></strong></td>
                                        <td width="10%" class="tstyle1">文件上传</td>
                                        <td colspan="3"><input name="file2" type="text" id="file2" size=25  value="<? echo $file2?>"/>
                                            <? if ($state=="新建") {?><a href="javascript:void(0)" onclick="javascript:window.open('<? echo $localftp?>/fileupdex.php?mxid=4&lx=file&machine=7600','<?//echo $bh.time().rand(100,999);?>','height=300px,width=400px,top=100px,left=100px,location=no,menubar=no,resizable=no,scrollbars=yes,status=no,titlebar=no,toolbar=no,directories=no');return false;"  class="a1">上传<!--至hp76--></a><!--<a href="javascript:void(0)" onclick="javascript:window.open('<? echo $localftp?>/fileup.php?mxid=4&lx=file&machine=10000','<?//echo $bh.time().rand(100,999);?>','height=300px,width=400px,top=100px,left=100px,location=no,menubar=no,resizable=no,scrollbars=yes,status=no,titlebar=no,toolbar=no,directories=no');return false;"  class="a1">上传至hp10000</a>--><? }?></td>
                                    </tr>
                                    <tr>
                                        <td class="tstyle1">机器/颜色</td>
                                        <td colspan="3"><select name="machine2" id="machine2" onchange="setjg(2)" >
                                                <?
                                                if($rs1)
                                                    for ($i=0;$i<mysql_num_rows($rs1);$i++) {?>
                                                        <option value="<? echo mysql_result($rs1,$i,1);?>" <? if ($machine2==mysql_result($rs1,$i,1)) echo "selected";?> ><? echo mysql_result($rs1,$i,1);?></option>
                                                    <? }?>
                                            </select></td>
                                        <!--
		  <td class="tstyle1">颜色</td>
		  <td><select name="color2" id="color2"><?
                                        //			foreach($colors as $color) {
                                        //				echo "<option value='".$color."' ";
                                        //				if ($color2 == $color) echo "selected";
                                        //				echo ">".$color."</option>";
                                        //			}?></select></td>
-->
                                        <td class="tstyle1">纸张</td>
                                        <td colspan="3"><select name="paper2" id="paper2" onchange="setjg(2)" >
                                                <?
                                                if($rs1)
                                                    for ($i=0;$i<mysql_num_rows($rs2);$i++) {?>
                                                        <option value="<? echo mysql_result($rs2,$i,0);?>" <? if ($paper2==mysql_result($rs2,$i,0)) echo "selected";?> ><? echo mysql_result($rs2,$i,1);if($dwdm <> '3301') echo '['.mysql_result($rs2,$i,2).']';?></option>
                                                    <? }?>
                                            </select></td>
                                    </tr>

                                    <tr>
                                        <td class="tstyle1">单位</td>
                                        <td width="15%"><select name="jldw2" id="jldw2"  onchange="setjg(2)" >
                                                <option value="P" <? if ($jldw2=="P") echo "selected";?>>P</option>
                                                <option value="本" <? if ($jldw2=="本") echo "selected";?>>本</option>
                                                <option value="根" <? if ($jldw2=="根") echo "selected";?>>根</option>
                                            </select></td>
                                        <td width="10%" class="tstyle1">单双面</td>
                                        <td width="15%"><select name="dsm2" id="dsm2" onchange="set(2,this.value);setjg(2);">
                                                <option value="双面" <? if ($dsm2=="双面") echo "selected";?>>双面</option>
                                                <option value="单面" <? if ($dsm2=="单面") echo "selected";?>>单面</option>
                                            </select></td>
                                        <td class="tstyle1">横纵向</td>
                                        <td width="15%"><!--<select name="hzx2" id="hzx2">
                    <option value="横向" <? //if ($hzx2=="横向") echo "selected";?>>横向</option>
                    <option value="纵向" <? //if ($hzx2=="纵向") echo "selected";?>>纵向</option>
		  </select>-->
                                            <input name="hzx2" id="hzx2" value="<? echo $hzx2; ?>" list="hzx" size="8"/>
                                        </td>
                                        <td width="10%" class="tstyle1">单价</td>
                                        <td width="15%"><input name="jg2" type="text" id="jg2" size="4" onchange="form1.je2.value=form1.pnum2.value*form1.sl2.value*form1.jg2.value;" value="<? echo $jg2==""?$jg:$jg2?>" /></td>
                                    </tr>
                                    <tr>
                                        <td class="tstyle1">P数</td>
                                        <td><input name="pnum2" type="text" id="pnum2" size="4" onchange="form1.zs2.value=form1.pnum2.value*form1.sl2.value;setjg(2);form1.je2.value=form1.pnum2.value*form1.sl2.value*form1.jg2.value;" value="<? echo $pnum2?>"/></td>
                                        <td class="tstyle1">份数</td>
                                        <td><input name="sl2" type="text" id="sl2" size="4" onchange="form1.zs2.value=form1.pnum2.value*form1.sl2.value;setjg(2);form1.je2.value=form1.pnum2.value*form1.sl2.value*form1.jg2.value;" value="<? echo $sl2?>"/></td>
                                        <td class="tstyle1">总数</td>
                                        <td><input name="zs2" type="text" id="zs2" size="4" readonly="readonly"  value="<? echo $sl2*$pnum2?>"/></td>
                                        <td class="tstyle1">金额</td>
                                        <td><input name="je2" type="text" id="je2" size="8" readonly="readonly" value="<? echo $sl2*$pnum2*$jg2?>"/></td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td height="33" colspan="3"><div align="center">
                                <? if ($state=="新建") {?>
                                    <input type="submit" name="Submit" value="保 存">
                                <? }?>
                            </div></td>
                    </tr>
                </table>
                <? if ($id<>"" and $state=="新建") {
                    echo "<a href='#' class='nav' onClick='javascript:window.open(\"YSXMqt_mxdjs_hd.php?ddh={$bh}&pn={$pn}&mxids=".$id."\", \"HT_dhdj22\", \"toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=yes,width=850,height=410,left=400,top=150\")'>【增加后加工】</a>&nbsp;&nbsp;";

                    echo "<a href='#' class='nav' onClick='javascript:window.open(\"YSXMqt_mxdjs_fm.php?ddh={$bh}&pn={$pn}&mxids=".$id."\", \"HT_dhdj22\", \"toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=yes,width=850,height=410,left=400,top=150\")'>【增加覆膜工艺】</a>&nbsp;&nbsp;";

                }
                ?>

            </td>
        </tr>
        <? if ($id<>"") {?>
            <tr>
                <td>
                    <table cellspacing="0" cellpadding="0" rules="all" border="1" id="gvOrder" style="border-width:0px;width:100%;border-collapse:collapse;border:1px solid #D3D3D3; border-bottom:1px solid #D3D3D3; font-size:12px">
                        <tbody><tr class="td_title" style="height:30px;">

                            <th width="132"  align="center" scope="col">后加工方式</th>
                            <th width="22"  align="center" scope="col">成品尺寸</th>
                            <th width="35"  align="center" scope="col">单位</th>
                            <th width="64"  align="center" scope="col">数量</th>
                            <th width="64" align="center" scope="col">加工单价</th>
                            <th width="93"  align="center" scope="col">加工金额</th>
                            <th width="64"  align="center" scope="col">备注</th>
                        </tr>
                        <? $rsmx=mysql_query("select * from order_mxqt_hd where mxid=$id");
                        for($i=0;$i<mysql_num_rows($rsmx);$i++){  ?>
                            <tr class="td_title" style="height:30px;">

                                <td class="td_content" align="center" ><? echo mysql_result($rsmx,$i,"jgfs");/*if (($state=="新建") or ($_SESSION["FBCW"]=="1" and date('m',strtotime(mysql_result($rs,0,"ddate")))==date('m')))*/ echo "<a href='#' class='nav' onClick='javascript:window.open(\"YSXMqt_mxdjs_hd.php?ddh={$bh}&pn={$pn}&mxids={$id}&mxhdid=".mysql_result($rsmx,$i,"id")."\", \"HT_dhdj33\", \"toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=yes,width=850,height=410,left=400,top=150\")'> [修改]</a>";
                                    if ($state=="新建") {?>
                                        <a href='?deletype=1&deleid=<? echo mysql_result($rsmx,$i,"id");?>&ddh=<? echo $bh;?>&mxsid=<? echo $id?>'>[删除]</a>
                                    <? }
                                    ?>
                                </td>
                                <td align="center" class="td_content" ><? echo mysql_result($rsmx,$i,"cpcc");?></td>
                                <td class="td_content" align="center" ><? echo mysql_result($rsmx,$i,"jldw");?></td>
                                <td class="td_content" align="center" ><? echo mysql_result($rsmx,$i,"sl");?></td>
                                <td align="center" class="td_content"><? echo mysql_result($rsmx,$i,"jg");?></td>
                                <td align="center" class="td_content"><? echo mysql_result($rsmx,$i,"sl")*mysql_result($rsmx,$i,"jg");?></td>
                                <td class="td_content" align="center" ><? echo mysql_result($rsmx,$i,"memo");?></td>
                            </tr>
                        <? }?>
                        </tbody></table>

                    <table cellspacing="0" cellpadding="0" rules="all" border="1" id="gvOrder" style="border-width:0px;width:100%;border-collapse:collapse;margin-top:10px;border:1px solid #D3D3D3; border-bottom:1px solid #D3D3D3; font-size:12px">
                        <tbody><tr class="td_title" style="height:30px;">

                            <th width="132"  align="center" scope="col">覆膜方式</th>
                            <th width="22"  align="center" scope="col">成品尺寸</th>
                            <th width="35"  align="center" scope="col">单位</th>
                            <th width="64"  align="center" scope="col">数量</th>
                            <th width="64" align="center" scope="col">加工单价</th>
                            <th width="93"  align="center" scope="col">加工金额</th>
                            <th width="64"  align="center" scope="col">备注</th>
                        </tr>
                        <? $fmmx=mysql_query("select * from order_mxqt_fm where mxid=$id");

                        for($i=0;$i<mysql_num_rows($fmmx);$i++){  ?>
                            <tr class="td_title" style="height:30px;">

                                <td class="td_content" align="center" ><? echo mysql_result($fmmx,$i,"fmfs");
                                    echo "<a href='#' class='nav' onClick='javascript:window.open(\"YSXMqt_mxdjs_fm.php?ddh={$bh}&pn={$pn}&mxids={$id}&mxhdid=".mysql_result($fmmx,$i,"id")."\", \"HT_dhdj33\", \"toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=yes,width=850,height=410,left=400,top=150\")'> [修改]</a>";
                                    if ($state=="新建") {?>
                                        <a href='?deletype=2&deleid=<? echo mysql_result($fmmx,$i,"id");?>&ddh=<? echo $bh;?>&mxsid=<? echo $id?>'>[删除]</a>
                                    <? }
                                    ?>
                                </td>
                                <td align="center" class="td_content" ><? echo mysql_result($fmmx,$i,"cpcc");?></td>
                                <td class="td_content" align="center" ><? echo mysql_result($fmmx,$i,"jldw");?></td>
                                <td class="td_content" align="center" ><? echo mysql_result($fmmx,$i,"sl");?></td>
                                <td align="center" class="td_content"><? echo mysql_result($fmmx,$i,"jg");?></td>
                                <td align="center" class="td_content"><? echo mysql_result($fmmx,$i,"sl")*mysql_result($fmmx,$i,"jg");?></td>
                                <td class="td_content" align="center" ><? echo mysql_result($fmmx,$i,"memo");?></td>
                            </tr>
                        <? }?>
                        </tbody></table>
                </td>
            </tr>
        <? }?>
    </table>
</form>
</body>
</html>
