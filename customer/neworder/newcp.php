<?
require("../../inc/conn.php");
session_start();
$_SESSION["OK"]="OK";

$dwdm = substr($_SESSION["GDWDM"],0,4);
$khmc = $_SESSION["KHMC"];
/*结算
if ($_POST["button2"]<>'') {
	$je=$_POST["je"];
	$skfs=$_POST["butt"];
    $skbz=$_POST["skbz"];
	mysql_query("update order_mainqt set state='待配送',skbz='$skbz',sdate=now() where ddh='".$_POST["ddh"]."'");
	if ($_POST["butt"]=="预存扣款")
		mysql_query("insert into order_zh select 0,'$skfs',now(),0,$je,'订单结算',memo,now(),khmc,ddh from order_mainqt where ddh='".$_POST["ddh"]."'");
	else
		mysql_query("insert into order_zh select 0,'$skfs',now(),$je,$je,'订单结算',memo,now(),khmc,ddh from order_mainqt where ddh='".$_POST["ddh"]."'");
	header("location:NS_new.php?ddh=".$_POST["ddh"]);
	exit;
}
 */
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
	mysql_query("update order_mainqt set khmc='$khmc',psfs='".$_POST["psfs"]."',dje='".$_POST["dje"]."',kdje='".$_POST["kdje"]."',shr='".$shr."',shdh='".$shdh."',shdz='".$shdz."',scjd='$scjd',bzyq='$bzyq',kpyq='$kpyq',memo='$memo',yqwctime='".$_POST["yqwctime"]."',djje='".$_POST["djje"]."',kpyq='".$_POST["butt2"]."' where ddh='".$_POST["ddh"]."'",$conn);

	/*保存定金信息
	mysql_query("delete from order_zh where ddh='".$_POST["ddh"]."'");
	if ($skfs=="预存扣款")
		mysql_query("insert into order_zh select 0,kpyq,now(),0,djje,'订单订金',memo,now(),khmc,ddh from order_mainqt where ddh='".$_POST["ddh"]."' and djje>0");
	else
		mysql_query("insert into order_zh select 0,kpyq,now(),djje,djje,'订单订金',memo,now(),khmc,ddh from order_mainqt where ddh='".$_POST["ddh"]."' and djje>0");
	 */
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
	if ($_GET["taskid"]<>"") {
		$rss=mysql_query("select ddh from order_mainqt where xjdid=".$_GET["taskid"],$conn);
		if (mysql_num_rows($rss)>0) $bh=mysql_result($rss,0,0);
		else {
			$rss=mysql_query("select khmc,lxr,lxdh,lxdz,task_list.taskdescribe from base_kh,task_list where task_list.fromuser=base_kh.mpzh and task_list.id=".$_GET["taskid"],$conn);
			if (mysql_num_rows($rss)>0) {
				$khmc=mysql_result($rss,0,"khmc");
				$lxr=mysql_result($rss,0,"lxr");
				$lxdh=mysql_result($rss,0,"lxdh");
				$lxdz=mysql_result($rss,0,"lxdz");
				$memo=mysql_result($rss,0,"taskdescribe");
			}
		}
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

		mysql_query("insert into order_mainqt (ddh,khmc,xsbh,ddate,state,sdate,shr,shdh,shdz,memo,scjd,xjdid,zzfy,filefy) values ('".$bh."','".$khmc."','".$xsbh."',now(),'新建订单',now(),'$lxr','$lxdh','$lxdz','$memo','".$_SESSION["GSSDQ"]."','".$_GET["taskid"]."','$dwdm','".$_SESSION["INFO"]["loginname"]."')",$conn);



		if($_POST['fnames']<>''){

			$pbhs = '';

/*文件命名规则
 * $ddfile="http://erp.yikayin.com/nc_erp/Pok1/".mysql_result($rs,$i,"pbh")."-".str_replace("|","",mysql_result($rs,$i,"mbzz"))."-".mysql_result($rs,$i,"pdate")."-main.pdf";

$dbfile="http://erp.yikayin.com/nc_erp/Pok1/".mysql_result($rs,$i,"pbh")."-".str_replace("|","",mysql_result($rs,$i,"mbzz"))."-".mysql_result($rs,$i,"pdate")."-barcode.pdf";
*/
//			$namestr = substr($bh,7,4);

			$i=1;
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
                $fname2 = $fnamearr[1];

				$arr = explode('-',$fname1);
				$pnum1 = 2;
				$sl1 = intval($arr[0]);

				$arri = explode('I',$arr[1]);

//				I-hp7600 I72-hp10000  63自带纸(464*320) 64自带纸(750*530)

				if(empty($arri)){
					$machine1 = 'Hp彩色';
					$paper1 = '63';
				}else{
					$machine1 = 'Hp10000彩色';
					$paper1='64';
				}

				$pname = $arr[2];
				$color1 = '彩色';
				$jldw1= 'P';
				$productname = '单张';
				$n1 = '单张';
				$dsm1 = '双面';
				$hzx1 = '横向';
				$jg1 = 0.6;

                $namestr = "http://erp.yikayin.com/nc_erp/Pok1/";
//                $file1 = $namestr.$arr[0].'-'.$arr[1].'-'.$arr[2].'-'.$arr[3].'-main.pdf';
				$file1 = $namestr.$fname1 . '-main.pdf';

                $insmx = "insert into order_mxqt (id,ddh,productname,pname,n1,file1,machine1,paper1,jldw1,dsm1,hzx1,pnum1,sl1,jg1,paper2,sl2,pnum2,jg2,sczzbh1) values (0,'$bh','$productname','$pname','$n1','$file1','$machine1','$paper1','$jldw1','$dsm1','$hzx1','$pnum1','$sl1','$jg1','1',0,0,0,'$fname2')";
				mysql_query($insmx,$conn);

//				份数大于5就有分拣条码,分拣条码多打印一条明细

				if($sl1>=5){

//					$file1_bar = $namestr.$arr[0].'-'.$arr[1].'-'.$arr[2].'-'.$arr[3].'-barcode.pdf';
					$file1_bar = $namestr.$fname1.'-barcode.pdf';
					$pnum1_bar = 2;
					$sl1_bar = 1;
					$insmxbar = "insert into order_mxqt (id,ddh,productname,pname,n1,file1,machine1,paper1,jldw1,dsm1,hzx1,pnum1,sl1,jg1,paper2,sl2,pnum2,jg2,sczzbh1) values (0,'$bh','$productname','铜版纸','$n1','$file1_bar','$machine1','$paper1','$jldw1','$dsm1','$hzx1','$pnum1_bar','$sl1_bar','$jg1','1',0,0,0,'YK3TB270')";
					mysql_query($insmxbar,$conn);
//					$file = 'logfile.txt';
//					$f= file_put_contents($file,$insmxbar,FILE_APPEND);

				}

				$selje = mysql_query("select ifnull(sum(jg1*pnum1*sl1+jg2*pnum2*sl2),0) as mxje from order_mxqt mx where mx.ddh='$bh'",$conn);

				$je = mysql_result($selje,0,'mxje');
				$updje = mysql_query("update order_mainqt set dje = '$je' WHERE ddh = $bh",$conn);

				$i++;
				$pbhs .= $arr[0].'-'.$arr['1'].';';

			}
			//文件名提交易卡工坊 调用：http://erp.yikayin.com/nc_erp/pbfiledownload.php?ddh=1234&pbhs=111;222;333


//			echo "<script>window.open('http://erp.yikayin.com/nc_erp/pbfiledownload.php?ddh=$bh&pbhs=$pbhs');</script>";


//			刷新页面防止重复提交
//			"&auth=".md5($_SESSION["KHID"]."-".$_SESSION["AUTHCODE"]."-".$row["ddh"]."-".$row["state"])."
//			$authToCheckModify = md5($_SESSION["KHID"]."-".$_SESSION["AUTHCODE"]."-".$_POST["ddh"]."-"."新建订单");
//			echo "<script>window.location.href='?ddh=$bh&auth=$authToCheckModify';</script>";

		}


	}
} else {
	$bh=$_GET["ddh"];
	$authRequest = $_GET["auth"];
	$authToCheck = md5($_SESSION["KHID"]."-".$_SESSION["AUTHCODE"]."-".$bh."-"."新建订单");
//	echo $authRequest."  ".$authToCheck;
	if($authToCheck != $authRequest){
		echo "<script>alert('验证失败！');history.go(-1);</script>";
		exit;
	}
}

$rs=mysql_query("select order_mainqt.*,order_zh.sksj,base_kh.lxr,lxdh from order_mainqt left join order_zh on order_zh.ddh=order_mainqt.ddh and order_zh.zy<>'订单订金' left join base_kh on order_mainqt.khmc=base_kh.khmc where  order_mainqt.ddh='".$bh."'",$conn);
$xjzje=mysql_result($rs,0,"dje");$state=mysql_result($rs,0,"state");
$rsmx = mysql_query("select order_mxqt.*,m1.materialname mm1,m1.specs ms1,m2.materialname mm2,m2.specs ms2,group_concat(hd.jgfs) hd,sum(hd.jg*hd.sl) hdje,group_concat(hd.jg) hdjg, group_concat(fm.fmfs) fmfs,sum(fm.jg*fm.sl) fmje,group_concat(fm.jg) fmjg from order_mxqt left join order_mxqt_hd hd on order_mxqt.id=hd.mxid left join order_mxqt_fm fm on order_mxqt.id=fm.mxid,material m1,material m2 where order_mxqt.ddh='" . $bh . "' and m1.id=paper1 and m2.id=paper2 group by order_mxqt.id", $conn);
//$rsmx=mysql_query("(select order_mxqt.*,m1.materialname mm1,m1.specs ms1,m2.materialname mm2,m2.specs ms2,group_concat(hd.jgfs) hd,sum(hd.jg*hd.sl) hdje from order_mxqt left join order_mxqt_hd hd on order_mxqt.id=hd.mxid,material m1,material m2 where ddh='".$bh."' and m1.materialcode=paper1 and m2.materialcode=paper2 group by order_mxqt.id) union (select order_mxqt.*,m1.materialname mm1,m1.specs ms1,m2.materialname mm2,m2.specs ms2,group_concat(hd.jgfs) hd,sum(hd.jg*hd.sl) hdje from order_mxqt left join order_mxqt_hd hd on order_mxqt.id=hd.mxid,material1 m1,material1 m2 where ddh='".$bh."' and m1.materialcode=paper1 and m2.materialcode=paper2 group by order_mxqt.id)",$conn);
$rskh=mysql_query("select * from order_mxqt where ddh='".$bh."' and (zj is null or zj=0)",$conn);
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
							$rsye=mysql_query("select ye from user_zhjf where depart='$khmc'");
							if($rsye && mysql_num_rows($rsye)>0)
							{$yue=mysql_result($rsye,0,"ye");echo $yue."元";}
							else
								echo "0元";
							?></td>
					</tr>
					<!--<tr>
              <td  height="24" class="STYLE11" align="center">订单金额</td>
              <td colspan="2" class="STYLE13"><input name="dje" type="text" value="<? echo mysql_result($rs,0,"dje")?>" size="6" <? if ((mysql_result($rs,0,"state")!="新建订单" and $_SESSION["FBSD"]!="1") or mysql_result($rs,0,"state")=="进入生产") echo "readonly"?>> 元， 配送费:<input name="kdje" type="text" value="<? echo mysql_result($rs,0,"kdje")?>" size="6" <? if ((mysql_result($rs,0,"state")!="新建订单" and $_SESSION["FBSD"]!="1") or mysql_result($rs,0,"state")=="进入生产") echo "readonly"?>> 元。<? echo "合计：",mysql_result($rs,0,"dje")+mysql_result($rs,0,"kdje"),"元。";?></td>
              <td class="STYLE13">订金：
                <input name="djje" type="text" value="<? echo mysql_result($rs,0,"djje")?>" size="5" <? if ((mysql_result($rs,0,"state")!="新建订单" and $_SESSION["FBSD"]!="1") or mysql_result($rs,0,"state")=="进入生产") echo "readonly"?>>
                元,
                <select name="butt2">
                <option value="现金" <? echo mysql_result($rs,0,"kpyq")=="现金"?"selected":""?>>现金</option>
                <option value="支票" <? echo mysql_result($rs,0,"kpyq")=="支票"?"selected":""?>>支票</option>
                <option value="POS刷卡" <? echo mysql_result($rs,0,"kpyq")=="POS刷卡"?"selected":""?>>POS刷卡</option>
                <option value="汇款" <? echo mysql_result($rs,0,"kpyq")=="汇款"?"selected":""?>>汇款</option>
                <? if ($yue>0) {?>
                <option value="预存扣款" <? echo mysql_result($rs,0,"kpyq")=="预存扣款"?"selected":""?>>预存扣款</option>
                <? }?>
              </select></td>
            </tr>-->
					<tr>
						<td height="24" class="STYLE11" align="center">配送信息</td>
						<td colspan="3" class="STYLE13">配送：
							<select name="psfs" id="psfs" onChange="change(this.value)" >
								<option value="上门自取" <? if (mysql_result($rs,0,"psfs")=="上门自取") echo "selected";?>>上门自取</option>
								<option value="快递配送" <? if (mysql_result($rs,0,"psfs")=="快递配送") echo "selected";?>>快递配送</option>
								<option value="物流配送" <? if (mysql_result($rs,0,"psfs")=="物流配送") echo "selected";?>>物流配送</option>
								<option value="其他" <? if (mysql_result($rs,0,"psfs")=="其他") echo "selected";?>>其他</option>
							</select>
							&nbsp;&nbsp;收货人：<input name="shr" type="text" value="<? echo mysql_result($rs,0,"shr")==""?$lxr:mysql_result($rs,0,"shr")?>" size="6">&nbsp;&nbsp;电话：<input name="shdh" type="text" value="<? echo mysql_result($rs,0,"shdh")==""?$lxdh:mysql_result($rs,0,"shdh")?>" size="12"><br>
							地址： <input name="shdz" type="text" value="<? echo mysql_result($rs,0,"shdz")==""?$lxdz:mysql_result($rs,0,"shdz")?>" size="57"></td>
					</tr>
					<tr style=" display:none">
						<td height="24" class="STYLE11" align="center">开票信息</td>
						<td colspan="3" class="STYLE13"><textarea name="kpyq" cols="50" rows="3"><? echo mysql_result($rs,0,"kpyq");?></textarea></td>
					</tr>
					<tr style=" display:none">
						<td height="24" class="STYLE11" align="center">包装要求</td>
						<td colspan="3" class="STYLE13"><textarea name="bzyq" cols="50" rows="3"><? echo mysql_result($rs,0,"bzyq");?></textarea></td>
					</tr>
					<tr>
						<td height="24" class="STYLE11" align="center">订单备注</td>
						<td colspan="3" class="STYLE13"><textarea name="memo" cols="50" rows="3" style="width:100%;height:50px"><? echo mysql_result($rs,0,"memo");?></textarea></td>
					</tr>
					<tr>
						<td height="24" class="STYLE11" align="center">生产地</td>
						<td colspan="3" class="STYLE13"><!--<input name="scjd" id="scjd" type="text" value="<? echo mysql_result($rs,0,"scjd");?>" size="12" maxlength="4">-->
							<select name="scjd"><option value="上海">上海</option><option value="北京">北京</option></select>
							<input type="submit" name="button" id="button" value="保存订单信息"></td>
					</tr>
					<tr>
						<td height="34" colspan="4" align="left" valign="bottom"><font size="+0.5">
								<? if (mysql_result($rs,0,"state")=="新建订单") {
									echo "<a href='#' class='nav' onClick='javascript:window.open(\"upload_caipu.php?ddh=".mysql_result($rs,0,"ddh")."\", \"HT_dhdj\", \"toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=yes,width=850,height=610,left=300,top=100\")'>【增加明细】</a>&nbsp;&nbsp;";

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
								foreach ($aaa as $key=>$a1)
									if ($a1<>"") echo "<a href='{$localftp}/scfiles/{$a1}?".rand(10,1000)."' target='_blank'>{$a1}</a>  ","<br>";


                                if(!empty(mysql_result($rsmx,$i,"jdf1"))){
                                    echo '<br>';
                                    echo mysql_result($rsmx,$i,"jdf1");
                                }

                                if(!empty(mysql_result($rsmx,$i,"sczzbh1"))){
                                    echo '<br>';
                                    echo mysql_result($rsmx,$i,"sczzbh1");
                                }
                                ?>
                            </td>
							<td class="td_content" align="center"><? echo mysql_result($rsmx,$i,"machine1"),"/<font color=red>",mysql_result($rsmx,$i,"mm1"),"[",mysql_result($rsmx,$i,"ms1"),"]</font>/",mysql_result($rsmx,$i,"jldw1"),"/",mysql_result($rsmx,$i,"dsm1"),"/",mysql_result($rsmx,$i,"hzx1"),"/P:",mysql_result($rsmx,$i,"pnum1"),"/SL:",mysql_result($rsmx,$i,"sl1");?></td>
							<td class="td_content" align="center" ><? echo mysql_result($rsmx,$i,"n2");?></td>
							<td align="center" class="td_content"><?
								$aaa=array_unique(explode(";",mysql_result($rsmx,$i,"file2")));
								foreach ($aaa as $key=>$a1)
									if ($a1<>"") echo "<a href='{$localftp}/scfiles/{$a1}?".rand(10,1000)."' target='_blank'>{$a1}</a>  ","<br>";

                                if(!empty(mysql_result($rsmx,$i,"jdf2"))){
                                    echo '<br>';
                                    echo mysql_result($rsmx,$i,"jdf2");
                                }

                                if(!empty(mysql_result($rsmx,$i,"sczzbh2"))){
                                    echo '<br>';
                                    echo mysql_result($rsmx,$i,"sczzbh2");
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
						<? $zje=$zje+mysql_result($rsmx,$i,"sl1")*mysql_result($rsmx,$i,"pnum1")*mysql_result($rsmx,$i,"jg1")+mysql_result($rsmx,$i,"sl2")*mysql_result($rsmx,$i,"pnum2")*mysql_result($rsmx,$i,"jg2")+mysql_result($rsmx,$i,"hdje");
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
