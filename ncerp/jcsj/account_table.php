<?
	require "../fragment/title_in.php";
?>
<body>



    <div class="navigation  ">
        <div class="floatBarMask"></div>
        <div class="navContent">
                            <a class="glyphicon glyphicon-menu-left goback" href="http://oa.skyprint.cn/wxs/per_center.php"></a>
            
            <p class="navTitle">账户中心</p>

            <div class="nav-action">
                                                                </div>
        </div>

    </div>







<div class="widthLimit">

    <div class="cardtag-wrapper">
        <?
        require "../sql/sql.php";
		session_start();
		$openid=$_SESSION['openId'];
		$name=login_name($openid);
		$a=get_khmc($name[0]);
		$khmc=$a[0];
		$res=select_mpzh($name[0]);
		$zh=$res[0];
		$row=select_ye_by_mpzh($zh);
        ?>
        <div class="cardtag-section">
            <p class="balance" align="center">余额 <span class="text-orange">
            <?
            if($row[0]!=""){
            echo $row[0];
			}else{
			echo "0.00";
			}
            ?>
            </span> 元</p>
            <!--<button id="topup" class="btn formButton Gbtn btn-intag transition">充 值</button>-->
        </div>
        <div class="cardtag-section">
        <p class="balance"><font color="#ab0000">消费明细</font></p>
        <p class="balance">显示目前4月份条明细</p>
        <p>月初款：月末款：</p>
        <table  width="100%" border="1">
            <tr>    	
            <th width="25%">单号</th>
            <th width="25%">时间</th>
            <th width="25%">充值金额（元）</th>
            <th width="25%">消费金额（元）</th>
            </tr>
        <?
         $sql="select * from `order_zh` where `khmc`='$khmc' order by id desc limit 0,600";
		 $str = mysql_query($sql);
         while($row = @mysql_fetch_array ($str)){
        ?>	
        <tr>
        	<td align="center"><?
            echo $row['ddh'];
            ?></td>
        	<td align="center"><?
        	echo substr($row['sksj'], 8);
            ?></td>
        	<td align="center"><?
            echo $row['df'];
            ?></td>
        </tr>
        <?
		 }
        ?>
        </table>
        </div>
        <div class="cardtag-section">
        <p class="balance" align="center">如需充值，请联系客服人员</p>
        <p class="balance" align="center">客服热线，021-51096119，021-51098805</p>
        </div>
    </div>

</div>


<!--<div class="bot-float-action-layer hide">
    <div class="widthLimit">

        <div class="clearfix">
            <button class="btn-icon-cancel float-right tapToCancel">
                <i class="left-cross"></i>
                <i class="right-cross"></i>
            </button>
        </div>

        <div class="formWrapper" data-toggle="buttons">
            <label class="transition btn btn-option-select btn-radio active">
                <input class="topupFee" name="" autocomplete="off" value="100" checked="checked" type="radio"> 100 元 (实付: 99元)
            </label>
            <label class="transition btn btn-option-select btn-radio">
                <input class="topupFee" name="" autocomplete="off" value="300" type="radio"> 300 元 (实付: 294元)
            </label>
            <label class="transition btn btn-option-select btn-radio">
                <input class="topupFee" name="" autocomplete="off" value="500" type="radio"> 500 元 (实付: 475元)
            </label>
            <label class="transition btn btn-option-select btn-radio">
                <input class="topupFee" name="" autocomplete="off" value="1000" type="radio"> 1000 元 (实付: 900元)
            </label>


            
            <button id="gototopup" class="btn formButton Gbtn transition">去付款</button>
        </div>
    </div>
</div>-->


<script type="text/javascript" src="../js/account.js"></script>

<ul style="display: none">
    <li>
        <span>
            <i>
                <input id="xNonce" value="HdY07ngXxSQi2S4NlRBdbaeIwjqxPvsn" type="hidden">
            </i>
            <i>
                <input id="xSignature" value="ef8271d8b59fca47e281fbe25590c1df51fea011" type="hidden">
            </i>
            <i>
                <input id="openId" value="" type="hidden">
            </i>
        </span>
    </li>
</ul>


<div class="message"></div>

<div data-state="no-more" data-scrollaction="" id="loadStateWrapper" class="loadStateWrapper loadStateOn clearfix"></div>

<div class="bot-menu-fix"></div>

<script type="text/javascript" src="../js/yikaM.js"></script>



</body></html>