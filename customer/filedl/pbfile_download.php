<?

function addFiletoZip($dir,$zip){

    $handler = opendir($dir);

    while(($filename = readdir($handler))!==false){

        if($filename != '.' && $filename != '..'){

            if(is_dir($dir.'/'.$filename)){
                addFiletoZip($dir.'/'.$filename,$zip);
            }else{
                $zip -> addFile($dir.'/'.$filename);
            }
        }
    }
    @closedir($dir);
}

$ddh= $_GET['ddh'];
$dir = '../neworder/server/upload/'.$ddh;

$outfile = '../neworder/server/upload/'.$ddh.'.zip';
$zip = new ZipArchive();

touch($outfile);
if($zip -> open($outfile , ZipArchive::OVERWRITE === TRUE)){

    addFiletoZip($dir,$zip);
    $zip->close();
}
header("Cache-Control:max-age=0");
header("Content-Description:File Transfer");
header("Content-disposition:attachment;filename=" . basename($outfile));//文件名
header("Content-Type:application/zip");//zip
header("Content-Transfer-Encoding: binary"); //2进制
header("Content-Length" . filesize($outfile));
readfile($outfile);//输出
?>