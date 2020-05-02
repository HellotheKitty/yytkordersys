<?

    $p = $_GET['page'] ? $_GET['page'] :1;
    $curpage = $p;
    $pagenum = 50;

    $totalpage = ceil($rowcount/$pagenum); //总页数
    $showpage = 5; //显示页数
    $offsetpage = ($showpage-1)/2;
    $startpage = $curpage>$offsetpage ? $curpage-$offsetpage : 1; //起始页码
    $endpage = $totalpage>$curpage+$offsetpage ? $curpage+$offsetpage : $totalpage;//结尾页码

$startrow = ($p-1)*$pagenum;
//    $newslist = $news -> limit(($p-1)*$pagenum,$pagenum) ->select();


?>