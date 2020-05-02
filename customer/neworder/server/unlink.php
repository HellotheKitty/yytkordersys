<meta charset="utf-8" />
<?
function deldir($dir)
{
   $dh = opendir($dir);
   while ($file = readdir($dh))
   {
      if ($file != "." && $file != "..")
      {
         $fullpath = $dir . "/" . $file;
         if (!is_dir($fullpath))
         {
            unlink($fullpath);
         } else
         {
            deldir($fullpath);
         }
      }
   }
   closedir($dh);
   if (rmdir($dir))
   {
      return true;
   } else
   {
      return false;
   }
}
$text=$_POST['text'];
deldir("../../neworder/function/BillFiles/".$text);
echo  "<script>alert('删!');location.href='../delete.php'</script>";
?>