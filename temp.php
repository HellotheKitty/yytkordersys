<?php
require("inc/conn.php");
header("Content-Type:text/html;charset=utf-8");
if($_POST){
	switch($_POST["type"]){
		case '1':
			mysql_query("update order_mainqt set khmc='".$_POST["khmc"]."' where ddh='".$_POST["ddh"]."'",$conn);
			break;
		case '2':
			$kh = mysql_query("select khmc,lxr,lxdh,lxdz from base_kh where khmc='".$_POST["khmc"]."'",$conn);
			if($kh && mysql_num_rows($kh)>0){
				$kharr = mysql_fetch_array($kh);
				$khmc = $kharr["khmc"];
				$lxr = $kharr["lxr"];
				$lxdh = $kharr["lxdh"];
				$lxdz = $kharr["lxdz"];
				mysql_query("update order_mainqt set khmc='$khmc',shr='$lxr',shdh='$lxdh',shdz='$lxdz' where ddh='".$_POST["ddh"]."'",$conn);
			}
			break;
	}
}
?>
<form method="post">
<select name="type">
<option value="1">更新无余额客户为有余额客户（同一客户）</option>
<option value="2">原客户名错误，修改为正确的客户名</option>
</select>
<br>
【完整、正确的】订单号：<input type="text" name="ddh" id="ddh" />
<br>
【完整、正确的】新客户名<input type="text" name="khmc" id="khmc" />
<br>
<input type="submit" value="确定" />
</form>
