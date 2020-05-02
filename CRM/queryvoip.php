<?php
/*
 *  Copyright (c) 2013 The CCP project authors. All Rights Reserved.
 *
 *  Use of this source code is governed by a Beijing Speedtong Information Technology Co.,Ltd license
 *  that can be found in the LICENSE file in the root of the web site.
 *
 *   http://www.cloopen.com
 *
 *  An additional intellectual property rights grant can be found
 *  in the file PATENTS.  All contributing project authors may
 *  be found in the AUTHORS file in the root of the source tree.
 */
require("../inc/conn.php"); 

 if($_SERVER["REQUEST_METHOD"]=="POST")
{  
  //获取POST数据
  $result = file_get_contents("php://input");
  //解析XML
  $xml = simplexml_load_string(trim($result," \t\n\r"));
  //获取XML数据
  $action = $xml->action;
  $id = $xml->id;
  $type = $xml->type;
  $strXML="";
  //ID判断
  mysql_query("insert into call_token_log values (0,'$id',now())",$conn);
  $rs=mysql_query("select * from call_token where token='$id'",$conn);
  if (mysql_num_rows($rs)>0){
     $strXML="<?xml version='1.0' encoding='utf-8'?>
              <Response>
              <dname>".mysql_result($rs,0,"nickname")."</dname>        
              <voipid>".mysql_result($rs,0,"voipzh")."</voipid>
              <voippwd>".mysql_result($rs,0,"voipmm")."</voippwd>
              <hash>$id</hash>
              </Response>";    
  }
  echo $strXML; 
}else{
  //兼容旧版本GET-json方式
  $type = $_REQUEST["type"];    
  $id = $_REQUEST["id"];   
  $strJson="";
  // 此处可根据$id查询对应的voip信息，进行json组包。
  
  //json示例 
  $rs=mysql_query("select * from call_token where token='$id'");  
  if (mysql_num_rows($rs)>0){      
     $strJson="{\"dname\":\"".mysql_result($rs,0,"nickname")."\",\"voipid\":\"".mysql_result($rs,0,"voipzh")."\",\"voippwd\":\"".mysql_result($rs,0,"voipmm")."\",\"hash\":\"".$id."\"}";    
  }  
  echo $strJson;
}
 ?>