<? session_start();
require("../inc/conn.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script src="../js/jquery-1.8.3.min.js" type="text/javascript"></script>
<script>
$.support.cors = true;
</script>
<title></title>
</head>
<style>
	#navigation {
		height: 50px;
		width: 100%;
		text-align: center;
		background: #2aa439;
	}
	#navigation a.back {
		position: absolute;
		top: 15px;
		left: 15px;
		width: 50px;
		background: none;
		color: #fff;
		font-size: 16px;
	}
	h3 {
		width: 180px;
		height: 50px;
		line-height: 50px;
		margin: 0 auto;
		color: #fff;
		font-size: 20px;
	}
	.text_line {
		height: 30px;
	}
	.but6 {
		border-radius: 5px; cursor:pointer; width:33px; height:25px; text-align: center; color:#fff; border:1px solid #333333; background: #333333; -webkit-appearance: none; font-size: 12px;
	}
	.but7 {
		border-radius: 5px; cursor:pointer; width:33px; height:25px; text-align: center; color:#fff; border:1px solid #2aa439; background: #288439; -webkit-appearance: none; font-size: 13px;
	}
	.but8 {
		border-radius: 5px; cursor:pointer; width:33px; height:25px; text-align: center; color:#fff; border:1px solid #2aa439; background: #bbbbbb; -webkit-appearance: none; font-size: 13px;
	}
	.motop {
		margin-top: 5px;
	}
	.titleID {
		height: 25px;
		width: 100%;
		line-height: 25px;
		text-align: left;
		background: #eee;
		color: #666;
		font-size: 14px;
	}
	.titleIDnow {
		height: 25px;
		width: 100%;
		line-height: 25px;
		text-align: left;
		background:#0CF;
		color: #333;
		font-size: 14px;
	}
	#orderList {
		border: 1px solid #eaeaea;
		border-radius: 5px;
		margin-top: 10px;
	}
	.content {
		font-size:12px;
	}
	.orderOption {
		height: 30px;
		line-height: 30px;
		margin: 10px 0px 10px 0px;
		text-align:center
	}
</style>
<body>
<div style="text-align:center">
<input type="button" class="but7" href="javascript:void(0)" onClick="javascript:window.parent.cmain.location.href='YKKF_newtask.php';return false;" value="新建" >
<input type="button" class="but7" href="javascript:void(0)" onClick="javascript:window.parent.cmain.location.href='YKKF_tools.php';return false;" value="工具" >
<input type="button" class="but7" href="javascript:void(0)" onClick="javascript:window.parent.cmain.location.href='YKKF_taskhistory.php';return false;" value="历史" >
</div>
<div style="font-size:14px;background: #eee;line-height: 25px;">&nbsp;<? echo $_SESSION["YKOAUSER"]?>'s任务列表</div>
<div id="task">

</div>
<script>
		
function update() {
    $.post("YKKF_tasklist_b.php",{from:"step"},function(data) {
		$("#task").html(data);
    });
};

var intervalId=window.setInterval(update, 2000);
update();

        function actions(thisBtn, command) {

        	var c = arguments[2] ? arguments[2] : '1'; 

        	var mId = thisBtn.attr('data-id');
        	$.ajax({  
                cache: false,  
                type: "POST",  
                url: "YKKF_save.php",  
                data: {
                    command: command,
                    id: mId
                },
                //dataType: 'json', 
                beforeSend: function() { 

                },
                error: function(request) {  

                },  
                success: function(data) {  
                	if (c != '') {
                		document.cookie = 'currenttaskid=' + mId;
                	}
                	//setTimeout(function() {
                	//	window.location.reload();
                	//}, 100);
                }  
            })
        }
$(document).ready(function() {

	$(document).on('click', '.click2BeginA', function() {

		window.open($(this).attr('data-open'));
		actions($(this), 'begin');

	})

	$(document).on('click', '.click2Begin', function() {

		parent.cmain.document.location = $(this).attr('data-parent');
		actions($(this), 'begin');
	})

	$(document).on('click', '.click2OpenA', function() {

		window.open($(this).attr('data-open'));
		actions($(this), 'open');

	})

	$(document).on('click', '.click2Open', function() {

		parent.cmain.document.location = $(this).attr('data-parent');
		actions($(this), 'open');
	})

	$(document).on('click', '.click2Finish', function() {

	
		if (confirm('确定任务完成并结束吗？')) {
			//$.post('YKKF_save.php',{command:'end',id: mId},function(data){});
			actions($(this), 'end');
		}
	})

});
        

</script>
</body>
</html>