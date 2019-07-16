<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:90:"F:\phpserver\wwwroot\default\peini-formal\public/../application/admin\view\auth\login.html";i:1563293877;}*/ ?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>管理员登录-陪你 后台管理系统</title>
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link rel="shortcut icon" href="./favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="/static/admin/static/css/font.css">
    <link rel="stylesheet" href="/static/admin/static/css/weadmin.css">
    <script src="/static/admin/lib/layui/layui.js" charset="utf-8"></script>
</head>

<body class="login-bg">
    <div class="login">
        <div class="message">陪你 后台管理系统</div>
        <div id="darkbannerwrap"></div>
        <form class="layui-form">
            <input name="username" placeholder="用户名" type="text" lay-verify="required" class="layui-input">
            <hr class="hr15">
            <input name="password" lay-verify="required" placeholder="密码" type="password" class="layui-input">
            <hr class="hr15">
            <input class="loginin" value="登录" lay-submit lay-filter="login" style="width:100%;" type="submit">
            <hr class="hr20">
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script type="text/javascript">
    // layui.extend({
    //     admin: "/static/admin/static/js/admin"
    // });
    layui.use(['form'], function() {
        var form = layui.form;
            // admin = layui.admin;
        //监听提交
        form.on('submit(login)', function(data) {
            $.ajax({
                url: "<?php echo url('admin/auth/loginin'); ?>",
                type: "post",
                data: data.field,
                dataType: "json",
                success: function(msg) {
                    layer.msg(msg.msg, { time: 1000 }, function() {
                        if (msg.code == 0) {
                            location.href = "<?php echo url('admin/index/index'); ?>";
                        } else {
                            return false;
                        }
                    })
                }
            })
            return false;
        });
    });
    </script>
    <!-- 底部结束 -->
</body>

</html>