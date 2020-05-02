<?
function make_jdf($name,$type,$x,$y,$duplex,$num,$machine,$set_wz,$ddh,$i,$paper_name){

	if($machine=='Hp彩色' || $machine == 'Hp三色' || $machine =='Hp黑白'){
		$a=$x*2.834375;
		$b=$y*2.834375;
		if($set_wz=='横向'||$set_wz==''){
			$x=(int)$b;
			$y=(int)$a;
		}else if($set_wz=='纵向'){
			$x=(int)$a;
			$y=(int)$b;
		}
	}else if($machine=='Hp10000彩色' || $machine=='Hp10000三色' || $machine =='Hp10000黑白'){
		$a=$x*2.945;
		$b=$y*2.945;
		if($set_wz=='横向'||$set_wz==''){
			$x=(int)$b;
			$y=(int)$a;
		}else if($set_wz=='纵向'){
			$x=(int)$a;
			$y=(int)$b;
		}
	}
	$file=$name;
	$file1=explode("/",$file);
	$file=end($file1);

	$file=urlencode($file);

	$a=strrev($name);
	$a=strstr($a, "/");
	$a=strrev($a);


    $paper_type=substr($paper_name,0,3);
    //纸张种类
//	$dis_name=$mxid;
	$dis_name=substr($ddh,5) . '-' . $i;
	//------获取jdf的名字
//	if($machine=='Hp彩色' || $machine == 'Hp三色' || $machine =='Hp黑白'){
//
//		$myfile = "../upload/JDF/$dis_name.jdf";
//	}else if($machine=='Hp10000彩色' || $machine=='Hp10000三色' || $machine =='Hp10000黑白'){
//		$myfile = "../Jobs/JDF/$dis_name.jdf";
//	}else{
//        $myfile = "../Jobs/JDF/$dis_name.jdf";
//
//    }
	if($machine=='Hp彩色' || $machine == 'Hp三色' || $machine =='Hp黑白'){

		$myfile = fopen("$dis_name.jdf", "w") or die("Unable to open file!");
	}else if($machine=='Hp10000彩色' || $machine=='Hp10000三色' || $machine =='Hp10000黑白'){
		$myfile = fopen("$dis_name.jdf", "w") or die("Unable to open file!");
	}else{
		$myfile = fopen("$dis_name.jdf", "w") or die("Unable to open file!");

	}

	$name=$a.$file;
	
//	$dis_name=$dis_name."_".$paper_name;
	
	$text="<?xml version=\"1.0\" encoding=\"UTF-8\"?>

<JDF Type=\"Combined\" xmlns=\"http://www.CIP4.org/JDFSchema_1_1\" ID=\"rootNodeId\" Status=\"Waiting\" JobPartID=\"000.cdp.797\" Version=\"1.2\"  Types=\"DigitalPrinting LayoutPreparation Gathering\" DescriptiveName=\"$dis_name\">
<ResourcePool>
<Media Class=\"Consumable\" ID=\"M001\" Status=\"Available\" StockType=\"$paper_type\"/>
<DigitalPrintingParams Class=\"Parameter\" ID=\"DPP001\" Status=\"Available\"/>
<RunList ID=\"RunList_1\" Status=\"Available\" Class=\"Parameter\">
<LayoutElement>
<FileSpec MimeType=\"application/pdf\" URL=\"$name\"/>
</LayoutElement>
</RunList>
<LayoutPreparationParams Class=\"Parameter\" ID=\"LPP001\" Sides=\"$duplex\" Status=\"Available\">
<PageCell TrimSize=\"$x $y\" />
</LayoutPreparationParams>
<FeedingParams Class=\"Parameter\" ID=\"FPS-DS\" Status=\"Available\">
<Feeder FeederType=\"Copy\" />
</FeedingParams>
<GatheringParams ID=\"GP01\" Class=\"Parameter\" Status=\"Available\">
<SourceResource>
<FeedingParamsRef rRef=\"FPS-DS\"/>
</SourceResource>
<Disjointing>
<InsertSheet SheetType=\"SeparatorSheet\" SheetUsage=\"Trailer\" SheetFormat=\"Standard\"/>
</Disjointing>
</GatheringParams>
<Component ID=\"Component\" ComponentType=\"FinalProduct\" Status=\"Unavailable\" Class=\"Quantity\"> </Component>
</ResourcePool>
<ResourceLinkPool>
<MediaLink rRef=\"M001\" Usage=\"Input\"/>
<DigitalPrintingParamsLink rRef=\"DPP001\" Usage=\"Input\"/>
<RunListLink rRef=\"RunList_1\" Usage=\"Input\"/>
<LayoutPreparationParamsLink rRef=\"LPP001\" Usage=\"Input\"/>
<GatheringParamsLink rRef=\"GP01\" Usage=\"Input\"/>
<ComponentLink Amount=\"$num\" Usage=\"Output\" rRef=\"Component\"/>
</ResourceLinkPool>
</JDF>";
echo $text;
	fwrite($myfile,$text);

}
?>