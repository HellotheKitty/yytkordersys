<?
require "../inc/conn.php";

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>print price manage</title>
    <style type="text/css">
        ul,li{
            margin:0;
            padding:0;
        }
        .ptype_box{
            display: block;
        }
        .ptype_box li{
            display: inline-block;
            zoom: 1;
            float: left;
            border-top:1px solid #aaa;
            border-bottom:1px solid #aaa;
            border-right:1px solid #aaa;
        }
        .ptype_box li a{
            line-height:26px;
            display: block;
            text-decoration: none;
            font-size:14px;
            color:#aaa;
            padding:0 5px;
        }
        .ptype_box .chosen{
            background-color: #aaa;
            color: #fff;
        }
        .ptype_box .first-box{
            border-left:1px solid #777;
        }
        .clearfix{
            overflow: hidden;
            zoom: 1;
        }
    </style>
</head>
<body>
<div>
    <div>
        <h3>打印价格管理</h3>

        <ul class="ptype_box clearfix">
            <li class="first-box"><a href="" class="chosen">门市价</a></li>
            <li><a href="">会员价</a></li>
            <li><a href="">协议价</a></li>
        </ul>
    </div>
    <div>
        <h5>查询</h5>
        <select>
            <option>请选择机型</option>
        </select>
        <select>
            <option>请选择纸张</option>
        </select>
        <select>
            <option>请选择会员级别</option>
        </select>
        <span>请输入客户名</span>
        <input type="text" name=""/>
    </div>
    <div>
        <h5>添加</h5>
        <input type="button" value="excel导入"/>
    </div>

    <p>门市价</p>
    <table>
        <thead>
        <tr>
            <th>id</th>
            <th>单双面</th>
            <th>机型</th>
            <th>纸张</th>
            <th>尺寸</th>
            <th>单位</th>
            <th>数量下限</th>
            <th>数量上限</th>
            <th>价格</th>
        </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
    <p>会员价</p>
    <table>
        <thead>
        <tr>
            <th>id</th>
            <th>单双面</th>
            <th>机型</th>
            <th>纸张</th>
            <th>尺寸</th>
            <th>单位</th>
            <th>会员级别</th>
            <th>最低预存款</th>
            <th>价格</th>
        </tr>
        </thead>
    </table>
</div>
</body>
</html>
