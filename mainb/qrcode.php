<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
    <title>名片工坊</title>
    <link href="./css/CITICcss.css" rel="stylesheet" type="text/css">
</head>
<?php    
/*
 * PHP QR Code encoder
 *<meta http-equiv="content-type" content="text/html;charset=utf-8"/>
 * Exemplatory usage
 *
 * PHP QR Code is distributed under LGPL 3
 * Copyright (C) 2010 Dominik Dzienia <deltalab at poczta dot fm>
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 */
    
    echo "<h1>PHP QR Code</h1><hr/>";
    
    //set it to writable location, a place for temp generated PNG files
    $PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'temp'.DIRECTORY_SEPARATOR;
    
    //html PNG location prefix
    $PNG_WEB_DIR = 'temp/';

    include "./qrlib/phpqrcode.php";
    
    //ofcourse we need rights to create temp dir
    if (!file_exists($PNG_TEMP_DIR))
        mkdir($PNG_TEMP_DIR);
    
    
    $filename = $PNG_TEMP_DIR.'test.png';
    
    //processing form input
    //remember to sanitize user input in real-life solution !!!
    $errorCorrectionLevel = 'L';
    if (isset($_REQUEST['level']) && in_array($_REQUEST['level'], array('L','M','Q','H')))
        $errorCorrectionLevel = $_REQUEST['level'];    

    $matrixPointSize = 4;
    if (isset($_REQUEST['size']))
        $matrixPointSize = min(max((int)$_REQUEST['size'], 1), 10);
		
	$color = '0,0,0';
	if (isset($_REQUEST['color'])) {
		$color=$_REQUEST['color'];
		$tmp = explode(',',$_REQUEST['color']);
		$red = $tmp[0];
		$green = $tmp[1];
		$blue = $tmp[2];
	}


    if (isset($_REQUEST['data'])) { 
    
        //it's very important!
        if (trim($_REQUEST['data']) == '')
            die('data cannot be empty! <a href="?">back</a>');
            
        // user data
        $filename = $PNG_TEMP_DIR.md5($_REQUEST['data'].'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';
		define('CRLF', "\r\n");
		$text = $_REQUEST['data'];
		//$text = iconv("GBK","utf-8",$text);
		//htmlentities($text);
		//echo $red;
        QRcode::png($text, $filename, $errorCorrectionLevel, $matrixPointSize, 1,false, $red, $green, $blue);    
		//QRcode::png($text, $filename);    
        
    } else {    
    
        //default data
        echo '把需要生成二维码的数据放入输入框，可以是网址、Vcard、其他信息等。';    
        QRcode::png('PHP QR Code :)', $filename, $errorCorrectionLevel, $matrixPointSize, 1,false,0,0,0);    
        
    }    
        
    //display generated file
    echo '<img src="'.$PNG_WEB_DIR.basename($filename).'" /><hr/>';  
    
    //config form
    echo '<form action="qrcode.php" method="post">
        Data:&nbsp;<textarea rows="8" cols="45" name="data" value="'.(isset($_REQUEST['data'])?htmlspecialchars($_REQUEST['data']):'PHP QR Code :)').'" ></textarea>&nbsp;
        ECC校验等级，建议M:&nbsp;<select name="level">
            <option value="L"'.(($errorCorrectionLevel=='L')?' selected':'').'>L - smallest</option>
            <option value="M"'.(($errorCorrectionLevel=='M')?' selected':'').'>M</option>
            <option value="Q"'.(($errorCorrectionLevel=='Q')?' selected':'').'>Q</option>
            <option value="H"'.(($errorCorrectionLevel=='H')?' selected':'').'>H - best</option>
        </select>&nbsp;
        Size尺寸,建议8以上:&nbsp;<select name="size">';
        
    for($i=1;$i<=10;$i++)
        echo '<option value="'.$i.'"'.(($matrixPointSize==$i)?' selected':'').'>'.$i.'</option>';
        
    echo '</select>&nbsp;';
	
	//
	echo 'Color颜色:&nbsp;&nbsp;<input type=text size="15" name=color value="'.$color.'" />';
	
    echo '<input type="submit" value="生成二维码"></form><hr/>';
        
    // benchmark
//	<select name="color">
//		<option value="0,0,0"'.(($color=='0,0,0')?' selected':'').'>black</option>
//		<option value="204,0,0"'.(($color=='204,0,0')?' selected':'').'>red</option>
//		<option value="0,128,0"'.(($color=='0,128,0')?' selected':'').'>green</option>
//		<option value="0,0,255"'.(($color=='0,0,255')?' selected':'').'>blue</option>
//	</select>
    //QRtools::timeBenchmark();    

    echo "数据示例：";
	?><br><br>Vcard 格式：<br><br>
BEGIN:VCARD<br>
VERSION:3.0<br>
TEL;TYPE=CELL,VOICE:13516777880<br>
URL;TYPE=WORK:http://www.skypring.cn<br>
FN:张三<br>
ORG:印艺天空<br>
END:VCARD
<br><br>
网址格式：<br><br>
http://www.skyprint.cn
<label for="textfield"></label>
