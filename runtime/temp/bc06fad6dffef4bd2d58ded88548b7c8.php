<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:79:"E:\Project\peini-formal\public/../application/admin\view\banner\bannerlist.html";i:1562150096;}*/ ?>
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
        <!-- <span class=""> -->
        <!-- <a href="">首页</a>/<a href="">订单管理</a>/<a><cite>订单列表</cite></a> -->
        <!-- </span> -->
        <a class="layui-btn layui-btn-sm" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i></a>
    </div>
    <div class="weadmin-body">
        <div class="layui-row">
           <form class="layui-form layui-col-md12 we-search" action="<?php echo url('admin/banner/bannerlist'); ?>" method="get">
                
                <div class="layui-inline">
                    <select name="type">
                        <option value="">请选择分类</option>
                        <option value="3">Index</option>
                        <option value="1">Lifestyle</option>
                        <option value="2">Market</option>
                    </select>
                </div>
                <div class="layui-inline">
                    <select name="is_app">
                        <option value="">PC-APP</option>
                        <option value="1">PC</option>
                        <option value="2">APP</option>
                    </select>
                </div>
                <div class="layui-inline">
                    <select name="language">
                        <option value="">语言</option>
                        <option value="1">中文</option>
                        <option value="2">英文</option>
                    </select>
                </div>
                <!-- <div class="layui-inline">
                    <input type="text" name="username" placeholder="请输入用户名" autocomplete="off" class="layui-input" <?php if((isset($username))): ?>value="<?php echo $username; ?>"<?php endif; ?>>
                </div> -->
                <button class="layui-btn" lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
            </form>
        </div>
        <div class="weadmin-block">
            <button class="layui-btn layui-btn-danger" onclick="delAlltags()"><i class="layui-icon"></i>批量删除</button>
            <a class="layui-btn admin_add" href="<?php echo url('addArticleBanner',array('banner'=>1)); ?>"><i class="layui-icon"></i>添加</a>
        </div>
        <table class="layui-table">
            <thead>
                <tr>
                    <th>
                        <div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div>
                    </th>
                    <th>ID</th>
                    <th>封面</th>
                    <th>链接</th>
                    <th>分类</th>
                    <th>PC-APP</th>
                    <th>点击量</th>
                    <th>操作</th>
            </thead>
            <tbody>
                <?php foreach($list as $key => $value): ?>
                <tr>
                    <td>
                        <div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id='<?php echo $value['id']; ?>'><i class="layui-icon">&#xe605;</i></div>
                    </td>
                    <td><?php echo $value['id']; ?></td>
                    <td><img src="<?php echo $value['thumb']; ?>" style="height: 100px;max-width: 360px;"></td>
                    <td><?php echo $value['link']; ?></td>
                    <td>
                        <?php if(($value['type'] == 1)): ?>
                        lifestyle
                        <?php elseif(($value['type'] == 2)): ?>
                        market
                        <?php else: ?>
                        Index
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if(($value['is_app'] == 1)): ?>
                            PC
                        <?php elseif(($value['is_app'] == 2)): ?>
                            APP
                        <?php endif; ?>
                    </td>
                    <td><?php echo $value['read_num']; ?></td>
                    <td class="td-manage">
                        <a title="修改"  href="/admin/banner/banneredit/id/<?php echo $value['id']; ?>"><button class="layui-btn layui-btn-danger layui-btn-sm">修改</button></a>
                        <a title="删除" onclick="delBanner(<?php echo $value['id']; ?>)" href="javascript:;"><button class="layui-btn layui-btn-danger layui-btn-sm">删除</button></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="page">
            <div>
                <?php echo $list->render(); ?>
            </div>
        </div>
    </div>
    
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
            $.post("<?php echo url('update'); ?>", data.field, function(response) {
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
            $.post("<?php echo url('admin/banner/delBanner'); ?>",{id:data},function(response){
                layer.msg(response.msg,{time:1000,icon:response.icon},function(){
                    location.reload();
                })
            },'json');
        });
    }
    // 单个删除
    function delBanner(id){
        layer.confirm('确认要删除吗？',function(index){
        $.post('/admin/banner/delBanner',{id:id},function(data){
            layer.msg(data.msg,{icon:data.icon,time:1000},function(){
                location.reload();
            })
        },'json');
    });
    }
    </script>
</body>

</html>