<? require("../includes/conn.php");?>
<?
session_start();
if ($_SESSION["OK"]<>"OK") { 
echo "<script language=JavaScript>{window.location.href='error.php';}</script>";
exit; 
}?>
<? if ($_GET["ND"]=="") {$nd=date("Y");} else {$nd=$_GET["ND"];} 
$rs=mssql_query("select ysdm,ysmc,ysbm,ysje,convert(varchar(10),yssj,21) yssj,yk_ysxm.bz,dwmc,jfly,mc,sum(je) as je,state from YK_YSXM,dwdm,YK_jfly,YK_yfp where substring(ysdm,3,4)='$nd' and ysbm=dwdm.dwdm and jfly=dm and ysdm*=ysxm group by ysdm,ysmc,ysbm,ysje,yssj,yk_ysxm.bz,dwmc,jfly,mc,state order by ysdm desc");
?>
<html><head><title>浙江省公安厅财务一科综合管理系统</title>
<META http-equiv=Content-Type content="text/html; charset=GBK">
<meta content="MSHTML 6.00.3790.1830" name=GENERATOR>
<style TYPE="text/css">
<!--
A:link{text-decoration:none}
A:visited{text-decoration:none}
A:hover {color: #EF6D21;text-decoration:underline}
.style11 {font-size: 14px}
.STYLE13 {color: #FFFF00}
 -->
</style>
<script language="JavaScript">
<!--
function suredo(src,q)
    {
      var ret;
      ret = confirm(q);
      if(ret!=false) window.location=src;
    }
//-->
</script>
    
<link href="../content.css" rel="stylesheet" type="text/css">
</head>
<body text=#000000 bgColor=#ffffff topMargin=0>
<table cellSpacing=0 cellPadding=0 width="100%" border=0>
  <tbody>
  <tr>
    <td width="57%" height=13 class=guide style="background-image: url('../images/main_guide_bg2.gif')">
	<img src="../images/guide.gif" 
      align=absMiddle>预算项目管理--项目列表 <span class="field">
	  <? $rsnd=mssql_query("select distinct substring(ysdm,3,4) from yk_ysxm order by substring(ysdm,3,4) desc");?>
	<select name="ND" onChange="window.location.href='YSXM_list.php?ND='+this.options[this.selectedIndex].value;">
      <? for($i=0;$i<mssql_num_rows($rsnd);$i++){ ?>
      <option value="<? echo mssql_result($rsnd,$i,0)?>" <? if (mssql_result($rsnd,$i,0)==$nd) {echo "selected";}?>><? echo mssql_result($rsnd,$i,0)?></option>
      <? } ?>
    </select>
	年
	</span></td>
    <td width="43%" align=right class=guide style="background-image: url('../images/main_guide_bg2.gif')">
	<img
  src="../images/main_r.gif"></td>
</tr></tbody></table>
<br>
<? if (substr($_SESSION["GL_QX"],2,2)<>"") {?>
<img src="../images/func_new.gif" width="15" height="17"><a href="#" class="nav" onClick="javascript:window.open('YSXM_dj.php?lx=1', 'HT_dhdj', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=yes,width=500,height=310,left=300,top=100')">新增预算项目</a>　　<img src="../images/func_handle.gif"  width="17" height="15"><a href="#" class="nav" onClick="window.open('ys_excelcsv.htm', 'upload', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=yes,width=310,height=100,left=400,top=300')">导入预算项目</a>　　<img src="../images/func_return.gif"  width="17" height="15"><a href="YSXM_list_excel.php?ND=<? echo $nd?>" target="_blank">导出预算项目</a>
<? }?><br>
<table class="maintable" cellspacing="1" cellpadding="1" width="100%" border="0" height="52">
  <tbody>
    <tr>
      <td align="center" width="89" height="26" class="head" style="background-image: url('../images/nabg1.gif')">项目代码</td>
      <td align="center" class="head" style="background-image: url('../images/nabg1.gif')" width="147">项目名称</td>
      <td align="center" class="head" style="background-image: url('../images/nabg1.gif')" width="90">所属部门</td>
      <td align="center" width="87" class="head" style="background-image: url('../images/nabg1.gif')">预算金额</td>
      <td width="58" align="center" class="head" style="background-image: url('../images/nabg1.gif')">设立时间</td>
      <td width="58" align="center" class="head" style="background-image: url('../images/nabg1.gif')">资金来源</td>
      <td align="center" colspan="2" class="head" style="background-image: url('../images/nabg1.gif')">备注</td>
    </tr>
   <? for($i=0;$i<mssql_num_rows($rs);$i++){?>
    <tr>
      <td width="89" height="26" align="center"><? echo mssql_result($rs,$i,"ysdm")?></td>
      <td width="147"><? if (mssql_result($rs,$i,"state")==1) {echo mssql_result($rs,$i,"ysmc");} else {{echo mssql_result($rs,$i,"ysmc")."<span class=alert>[已停]</span>";}}?>
      </td>
      <td width="90" align="center"><? echo mssql_result($rs,$i,"dwmc")?></td>
      <td align="right"><? echo number_format(mssql_result($rs,$i,"ysje"),2)?></td>
      <td align="center"><? echo mssql_result($rs,$i,"yssj")?></td>
      <td><? echo mssql_result($rs,$i,"mc")?></td>
      <td width="113"><? echo mssql_result($rs,$i,"bz")?></td>
      <td width="53" align="center"><a onClick="javascript:window.open('YSXM_dj.php?BH=<? echo mssql_result($rs,$i,"ysdm")?>&lx=<? if (mssql_result($rs,$i,"je")==0) {echo "1";} else {echo "0";}?>', 'HT_dhdj', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=yes,width=500,height=310,left=300,top=100')"><img src="../images/func_edit.gif" width="15" height="17"  alt="修改"></a>　<? if (mssql_result($rs,$i,"je")==0) {?><a onClick="javascript:suredo('YSXM_del.php?BH=<? echo mssql_result($rs,$i,"ysdm")?>','确定删除?')"><img src="../images/func_delete.gif" width="15" height="17" alt="删除"></a><? }?></td>
    </tr>
     <? $ysje=$ysje+mssql_result($rs,$i,"ysje");
	 } ?>
    <tr>
      <td width="89" height="26" align="center">&nbsp;</td>
      <td width="147">&nbsp;</td>
      <td width="90" align="center">合计：</td>
      <td align="right"><? echo number_format($ysje,2)?></td>
      <td align="center">&nbsp;</td>
      <td>&nbsp;</td>
      <td width="113">&nbsp;</td>
      <td width="53">&nbsp;</td>
    </tr>
          <? if (mssql_num_rows($rs)==0) {?>
    <tr>
      <td colspan="8" align="center" valign="top">暂无项目信息</td>
    </tr>
    <? }?>
  </tbody>
</table>
<table class=maintable cellSpacing=1 cellPadding=1 width="100%" border=0 id="table1" height="28">
  <tr>
    <td height="24" background="../images/nabg1.gif" ><span class="STYLE13">·共<? echo $i-1;?>条记录。</span></TD>
  </tr>
</table>

</body>
</html>
<? 
$rs=null;
unset($rs);
$rsnd=null;
unset($rsnd);
?>