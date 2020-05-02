<?

$rs=mysql_query("select ifnull(sum(jg1*pnum1*sl1+IFNULL(jg2*pnum2*sl2,0)),0) from order_mxqt mx where mx.ddh='$ddh'",$conn);
$rsfm=mysql_query("select ifnull(sum(jg*sl),0) from order_mxqt_fm where mxid in (select id from order_mxqt where ddh='$ddh')",$conn);
$rshd=mysql_query("select ifnull(sum(jg*sl),0) from order_mxqt_hd where mxid in (select id from order_mxqt where ddh='$ddh')",$conn);

$dje = mysql_result($rs,0,0)+mysql_result($rshd,0,0)+mysql_result($rsfm,0,0);
mysql_query("update order_mainqt set dje=".$dje." where ddh='$ddh'",$conn);

?>