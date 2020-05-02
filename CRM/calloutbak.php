<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>通话能力演示</title>
<link rel="stylesheet" href="css/css.css" type="text/css" />
<script src="../js/jquery-1.8.3.min.js" type="text/javascript"></script>
<script type="text/javascript" src="http://www.cloopen.com/js/voip/swfobject.js"></script>
<script type="text/javascript" src="http://www.cloopen.com/js/voip/Cloopen_sandbox.js"> </script>
<script type="text/javascript">

// 配置已创建应用的应用ID和子账号，可根据应用侧需要自行确定获取形式。
/*--------------------------------------配置开始------------------------------------------------*/
$appid ='aaf98f894d2cd316014d2d1a89a50087';

/*当应用id为demo应用时，可使用子账户Id作为token登录，此时开发者不需要实现token反查接口*/
var tokenObj = {
'token1':'0e848897f7a511e48ad3ac853d9f54f2',
'token2':'在此输入自定义token，或子账户id(仅限demo应用)',
'token3':'在此输入自定义token，或子账户id(仅限demo应用)',
'token4':'在此输入自定义token，或子账户id(仅限demo应用)',
'token5':'在此输入自定义token，或子账户id(仅限demo应用)'
}
/*----------------------------------------配置结束----------------------------------------------*/

$(document).ready(function(){
    var hrefstr,pos,parastr;
    hrefstr = window.location.href;
    pos = hrefstr.indexOf("?");
    //获得本页面token，可根据应用侧需要自行确定获取形式
    parastr = hrefstr.substring(pos+4);
    $("#voipuser").html(parastr);
    $("#"+parastr).hide();
	var nHeight=$('.container').height();
	var nTop = -(nHeight/2)+'px';
	$('.container').css({'margin-top':nTop});
	$(".cdrboxitem").hover(function(){
			$(this).addClass("selected");
		},function(){
			$(this).removeClass("selected");
		});
        
		$(".cdrselectvalue").click(function(){
			$(this).blur();
			$(".cdrboxoptions").show();
			return false;
		});
        
		$(document).click(function(event){
			if($(event.target).attr("class") != "cdrboxoptions"){
				$(".cdrboxoptions").hide();
			}
		});
        
		$(".cdrboxitem").click(function(){
			$(this).blur();
			$(".cdrselectvalue").html($(this).text());
		});
            $(".step2").html();
		fn_updatelog('您的token为：'+tokenObj[$("#voipuser").text()]);
        fn_updatelog('您的应用ID为：'+$appid);
		
		/*设置为debug模式*/
		Cloopen.debug();
		
		/*设置为强制登录模式*/
		Cloopen.forceLogin();
        
        /*以自定义token方式初始化*/
        Cloopen.init('idvideophone'//swf对应的id
                    ,initCallBack//初始化时自定义fun
                    ,notifyCallBack//显示通知的自定义fun
                    ,$appid+"#"+tokenObj[$("#voipuser").text()]//登陆用户token
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
            $(".step1").hide(); 
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
            $(".step1").hide();
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
    /*发起VoIP呼叫*/
   $("#voipcall").click(function(){
     if($('.cdrselectvalue').text()!='请选择被叫用户token')
      {
         fn_updatelog('VoIP呼出：'+$('.cdrselectvalue').text());
         Cloopen.inviteplus($appid+'#'+tokenObj[$('.cdrselectvalue').text()]);
      } else{
         fn_updatelog('VoIP呼出输入有误');  
      }
   });
   
   /*发起落地呼叫*/
   $("#landcall").click(function(){
       if($('.txt').val()!='请输入被叫号码（固号加区号）'){
         fn_updatelog('落地呼出：'+$('.txt').val());
         Cloopen.invitetel($('.txt').val());
       } else{
          fn_updatelog('落地呼出输入有误');  
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
  <div class="container">
    <div class="content"> <span class="bg_t"><img src="images/bg_t.png" /></span>
      <div class="bg_m">
        <div class="titDiv"> <img src="images/logo.png" class="logo"/><span>通话能力DEMO</span></div>
        <div class="left">
          <h2>欢迎云通讯能力</h2>
          <p>如果您想通过本电脑接听电话，请将您的VoIP帐号告诉您的伙伴</p>
          <div class="voipDiv"> <em>您的TOKEN</em><span id="voipuser" ></span> </div>
          <div class="step1" style="display:block;" >
            <div class="c1">
              <div class="c1_l pd60"> <img src="images/img_voip.png" /> <span>VoIP 网络语音电话</span> </div>
              <div class="c1_r">
                <div class="box_select">
                  <div class="select"> <a href="javascript:void(0);" class="cdrselectvalue">请选择被叫TOKEN</a>
                    <ul class="cdrboxoptions">  
                      <!-- 可根据应用侧需要自行确定获取形式。DEMO程序为对应queryvoip.php文件中处理的token值-->  
                      <li class="cdrboxitem" id="token1"><a href="javascript:void(0);">token1</a></li>
                      <li class="cdrboxitem" id="token2"><a href="javascript:void(0);">token2</a></li>
					  <li class="cdrboxitem" id="token2"><a href="javascript:void(0);">token3</a></li>
					  <li class="cdrboxitem" id="token2"><a href="javascript:void(0);">token4</a></li>
					  <li class="cdrboxitem" id="token2"><a href="javascript:void(0);">token5</a></li>
                    </ul>
                  </div>
                </div>
                <input id="voipcall" type="button" class="btn1" value="VoIP 呼叫" disabled="true"/>
              </div>
               <br style="clear:both;" />
            </div>
             <div class="c1 height">
              <div class="c1_l pd60"> <img src="images/img_tel.png" /> <span>落地电话</span> </div>
              <div class="c1_r">
                <input type="text" class="txt" value="请输入被叫号码（固号加区号）" onfocus="fn_focus(this);" onblur="fn_blur(this);"/>
                <input id="landcall" type="button" class="btn1" value="落地 呼叫" disabled="true"/>
              </div>
            </div>
          </div>
          <div class="step2">
          	<div class="imgDiv">
            	<img src="images/ico_1.gif" />
                <img src="images/img_voip.png" />
                <img src="images/ico_2.gif" />
            </div>
            <p>有VoIP来电</p>
            <div class="btns"><input id="accept" type="button" class="btn2" value="接听"/><input id="reject" type="button" class="btn3" value="拒绝"/></div>
          </div>
          <div class="step3">
          	<div class="imgDiv pd27">
            	<img src="images/img_voip.png" />
                <span><em>连接中.....</em><img src="images/ico_3.gif" /></span>
                <img src="images/img_phone.gif" />
            </div>
            <input id="bye" type="button" class="btn3" value="挂机"/>
          </div>
          <div class="step4" >
          	<img src="images/img_voip.png" />
            <p>通话中</p>
            <p><span id="time">00:00</span></p>
            <input id="bye2" type="button" class="btn3" value="挂机"/>
          </div>
         
        </div>
        <div class="right">
          <h2>DEMO日志：</h2>
          <div class="divBox">
            </div>
        </div>
      </div>
      <span class="bg_b"><img src="images/bg_b.png"/></span> </div>
  </div>
</div>
<div id="videobg">
    <div id="idvideophone" style="display:block">
        <a href="http://www.adobe.com/go/getflashplayer" alt="Get Adobe Flash player">Get Adobe Flash player</a>
    </div>
</div>
</body>
</html>
