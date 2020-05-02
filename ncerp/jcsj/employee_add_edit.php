<? 
header("Content-Type:text/html;charset=UTF-8");
require("../../inc/conn.php");?>
<?

session_start();
if ($_SESSION["OK"]<>"OK") { 
echo "<script language=JavaScript>{window.location.href='error.php';}</script>";
exit; 
}?>
<? 
	if($_GET["repwd"] == 1 && mysql_query("update b_ry set password='123456' where id='".$_GET["id"]."'"))
	{
		echo "重置成功。";
		exit;
	}

	$nowPage = $_POST["nowPage"];
	if ($_POST["Submit"]<>"查 询") {          //保存数据

	//根据权限确定职务；

	if ($_POST["ID"]<>"") {			//保存
		$rss=mysql_query("select id from b_ry where bh='".$_POST["bh"]."' or xm = '".$_POST['xm']."'");
		if($rss && mysql_result($rss,0,"id")!=$_POST["ID"]){
			echo "<script language=JavaScript>alert('员工编号和姓名不能和已有的重复~~~');window.location.href='employee_list.php?pno=$nowPage';</script>";
			exit;
		}

		$sql = "update b_ry set ";
//		if($_POST["bh"]<>"") $sql.="bh='".$_POST["bh"]."',";
//		if($_POST["xm"]<>"") $sql.="xm='".$_POST["xm"]."',";
		if($_POST["xb"]<>"") $sql.="xb='".$_POST["xb"]."',";
		if($_POST["zw"]<>"") $sql.="zw='".$_POST["zw"]."',";
//		if($_POST["bm"]<>"") $sql.="bm='".$_POST["bm"]."',";
		if($_POST["qx"]<>"") $sql.="qx='".$_POST["qx"]."',";
		if($_POST["dw"]<>"") $sql.="dwdm='".$_POST["dw"]."',";
		if($_POST["mobile"]<>"") $sql.="mobile='".$_POST["mobile"]."',";
		if($_POST["qq"]<>"") $sql.="QQno='".$_POST["qq"]."',";
		if($_POST["txaddress"]<>"") $sql.="txaddress='".$_POST["txaddress"]."',";
		if($sql[strlen($sql)-1] == ",")
			$sql = substr($sql, 0, -1);
		$sql.="where id ='".$_POST["ID"]."'";
		mysql_query($sql,$conn);
	} else {				//添加
		$rss=mysql_query("select bh from b_ry where bh='".$_POST["bh"]."' or xm = '".$_POST["xm"]."'",$conn);
		if (mysql_num_rows($rss)>0) {
			echo "<script language=JavaScript>alert('员工编号或姓名不可与已有相同。不能增加重复的员工编号和姓名！');window.location.href='employee_list.php?pno=$nowPage';</script>";
			exit;
		}

        if(empty($_POST['dw'])){
            echo "<script language=JavaScript>alert('请选择单位');window.location.href='employee_list.php?pno=$nowPage';</script>";
            exit();
        }
		$insertSql = "insert into b_ry (bh,xm,xb,zw,qx,dwdm,mobile,QQno,txaddress,password) values ('".$_POST["bh"]."','".$_POST["xm"]."','".$_POST["xb"]."','".$_POST["zw"]."','".$_POST["qx"]."','".$_POST["dw"]."','".$_POST["mobile"]."','".$_POST["qq"]."','".$_POST["txaddress"]."','123456')";

//		 echo $insertSql;
		mysql_query($insertSql,$conn);
		if($_POST["qx"] == 'kf') {
			$sql2 = "insert into task_kfry (oaBh,isOK,taskAmount,groupName,isHead,taskDay,xm,isOnduty,zzfy) values ('".$_POST["bh"]."','1','0','A','0','".date("Y-m-d")."','".$_POST["xm"]."','0','".substr($_POST["dw"],0,4)."')";
			mysql_query($sql2, $conn);
			$sql3 = "update task_type set taskRyxm=concat(taskRyxm,'".$_POST["xm"].";"."') where taskType<14 and zzfy='".substr($_POST["dw"],0,4)."'";
			mysql_query($sql3, $conn);
		}
//		exit;
	}

header("Location:employee_list.php?pno=$nowPage");
}
else {							//？查询是button，不是submit，else子句永远不会执行？？？查询数据
header("Location:employee_list.php?zdm=".$_POST["khmc"]."&zmc=".$_POST["lxr"]."&gsmc=".$_POST["lxdh"]."&gwmc=".$_POST["lxdz"]."&qq=".$_POST["qq"]);
} 
?>
