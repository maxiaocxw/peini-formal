<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:73:"E:\Project\peini-formal\public/../application/admin\view\admin\index.html";i:1553478215;}*/ ?>
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
        <span class="layui-breadcrumb">
        <a href="">首页</a>
        <a href="">管理员管理</a>
        <a>
          <cite>管理员列表</cite></a>
      </span>
        <a class="layui-btn layui-btn-sm" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i></a>
    </div>
    <div class="weadmin-body">
        <div class="layui-row">
            <form class="layui-form layui-col-md12 we-search" action="<?php echo url('admin/admin/index'); ?>" method="get">
                <div class="layui-inline">
                    <input class="layui-input" placeholder="开始日" name="start" id="start" autocomplete="off" <?php if((isset($start))): ?>value="<?php echo $start; ?>" <?php endif; ?>> </div> <div class="layui-inline">
                    <input class="layui-input" placeholder="截止日" name="end" id="end" autocomplete="off" <?php if((isset($end))): ?>value="<?php echo $end; ?>" <?php endif; ?>> </div> <div class="layui-inline">
                    <input type="text" name="username" placeholder="请输入用户名" autocomplete="off" class="layui-input" <?php if((isset($username))): ?>value="<?php echo $username; ?>" <?php endif; ?>> </div> <button class="layui-btn" lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
            </form>
        </div>
        <div class="weadmin-block">
            <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>
            <a href="<?php echo url('admin/admin/add'); ?>"><button class="layui-btn admin_add"><i class="layui-icon"></i>添加</button></a>
            <span class="fr" style="line-height:40px">共有数据：<?php echo $total; ?> 条</span>
        </div>
        <table class="layui-table">
            <thead>
                <tr>
                    <th>
                        <div class="layui-unselect header layui-form-checkbox" id="c_all" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div>
                    </th>
                    <th>ID</th>
                    <th>昵称</th>
                    <th>账号</th>
                    <th>角色</th>
                    <th>加入时间</th>
                    <th>状态</th>
                    <th>操作</th>
            </thead>
            <tbody>
                <?php foreach($list as $key => $value): ?>
                <tr>
                    <td>
                        <div class="layui-unselect layui-form-checkbox" id="admin_id" lay-skin="primary" data-id="<?php echo $value['id']; ?>"><i class="layui-icon">&#xe605;</i></div>
                    </td>
                    <td><?php echo $value['id']; ?></td>
                    <td><?php echo $value['user_name']; ?></td>
                    <td><?php echo $value['user_account']; ?></td>
                    <td>超级管理员</td>
                    <td>
                        <?=date('Y-m-d H:i:s',$value['inputtime'])?>
                    </td>
                    <td class="td-status">
                        <?php if(($value['status'] == 1)): ?>
                        <span class="layui-btn layui-btn-normal layui-btn-xs">已启用</span></td>
                    <?php else: ?>
                    <span class="layui-btn layui-btn-danger layui-btn-xs">已停用</span></td>
                    <?php endif; ?>
                    <td class="td-manage">
                        <?php if(($value['status'] == 1)): ?>
                        <a onclick="updateAdmin(<?php echo $value['id']; ?>,2)" href="javascript:;" title="禁用"><button class="layui-btn layui-btn-danger layui-btn-sm">禁用</button></a>
                        <?php else: ?>
                        <a onclick="updateAdmin(<?php echo $value['id']; ?>,1)" href="javascript:;" title="启用"><button class="layui-btn layui-btn-normal layui-btn-sm">启用</button></a>
                        <?php endif; ?>
                        <a title="删除" onclick="delAdmin(<?php echo $value['id']; ?>)" href="javascript:;"><button class="layui-btn layui-btn-sm">删除</button></a>
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
    layui.use(['layer', 'laydate','form'], function() {
        var laydate = layui.laydate;
        var form = layui.form;
        //执行一个laydate实例
        laydate.render({
            elem: '#start' //指定元素
        });
        laydate.render({
            elem: '#end' //指定元素
        });
    });
    function delAll(){
        var data = tableCheck.getData();
        layer.confirm('确认要删除吗？',function(index){
            $.post("<?php echo url('admin/admin/delAll'); ?>",{id:data,table:'admin'},function(response){
                layer.msg(response.msg,{time:1000,icon:response.icon},function(){
                    location.reload();
                })
            },'json');
        });
    }

    function delAdmin(id){
        $.post('/admin/admin/delAdmin',{id:id},function(data){
            layer.msg(data.msg,{icon:data.icon,time:1000},function(){
                location.reload();
            })
        },'json');
    }

    function updateAdmin(id, statu) {
        $.post("<?php echo url('update'); ?>", { id: id, status: statu }, function(data) {
            layer.msg(data.msg, { time: 1000 }, function() {
                location.reload();
            })
        }, 'json');
    }

    


    </script>
</body>

</html>