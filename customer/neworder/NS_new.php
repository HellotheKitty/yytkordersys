<?
require("../../inc/conn.php");
require("../inc/connykgf.php");

session_start();
$_SESSION["OK"]="OK";
mb_internal_encoding("UTF-8");
$dwdm = substr($_SESSION["GDWDM"],0,4);
$khmc = $_SESSION["KHMC"];


//保存
if ($_POST["button"]<>"") {
	$authRequestModify = $_POST["authcode"];
	$authToCheckModify = md5($_SESSION["KHID"]."-".$_SESSION["AUTHCODE"]."-".$_POST["ddh"]."-"."新建订单");
	if($authRequestModify != $authToCheckModify){
		echo "<script>alert('参数错误！订单未修改！');</script>";
		exit;
	}

	$bzyq=$_POST["bzyq"];
	$kpyq=$_POST["kpyq"];
	$memo=$_POST["memo"];
	$scjd=$_POST["scjd"];
	$khmc=$_SESSION["KHMC"];//$_POST["khmc"];
	$shr = $_POST["shr"];//!=""?$_POST["shr"]:$_SESSION["INFO"]["lxr"];
	$shdh = $_POST["shdh"];//!=""?$_POST["shdh"]:$_SESSION["INFO"]["lxdh"];
	$shdz = $_POST["shdz"];//!=""?$_POST["shdz"]:$_SESSION["INFO"]["lxdz"];
	$skfs=$_POST["butt2"];
	mysql_query("update order_mainqt set khmc='$khmc',psfs='".$_POST["psfs"]."',kdje='".$_POST["kdje"]."',shr='".$shr."',shdh='".$shdh."',shdz='".$shdz."',scjd='$scjd',bzyq='$bzyq',kpyq='$kpyq',memo='$memo',yqwctime='".$_POST["yqwctime"]."',djje='".$_POST["djje"]."',kpyq='".$_POST["butt2"]."' where ddh='".$_POST["ddh"]."'",$conn);

	/*保存定金信息
	mysql_query("delete from order_zh where ddh='".$_POST["ddh"]."'");
	if ($skfs=="预存扣款")
		mysql_query("insert into order_zh select 0,kpyq,now(),0,djje,'订单订金',memo,now(),khmc,ddh from order_mainqt where ddh='".$_POST["ddh"]."' and djje>0");
	else
		mysql_query("insert into order_zh select 0,kpyq,now(),djje,djje,'订单订金',memo,now(),khmc,ddh from order_mainqt where ddh='".$_POST["ddh"]."' and djje>0");
	 */


//    发货信息保存在fh_info表里面
    if( $_POST['fhr'] <>'' ||  $_POST['fhlxdh']<>'' ||  $_POST['fhdz'] <>''){

        mysql_query("delete from fh_info where ddh = '" . $_POST['ddh'] . "'" ,$conn);

        mysql_query("insert into fh_info (id,ddh,fhr,fhlxdh,fhdz) VALUES (0, '" . $_POST["ddh"] . "' , '" . $_POST['fhr'] . "','" . $_POST['fhlxdh'] . "' , '" . $_POST['fhdz'] . "' )" ,$conn);

    }
	header("location:NS_new.php?ddh=".$_POST["ddh"]."&auth=$authRequestModify");
	exit;
}
//删除订单
if ($_GET["deleid"]<>"") {
	$ddh=$_GET["ddh"];
	mysql_query("delete from order_mxqt_hd where mxid='".$_GET["deleid"]."'",$conn);
	mysql_query("delete from order_mxqt where id='".$_GET["deleid"]."'",$conn);
	$rs=mysql_query("select sum(jg1*pnum1*sl1+jg2*pnum2*sl2) from order_mxqt mx where mx.ddh='$ddh'",$conn);
	$rshd=mysql_query("select sum(jg*sl) from order_mxqt_hd where mxid in (select id from order_mxqt where ddh='$ddh')",$conn);
	mysql_query("update order_mainqt set dje=".(mysql_result($rs,0,0)+mysql_result($rshd,0,0))." where ddh='$ddh'",$conn);
	header("location:NS_new.php?ddh=".$ddh."&auth=".md5($_SESSION["KHID"]."-".$_SESSION["AUTHCODE"]."-".$ddh."-"."新建订单"));
	exit;
}


if ($_GET["BH6"]<>"") {
	mysql_query("update nc_erp.order_mxqt set file=replace(file,'".$_GET["BH6"].";','') where id=".$_GET["id"],$conn);
}

if ($_GET["ddh"]=="") {


	$rss=mysql_query("select khmc,lxr,lxdh,lxdz from base_kh where base_kh.khmc= '$khmc'",$conn);
	if (mysql_num_rows($rss)>0) {
		$khmc=mysql_result($rss,0,"khmc");
		$lxr=mysql_result($rss,0,"lxr");
		$lxdh=mysql_result($rss,0,"lxdh");
		$lxdz=mysql_result($rss,0,"lxdz");
	}


	if ($bh=="") {  //没有订单生成
		/*
            $bh=date("ymdhis",time()).rand(10,99)."5";
            $rs=mysql_query("select ddh from order_mainqt where ddh='".$bh."'",$conn);
            while (mysql_num_rows($rs)>0) {
                $bh=date("ymdhis",time()).rand(10,99)."5";
                $rs=mysql_query("select ddh from order_mainqt where ddh='".$bh."'",$conn);
            }
         */

		$_ddh = mysql_query("select ddh from order_mainqt where zzfy='$dwdm' order by id desc limit 1",$conn);
		$_arr = mysql_fetch_array($_ddh);
		$_last = $_arr[0];
		if($dwdm == '3301'){
			$_lastYM = substr($_last,0,6);
			$_nowYM = date("Ym",time());
			if($_lastYM == $_nowYM)
				$bh = $_last + 1;
			else
				$bh = $_nowYM."00001";
		}else{
			$_lastYM = substr($_last,0,4);//前四位
			$_nowYM = substr(date("Ym",time()),2,4);
			if($_lastYM == $_nowYM)
				$bh = $_last + 1;
			else
				$bh = $_nowYM.substr($dwdm,2,2)."00001";
		}

		if($_GET['fnames']<>''){

			$khmc = $_SESSION['KHMC'];

			$khinfo=mysql_query("select khmc,lxr,lxdh,lxdz,mpzh,qq,xsbh from base_kh where khmc = '$khmc'",$conn);
			$lxr = mysql_result($khinfo, 0, "lxr");
			$lxdh = mysql_result($khinfo, 0, "lxdh");
			$lxdz = mysql_result($khinfo, 0, "lxdz");
			$xsbh = mysql_result($khinfo,0,'xsbh');

		}
//备注 易卡吧还是易卡印
		if($_POST['mptype']<>''){
            $dt = date('m-d');

            if($_POST['mptype']=='yikaba'){

                $memo = $dt.'易卡吧';

            }else if($_POST['mptype']=='yikayin'){

                $memo = $dt.'易卡印';
            }
		}
		mysql_query("insert into order_mainqt (ddh,khmc,xsbh,ddate,state,sdate,shr,shdh,shdz,memo,scjd,xjdid,zzfy,filefy) values ('".$bh."','".$khmc."','".$xsbh."',now(),'新建订单',now(),'$lxr','$lxdh','$lxdz','$memo','".$_SESSION["GSSDQ"]."','','$dwdm','".$_SESSION["INFO"]["loginname"]."')",$conn);


		if($_POST['fnames']<>''){

//            if($_GET['mptype']=='yikaba'){
////                条码单面
//
//                $pnum1_bar = 1;
//                $dsm1_bar = '单面';
//            }else{
//                $pnum1_bar = 2;
//                $dsm1_bar = '双面';
//            }
			$pbhs = '';

			/*文件命名规则
             * $ddfile="http://erp.yikayin.com/nc_erp/Pok1/".mysql_result($rs,$i,"pbh")."-".str_replace("|","",mysql_result($rs,$i,"mbzz"))."-".mysql_result($rs,$i,"pdate")."-main.pdf";

            $dbfile="http://erp.yikayin.com/nc_erp/Pok1/".mysql_result($rs,$i,"pbh")."-".str_replace("|","",mysql_result($rs,$i,"mbzz"))."-".mysql_result($rs,$i,"pdate")."-barcode.pdf";
            */

//			$namestr = substr($bh,7,4);
//         filename:   3-183321D72-铜版纸双面覆哑膜夸客橙色模板-2016.07.18[102802]|YK3TB270
//         new filename:   3-183321D72-铜版纸双面覆哑膜夸客橙色模板-2016.07.18[102802]-H-D88-S21|YK3TB270
//         new filename:   3-183321D72-zzmc-2016.07.18[102802]-H-D88-S21|YK3TB270|铜版纸覆膜

			$i=1;
			$queryprice1w = "SELECT price from price_of_print WHERE khmc = '".$_SESSION["KHMC"]."' AND LOCATE('Hp10000',machine)>0 AND LOCATE('自带纸',materialname)>0 AND dsm = '双面'";
			if(mysql_num_rows(mysql_query($queryprice1w,$conn))>0){
				$price1w = mysql_result(mysql_query($queryprice1w,$conn),0,'price');
			}else{
				$price1w = 0.9;
			}

            $queryprice76 = "SELECT price from price_of_print WHERE khmc = '".$_SESSION["KHMC"]."' AND machine='Hp彩色' AND LOCATE('自带纸',materialname)>0 AND dsm = '双面'";
            if(mysql_num_rows(mysql_query($queryprice76,$conn))>0){
				$price76 = mysql_result(mysql_query($queryprice76,$conn),0,'price');
			}else{
				$price76 = 0.5;
			}


//            第一次 将铜版纸名片遍历出来
			foreach($_POST['fnames'] as $fname){

				$fnamearr = explode('|',$fname);

				$fname1 = $fnamearr[0];

//                机器需要的纸张编号
				$fname2 = $fnamearr[1];

				$arr = explode('-',$fname1);
				$pnum1 = 2;
				$sl1 = intval($arr[0]);

//				$arri = explode('I',$arr[1]);
//				$arrd = explode('D',$arr[1]);
//				判断empty($arri[1]) && empty($arrd[1])

				$strmachine = substr($arr[1],strlen($arr[1])-2);
//                    铜版纸双面覆哑膜夸客橙色模板
//				$sczzmc = $arr[2];
				$sczzmc =$fnamearr[2];
				if (strstr($sczzmc,'覆') and strstr($sczzmc,'膜') and $dwdm=='3405') {$fm=mb_substr($sczzmc,mb_strpos($sczzmc,'覆'));$fm=mb_substr($fm,0,mb_strpos($fm,'膜')+1);} else $fm='';

//				用纸张编号对应的纸张名做pname
//                $rszzm = mysql_query("select zzmc from base_zz_ck where scbh = '$fname2'",$connykgf);铜版纸覆亚膜
				$rszzm = mysql_query("select zzmc from base_zz_ck where instr(base_zz_zzmc,concat(\"'\",replace(replace(replace(replace(replace('$sczzmc','[2盒版]',''),'工牌-',''),'|夸客橙色模板',''),'|夸客蓝色模板',''),'60张',''),\"'\"))>0 ",$connykgf);
				if(mysql_num_rows($rszzm)>0 && mysql_result($rszzm,0,'zzmc')=='铜版纸'){

					$pname = mysql_result($rszzm,0,'zzmc');

//				I-hp7600 I72-hp10000  63自带纸(464*320) 64自带纸(750*530)

					if($strmachine !='72'&&$strmachine!='65'&&$strmachine!='60' &&$strmachine!='50'){
						$machine1 = 'Hp彩色';
						$paper1 = '63';
                        $jg1 = $price76;
                        $hzx1 = '纵向';
                    }else{
						$machine1 = 'Hp10000彩色';
						$paper1='64';
                        $jg1 = $price1w;
                        $hzx1 = '横向';
                    }

					$color1 = '彩色';
					$jldw1= 'P';
					$productname = '单张';
					$n1 = '单张';
					$dsm1 = '双面';

					$namestr = "http://erp.yikayin.com/nc_erp/Pok1/";
//                $file1 = $namestr.$arr[0].'-'.$arr[1].'-'.$arr[2].'-'.$arr[3].'-main.pdf';
					$file1 = $namestr.$fname1 . '-main.pdf';

//					-D88-S12 单双面p数
//                    if(!empty($arr[4])){
//
//                        $dsm_pnum =$arr[4];
//                        if(substr($dsm_pnum,0,1) == 'D'){
//                            $dsm1 = '单面';
//                        }elseif(substr($dsm_pnum,0,1) == 'S'){
//                            $dsm1 = '双面';
//                        }
//                        $pnum1 = substr($dsm_pnum,1);
//                    }
//					获取并删除数组最后一个
					if(stristr(end($arr),'D')||stristr(end($arr),'S')){

                        $dsm_pnum_1 = array_pop($arr);

                        if(substr($dsm_pnum_1,0,1)=='D'){

                            $dsm1 = '单面';
                        }elseif(substr($dsm_pnum_1,0,1)=='S'){

                            $dsm1='双面';
                        }
                        $pnum1 = substr($dsm_pnum_1,1);

                        if(stristr(end($arr),'D')||stristr(end($arr),'S')){
//                          有条码 改用前一个

                            $dsm_pnum_2 = array_pop($arr);
                            if(substr($dsm_pnum_2,0,1)=='D'){

                                $dsm1 = '单面';
                            }elseif(substr($dsm_pnum_2,0,1)=='S'){

                                $dsm1='双面';
                            }
                            $pnum1 = substr($dsm_pnum_2,1);

                        }

					}
					$pbh = $arr[0].'-'.$arr[1];

//                    横纵向
                    if(strtoupper(end($arr)) == 'H'){

                        $hzx1 = '横向';
                    }elseif(strtoupper(end($arr))=='Z'){
                        $hzx1 = '纵向';
                    }

					$insmx = "insert into order_mxqt (id,ddh,productname,pname,n1,file1,machine1,paper1,color1,jldw1,dsm1,hzx1,pnum1,sl1,jg1,paper2,sl2,pnum2,jg2,sczzbh1,pbh1) values (0,'$bh','$productname','$pname','$n1','$file1','$machine1','$paper1','','$jldw1','$dsm1','$hzx1','$pnum1','$sl1','$jg1','1',0,0,0,'$fname2','$pbh')";
					mysql_query($insmx,$conn);
					$mxidnew=mysql_insert_id();
					if ($fm<>'') {
						//北京按0.5/P价格计算
						$insmx = "insert into order_mxqt_fm (id,mxid,fmfs,cpcc,jdlw,sl,jg,memo,ddh) values (0,'$mxidnew','$fm','750*530','P','".($pnum1*$sl1)."','0.5','易卡名片','$bh')";
						mysql_query($insmx,$conn);
					}

//					$pbhs .= $arr[0].'-'.$arr[1].';';
					$i++;

				}
//                $rszzm = mysql_query("select zzmc from base_zz_ck where locate('$sczzmc',base_zz_zzmc)>0 ",$connykgf);
//                if(mysql_num_rows($rszzm)>0){
//                    $pname = mysql_result($rszzm,0,'zzmc');
//                }else{
//                    $pname = $arr[2];
//                }

			}

//            $selje = mysql_query("select ifnull(sum(jg1*pnum1*sl1+jg2*pnum2*sl2),0) as mxje from order_mxqt mx where mx.ddh='$bh'",$conn);
//            $je = mysql_result($selje,0,'mxje');
//            $updje = mysql_query("update order_mainqt set dje = '$je' WHERE ddh = $bh",$conn);

//            第二次 将条码页遍历出来
			foreach($_POST['fnames'] as $fname){

//				if(count($_POST['fnames'])>1){
//					$file1 = $namestr.'-'.$i.'.pdf';
//				}else{
//					$file1 = $namestr.'.pdf';
//				}
//				$file1 = $namestr.'-'.$i.'.pdf';
				$fnamearr = explode('|',$fname);

				$fname1 = $fnamearr[0];

//                机器需要的纸张编号
//                $fname2 = $fnamearr[1];
//                条码用铜版纸
				$fname2 = 'YK3TB270';

				$arr = explode('-',$fname1);
				$pnum1 = 2;
				$sl1 = intval($arr[0]);

//				$arri = explode('I',$arr[1]);
//                $arrd = explode('D',$arr[1]);
				$strmachine = substr($arr[1],strlen($arr[1])-2);

//				I-hp7600 I72-hp10000  63自带纸(464*320) 64自带纸(750*530)

				if($strmachine!='72'&&$strmachine!='65'&&$strmachine!='60' &&$strmachine!='50'){
					$machine1 = 'Hp彩色';
					$paper1 = '63';
                    $jg1=$price76;
                    $hzx1 = '纵向';

                }else{
					$machine1 = 'Hp10000彩色';
					$paper1='64';
                    $jg1 = $price1w;
                    $hzx1 = '横向';
                }

//				$sczzmc = $arr[2];
                $sczzmc =$fnamearr[2];
				if (strstr($sczzmc,'覆') and strstr($sczzmc,'膜') and $dwdm=='3405') {$fm=mb_substr($sczzmc,mb_strpos($sczzmc,'覆'));$fm=mb_substr($fm,0,mb_strpos($fm,'膜')+1);} else $fm='';
//				用纸张编号对应的纸张名做pname
//                $rszzm = mysql_query("select zzmc from base_zz_ck where locate('$sczzmc',base_zz_zzmc)>0 ",$connykgf);
				$rszzm = mysql_query("select zzmc from base_zz_ck where instr(base_zz_zzmc,concat(\"'\",replace(replace(replace(replace(replace('$sczzmc','[2盒版]',''),'工牌-',''),'|夸客橙色模板',''),'|夸客蓝色模板',''),'60张',''),\"'\"))>0 ",$connykgf);

				if(mysql_num_rows($rszzm)>0){
					$pname = mysql_result($rszzm,0,'zzmc');
				}else{
					$pname = $arr[2];
				}
//                bar name 铜版纸
				$pname = "铜版纸";
				$color1 = '彩色';
				$jldw1= 'P';
				$productname = '单张';
				$n1 = '单张';
				$dsm1 = '双面';

				$namestr = "http://erp.yikayin.com/nc_erp/Pok1/";

//				份数大于5就有分拣条码,分拣条码多打印一条明细

				if($sl1>5 || ($sl1==5 && stristr($arr[1] , 'aliyun'))){

//					$file1_bar = $namestr.$arr[0].'-'.$arr[1].'-'.$arr[2].'-'.$arr[3].'-barcode.pdf';
					$file1_bar = $namestr.$fname1.'-barcode.pdf';
					$sl1_bar = 1;
					$pnum1_bar = 2;
					$dsm1_bar = '双面';

//                    if(!empty( $arr[5])){
//
//                        $dsm_pnum_bar = $arr[5];
//                        if(substr($dsm_pnum_bar,0,1) == 'D'){
//                            $dsm1_bar = '单面';
//                        }elseif(substr($dsm_pnum_bar,0,1) == 'S'){
//                            $dsm1_bar = '双面';
//                        }
//                        $pnum1_bar = substr($dsm_pnum_bar,1);
//                    }

                    if(stristr(end($arr),'D')||stristr(end($arr),'S')){

                        //					获取并删除数组最后一个
                        $dsm_pnum_1 = array_pop($arr);


                        if(stristr(end($arr),'D')||stristr(end($arr),'S')){
//                          有条码 用最后一个

                            if(substr($dsm_pnum_1,0,1)=='D'){

                                $dsm1_bar = '单面';
                            }elseif(substr($dsm_pnum_1,0,1)=='S'){

                                $dsm1_bar='双面';
                            }
                            $pnum1_bar = substr($dsm_pnum_1,1);

//                            删掉单双面标识 方便取横纵向
                            array_pop($arr);
                        }

                    }
					$pbh = $arr[0].'-'.$arr[1];

                    //                    横纵向
                    if(strtoupper(end($arr)) == 'H'){

                        $hzx1 = '横向';
                    }elseif(strtoupper(end($arr))=='Z'){
                        $hzx1 = '纵向';
                    }

					$insmxbar = "insert into order_mxqt (id,ddh,productname,pname,n1,file1,machine1,paper1,jldw1,dsm1,hzx1,pnum1,sl1,jg1,paper2,sl2,pnum2,jg2,sczzbh1,pbh1) values (0,'$bh','$productname','$pname','$n1','$file1_bar','$machine1','$paper1','$jldw1','$dsm1_bar','$hzx1','$pnum1_bar','$sl1_bar','$jg1','1',0,0,0,'$fname2','$pbh')";
					mysql_query($insmxbar,$conn);
					$mxidnew=mysql_insert_id();
					if ($fm<>'') {
						//北京按0.5/P价格计算
						$insmx = "insert into order_mxqt_fm (id,mxid,fmfs,cpcc,jdlw,sl,jg,memo,ddh) values (0,'$mxidnew','$fm','750*530','P','".($pnum1*$sl1)."','0.5','易卡名片','$bh')";
						mysql_query($insmx,$conn);
					}
//					$file = 'logfile.txt';
//					$f= file_put_contents($file,$insmxbar,FILE_APPEND);

				}

				$i++;
//				$pbhs .= $arr[0].'-'.$arr[1].';';
			}
//            $selje = mysql_query("select ifnull(sum(jg1*pnum1*sl1+jg2*pnum2*sl2),0) as mxje from order_mxqt mx where mx.ddh='$bh'",$conn);
//            $je = mysql_result($selje,0,'mxje');
//            $updje = mysql_query("update order_mainqt set dje = '$je' WHERE ddh = $bh",$conn);

//            第三次 将剩下的名片遍历出来
			foreach($_POST['fnames'] as $fname){

				$fnamearr = explode('|',$fname);

				$fname1 = $fnamearr[0];

//                机器需要的纸张编号
				$fname2 = $fnamearr[1];

				$arr = explode('-',$fname1);
				$pnum1 = 2;
				$sl1 = intval($arr[0]);

//				$arri = explode('I',$arr[1]);

				$strmachine = substr($arr[1],strlen($arr[1])-2);
//				I-hp7600 I72-hp10000  63自带纸(464*320) 64自带纸(750*530)

				if($strmachine<>'72'&&$strmachine!='65'&&$strmachine!='60' &&$strmachine!='50'){
					$machine1 = 'Hp彩色';
					$paper1 = '63';
                    $jg1=$price76;
                    $hzx1 = '纵向';

                }else{
					$machine1 = 'Hp10000彩色';
					$paper1='64';
                    $jg1 = $price1w;
                    $hzx1 = '横向';
                }

//				$sczzmc = $arr[2];
                $sczzmc =$fnamearr[2];
				if (strstr($sczzmc,'覆') and strstr($sczzmc,'膜') and $dwdm=='3405') {$fm=mb_substr($sczzmc,mb_strpos($sczzmc,'覆'));$fm=mb_substr($fm,0,mb_strpos($fm,'膜')+1);} else $fm='';
//				用纸张编号对应的纸张名做pname
//                $rszzm = mysql_query("select zzmc from base_zz_ck where locate('$sczzmc',base_zz_zzmc)>0 ",$connykgf);
				$rszzm = mysql_query("select zzmc from base_zz_ck where instr(base_zz_zzmc,concat(\"'\",replace(replace(replace(replace(replace('$sczzmc','[2盒版]',''),'工牌-',''),'|夸客橙色模板',''),'|夸客蓝色模板',''),'60张',''),\"'\"))>0 ",$connykgf);

				if(mysql_num_rows($rszzm)>0){
					if(mysql_result($rszzm,0,'zzmc')=='铜版纸'){
						continue;
					}
					$pname = mysql_result($rszzm,0,'zzmc');
				}else{
					$pname = $arr[2];
				}
				$color1 = '彩色';
				$jldw1= 'P';
				$productname = '单张';
				$n1 = '单张';
				$dsm1 = '双面';

				$namestr = "http://erp.yikayin.com/nc_erp/Pok1/";
//                $file1 = $namestr.$arr[0].'-'.$arr[1].'-'.$arr[2].'-'.$arr[3].'-main.pdf';
				$file1 = $namestr.$fname1 . '-main.pdf';

                if(stristr(end($arr),'D')||stristr(end($arr),'S')){

                    $dsm_pnum_1 = array_pop($arr);

                    if(substr($dsm_pnum_1,0,1)=='D'){

                        $dsm1 = '单面';
                    }elseif(substr($dsm_pnum_1,0,1)=='S'){

                        $dsm1='双面';
                    }
                    $pnum1 = substr($dsm_pnum_1,1);

                    if(stristr(end($arr),'D')||stristr(end($arr),'S')){
//                          有条码 改用前一个

                        $dsm_pnum_2 = array_pop($arr);
                        if(substr($dsm_pnum_2,0,1)=='D'){

                            $dsm1 = '单面';
                        }elseif(substr($dsm_pnum_2,0,1)=='S'){

                            $dsm1='双面';
                        }
                        $pnum1 = substr($dsm_pnum_2,1);

                    }

                }
				$pbh = $arr[0].'-'.$arr[1];

                //                    横纵向
                if(strtoupper(end($arr)) == 'H'){

                    $hzx1 = '横向';
                }elseif(strtoupper(end($arr))=='Z'){
                    $hzx1 = '纵向';
                }
				$insmx = "insert into order_mxqt (id,ddh,productname,pname,n1,file1,machine1,paper1,jldw1,dsm1,hzx1,pnum1,sl1,jg1,paper2,sl2,pnum2,jg2,sczzbh1,pbh1) values (0,'$bh','$productname','$pname','$n1','$file1','$machine1','$paper1','$jldw1','$dsm1','$hzx1','$pnum1','$sl1','$jg1','1',0,0,0,'$fname2','$pbh')";
				mysql_query($insmx,$conn);
				$mxidnew=mysql_insert_id();
					if ($fm<>'') {
						//北京按0.5/P价格计算
						$insmx = "insert into order_mxqt_fm (id,mxid,fmfs,cpcc,jdlw,sl,jg,memo,ddh) values (0,'$mxidnew','$fm','750*530','P','".($pnum1*$sl1)."','0.5','易卡名片','$bh')";
						mysql_query($insmx,$conn);
					}

				$i++;
//				$pbhs .= $arr[0].'-'.$arr['1'].';';

			}

            $selje = mysql_query("select ifnull(sum(jg1*pnum1*sl1+jg2*pnum2*sl2),0) as mxje from order_mxqt mx where mx.ddh='$bh'",$conn);

            $je = mysql_result($selje,0,'mxje');
            $updje = mysql_query("update order_mainqt set dje = '$je' WHERE ddh = $bh",$conn);

			echo "<script>window.location.href='?ddh=$bh&auth=$authToCheckModify';</script>";

		}

	}
} else {
	$bh=$_GET["ddh"];
	$authRequest = $_GET["auth"];
	$authToCheck = md5($_SESSION["KHID"]."-".$_SESSION["AUTHCODE"]."-".$bh."-"."新建订单");

}


$rs=mysql_query("select order_mainqt.*,order_zh.sksj,base_kh.lxr,lxdh from order_mainqt left join order_zh on order_zh.ddh=order_mainqt.ddh and order_zh.zy<>'订单订金' left join base_kh on order_mainqt.khmc=base_kh.khmc where  order_mainqt.ddh='".$bh."'",$conn);
$xjzje=mysql_result($rs,0,"dje");$state=mysql_result($rs,0,"state");

$rsmx = mysql_query("select order_mxqt.*,m1.materialname mm1,m1.specs ms1,m2.materialname mm2,m2.specs ms2,group_concat(hd.jgfs) hd,sum(hd.jg*hd.sl) hdje,group_concat(hd.jg) hdjg, group_concat(fm.fmfs) fmfs,sum(fm.jg*fm.sl) fmje,group_concat(fm.jg) fmjg from order_mxqt left join order_mxqt_hd hd on order_mxqt.id=hd.mxid left join order_mxqt_fm fm on order_mxqt.id=fm.mxid LEFT JOIN material m1 on  m1.id=paper1 LEFT JOIN material m2 on m2.id=paper2 where order_mxqt.ddh='" . $bh . "'  group by order_mxqt.id", $conn);

?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>订单信息</title>
	<SCRIPT language=JavaScript src="../form.js"></SCRIPT>
	<script language="JavaScript">
		<!--
		function suredo(src,q)
		{
			var ret;
			ret = confirm(q);
			if(ret!=false) {
				window.location=src;
			}
		}

		//-->
		window.opener.location.reload();
		lastv = "上门自取";
		function change(v){
			if(v!="上门自取" && lastv == "上门自取"){
				alert("如选物流配送，请务必填写收货信息");
			}
			lastv = v;
		}
	</script>
	<style type="text/css">
		<!--
		body {
			background-color: #A5CBF7;
		}
		.style11 {font-size: 14px}
		.STYLE13 {font-size: 12px}
		-->
	</style>
</head>

<body>
<form name=form1 method="post" action="#">
	<input type="hidden" name="ddh" value="<? echo $bh?>">
	<input type="hidden" name="authcode" value="<? echo md5($_SESSION["KHID"]."-".$_SESSION["AUTHCODE"]."-".$bh."-".$state)?>" />
	<table width="100%" border="1" cellpadding="4" cellspacing="1" bordercolor="#000099" bgcolor="f6f6f6" >
		<tr>
			<td height="222" valign="top">
				<table width="80%"  border="1" align="center" cellspacing="0" cellpadding="0" style="border-width:0px;border-collapse:collapse;border:1px solid #D3D3D3; border-bottom:1px solid #D3D3D3; font-size:12px">
					<tr>
						<td height="28" class="STYLE11" width="10%" align="center">订单编号</td>
						<td width="10%" class="STYLE13"><? echo mysql_result($rs,0,"ddh");?></td>
						<td width="30%" align="left" class="STYLE11">下单时间：<span class="STYLE13"><? echo mysql_result($rs,0,"ddate");?></span></td>
						<td width="30%" align="left" class="STYLE11">要求完成：<span class="STYLE13">
                <input name="yqwctime" type="text" value="<? echo mysql_result($rs,0,"yqwctime")==""?date("Y-m-d H:i:s",strtotime("+1 day",strtotime(mysql_result($rs,0,"ddate")))):mysql_result($rs,0,"yqwctime")?>" size="15">
              </span></td>
					</tr>
					<tr>
						<td  height="24" class="STYLE11" align="center">客户名称</td>
						<td colspan="2" class="STYLE13"><font size="3"><?echo $_SESSION["KHMC"]?></font><br><?echo $_SESSION["INFO"]["lxr"]."/".$_SESSION["INFO"]["lxdh"]?></td>
						<td class="STYLE13"><font color="red">账户余额：</font><?
							$khmc = mysql_result($rs,0,"khmc");

                            $sql_czxf = "SELECT ifnull(sum((ifnull(`order_zh`.`jf`, 0) - ifnull(`order_zh`.`df`, 0))),0) AS `czxf` FROM order_zh WHERE fssj > IFNULL((SELECT sdate FROM kh_ye WHERE depart = '$khmc' LIMIT 1),'2015-01-01') AND khmc = '$khmc' GROUP BY khmc ";

                            $sql_ye = "select ye from kh_ye where depart = '$khmc'";

                            $resye1 = mysql_query($sql_czxf,$conn);
                            $resye2 = mysql_query($sql_ye,$conn);
                            if(mysql_num_rows($resye1) >0){

                                $res_czxf = mysql_result($resye1 ,0,'czxf');

                            }else{
                                $res_czxf = 0;
                            }

                            if(mysql_num_rows($resye2)>0){
                                $res_ye = mysql_result($resye2 ,0,'ye');

                            }else{
                                $res_ye=0;
                            }

                            $yue = round(floatval($res_czxf) + floatval($res_ye) , 2);
                            echo $yue . '元';
							?></td>
					</tr>
					
					<tr>
						<td height="24" class="STYLE11" align="center">配送信息</td>
						<td colspan="3" class="STYLE13">
                            <span style="display: inline-block;width: 150px; float: left;margin-top: 5px;margin-left: 5px;">

                                配送：
                                <select name="psfs" id="psfs" onChange="change(this.value)" >
                                    <option value="上门自取" <? if (mysql_result($rs,0,"psfs")=="上门自取") echo "selected";?>>上门自取</option>
                                    <option value="快递配送" <? if (mysql_result($rs,0,"psfs")=="快递配送") echo "selected";?>>快递配送</option>
                                    <option value="物流配送" <? if (mysql_result($rs,0,"psfs")=="物流配送") echo "selected";?>>物流配送</option>
                                    <option value="送货" <? if (mysql_result($rs,0,"psfs")=="送货") echo "selected";?>>送货</option>
                                    <option value="其他" <? if (mysql_result($rs,0,"psfs")=="其他") echo "selected";?>>其他</option>
                                </select>
                            </span>

                            <span style="display: inline-block; float: left;margin-top: 5px;margin-left: 5px;">
                                收货人：<input name="shr" type="text" value="<? echo mysql_result($rs,0,"shr")==""?$lxr:mysql_result($rs,0,"shr")?>" size="6">&nbsp;&nbsp;
                                电话：<input name="shdh" type="text" value="<? echo mysql_result($rs,0,"shdh")==""?$lxdh:mysql_result($rs,0,"shdh")?>" size="12">
                                地址：<input name="shdz" type="text" value="<? echo mysql_result($rs,0,"shdz")==""?$lxdz:mysql_result($rs,0,"shdz")?>" size="57">

					        </span>
                            <span id="fh_info" style=" float: left;margin-top: 5px;margin-left: 5px;">
                                <? $res_fhinfo = mysql_query("select * from fh_info where ddh = " . $bh , $conn);
                                if(mysql_num_rows($res_fhinfo) >0){

                                    $fhr = mysql_result($res_fhinfo ,0,'fhr');
                                    $fhlxdh = mysql_result($res_fhinfo,0,'fhlxdh');
                                    $fhdz = mysql_result($res_fhinfo,0,'fhdz');
                                }

                                ?>
                                发货人：<input name="fhr" type="text" value="<? echo $fhr; ?>" size="6">&nbsp;&nbsp;
                                电话：<input name="fhlxdh" type="text" value="<? echo $fhlxdh; ?>" size="12">
                                发货地址：<input name="fhdz" type="text" value="<? echo $fhdz; ?>" size="35">
                            </span>
                        </td>
                    </tr>
					
					<tr>
						<td height="24" class="STYLE11" align="center">订单备注</td>
						<td colspan="3" class="STYLE13"><textarea name="memo" cols="50" rows="3" style="width:100%;height:50px"><? echo mysql_result($rs,0,"memo");?></textarea></td>
					</tr>
					<tr>
						<td height="24" class="STYLE11" align="center">生产地</td>
						<td colspan="3" class="STYLE13"><!--<input name="scjd" id="scjd" type="text" value="<? echo mysql_result($rs,0,"scjd");?>" size="12" maxlength="4">-->
							<select name="scjd">
                            <option value="上海">上海</option>
                            <option value="北京" <? if($_SESSION['INFO']['loginname'] == 'bjykgf') echo 'selected';?>>北京</option>
                            </select>
							<input type="submit" name="button" id="button" value="保存订单信息"></td>
					</tr>
					<tr>
						<td height="34" colspan="3" align="left" valign="bottom"><font size="+0.5">
								<? if (mysql_result($rs,0,"state")=="新建订单") {
									echo "<a href='#' class='nav' onClick='javascript:window.open(\"YSXMqt_mxdjs.php?ddh=".mysql_result($rs,0,"ddh")."\", \"HT_dhdj\", \"toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=yes,width=850,height=610,left=300,top=100\")'>【增加明细】</a>&nbsp;&nbsp;";

									echo "<a href='#' onClick=\"javascript:suredo('YSXMqt_del.php?lx=new&BH=".mysql_result($rs,0,"ddh")."&auth=".md5($_SESSION["KHID"]."-".$_SESSION["AUTHCODE"]."-".mysql_result($rs,0,"ddh")."-".mysql_result($rs,0,"state"))."','确定删除本订单?');\">【删除订单】</a>&nbsp;&nbsp;";

									if (mysql_num_rows($rsmx)>0)
										echo "<a href='#' onClick=\"javascript:suredo('YSXMqt_del.php?lx=new&BH2=".mysql_result($rs,0,"ddh")."&auth=".md5($_SESSION["KHID"]."-".$_SESSION["AUTHCODE"]."-".mysql_result($rs,0,"ddh")."-".mysql_result($rs,0,"state"))."','确定提交生产?');\">【提交生产】</a>&nbsp;&nbsp;";
								}
								echo " </font>";
								if (mysql_result($rs,0,"state")=='待结算') {
									if (mysql_result($rs,0,"sksj")<>'') echo " [已收款：",mysql_result($rs,0,"sksj"),"]"; else {?>
										　　收款金额：
										<input name="je" type="text" value="<? echo mysql_result($rs,0,"dje")+mysql_result($rs,0,"kdje")-mysql_result($rs,0,"djje")?>" size="5">元
										<select name="butt">
											<option value="现金">现金</option>
											<option value="支票">支票</option>
											<option value="POS刷卡">POS刷卡</option>
											<option value="汇款">汇款</option>
											<? if ($yue>0) {?><option value="预存扣款">预存扣款</option><? }?>
										</select>
										<input type="submit" name="button2" id="button2" value="收款" ><br>备注：<input type="text" name="skbz" id="skbz" value=""/>
										<?
									}
								}?>
						</td>
                        <td height="24"><span style="float:left;color:red"><? echo mysql_result($rs, 0, "state") ?></span><span style="float:right">明细合计金额：<span id="zje" style="color:#F00"></span>元</span></td>

                    </tr>
				</table>

			</td>
		</tr>
		<tr>
			<td>
				<table cellspacing="0" cellpadding="0" rules="all" border="1" id="gvOrder" style="border-width:0px;width:100%;border-collapse:collapse;border:1px solid #D3D3D3; border-bottom:1px solid #D3D3D3; font-size:12px">
					<tbody><tr class="td_title" style="height:30px;">

						<th width="132"  align="center" scope="col">产品</th>
						<th width="22"  align="center" scope="col">规格</th>
						<th width="35"  align="center" scope="col">数量</th>
						<th width="64"  align="center" scope="col">构件1</th>
						<th width="64" align="center" scope="col">生产文件</th>
						<th width="93"  align="center" scope="col">信息</th>
						<th width="64"  align="center" scope="col">构件2</th>
						<th width="64"  align="center" scope="col">生产文件</th>
						<th width="93"  align="center" scope="col">信息</th>
						<!--						<th width="45"   align="center" scope="col">金额</th>-->
						<th width="93" align="center" scope="col">后加工方式</th>
						<th width="93" align="center" scope="col">单价</th>
						<!--						<th width="45" align="center" scope="col">加工金额</th>-->
						<th width="93" align="center" scope="col">覆膜方式</th>
						<th width="93" align="center" scope="col">单价</th>
						<!--						<th width="45" align="center" scope="col">加工金额</th>-->
					</tr>
					<?
					for($i=0;$i<mysql_num_rows($rsmx);$i++){  ?>
						<tr class="td_title" style="height:30px;">

							<td class="td_content" align="center" ><? echo mysql_result($rsmx,$i,"productname");if (($state=="新建订单") or ($_SESSION["FBCW"]=="1" and date('m',strtotime(mysql_result($rs,0,"ddate")))==date('m'))) echo "<a href='#' class='nav' onClick='javascript:window.open(\"YSXMqt_mxdjs.php?ddh=".mysql_result($rs,0,"ddh")."&mxsid=".mysql_result($rsmx,$i,"id")."\", \"HT_dhdj\", \"toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=yes,width=850,height=610,left=300,top=100\")'> [修改]</a>"; else echo "<a href='#' class='nav' onClick='javascript:window.open(\"YSXMqt_mxdjs.php?lx=show&ddh=".mysql_result($rs,0,"ddh")."&mxsid=".mysql_result($rsmx,$i,"id")."\", \"HT_dhdj\", \"toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=yes,width=850,height=610,left=300,top=100\")'> [查看]</a>";
								if ($state=="新建订单") {?>
									<a href='?deleid=<? echo mysql_result($rsmx,$i,"id");?>&ddh=<? echo mysql_result($rs,0,"ddh");?>'>[删除]</a>
								<? }
								?>
							</td>
							<td class="td_content" align="center" ><? echo mysql_result($rsmx,$i,"chicun");?></td>
							<td align="center" class="td_content" ><? echo mysql_result($rsmx,$i,"sl");?></td>
							<td class="td_content" align="center" ><? echo mysql_result($rsmx,$i,"n1");?></td>
							<td class="td_content" align="center"><?
								$aaa=array_unique(explode(";",mysql_result($rsmx,$i,"file1")));
								foreach ($aaa as $key=>$a1){

									if(stristr($a1,'http')){
										if(stristr($a1,'yikayin')){

											$a1arr = explode('-',$a1);
											$a1= '拼版号:'. $a1arr[1];
											echo $a1;
										}else{

											$nameofa1 = explode('/',$a1);
											$nameofa1 = end($nameofa1);
											echo "<a href='$a1'>{$nameofa1}</a>";
										}

									}else{

										if ($a1<>"") echo "<a href='{$localftp}/scfiles/{$a1}?".rand(10,1000)."' target='_blank'>{$a1}</a>  ","<br>";

									}

								}
								if(!empty(mysql_result($rsmx,$i,"jdf1"))){
									echo '<br>';
									echo 'jdf文件:'.mysql_result($rsmx,$i,"jdf1");
								}

								if(!empty(mysql_result($rsmx,$i,"sczzbh1"))){
									echo '<br>';
									echo '纸张编号:'.mysql_result($rsmx,$i,"sczzbh1");
								}
								?>
							</td>
							<td class="td_content" align="center"><? echo mysql_result($rsmx,$i,"machine1"),"/<font color=red>",mysql_result($rsmx,$i,"mm1"),"[",mysql_result($rsmx,$i,"ms1"),"]</font>/",mysql_result($rsmx,$i,"jldw1"),"/",mysql_result($rsmx,$i,"dsm1"),"/",mysql_result($rsmx,$i,"hzx1"),"/P:",mysql_result($rsmx,$i,"pnum1"),"/SL:",mysql_result($rsmx,$i,"sl1");?></td>
							<td class="td_content" align="center" ><? echo mysql_result($rsmx,$i,"n2");?></td>
							<td align="center" class="td_content"><?
								$aaa=array_unique(explode(";",mysql_result($rsmx,$i,"file2")));
								foreach ($aaa as $key=>$a1){

									if(stristr($a1,'http')){
										$a1arr = explode('-',$a1);
										$a1= '拼版号:'.$a1arr[1];
									}
									if ($a1<>"") echo "<a href='{$localftp}/scfiles/{$a1}?".rand(10,1000)."' target='_blank'>{$a1}</a>  ","<br>";

								}

								if(!empty(mysql_result($rsmx,$i,"jdf2"))){
									echo '<br>';
									echo 'jdf文件:'.mysql_result($rsmx,$i,"jdf2");
								}

								if(!empty(mysql_result($rsmx,$i,"sczzbh2"))){
									echo '<br>';
									echo '纸张编号:'.mysql_result($rsmx,$i,"sczzbh2");
								}
								?>
							</td>
							<td align="center" class="td_content"><? if (mysql_result($rsmx,$i,"n2")<>"") echo mysql_result($rsmx,$i,"machine2"),"/",mysql_result($rsmx,$i,"mm2"),"[",mysql_result($rsmx,$i,"ms2"),"]/",mysql_result($rsmx,$i,"jldw2"),"/",mysql_result($rsmx,$i,"dsm2"),"/",mysql_result($rsmx,$i,"hzx2"),"/P:",mysql_result($rsmx,$i,"pnum2"),"/SL:",mysql_result($rsmx,$i,"sl2");?></td>
							<!--							<td align="center" class="td_content" >--><?// echo mysql_result($rsmx,$i,"sl1")*mysql_result($rsmx,$i,"pnum1")*mysql_result($rsmx,$i,"jg1")+mysql_result($rsmx,$i,"sl2")*mysql_result($rsmx,$i,"pnum2")*mysql_result($rsmx,$i,"jg2");  ?>
							<!--							</td>-->

							<?
							$mxid = mysql_result($rsmx, $i, 'id');

							$sql = "select group_concat(jgfs) as hd , group_concat(jg) as hdjg ,sum(jg*sl) as hdje from order_mxqt_hd where mxid = $mxid group by mxid";

							$rshd = mysql_query($sql, $conn);

							if (mysql_num_rows($rshd) > 0) {
								?>
								<td align="center" class="td_content"><? echo mysql_result($rshd, 0, "hd"); ?></td>
								<td class="td_content" align="center"><? echo mysql_result($rshd, 0, "hdjg"); ?> </td>
								<!--								<td align="center" class="td_content">--><?// echo mysql_result($rshd, 0, "hdje"); ?><!--</td>-->
							<? } else {
								?>
								<td></td>
								<td></td>
								<!--								<td></td>-->
								<?
							} ?>


							<?
							$sql2 = "select group_concat(fmfs) as fmfs , group_concat(jg) as fmjg ,sum(jg*sl) as fmje from order_mxqt_fm where mxid = $mxid group by mxid";

							$rsfm = mysql_query($sql2, $conn);

							if (mysql_num_rows($rsfm) > 0) {
								?>
								<td align="center" class="td_content"><? echo mysql_result($rsfm, 0, "fmfs"); ?></td>
								<td class="td_content" align="center"><? echo mysql_result($rsfm, 0, "fmjg"); ?> </td>
<!--								<td align="center" class="td_content">--><?// echo mysql_result($rsfm, 0, "fmje"); ?>
								</td>
							<? } else {
								?>
								<td></td>
								<td></td>
<!--								<td></td>-->
								<?
							} ?>
						</tr>
						<?
                        $zje = $zje + mysql_result($rsmx, $i, "sl1") * mysql_result($rsmx, $i, "pnum1") * mysql_result($rsmx, $i, "jg1") + mysql_result($rsmx, $i, "sl2") * mysql_result($rsmx, $i, "pnum2") * mysql_result($rsmx, $i, "jg2") + (mysql_num_rows($rshd) > 0 ? mysql_result($rshd, 0, "hdje") : 0) + (mysql_num_rows($rsfm) > 0 ? mysql_result($rsfm, 0, "fmje") : 0);

					}?>
					</tbody></table></td>
		</tr>
	</table>
	<br>
	<? if ((mysql_result($rs,0,"state")=="进入生产") and $_SESSION["FBSD"]=="1") {?><div align="center"><input name="b1" value="打印生产单" type="button" onClick="window.open('YSXMqt_show_p.php?ddh=<? echo $bh;?>')"></div><? }?>
	<? if (mysql_result($rs,0,"state")=="待配送") {?><div align="center"><input type="button" onClick="window.open('YSXMqt_sh_p.php?ddh=<? echo $bh;?>','new');" value="生成配送单" /></div><? }?>
</form>
</body>
</html>
<script language="javascript">
	document.getElementById("zje").innerHTML='<? echo $zje;?>';
	window.opener.location.reload();
</script>
<?
$rs=null;
unset($rs);
$rss=null;
unset($rss);
?>
