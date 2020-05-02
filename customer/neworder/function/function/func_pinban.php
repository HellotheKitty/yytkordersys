<?

/* 图片  单独拼
 * 文件路径
 * 订单号
 * 脚注文本
 * 单双面
 * 机型
 * 数据库连接句柄*/

function pic_pinban_seperate($inPutDir,$bh,  $text, $machine,$dsm , $cp_width , $cp_height , $cx , $conn){

    $pdfd=new fpdi();
    $pdfd->SetMargins(0,0,0);
    $pdfd->SetAutoPageBreak(1,1);
    $pdfd->AddGBFont();
    $pdfd->Open();
    $pdfd->SetFont('GB','B',8);
    $pdfd->AliasNbPages();

    if($machine == 'Hp彩色'){

        //打印幅面 310*450
        $zzk=310;$zzg=450;
//        page size
        $psizek = 320;$psizeg = 464;

    }elseif($machine == 'Hp10000彩色'){

        //打印幅面 740*510
        $zzk=510;$zzg=740;
//        page size
        $psizek = 530;$psizeg = 750;
    }else{

        //打印幅面 740*510
        $zzk=510;$zzg=740;
//        page size
        $psizek = 530;$psizeg = 750;
    }

//    $cx=3;

    $filearr = scandir($inPutDir);

    $picfilename1 = $inPutDir.$filearr[2];

    $c=count($filearr);

//    整理文件名 按照文件名大小排序

    $filearr = maopao($filearr);

    $page_num = 1;

/*    $logd = new Log();
    $logd -> INFO($filearr[0]);
    $logd -> INFO($filearr[1]);
    $logd -> INFO($filearr[2]);
    $logd -> INFO($filearr[5]);
    $logd -> INFO($filearr[6]);
    $logd -> INFO($filearr[7]);*/

    if($cp_width == 0 || $cp_height == 0){

        list($width, $height, $type, $attr) = getimagesize($picfilename1);

        $cp_width = $width * 2.54 / 30;               //300 dpi 2.54英寸 像素转毫米
        $cp_height = $height * 2.54 / 30;

    }

    //    横版能拼多少
    $x1 = floor($zzg / $cp_width);  //横的几个
    $y1 = floor($zzk / $cp_height);  //竖的几个
    $n1 = $x1 * $y1;

    //    竖版能拼多少
    $x2 = floor($zzk / $cp_width ); //列数
    $y2 = floor($zzg / $cp_height); //行数
    $n2 = $x2 * $y2;

    if($n1 > $n2){//横拼

        $layout = 'L';

        $lmargin = ($psizeg - $x1 * $cp_width)/2;
        $tmargin = ($psizek - $y1 * $cp_height)/2;

        $x = $x1;
        $y = $y1;
        $n = $n1;

        $land_e = $psizeg;
        $port_e = $psizek;

    }else{//        竖拼

        $layout = 'P';

        $lmargin = ($psizek - $x2 * $cp_width)/2;
        $tmargin = ($psizeg - $y2 * $cp_height)/2;

        $x = $x2;
        $y = $y2;
        $n = $n2;

        $land_e = $psizek;
        $port_e = $psizeg;

    }

    for($i=2;$i<$c;$i++){

        $pdffilename = $inPutDir.$filearr[$i];

        $pdfd -> addPage($layout,array($psizeg, $psizek));//生成新页

        for($h =0;$h<$y;$h++){ //行遍历

            for($l = 0 ;$l <$x;$l++){ //列遍历

                $pdfd->Image($pdffilename, $lmargin + $l * $cp_width , $tmargin + $h * $cp_height ,$cp_width,$cp_height);

            }
        }

//            绞线
        draw_line($pdfd,$y,$x,$cx,$tmargin,$lmargin,$cp_width,$cp_height,$land_e,$port_e);

        $text1 = $text . "第：" . $page_num . "页" . "共：" . ($c-2) . "页";

//            脚注
        $file=explode(".", $filearr[$i]);
        $file = $file[0];
        $images = $file;
        cptext(1000,10,$text1, $images);
        $pdfd->image("image/$images.png",2 * 3 + $lmargin, $port_e -  13);

//            是否双面
        if($dsm == '双面'){

//            $page_num++;
        }

        $page_num++;

    }

    $outfilename = substr($bh, -6).".pdf";

    outputpdf("../server/files/".$outfilename, $pdfd);

//    修改明细
    savemx($layout, $c-2,0 ,$bh , $conn);

}

// 图片拼版 一起拼
function pic_pinban_together($inPutDir,$bh,  $text, $machine,$dsm , $cp_width , $cp_height , $cx, $conn){


    $pdfd=new fpdi();
    $pdfd->SetMargins(0,0,0);
    $pdfd->SetAutoPageBreak(1,1);
    $pdfd->AddGBFont();
    $pdfd->Open();
    $pdfd->SetFont('GB','B',8);
    $pdfd->AliasNbPages();


    if($machine == 'Hp彩色'){

        //打印幅面 740*510
        $zzk=310;$zzg=450;
//        page size
        $psizek = 320;$psizeg = 464;

    }elseif($machine == 'Hp10000彩色'){

        //打印幅面 740*510
        $zzk=510;$zzg=740;
        //        page size
        $psizek = 530;$psizeg = 750;
    }else{

        //打印幅面 740*510
        $zzk = 510;$zzg = 740;
        //        page size
        $psizek = 530;$psizeg = 750;
    }

//    $cx=3;

    $filearr = scandir($inPutDir);

    $picfilename1 = $inPutDir.$filearr[2];


    if($cp_width == 0 || $cp_height == 0){

        list($width, $height, $type, $attr) = getimagesize($picfilename1);

        $cp_width = $width*2.54/30;               //300 dpi 2.54英寸 像素转毫米
        $cp_height = $height*2.54/30;

    }

    //    横版能拼多少
    $x1 = floor($zzg / $cp_width); //横的几个
    $y1 = floor($zzk / $cp_height); //竖的几个
    $n1 = $x1 * $y1;

    //    竖版能拼多少
    $x2 = floor($zzk / $cp_width ); //列数
    $y2 = floor($zzg / $cp_height); //行数
    $n2 = $x2 * $y2;

    if($n1 > $n2){//横拼

        $layout = 'L';

        $lmargin = ($psizeg - $x1 * $cp_width)/2;
        $tmargin = ($psizek - $y1 * $cp_height)/2;

        $x = $x1;
        $y = $y1;
        $n = $n1;

        $land_e = $psizeg;
        $port_e = $psizek;

    }else{//        竖拼

        $layout = 'P';

        $lmargin = ($psizek - $x2 * $cp_width)/2;
        $tmargin = ($psizeg - $y2 * $cp_height)/2;

        $x = $x2;
        $y = $y2;
        $n = $n2;

        $land_e = $psizek;
        $port_e = $psizeg;

    }

    $c=count($filearr);

    $page_num = 1;

//        版数
    $pnum = ceil(($c - 2) / $n);

    for($p = 0; $p < $pnum; $p++){

        $pdfd -> addPage($layout,array($psizeg, $psizek));//生成新页

        for($item= $p * $n; $item < $p * $n + $n; $item++){

//            文件拼完之后重复直到版面拼满
            if($item + 3 > $c) {

                $rd = floor($item / ($c-2));

                $pdffilename = $inPutDir.$filearr[$item + 2 - $rd * ($c-2)];
            }else{

                $pdffilename = $inPutDir.$filearr[$item + 2];

            }

//            当前行数和列数
            $a = $item - $p * $n;
            $h = floor($a / $x);
            $l = $a % $x ;

            $pdfd->Image($pdffilename, $lmargin + $l * $cp_width , $tmargin + $h * $cp_height ,$cp_width,$cp_height);

        }

//            绞线
        draw_line($pdfd,$y,$x,$cx,$tmargin,$lmargin , $cp_width ,$cp_height,$land_e,$port_e);

//            脚注
        $text1 = $text . "第：" . $page_num . "页" . "共：" . ceil(($c-2)/$n) . "页";

        $file=explode(".", $filearr[$item+2]);
        $file=$file[0];
        $images=$file;
        cptext(1000,10,$text1, $images);
        $pdfd->image("image/$images.png",2 * 3 + $lmargin,  $port_e - 13);

        $page_num++;
    }

    $outfilename = substr($bh, -6).".pdf";

    outputpdf("../server/files/".$outfilename, $pdfd);

//    修改明细
    savemx($layout,$pnum , 0,$bh , $conn);

}

// pdf拼版 单独拼
function pdf_seperate_pb_1w($inPutDir,$bh,  $text ,$dsm , $machine ,$cx ,$conn){

    $pdfd=new fpdi();
    $pdfd->SetMargins(0,0,0);
    $pdfd->SetAutoPageBreak(1,1);
    $pdfd->AddGBFont();
    $pdfd->Open();
    $pdfd->SetFont('GB','B',8);
    $pdfd->AliasNbPages();

    if($machine == 'Hp彩色'){

        //打印幅面 740*510
        $zzk=310;$zzg=450;
        //        page size
        $psizek = 320;$psizeg = 464;

    }elseif($machine == 'Hp10000彩色'){

        //打印幅面 740*510
        $zzk=510;$zzg=740;
        //        page size
        $psizek = 530;$psizeg = 750;
    }else{

        //打印幅面 740*510
        $zzk=510;$zzg=740;
        //        page size
        $psizek = 530;$psizeg = 750;
    }

//    $cx=3;

    $filearr = scandir($inPutDir);

    $pdffilename1 = $inPutDir.$filearr[2];

    $pagecount = $pdfd->setSourceFile($pdffilename1);  //源文件pdf

    $tplidx = $pdfd->ImportPage(1);

    $size = $pdfd -> getTemplateSize($tplidx);

    $width1 = $size['w'];
    $height1 = $size['h'];


        $c=count($filearr);

        $page_num = 1;

        $pdffilename = $inPutDir.$filearr[2];

        $pagecount = $pdfd->setSourceFile($pdffilename);  //源文件pdf

        $tplidx = $pdfd->ImportPage(1); //文件的第1页

        $size = $pdfd -> getTemplateSize($tplidx);

        $width = $size['w'];
        $height = $size['h'];

        //    横版能拼多少
        $x1 = floor($zzg / $width); //横的几个
        $y1 = floor($zzk / $height); //竖的几个
        $n1 = $x1 * $y1;

        //    竖版能拼多少
        $x2 = floor($zzk / $width ); //列数
        $y2 = floor($zzg / $height); //行数
        $n2 = $x2 * $y2;

        if($n1 > $n2){//横拼


            $layout = 'L';

            $lmargin = ($psizeg - $x1 * $width)/2;
            $tmargin = ($psizek - $y1 * $height)/2;

            $x = $x1;
            $y = $y1;
            $n = $n1;

            $land_e = $psizeg;
            $port_e = $psizek;

        }else{//        竖拼

            $layout = 'P';

            $lmargin = ($psizek - $x2 * $width)/2;
            $tmargin = ($psizeg - $y2 * $height)/2;

            $x = $x2;
            $y = $y2;
            $n = $n2;

            $land_e = $psizek;
            $port_e = $psizeg;

        }
//页数
    if($dsm == '双面'){

        $pnum = 2 * ($c - 2);
    }else{
        $pnum = $c - 2;
    }
        for($i=2;$i<$c;$i++){


            $pdffilename = $inPutDir.$filearr[$i];

            $pagecount = $pdfd->setSourceFile($pdffilename);  //源文件pdf

            $tplidx = $pdfd->ImportPage(1); //文件的第1页

            $pdfd -> addPage($layout,array($psizeg, $psizek));//生成新页

            for($h =0;$h<$y;$h++){ //行遍历

                for($l = 0 ;$l <$x;$l++){ //列遍历

                    $pdfd->useTemplate($tplidx,$lmargin + $l * $width ,$tmargin + $h * $height ,$width,$height);

                }
            }

//            绞线
            draw_line($pdfd,$y,$x,$cx,$tmargin,$lmargin,$width,$height,$land_e,$port_e);

//            脚注
            $text1 = $text . "第：" . $page_num . "页" . "共：" . $pnum . "页";

            $file=explode(".", $filearr[$i]);
            $file=$file[0];
            $images=$file;
            cptext(1000,10,$text1, $images);
            $pdfd->image("image/$images.png",2 * 3 + $lmargin, $port_e -  13);

//            是否双面
            if($dsm == '双面'){

                $tplidx = $pdfd->ImportPage(2); //文件的第2页

                $size = $pdfd -> getTemplateSize($tplidx);

                $width = $size['w'];
                $height = $size['h'];

                $pdfd -> addPage($layout,array($psizeg, $psizek));//生成新页

                for($h =0;$h<$y;$h++){ //行遍历

                    for($l = $x-1 ;$l >= 0; $l--){ //列遍历

                        $pdfd->useTemplate($tplidx,$lmargin + $l * $width ,$tmargin + $h * $height ,$width,$height);

                    }
                }

//            绞线
                draw_line($pdfd,$y,$x,$cx,$tmargin,$lmargin,$width,$height,$land_e,$port_e);

                $page_num++;
//            脚注

                $text1 = $text . "第：" . $page_num . "页" . "共：" . $pnum . "页";

                $file=explode(".", $filearr[$i]);
                $file=$file[0];
                $images=$file;
                cptext(1000,10,$text1, $images);
                $pdfd->image("image/$images.png",2 * 3 + $lmargin, $port_e -  13);

            }

            $page_num++;

        }

    $outfilename = substr($bh, -6).".pdf";

    outputpdf("../server/files/".$outfilename, $pdfd);


//            database修改明细
    savemx($layout, $pnum,0 ,$bh , $conn);

}

// 一起拼 pdf
function pdf_together_pb_1w($inPutDir,$bh,  $text ,$dsm,$machine ,$cx ,$conn){

    $pdfd=new fpdi();
    $pdfd->SetMargins(0,0,0);
    $pdfd->SetAutoPageBreak(1,1);
    $pdfd->AddGBFont();
    $pdfd->Open();
    $pdfd->SetFont('GB','B',8);
    $pdfd->AliasNbPages();


    if($machine == 'Hp彩色'){

        //打印幅面 740*510
        $zzk=310;$zzg=450;
        //        page size
        $psizek = 320;$psizeg = 464;

    }elseif($machine == 'Hp10000彩色'){

        //打印幅面 740*510
        $zzk=510;$zzg=740;
        //        page size
        $psizek = 530;$psizeg = 750;
    }else{

        //打印幅面 740*510
        $zzk=510;$zzg=740;
        //        page size
        $psizek = 530;$psizeg = 750;
    }

//    $cx=3;

    $filearr = scandir($inPutDir);

    $pdffilename1 = $inPutDir.$filearr[2];

    $pagecount = $pdfd->setSourceFile($pdffilename1);  //源文件pdf

    $tplidx = $pdfd->ImportPage(1);

    $size = $pdfd -> getTemplateSize($tplidx);

    $width1 = $size['w'];
    $height1 = $size['h'];

    //    横版能拼多少
    $x1 = floor($zzg / $width1); //横的几个
    $y1 = floor($zzk / $height1); //竖的几个
    $n1 = $x1 * $y1;

    //    竖版能拼多少
    $x2 = floor($zzk / $width1 ); //列数
    $y2 = floor($zzg / $height1); //行数
    $n2 = $x2 * $y2;

    if($n1 > $n2){//横拼


        $layout = 'L';

        $lmargin = ($psizeg - $x1 * $width1)/2;
        $tmargin = ($psizek - $y1 * $height1)/2;

        $x = $x1;
        $y = $y1;
        $n = $n1;

        $land_e = $psizeg;
        $port_e = $psizek;

    }else{//        竖拼


        $layout = 'P';

        $lmargin = ($psizek - $x2 * $width1)/2;
        $tmargin = ($psizeg - $y2 * $height1)/2;

        $x = $x2;
        $y = $y2;
        $n = $n2;

        $land_e = $psizek;
        $port_e = $psizeg;
    }

    $c=count($filearr);

    $page_num = 1;

//        版数
    if($dsm == '双面'){

        $pnum = 2 * ceil(($c - 2) / $n);

    }else{
        $pnum = ceil(($c - 2) / $n);
    }
    $pnum = ceil(($c - 2) / $n);

    for($p =0; $p<$pnum;$p++){

        $pdfd -> addPage($layout,array($psizeg, $psizek));//生成新页

        for($item= $p * $n; $item < $p * $n + $n; $item++){


            //            文件拼完之后重复直到版面拼满
            if($item + 3 > $c) {

                $rd = floor($item / ($c-2));

                $pdffilename = $inPutDir.$filearr[$item + 2 - $rd * ($c-2)];
            }else{

                $pdffilename = $inPutDir.$filearr[$item+2];

            }


            $pagecount = $pdfd->setSourceFile($pdffilename);  //源文件pdf

            $tplidx = $pdfd->ImportPage(1); //文件的第1页

//            当前行数和列数
            $a = $item - $p * $n;
            $h = floor($a / $x);
            $l = $a % $x ;

            $pdfd->useTemplate($tplidx,$lmargin + $l * $width1 ,$tmargin + $h * $height1 ,$width1,$height1);

        }

//            绞线
        draw_line($pdfd,$y,$x,$cx,$tmargin,$lmargin,$width1,$height1,$land_e,$port_e);


//            脚注
        $text1 = $text . "第：" . $page_num . "页" . "共：" . ceil(($c-2)/$n) . "页";

        $file=explode(".", $filearr[$item+2]);
        $file=$file[0];
        $images=$file;
        cptext(1000,10,$text1, $images);
        $pdfd->image("image/$images.png",2 * 3 + $lmargin,  $port_e -  13);


//            是否双面
        if($dsm == '双面'){

            $pdfd -> addPage($layout,array($psizeg, $psizek));//生成新页

            for($item= $p * $n; $item < $p * $n + $n; $item++){


                //            文件拼完之后重复直到版面拼满
                if($item + 3 > $c) {

                    $rd = floor($item / ($c-2));

                    $pdffilename = $inPutDir.$filearr[$item + 2 - $rd * ($c-2)];
                }else{

                    $pdffilename = $inPutDir.$filearr[$item+2];

                }

                $pagecount = $pdfd->setSourceFile($pdffilename);  //源文件pdf

                $tplidx = $pdfd->ImportPage(2); //文件的第2页

//            当前行数和列数 列数是关键！关键！关键！重要的事情说三遍
                $a = $item - $p * $n;
                $h = floor($a / $x);
                $l = $x - 1 - $a % $x ;

                $pdfd->useTemplate($tplidx,$lmargin + $l * $width1 ,$tmargin + $h * $height1 ,$width1,$height1);

            }

//            绞线
            draw_line($pdfd,$y,$x,$cx,$tmargin,$lmargin,$width1,$height1,$land_e,$port_e);

//            脚注

            $page_num++;
            $text1 = $text . "第：" . $page_num . "页" . "共：" . ceil(($c-2)/$n) . "页";

            $file=explode(".", $filearr[$item+2]);
            $file=$file[0];
            $images=$file;
            cptext(1000,10,$text1, $images);

            $pdfd->image("image/$images.png",2 * 3 + $lmargin, $port_e -  13);

        }
        $page_num++;
    }

    $outfilename = substr($bh, -6).".pdf";

    outputpdf("../server/files/".$outfilename, $pdfd);


//            database修改明细
    savemx($layout , ceil(($c-2)/$n) ,0 ,$bh , $conn);

}

/* 书本骑马钉拼版
 * 文件路径
 * 订单号
 * 脚注文本
 * 单双面
 * 机型
 * 数据库连接句柄
*/
function book_staple_pb($inPutDir,$bh,  $text ,$machine, $is_binding ,$cx , $conn){


    $pdfd=new fpdi();
    $pdfd->SetMargins(0,0,0);
    $pdfd->SetAutoPageBreak(1,1);
    $pdfd->AddGBFont();
    $pdfd->Open();
    $pdfd->SetFont('GB','B',8);
    $pdfd->AliasNbPages();

    if($machine == 'Hp彩色'){

        //打印幅面
        $zzk=310;$zzg=450;
        //        page size
        $psizek = 320;$psizeg = 464;

    }elseif($machine == 'Hp10000彩色'){

        //打印幅面 740*510
        $zzk=510;$zzg=740;
        //        page size
        $psizek = 530;$psizeg = 750;
    }else{

        //打印幅面 740*510
        $zzk=310;$zzg=450;
        //        page size
        $psizek = 320;$psizeg = 464;
    }

//    $cx=3;

    $filearr = scandir($inPutDir);

    $pdffilename1 = $inPutDir.$filearr[2];

    if(!$is_binding){
        //    去掉偶数页右边的出血 奇数页左边的出血
        $type = 'all';
    }else{
//        胶装去掉封面出血
        $type = 'cover';
    }
    del_bleed( $pdffilename1 , $cx,$type );

    $pagecount = $pdfd->setSourceFile($pdffilename1);  //源文件pdf

    //    页数是4的倍数must
    if($pagecount % 4 <> 0 || $pagecount <=4){

        return $pdffilename1;
    }

// -------------------------- 封面 ----------------------
//    封面
    $tplidx = $pdfd->ImportPage(1);

    $size = $pdfd -> getTemplateSize($tplidx);

    $width1 = $size['w'];
    $height1 = $size['h'];

//    封底
    $tplidx2 = $pdfd -> ImportPage($pagecount);

    $size2 = $pdfd -> getTemplateSize($tplidx2);

    $width2 = $size2['w'];
    $height2 = $size2['h'];

//    封面内页

    $tplidx3 = $pdfd -> ImportPage(2);

//    封底内页
    $tplidx4 = $pdfd -> ImportPage($pagecount-1);

    //   封面封底合并 放一个版里面 横版能拼多少
    $x1 = floor($zzg / ($width1 + $width2)); //横的几个
    $y1 = floor($zzk / $height1); //竖的几个
    $n1 = $x1 * $y1;

    //    竖版能拼多少
    $x2 = floor($zzk / ($width1 + $width2)); //列数
    $y2 = floor($zzg / $height1); //行数
    $n2 = $x2 * $y2;

    if($n1 > $n2){ //横拼

        $layout = 'L';

        $lmargin = ($psizeg - $x1 * ($width1 + $width2))/2;
        $tmargin = ($psizek - $y1 * $height1 )/2;

        $x = $x1;
        $y = $y1;
        $n = $n1;

        $land_e = $psizeg;
        $port_e = $psizek;

    }else{//        竖拼

        $layout = 'P';

        $lmargin = ($psizek - $x2 * ($width1 + $width2))/2;
        $tmargin = ($psizeg - $y2 * $height1)/2;

        $x = $x2;
        $y = $y2;
        $n = $n2;

        $land_e = $psizek;
        $port_e = $psizeg;
    }

    $page_num = 1;
    $pnum_1 = 2; //封面p数

//    正面 - 封面封底

    $pdfd -> addPage($layout , array($psizeg , $psizek)); //new page

    for($item = 0; $item <$n; $item ++){

        //            当前行数和列数
        $a = $item ;
        $h = floor($a / $x);
        $l = $a % $x;

//        文件高度以封面为准
        $pdfd->useTemplate($tplidx2 ,$lmargin + $l * ($width2 + $width1) ,$tmargin + $h * $height1 ,$width2,$height1); //2 封底

        $pdfd->useTemplate($tplidx ,$lmargin + $l * ($width2 + $width1) + $width2 ,$tmargin + $h * $height1 ,$width1,$height1); //1 封面

        draw_line($pdfd,$y,$x,$cx,$tmargin,$lmargin,($width1 + $width2),$height1,$land_e,$port_e);

    }

//    反面 - 封面封底内页
    $pdfd -> addPage($layout , array($psizeg , $psizek));

    for($h = 0; $h < $y; $h++){

        //            当前行数和列数 列数是关键！关键！关键！重要的事情说三遍 此处遍历是递减的 所以不需要
//        $h = floor($item / $x);
//        $l = $x - 1 - $item % $x;

        for($l = $x-1; $l >= 0; $l --){

//        文件高度以封面为准

            $pdfd->useTemplate($tplidx4 ,$lmargin + $l * ($width2 + $width1) + $width2 ,$tmargin + $h * $height1 ,$width2,$height1);// 4 封底内页

            $pdfd->useTemplate($tplidx3 ,$lmargin + $l * ($width2 + $width1) ,$tmargin + $h * $height1 ,$width1,$height1); //3 封面内页

        }

    }

//        绞线
    draw_line($pdfd,$y,$x,$cx,$tmargin,$lmargin,($width1 + $width2),$height1,$land_e,$port_e);

    //            脚注
    $text1 = $text . "第：" . $page_num . "页" . "共 2 页";
    $file=explode(".", $filearr[$item+2]);
    $file=$file[0];
    $images=$file;
    cptext(1000,10,$text1, $images);
    $pdfd->image("image/$images.png",2 * 3 + $lmargin,  $port_e -  13);


    $outfilename = substr($bh, -6)."-1.pdf";

    outputpdf("../server/files/".$outfilename, $pdfd);

//  ------------------ 内页 --------------------
    $pdfd=new fpdi();
    $pdfd->SetMargins(0,0,0);
    $pdfd->SetAutoPageBreak(1,1);
    $pdfd->AddGBFont();
    $pdfd->Open();
    $pdfd->SetFont('GB','B',8);
    $pdfd->AliasNbPages();

    $pagecount = $pdfd->setSourceFile($pdffilename1);  //源文件pdf


//    内页第一页
    $tplidx_3 = $pdfd->ImportPage(3);

    $size_inner = $pdfd -> getTemplateSize($tplidx_3);

    $width_inner = $size_inner['w'];
    $height_inner = $size_inner['h'];

    //   首尾合并 放一个版里面 横版能拼多少
    $x1 = floor($zzg / ($width_inner * 2)); //横的几个
    $y1 = floor($zzk / $height_inner); //竖的几个
    $n1 = $x1 * $y1;

    //    竖版能拼多少
    $x2 = floor($zzk / ($width_inner * 2)); //列数
    $y2 = floor($zzg / $height_inner); //行数
    $n2 = $x2 * $y2;

    if($n1 > $n2){//横拼

        $layout = 'L';

        $x = $x1;
        $y = $y1;
        $n = $n1;

        $land_e = $psizeg;
        $port_e = $psizek;

        //    内页胶装骑马钉拼法 只拼两页
        if($is_binding == true){

            $x = 1;
            $y = 1;
            $n = 1;
        }
        $lmargin = ($psizeg - $x * ($width_inner * 2))/2;
        $tmargin = ($psizek - $y * $height_inner )/2;

    }else{//        竖拼

        $layout = 'P';

        $x = $x2;
        $y = $y2;
        $n = $n2;

        $land_e = $psizek;
        $port_e = $psizeg;

        //    内页胶装骑马钉拼法 只拼两页
        if($is_binding == true){

            $x = 1;
            $y = 1;
            $n = 1;
        }
        $lmargin = ($psizek - $x * ($width_inner * 2))/2;
        $tmargin = ($psizeg - $y * $height_inner)/2;
    }


    //        内页版数 (减去封面)
    $pnum_2 = ceil(($pagecount -4) / ($n * 2));

    for($p = 0; $p < $pnum_2 / 2 ; $p ++){

        $pdfd -> addPage($layout , array($psizeg , $psizek));

//    ------- 正面
        for($item= $p * $n; $item < $p * $n + $n; $item++ ){

            if($item > ($pagecount - 4) / 4 - 1 ){ //拼完结束
                break;
            }

            $page_left = $pagecount - 2 * ($item + 1); // (12) 10 8
            $page_right = 2 * $item + 3;  // (1) 3 5

            $tplidx_left = $pdfd -> importPage($page_left);
            $tplidx_right = $pdfd -> importPage($page_right);

            //            当前行数和列数
            $h = floor(($item - $p * $n) / $x);
            $l = ($item - $p * $n) % $x;

            $pdfd->useTemplate($tplidx_left ,$lmargin + $l * ($width_inner * 2) ,$tmargin + $h * $height_inner ,$width_inner,$height_inner); //3 封面内页

            $pdfd->useTemplate($tplidx_right ,$lmargin + $l * ($width_inner * 2) + $width_inner ,$tmargin + $h * $height_inner ,$width_inner,$height_inner);// 4 封底内页

        }

//        绞线
        draw_line($pdfd,$y,$x,$cx,$tmargin,$lmargin,($width_inner * 2),$height_inner,$land_e,$port_e);

        //            脚注
        $text1 = $text . "第：" . ($p + 1) . "页" . "共 " . $pnum_2 . " 页";
        $file=explode(".", $filearr[$item+2]);
        $file=$file[0];
        $images=$file;
        cptext(1000,10,$text1, $images);
        $pdfd->image("image/$images.png",2 * 3 + $lmargin,  $port_e -  13);


        $pdfd -> addPage($layout , array($psizeg , $psizek));

        // ------- 反面
        for($item= $p * $n; $item < $p * $n + $n; $item++){

            if($item > ($pagecount - 4) / 4 - 1 ){
                break;
            }

            $page_left = 2 * $item + 4; // (2) 4 6
            $page_right = $pagecount - $item * 2 - 3; // (11) 9 7

            $tplidx_left = $pdfd -> importPage($page_left);
            $tplidx_right = $pdfd -> importPage($page_right);

            //            当前行数和列数 列数是关键！关键！关键！重要的事情说三遍
            $h = floor(($item - $p * $n) / $x);
            $l = $x - 1 - ($item - $p * $n) % $x;

            $pdfd->useTemplate($tplidx_left ,$lmargin + $l * ($width_inner * 2) ,$tmargin + $h * $height_inner ,$width_inner,$height_inner); //3 封面内页

            $pdfd->useTemplate($tplidx_right ,$lmargin + $l * ($width_inner * 2) + $width_inner ,$tmargin + $h * $height_inner ,$width_inner,$height_inner);// 4 封底内页

        }


//        绞线
        draw_line($pdfd,$y,$x,$cx,$tmargin,$lmargin,($width_inner * 2),$height_inner,$land_e,$port_e);

        //            脚注
        $text1 = $text . "第：" . ($p + 1) . "页" . "共 " . $pnum_2  . " 页";
        $file=explode(".", $filearr[$item+2]);
        $file=$file[0];
        $images=$file;
        cptext(1000,10,$text1, $images);
        $pdfd->image("image/$images.png",2 * 3 + $lmargin,  $port_e -  13);

    }


    $outfilename = substr($bh, -6)."-2.pdf";

    outputpdf("../server/files/".$outfilename, $pdfd);

    //            database修改明细
    savemx($layout , $pnum_1 ,$pnum_2 ,$bh , $conn);
    return 'done';
}


//书本胶装拼版
function book_binding_pb($inPutDir,$bh,  $text ,$machine ,$cx, $conn){

    $pdfd=new fpdi();
    $pdfd->SetMargins(0,0,0);
    $pdfd->SetAutoPageBreak(1,1);
    $pdfd->AddGBFont();
    $pdfd->Open();
    $pdfd->SetFont('GB','B',8);
    $pdfd->AliasNbPages();

    if($machine == 'Hp彩色'){

        //打印幅面
        $zzk=310;$zzg=450;
        //        page size
        $psizek = 320;$psizeg = 464;

    }elseif($machine == 'Hp10000彩色'){

        //打印幅面 740*510
        $zzk=510;$zzg=740;
        //        page size
        $psizek = 530;$psizeg = 750;
    }else{

        //打印幅面 740*510
        $zzk=310;$zzg=450;
        //        page size
        $psizek = 320;$psizeg = 464;
    }

//    $cx=3;

    $filearr = scandir($inPutDir);

    $pdffilename1 = $inPutDir.$filearr[2];


//        胶装去掉封面出血
    $type = 'cover';
    del_bleed( $pdffilename1 , $cx,$type );

    $pagecount = $pdfd->setSourceFile($pdffilename1);  //源文件pdf

    //    页数是4的倍数must
    if($pagecount % 4 <> 0 || $pagecount <=4){

        return $pagecount;
    }


// -------------------------- 封面 ----------------------
//    封面
    $tplidx = $pdfd->ImportPage(1);

    $size = $pdfd -> getTemplateSize($tplidx);

    $width1 = $size['w'];
    $height1 = $size['h'];

//    封底
    $tplidx2 = $pdfd -> ImportPage($pagecount);

    $size2 = $pdfd -> getTemplateSize($tplidx2);

    $width2 = $size2['w'];
    $height2 = $size2['h'];

//    封面内页

    $tplidx3 = $pdfd -> ImportPage(2);

//    封底内页
    $tplidx4 = $pdfd -> ImportPage($pagecount-1);

    //   封面封底合并 放一个版里面 横版能拼多少
    $x1 = floor($zzg / ($width1 + $width2)); //横的几个
    $y1 = floor($zzk / $height1); //竖的几个
    $n1 = $x1 * $y1;

    //    竖版能拼多少
    $x2 = floor($zzk / ($width1 + $width2)); //列数
    $y2 = floor($zzg / $height1); //行数
    $n2 = $x2 * $y2;

    if($n1 > $n2){ //横拼

        $layout = 'L';

        $lmargin = ($psizeg - $x1 * ($width1 + $width2))/2;
        $tmargin = ($psizek - $y1 * $height1 )/2;

        $x = $x1;
        $y = $y1;
        $n = $n1;

        $land_e = $psizeg;
        $port_e = $psizek;

    }else{//        竖拼

        $layout = 'P';

        $lmargin = ($psizek - $x2 * ($width1 + $width2))/2;
        $tmargin = ($psizeg - $y2 * $height1)/2;

        $x = $x2;
        $y = $y2;
        $n = $n2;

        $land_e = $psizek;
        $port_e = $psizeg;
    }

    $page_num = 1;
    $pnum_1 = 2; //封面p数

//    正面 - 封面封底

    $pdfd -> addPage($layout , array($psizeg , $psizek)); //new page

    for($item = 0; $item <$n; $item ++){

        //            当前行数和列数
        $a = $item ;
        $h = floor($a / $x);
        $l = $a % $x;

//        文件高度以封面为准
        $pdfd->useTemplate($tplidx2 ,$lmargin + $l * ($width2 + $width1) ,$tmargin + $h * $height1 ,$width2,$height1); //2 封底

        $pdfd->useTemplate($tplidx ,$lmargin + $l * ($width2 + $width1) + $width2 ,$tmargin + $h * $height1 ,$width1,$height1); //1 封面

        draw_line($pdfd,$y,$x,$cx,$tmargin,$lmargin,($width1 + $width2),$height1,$land_e,$port_e);

    }

//    反面 - 封面封底内页
    $pdfd -> addPage($layout , array($psizeg , $psizek));

    for($h = 0; $h < $y; $h++){

        for($l = $x-1; $l >= 0; $l --){

//        文件高度以封面为准

            $pdfd->useTemplate($tplidx4 ,$lmargin + $l * ($width2 + $width1) + $width2 ,$tmargin + $h * $height1 ,$width2,$height1);// 4 封底内页

            $pdfd->useTemplate($tplidx3 ,$lmargin + $l * ($width2 + $width1) ,$tmargin + $h * $height1 ,$width1,$height1); //3 封面内页

        }

    }

//        绞线
    draw_line($pdfd,$y,$x,$cx,$tmargin,$lmargin,($width1 + $width2),$height1,$land_e,$port_e);

    //            脚注
    $text1 = $text . "第：" . $page_num . "页" . "共 2 页";
    $file=explode(".", $filearr[$item+2]);
    $file=$file[0];
    $images=$file;
    cptext(1000,10,$text1, $images);
    $pdfd->image("image/$images.png",2 * 3 + $lmargin,  $port_e -  13);


    $outfilename = substr($bh, -6)."-1.pdf";

    outputpdf("../server/files/".$outfilename, $pdfd);

//  ------------------ 内页 --------------------
    $pdfd=new fpdi();
    $pdfd->SetMargins(0,0,0);
    $pdfd->SetAutoPageBreak(1,1);
    $pdfd->AddGBFont();
    $pdfd->Open();
    $pdfd->SetFont('GB','B',8);
    $pdfd->AliasNbPages();

    $pagecount = $pdfd->setSourceFile($pdffilename1);  //源文件pdf

//    内页第一页 按照单张拼版的方式
    $tplidx_3 = $pdfd->ImportPage(3);

    $size_inner = $pdfd -> getTemplateSize($tplidx_3);

    $width_inner = $size_inner['w'];
    $height_inner = $size_inner['h'];

    //   首尾合并 放一个版里面 横版能拼多少
    $x1 = floor($zzg / $width_inner ); //横的几个
    $y1 = floor($zzk / $height_inner); //竖的几个
    $n1 = $x1 * $y1;

    //    竖版能拼多少
    $x2 = floor($zzk / $width_inner ); //列数
    $y2 = floor($zzg / $height_inner); //行数
    $n2 = $x2 * $y2;

    if($n1 > $n2){//横拼

        $layout = 'L';

        $x = $x1;
        $y = $y1;
        $n = $n1;

        $land_e = $psizeg;
        $port_e = $psizek;

        $lmargin = ($psizeg - $x * $width_inner )/2;
        $tmargin = ($psizek - $y * $height_inner )/2;

    }else{//        竖拼

        $layout = 'P';

        $x = $x2;
        $y = $y2;
        $n = $n2;

        $land_e = $psizek;
        $port_e = $psizeg;

        $lmargin = ($psizek - $x * $width_inner )/2;
        $tmargin = ($psizeg - $y * $height_inner)/2;
    }

    //        内页版数 (减去封面)
    $pnum_2 = ceil(($pagecount - 4) / $n );

//    数量等于每个版面拼的个数时 用单拼 (暂时不做)

//    多拼

    for($p = 0; $p < $pnum_2; $p++){
//           正面
        $pdfd -> addPage($layout,array($psizeg, $psizek));//生成新页

        for($item= $p * $n; $item < $p * $n + $n; $item ++){

            if(2 * $item + 3 > ($pagecount - 2) - 1 ){ //pdf拼完结束
                break;
            }

            $tplidx = $pdfd->ImportPage(2 * $item + 3);

//            当前行数和列数
            $a = $item - $p * $n;
            $h = floor($a / $x);
            $l = $a % $x ;

            $pdfd->useTemplate($tplidx,$lmargin + $l * $width_inner ,$tmargin + $h * $height_inner ,$width_inner,$height_inner);

        }

//            绞线
        draw_line($pdfd,$y,$x,$cx,$tmargin,$lmargin,$width_inner,$height_inner,$land_e,$port_e);


//            脚注
        $text1 = $text . "第：" . ($p + 1) . "页" . "共：" . $pnum_2 . "页";

        $file=explode(".", $filearr[2]);
        $file=$file[0];
        $images=$file;
        cptext(1000,10,$text1, $images);
        $pdfd->image("image/$images.png",2 * 3 + $lmargin,  $port_e -  13);

//           反面
        $pdfd -> addPage($layout,array($psizeg, $psizek));//生成新页

        for($item= $p * $n; $item < $p * $n + $n; $item ++){

            if(2 * $item + 4 > ($pagecount - 2) ){ //pdf拼完结束
                break;
            }

            $tplidx = $pdfd->ImportPage(2 * $item + 4);

//            当前行数和列数 列数是关键！关键！关键！重要的事情说三遍
            $a = $item - $p * $n;
            $h = floor($a / $x);
            $l = $x - 1 - $a % $x ;

            $pdfd->useTemplate($tplidx,$lmargin + $l * $width_inner ,$tmargin + $h * $height_inner ,$width_inner,$height_inner);

        }

//            绞线
        draw_line($pdfd,$y,$x,$cx,$tmargin,$lmargin,$width_inner,$height_inner,$land_e,$port_e);

//            脚注

        $text1 = $text . "第：" . ($p + 1) . "页" . "共：" . $pnum_2 . "页";

        $file=explode(".", $filearr[2]);
        $file=$file[0];
        $images=$file;
        cptext(1000,10,$text1, $images);

        $pdfd->image("image/$images.png",2 * 3 + $lmargin, $port_e -  13);

    }

    $outfilename = substr($bh, -6)."-2.pdf";

    outputpdf("../server/files/".$outfilename, $pdfd);

    //            database修改明细
    savemx($layout , $pnum_1 ,$pnum_2 ,$bh , $conn);
    return 'done';

}



//    去出血
function del_bleed($pdffilename1  , $cx, $type){

    $pdfd1=new fpdi();
    $pdfd1->SetMargins(0,0,0);
    $pdfd1->SetAutoPageBreak(1,1);
    $pdfd1->AddGBFont();
    $pdfd1->Open();
    $pdfd1->SetFont('GB','B',8);
    $pdfd1->AliasNbPages();


    $pagecount = $pdfd1->setSourceFile($pdffilename1);  //源文件pdf


//    去掉奇数页左边的出血 偶数页右边的出血

    if($type == 'all'){

        for($i = 1; $i<=$pagecount; $i++){

            $tplidx = $pdfd1 -> ImportPage($i);

            $size = $pdfd1 ->getTemplateSize($tplidx);

            if($i % 2 <> 0){

                if($size['w'] > $size['h']){

                    $hzx = 'L';
                }else{
                    $hzx = 'P';
                }
                $pdfd1->AddPage($hzx , array($size['w'] - $cx ,$size['h']));   //纸张大小

                $pdfd1->useTemplate($tplidx, 0 - $cx,0,$size['w'],$size['h']);

            }else{

                if($size['w'] > $size['h']){

                    $hzx = 'L';
                }else{
                    $hzx = 'P';
                }
                $pdfd1->AddPage($hzx , array($size['w'] - $cx ,$size['h'] ));   //纸张大小

                $pdfd1->useTemplate($tplidx,0,0,$size['w'],$size['h']);

            }

        }

    }elseif($type == 'cover'){

        for($i = 1; $i<=$pagecount; $i++){

            $tplidx = $pdfd1 -> ImportPage($i); //i

            $tplidx1 = $pdfd1 -> ImportPage(1);
            $tplidx3 = $pdfd1 -> ImportPage($pagecount -1);

            $size = $pdfd1 ->getTemplateSize($tplidx);//i

            $size1 = $pdfd1 ->getTemplateSize($tplidx1);
            $size3 = $pdfd1 ->getTemplateSize($tplidx3);

            if($i == 1 || $i == $pagecount - 1){

                if($size1['w'] > $size1['h']){

                    $hzx = 'L';
                }else{
                    $hzx = 'P';
                }
                $pdfd1->AddPage($hzx , array($size1['w'] - $cx ,$size1['h']));   //纸张大小

                $pdfd1->useTemplate($tplidx, 0 - $cx,0,$size1['w'],$size1['h']);

            }elseif($i == 2 || $i == $pagecount){

                if($size3['w'] > $size3['h']){

                    $hzx = 'L';
                }else{
                    $hzx = 'P';
                }
                $pdfd1->AddPage($hzx , array($size3['w'] - $cx ,$size3['h'] ));   //纸张大小

                $pdfd1->useTemplate($tplidx,0,0,$size3['w'],$size3['h']);

            }else{

                if($size['w'] > $size['h']){

                    $hzx = 'L';
                }else{
                    $hzx = 'P';
                }
                $pdfd1->AddPage($hzx , array($size['w'] ,$size['h']));   //纸张大小

                $pdfd1->useTemplate($tplidx, 0 ,0,$size['w'],$size['h']);
            }

        }

    }

    outputpdf($pdffilename1 , $pdfd1);

}

//line
function draw_line($pdfd,$y1,$x1,$cx,$tmargin,$lmargin,$width,$height,$land_e,$port_e){

    for($h =0;$h<$y1;$h++){ //行遍历

//                每个图片上下边纵坐标
        $top_y = $tmargin + $h * $height;
        $bottom_y = $tmargin + $h * $height +$height;

//                左边横线
        $pdfd->line($lmargin -3, $top_y , $lmargin ,  $top_y ); //上出血横线
        $pdfd->line($lmargin -3, $top_y + $cx , $lmargin , $top_y + $cx); //上成品横线
        $pdfd->line($lmargin -3, $bottom_y , $lmargin , $bottom_y ); //下出血横线
        $pdfd->line($lmargin-3, $bottom_y - $cx , $lmargin , $bottom_y - $cx ); //下成品横线

//                右边横线
        $pdfd->line($land_e - $lmargin +3, $top_y , $land_e - $lmargin ,  $top_y); //上出血横线
        $pdfd->line($land_e - $lmargin +3, $top_y + $cx  ,  $land_e - $lmargin , $top_y + $cx ); //上成品横线
        $pdfd->line($land_e - $lmargin +3, $bottom_y  ,  $land_e - $lmargin , $bottom_y  ); //下出血横线
        $pdfd->line($land_e - $lmargin+3 , $bottom_y - $cx ,  $land_e - $lmargin , $bottom_y - $cx ); //下成品横线
    }

    for($l = 0 ;$l <$x1;$l++){ //列遍历

//               每个图片的左右边横坐标
        $left_x = $lmargin + $l *  $width ;
        $right_x = $lmargin + $l * $width + $width;

//              上边竖线
        $pdfd->line($left_x  , $tmargin -3 , $left_x ,  $tmargin ); // 左出血竖线
        $pdfd->line($left_x +$cx, $tmargin -3, $left_x +$cx,  $tmargin ); // 左成品竖线
        $pdfd->line($right_x , $tmargin-3, $right_x ,  $tmargin ); // 右出血竖线
        $pdfd->line($right_x-$cx , $tmargin -3, $right_x-$cx ,  $tmargin ); // 右成品竖线

//                下边竖线
        $pdfd->line($left_x  , $port_e - $tmargin +3 , $left_x , $port_e -  $tmargin); // 左出血竖线
        $pdfd->line($left_x + $cx,$port_e - $tmargin +3 , $left_x +$cx, $port_e -  $tmargin); // 左成品竖线
        $pdfd->line($right_x , $port_e - $tmargin+3  , $right_x, $port_e -  $tmargin); // 右出血竖线
        $pdfd->line($right_x -$cx, $port_e - $tmargin +3, $right_x -$cx, $port_e -  $tmargin); // 右成品竖线


    }
}


//            database修改明细
function savemx($layout,$page_num1 , $page_num2,$bh , $conn){

    if($layout == 'L'){
        $hzx1 = '横向';
    }else{
        $hzx1 = '纵向';
    }
    mysql_query("update order_mxqt set hzx1='$hzx1' , pnum1= $page_num1 , pnum2 = $page_num2 where ddh= $bh ",$conn);
}

//文件名排序
function maopao($arr){

    for($i = 2; $i < count($arr); $i++){

        for($j = $i+1; $j < count($arr); $j ++){

            if(explode('.' , $arr[$i])[0] > explode('.' , $arr[$j])[0]){

                $temp = $arr[$j];
                $arr[$j] = $arr[$i];
                $arr[$i] = $temp;

            }
        }
    }
    return $arr;
}
?>
