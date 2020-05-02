<? 
session_start();
require("../inc/conn.php");
?>
<?

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
    <title>我的车</title>
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
    <? //echo $_SESSION["ZZUSER"];?><!-- ,您好！　　 -->
    <div class="s-navWrapper clearfix"><a class="s-navigation" href="javascript:" onClick='window.open("../ncerp/jcsj/kh_list.php", "HT_dhdj2", "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=yes,width=1000,height=700,left=300,top=100")'>客户列表</a> <a class="s-navigation" href="qrcode.php" target="_blank">二维码生成工具</a>
		

 	</div>　  

	<br>查找客户：    登录名<input name="t1" type="text" id="t1" size="12" />  姓名<input name="t3" type="text" id="t3" size="10" />  手机号<input name="t4" type="text" id="t4" size="10" /> QQ<input name="t5" type="text" id="t5" size="10" /> 单位<input name="t2" type="text" id="t2" size="15" /> <input type="submit" name="button2" id="button2" value="查找" />　
    <br><br>查找任务：    登录名<input name="t31" type="text" id="t31" size="12" />  姓名<input name="t32" type="text" id="t32" size="10" />  手机号<input name="t33" type="text" id="t33" size="10" /> 单位<input name="t34" type="text" id="t34" size="15" /> <input type="submit" name="button3" id="button3" value="查找" />
     <br><br>
    <div>
  <br>
  <? if ($_POST["t1"]<>"" or $_POST["t2"]<>"" or $_POST["t3"]<>"" or $_POST["t4"]<>"" or $_POST["t5"]<>"") {
		$rs2=mysql_query("select mpzh,khmc,id from base_kh where mpzh like '%".$_POST["t1"]."%' and khmc like '%".$_POST["t2"]."%' and lxr like '%".$_POST["t3"]."%' and lxdh like '%".$_POST["t4"]."%' and QQ like '%".$_POST["t5"]."%' order by mpzh limit 50",$conn);
		while ($row=mysql_fetch_row($rs2)) {
		echo "$row[0]-$row[1]"."/"."[<a href='javascript:' class='nav' onClick='window.open(\"YK_kf_user.php?ID=".$row[2]."\", \"HT_dhdj\", \"toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=yes,width=850,height=800,left=580,top=100\")'>用户详情</a>]&nbsp;&nbsp;";
		}
	}
    ?>
     
<? if ($_POST["t31"]<>"" or $_POST["t32"]<>"" or $_POST["t33"]<>"" or $_POST["t34"]<>"") {
		$rs2=mysql_query("select taskcreatetime,taskname,xm,taskstate,task_list.taskmemo,task_list.fromuser from task_list,task_type,task_kfry where task_list.tasktype=task_type.tasktype and task_list.taskrecver=task_kfry.oabh and fromuser in (select mpzh from base_kh where mpzh like '%".$_POST["t31"]."%' and khmc like '%".$_POST["t34"]."%' and lxr like '%".$_POST["t32"]."%' and lxdh like '%".$_POST["t33"]."%' ) order by taskcreatetime desc limit 50",$conn);
		
		while ($row=mysql_fetch_row($rs2)) {
		echo $row[5],":",$row[0],"",$row[1],"[",$row[2],"]",$row[3],",任务备注：",$row[4],"<br>";
		}
	}
    ?>
   
 </div>
      

</div>


</div>

</div>
</form>
</body></html>