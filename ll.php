<? session_start();

$pp=1;
$url = "http://www.namex.cn/namedia.back/crm/CustomerManage_leader.aspx?Token=e52af1f8209d362c&page=2"; 
$contents = file_get_contents($url); 
$file = fopen("namecc/$pp.htm","w+");
fwrite($file,$contents);
fclose($file);

 
//ob_start();                                                                            //打开输出缓冲区 
//$ch = curl_init();                                                            //初始化会话 
//curl_setopt( $ch, CURLOPT_URL, $url );                        //设定目标URL 
//curl_exec( $ch );                                                                //发送请求 
//$retrievedhtml = ob_get_contents();                                  //返回内部缓冲区的内容 
//ob_end_clean();                          //删除内部缓冲区的内容并关闭内部缓冲区 
//$contents = ob_get_contents(); 
//$file = fopen("namecc/$pp.htm","w+");
//fwrite($file,$contents);
//fclose($file);
//curl_close( $ch );                        //会话结束 
 

//如果出现中文乱码使用下面代码 
//$contents = iconv("gb2312", "utf-8",$contents); 
//$contents=substr($contents,strpos($contents,"05.资讯列表/productStyle/87_列表样式47/pic/list_title_1.gif) no-repeat left center; padding-left:12px;")); 
//$contents=substr($contents,0,strpos($contents,"NewsListFoot_htm_div"));
?> 
