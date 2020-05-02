<meta charset="utf-8" />
<?
        ini_set("upload_max_filesize", "15000m");
        error_reporting(E_ALL ^ E_DEPRECATED&E_ALL ^ E_NOTICE);
        //屏蔽错误
        require "function/public.php";
		require "function/pinban_double_double.php";
		require "function/pinban_double_same.php";
		require "function/pinban_double.php";
		require "function/pinban_one.php";
		//拼版函数导入
	    require('../lib/fpdi.php');
		require "../JDF/function/conn.php";
		$bh=$_GET['ddh'];
		$double=$_POST['double'];
		$dir = '../server/upload/';
        class PDF extends fpdi{}
		$pdfd=new PDF();
		//post
		$ddh = $bh;                               //单号
		$materialid = $_POST['materialid'];       //纸张种类
		$dsm1 = $_POST['double'];                 //单双面
 		$pname = $_POST["pname"];                 //印件名
		$machine1 = $_POST["machine"];            //机器
		$pnum1 = count($filearr) - 2;             //页数
		$sl1 = $_POST["sl1"];                       //数量
		$n2 = '';
        $productname='单张';
		$chicun='A3';
		$color1='彩色';
		$jldw1='P';
		$hzx1="纵向";
		// 设置
		$pdfd->SetMargins(0,0,0);
		$pdfd->SetAutoPageBreak(1,1);
		$pdfd->AddGBFont();
		$pdfd->Open();
		$pdfd->SetFont('GB','B',8);
		$pdfd->AliasNbPages();
					  
		$page_num=1;
		$unzipdir = $dir.$bh.'/';
		if(!is_dir($unzipdir)){
			echo "<script>alert('请上传文件！');location.href='../upload_caipu.php?ddh=$bh'</script>";
			exit;
		}
		$filearr = scandir($unzipdir);	// P数 = count($filearr) - 2  列出指定路径中的文件和目录 
		
        list($width, $height, $type, $attr) = getimagesize($unzipdir.$filearr[2]);
		$width = $width*2.54/30;               //2.54的距离
		$height= $height*2.54/30;
		if($width>308){
			$hzx="L";
		}else{
			$hzx="P";
		}
		if($sl1!='1'){
			if($width<=225&$height<=310){      //多份双拼
			     $c=count($filearr);
			     for($i = 2; $i < $c; $i++) {     //单拼
			     $x=$c-2;
			     pinban_two_double($unzipdir, $filearr[$i], $pdfd,$pname.$bh."第：".$page_num."页"."共：".$x."页");
				 $page_num++;
				 
		         }
                 deldir($unzipdir);
			}else{                             //多份单拼
			    for($i = 2; $i < count($filearr); $i++) {     //单拼
                $x=count($filearr)+1;
			    pinban($unzipdir, $filearr[$i], $pdfd,$pname.$bh."第：".$page_num."页"."共".$x."页",$hzx);
		        $page_num++;

				}
		        deldir($unzipdir);
			}
		}else if($sl1=='1'){
	        if($width<=225&$height<=310){                    //双拼
	    	$c=count($filearr);
			$b=(int)($c/2-0.5);
			$b_b=(int)($c/2);
			$b_c=(int)($c/4)+1;
			if($double=='单面'){
	    	for($i = 1; $i <=$b; $i++) {
	    		$a=$i+1;                  //单面
	    		$x=$b;
	    	    if($c%2=='0'){      //偶数
	    	    	pinban_double($unzipdir, $filearr[$a]."A".$filearr[$i+$b_b], $pdfd,$pname.$bh."第：".$page_num."页"."共".$x."页");
	    	        $page_num++;
				}else if($c%2=='1'){
					if($a==$b+1){
						$a='';
					}
					pinban_double($unzipdir, $filearr[$a]."A".$filearr[$i+$b_b], $pdfd,$pname.$bh."第：".$page_num."页"."共".$x."页");
	    	        $page_num++;
				    }
			    }
			deldir($unzipdir);
			}else if($double='双面'){
			$a=0;
			$c=$c-1;
			$page_num=1;
			for($i = 1; $i <$b_c; $i++) {                   //双面
			$x1=$page_num+($i*$a);
			$x2=$x1+1;
	        pinban_double_double($unzipdir,
	        $filearr[$c-$i*$a]."A".$filearr[2*$i],          //拼版顺序
	        $filearr[2*$i+1]."B".$filearr[$c-$a*$i-1], 
	        $pdfd,$pname.$bh."共".($b_c+1)."页",
	        "第".$x1."页","第".$x2."页");
	        $a++;
			}
			deldir($unzipdir);
			}	
        
	    }else{
		   for($i = 2; $i < count($filearr); $i++) {     //单拼
		    $x=count($filearr)-2;
			pinban($unzipdir, $filearr[$i], $pdfd,$pname.$bh."第：".$page_num."页"."共：".$x."页",$hzx);
		    $page_num++; 
		   }
		   deldir($unzipdir);

	    }
	}
		$outfilename = substr($bh, -6).".pdf";
		$path=substr($bh, -6);
		if (!file_exists("BillFiles/".$path)) {
        @mkdir("BillFiles/".$path);
    }
		outputpdf("../server/files/".$outfilename, $pdfd);
        $file1 = substr($bh, -6);           //文件名
        //deldir($unzipdir);
        $n1="单张";
		$paper1="2";
		$jg1="0.000";
		
		$estr="gswin\\gswin32c.exe -dBATCH -dNOPAUSE -dFirstPage=1 -dAlignToPixels=0 -dGridFitTT=0 -sDEVICE=jpeg -dTextAlphaBits=4 -dGraphicsAlphaBits=4 -sOutputFile=BillFiles/{$path}/{$outfilename}%d.jpg -dJPEGQ=100 -r100x100 -q ../server/files/{$outfilename} -c quit";
		$ss="";$si=0;
		exec($estr, $ss, $si);
		$file1=urlencode($file1);
		$file1="http://oa.skyprint.cn/customer/neworder/server/files/".$file1.".pdf";
		$sql = "INSERT INTO `ordersys`.`order_mxqt` 
		(`id`, `ddh`, `productname`, `pname`, `chicun`, `sl`, `n1`, `file1`, `machine1`, 
		`paper1`, `color1`, `jldw1`, `dsm1`, `hzx1`, `pnum1`, `sl1`, `jg1`, `n2`, `file2`,
		`machine2`, `paper2`, `color2`, `jldw2`, `dsm2`, `hzx2`, `pnum2`, `sl2`, `jg2`, `n3`,
		`file3`, `machine3`, `paper3`, `color3`, `jldw3`, `dsm3`, `hzx3`, `pnum3`, `sl3`, `jg3`, 
		`down_type`, `user`, `jdf1`, `jdf2`, `jdf3`, `sczzbh1`, `sczzbh2`, `sczzbh3`) 
		VALUES
		(NULL, '$ddh', '$productname', '$pname', '$chicun', '$sl', '$n1', '$file1', '$machine1',
		'$paper1', '$color1', '$jldw1', '$dsm1', '$hzx1', '$pnum1', '$sl1', '$jg1', NULL, NULL, NULL,
		'1', NULL, NULL, NULL, NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL,
		NULL, NULL, NULL, '0', '0', NULL, NULL, NULL, NULL, NULL, NULL)";
		@mysql_query($sql, $conn);
		
		$je = $pnum1*$sl1*$jg1;
		$sql2 = "update order_mainqt set dje=dje+$je where ddh='$bh'";	// 更新订单金额，构件时，只需要确定文件名的序号即可，如2345-1.pdf、2345-2.pdf
		@mysql_query($sql2, $conn);
		header("Location:http://oa.skyprint.cn/customer/neworder/upload_caipu.php?ddh=$bh")

?>