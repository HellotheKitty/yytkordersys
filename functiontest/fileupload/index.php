<? session_start();
$_SESSION["mxid"]=$_GET["mxid"];
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>

<title>upload - skyprint.com</title>

<!-- production -->
<script type="text/javascript" src="js/plupload.full.min.js"></script>

</head>
<body style="font: 13px Verdana; background: #eee; color: #333">

<h1>文件上传</h1>

<p>把文件上传系统。(支持格式：jpg,gif,png,zip,pdf,rar,ai,cdr,doc,xls)</p>


<div id="filelist">Your browser doesn't have Flash, Silverlight or HTML5 support.</div>
<br />

<div id="container">
    <a id="pickfiles" href="javascript:">[选择文件]</a>
    <a id="uploadfiles" href="javascript:">[上传文件]</a>
</div>
<p>
<a id="close" href="javascript:window.close();">[关闭窗口]</a>
</p>
<br />
<pre id="console"></pre>


<script type="text/javascript">
var upok=false;

var uploader = new plupload.Uploader({
	runtimes : 'html5,flash,silverlight,html4',
	browse_button : 'pickfiles', // you can pass in id...
	container: document.getElementById('container'), // ... or DOM Element itself
	url : 'upload.php',
	flash_swf_url : 'js/Moxie.swf',
	silverlight_xap_url : 'js/Moxie.xap',
	chunk_size : '4MB',
	
	filters : {
		max_file_size : '1500mb',
		mime_types: [
			{title : "Image files", extensions : "jpg,gif,png"},
			{title : "Zip files", extensions : "zip"},
			{title : "Pdf files", extensions : "pdf"},
			{title : "RAR files", extensions : "rar"},
			{title : "AI files", extensions : "ai"},
			{title : "CDR files", extensions : "cdr"},
			{title : "Word files", extensions : "doc"},
			{title : "EXCEL files", extensions : "xls"}
		]
	},

	init: {
		PostInit: function() {
			document.getElementById('filelist').innerHTML = '';

			document.getElementById('uploadfiles').onclick = function() {
				uploader.start();
				upok=true;
				return false;
			};
		},

		FilesAdded: function(up, files) {
			plupload.each(files, function(file) {
				document.getElementById('filelist').innerHTML += '<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></div>';
				window.opener.postMessage(<? echo $_GET["mxid"]?>+file.name,"*");
				file.name=encodeURI(file.name);
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

window.onbeforeunload = function() {
if(!upok){
	return "文件还没有上传，请点击上传文件";
}else{
	return;
}
}
window.onunload = function() {
if(!upok){
	window.opener.postMessage(<? echo $_GET["mxid"]?>+"del","*");
}
}
</script>
</body>
</html>
