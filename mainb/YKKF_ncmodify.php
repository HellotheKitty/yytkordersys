<? 
session_start();
require("inc/conn.php");
?>
<?

        $rs = mysql_query("select distinct temp_ry.xm,smd5,mx.Ffile,mbc,mbk,temp_ry.id tid,temp_ry.user,temp_ry.mbid,ry_kf.xm zzxm,wctime,temp_ry.pfs from nccheck left join ry_kf on dealry=kfbh,temp_ry left join order_mx mx on mx.Zfile=temp_ry.smd5 and ddh is null where temp_ry.id=temp_ry_id and nccheck.id=".$_GET["NCckid"],$conn);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
    <title>名片工坊名片订购专区-我的购物车</title>
    <link href="./css/CITICcss.css" rel="stylesheet" type="text/css">
    <link href="./css/service.css?12345" rel="stylesheet" type="text/css">
</head>
<script language="javascript">
 function modiit(b1,b2,b3,b4) {
 			var xmlHttpReq;
            if (typeof (XMLHttpRequest) != "undefined")
                xmlHttpReq = new XMLHttpRequest();
            else if (window.ActiveXObject)
                xmlHttpReq = new ActiveXObject("MSXML2.XMLHTTP.3.0");
            xmlHttpReq.open("POST", "YKCartmodi.php?jid=" + Math.round(Math.random() * 10000), false);
            xmlHttpReq.setRequestHeader("Content-Type","application/x-www-form-urlencoded;charset=UTF-8");
            xmlHttpReq.send("ID=" + b1 + "&lx=" + b2+ "&nr=" + b3+ "&zzr=" + b4);
            if (xmlHttpReq.status == 200) {
                var data = xmlHttpReq.responseText;
				alert(data);     //测试返回数据
                if (data.indexOf("Error") == 0) {
                    alert(data.replace("Error:",""));
                } else {
                    isOk = true;
                }
            }
}
</script>
<body style="">
<form name="form1" method="post" action="" id="form1">


<div style="padding:0px 10px 0px 10px;">
<br />
   
    <div class="s-navWrapper clearfix"><a href="javascript:window.history.go(0);" class="s-navigation s-refresh">刷新</a>
	    <a class="s-navigation" href="tempmng/temp_fontbase.php?lx=show" target="_blank">系统已有字体</a>
        <a class="s-navigation" href="javascript:" onclick='window.open("tempmng/temp_icons.php", "HT_dhdj", "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=yes,width=1000,height=900,left=300,top=100")'>图标管理</a>
	    <a class="s-navigation" href="ecard-style.php" target="_blank">电子名片模板</a>
		<a class="s-navigation" href="qrcode.php" target="_blank">二维码生成工具</a>
		
 	</div>　  

	<br>查找客户：    登录名<input name="t1" type="text" id="t1" size="12" />  姓名<input name="t3" type="text" id="t3" size="10" />  手机号<input name="t4" type="text" id="t4" size="10" /> 单位<input name="t2" type="text" id="t2" size="15" /> <input type="submit" name="button2" id="button2" value="查找" />　查找名片：    姓名<input name="t13" type="text" id="t13" size="10" />  手机号<input name="t14" type="text" id="t14" size="12" /> <input type="submit" name="button2" id="button2" value="查找" />
<div>
  <? if ($_POST["t1"]<>"" or $_POST["t2"]<>"" or $_POST["t3"]<>"" or $_POST["t4"]<>"") {
		$rs2=mysql_query("select zh,depart,id,dbname from v_base_user where zh like '%".$_POST["t1"]."%' and depart like '%".$_POST["t2"]."%' and xm like '%".$_POST["t3"]."%' and mobile like '%".$_POST["t4"]."%' order by zh limit 50",$conn);
		while ($row=mysql_fetch_row($rs2)) {
		echo "<a href='http://www.yikayin.com/pmc/checklogin.php?bs=".urlencode(iconv("utf-8","gb2312",$row[0]))."&ks=".md5(iconv("utf-8","gb2312","hzyk".$row[0]."winner"))."&zzuser=".$_SESSION["ZZUSER"]."&sys=".($row[3]=="yikab"?"":$row[3])."' target=_blank>$row[0]-$row[1]".($row[3]=="yikab"?"":"/".$row[3])."</a>&nbsp;&nbsp;";
		}
	}
    ?>
  <? if ($_POST["t13"]<>"" or $_POST["t14"]<>"" ) {
		$rs2=mysql_query("select id,user,xm from temp_ry where xm like '%".$_POST["t13"]."%' and mobile like '%".$_POST["t14"]."%' order by user,xm limit 20",$conn);
		while ($row=mysql_fetch_row($rs2)) {
		echo "<a href='YKfileupload.php?id=".$row[0]."&tj=zhuanqu' target=_blank>$row[1]-$row[2]</a>&nbsp;&nbsp;";
		}
	}
    ?>

  <? if(mysql_num_rows($rs)==0) {?>
  <table class="table_line_empty" cellspacing="0" cellpadding="0" rules="all" border="0" id="gvCart" style="border-width:0px;width:100%;border-collapse:collapse;border:1px solid #D3D3D3; border-bottom:0px;">
		<tbody><tr class="table_line_empty td_content" align="center" valign="middle" style="height:50px;">
			<td colspan="7"></td>
		</tr>
	</tbody></table>
    <? } else {?>
    <table cellspacing="0" cellpadding="0" rules="all" border="0" id="gvOrder" style="border-width:0px;width:100%;border-collapse:collapse;border:1px solid #D3D3D3; border-bottom:0px;">
		<tbody><tr class="td_title" style="height:30px;">
			<th width="246" align="center" scope="col">修改要求</th>
			<th width="368"  align="center" scope="col">样张</th>
			<th width="134" align="center" scope="col">姓名</th>
			<th width="151" align="center" scope="col">操作</th>
			<th width="72" align="center" scope="col">&nbsp;</th>
		</tr>
        <? $zje=0;
		while ($row=mysql_fetch_row($rs)) {?>
        <tr>
            <td class="td_content" align="center">
            <?  
            $rswt=mysql_query("select NCcheck.*,ry_kf.xm from NCcheck left join ry_kf on  dealry=kfbh where temp_ry_id=".$row[5]." order by fssj desc",$conn);
			
			for ($i=0;$i<mysql_num_rows($rswt);$i++) { 
			?>

            <?php if (substr(mysql_result($rswt,$i,"fssj"), 0, 10) == date('Y-m-d')): ?>
                <p style="text-align: left; text-indent: 1em"><span style="color: #555555"><? echo mysql_result($rswt,$i,"fssj")?></span> | <span style="color: #222222"><? echo mysql_result($rswt,$i,"msg"),mysql_result($rswt,$i,"dealry")!=""?"<font color='#FF0000'>By:".mysql_result($rswt,$i,"xm")."</font>":"";?></span></p>
            <?php else: ?>
                <p style="text-align: left; text-indent: 1em"><span style="color: #999999"><? echo mysql_result($rswt,$i,"fssj")?></span> | <span style="color: #888888"><? echo mysql_result($rswt,$i,"msg"),mysql_result($rswt,$i,"dealry")!=""?"<font color='#FF0000'>By:".mysql_result($rswt,$i,"xm")."</font>":"";?></span></p>
            <?php endif ?>
            <? }?>
			

            </td>
			<td class="td_content" align="center" ><span class="td_content" style="width:90px;" >
			  <? 
			  echo "<img src='http://www.yikayin.com/pmc/ossimg.php?object=".$row[1]."Z_S.jpg' style='border-color:DarkGray;border-width:1px;border-style:Solid;'/>";
			  echo "<img src='http://www.yikayin.com/pmc/ossimg.php?object=".$row[1]."F_S.jpg' style='border-color:DarkGray;border-width:1px;border-style:Solid;'/>";
			  //width:".($row[3]*2)."px; height:".($row[4]*2)."px;
			?>
			</span></td>
            <td class="td_content" align="center" >
                <?
               
                    echo $row[0].'<br><br>';
                
                ?>

                <a href='PreviewCheck.php?Src=<? echo $row[1]."Z_M.jpg"?>&F=<? echo $row[1]."F_M.jpg"?>' target=_blank>名片核对</a><br><br>
             <? 
				 if (mysql_num_rows($rswt)>0 && mysql_result($rswt,0,"dealry")=="") {?>
             <input type="button" onclick="if (confirm('确定不用修改？')) modiit('<? echo $row[5];?>',12);" name="button" id="button" value="不用修改" />
				 <?
			    }?>
				 
            </td>
             <td align="center" class="td_content" ><p>
             <a id='ddown<? echo $row[5];?>' title="<? if (mysql_num_rows($rswt) > 0) echo mysql_result($rswt,0,"dealry");?>" href='#' onclick='window.open("<? if ($row[7]==-1) {if (file_exists("Printit3/".$row[1].".pdf?".rand(4000000, 7000000)*rand(4000000, 7000000))) echo "Printit3/".$row[1].".pdf?".rand(4000000, 7000000)*rand(4000000, 7000000); else echo "Printit/".$row[1].".pdf?".rand(4000000, 7000000)*rand(4000000, 7000000);} else echo "Printing/".$row[1].".pdf?".rand(4000000, 7000000)*rand(4000000, 7000000);?>");return false;'>下载pdf</a></p>
               <p><? if (substr(mysql_result($rs,0,"pfs"),0,3)=="SOP") echo "<font color=red>小胶印，注意页数！</font><br>";?><input type=button value="上传已转曲pdf" onClick="window.open('YKfileupload.php?id=<? echo $row[5];?>&tj=<? echo $_GET["tj"];?>','actSwfUploadOpenWin1','dependent, toolbar=no,location=no,status=no,menubar=no,resizable=yes,scrollbars=auto,width=600,height=180,left=335.0,top=242.0');document.getElementById('button<? echo $row[5];?>').disabled='';"><br>
                 用acrobat转曲必须用另存为<font color="#FF0000">"PDF/X"！</font></p>
              <p>直接系统里修改不要点<input type="button" disabled="disabled" onclick="modiit('<? echo $row[5];?>',2);" name="button<? echo $row[5];?>" id="button<? echo $row[5];?>" value="完成" /></p></td>
             <td class="td_content" align="center" >用户：<? echo $row[6];?><a href='http://www.yikayin.com/pmc/checklogin.php?bs=<? echo urlencode(iconv("utf-8","gb2312",$row[6]));?>&ks=<? echo md5(iconv("utf-8","gb2312","hzyk".$row[6]."winner"));?>&zzuser=<? echo $_SESSION["ZZUSER"];?>' target="_blank">进入系统</a>
             <? echo "<br><br>处理人:",mysql_result($rs,0,"zzxm");
			 echo "<br>完成时间:",mysql_result($rs,0,"wctime");
			 ?>
             <br>
            
            </td>
		</tr>
        <? 
		}?>
	</tbody></table>
    	<? }?>
</div>
      

	<div class="c_t22">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tbody><tr>
    <td style="padding-top:15px; padding-bottom:15px;">
         </span></td>
  </tr>
</tbody></table>

</div>
</div>


</div>

</div>
</form>
</body></html>