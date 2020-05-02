<?
session_start();
require("JDF/function/conn.php");
require("../inc/connykgf.php");

if($_SESSION["CUSTOMER"]<>"OK")
    die("请登录！");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<script src="../js/jquery-1.8.3.min.js" type="text/javascript"></script>
<head>
    <style type="text/css">
        .boxinput .aaaa{

        }
        #pbtype{
            margin-left:20px;}

        .i-item{
            display: block;
        }
    </style>
</head>
<? if($_SESSION['INFO']['loginname'] == 'bjykgf') $scjd='北京'; else $scjd='上海';
$khmc=$_SESSION["KHMC"];
//$sql = "SELECT distinct mbzz,pbh,pdate,scbh FROM `p_mx` left join base_zz_ck on instr(base_zz_zzmc,concat(\"'\",replace(replace(replace(replace(replace(mbzz,'[2盒版]',''),'工牌-',''),'|夸客橙色模板',''),'|夸客蓝色模板',''),'60张',''),\"'\"))>0 where not pbh is null and qrdate is null and scjd='上海' ORDER BY base_zz_ck.scbh desc,base_zz_zzmc, qrdate desc ";
$sql = "SELECT distinct mbzz,pbh,pdate,scbh FROM `p_mx` left join base_zz_ck on instr(base_zz_zzmc,concat(\"'\",replace(replace(replace(replace(replace(mbzz,'[2盒版]',''),'工牌-',''),'|夸客橙色模板',''),'|夸客蓝色模板',''),'60张',''),\"'\"))>0 where not pbh is null and qrdate is null and scjd='$scjd' ORDER BY base_zz_ck.scbh desc,base_zz_zzmc, qrdate desc ";
//$sql = "SELECT distinct mbzz,pbh,pdate FROM `p_mx` where not pbh is null and qrdate is null and scjd='上海'";
$rs = mysql_query($sql,$connykgf);
//调用：http://erp.yikayin.com/nc_erp/pbfiledownload.php?ddh=1234&pbhs=111;222;333

?>
<body>
<!--<form method="post" enctype="multipart/form-data">-->
<!--    <h3>上传图片：</h3><input  type="file" name="file_stu" />-->
<!--    <div id="boxinput" class="boxinput">-->
<!--        <div class="innerbox">-->
<!--            <input class="textinput" type="text" name="0"  placeholder="填写完整的文件名，如test.pdf" width="300px">-->
<!--            <input class='delbtn' type='button' value='删除'/>-->
<!--        </div>-->
<!--    </div>-->
<!--    <input type="button" value="增加文件名" onclick="addmx()"/>-->
<!--    <input type="submit"  value="提交生成订单"/>-->
<!--</form>-->
<form method="post" action="NS_new.php?">
    <h3>请选择需要生成订单的文件名</h3>
    <div id="boxinput" class="boxinput">
        <? if(mysql_num_rows($rs)>0){
            ?>
            <lable><input id='ifcheckall' type='checkbox'/>全选</lable>
            <select id="pbtype">
<!--                <option value="all">选择拼板类型</option>-->
                <option value="mp1w">名片1w</option>
                <option value="mp76">名片76</option>
                <option value="gp1w">工牌1w</option>
                <option value="gp76">工牌76</option>
            </select>
            <br/>
            <?
        }?>
        <div id="filenamebox">
        <?
        while($item = mysql_fetch_array($rs,MYSQL_ASSOC)){

            if(!empty($item['pbh'])){
                $locstr = $item['pbh'].'-'.'zzmc'.'-'.$item['pdate'];
                $sqlisorder = mysql_query("select order_mxqt.id from order_mxqt,order_mainqt where order_mainqt.ddh=order_mxqt.ddh and khmc='$khmc' and datediff(now(),ddate)<10 and locate('$locstr',file1)>0",$conn);
                if(mysql_num_rows($sqlisorder)>0){
                    continue;
                }
                ?>
                <lable datatype="<? echo $item['pbh']; ?>" class="i-item">
                    <input name="fnames[]" class="aaaa" type="checkbox" value="<? echo $item['pbh'].'-'.'zzmc'.'-'.$item['pdate'].'|'.$item['scbh'] . '|'.str_replace('|','',$item['mbzz']) ?>">
                    <? echo $item['pbh'].'-'.$item['mbzz'].'-'.$item['pdate'].'|'.$item['scbh']; ?>
                </lable>

            <? }
        }?>
        </div>
    </div>
    <br/>
    <!--<label>
        <input type="radio" name="mptype" value="yikaba"/>易卡吧
    </label>
    <label>
        <input type="radio" name="mptype" value="yikayin"/>易卡印
    </label>
    <br/>-->
    <input name="newordbt" type="submit" value="提交下单"/>
</form>

<script type="text/javascript">


$(document).on('click','#ifcheckall',function(){

    var allinput = $("input[name='fnames[]']");
    var ischecked = $('#ifcheckall').is(':checked');
    allinput.attr('checked',ischecked);

});
    $('#pbtype').on('change',function(){

        var _type = $(this).val();
        var items = $('#filenamebox').find('lable');
        var allinput = $("input");
        allinput.attr('checked',false);

        if(_type != 'all'){

            switch (_type){

                case 'mp76':
                    for(var i=0; i < items.length;i++){

                        //I
                        var itemtype = $(items[i]).attr('datatype');

                        if(itemtype.indexOf('I') > 0 && (itemtype.indexOf('I72') < 0 ) && itemtype.indexOf('I65') < 0){
                            $(items[i]).show();
                            $(items[i]).find('input').attr('name','fnames[]');
                        }else{
                            $(items[i]).hide();
                            $(items[i]).find('input').attr('name','');

                        }
                    }

                    break;
                case 'mp1w':

                    for(var i=0; i < items.length;i++){

                        //I72 I65 D72
                        var itemtype = $(items[i]).attr('datatype');

                        if( (itemtype.indexOf('I72') > 0 ) ||itemtype.indexOf('D72') > 0||itemtype.indexOf('I65') > 0 ||itemtype.indexOf('I60') > 0 ||itemtype.indexOf('C9050') > 0){
                            $(items[i]).show();
                            $(items[i]).find('input').attr('name','fnames[]');


                        }else{
                            $(items[i]).hide();
                            $(items[i]).find('input').attr('name','');

                        }
                    }
                    break;
                case 'gp1w':

                    for(var i=0; i < items.length;i++){

//                        GP72 GP65
                        var itemtype = $(items[i]).attr('datatype');

                        if( itemtype.indexOf('GP72') > 0 || itemtype.indexOf('GP65') > 0){
                            $(items[i]).show();
                            $(items[i]).find('input').attr('name','fnames[]');

                        }else{
                            $(items[i]).hide();
                            $(items[i]).find('input').attr('name','');

                        }
                    }
                    break;
                case 'gp76':
                    for(var i=0; i < items.length;i++){

//                        GP
                        var itemtype = $(items[i]).attr('datatype');

                        if(itemtype.indexOf('GP')>0 && itemtype.indexOf('GP72') < 0 && itemtype.indexOf('GP65') < 0){
                            $(items[i]).show();
                            $(items[i]).find('input').attr('name','fnames[]');

                        }else{
                            $(items[i]).hide();
                            $(items[i]).find('input').attr('name','');

                        }
                    }
                    break;
                default:
                    break;
            }

        }else{

            items.show();
            $(items[i]).find('input').attr('name','fnames[]');

        }

    });
    $(document).ready(function(){
        $('#pbtype').change();
    })
</script>
</body>
