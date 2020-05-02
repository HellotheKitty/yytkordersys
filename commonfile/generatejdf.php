<?
class make_jdf_func{

    public static function make_jdf($name,$type,$x,$y,$duplex,$num,$machine,$set_wz,$ddh,$i,$paper_name,$jdfold){

        if($machine=='Hp彩色' || $machine=='Hp三色' || $machine=='Hp黑白'){
            $a=$x*2.834375;
            $b=$y*2.834375;

            $x=(int)$a;
            $y=(int)$b;

        }else if($machine=='Hp10000彩色' || $machine=='Hp10000三色' ||  $machine=='Hp10000黑白'){
            $a=$x*2.945;
            $b=$y*2.945;

            $x=(int)$b;
            $y=(int)$a;

        }
        $file=$name;
        $file1=explode("/",$file);
        $file=end($file1);

        $file=urlencode($file);

        $a=strrev($name);
        $a=strstr($a, "/");
        $a=strrev($a);

        //$paper_type=substr($paper_name,0,3);
        $paper_type=$paper_name;
        //纸张种类
//	$dis_name
        if(!empty($jdfold)){

            $jdfnameold = explode('.',$jdfold);
            $dis_name = $jdfnameold[0].'-'.'re';

        }else{
            $dis_name=substr($ddh,5) . '-' . $i;
        }

        //------获取jdf的名字
        $myfiledir = "../../resources/jdf/";
        if(!is_dir($myfiledir)){
            mkdir($myfiledir,0777,true);
        }

        $myfile = $myfiledir.$dis_name.".jdf";

        $name=$a.$file;

        //$dis_name=$dis_name."_".$paper_name;

        if($_SESSION['GDWDM']=='34050000'){
            $text="<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<JDF Type=\"Combined\" xmlns=\"http://www.CIP4.org/JDFSchema_1_1\" ID=\"rootNodeId\" Status=\"Waiting\" JobPartID=\"000.cdp.797\" Version=\"1.2\"  Types=\"DigitalPrinting LayoutPreparation \" DescriptiveName=\"$dis_name\">
<ResourcePool>
<ColorantControl ID=\"CC001\" Status=\"Available\" Class=\"Parameter\" ProcessColorModel=\"DeviceCMYK\" />
<TransferFunctionControl ID=\"TFC001\" Class=\"Parameter\" Status=\"Available\" TransferFunctionSource=\"Custom\">
			<TransferCurvePool>
				<TransferCurveSet Name=\"Paper\">
					<TransferCurve Separation=\"All\" DescriptiveName=\"exp_025\" Curve=\"\" />
					<TransferCurve Separation=\"Cyan\" DescriptiveName=\"0504-Cyan\" Curve=\"\" />
					<TransferCurve Separation=\"Magenta\" DescriptiveName=\"0504-Magenta\" Curve=\"\" />
					<TransferCurve Separation=\"Yellow\" DescriptiveName=\"0504-Yellow\" Curve=\"\" />
					<TransferCurve Separation=\"Black\" DescriptiveName=\"0504-Black\" Curve=\"\" />
				</TransferCurveSet>
			</TransferCurvePool>
</TransferFunctionControl>
<DigitalPrintingParams Class=\"Parameter\" Collate=\"Sheet\" ID=\"DPP001\" Status=\"Available\" />
<Media Class=\"Consumable\" ID=\"M001\" Status=\"Available\" StockType=\"$paper_type\"/>
<DigitalPrintingParams Class=\"Parameter\" ID=\"DPP001\" Status=\"Available\" PageDelivery=\"ReverseOrderFaceUP\"/>
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
<Component ID=\"Component\" ComponentType=\"FinalProduct\" Status=\"Unavailable\" Class=\"Quantity\"> </Component>
</ResourcePool>
<ResourceLinkPool>
<MediaLink rRef=\"M001\" Usage=\"Input\"/>
<DigitalPrintingParamsLink rRef=\"DPP001\" Usage=\"Input\"/>
<RunListLink rRef=\"RunList_1\" Usage=\"Input\"/>
<LayoutPreparationParamsLink rRef=\"LPP001\" Usage=\"Input\"/>
<ComponentLink Amount=\"$num\" Usage=\"Output\" rRef=\"Component\"/>
<TransferFunctionControlLink rRef=\"TFC001\" Usage=\"Input\" />
<ColorantControlLink rRef=\"CC001\" Usage=\"Input\" />
</ResourceLinkPool>
</JDF>";
        }else{
            $text="<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<JDF Type=\"Combined\" xmlns=\"http://www.CIP4.org/JDFSchema_1_1\" ID=\"rootNodeId\" Status=\"Waiting\" JobPartID=\"000.cdp.797\" Version=\"1.2\"  Types=\"DigitalPrinting LayoutPreparation \" DescriptiveName=\"$dis_name\">
<ResourcePool>
<DigitalPrintingParams Class=\"Parameter\" Collate=\"Sheet\" ID=\"DPP002\" Status=\"Available\" />
<Media Class=\"Consumable\" ID=\"M001\" Status=\"Available\" StockType=\"$paper_type\"/>
<DigitalPrintingParams Class=\"Parameter\" ID=\"DPP001\" Status=\"Available\" PageDelivery=\"ReverseOrderFaceUP\"/>
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
<Component ID=\"Component\" ComponentType=\"FinalProduct\" Status=\"Unavailable\" Class=\"Quantity\"> </Component>
</ResourcePool>
<ResourceLinkPool>
<MediaLink rRef=\"M001\" Usage=\"Input\"/>
<DigitalPrintingParamsLink rRef=\"DPP001\" Usage=\"Input\"/>
<DigitalPrintingParamsLink rRef=\"DPP002\" Usage=\"Input\"/>
<RunListLink rRef=\"RunList_1\" Usage=\"Input\"/>
<LayoutPreparationParamsLink rRef=\"LPP001\" Usage=\"Input\"/>
<ComponentLink Amount=\"$num\" Usage=\"Output\" rRef=\"Component\"/>
</ResourceLinkPool>
</JDF>";
        }


        file_put_contents($myfile,$text,FILE_APPEND);

    }
}

?>