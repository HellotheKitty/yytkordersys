<?
require("../inc/conn.php");
if($_POST["btn1"]<>""){
//	print_r($_POST);
	$khid = $_POST["khid"];
	$khmc = $_POST["khmc"];
	$dsm = $_POST["dsm"];
	$machine = $_POST["machine"];
	$materialid = $_POST["materialid"];
	$min = $_POST["min"];
	$max = $_POST["max"];
	$price = $_POST["price"];
	$unit = $_POST["unit"];
	$testrs = mysql_query("select id from price_of_print where khid=$khid and materialid=$materialid and min=$min and max=$max and dsm='$dsm' and unit='$unit'",$conn);
	if($testrs && mysql_num_rows($testrs) > 0){
		echo "<script>alert('存在相同条件的价格条目，请核对')</script>";
	}else{
		$materialrs_insert = mysql_query("select * from material where id=$materialid",$conn);
		$specs_insert = mysql_result($materialrs_insert,0,"Specs");
		$materialname_insert = mysql_result($materialrs_insert,0,"MaterialName");
		mysql_query("insert into price_of_print values ('','$khmc',$khid,'$dsm','$machine',$materialid,'$specs_insert','$materialname_insert',$min,$max,'$price','$unit')",$conn);
	}
}
if($_GET["khid"]){
	$khid = $_GET["khid"];
	$khid = 192;
}
$khid = 192;
$printrs = mysql_query("select * from price_of_print where khid=$khid",$conn);
$machiners = mysql_query("select * from b_machine",$conn);
$materialrs = mysql_query("select * from material where zzfy=3301",$conn);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<style>
table {
	border-collapse: collapse;
	border: none;
}
th, td {
	border: solid 1px;
	text-align: center;
}
input {
	text-align: center;
}
</style>
<script src="../js/jquery-1.8.3.min.js"></script>
<script>
function updateprice(id,price){
	
}
isedit = true;
$(document).ready(function(){
	$("td").click(function(){
		if(this.id=="" || isedit==false) return;
		color = $("#"+this.id).css("background");
		$("#"+this.id).css("background","green");
		newp = prompt("输入新价格");
		if(newp){
			if(isNaN(newp)){
				alert("价格必须为数字");
				return;
			}
			$.ajax({
				type: "get",
				url: "price_update.php?type=print&id="+this.id+"&np="+newp,
				success: function(data){
					if(data > 0){
						alert("更新成功");
						$("#"+data).html(newp);
					}else{
						alert("失败，重试");
					}
				},
				error: function(){
					alert("失败，重试");
				}
			});
		}
		$("#"+this.id).css("background",color);
	});

	$("#btn1").click(function(){
		if(isedit){
//			alert(isedit);
			isedit = false;
			$("#btn1").attr("value","打开编辑");
		}else{
//			alert(isedit);
			isedit = true;
			$("#btn1").attr("value","关闭编辑");
		}
	});
});
</script>
</head>

<body>
<table>
	<tr>
    	<th>单/双</th>
    	<th>机型及颜色</th>
    	<th>物料编码</th>
    	<th>规格</th>
    	<th>纸张</th>
    	<th>下限</th>
    	<th>上限</th>
    	<th>单价</th>
    	<th>单位</th>        
    </tr>
    <? $i = 0; while($row = mysql_fetch_array($printrs,MYSQL_ASSOC)) { $i++; ?>
    <tr <? if($i % 2 == 1) echo "style='background:#EFEFEF'"?>>
    	<td><? echo $row["dsm"]?></td>
    	<td><? echo $row["machine"]?></td>
    	<td><? echo $row["materialid"]?></td>
    	<td><? echo $row["specs"]?></td>
    	<td><? echo $row["materialname"]?></td>
    	<td><? echo $row["min"]?></td>
    	<td><? echo $row["max"]?></td>
	<td id="<?echo $row["id"]?>" class="hand"><? echo $row["price"]?></td>
    	<td><? echo $row["unit"]?></td>
    </tr>
    <? } ?>
</table>
<br><br>
<div>
<form method="post">
<input type="hidden" name="khid" value="<? echo $khid ?>" />
<input type="hidden" name="khmc" value="<? echo $row["khmc"] ?>" />
单双面：<select name="dsm">
	<option value="单面">单面</option>
	<option value="双面">双面</option>
</select>　
机型及颜色：<select name="machine">
<? while ($machine = mysql_fetch_array($machiners,MYSQL_ASSOC))
	echo "<option value='".$machine["machine"]."'>".$machine["machine"]."</option>";
?>
</select>　
纸张：<select name="materialid">
<? while($material = mysql_fetch_array($materialrs,MYSQL_ASSOC)) 
	echo "<option value='".$material["id"]."'>".$material["MaterialName"]."[".$material["Specs"]."]"."</option>";
?>
</select>　
<br>
下限：<input type="text" name="min" value="1" size="1" />　
上限：<input type="text" name="max" value="10000" size="1" />　
价格：<input type="text" name="price" value="" size="1" />　
单位<input type="text" name="unit" value="P" size="1" />　
<input type="submit" name="btn1" value="确认添加" />
</form>
</div>
</body>
</html>
