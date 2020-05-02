<? session_start();
require("../inc/conn.php");?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=2.0,user-scalable=yes">
<title>客户管理</title>
<? 

if ($_POST["zh"]<>"") {    //修改提交
$zh=$_POST["zh"];
$depart=$_POST["depart"];
$xm=$_POST["xm"];
$lxdz=$_POST["lxdz"];
$mobile=$_POST["mobile"];
$memo=$_POST["memo"];
$kfmemo=$_POST["kfmemo"];

 mysql_query("update base_kh set khmc='$depart',lxr='$xm',lxdh='$mobile',memo='$memo',kp_sm='$kfmemo',qq='".$_POST["qq"]."',email='".$_POST["email"]."',lxdz='$lxdz' where mpzh='$zh'",$conn);

echo "<script>alert('保存完成');</script>";

}
if ($_POST["zh2"]<>"") {    //修改提交
$zh=$_POST["zh2"];
$dmessage=$_POST["dmessage"];

mysql_query("insert into base_kh_log (zh,dname,dtime,dmessage) values ('$zh','".$_SESSION["KFUSER"]."',now(),'$dmessage')",$conn);

echo "<script>alert('保存完成');</script>";

}

if ($_GET["YIKAZH"]<>"") {
	if ($_GET["en"]=="0")
  		$rs=mysql_query("select mpzh zh,lxr xm,khmc depart,lxdh mobile,lxdz address,xsbh,zctime,memo,xsbh,QQ,email,kp_sm kfmemo from base_kh where mpzh='".($_GET["YIKAZH"])."'",$conn);
	else
  		$rs=mysql_query("select mpzh zh,lxr xm,khmc depart,lxdh mobile,lxdz address,xsbh,zctime,memo,xsbh,QQ,email,kp_sm kfmemo from base_kh where mpzh='".base_decode($_GET["YIKAZH"])."' or khmc='".base_decode($_GET["YIKAZH"])."'",$conn);
	
} else
  $rs=mysql_query("select mpzh zh,lxr xm,khmc depart,lxdh mobile,lxdz address,xsbh,zctime,memo,xsbh,QQ,email,kp_sm kfmemo from base_kh where id=".$_GET["ID"],$conn);

if (mysql_num_rows($rs)<1) {echo "账号错误！",base_decode($_GET["YIKAZH"]);exit;}

?>

<style type="text/css">
<!--
body {
  background-color: #A5CBF7;
}
.style11 {font-size: 14px}
.STYLE13 {font-size: 12px}
.STYLE14 {font-size: 12px; font-weight:bold}
.STYLE15 {font-size: 12px; color: #FF0000; }
-->
</style>
</head>
<SCRIPT language=JavaScript>
function checkForm(){
  var tmpFrm = document.forms[0];
    var charBag = "-0123456789.";
  
  return true;
}


function changeArea() {
  window.open('choosearea.php', 'selectorPersonWin', 'dependent,toolbar=no,location=no,status=no,menubar=no,resizable=no,scrollbars=no,width=520px,height=500px,left=100,top=100');
      
}

</SCRIPT>
<body>
<table width="820" border="1" cellpadding="4" cellspacing="1" bordercolor="#000099" bgcolor="f6f6f6" >
  <tr>
<td valign="top">
	<form action="#" method="post" ENCTYPE="multipart/form-data" name="form1" id="form1" onSubmit="return checkForm()">
     <table width="96%" height="277" border="1" cellspacing="0" align="center" style="border-collapse:collapse;">
    <tr>
              <td height="27" class="STYLE14" width="59">用户账号</td>
              <td width="225">
                <? echo mysql_result($rs,0,"zh");?>  
              </td>
  
      <input type="hidden" name="MAX_FILE_SIZE" value="100000000">
      <input type="hidden" name="zh" value="<? echo mysql_result($rs,0,"zh");?>">              
              <td width="68" class="STYLE14">注册日期</td>
              <input type="hidden" name="ddh" value="<? echo $bh?>" />
              <td width="295" align="left" class="STYLE13"><? echo mysql_result($rs,0,"zctime");?></td>
          </tr>
          <tr>
              <td width="59" height="27" class="STYLE14">单位名</td>
            <td colspan="3" class="STYLE13"><input name="depart" type="text" id="depart" size="20" value="<? echo mysql_result($rs,0,"depart");?>">
            <a href='#' onClick='javascript:window.open("YKKF_taskhistory.php?fromuser=<? echo base_encode(mysql_result($rs,0,"zh"));?>", "HT_d", "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=yes,width=800,height=650,left=300,top=100");return false;'>任务记录</a>　</td>
            </tr>
            <tr>
              <td height="27" class="STYLE14">联系人</td>
              <td>
                <input name="xm" type="text" id="xm" size="10" value="<? echo mysql_result($rs,0,"xm");?>">
                </td>
              <td class="STYLE14">手机</td>
              <td width="295" class="STYLE13"><input name="mobile" type="text" id="mobile" size="11" value="<? echo mysql_result($rs,0,"mobile");?>">
              QQ:<input name="qq" type="text" id="qq" size="10" value="<? echo mysql_result($rs,0,"QQ");?>">
              		<a href='#' onclick='javascript:window.open("YK_kf_qqhistory.php?zh=<? echo base_encode(mysql_result($rs,0,"zh"));?>", "HT_d23", "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=yes,width=800,height=650,left=300,top=100");return false;'>记录</a>
              </td>
          </tr>
            
            <tr>
              <td height="27" class="STYLE14">地址</td>
              <td class="STYLE13"><input name="lxdz" type="text" id="lxdz" size="30" value="<? echo mysql_result($rs,0,"address");?>"></td>
              <td class="STYLE14">Email</td>
              <td width="295" class="STYLE13"><input name="email" type="text" id="email" size="13" value="<? echo mysql_result($rs,0,"email");?>"></td>
          </tr>
           
    
            <tr>
              <td height="27" class="STYLE14">生产备注</td>
              <td colspan="3" class="STYLE13">
              <textarea name="memo" id="memo" cols="75" rows="2"><? echo mysql_result($rs,0,"memo");?></textarea></td>
            </tr>
			 <tr>
              <td height="27" class="STYLE14">客服备注</td>
              <td colspan="3" class="STYLE13">
              <textarea name="kfmemo" id="kfmemo" cols="75" rows="2"><? echo mysql_result($rs,0,"kfmemo");?></textarea></td>
            </tr>

          <tr>

          <td height="27" class="STYLE14">客户所属客服</td>
              <td ></td>
              <td class="STYLE14">&nbsp;</td>
              <td width="295" class="STYLE13">&nbsp;</td>
            </tr>

           

            <tr>
              <td height="33" colspan="4" class="STYLE13" align="center">
                <? if($_GET["lx"]!="show") {?><input type="submit" name="Submit"  value="保存用户信息"> <? }?>
              </td>
            </tr>
        </table>
</form>
    </td>
  </tr>
  <tr>
<td valign="top" bgcolor="#FFFFFF">
<form action="#" method="post" name="form2" id="form2" >
      <input type="hidden" name="zh2" value="<? echo mysql_result($rs,0,"zh");?>">
     <table width="96%" height="277" border="1" cellspacing="0" align="center" style="border-collapse:collapse;">
            <tr>
              <td height="27" colspan="4" class="STYLE13"><span class="STYLE15">
              本次联系记录：</span></td>
          </tr>
            <tr>
              <td height="27" class="STYLE14">联系内容</td>
              <td colspan="3" class="STYLE13"><textarea name="dmessage" id="dmessage" cols="75" rows="3"></textarea></td>
            </tr>
            
            <tr>
              <td height="33" colspan="4" class="STYLE13"><div align="center">
                <input type="submit" name="Submit"  value="保存联系记录"> 
              </div></td>
            </tr>
            <tr>
              <td height="27" colspan="4" class="STYLE13">
                <table width="100%" border="0" class="STYLE13">
                <tr>
                  <td class="STYLE14">记录人</td>
                  <td class="STYLE14">日期</td>
                  <td class="STYLE14">内容</td>
                  
                </tr>
                <? $rsb=mysql_query("select * from base_kh_log where zh='".mysql_result($rs,0,"zh")."' order by dtime desc",$conn);
      for ($i=0;$i<mysql_num_rows($rsb);$i++) {?>
                <tr <? if (mysql_result($rsb,$i,"needcheck")==1 and mysql_result($rsb,$i,"checktime")=="") echo "bgcolor='#FFFF99'";?> >
                  <td>
                    <?php
                    
                        $kfbh = mysql_result($rsb,$i,"dname");
                        $sql = "SELECT xm FROM b_ry WHERE bh='$kfbh'";
                        $kf = mysql_query($sql, $conn);
                        echo mysql_num_rows($kf) > 0 ? mysql_result($kf, 0, 0) : '';

                    ?>
                  </td>
                  <td><? echo mysql_result($rsb,$i,"dtime");?></td>
                  <td><? echo mysql_result($rsb,$i,"dmessage");?></td>
                 
                </tr>
                <? }?>
                
              </table>
              
              </td>
            </tr>
        </table>
</form>
    </td>
  </tr>


  <tr>
<td valign="top">
     <table width="96%" height="50" border="1" cellspacing="0" align="center" style="border-collapse:collapse;">
            <tr>
              <td height="27" colspan="4" class="STYLE13"><span class="STYLE15">
              用户订单列表：</span><a href='javascript:window.open("Userzh_mx.php?userzh=<? echo base_encode(mysql_result($rs,0,"zh"));?>&readonly=1",window,"width=800,height=485,top=100,left=100,toolbar=no,menubar=no,scrollbars=no, resizable=yes,location=no, status=no");'>账户信息</a></td>
          </tr>
            <tr>
              <td height="27" colspan="4" class="STYLE13">
                <table width="100%" border="0" class="STYLE13">
                <tr>
                  <td class="STYLE14">订单号</td>
                  <td class="STYLE14">日期</td>
                  <td class="STYLE14">金额+快递费</td>
                  <td class="STYLE14">配送方式</td>
                  <td class="STYLE14">状态</td>
                  <td class="STYLE14">收货人</td>
                </tr>
                <? $rsb=mysql_query("select m.* from order_mainqt m where m.khmc='".mysql_result($rs,0,"depart")."' order by m.ddate desc",$conn);
      for ($i=0;$i<mysql_num_rows($rsb);$i++) {?>
                <tr <? if (mysql_result($rsb,$i,"state")<>'订单完成') echo "bgcolor='#FFFF99'";?> >
                  <td><a href='OrderLocate.php?id=<? echo mysql_result($rsb,$i,"id");?>&readonly=1'><? echo mysql_result($rsb,$i,"ddh");?></a></td>
                  <td><? echo mysql_result($rsb,$i,"ddate");?></td>
                  <td><? echo mysql_result($rsb,$i,"dje"),"+",mysql_result($rsb,$i,"kdje");?></td>
                  <td><? echo mysql_result($rsb,$i,"psfs"),"/",mysql_result($rsb,$i,"sjpsfs"),"/",mysql_result($rsb,$i,"kydh");?></td>
                  <td><? echo mysql_result($rsb,$i,"state"),"/",mysql_result($rsb,$i,"sdate");?></td>
                  <td><? echo mysql_result($rsb,$i,"shr"),"/",mysql_result($rsb,$i,"shdh");?></td>
                </tr>
                <? }?>
                
              </table></td>
            </tr>
        </table>
    </td>
  </tr>
</table>
</body>
</html>
<? 
$rs=null;
unset($rs);
$rss=null;
unset($rss);
?>