<?php
/**
 * Created by PhpStorm.
 * User: yikatest
 * Date: 2016/3/21
 * Time: 10:28
 */
header("Content-type:application/vnd.ms-excel;charset=UTF-8");
header("Content-Disposition:filename=".iconv("utf-8","gb2312","财务单导出.xls"));
header("Expires:0");
header('Pragma:   public'   );
header("Cache-control:must-revalidate,post-check=0,pre-check=0");