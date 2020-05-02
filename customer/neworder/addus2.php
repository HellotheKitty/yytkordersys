<!DOCTYPE html>
<html>
	<?
		require "fragment/top.php";
	?>
	<body>
    <?
        require "data/sql.php";
    	require "fragment/header.php";
		$num="3";
		$type=$_GET['job_type'];
		$sql_web=new sql_web();
		$res=$sql_web->get_com_detail($num);
		$res_job=$sql_web->get_job_detail($type);
    ?>
    <div style="height: auto;" align="center">
    	<div>
    		<img src="public/image/addus_banner.png" style="width: 100%;"/>
    	</div>
         <div class="content" style="width: 570px;height: auto;margin-top: 3%;" align="left">
         	<div style="height: 75px;">
        		<h2>
        		<?
        			echo $res_job['type'];
        			
        		?>
        		</h2>
        		<?
        		echo $res_job['content1'];
				?>
        	</div>
        	<hr class="hrcolor">
        	<div>
                <p class="comtitle">岗位描述</p>
        		<?
        			echo $res_job['content2'];
        		?>
        		<p class="comtitle">职位要求</p>
        		<?
        			echo $res_job['content3'];
        		?>
        		<a href="<?echo "http://www.datassis.com/new_work/addus3.php?job_type=".$type?>">
        		<div align="left" style="background-image: url(public/image/申请按钮.png);width: 120px;margin-top: 6%;height: 40px;">
        			<label class="lablebutton" style="margin-left: 17%">申请职位</label>
        		</div>
        		</a>
        	</div>
        	<div align="left">
        	<p>
        		<strong>
               	<?
               		echo $res['content'];
               	?>
               	</strong>
       		 </p>
       		 <p>
       		 	<strong>
               	<?
               		echo $res['content2']
               	?>
               	</strong>
        	</p>
        	</div>
        </div>
    </div>
	<?
		require "fragment/footer.php";
	?>
	</body>
</html>
