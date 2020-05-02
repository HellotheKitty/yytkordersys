<?php
/**
 * Created by PhpStorm.
 * User: GyCCo
 * Date: 5/28/15
 * Time: 2:41 PM
 */
define('CURRENTPATH', 'oa-service-needs.php');

header("P3P:CP=CAO PSA OUR");
header("Content-type: text/html; charset=utf-8");

session_start();

require("inc/conn.php");

if ($_SESSION["OPERATOR"] == "") {
    echo 'Connection timeout';
    exit;
} elseif ($_SESSION["OPERATOR"] == 'SERVICE') {  //客服功能

    $operator = 'S';

    $rsSTeam = mysql_query("SELECT jb FROM ry_kf where kfbh='{$_SESSION["KFUSER"]}'", $conn);

    $sTeam = substr(mysql_result($rsSTeam, 0, 0), 0, 1);
    $grade = substr(mysql_result($rsSTeam, 0, 0), 1, 2);

    $rsServiceList = mysql_query("SELECT kfbh,xm,jb FROM ry_kf where LEFT(jb, 1)='$sTeam' order by id", $conn);


} elseif ($_SESSION["OPERATOR"] == 'DESIGN') {  //制作功能

    $operator = 'D';

}

$category = $_GET['c'];
$searchFor = urldecode(trim($_GET['v']));

if ($_GET['limit'] == '') {
    $limit = 50;
} else {
    $limit = $_GET['limit'];
}

if ($_GET["Needid"]<>"")
    $rs = mysql_query("SELECT id,uptime,bz,zzwctime,zzr,filename,user,filename2,qfile,mblx,rs,zztime,serviceNo,templateStartTime,templateEndTime,sop_state,rating,wctime from temp_userneed where  id=".$_GET["Needid"], $conn);
else
	$rs = mysql_query("SELECT id,uptime,bz,zzwctime,zzr,filename,user,filename2,qfile,mblx,rs,zztime,serviceNo,templateStartTime,templateEndTime,sop_state,rating,wctime from temp_userneed where  user='".base_decode($_GET["zh"])."'", $conn);

$rsDesignList = mysql_query("SELECT zzbh,xm,jb FROM ry_zz where jb>0 order by id", $conn);
$rsServiceList = mysql_query("SELECT kfbh,xm,jb FROM ry_kf where jb>0 order by id", $conn);


?>

<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>制作需求</title>
    <link rel="stylesheet" type="text/css" href="css/oa-global.css?v2.15" />
</head>
<body>
    <div class="os-loadingBar"></div>
    <?php require('oa-service-navigation.php'); ?>

    <div class="os-main-wrapper clearfix">

        <?php
        while ($assoc = mysql_fetch_assoc($rs)):

            $requestId = $assoc['id'];
            $user = $assoc['user'];
            $createTime = $assoc['uptime'];
            $file = $assoc['filename'];
            $file1 = $assoc['filename2'];
            $file2 = $assoc['qfile'];
            $checkSource = $assoc['rs'];
            $memo = $assoc['bz'];
			$salesm = $assoc['salesm'];
            $designerNo = substr($assoc['mblx'], 2);
            $missionStartTime = $assoc['zztime'];
            $serviceNo = $assoc['serviceNo'];
            $missionCompleteTime = $assoc['zzwctime'];
            $templateStartTime = $assoc['templateStartTime'];
            $templateEndTime = $assoc['templateEndTime'];
            $sopState = $assoc['sop_state'];
            $rating = $assoc['rating'];
            $ready2StartTemplateTime = $assoc['wctime'];

            $rsDesign = mysql_query("SELECT xm FROM ry_zz where zzbh='$designerNo'", $conn);
            if ($designRow = mysql_fetch_row($rsDesign)) {
                $designer = $designRow[0];
            }

            $designer = $designerNo == '' ? '未分配' : 'No. '.$designerNo.' / '.$designer;

            if ($designerNo != '') { //任务已转制作

                if ($missionStartTime == '' && $missionCompleteTime == '') {
                    $missionState = '<p>队列中</p>';
                } elseif ($missionCompleteTime == '') {
                    $missionState = '<p>正在制作...</p><p>开始时间</p><p>'.$missionStartTime.'</p>';
                }

            }else {

                $missionState = '';

            }


            $rsService = mysql_query("SELECT xm FROM ry_kf where kfbh='$serviceNo'", $conn);
            if ($serviceRow = mysql_fetch_row($rsService)) {
                $service = $serviceRow[0];
            }

            if ($serviceNo == '') {
                $service = '暂无';
            }else {
                $service = 'No. '.$serviceNo.' / '.$service;
            }

            if ($templateStartTime != '' && $templateEndTime == '') {
                $templateState = '正在制作...';
            } else {
                $templateState = '';
            }


            $rsUserInfo = mysql_query("SELECT base_user.xm,mobile,tel,base_user.email,depart,base_user.xsbh,QQ,samples,samplesPaper,samplesProcess,newPaper,ry_xs.xm,ssdq,mb FROM base_user,ry_xs where base_user.zh='$user' and base_user.xsbh=ry_xs.xsbh", $conn);

            if ($usera = mysql_fetch_row($rsUserInfo)) {
                $name = $usera[0];
                $mobile = $usera[1];
                $tel = $usera[2];
                $email = $usera[3];
                $depart = $usera[4];
                $xsbh = $usera[5];
                $QQ = $usera[6];
                $samples = $usera[7];
                $samplesPaper = $usera[8];
                $samplesProcess = $usera[9];
                $newPaper = $usera[10];
                $sales = $usera[11];
                $salesArea = $usera[12];
                $salesMobile = $usera[13];
            }

            if ($checkSource == -1) {

                $source = '销售端';

                if ($samples == 1) {
                    $samples = '需要试生产(打样), 纸张: '.$samplesPaper.', 工艺: '.$samplesProcess;
                }else {
                    $samples = '不需要试生产(打样), 文件确认后直接下单';
                }
                $style = '';

            } elseif ($checkSource == -2) {
                $source = '桌面Web客户端';
                $samples = '市场新用户提醒';
                $style = 'style="color: #f25a49"';
            } elseif ($checkSource == -3) {
                $source = '移动Web客户端';
                $samples = '市场新用户提醒';
                $style = 'style="color: #f25a49"';
            } elseif ($checkSource == -4) {
                $source = '微信公众号';
                $samples = '市场新用户提醒';
                $style = 'style="color: #f25a49"';
            } else {
                $source = '客户端';
                $samples = '';
                $style = '';
            }


            if ($newPaper != '') {
                $newPaper = '需要新增纸张: '.$newPaper;
            }


        ?>
        <div class="os-list-wrapper <? if ($rating != '') echo 'os-list-wrapper-rating'.$rating?> clearfix">
            <div class="os-list-userInfo clearfix">
                <span class="os-listNo"><? echo $requestId?></span>
                <input class="os-thisId" type="hidden" value="<? echo $requestId?>"/>
                <span><? echo $user.' - '.$depart?></span>
                <a class="os-user-enter" href='http://www.yikayin.com/pmc/checklogin.php?bs=<? echo urlencode(iconv("utf-8","gbk",$user));?>&ks=<? echo md5(iconv("utf-8","gbk","hzyk".$user."winner"));?>&kfuser=<? echo $_SESSION["KFUSER"];?>' target="_blank">进入系统</a>
                <span><i>联系人: </i><? echo $name != '' ? $name : '-'?></span>
                <span><i>QQ: </i><? echo $QQ != '' ? $QQ : '-'?></span>
                <span><i>手机: </i><? echo $mobile != '' ? $mobile : '-'?></span>
                <span><i>电话: </i><? echo $tel != '' ? $tel : '-'?></span>
                <span><i>Email: </i><? echo $email != '' ? $email : '-'?></span>
                <span><i><? echo $createTime?></i></span>
                <span><i>销售: </i><? echo $sales.' ('.$salesArea.') - '.$salesMobile?></span>
            </div>
            <div class="os-list-userFiles clearfix">
                <span>用户文件: </span>
                <a href="<? echo $file != '' ? 'upload/'.$file : 'javascript:'?>" target="_blank"><? echo $file != '' ? $file : '-'?></a>
                <a href="<? echo $file1 != '' ? 'upload/'.$file1 : 'javascript:'?>" target="_blank"><? echo $file1?></a>
                <a href="<? echo $file2 != '' ? 'upload/'.$file2 : 'javascript:'?>" target="_blank"><? echo $file2?></a>
                <a class="os-upload-userFiles" href="javascript:">补充上传用户文件</a>
                <?php if ($operator == 'D' && $missionCompleteTime == '') { ?>
                    <a class="os-sop-settings <? if ($sopState == 1) echo 'os-sop-settings-showing';?>" href="javascript:" onClick='window.open("SOP/sop_set.php?id=<? echo $requestId?>", "HT_dhdj", "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=yes,width=650,height=650,left=300,top=100")'>专色印配置</a>
                    <select class="os-chooseWay2Print" name="" id="">
                        <option value="0">数码印</option>
                        <option <? if ($sopState == 1) echo 'selected'?> value="1">专色印</option>
                    </select>
                <?php } ?>
            </div>
            <div class="os-list-content clearfix">
                <div class="os-list-content-l">
                    <div class="os-list-subtitle">需求描述</div>
                    <p <? echo $style?>>需求来源: <? echo $source?></p>
                    <p <? echo $style?>><? echo $samples?></p>
                    <p><? echo $newPaper?></p>
                    <p><? echo $memo?></p>
                    <p><? echo $salesm?></p>
                </div>
                <div class="os-list-content-r">
                    <div class="os-list-subtitle">接入客服</div>
                    <p class="serviceNumber"><? echo $service?></p>
                    <?php if ($serviceNo == '' && $missionCompleteTime == '') { ?>
<!--                        <a data-id="--><?// //echo $requestId?><!--" class="os-click2AcceptTask os-buttonStyle2 transition" href="javascript:">接 入</a>-->
                        <p>
                            <select class="os-chooseService" name="" id="">
                                <?php
                                mysql_data_seek($rsServiceList, 0);
                                while ($rowS = mysql_fetch_row($rsServiceList)) {
                                    ?>
                                    <option <? if (substr($rowS[3], 1, 2) == 1) echo 'selected'?> value="<? echo $rowS[0]?>"><? echo $rowS[0].' / '.$rowS[1]?></option>
                                <?php } ?>
                            </select>
                        </p>
                        <a data-id="<? //echo $requestId?>" class="os-click2OtherService os-buttonStyle2 transition" href="javascript:">接入</a>
                    <?php }//} elseif ($operator == 'S' && $serviceNo == $_SESSION['KFUSER'] && $missionCompleteTime == '') { ?>
                        <!-- <p>
                            <select class="os-chooseService" name="" id="">
                                <?php
                                //mysql_data_seek($rsServiceList, 0);
                                //while ($rowS = mysql_fetch_row($rsServiceList)) {
                                    ?>
                                    <option <? //if ($rowS[0] == $_SESSION['KFUSER']) echo 'selected'?> value="<? //echo $rowS[0]?>"><? //echo $rowS[0].' / '.$rowS[1]?></option>
                                <?php //} ?>
                            </select>
                        </p>
                        <a data-id="<? //echo $requestId?>" class="os-click2OtherService os-buttonStyle1 transition" href="javascript:">转其他客服</a> -->
                    <?php //} ?>
                    <?php if ($missionCompleteTime == '') { ?>
                    <a class="os-click2UploadSingleCard os-buttonStyle3 transition" href="javascript:" onClick='window.open("YK_newman.php?bs=<? echo $user?>", "HT_dhdj", "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=yes,width=650,height=600,left=300,top=100")'>上传单名片</a>
                    <?php } ?>
                    <?php if (($designerNo == $_SESSION['ZZUSER'] || $serviceNo == $_SESSION['KFUSER']) && $missionCompleteTime == '') { ?>
                        <a data-id="<? //echo $requestId?>" class="os-click2DropTask transition" href="javascript:">任务丢弃</a>
                    <?php } ?>
                </div>
                <div class="os-list-content-r">
                    <div class="os-list-subtitle">制作</div>
                    <p class="designerNumber"><? echo $designer?></p>
                    <? echo $missionState?>
                    <?php if ($operator == 'S' && $missionStartTime == '' && $serviceNo == $_SESSION['KFUSER'] && $missionCompleteTime == '') { ?>
                        <p>
                            <select class="os-chooseDesigner" name="" id="">
                                <?php
                                    mysql_data_seek($rsDesignList, 0);
                                    while ($rowD = mysql_fetch_row($rsDesignList)) {
                                ?>
                                    <option value="<? echo $rowD[0]?>"><? echo $rowD[0].' / '.$rowD[1]?></option>
                                <?php } ?>
                            </select>
                        </p>
                        <?php if ($designerNo == '' && $missionCompleteTime == '') { ?>
                            <a data-id="<? //echo $requestId?>" class="os-click2ShareTask os-buttonStyle1 transition" href="javascript:">任务转制作</a>
                        <?php } elseif ($missionStartTime == '' && $missionCompleteTime == '') { ?>
                            <a data-id="<? //echo $requestId?>" class="os-click2ShareTask os-buttonStyle1 transition" href="javascript:">重新分配</a>
                        <?php } ?>
                    <?php } elseif ($designerNo == $_SESSION['ZZUSER'] && $missionStartTime == '' && $operator == 'D') { ?>
                        <a data-id="<? //echo $requestId?>" class="os-click2StartMission os-buttonStyle2 transition" href="javascript:">开 始</a>
                        <p>
                            <select class="os-chooseDesigner" name="" id="">
                                <?php
                                mysql_data_seek($rsDesignList, 0);
                                while ($rowD = mysql_fetch_row($rsDesignList)) {
                                    ?>
                                    <option <? if ($rowD[0] == $_SESSION['ZZUSER']) echo 'selected'?> value="<? echo $rowD[0]?>"><? echo $rowD[0].' / '.$rowD[1]?></option>
                                <?php } ?>
                            </select>
                        </p>
                        <a data-id="<? //echo $requestId?>" class="os-click2ShareTask os-buttonStyle1 transition" href="javascript:">转其他制作</a>
                    <?php } ?>
                </div>
                <div class="os-list-content-r">
                    <div class="os-list-subtitle">模板</div>
                    <a data-id="<? //echo $requestId?>" class="os-click2UploadTemplateFiles transition" href="JavaScript:">上传模板相关文件</a>
                    <p><? echo $templateState?></p>
                    <?php if ($missionCompleteTime == '') { ?>
                        <a data-id="<? //echo $requestId?>" class="os-click2SetMissionComplete transition" href="JavaScript:">任务完成<br>模板待确认</a>
                        <a data-id="<? //echo $requestId?>" class="os-buttonStyle1 os-click2SetCompleteAndAskForTemplate transition" href="JavaScript:">完成并提交模板需求</a>
                    <?php } elseif ($ready2StartTemplateTime == '') { ?>
                        <a data-id="<? //echo $requestId?>" class="os-buttonStyle1 os-click2AskForTemplate transition" href="JavaScript:">提交模板需求</a>
                        <a data-id="<? //echo $requestId?>" class="os-buttonStyle1 os-click2DropTemplateMission transition" href="JavaScript:">不需要电子模板</a>
                    <?php } elseif ($templateStartTime == '') { ?>
                        <a data-id="<? //echo $requestId?>" class="os-click2StartTemplate os-buttonStyle2 transition" href="JavaScript:">开 始</a>
                    <?php } elseif ($templateEndTime == '') { ?>
                        <a data-id="<? //echo $requestId?>" class="os-click2EndTemplate os-buttonStyle1 transition" href="JavaScript:">完 成</a>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php $newPaper = ''; endwhile ?>


    </div>

    <div class="os-global-notice-wrapper">
        <div class="os-global-notice"></div>
    </div>

    <div class="os-global-upload-wrapper">

    </div>

<script type="text/javascript" src="jsp/jquery.min.js"></script>
<script type="text/javascript" src="jsp/ajaxfileupload.js"></script>
<script type="text/javascript">

    function processing(thisElement, mission, designerNo, serviceNo, temp) {
        var i = thisElement;
        var m = mission;
        var d = arguments[2] ? arguments[2] : "";
        var s = arguments[3] ? arguments[3] : "";
        var t = arguments[4] ? arguments[4] : "";
        var tc = i.parents('.os-list-wrapper');
        var id = tc.find('.os-thisId').val();
        var n = $(".os-global-notice-wrapper");
        var nc = $(".os-global-notice");

        $.ajax({
            cache: false,
            type: "POST",
            url: "oa-service-notify.php?" + Math.round(Math.random() * 10000),
            data: {
                id: id,
                mission: m,
                designerNo: d,
                serviceNo: s,
                sop: t
            },
            dataType: 'json',
            beforeSend: function() {
                nc.html('正在玩命处理...');
                n.slideDown(160);
            },
            error: function(request) {
                nc.html('服务器累了, 请重试...');
            },
            success: function(data) {
                if (data.sign == 1) {
                    nc.html('操作成功了 ! ! !');
                    switch (m) {
                        case 'M06':
                            tc.slideUp(188);
                            break;
                        case 'M07':
                            if (t == 1) {
                                tc.find(".os-sop-settings").addClass('os-sop-settings-showing');
                            } else {
                                tc.find(".os-sop-settings").removeClass('os-sop-settings-showing');
                            }
                            break;
                        case 'M09':
                            tc.slideUp(188);
                            break;
                        default :
                            $(".os-nav-reload").click();
                            break;
                    }

                } else if (data.sign == -100) {
                    nc.html('登录超时, 请重新登录');
                } else if (data.sign == -1) {
                    nc.html('服务器累了, 请重试...');
                } else {
                    nc.html(data.msg);
                }

                setTimeout(function() {
                    n.slideUp(160);
                }, 2800);
            }
        });

    }

    $(document).ready(function() {

        var url = window.location.href;
        var arrUrl = url.split("/");
        var strClass = arrUrl[arrUrl.length-1];
        //strClass = strClass.split("?");
        //var cClass = strClass[0];
        //cClass = cClass.replace(".php","");

        //$("." + cClass).addClass('menu-selected');

        $(".os-navigation [href='" + strClass + "']").addClass('os-nav-selected');

        $(".os-count-no").each(function() {
            var c = Number($(this).html());
            if (c > 0) {
                $(this).css('color', '#f25a49');
            }
        });

        $(".os-click2DropTask").live("click", function() {
            var i = $(this);
            if (confirm("确认丢弃任务吗")) {
                processing(i, 'M00');
            }
        });

        $(".os-click2OtherService").live("click", function() {
            var i = $(this);
            var s = i.parent("div").find(".os-chooseService").val();
            processing(i, 'M02', '', s);
        });

        $(".os-click2ShareTask").live("click", function() {
            var i = $(this);
            var d = i.parent("div").find(".os-chooseDesigner").val();
            processing(i, 'M03', d);
        });

        $(".os-click2StartMission").live("click", function() {
            var i = $(this);
            processing(i, 'M04');
        });

        $(".os-click2SetMissionComplete").live("click", function() {
            var i = $(this);
            if (confirm("确定任务完成, 没点错 0.0 ??")) {
                processing(i, 'M06');
            }
        });

        $(".os-chooseWay2Print").live("change", function() {
            var i = $(this);
            processing(i, 'M07', '', '', i.val());
        });

        $(".os-click2StartTemplate").live("click", function() {
            var i = $(this);
            processing(i, 'M08');
        });

        $(".os-click2EndTemplate").live("click", function() {
            var i = $(this);
            if (confirm("确定模板完成, 没点错 0.0 ??")) {
                processing(i, 'M09');
            }
        });

        $(".os-click2SetCompleteAndAskForTemplate").live("click", function() {
            var i = $(this);
            if (confirm("确定完成并提交需求, 没点错 0.0 ??")) {
                processing(i, 'M10');
            }
        });

        $(".os-click2AskForTemplate").live("click", function() {
            var i = $(this);
            if (confirm("确定提交模板需求, 没点错 0.0 ??")) {
                processing(i, 'M11');
            }
        });

        $(".os-click2DropTemplateMission").live("click", function() {
            var i = $(this);
            if (confirm("确定不需要电子模板, 没点错 0.0 ??")) {
                processing(i, 'M12');
            }
        });

        $(".os-searchForRequirements").live("click", function() {
            var v = $(".os-searchField").val();
            if (v != '') {
                $(".os-loadingBar").slideDown(200);
                setTimeout(function() {
                    window.location.href = '<? echo CURRENTPATH?>?c=searchForRequirements&v=' + encodeURIComponent(v);
                }, 200);
            }
        });

        $(".os-nav-reload").live("click", function() {
            $(".os-loadingBar").slideDown(200);
            setTimeout(function() {
                window.location.reload();
            }, 200);
        });

        setTimeout(function() {
            $(".os-loadingBar").slideUp(240);
        }, 800);



    });
</script>
</body>
</html>






