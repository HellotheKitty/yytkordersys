<? 
session_start();
require("../inc/conn.php"); 
$dwdm = substr($_SESSION["GDWDM"],0,4);
$tmp = explode(';',$_SESSION["QX"]);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<head>
    <link href="../css/Styles.css" rel="stylesheet" type="text/css">
    <script src="../js/jquery-1.8.3.min.js" type="text/javascript"></script>
    <script src="../js/jQuery-ui.js" type="text/javascript"></script> 
    <script src="../js/JQuery.MenuTree.js" type="text/javascript"></script>

    <script type="text/javascript">
            $(function() {
                $('#menu').menuTree();
            });
			function Ddown(s) {
				var character = new Array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O");  
				for(var i=0;i<15;i++)  
				{  
					$('#'+character[i]).slideUp('fast');
				}
				if (s!="") $('#'+s).slideDown('slow');
			}
    </script>

<style>
html { overflow-x:hidden; overflow-y: auto; width: 200px; }
</style>
</head>
<body>
<div class="leftside">
<div id="menu" class="menuTree">
 <ul  style="display: block;">

    <li class="parent expanded" ><a href="#"><div class="renzhengshenpi treeicon"></div>我的业务平台</a>
		<ul  id="O" style="display:block">
			<li class="child" ><a href="order/orderlist.php" target="main">订单列表</a></li>
			<li class="child" ><a href="balance/balancelist.php" target="main">充值消费</a></li>
			<? if($_SESSION['LOGINNAME'] <> 'ykgfmp'){ ?>
				<li class="child" ><a href="order/excel_mx_kh.php" target="main">订单明细</a></li>
			<?}?>

			<? if($_SESSION['LOGINNAME'] == 'ykgfmp'){ ?>
			<li class="child" ><a href="zzck/paper_get_list.php" target="main">取纸单列表</a></li>
			<? } ?>
		</ul>
    </li>

	 <li class="parent">
		 <a href="#">
			 <div class="gerenshezhi treeicon"></div>
			 <span>使用指南</span>
		 </a>
		 <ul id="N">
			 <li class="child"><a href="profile1.pdf" target="blank">自助下单</a></li>
		 </ul>
	 </li>

    <div class="liline">    </div>
</ul> 
</div>
	<div class="shadow1"></div>
	<div class="shadow2"></div>
</div>
<script type="text/javascript">
    $('.parent UL LI').click(function(){
		if(this.className=="child"){
			$('.parent ul li').removeClass();
			$('.parent ul li').addClass("child");
			this.className="childclick";
		}else{
			this.className="childclick";
		}
	
	});
</script>
</body>
</html>
