<?
session_start();
require("inc/conn.php");
$dwdm = substr($_SESSION["GDWDM"], 0, 4);
$tmp = explode(';', $_SESSION["QX"]);
$tmp = substr($tmp[0] ,0,2);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<head>

    <link href="css/Styles.css?4" rel="stylesheet" type="text/css">
    <script src="js/jquery-1.8.3.min.js" type="text/javascript"></script>
    <script src="js/jQuery-ui.js" type="text/javascript"></script>
    <script src="js/JQuery.MenuTree.js" type="text/javascript"></script>

    <script type="text/javascript">
        $(function () {
            $('#menu').menuTree();
        });
        function Ddown(s) {
            var character = new Array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O");
            for (var i = 0; i < 15; i++) {
                $('#' + character[i]).slideUp('fast');
            }
            if (s != "") $('#' + s).slideDown('slow');
        }
    </script>

    <style type="text/css">
        html {
            overflow-x: hidden;
            overflow-y: auto;
            width: 200px;
        }
    </style>
</head>
<body>
<div class="leftside">
    <div id="menu" class="menuTree">
        <ul style="display: block;">

            <li class="parent">
                <a href="#">
                    <div class="gerenshezhi treeicon"></div>
                    <span>个人相关</span>
                </a>
                <ul id="N">
                    <li class="child"><a href="changepwd.php?user=<? echo $_SESSION['YKOAUSER'] ?>&from=list" target="main">密码修改</a></li>
                </ul>
            </li>

            <? if($tmp == 'fm'){
                ?>
                <li class="parent"><a href="#">
                        <div class="ordermanage treeicon"></div>
                        <span>订单管理</span>
                    </a>
                    <ul id="N">
                        <li class="child">
                            <a href="ncerp/MYOrderShowns_fm.php?uu=<? echo $_SESSION["YKOAUSER"] ?>&cks=<? echo md5("hzyk" . $_SESSION["YKOAUSER"] . "winner"); ?>" target="main">
                                覆膜订单列表
                            </a>
                        </li>
                    </ul>
                </li>
                <?
            }else{
                ?>
                <li class="parent"><a href="#">
                        <div class="ordermanage treeicon"></div>
                        <span>订单管理</span></a>
                    <ul id="N">
                        <li class="child">
                            <a href="ncerp/MYOrderShowns.php?uu=<? echo $_SESSION["YKOAUSER"] ?>&cks=<? echo md5("hzyk" . $_SESSION["YKOAUSER"] . "winner"); ?>" target="main">
                                订单列表
                            </a>
                        </li>
                    </ul>
                </li>
                <?
            }


            ?>

            <?
            $_SESSION["FBCW"] = 0;
            $_SESSION["FBSD"] = 0;
            $_SESSION['FBKF'] = 0;
            $_SESSION["FBHD"] = 0;
            $_SESSION["FBPD"] = 0;
            $_SESSION["FBFM"] = 0;
            $_SESSION['FBFH'] = 0;

            switch($tmp){
                case 'cw': $_SESSION["FBCW"] = 1;
                    break;
                case 'ch': $_SESSION["FBSD"] = 1;
                    break;
                case 'kf': $_SESSION['FBKF'] = 1;
                    break;
                case 'hd': $_SESSION["FBHD"] = 1;
                    break;
                case 'sc': $_SESSION["FBPD"] = 1;
                    break;
                case 'fm': $_SESSION["FBFM"] = 1;
                    break;
                case 'fh': $_SESSION['FBFH'] = 1;
                    break;
            }

            if($tmp == 'cw' ){
                ?>
                <li class="parent">
                    <a href="#">
                        <div class="cwgzt treeicon"></div>
                        <span>财务工作台</span>
                    </a>
                    <ul id="N">
                        <li class="child">
                            <a href="mainb/ykcw.php?uu=<? echo $_SESSION["YKOAUSER"] ?>&cks=<? echo md5("hzyk" . $_SESSION["YKOAUSER"] . "winner"); ?>" target="main">
                                财务工作台
                            </a>
                        </li>
                    </ul>
                </li>
                <? }
				 if($tmp == 'cw' || $tmp == 'ch'){?>
                <li class="parent"><a href="#">
                        <div class="countdata treeicon"></div>
                        <span>统计数据</span></a>
                    <ul id="N">
                        <li class="child"><a href="mainb/excel_sk3.php" target="main">收款单</a></li>
                        <li class="child"><a href="mainb/excel_dd2.php" target="main">订单统计</a></li>
                        <li class="child"><a href="mainb/excel_mx.php" target="main">订单明细统计</a></li>

                        <li class="child"><a href="mainb/ykcw_xstj.php" target="main">分客服销售统计</a></li>
                        <!--<li class="child"><a href="mainb/ykcw_xstj2.php" target="main">分客户销售统计</a></li>-->

                        <li class="child"><a href="mainb/ykcw_jftj.php" target="main">机房分人员统计</a></li>
                        <li class="child"><a href="mainb/ykcw_hdtj.php" target="main">后道分人员统计</a></li>
                        <li class="child"><a href="mainb/YKcw_fmtj.php" target="main">覆膜分人员统计</a></li>

                        <li class="child"><a href="mainb/jiesuan.php" target="main">结算统计</a></li>

                        <li class="child"><a href="mainb/excel_yq.php" target="main">机器打印统计</a></li>
                        <li class="child"><a href="mainb/excel_hd2.php" target="main">后加工统计</a></li>
                        <li class="child"><a href="mainb/excel_fm.php" target="main">覆膜统计</a></li>

                        <li class="child"><a
                                href="ncerp/jcsj/KH_rank_cw.php?uu=<? echo $_SESSION["YKOAUSER"] ?>&cks=<? echo md5("hzyk" . $_SESSION["YKOAUSER"] . "winner"); ?>"
                                target="main">客户订购排行</a></li>
                        <li class="child"><a
                                href="ncerp/jcsj/KH_czjl.php?uu=<? echo $_SESSION["YKOAUSER"] ?>&cks=<? echo md5("hzyk" . $_SESSION["YKOAUSER"] . "winner"); ?>"
                                target="main">预存记录</a></li>
                        <li class="child"><a href="mainb/datalive.php" target="main">实时数据</a></li>
                    </ul>
                </li>
                <li class="parent"><a href="#">
                        <div class="guestmanage treeicon"></div>
                        <span>客户管理</span></a>
                    <ul id="N">
                        <li class="child"><a href="ncerp/jcsj/KH_list_cw.php?uu=<? echo $_SESSION["YKOAUSER"] ?>&cks=<? echo md5("hzyk" . $_SESSION["YKOAUSER"] . "winner"); ?>"
                                             target="main">客户管理</a></li>

                    </ul>
                </li>
                <!--<li class="parent"><a href="#">
                        <div class="guestmanage treeicon"></div>
                        <span>价格管理</span></a>
                    <ul id="N">
                        <li class="child"><a href="ncerp/jcsj/KH_list_cw.php?uu=<?/* echo $_SESSION["YKOAUSER"] */?>&cks=<?/* echo md5("hzyk" . $_SESSION["YKOAUSER"] . "winner"); */?>"
                                             target="main">门市价格</a></li>
                        <li class="child"><a href="ncerp/jcsj/KH_list_cw.php?uu=<?/* echo $_SESSION["YKOAUSER"] */?>&cks=<?/* echo md5("hzyk" . $_SESSION["YKOAUSER"] . "winner"); */?>"
                                             target="main">会员价格</a></li>
                        <li class="child"><a href="ncerp/jcsj/KH_list_cw.php?uu=<?/* echo $_SESSION["YKOAUSER"] */?>&cks=<?/* echo md5("hzyk" . $_SESSION["YKOAUSER"] . "winner"); */?>"
                                             target="main">协议价格</a></li>

                    </ul>
                </li>-->
                <?
            }
            if($tmp == 'cw'){

                ?>

                <li class="parent"><a href="#">
                        <div class="stockmanage treeicon"></div>
                        <span>库存管理</span>
                    </a>
                    <ul id="N">
                        <li class="child"><a
                                href="pmgr/P_kctj.php?uu=<? echo $_SESSION["YKOAUSER"] ?>&cks=<? echo md5("hzyk" . $_SESSION["YKOAUSER"] . "winner"); ?>"
                                target="main">纸张库存</a></li>
                    </ul>
                </li>


                <li class="parent"><a href="#">
                        <div class="employmanage treeicon"></div>
                        <span>人员管理</span></a>
                    <ul id="N">
                        <li class="child">
                            <a href="ncerp/jcsj/employee_list.php?uu=<? echo $_SESSION["YKOAUSER"] ?>&cks=<? echo md5("hzyk" . $_SESSION["YKOAUSER"] . "winner"); ?>" target="main">
                                人员管理
                            </a>
                        </li>
                    </ul>
                </li>
                <?
            }
            if( $tmp == 'kf'){

                $rskf = mysql_query("select ishead+0 from task_kfry where oabh='" . $_SESSION["YKOAUSER"] . "'", $conn);
                ?>
                <!--<li class="parent"><a href="#">
                        <div class="taskmanage treeicon">.</div>
                        <span>任务管理</span></a>
                    <ul id="N">
                        <li class="child"><a href="mainb/YKKF_main.php?uu=<? echo $_SESSION["YKOAUSER"] ?>&cks=<? echo md5("hzyk" . $_SESSION["YKOAUSER"] . "winner"); ?>" target="main">
                                客服工作台
                            </a>
                        </li>
                        <?
                        if (mysql_num_rows($rskf) > 0 and mysql_result($rskf, 0, 0) == 1) {  //客服主管

                        ?>
                        <li class="child">
                            <a href="mainb/YKKF_userman.php?uu=<? echo $_SESSION["YKOAUSER"] ?>&cks=<? echo md5("hzyk" . $_SESSION["YKOAUSER"] . "winner"); ?>" target="main">
                                客服任务管理
                            </a>
                        </li>
                        <li class="child"><a
                                href="mainb/YKKF_querytask.php?uu=<? echo $_SESSION["YKOAUSER"] ?>&cks=<? echo md5("hzyk" . $_SESSION["YKOAUSER"] . "winner"); ?>"
                                target="main">客服任务查询</a></li>

                        <li class="child"><a
                                href="mainb/YKKF_taskman.php?uu=<? echo $_SESSION["YKOAUSER"] ?>&cks=<? echo md5("hzyk" . $_SESSION["YKOAUSER"] . "winner"); ?>"
                                target="main">任务设置</a></li>

                        <? } ?>
                    </ul>
                </li>

                <li class="parent"><a href="#">
                        <div class="guestmanage treeicon">.</div>
                        <span>客户管理</span></a>
                    <ul id="N">
                        <li class="child">
                            <a href="ncerp/jcsj/KH_list.php?uu=<? echo $_SESSION["YKOAUSER"] ?>&cks=<? echo md5("hzyk" . $_SESSION["YKOAUSER"] . "winner"); ?>" target="main">
                                我的客户
                            </a>
                        </li>
                    </ul>
                </li>
 -->                <?
                if (mysql_num_rows($rskf) > 0 and mysql_result($rskf, 0, 0) == 1) {  //客服主管

                    ?>
                    <li class="parent"><a href="#">
                            <div class="countdata treeicon">.</div>
                            <span>统计数据</span></a>
                        <ul id="N">
                            <li class="child"><a href="mainb/ykcw_xstj.php" target="main">分客服销售统计</a></li>
                            <li class="child"><a href="mainb/excel_yq.php" target="main">机器打印统计</a></li>
                            <li class="child"><a href="mainb/excel_hd2.php" target="main">后加工统计</a></li>
                        </ul>
                    </li>
                    <?
                }
            }
             if($_SESSION["YKOAUSER"] =='lyq-zxd'){
                ?>
                 <li class="parent"><a href="#">
                         <div class="countdata treeicon">.</div>
                         <span>统计数据</span></a>
                     <ul id="N">
                         <li class="child"><a href="mainb/ykcw_jftj.php" target="main">机房分人员统计</a></li>
                     </ul>
                 </li>
            <? }
            if($_SESSION["YKOAUSER"] =='gaost' || $_SESSION["YKOAUSER"] =='renka' || $_SESSION["YKOAUSER"] =='xxping'){
                ?>
                <li class="parent"><a href="#">
                        <div class="countdata treeicon">.</div>
                        <span>统计数据</span></a>
                    <ul id="N">
                        <li class="child"><a href="mainb/ykcw_hdtj.php" target="main">后道分人员统计</a></li>
                    </ul>
                </li>
            <? }

            if($_SESSION["YKOAUSER"] =='dongchao'){
                ?>
                <li class="parent"><a href="#">
                        <div class="countdata treeicon"></div>
                        <span>统计数据</span></a>
                    <ul id="N">
                        <li class="child"><a href="mainb/YKcw_fmtj.php" target="main">覆膜分人员统计</a></li>
                    </ul>
                </li>
            <? }

            if($_SESSION["YKOAUSER"] =='niesf' || $_SESSION["YKOAUSER"] =='niesz' || $_SESSION["YKOAUSER"] =='gaoyj'){
                ?>
                <li class="parent"><a href="#">
                        <div class="countdata treeicon"></div>
                        <span>统计数据</span>
                    </a>
                    <ul id="N">
                        <li class="child"><a href="mainb/ykcw_jftj.php" target="main">机房分人员统计</a></li>
                        <li class="child"><a href="mainb/ykcw_hdtj.php" target="main">后道分人员统计</a></li>
                        <li class="child"><a href="mainb/YKcw_fmtj.php" target="main">覆膜分人员统计</a></li>
                    </ul>
                </li>
            <? }
            ?>


            <div class="liline"></div>
        </ul>
    </div>
    <div class="shadow1"></div>
    <div class="shadow2"></div>
</div>
<script type="text/javascript">
    $('.parent UL LI').click(function () {
        if (this.className == "child") {
            $('.parent ul li').removeClass();
            $('.parent ul li').addClass("child");
            this.className = "childclick";
        } else {
            this.className = "childclick";
        }

    });
</script>
</body>
</html>
