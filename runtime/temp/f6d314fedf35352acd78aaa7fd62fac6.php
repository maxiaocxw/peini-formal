<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:97:"D:\phpserver\wwwroot\default\peini-formal\public/../application/admin\view\cate\update_works.html";i:1562754344;}*/ ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>欢迎页面-WeAdmin Frame型后台管理系统-WeAdmin 1.0</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="/static/admin/static/css/font.css">
    <link rel="stylesheet" href="/static/admin/static/css/weadmin.css">
</head>

<body>
<div class="weadmin-body">
    <form class="layui-form layui-form-pane" action="" method="post">
        <div class="layui-form-item">
            <input type="hidden" name="wid" value="<?php echo $data['wid']; ?>">
            <label for="name" class="layui-form-label">
                <span class="we-red">*</span>职业名称
            </label>
            <div class="layui-input-inline">
                <input type="text" id="name" name="name" value="<?php echo $data['name']; ?>" required="" lay-verify="required" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <button class="layui-btn" lay-submit="" lay-filter="upda">修改</button>
            &nbsp;&nbsp;&nbsp;&nbsp;<a href="/admin/cate/work"><button type="button" class="layui-btn">返回</button></a>
        </div>
    </form>
</div>
<script src="/static/admin/lib/layui/layui.js" charset="utf-8"></script>
<script src="/static/admin/static/js/jquery.js" charset="utf-8"></script>
<script type="text/javascript">
    layui.extend({
        admin: '/static/admin/static/js/admin'
    });
    layui.use(['form', 'layer'], function() {
        var form = layui.form,
            admin = layui.admin,
            layer = layui.layer;

        //监听提交
        form.on('submit(upda)', function(data) {
            $.post("<?php echo url('admin/cate/updateWorksDo'); ?>", data.field, function(response) {
                if (response.code == 0) {
                    layer.alert("修改成功", {
                        icon: response.icon
                    }, function() {
                        if(response.code == 0){
                            location.href = "<?php echo url('admin/cate/work'); ?>";
                        }else{
                            return false;
                        }
                    });
                }else{
                    layer.msg(response.msg, { time: 1000, icon: response.icon });
                }
            }, 'json');
            //发异步，把数据提交给php
            return false;
        });

    });
</script>
</body>

</html>