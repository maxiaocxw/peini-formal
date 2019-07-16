<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:91:"F:\phpserver\wwwroot\default\peini-formal\public/../application/admin\view\order\index.html";i:1563293877;}*/ ?>
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
        <a href="">订单管理</a>
        <a>
          <cite>下单列表</cite></a>
      </span>
        <a class="layui-btn layui-btn-sm" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i></a>
    </div>
    <div class="weadmin-body">
        
        <!-- <div class="weadmin-block">
            <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>
            <a class="layui-btn admin_add" href="<?php echo url('addTag'); ?>"><i class="layui-icon"></i>添加</a>
            <span class="fr" style="line-height:40px">共有数据：<?php echo $total; ?> 条</span>
        </div> -->
        <table class="layui-table">
            <thead>
                <tr>
                    <!-- <th>
                        <div class="layui-unselect header layui-form-checkbox" id="c_all" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div>
                    </th> -->
                    <th>ID</th>
                    <th>订单编号</th>
                    <th>用户名</th>
                    <th>达人名</th>
                    <th>时长</th>
                    <th>总货币价格</th>
                    <th>游戏名</th>
                    <th>游戏大区</th>
                    <th>下单时间</th>
                    <th>订单状态</th>
                    <th>接单时间</th>
                    <!-- <th>操作</th> -->
            </thead>
            <tbody>
                <?php foreach($data as $key => $value): ?>
                <tr>
                    <!-- <td>
                        <div class="layui-unselect layui-form-checkbox" id="admin_id" lay-skin="primary" data-id="<?php echo $value['id']; ?>"><i class="layui-icon">&#xe605;</i></div>
                    </td> -->
                    <td><?php echo $value['id']; ?></td>
                    <td><?php echo $value['tranno']; ?></td>
                    <td><?php echo $value['uname']; ?></td>
                    <td><?php echo $value['pname']; ?></td>
                    <td><span style="color:#1E9FFF;font-weight:bold;"><?php echo $value['num']; ?></span>小时</td>
                    <td><?php echo $value['amount']; ?></td>
                    <td><?php echo $value['gamename']; ?></td>
                    <td><?php echo $value['regional']; ?></td>
                    <td><?php echo $value['addtime']; ?></td>
                    <td class="td-status">
                    <?php if(($value['status'] == 1)): ?>
                        <span class="layui-btn layui-btn-primary layui-btn-xs">待接单</span></td>
                    <?php elseif(($value['status'] == 2)): ?>
                        <span class="layui-btn layui-btn layui-btn-xs">待完成</span></td>
                    <?php elseif(($value['status'] == 3)): ?>
                        <span class="layui-btn layui-btn-normal layui-btn-xs">已完成</span></td>
                    <?php elseif(($value['status'] == 4)): ?>
                        <span class="layui-btn layui-btn-warm layui-btn-xs">已评价</span></td>
                    <?php elseif(($value['status'] == 5)): ?>
                        <span class="layui-btn layui-btn-danger layui-btn-xs">申诉中</span></td>
                    <?php elseif(($value['status'] == 6)): ?>
                        <span class="layui-btn layui-btn-disabled layui-btn-xs">已取消</span></td>
                    <?php endif; ?>
                    <td><?php echo $value['receivetime']; ?></td>
                    <!-- <td class="td-manage">
                        <a title="修改"  href="/admin/tag/update/id/<?php echo $value['id']; ?>"><button class="layui-btn layui-btn-normal layui-btn-sm">修改</button></a>
                        <a title="删除" onclick="delAdmin(<?php echo $value['id']; ?>)"><button class="layui-btn layui-btn-danger layui-btn-sm">删除</button></a>
                    </td> -->
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
            $.post("<?php echo url('admin/tag/delTag'); ?>",{lid:data},function(response){
                layer.msg(response.msg,{time:1000,icon:response.icon},function(){
                    location.reload();
                })
            },'json');
        });
    }

    function delAdmin(lid){
        $.post('/admin/tag/delTag',{lid:lid},function(data){
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