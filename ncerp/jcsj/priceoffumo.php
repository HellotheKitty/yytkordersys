<?
require("../../inc/conn.php");
if($_POST["btn1"]<>""){
//	print_r($_POST);
//	exit;
    $khid = $_POST["khid"];
    $khmc = $_POST["khmc"];
    $fmid = $_POST["fmid"];
    $min = $_POST["min"];
    $max = $_POST["max"];
    $price = $_POST["price"];
    $unit = $_POST["unit"];
    $testrs = mysql_query("select id from price_of_fumo where khid=$khid and fmid=$fmid and min=$min and max=$max and unit='$unit'",$conn);
    if($testrs && mysql_num_rows($testrs) > 0){
        echo "<script>alert('存在相同条件的价格条目，请核对')</script>";
    }else{
        $fmrs_insert = mysql_query("select * from b_fumo where id=$fmid",$conn);
        $chicun_insert = mysql_result($fmrs_insert,0,"chicun");
        $fumo_insert = mysql_result($fmrs_insert,0,"fumo");
        mysql_query("insert into price_of_fumo values ('','$khmc',$khid,'$fmid','$fumo_insert','$chicun_insert',$min,$max,'$unit','$price')",$conn);

    }
}
if($_GET["khid"]){
    $khid = $_GET["khid"];
}
if(!isset($khid))
    exit;
$_khmc = mysql_result(mysql_query("select khmc from base_kh where id=$khid"),0,"khmc");
$fmrs = mysql_query("select * from price_of_fumo where khid=$khid order by fumo,chicun,unit",$conn);
$b_fmrs = mysql_query("select * from b_fumo",$conn);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>无标题文档</title>
    <style>
        table {
            border-collapse: collapse;
            border: none;
            width: 57%;
        }
        th, td {
            border: solid 1px;
            text-align: center;
        }
        input {
            text-align: center;
        }
    </style>
    <script src="../../js/jquery-1.8.3.min.js"></script>
    <script>
        function updateprice(id,price){

        }
        isedit = true;
        $(document).ready(function(){
            $("td").click(function(){
                if(this.id=="" || isedit==false) return;
                color = $("#"+this.id).css("background");
                $("#"+this.id).css("background","green");
                newp = prompt("输入新价格");
                if(newp){
                    if(isNaN(newp)){
                        alert("价格必须为数字");
                        return;
                    }
                    $.ajax({
                        type: "get",
                        url: "price_update.php?type=fumo&id="+this.id+"&np="+newp,
                        success: function(data){
                            if(data > 0){
                                alert("更新成功");
                                $("#"+data).html(newp);
                            }else{
                                alert("失败，重试");
                            }
                        },
                        error: function(){
                            alert("失败，重试");
                        }
                    });
                }
                $("#"+this.id).css("background",color);
            });

            $("#btn1").click(function(){
                if(isedit){
//			alert(isedit);
                    isedit = false;
                    $("#btn1").attr("value","打开编辑");
                }else{
//			alert(isedit);
                    isedit = true;
                    $("#btn1").attr("value","关闭编辑");
                }
            });
        });
        function del(id)
        {
            if(confirm("确定删除？")) {
                $.ajax({
                    type: "get",
                    url: "price_update.php?table=fumo&id="+id,
                    success: function(data){
                        if(data > 0){
                            $("#tr"+id).remove();
                        }else{
                            alert("error! ...");
                        }
                    },
                    error: function(){
                        alert("error! retry plz.");
                    }
                });
            }
        }
    </script>
</head>

<body>
<font size="6"><?echo $_khmc?> 覆膜价格表</font>
<table>
    <tr>
        <th width=8%>覆膜方式</th>
        <th width=15%>成品尺寸</th>
        <th width=7%>下限</th>
        <th width=7%>上限</th>
        <th width=7%>单位</th>
        <th width=7%>单价</th>
        <th width=7%>操作</th>
    </tr>
    <? $i = 0; while($row = mysql_fetch_array($fmrs,MYSQL_ASSOC)) { $i++; ?>
        <tr <? if($i % 2 == 1) echo "style='background:#EFEFEF'"?> id="<?echo "tr".$row["id"];?>">
            <td><? echo $row["fumo"]?></td>
            <td><? echo $row["chicun"]?></td>
            <td><? echo $row["min"]?></td>
            <td><? echo $row["max"]?></td>
            <td><? echo $row["unit"]?></td>
            <td id="<?echo $row["id"]?>" style="cursor:pointer;"><? echo $row["price"]?></td>
            <td><a href="javascript:del(<?echo $row["id"]?>)">删除</a></td>
        </tr>
    <? } ?>
</table>
<br><br>
<div>
    <form method="post">
        <input type="hidden" name="khid" value="<? echo $khid ?>" />
        <input type="hidden" name="khmc" value="<? echo $_khmc ?>" />
        <br>
        <select name="fmid">
            <?
            while($fm = mysql_fetch_array($b_fmrs,MYSQL_ASSOC)){
                echo "<option value='".$fm["id"]."'>".$fm["fumo"];
                $spaceNums = (15 - strlen($ap["fumo"])) / 3;
                for($i = 0; $i < $spaceNums; $i++)
                    echo "　";
                echo "[".$fm["chicun"]."]</option>";
            }
            ?>
        </select>
        下限：<input type="text" name="min" value="1" size="1" />　
        上限：<input type="text" name="max" value="9999" size="1" />　
        单位<input type="text" name="unit" value="P" size="1" />　
        价格：<input type="text" name="price" value="" size="1" />　
        <input type="submit" name="btn1" value="确认添加" />
    </form>
</div>
</body>
</html>
