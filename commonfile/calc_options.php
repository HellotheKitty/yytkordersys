<? if (strlen($dw0) ==2){ //城市级  ?>


    <select name="seldw1" onchange="form1.submit();">
        <option value="所有门店">所有门店</option>
        <?

        $dw0.='0';
        $dwlist = mysql_query("select dwdm, dwmc, ssdq from b_dwdm where locate('$dw0',dwdm)>0 and locate('0000',dwdm)=0",$conn);

        while($dwitem = mysql_fetch_assoc($dwlist)){
            if ($dwitem['dwdm'] == '330200' || $dwitem['dwdm'] == '330900') continue;

            ?>
            <option value="<? echo $dwitem['dwdm'];?>" <? if($seldw == substr($dwitem['dwdm'],0,4)){ echo "selected";} ?>><? echo $dwitem['dwmc'];?></option>

        <? } ?>
    </select>

<? }elseif(strlen($dw0)== 1){  //中国区 ?>

    <select name="seldw2" onchange="form1.submit();">
        <option value="所有区域">所有区域</option>
        <option value="bj" <? if($seldw == 'bj'){ echo "selected";} ?>>北京区</option>
        <option value="sh" <? if($seldw == 'sh'){ echo "selected";} ?>>上海区</option>

    </select>
<? }  ?>
