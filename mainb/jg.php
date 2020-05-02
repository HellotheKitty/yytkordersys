<?php
require("../inc/conn.php");
session_start();
@$dwdm = substr($_SESSION["GDWDM"],0,4);
$aprs = mysql_query("select * from b_afterprocess order by id",$conn);
$zzrs = mysql_query("select * from material where zzfy=3301",$conn);
?>
<html>
<head>
<script type="text/javascript" src="../js/jquery-1.8.3.min.js"></script>
<style>
table
	{
            border-collapse: collapse;
            border: none;
	    width: 50%;
        }
td,th
        {
	    border: solid #000 1px;
	    text-align: center;
        }
</style>
<script>
var i=1;
var isedit=false;
function changeview(){
	if(i==0){
		$("#hd").css("display","none");
		$("#zz").css("display","block");
		$("#btn0").attr("value","切换到后道价格表");
		i=1;
	}else{
		$("#hd").css("display","block");
		$("#zz").css("display","none");
		$("#btn0").attr("value","切换到纸张价格表");
		i=0;
	}
}

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
				url: "jg_update.php?a="+this.id+"&np="+newp,
				success: function(data){
					alert(data);
				},
				error: function(){
					alert("失败，重试");
				}
			
			});
			$("#"+this.id).html(newp);
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
<div>
<input type="button" id="btn0" onClick="changeview()" value="切换到后道价格表"/>　　<input type="button" id="btn1" value="打开编辑" />
<br>
<br>
</div>
<div id="hd" style="display:none;">
<table>
<tr>
	<th colspan='8'><font size='5'>后道价格表</font></th>
</tr>
<tr>
	<th>方式</th>
	<th>尺寸</th>
	<th>单位</th>
	<th>级别1价格</th>
	<th>级别2价格</th>
	<th>级别3价格</th>
	<th>级别4价格</th>
	<th>级别5价格</th>
</tr>
<?$i = 0;while($apArr = mysql_fetch_array($aprs)){ $i++;?>
<tr <?if($i%2==1) echo 'style="background:#EFEFEF"'?>>
	<td><?echo $apArr["afterprocess"]?></td>
	<td><?echo $apArr["chicun"]?></td>
	<td><?echo $apArr["unit"]?></td>
	<td id="<?echo "h-".$apArr["id"]."-a"?>">0<?//echo $apArr["afterprocess"]?></td>
	<td id="<?echo "h-".$apArr["id"]."-b"?>">0<?//echo $apArr["afterprocess"]?></td>
	<td id="<?echo "h-".$apArr["id"]."-c"?>">0<?//echo $apArr["afterprocess"]?></td>
	<td id="<?echo "h-".$apArr["id"]."-d"?>">0<?//echo $apArr["afterprocess"]?></td>
	<td id="<?echo "h-".$apArr["id"]."-e"?>">0<?//echo $apArr["afterprocess"]?></td>
</tr>
<?}?>
</table>
</div>

<div id="zz">
<table>
<tr>
	<th colspan='4'><font size='5'>纸张价格表</font></th>
</tr>
<tr>
	<th>纸张名称</th>
	<th>纸张尺寸</th>
	<th>纸张重量</th>
	<th>价格</th>
</tr>
<?$i = 0;while($zzArr = mysql_fetch_array($zzrs)){$i++;?>
<tr <?if($i%2==1) echo 'style="background:#EFEFEF"'?>>
	<td><?echo $zzArr["MaterialName"]?></td>
	<td><?echo $zzArr["Specs"]?></td>
	<td><?echo $zzArr["weight"]?></td>
	<td>0<?//echo $zzArr["MaterialName"]?></td>
</tr>
<?}?>
</table>
</div>
</body>
</html>
