<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:53:"D:\pei\public/../application/admin\view\gift\add.html";i:1562318748;}*/ ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>管理员列表-WeAdmin Frame型后台管理系统-WeAdmin 1.0</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="/static/admin/static/css/font.css">
    <link rel="stylesheet" href="/static/admin/static/css/weadmin.css">
</head>

<body>
<div class="weadmin-nav">
     <span class="">
     <a href="">首页</a>/<a href="">礼物管理</a>/<a><cite>添加礼物</cite></a>
     </span>
    <a class="layui-btn layui-btn-sm" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i></a>
</div>
<form action="/admin/gift/addDo" method="post">

    <div class="weadmin-body">
        <div class="layui-form-item">
            <label for="name" class="layui-form-label">
                <span class="we-red">*</span>礼物名称
            </label>
            <div class="layui-input-inline">
                <input type="text" id="name" name="name" required="" lay-verify="required" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="name" class="layui-form-label">
                <span class="we-red">*</span>价格
            </label>
            <div class="layui-input-inline">
                <input type="text" id="price" name="price" required="" lay-verify="required" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label for="language" class="layui-form-label">
                <span class="we-red">*</span>游戏图片
            </label>

            <input type="file" name="img" class="layui-btn">
        </div>
        <div class="layui-form-item">
            <label for="name" class="layui-form-label">
                <span class="we-red">*</span>排序
            </label>
            <div class="layui-input-inline">
                <input type="text" id="link" name="order" required="" lay-verify="required" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="is_hot" class="layui-form-label">
                <span class="we-red">*</span>状态
            </label>
            <div class="layui-input-block">
                <input type="radio" name="status" lay-filter="type" value="1" title="正常">正常
                <input type="radio" name="status" lay-filter="type" value="2" title="禁用" checked="checked">禁用
            </div>
        </div>
        <div class="layui-form-item">
            <label for="L_repass" class="layui-form-label">
            </label>
            <input type="submit" value="添加" class="layui-btn">
        </div>


    </div>

</form>



<script src="/static/admin/static/js/jquery.js" charset="utf-8"></script>
<script src="/static/admin/lib/layui/layui.js" charset="utf-8"></script>
<script src="/static/admin/static/js/weadmin.js" charset="utf-8"></script>
<script type="text/javascript">
    layui.use(['form','layer', 'laydate'], function() {
        var form = layui.form;
        var laydate = layui.laydate;
        //执行一个laydate实例
        laydate.render({
            elem: '#start' //指定元素
        });
        laydate.render({
            elem: '#end' //指定元素
        });

        //监听提交
        form.on('submit(add)', function(data) {
            $.post("<?php echo url('addDo'); ?>", data.field, function(response) {
                layer.msg(response.msg,{time:1000,icon:response.icon},function(){
                    location.reload();
                })
            }, 'json');
            //发异步，把数据提交给php

            return false;
        });
    });

    // 批量删除
    function delAlltags(){
        var data = tableCheck.getData();
        layer.confirm('确认要删除吗？',function(index){
            $.post("<?php echo url('admin/banner/delrightBanner'); ?>",{id:data},function(response){
                layer.msg(response.msg,{time:1000,icon:response.icon},function(){
                    location.reload();
                })
            },'json');
        });
    }
    // 单个删除
    function delBanner(id){
        layer.confirm('确认要删除吗？',function(index){
            $.post('/admin/banner/delrightBanner',{id:id},function(data){
                layer.msg(data.msg,{icon:data.icon,time:1000},function(){
                    location.reload();
                })
            },'json');
        });
    }
</script>
</body>

</html>