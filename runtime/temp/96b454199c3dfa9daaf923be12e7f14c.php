<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:90:"D:\phpserver\wwwroot\default\peini-formal\public/../application/admin\view\user\index.html";i:1563279818;}*/ ?>
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
        <a href="">用户管理</a>
        <a>
          <cite>用户管理列表</cite></a>
      </span>
        <a class="layui-btn layui-btn-sm" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i></a>
    </div>
    <div class="weadmin-body">
        <div class="layui-row">
            <form class="layui-form layui-col-md12 we-search" action="<?php echo url('admin/user/index'); ?>" method="get">
                <div class="layui-inline">
                    <input type="text" name="username" placeholder="请输入用户名" autocomplete="off" class="layui-input" <?php if((isset($username))): ?>value="<?php echo $username; ?>" <?php endif; ?>> </div> <button class="layui-btn" lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
            </form>
        </div>
        <div class="weadmin-block">
            <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>
            <span class="fr" style="line-height:40px">共有数据：<?php echo $total; ?> 条</span>
        </div>
        <table class="layui-table">
            <thead>
                <tr>
                    <th>
                        <div class="layui-unselect header layui-form-checkbox" id="c_all" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div>
                    </th>
                    <th>ID</th>
                    <th>编号</th>
                    <th>用户名</th>
                    <th>性别</th>
                    <th>手机号</th>
                    <th>头像</th>
                    <th>城市</th>
                    <th>等级</th>
                    <th>余额</th>
                    <th>注册时间</th>
                    <th>用户类型</th>
                    <th>状态</th>
                    <th>操作</th>
            </thead>
            <tbody>
                <?php foreach($list as $key => $value): ?>
                <tr>
                    <td>
                        <div class="layui-unselect layui-form-checkbox" id="admin_id" lay-skin="primary" data-id="<?php echo $value['uid']; ?>"><i class="layui-icon">&#xe605;</i></div>
                    </td>
                    <td><?php echo $value['uid']; ?></td>
                    <td><?php echo $value['number']; ?></td>
                    <td><?php echo $value['username']; ?></td>
                    <td><?php if($value['sex']==1): ?>男<?php elseif($value['sex']==2): ?>女<?php else: ?>未知<?php endif; ?></td>
                    <td><?php echo $value['mobile']; ?></td>
                    <td><img src="http://cdn.lanyushiting.com/<?php echo $value['headimg']; ?>" style="height: 50px;width: 50px;border-radius:50%;" class="pimg"></td>
                    <td><?php echo $value['city']; ?></td>
                    <td><?php echo $value['level']; ?></td>
                    <td><?php echo $value['currency']; ?></td>
                    <td><?=date('Y-m-d H:i:s',$value['addtime'])?></td>
                    <td class="td-status">
                        <?php if(($value['type'] == 1)): ?>
                        <span class="layui-btn layui-btn-primary layui-btn-xs">用户</span></td>
                        <?php elseif(($value['type'] == 2)): ?>
                            <span class="layui-btn layui-btn layui-btn-xs">达人</span></td>
                        </td>
                        <?php endif; ?>
                    <td class="td-status">
                        <?php if(($value['status'] == 1)): ?>
                        <span class="layui-btn layui-btn-primary layui-btn-xs">正常</span></td>
                        <?php elseif(($value['status'] == 2)): ?>
                            <span class="layui-btn layui-btn layui-btn-xs">禁用</span></td>
                        <?php elseif(($value['status'] == 3)): ?>
                            <span class="layui-btn layui-btn-normal layui-btn-xs">冻结</span></td>
                        <?php endif; ?>
                    <td class="td-manage">
                        <a title="修改"  href="/admin/user/update/uid/<?php echo $value['uid']; ?>"><button class="layui-btn layui-btn-danger layui-btn-sm">修改</button></a>
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