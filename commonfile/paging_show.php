<style type="text/css">
    .pageing {
        display: block;
        float: right;
        margin:20px;
    }
    .pageing .curlink{
        color:#fff;
        background: #eb6100;
    }
    .pageing a{
        border:1px solid #eb6100;
        display: inline-block;
        *display:inline;
        zoom: 1;
        padding:5px;
        text-align: center;
        line-height:20px;
        margin-left:5px;
    }
    .pageing .normallink{

    }
</style>

<div class="pageing" id="pageing">
<?

$dw0 = dw0($_SESSION['GDWDM']);

if (strlen($dw0) ==2){
    //城市级
    $param .= '&seldw1='.$seldw;
}elseif(strlen($dw0)== 1){
//    中国区
    $param .= '&seldw2='.$seldw;

}else{
//    门店级

}
?>
    <? if($curpage > 1){ ?>
        <a href="?<? echo $param ?>&page=1">首页</a>
        <a href="?<? echo $param ?>&page=<? echo ($curpage-1) ;?>">上一页</a>
    <? }
    if($curpage == 1){
        ?>
        <a class="curlink">首页</a>
        <a class="curlink">上一页</a>

        <?
    }
    if($showpage < $totalpage){
        if($curpage > ($offsetpage+1)){
            echo '...';
        }
    }
    for($i = $startpage;$i<=$endpage;$i++){

        if($curpage<> $i){
            ?>
            <a href="?<? echo $param ?>&page=<? echo $i; ?>" class="normallink">&nbsp;<? echo $i; ?>&nbsp;</a>
            <?
        }else{
            ?>
            <a class="curlink">&nbsp;<? echo $i; ?>&nbsp;</a>
            <?
        }
    }

    if($showpage < $totalpage){

        if($curpage < $totalpage -$offsetpage){

            echo '...';
        }
    }

    if($curpage <$totalpage){
        echo '<a href="?' . $param . '&page='.($p+1).'">下一页</a>';
        echo '<a href="?' . $param . '&page='.$totalpage.'">尾页</a>';
    }

    if($curpage == $totalpage){

        echo "<a class='curlink'>下一页</a>";
        echo "<a class='curlink'>尾页</a>";
    }
    ?>


    <span>共<? echo $totalpage ?>页</span>
</div>