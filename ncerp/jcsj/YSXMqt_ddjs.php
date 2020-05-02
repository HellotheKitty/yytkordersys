<? require("../../inc/conn.php");?>
<? session_start();
if ($_SESSION["OK"]<>"OK") { 
echo "<script language=JavaScript>{window.location.href='../error.php';}</script>";
exit; 
}?>
<?
if ($_POST["button2"]<>'') {
	$je=$_POST["je"];$je2=$_POST["je2"];
	$skfs=$_POST["butt"];
    $khmc=$_POST["khmc"];
	$ddh=$_POST["ddh"];
	$skbz=$_POST["skbz"];
	mysql_query("update order_mainqt set state='待配送',skbz='$skbz' where instr('$ddh',ddh)>0");
	if ($_POST["butt"]=="预存扣款") {
		$rs=mysql_query("select ddh,dje+ifnull(kdje,0) from order_mainqt where  instr('$ddh',ddh)>0");
		while ($row=mysql_fetch_row($rs)) {
			mysql_query("insert into order_zh values (0,'$skfs',now(),0,".$row[1]>$je?$je:$row[1].",'订单结算','合并结算：$ddh',now(),'$khmc','$row[0]')");
			$je=$je-$row[1]>$je?$je:$row[1];
		}
	} else {
		$rs=mysql_query("select ddh,dje+ifnull(kdje,0) from order_mainqt where  instr('$ddh',ddh)>0");
		while ($row=mysql_fetch_row($rs)) {
			mysql_query("insert into order_zh values (0,'$skfs',now(),".$row[1]>$je?$je:$row[1].",".$row[1]>$je?$je:$row[1].",'订单结算','合并结算：$ddh',now(),'$khmc','$row[0]')");
			$je=$je-$row[1]>$je?$je:$row[1];
		}
	}
	if ($je2>0) mysql_query("insert into order_zh values (0,'$skfs',now(),-$je2,-$je2,'订单结算优惠','合并结算：$ddh',now(),'$khmc','$row[0]')");
	echo "<script>window.opener.location.reload();window.close();</script>";
	exit;
}


$ddh=$_GET["ddh"];
$rs=mysql_query("select khmc,ddh,dje,kdje,djje from order_mainqt where instr('$ddh',ddh)>0");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>订单明细</title>
<SCRIPT language=JavaScript src="../../js/form.js"></SCRIPT>
<SCRIPT language=JavaScript>
function checkForm(){
	var tmpFrm = document.forms[0];
    var charBag = "-0123456789.";
	if (!checkNotNull(form1.mc, "")) return false;
	if (!checkNotNull(form1.bm, "")) return false;
	if (!checkNotNull(form1.je, "")) return false;
	if (!checkStrLegal(form1.je, "", charBag)) return false;
	return true; }

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

</SCRIPT>
<style type="text/css">

.style11 {font-size: 14px}
.STYLE13 {font-size: 12px}
.tstyle1 { background-color:#DDD; text-align:center; height:25px}
</style>
</head>

<body>

<form action="" method="post" name="form1" id="form1" onSubmit="return checkForm()">
         
              <table width="100%" border="1" align="center" cellspacing="0" cellpadding="0" style="border-width:0px;border-collapse:collapse;border:1px solid #D3D3D3; border-bottom:1px solid #D3D3D3; font-size:12px">
                
                <tr>
                  <td width="14%" class="tstyle1">订单号</td>
                  <td width="27%" class="tstyle1">订单金额</td>
                  <td width="21%" class="tstyle1">配送费</td>
                  <td width="19%" class="tstyle1">预付定金</td>
                  <td width="19%" class="tstyle1">待结算</td>
                </tr>
                <? $khmc=mysql_result($rs,0,"khmc");$hj=0;$ddh="";
				    $rsye=mysql_query("SELECT ifnull(sum((ifnull(`order_zh`.`jf`, 0) - ifnull(`order_zh`.`df`, 0))),0) AS `ye` FROM `base_kh` `b` LEFT JOIN `order_zh` ON ((CONVERT (`b`.`khmc` USING utf8) = `order_zh`.`khmc`)) WHERE b.khmc ='$khmc'");
                  if($rsye && mysql_num_rows($rsye)>0)
                    {$yue=mysql_result($rsye,0,"ye");} else $yue=0;
				for ($i=0;$i<mysql_num_rows($rs);$i++) { 
					if ($khmc==mysql_result($rs,$i,"khmc")) {
						$ddh.=",".mysql_result($rs,$i,"ddh");?>
                   <tr>
                  <td ><? echo mysql_result($rs,$i,"ddh")?></td>
                  <td><? echo mysql_result($rs,$i,"dje")?></td>
                  <td><? echo mysql_result($rs,$i,"kdje")?></td>
                  <td><? echo mysql_result($rs,$i,"djje")?></td>
                  <td><? echo mysql_result($rs,$i,"dje")+mysql_result($rs,$i,"kdje")-mysql_result($rs,$i,"djje");$hj+=mysql_result($rs,$i,"dje")+mysql_result($rs,$i,"kdje")-mysql_result($rs,$i,"djje");?></td>
                  </tr>
                  <? } else echo "<tr><td colspan=5>客户名称不同，过滤订单【".mysql_result($rs,$i,"ddh")."】</td></tr>";}?>
                   <tr>
                  <td class="tstyle1">结算</td>
                  <td colspan="4">应收款：<? echo $hj;?>，收款金额：
                    <input name="je" type="text" value="<? echo $hj?>" size="5" onchange="javascript:je2.value=<? echo $hj?>-this.value;" />
                    元，优惠：
                    <input name="je2" type="text"  size="5" />
元，收款方式：
<select name="butt">
<option value="现金">现金</option>
                      <option value="支票">支票</option>
                      <option value="POS刷卡">POS刷卡</option>
                      <option value="汇款">汇款</option>
                      <? if ($yue>0) {?>
                      <option value="预存扣款">预存扣款</option>
                      <? }?>
                    </select>
                    <input type="hidden" name="khmc" value="<? echo $khmc?>" />
                    <input type="hidden" name="ddh" value="<? echo $ddh?>" />
                    
                    <br>收款备注：
                     <input type="text" name="skbz" id="skbz" value=""/>
                     <input type="submit" name="button2" id="button2" value="收款" /></td>
                  </tr>
              </table>
              
</form>

</body>
</html>
