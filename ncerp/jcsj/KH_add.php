<? require("../../inc/conn.php");require("../../inc/hanzitosx.php");?>
<? session_start();
header("Content-Type:text/html;charset=utf-8");
if ($_SESSION["OK"]<>"OK") { 
echo "<script language=JavaScript>{window.location.href='../error.php';}</script>";
exit;
}?>
<?
	$dwdm = substr($_SESSION["GDWDM"],0,4);
if ($_POST["khmc"]<>"") {

	if ($_POST["khmc"]<>$_POST["khmc0"]) {
		$rss=mysql_query("select xsbh from base_kh where khmc='".$_POST["khmc"]."'",$conn);
//		$rss=mysql_query("select xsbh from base_kh where khmc='".$_POST["khmc"]."' or mpzh='".$_POST["mpzh"]."'",$conn);		
		if (mysql_num_rows($rss)>0) {
			echo "<script language=JavaScript>alert('客户名称已经存在，所属销售编号是：".mysql_result($rss,0,0)."，请联系沟通。不能增加重复用户名称！');window.close();</script>";
			exit;
		}
//		$ddrs = mysql_query("select * from order_mainqt where khmc='".$_POST["khmc0"]."'",$conn);
//		if($ddrs && mysql_num_rows($ddrs)>0){
//			mysql_query("update order_mainqt set khmc='".$_POST["khmc"]."' where khmc='".$_POST["khmc0"]."'",$conn);
//			mysql_query("update order_zh set khmc='".$_POST["khmc"]."' where khmc='".$_POST["khmc0"]."'",$conn);
//		}
	}

//自动生成编号
	$py = new py_class();
	$_tempBh = strtoupper($py->str2py($_POST["khmc"]));
	unset($py);
	$_tempRs = mysql_query("select id from base_kh where mpzh='$_tempBh'");
	$_i = 1;
	$_tempBh1 = $_tempBh;
	while(mysql_num_rows($_tempRs)>0){
		$_tempBh = $_tempBh1.$_i;
		$_i++;
		$_tempRs = mysql_query("select id from base_kh where mpzh='$_tempBh'");
	}

	if ($_POST["mpzh0"]=="") {
//		if ($_POST["mpzh"]=="") {
//			echo "<script language=JavaScript>alert('客户编号不能为空，请重新输入。保存失败！');window.location.href='KH_add.php';</script>";
//			exit;
//		}  
		mysql_query("insert into base_kh (id,khmc,lxr,lxdh,lxdz,kp_sm,memo,mpzh,xsbh,zctime,qq,hyjb,jg,gdzk,waixie) values (0,'".$_POST["khmc"]."','".$_POST["lxr"]."','".$_POST["lxdh"]."','".$_POST["lxdz"]."','".$_POST["kpsm"]."','".$_POST["memo"]."','"./*$_POST["mpzh"]*/$_tempBh."','".$_SESSION["YKOAUSER"]."',now(),'".$_POST["qq"]."','".$_POST["hyjb"]."','".$_POST["jg"]."','$dwdm','".$_POST["waixie"]."')");

        mysql_query("insert into kh_ye (id,zh,xm,mobile,depart,ye,xsbh,sdate,dwdm) values (0,'".$_tempBh."','".$_POST["lxr"]."','".$_POST["lxdh"]."','".$_POST["khmc"]."' , 0 , '".$_SESSION["YKOAUSER"]."',now() , '$dwdm')");

    } else {

        beginTransaction();

        $resupdate[] = mysql_query("update base_kh set khmc='".$_POST["khmc"]."',lxr='".$_POST["lxr"]."',lxdh='".$_POST["lxdh"]."',lxdz='".$_POST["lxdz"]."',kp_sm='".$_POST["kpsm"]."',memo='".$_POST["memo"]."',qq='".$_POST["qq"]."',hyjb='".$_POST["hyjb"]."',jg='".$_POST["jg"]."',waixie='".$_POST["waixie"]."' where REPLACE(REPLACE(mpzh, CHAR(10), ''), CHAR(13), '')='".$_POST["mpzh0"]."'");
		if ($_POST["khmc"]<>$_POST["khmc0"]) {
            $resupdate[] =mysql_query("update order_mainqt set khmc='".$_POST["khmc"]."' where khmc='".$_POST["khmc0"]."'",$conn);
            $resupdate[] =mysql_query("update order_zh set khmc='".$_POST["khmc"]."' where khmc='".$_POST["khmc0"]."'",$conn);
            $resupdate[] =mysql_query("update price_of_print set khmc='".$_POST["khmc"]."' where khmc='".$_POST["khmc0"]."'",$conn);
            $resupdate[] =mysql_query("update price_of_afterprocess set khmc='".$_POST["khmc"]."' where khmc='".$_POST["khmc0"]."'",$conn);
            $resupdate[] =mysql_query("update price_of_fumo set khmc='".$_POST["khmc"]."' where khmc='".$_POST["khmc0"]."'",$conn);
		}
        if(!transaction($resupdate)){
            echo "<script type='text/javascript'>alert('修改失败');</script>";

        }

    }
	echo "<script>window.opener.location.reload();window.close();</script>";
}


$mpzh=$_GET["mpzh"];
//$mpzh=str_replace(array("\r\n", "\r", "\n"), "", $mpzh);
$jg=0.50;
if ($mpzh<>"" ) {
	$rs=mysql_query("select * from base_kh where REPLACE(REPLACE(mpzh, CHAR(10), ''), CHAR(13), '') = '$mpzh'");
	$khmc=mysql_result($rs,0,"khmc");
	$lxr=mysql_result($rs,0,"lxr");
	$lxdh=mysql_result($rs,0,"lxdh");
	$qq=mysql_result($rs,0,"qq");
	$lxdz=mysql_result($rs,0,"lxdz");
	$kpsm=mysql_result($rs,0,"kp_sm");
	$memo=mysql_result($rs,0,"memo");
	$hyjb=mysql_result($rs,0,"hyjb");
	$jg=mysql_result($rs,0,"jg");
	$waixie=mysql_result($rs,0,"waixie");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>客户信息</title>
<SCRIPT language=JavaScript src="../../js/form.js"></SCRIPT>
<SCRIPT language=JavaScript>
function checkForm(){
	var tmpFrm = document.forms[0];
    var charBag = "-0123456789.";
	if (!checkNotNull(form1.jg, "价格不能空")) return false;
	if (!checkStrLegal(form1.jg, "价格格式不对", charBag)) return false;
//
//  var reg = /^[0-9a-zA-Z]+$/;
//  var str = document.getElementById("khmc").value;
//  if(!reg.test(str)){
//    alert("你输入的字符不是数字或者字母");
//    return false;
//  }

  return true;

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

<form action="" method="post" name="form1" id="form1" onSubmit="return checkForm()">
<table width="100%" border="1" cellpadding="4" cellspacing="1" bordercolor="#000099" bgcolor="f6f6f6">
	<tr>
	<td height="222" valign="top">
      <table width="95%" height="153" border="0" align="center">
            <tr>
	    <td width="21%" height="27" class="STYLE13"><? if($mpzh!="") echo "客户编号";?></td>
              <td width="79%" class="STYLE13"> <? echo $mpzh;//==""?"<input name='mpzh' type=text size='10' />*":$mpzh?> </td>
			 <input type="hidden" name="mpzh0" value="<? echo $mpzh?>" />
			<input type="hidden" name="khmc0" value="<? echo $khmc?>" />
            </tr>
     
            <tr>
              <td height="27" colspan="2" class="STYLE13">
              
              <div id="n1" >
         
              <table width="100%" border="1" align="center" cellspacing="0" cellpadding="0" style="border-width:0px;border-collapse:collapse;border:1px solid #D3D3D3; border-bottom:1px solid #D3D3D3; font-size:12px">
                
                <tr>
                  <td width="21%" class="tstyle1">客户名称</td>
                  <td><input name="khmc" type="text" id="khmc" size="50"  value="<? echo $khmc?>" required="required"/>*</td>
                  </tr>
               
                <tr>
                  <td class="tstyle1">联系人</td>
                  <td><input name="lxr" type="text" id="lxr" size="10" value="<? echo $lxr?>"/> 
                  电话： 
                    <input name="lxdh" type="text" id="sl" size="10" value="<? echo $lxdh?>" required="required"/>
                    QQ：
                    <input name="qq" type="text" id="sl3" size="10"  value="<? echo $qq?>"/></td>
                  </tr>
                <tr>
                  <td class="tstyle1">地址</td>
                  <td><input name="lxdz" type="text" id="lxdz" size="50" value="<? echo $lxdz?>" /></td>
                  </tr>
                   <tr>
                  <td class="tstyle1">客服备注</td>
                  <td><input name="kpsm" type="text" id="memo" size="50"  value="<? echo $kpsm?>"/></td>
                  </tr>
                  <tr>
                  <td class="tstyle1">生产说明</td>
                  <td><input name="memo" type="text" id="memo" size="50"  value="<? echo $memo?>"/></td>
                  </tr>
                  <tr>
                  <td class="tstyle1">会员级别</td>
                  <td><input name="hyjb" type="text" id="hyjb" size="10"  value="<? echo $hyjb?>"/></td>
                  </tr>
                  <tr>
                  <td class="tstyle1">打印价格</td>
                  <td><input name="jg" type="text" id="jg" size="10"  value="<? echo $jg?>"/></td>
                  </tr>
                  <tr>
                  <td class="tstyle1">共享客户到</td>
		  <td><select name="waixie"><option value="">不共享</option><option value="3405" <?if ($waixie=='3405') echo "selected"?>>北京中心店</option></select><?if(substr($dwdm,0,2)=='34') echo "对于添加需要到中心店打印的<font color='red'>外协</font>客户，请务必选择【<font color='red'>北京中心店</font>】"?></td>
                  </tr>
              </table>
              </div>
             
              </td>
            </tr>
            <tr>
              <td height="33" colspan="2"><div align="center">
              
                <input type="submit" name="Submit" value="保 存"> 
              
              </div>
              </td>
            </tr>
	    </table>
        
    </td>
  </tr>
</table>
</form>

</body>
</html>
