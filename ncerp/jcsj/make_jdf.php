<?
function makejdf($ddh,$conn,$updmxjdf=null,$mxid = null,$xh=1){

    include '../../commonfile/generatejdf.php';
	include '../../commonfile/https.php';
    //reget jdf for one mx
    if($mxid <> ''){
        $sql_all = "select * from order_mxqt where id= $mxid ";
        $str_all = mysql_query($sql_all);
    }else{
        $sql_all="select * from `order_mxqt` where `ddh`= $ddh ";
        $str_all=@mysql_query($sql_all);
    }

    $isyika = mysql_result(mysql_query("select khmc from order_mainqt where ddh = $ddh"),0,'khmc');
    $i = 0;
    while($row_all=@mysql_fetch_array($str_all)){
        $i++;
        $jdf1old = $row_all["jdf1"];//old jdf
        $jdf2old = $row_all["jdf2"];
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

            if($machine1 =='Hp彩色' ){
                if($set_wz1=='横向'){
                    $duplex1 ="TwoSidedFlipY";
                }elseif($set_wz1=='纵向'){
                    $duplex1 ="TwoSidedFlipX";
                }
            }elseif($machine1 =='Hp10000彩色' ){
                if($set_wz1=='横向'){
                    $duplex1 ="TwoSidedFlipX";
                }elseif($set_wz1=='纵向'){
                    $duplex1 ="TwoSidedFlipY";
                }
            }
        }
        if($row_all['machine1']=='Hp彩色'||$row_all['machine1']=='Hp三色'||$row_all['machine1']=='Hp黑白'){
            $x1=320;
            $y1=464;
        }else if($row_all['machine1']=='Hp10000彩色' || $row_all['machine1']=='Hp10000三色' || $row_all['machine1']=='Hp10000黑白'){
            $x1=510;
            $y1=740;
        }

        //isyika
        if($isyika == '商务部/易卡工坊' or $isyika == '北京百立易卡科技有限公司-商务'){
            $paper_name1 = substr($paper_name1,0,3);
        }
        //end isyika
        make_jdf_func::make_jdf($name1, $type1, $x1, $y1, $duplex1, $num1 , $machine1,$set_wz1,$ddh,($xh==1 or $xh=='')?$i:$xh,$paper_name1,$jdf1old);
//        echo json_encode(["code"=>"success"]);

        //	将jdf文件名存入数据库
        if(!empty($jdf1old)){

            $jdfnameold = explode('.',$jdf1old);
            $jdfname = $jdfnameold[0].'-'.'re'.'.jdf';

        }else{
            $jdfname = substr($ddh,5) . '-' . (($xh==1 or $xh=='')?$i:$xh) . '.jdf';
        }
		
        @mysql_query("update order_mxqt set jdf1 = '$jdfname' where id = $mxid",$conn);
		

//        调用nas的jmf 将jdf提交到dfe
//          machine1  没有考虑上海情况
        $localdfe1 = "http://192.168.1.76";
        $localdfe2 = "http://192.168.1.72";
		$localdfe1w= "http://192.168.1.90";
        $jdfurl = "http://oa.skyprint.cn/resources/jdf/" . $jdfname;
        $nasurl = "http://192.168.1.4:88";  //http://yytk-bj.myds.me:88
		if ($y1==740) {
			$url = "http://yytk-bj.myds.me:88/blyjmf/sendjdf.php?local_dfe_url=$localdfe1w&jdfurl=$jdfurl&nas_url=$nasurl";
			$ss=https::httpsRequest($url);
			if (!strstr($ss,'OK')) https::httpsRequest($url);
		} else {
        $url = "http://yytk-bj.myds.me:88/blyjmf/sendjdf.php?local_dfe_url=$localdfe1&jdfurl=$jdfurl&nas_url=$nasurl";

            $ss=https::httpsRequest($url);
			if (!strstr($ss,'OK')) https::httpsRequest($url);
//        machine2
        $url = "http://yytk-bj.myds.me:88/blyjmf/sendjdf.php?local_dfe_url=$localdfe2&jdfurl=$jdfurl&nas_url=$nasurl";
            $ss=https::httpsRequest($url);
			if (!strstr($ss,'OK')) https::httpsRequest($url);
		}
        //----------------构件2
        if($row_all['n2']!=""){

            //$ddh=$ddh."_1";
            $name2=$row_all['file2'];      //名称
            $paper_name2=$row_all['sczzbh2'];
            $load2=$row_all['file2'];      //路径
            $machine2=$row_all['machine2'];   //机器
            $set_wz2=$row_all['hzx2'];        //横纵向
            $num2=$row_all['sl2'];          //数量
            $type2='';                   //纸张种类


            if($row_all['dsm2']=='单面'){
                $duplex2="";
            }else if($row_all['dsm2']=='双面'){

                $duplex2="TwoSidedFlipX";

                if($machine2 =='Hp彩色' ){
                    if($set_wz2=='横向'){
                        $duplex2 ="TwoSidedFlipY";
                    }elseif($set_wz2=='纵向'){
                        $duplex2 ="TwoSidedFlipX";
                    }
                }elseif($machine2 =='Hp10000彩色' ){
                    if($set_wz2=='横向'){
                        $duplex2 ="TwoSidedFlipX";
                    }elseif($set_wz2=='纵向'){
                        $duplex2 ="TwoSidedFlipY";
                    }
                }
            }
            if($row_all['machine2']=='Hp彩色'||$row_all['machine2']=='Hp三色'||$row_all['machine2']=='Hp黑白'){
                $x2=320;
                $y2=464;
            }else if($row_all['machine2']=='Hp10000彩色' || $row_all['machine2']=='Hp10000三色' || $row_all['machine2']=='Hp10000黑白'){
                $x2=510;
                $y2=740;
            }
            //isyika
            if($isyika == '商务部/易卡工坊' or $isyika=='北京百立易卡科技有限公司-商务'){
                $paper_name2 = substr($paper_name2,0,3);
            }
            //end isyika
            make_jdf_func::make_jdf($name2, $type2, $x2, $y2, $duplex2, $num2, $machine2 ,$set_wz2,$ddh,$i.'-2',$paper_name2);

            //	将jdf文件名存入数据库
            $jdfname = substr($ddh,5) . '-' . $i . '-2.jdf';
			
            mysql_query("update order_mxqt set jdf2 = '$jdfname' where id = $mxid",$conn);
			

            //        调用nas的jmf 将jdf提交到dfe
            
            $jdfurl = "http://oa.skyprint.cn/resources/jdf/" . $jdfname;
			if ($y2==740) {
            $url = "http://yytk-bj.myds.me:88/blyjmf/sendjdf.php?local_dfe_url=$localdfe1w&jdfurl=$jdfurl&nas_url=$nasurl";

            $ss=https::httpsRequest($url);
			if (!strstr($ss,'OK')) https::httpsRequest($url);
			} else {
            $url = "http://yytk-bj.myds.me:88/blyjmf/sendjdf.php?local_dfe_url=$localdfe1&jdfurl=$jdfurl&nas_url=$nasurl";

            $ss=https::httpsRequest($url);
			if (!strstr($ss,'OK')) https::httpsRequest($url);

            $url = "http://yytk-bj.myds.me:88/blyjmf/sendjdf.php?local_dfe_url=$localdfe2&jdfurl=$jdfurl&nas_url=$nasurl";

            $ss=https::httpsRequest($url);
			if (!strstr($ss,'OK')) https::httpsRequest($url);
			}
        }

    }

}
?>
