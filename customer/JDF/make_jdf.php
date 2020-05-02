<meta charset="utf-8" />
<?
error_reporting(E_ALL ^ E_NOTICE);
//require("inc/conn.php");
require "function/error_exit.php";
require "function/function.php";
//----在线
require "function/conn.php";
//----测试
@$ddh = $_GET["ddh"];
$sql_all="select * from `order_mxqt` where `ddh`='$ddh'";
$str_all=@mysql_query($sql_all);

$i = 0;
while($row_all=@mysql_fetch_array($str_all)){
    $i++;
    $mxid = $row_all['id'];//明细id
    $paper_name1=$row_all['sczzbh1'];  //纸张名称
    $name1=$row_all['file1'];      //名称
    $num1=$row_all['sl1'];          //数量
    $machine1=$row_all['machine1'];
    $set_wz1=$row_all['hzx1'];        //横纵向1
    $type1='';                   //纸张种类
    if($row_all['dsm1']=='单面'){
        $duplex1="";
    }else if($row_all['dsm1']=='双面'){
        $duplex1="TwoSidedFlipX";
    }
    if($row_all['machine1']=='Hp彩色'||$row_all['machine1']=='Hp三色'||$row_all['machine1']=='Hp黑白'){
        $x1=320;
        $y1=464;
    }else if($row_all['machine1']=='Hp10000彩色' || $row_all['machine1']=='Hp10000三色' || $row_all['machine1']=='Hp10000黑白'){
        $x1=510;
        $y1=740;
    }
    make_jdf($name1, $type1, $x1, $y1, $duplex1, $num1 , $machine1,$set_wz1,$ddh,$i,$paper_name1);
	
	error_exit($ddh, $i, $machine1);

//	将jdf文件名存入数据库
    $jdfname = substr($ddh,5) . '-' . $i . '.jdf';
    @mysql_query("update order_mxqt set jdf1 = '$jdfname' where ddh='$ddh' and id = $mxid",$conn);

//----------------构件2
    if($row_all['file2']!=""){
        $i ++;
        $ddh=$ddh."_1";
        $name2=$row_all['file2'];      //名称
        $paper_name2=$row_all['sczzbh2'];
        $load2=$row_all['file2'];      //路径
        $machine2=$row_all['machine2'];   //机器
        $set_wz2=$row_all['hzx2'];        //横纵向1
        $num2=$row_all['sl2'];          //数量
        $type2='';                   //纸张种类
        if($row_all['dsm2']=='单面'){
            $duplex2="";
        }else if($row_all['dsm2']=='双面'){
            $duplex2="TwoSidedFlipX";
        }
        if($row_all['machine2']=='Hp彩色'||$row_all['machine2']=='Hp三色'||$row_all['machine2']=='Hp黑白'){
            $x2=320;
            $y2=464;
        }else if($row_all['machine2']=='Hp10000彩色'||$row_all['machine2']=='Hp10000三色'|| $row_all['machine2']=='Hp10000黑白'){
            $x2=510;
            $y2=740;
        }
        make_jdf($name2, $type2, $x2, $y2, $duplex2, $num2, $machine2 ,$set_wz2,$ddh,$i,$paper_name2);

        error_exit($ddh, $i, $machine2);
        //	将jdf文件名存入数据库
        $jdfname = substr($ddh,5) . '-' . $i . '.jdf';
        mysql_query("update order_mxqt set jdf2 = '$jdfname' where ddh = '$ddh' and id = $mxid",$conn);
    }
//--------------构件3
    if($row_all['file3']!=""){
        $i++;
        $ddh=$ddh."_2";
        $name3=$row_all['file3'];      //名称
        $paper_name3=$row_all['sczzbh3'];
        $machine3=$row_all['machine3'];
        $set_wz3=$row_all['hzx3'];        //横纵向1
        $num3=$row_all['sl3'];          //数量
        $type3='';                   //纸张种类
        if($row_all['dsm3']=='单面'){
            $duplex3="";
        }else if($row_all['dsm3']=='双面'){
            $duplex3="TwoSidedFlipX";
        }
        if($row_all['machine3']=='Hp彩色'){
            $x3=320;
            $y3=464;
        }else if($row_all['machine3']=='Hp10000彩色'||$row_all['machine3']=='Hp10000三色'){
            $x3=510;
            $y3=740;
        }
        make_jdf($name3, $type3, $x3, $y3, $duplex3, $num3, $machine3 ,$set_wz3,$ddh,$i,$paper_name3);
        
		error_exit($ddh, $i, $machine3);
        //	将jdf文件名存入数据库
        $jdfname = substr($ddh,5) . '-' . $i . '.jdf';
        mysql_query("update order_mxqt set jdf3 = '$jdfname' where ddh = '$ddh' and id = $mxid",$conn);
    }
}
?>
