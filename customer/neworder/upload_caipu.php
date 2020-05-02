<?
    ini_set("allow_call_time_pass_reference","true");
    error_reporting(E_ALL ^ E_DEPRECATED&E_ALL ^ E_NOTICE);
    require "function/mxdjs.php";
	require('lib/fpdi.php');
	$materialrs = mysql_query("select * from material where zzfy=3301", $conn);	// 现在3301单位测试使用。以后根据情况使用get传递参数。
    $khrs = mysql_query("select * from base_kh where gdzk=3301 and khmc like '%菜谱%'", $conn);
    $machiners = mysql_query("select machine from b_machine", $conn);	// 暂时只用Hp彩色。
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>拼版操作台</title>
<script src="diyUpload/js/jquery.js"></script>
<link rel="stylesheet" type="text/css" href="diyUpload/css/webuploader.css">
<link rel="stylesheet" type="text/css" href="diyUpload/css/diyUpload.css">
<script type="text/javascript" src="diyUpload/js/plupload.full.min.js"></script>
<?
	require  "function/js.php";
?>
</head>
<style>
*{ margin:0; padding:0;}
#box{ margin:5px; width:540px; min-height:400px; background:#FF9}
#demo{float:left; margin:5px;margin-left: 8%; width:25%; min-height:650px; background:#F2F2F2;}
#show{float:left; margin:5px;margin-left: 2%; width:57%; min-height:900px; background:#F2F2F2;}
</style>
<body>
<!--<div id="box">
	<div id="test" ></div>
</div>-->
<form action="function/pinban.php?ddh=<?echo $bh?>" method="post" enctype="multipart/form-data">
<input type="button" onclick="javascript:window.opener.location.href='newcp.php?ddh=<? echo $bh?>&auth=<?echo md5($_SESSION["KHID"]."-".$_SESSION["AUTHCODE"]."-".$bh."-"."新建订单")?>';window.close();" value="关闭返回" />
<table>
	<tr>单号：<?echo $bh;?></tr><hr>
	<tr>
		<td>印件名称</td><td>|机器及颜色</td><td>|纸张</td><td>|单双面</td><td>|打印份数<small>(pdf文件打印份数)</small></td>
	</tr>
	<tr>
		<?
			$sql_get_detail="select * from `order_mxqt` where `ddh`='$bh'";
			$str_get_detail=@mysql_query($sql_get_detail);
			$res_get_detail=@mysql_fetch_array($str_get_detail);
		?>
		<td><input name="pname" value="<?echo $res_get_detail['pname']?>" /></td>
		<td><input name="machine" value="Hp彩色" readonly /><!--<select name="machine"><? //while($row = mysql_fetch_array($machiners, MYSQL_ASSOC)) {echo "<option value='".$row['machine']."' "; if($row['machine'] == $_POST["machine"]) echo "selected"; echo ">".$row["machine"]."</option>";} ?></select>--></td>
	    <td><select name="materialid"><? while($row = mysql_fetch_array($materialrs, MYSQL_ASSOC)) {echo "<option value='".$row['id']."'";if($row['id'] == $_POST['materialid']) echo "selected"; echo">".$row["MaterialName"]."　[".$row["Specs"]."]</option>";} ?></select></td>
	    <td><select name="double"><option value="单面" name="double">单面</option><option value="双面" name="double" <?if($_POST['dsm'] == '双面') echo "selected"; ?>>双面</option></select></td>
	   <!-- <td><select name="hzx"><option value=""></option><option value="横向" <?if($_POST['hzx'] == '横向') echo "selected"; ?>>横向</option><option value="纵向" <?if($_POST['hzx'] == '纵向') echo "selected"; ?>>纵向</option></select></td>-->
	    <td><input name="sl1" value="<?echo $res_get_detail['sl1']?>" /></td>
	    
	</tr>
         <!--
            <td>打印单价</td>
	    <td><input name="price" value="<?echo $_POST['price']?>" /></td>-->
    </table>
    
     <hr>
<div id="demo">
<div id="test"></div>
<div id="filelist">您的浏览器不支持flash和html5，请下载flash插件或者更换浏览器</div>
<br />
<div id="container">
    <a id="pickfiles" href="javascript:;">[选择文件]</a> 
    <a id="uploadfiles" href="javascript:;">[上传文件]</a>
    <br>
    <input type="submit" name="" id="" value="点击生成" class="webuploader-pick"/>
</div>

<br />
<pre id="console"></pre>
    <p>文件命名规则：</p>
    <p>1,2,3,4,5,6......</p>
</div>

<div id="show">
	<p>
	拼版预览
	<input type="button" value="删除预览" onclick="window.location.href='function/function/delete_preview.php?ddh=<?echo $bh?>'"/>
	<small>--关闭本页之前请将预览删除</small>
	</p>
	<?
	$path="function/BillFiles/".substr($bh, -6)."/";
	if(is_dir($path)){
	$filearr = scandir($path);
	unset($filearr[0]);
	unset($filearr[1]);
	foreach($filearr as $image_url){
	?>	
		<img src="<?echo $path.$image_url?>" style="margin-left: 10%;margin-right:auto;margin-top: 3%; height: 250px;"/>
	<?
	}
}
	?>
</div>
</form>
</body>
<script type="text/javascript">
/*
* 服务器地址,成功返回,失败返回参数格式依照jquery.ajax习惯;
* 其他参数同WebUploader
*/

var uploader = new plupload.Uploader({
	runtimes : 'html5,flash,silverlight,html4',
	browse_button : 'pickfiles', // you can pass in id...
	container: document.getElementById('container'), // ... or DOM Element itself
	url : 'server/upload.php?bh=<?echo $bh;?>',
	flash_swf_url : '../js/Moxie.swf',
	silverlight_xap_url : '../js/Moxie.xap',
	
	filters : {
		max_file_size : '5000mb',
		mime_types: [
			{title : "Image files", extensions : "jpg,gif,png"},
			{title : "Zip files", extensions : "zip"}
		]
	},

	init: {
		PostInit: function() {
			document.getElementById('filelist').innerHTML = '';

			document.getElementById('uploadfiles').onclick = function() {
				uploader.start();
				return false;
			};
		},

		FilesAdded: function(up, files) {
			plupload.each(files, function(file) {
				document.getElementById('filelist').innerHTML += '<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></div>';
			});
		},

		UploadProgress: function(up, file) {
			document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
		},

		Error: function(up, err) {
			document.getElementById('console').innerHTML += "\nError #" + err.code + ": " + err.message;
		}
	}
});

uploader.init();

</script>
</div>
</html>
