<? require("inc/conn.php");

//step1 search kh
if($_GET['searchkhmc']=='1'){

    $khmc = $_GET['khmc'];
    $lxdh = $_GET['lxdh'];
    $res = mysql_query("select khmc ,lxdh,gdzk from base_kh where khmc like '%$khmc%' and lxdh like '%$lxdh%' LIMIT 10" , $conn);

    if(mysql_num_rows($res) > 0){

        $data['code'] = 1;
        $data['info'] = '<dl class="khmclist">
             <dt>
                请选择您的公司名称:
             </dt>';
        while($item = mysql_fetch_assoc($res)){

            $data['info'] .= '
             <dd>
                 【<a href="#" class="choosenkhmc">'. $item['khmc'] .'</a>】
             </dd>';
        }
        $data['info'] .= '</dl>';
        echo json_encode($data);
        exit;
    }else{
        exit;
    }

}
//step 2 yanzheng

//step 3 haslogname?
if($_GET['choosenkhmc'] =='1'){

    $ckhmc = $_GET['ckhmc'];
    $res = mysql_query("select loginname , loginpwd ,khmc from base_kh_login where khmc = '$ckhmc' LIMIT 1" ,$conn);

    if(mysql_num_rows($res) > 0){

        $loginname = mysql_result($res,0,'loginname');
        $data['haslogname'] = true;
        $data['info'] = $loginname;
        echo json_encode($data);
        exit;

    }else{

        $data['haslogname'] = false;
        echo json_encode($data);
        exit;
    }
}
//step 4 forgetpwd
//step 4 sign up
//$rs2 = mysql_query("SELECT dwdm,dwmc FROM b_dwdm WHERE web IS NULL OR web ='' ORDER BY dwdm", $conn);
if ($_POST["dlm"] <> "") {
//    $dwdm = $_POST["dwdm"];
//    $xm = $_POST["xm"];
//    $mobile = $_POST["mobile"];
    $company = $_POST['company'];//公司名
    $dlm = $_POST["dlm"];//loginname
    $mm = $_POST["mm"];
    $mm2 = $_POST["mm2"];
    if ($mm <> $mm2) {
        echo "<script>alert('两次输入的密码不一致，请检查输入！')</script>";
//        exit;
    } else {
        $rs = mysql_query("select bh from b_ry where xm='$dlm' or bh = '$dlm'", $conn);
        $rs1 = mysql_query("select id from base_kh_login where loginname = '$dlm' " , $conn);

        if (mysql_num_rows($rs) > 0 || mysql_num_rows($rs1)>0) {

            echo "<script>alert('您输入的登录名已经有人使用，请输入另外的登录名试试！')</script>";
//            exit;
        } else {

            mysql_query("insert into base_kh_login (loginname,loginpwd,khmc) values ('$dlm' , '$mm' , '$company')",$conn);
            echo "<script>window.location.href='?signupsucc=1';</script>";
            exit;
        }
    }
}

if($_GET['signupsucc']=='1'){
    echo '<span style="font-size: 20px;">注册成功!</span>';
    exit;
}

?>
<!doctype html>
<HTML>
<HEAD>
    <TITLE>印艺天空</TITLE>
    <META content="MSHTML 6.00.2800.1276" name=GENERATOR>
    <META http-equiv=Content-Type content="text/html; charset=utf-8">
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    <meta name="viewport" content="width=device-width,minimum-scale=1.0"/>

    <LINK href="css/mainWin.css" type=text/css rel=stylesheet>
    <LINK href="css/calendar.css" type=text/css rel=stylesheet>
    <LINK href="css/mainWin2.css" type=text/css rel=stylesheet>
    <style type="text/css">
        #searchkhmc{
            height:30px;
        }
        .errinfo{
            color:#900000;
            margin-left:40px;
            margin-top:10px;
        }
        .ItemBlockBorder{
            margin:10px auto;
        }
        .ItemBlock_Title{
            margin:0 auto;
        }
        .infotb{
            width:75%;
            margin:0 40px;
        }
        .infotb td{
            margin-left:5px;
        }
        .infotb tr{
            height:30px;
        }
        .khmclist{
            display: block;
            width:70%;
        }
        #showkhmc{
            margin:10px 40px;
            color: #004a7d;
        }
        #showkhmc a:hover{
            color:#ff0000;
        }
        dt{
            color:#333;
        }
        dd{
            color: #004a7d;
            font-size:16px;
            font-weight: bold;
        }
    </style>
</HEAD>
<?

?>
<body marginwidth="0" topmargin="0" leftmargin="0" marginheight="0">
<div class="mainbackground">
    <form method="post" id="actForm" action="">
        <DIV ID=Title_bar>
            <DIV ID=Title_bar_Head>
                <DIV ID=Title_Head></DIV>
                <DIV ID=Title>
                    <img border="0" width="18" height="18" src="images/title_arrow2.gif"/>
                    注册账号
                </DIV>
                <DIV ID=Title_End></DIV>
                <DIV ID=Title_bar_bg></DIV>
            </DIV>
            <DIV ID=Title_bar_Tail>
                <DIV ID=Title_FuncBar>
                    <ul>
                    </ul>
                </DIV>
            </DIV>
        </DIV>

        <DIV ID=MainArea>

            <DIV id="yanzhengbox">
                <DIV CLASS = ItemBlock_Title>
                    <img border="0" src="images/item_point.gif"/>
                    验证信息
                </DIV>
                <DIV CLASS=ItemBlockBorder>
                    <DIV CLASS=ItemBlock>
                        <TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 class="infotb">
                            <colgroup>
                                <col width="20%"/>
                                <col width="60%"/>
                            </colgroup>
                            <!--<TR>
                                <TD WIDTH=50 HEIGHT=27></TD>
                                <TD WIDTH=80>单位</TD>
                                <TD WIDTH=200>
                                    <select class="SelectStyle bluebtn" name="dwdm">
                                        <?/*
                                        while ($row = mysql_fetch_row($rs2)) {
                                            echo "<option value='$row[0]'>$row[1]</option>";
                                        } */?>

                                    </select>
                                </TD>
                            </TR>-->
                            <TR>
                                <TD>公司名</TD>
                                <TD>
                                    <input maxlength="100" id="keykhmc" class="InputStyle" style="width: 90%;" type="text" name="xm" placeholder="请输入公司名关键词模糊搜索"/>
                                </TD>
                                <td rowspan="2">
                                    <input type="button" value="search" id="searchkhmc" class="neatbtn bluebtn"/>
                                </td>
                            </TR>
                            <TR>
                                <TD>联系电话</TD>
                                <TD><input maxlength="100" id="lxdh" class="InputStyle" style="width: 90%;" type="text" placeholder="输入联系电话" name="mobile"/></TD>
                            </TR>
                        </TABLE>
                        <div id="showinfo">
                            <span id="errinfo" class="errinfo" style="display: none;">
                                #没有找到相关客户信息，请核对关键词或者咨询客服#
                            </span>
                            <div id="showkhmc">

                            </div>
                        </div>
                    </DIV>
                </DIV>
            </DIV>

            <DIV style="display: none;" id="zhinfobox">
                <DIV CLASS=ItemBlock_Title1>
                    <img border="0" src="images/item_point.gif"/>
                    账号信息
                </DIV>
                <DIV style="min-height:16px" CLASS=ItemBlockBorder>
                    <DIV style="min-height:16px" CLASS=ItemBlock>

                        <TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 class="infotb">
                            <colgroup>
                                <col width="25%"/>
                                <col width="60%"/>
                            </colgroup>
                            <tr>
                                <td>公司名</td>
                                <td>
                                    <h3 id="curkhmc"></h3>
                                    <input type="hidden" id="companyname" name="company"/>
                                </td>
                            </tr>
                            <TR>
                                <TD WIDTH=80>登录名</TD>
                                <TD WIDTH=200><input id="logname" onFocus="this.select();" maxlength="10" class="InputStyle" style="width: 300px;" type="text" name="dlm" /></TD>
                            </TR>
                            <tr id="oldpwdbox" style="display:none;">
                                <td width="80">请输入原密码</td>
                                <td WIDTH=200><input id="oldpwd" onFocus="this.select();" maxlength="10" class="InputStyle" style="width: 300px;" type="text" name="oldpwd" /></td>
                            </tr>
                            <TR class="haspwdhide">
                                <TD WIDTH=80>密码</TD>
                                <TD WIDTH=200><input onFocus="this.select();" maxlength="10" class="InputStyle" style="width: 300px;" type="password" name="mm" /></TD>
                            </TR>
                            <TR class="haspwdhide">
                                <TD WIDTH=80>重复密码</TD>
                                <TD WIDTH=200>
                                    <input onFocus="this.select();" maxlength="10" class="InputStyle" style="width: 300px;" type="password" name="mm2" />
                                </TD>
                            </TR>

                        </TABLE>
                    </DIV>
                </DIV>

                <DIV  class="haspwdhide" ID=InputDetailBar>
                    <TABLE BORDER=0 CELLSPACING=0 CELLPADDING=10 ALIGN=center>
                        <TR>
                            <TD>
                                <div onClick="actForm.submit();" class="FuncBtn">
                                    <div class=FuncBtnHead></div>
                                    <div class=FuncBtnMemo>确定</div>
                                    <div class=FuncBtnTail></div>
                                </div>
                            </TD>
                            <TD>
                                <div onClick="window.history.go(-1);" class="FuncBtn">
                                    <div class=FuncBtnHead></div>
                                    <div id="gobackbtn" class=FuncBtnMemo>返回</div>
                                    <div class=FuncBtnTail></div>
                                </div>
                            </TD>
                        </TR>
                    </TABLE>
                </DIV>
            </DIV>

        </DIV>
    </form>
</div>
<script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
<script type="text/javascript">

    $('#searchkhmc').on('click' , function(){

        $('#errinfo').hide();
        $('#showkhmc').html('');

        var _khmc = $('#keykhmc').val();
        var _lxdh = $('#lxdh').val();

        var _senddata = 'searchkhmc=1&khmc=' + _khmc + '&lxdh=' + _lxdh;

        $.ajax({
            method:'GET',
            dataType:'json',
            data : _senddata,
            success:function(data){

                $('#showkhmc').html(data.info);
            },
            error:function(){
                $('#errinfo').show();
            }
        });
    });
    $('#showkhmc').on('click','.choosenkhmc',function(){

        var _curkhmc = $(this).html();
        $('#curkhmc').html(_curkhmc);

        $('#companyname').val(_curkhmc);

        var _senddata = 'choosenkhmc=1&ckhmc=' + _curkhmc;
        $.ajax({
            method:'GET',
            dataType:'json',
            data:_senddata,
            success:function(data){

                var lognameinput =  $('#logname');

//                有登录名
                if(data.haslogname == true){

                    lognameinput.val(data.info);
                    lognameinput.closest('td').html('<span style="color:orange;font-size:14px;">' + data.info + '</span>');
//                    lognameinput.attr('disabled','disabled');
//                    $('#oldpwdbox').show();
                    $('.haspwdhide').hide();

                }else{ //        没有登录名 注册

                    lognameinput.removeAttr('disabled');
//                    $('#oldpwdbox').hide();

                }
            },
            error:function(){
                alert('出错啦，请稍后再试');
            }
        });

        $('#yanzhengbox').hide();
        $('#zhinfobox').show();

    });
//    goback
    $('#gobackbtn').on('click',function(){

        $('#yanzhengbox').show();
        $('#zhinfobox').hide();
    });
</script>
</body>
</HTML>
