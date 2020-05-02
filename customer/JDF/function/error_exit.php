<?
     function error_exit($ddh,$i,$machine){
     	
     $dis_name=substr($ddh,5) . '-' . $i;
     if($machine=="Hp彩色"){
     	
		$dir="Y:/Jobs/JDF/error/";
     }else if($machine=='Hp10000彩色' or $machine=='Hp10000三色'){
     	
		$dir="S:/Jobs/JDF/error/";
     }
	 if(file_exists($dir.$dis_name."jdf")==TRUE){
	    $a=array("code"=>"success");
	    echo json_encode($a);
	 }else{
	 	$a=array("code"=>"fail");
	    echo json_encode($a);
	 }
}
?>