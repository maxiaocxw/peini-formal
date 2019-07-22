<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:97:"D:\phpserver\wwwroot\default\peini-formal\public/../application/admin\view\cate\update_games.html";i:1562747205;}*/ ?>
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
            <input type="hidden" name="gid" value="<?php echo $data['gid']; ?>">
            <label for="name" class="layui-form-label">
                <span class="we-red">*</span>游戏名称
            </label>
            <div class="layui-input-inline">
                <input type="text" id="name" name="name" value="<?php echo $data['name']; ?>" required="" lay-verify="required" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="language" class="layui-form-label">
                <span class="we-red">*</span>游戏封面
            </label>
            <div class="layui-input-block">
                <button type="button" class="layui-btn" id="test1">上传图片</button>
                <div class="layui-upload-list">
                    <img class="layui-upload-img" id="demo1" src="<?php echo $data['img']; ?>" style="height: 200px;">
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label for="user_name" class="layui-form-label">
                <span class="we-red">*</span>游戏简介
            </label>
            <div class="layui-input-inline">
                <input type="text" id="info" name="info" value="<?php echo $data['info']; ?>" required="" lay-verify="required" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="user_name" class="layui-form-label">
                <span class="we-red">*</span>所属分类
            </label>
            <div class="layui-input-inline">
                <select name="tid" lay-verify="required" autocomplete="off" class="layui-select">
                    <option value="">请选择分类</option>
                    <?php foreach($gametype_data as $key => $v): if($v['tid'] == $data['tid']): ?>
                            <option value="<?php echo $v['tid']; ?>" selected><?php echo $v['name']; ?></option>
                        <?php else: ?>
                            <option value="<?php echo $v['tid']; ?>"><?php echo $v['name']; ?></option>
                        <?php endif; endforeach; ?>
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <button class="layui-btn" lay-submit="" lay-filter="upda">修改</button>
            &nbsp;&nbsp;&nbsp;&nbsp;<a href="/admin/cate/game"><button type="button" class="layui-btn">返回</button></a>
        </div>
    </form>
</div>
<script src="/static/admin/lib/layui/layui.js" charset="utf-8"></script>
<script src="/static/admin/static/js/jquery.js" charset="utf-8"></script>
<script type="text/javascript">
    layui.extend({
        admin: '/static/admin/static/js/admin'
    });
    layui.use(['form', 'layer', 'upload'], function() {
        var form = layui.form,
            admin = layui.admin,
            layer = layui.layer;
        upload = layui.upload,

            //图片上传
            upload.render({
                elem: '#test1',
                url: "/admin/cate/qinui_upload",
                done: function(res) {
                    if(res.code == 0){
                        $("#demo1").attr('src', res.src);
                        layer.msg(res.msg, { time: 1000, icon: res.icon });
                    }else{
                        layer.msg(res.msg, { time: 1000, icon: res.icon });
                    }
                }
            });

        //监听提交
        form.on('submit(upda)', function(data) {
            data.field.img = $("#demo1").attr('src');
            delete data.field.file;
            $.post("<?php echo url('admin/cate/updateGamesDo'); ?>", data.field, function(response) {
                if (response.code == 0) {
                    layer.alert("修改成功", {
                        icon: response.icon
                    }, function() {
                        if(response.code == 0){
                            location.href = "<?php echo url('admin/cate/game'); ?>";
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