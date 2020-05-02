<?php
require("inc/conn.php");

header("Content-type:application/vnd.ms-excel;charset=UTF-8"); 
header("Content-Disposition:filename=".iconv("utf-8","gb2312","后道统计[12月].xls"));
header("Expires:0");
header('Pragma:   public'   );
header("Cache-control:must-revalidate,post-check=0,pre-check=0");
// 印前 
//$sql = "select order_mxqt.ddh,sum(pnum1*sl1*jg1+pnum2*sl2*jg2) from order_mxqt left join order_mainqt on order_mxqt.ddh=order_mainqt.ddh where order_mainqt.zzfy='3301' and order_mxqt.ddh in(select ddh from order_zh where fssj>='2015-11-01 00:00:00' and fssj<='2015-11-30 23:59:59' and zy='订单结算' and df>0) group by ddh";

// 后道
$sql = "select order_mxqt.ddh,sum(order_mxqt_hd.sl*order_mxqt_hd.jg) from order_mxqt_hd left join order_mxqt on order_mxqt.id=order_mxqt_hd.mxid where mxid in (select id from order_mxqt where ddh in (select ddh from order_zh where fssj>='2015-12-01 00:00:00' and fssj<='2015-12-31 23:59:59' and zy='订单结算' and df>0)) group by order_mxqt.ddh";
$rs = mysql_query($sql, $conn);
echo "<html><head></head><body>";
echo "<table><tr><th>订单号</th><th>后道价格</th></tr>";
while($row = mysql_fetch_array($rs)){
	echo "<tr>";
	echo "<td>".$row[0]."</td>";
	echo "<td>".$row[1]."</td>";
	echo "</tr>";
	$sum += $row[1];
}
	echo "<tr><td>合计</td><td>".$sum."</td></tr></table>";
echo "</body></html>";
