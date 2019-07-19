<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:93:"D:\phpserver\wwwroot\default\peini-formal\public/../application/admin\view\approve\index.html";i:1563279819;}*/ ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>陪玩审核列表</title>
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
        <a href="">陪玩审核</a>
        <a>
          <cite>陪玩审核列表</cite></a>
      </span>
    <a class="layui-btn layui-btn-sm" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i></a>
</div>
<div class="weadmin-body">


    <div class="weadmin-block">
        <span class="fr" style="line-height:40px">共有数据：<?php echo $total; ?> 条</span>
    </div>
    <table class="layui-table">
        <thead>
        <tr>
            <th>
                <div class="layui-unselect header layui-form-checkbox" id="c_all" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div>
            </th>
            <th>ID</th>
            <th>真实姓名</th>
            <th>支付宝账号</th>
            <th>身份证号</th>
            <th>身份证正面</th>
            <th>身份证反面</th>
            <th>手持身份证</th>
            <th>状态</th>
            <th>申请时间</th>
            <th>游戏名称，游戏价格</th>
            <th>标签</th>
            <th>操作</th>
        </thead>
        <tbody>
        <?php foreach($listInfo as $key => $value): ?>

        <tr>
            <td>
                <div class="layui-unselect layui-form-checkbox" id="admin_id" lay-skin="primary" ><i class="layui-icon">&#xe605;</i></div>
            </td>
            <td><?php echo $value['list']['id']; ?></td>
            <td><?php echo $value['list']['username']; ?></td>
            <td><?php echo $value['list']['alipay']; ?></td>
            <td><?php echo $value['list']['idcode']; ?></td>
            <td>
                <div class="layui-upload-list">
                    <div class='imgBox'><img src="http://cdn.lanyushiting.com/<?php echo $value['list']['idcodefront']; ?>" class='wh100 ml2 mr2 screen'></div>
                </div>
            </td>
            <td>
                <div class="layui-upload-list">
                    <div class='imgBox'><img src="http://cdn.lanyushiting.com/<?php echo $value['list']['idcodereverse']; ?>" class='wh100 ml2 mr2 screen'></div>
                </div>
            </td>
            <td>
                <div class="layui-upload-list">
                    <div class='imgBox'><img src="http://cdn.lanyushiting.com/<?php echo $value['list']['handidcode']; ?>" class='wh100 ml2 mr2 screen'></div>
                </div>
            </td>
<<<<<<< HEAD
=======
            <td><img src="http://cdn.lanyushiting.com/<?php echo $value['list']['idcodefront']; ?>" style="height: 50px;max-width: 180px;" class="pimg"></td>
            <td><img src="http://cdn.lanyushiting.com/<?php echo $value['list']['idcodereverse']; ?>" style="height: 50px;max-width: 180px;" class="pimg" /></td>
            <td><img src="http://cdn.lanyushiting.com/<?php echo $value['list']['handidcode']; ?>" style="height: 50px;max-width: 180px;" class="pimg" /></td>
>>>>>>> 6c3cfaf2761c62b4a5c1ef6086a93820d4c2144d
            <td>
            <?php if($value['list']['status'] == 1): ?>
                <span class="layui-btn layui-btn-danger layui-btn-xs">待审核</span></td>
            <?php elseif($value['list']['status'] == 2): ?>
            <span class="layui-btn layui-btn-danger layui-btn-xs">审核成功</span></td>
            <?php elseif($value['list']['status'] == 3): ?>
            <span class="layui-btn layui-btn-danger layui-btn-xs">审核未通过</span></td>
            <?php endif; ?>
            </td>

            <td>
                <?=date('Y-m-d H:i:s',$value['list']['addtime'])?>
            </td>
            <td>
                <?php foreach($value['game'] as $game): ?>
                <span><?php echo $game['gameName']; ?></span>,<span><?php echo $game['price']; ?></span><br>
                <?php endforeach; ?>
            </td>
            <td>
                <?php foreach($value['label'] as $label): ?>
                <span><?php echo $label['name']; ?></span><br>
                <?php endforeach; ?>
            </td>
            <td class="td-manage">
                <?php if($value['list']['status'] == 1): ?>
                    <a title="通过审核" href="/admin/approve/review?id=<?php echo $value['list']['id']; ?>"><button class="layui-btn layui-btn-danger layui-btn-sm">通过审核</button></a>
                    <a title="审核未通过" href="/admin/approve/noReview?id=<?php echo $value['list']['id']; ?>"><button class="layui-btn layui-btn-danger layui-btn-sm">审核未通过</button></a>
                <?php elseif($value['list']['status'] == 2): ?>
                    <span class="layui-btn layui-btn-danger layui-btn-xs">审核通过</span></td>
                <?php elseif($value['list']['status'] == 3): ?>
                    <span class="layui-btn layui-btn-danger layui-btn-xs">审核未通过</span></td>
                <?php endif; ?>
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
            $.post("<?php echo url('admin/gift/delAll'); ?>",{id:data,table:'gift'},function(response){
                layer.msg(response.msg,{time:1000,icon:response.icon},function(){
                    location.reload();
                })
            },'json');
        });
    }

    //图片放大展示
    $(".screen").bind('click', function() {
        var photos = $(this).parent().parent();
        layer.photos({
            photos: photos,
            anim: 5, //0-6的选择，指定弹出图片动画类型，默认随机（请注意，3.0之前的版本用shift参数）
            closeBtn: 1,
        });
    });

    function updateWork(id, status) {
        $.post("<?php echo url('admin/gift/update'); ?>", { gid: id, status: status }, function(data) {
            layer.msg(data.msg, { time: 1000, data:icon }, function() {
                location.reload();
            })
        }, 'json');
    }
</script>
</body>

</html>