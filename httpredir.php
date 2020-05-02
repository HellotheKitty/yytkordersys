<? 
echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
setcookie("YKOAUSER", "dfdfdfd",time()+3600, "", ".191.88.122", 1);  
Header( "Location:http://".urldecode($_GET["ff"])."?".$_SERVER['QUERY_STRING']); 
?> 