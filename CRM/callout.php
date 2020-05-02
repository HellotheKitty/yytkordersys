<? require("../inc/conn.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>通话能力演示</title>
<script src="../js/jquery-1.8.3.min.js" type="text/javascript"></script>
<script type="text/javascript" src="http://www.cloopen.com/js/voip/swfobject.js"></script>
<script type="text/javascript" src="http://www.cloopen.com/js/voip/Cloopen.js"> </script>
<script type="text/javascript">

// 配置已创建应用的应用ID和子账号，可根据应用侧需要自行确定获取形式。
/*--------------------------------------配置开始------------------------------------------------*/
$appid ='aaf98f894d7439d8014d93bd08a616bd'; //'aaf98f894d2cd316014d2d1a89a50087';

/*当应用id为demo应用时，可使用子账户Id作为token登录，此时开发者不需要实现token反查接口*/
var tokenObj = "<? $rs=mysql_query("select token from call_token where nickname='".$_GET["id"]."'",$conn);echo mysql_result($rs,0,0);?>";
/*----------------------------------------配置结束----------------------------------------------*/

$(document).ready(function(){
    var hrefstr,pos,parastr;
		/*设置为debug模式*/
		Cloopen.debug();
		
		/*设置为强制登录模式*/
		Cloopen.forceLogin();
        
        /*以自定义token方式初始化*/
        Cloopen.init('idvideophone'//swf对应的id
                    ,initCallBack//初始化时自定义fun
                    ,notifyCallBack//显示通知的自定义fun
                    ,$appid+"#"+tokenObj//登陆用户token
        );
        
        /*未连接状态*/
        Cloopen.when_idle(function(){
            fn_updatelog('未连接...');
        })
        
        /*正在连接服务器注册*/
        Cloopen.when_connecting(function(){
            fn_updatelog('正在连接服务器注册...');
        })
        
        /*已经注册登录*/
        Cloopen.when_connected(function(){
            fn_updatelog('通话准备就绪！');
            $(".step1").show(); 
            $(".step2").hide();
            $(".step3").hide();
            $(".step4").hide(); 
            document.getElementById("landcall").disabled=false; 
            document.getElementById("voipcall").disabled=false;
        })
        
        /*正在呼出*/
        Cloopen.when_outbound(function(){
            fn_updatelog('正在呼出...');
            $(".step3").show(); 
            //$(".step1").hide(); 
        })
        
        /*有呼入*/
        Cloopen.when_inbound(function(){
            fn_updatelog('有电话呼入...');
            $(".step2").show(); 
            $(".step1").hide();
            $(".step3").hide();
            $(".step4").hide(); 
        })
        
        /*通话中*/
        Cloopen.when_active(function(){
            fn_updatelog('通话中...');
            stopCount();
            timedCount();
            $(".step4").show(); 
            //$(".step1").hide();
            $(".step2").hide();
            $(".step3").hide();
        })
});

function fn_focus(ele){
	if(ele.value == ele.defaultValue){
		ele.value = '';
	}
}
function fn_blur(ele){
	var reg = /^[\s]*$/;
	if(reg.test(ele.value) || ele.value == ele.defaultValue){
		ele.value = ele.defaultValue;
	}
}

/*更新日志*/
function fn_updatelog(text){
   $('.divBox').append('<p>'+text+'</p>');
}

/*计时器*/
var c=0
var t  
function timedCount()  
{  
//    hour=parseInt(c/60/60);
    minute=parseInt(c/60%60);
    second=parseInt(c%60);
    if(minute<10){
      mStr = '0'+minute;
    } else{
      mStr =  minute
    }
    if(second<10){
      sStr = '0'+second;
    } else{
      sStr =  second
    }
  $("#time").html(mStr+':'+sStr);
  c=c+1;  
  t=setTimeout("timedCount()",1000);  
} 
 
/*停止计时器*/
function stopCount()  
{  
  clearTimeout(t);
  c=0;
  $("#time").html('00:00');  
}  

/*Cloopen显示事件回调通知的自定义函数*/
function notifyCallBack(doFun,msg){
	if (doFun == 'invited') {
		// 发起呼叫成功事件
		fn_updatelog('发起呼叫成功事件');
	}
	else if (doFun == 'invitefailed') {
		// 发起呼叫失败事件
		fn_updatelog('发起呼叫失败事件');
	}
	else if (doFun == 'accepted') {
		// 对端应答事件
		fn_updatelog('对端应答事件');
	}
	else if (doFun == 'ringing') {
		// 来电事件
		fn_updatelog('来电事件，号码:'+msg);
	}
	else if (doFun == 'onHangup') {
		// 挂机事件
		if (msg == 'normal') {
			fn_updatelog('挂机事件: 本端正常挂机');
		}
		else if (msg == 'byed') {
			fn_updatelog('挂机事件: 对端正常挂机');
		}
		else if (msg == 'rejected') {
			fn_updatelog('挂机事件: 对端拒接');
		}
		else if (msg == 'unallocated') {
			fn_updatelog('挂机事件: 呼叫号码为空号');
		}
		else if (msg == 'noresponse') {
			fn_updatelog('挂机事件: 呼叫无响应');
		}
		else if (msg == 'noanswer') {
			fn_updatelog('挂机事件: 对方无应答');
		}
		else {
			fn_updatelog('挂机事件: '+msg);
		}
	}
	else {
		// 其他未知事件
		fn_updatelog(msg);
	} 
} 

/*Cloopen初始化成功后的自定义函数*/
function initCallBack(){   
   /*发起落地呼叫*/
   $("#landcall").click(function(){
       if($('.txt').val()!='请输入被叫号码（固号加区号）'){
         fn_updatelog('落地呼出：'+$('.txt').val());
         Cloopen.invitetel($('.txt').val(),'02029829633');
       } else{
          fn_updatelog('落地呼出输入有误');  
       }
   });
   $("#landcall2").click(function(){
       if($('#hm option:selected').val()!=''){
         fn_updatelog('呼出：'+$('#hm option:selected').val());
         Cloopen.invitetel($('#hm option:selected').val());
       } else{
          fn_updatelog('呼出输入有误');  
       }
   });   
   /*挂断*/
   $("#bye").click(function(){
       Cloopen.bye();
       stopCount();
       $(".step1").show(); 
       $(".step2").hide();
       $(".step3").hide();
       $(".step4").hide(); 
   });
   
   /*挂断*/
   $("#bye2").click(function(){
       Cloopen.bye();
       stopCount();
       $(".step1").show(); 
       $(".step2").hide();
       $(".step3").hide();
       $(".step4").hide(); 
   });
   
   /*接听*/
   $("#accept").click(function(){
       Cloopen.accept();
       $(".step4").show(); 
       $(".step2").hide();
       $(".step3").hide();
       $(".step1").hide(); 
   });
   
   /*拒接*/
   $("#reject").click(function(){
       Cloopen.reject();
       $(".step1").show(); 
       $(".step2").hide();
       $(".step3").hide();
       $(".step4").hide(); 
       
   });
}
</script>
</head>

<body>
<div class="wrap">
         
          <div class="step1" style="display:block; float:left; width:170px" >
             <div class="c1 height">
              <div class="c1_l pd60">  <span>落地电话</span> </div>
              <div class="c1_r">
                <input type="text" class="txt" onfocus="fn_focus(this);" onblur="fn_blur(this);" value="请输入被叫号码（固号加区号）" size="11"/>
                <input id="landcall" type="button" class="btn1" value="呼叫" disabled="true"/>
              </div>
            </div>
          <br><font color="green">客户信息:
<? echo urldecode($_GET["kh"]);
$hm=substr(trim($_GET["pnum"]),-11,11);
$hm2=trim($_GET["pnum2"]);
$hm2=str_replace("(","",$hm2);
$hm2=str_replace(")","",$hm2);
$hm2=str_replace("（","",$hm2);
$hm2=str_replace("）","",$hm2);
if (substr($hm2,0,3)=="+86" or substr($hm2,0,3)=="086" or substr($hm2,0,3)=="86 ") $hm2=substr($hm2,3);
if (substr($hm2,0,2)=="86") $hm2=substr($hm2,2);
if (substr($hm2,0,1)<>"0") $hm2="0".$hm2;
$hm2=str_replace(" ","",$hm2);
$hm2=str_replace("-","",substr($hm2,0,6)).substr($hm2,6);
?></font> <br>
<select name="hm" id="hm">
  <? 
  if ($hm2<>"") echo "<option value='".$hm2."'>".$hm2."</option>";
  if ($hm<>"") { 
  	echo "<option value='".$hm."'>".$hm."</option>";
  	}
  
  ?>
</select><input id="landcall2" type="button" class="btn1" value="呼叫" />
          </div>
  <div style="float:right; overflow:auto; height:200px; width:170px">
          <h5>状态日志：</h5>
   	<div class="divBox" style="height:182px; font-size:10px;">
            </div>
        </div>
         <div class="step2" style="float:left; width:200px"></div>
          <div class="step3" style="float:left; width:200px">
          	<div class="imgDiv pd27">
            	<img src="images/img_voip.png" />
                <span><em>连接中.....</em><img src="images/ico_3.gif" /></span>
                
            </div>
            <input id="bye" type="button" class="btn3" value="挂机"/>
          </div>
          <div class="step4" style="float:left; width:300px">
          	<img src="images/img_voip.png" />通话中<span id="time">00:00</span>
<input id="bye2" type="button" class="btn3" value="挂机"/>
          </div>
         
        </div>
        
</div>
<div id="videobg">
    <div id="idvideophone" style="display:none">
        <a href="http://www.adobe.com/go/getflashplayer" alt="Get Adobe Flash player">Get Adobe Flash player</a>
    </div>
</div>
</body>
</html>
