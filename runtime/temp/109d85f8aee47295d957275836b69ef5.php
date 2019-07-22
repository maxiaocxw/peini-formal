<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:90:"D:\phpserver\wwwroot\default\peini-formal\public/../application/union\view\auth\login.html";i:1563340894;}*/ ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>陪你-会长登录</title>
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link rel="shortcut icon" href="./favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="/static/admin/static/css/font.css">
    <link rel="stylesheet" href="/static/admin/static/css/weadmin.css">
    <script src="/static/admin/lib/layui/layui.js" charset="utf-8"></script>
</head>

<style>
    #code{
        float: left;
    }
    #sendcode{
        float: left;
    }
</style>

<body class="login-bg">
    <div class="login">
        <div class="message">陪你 会长管理系统</div>
        <div id="darkbannerwrap"></div>
        <form class="layui-form">
            <input name="phone" placeholder="手机号" type="text" lay-verify="required" class="layui-input">
            <hr class="hr15">
            <input name="code" lay-verify="required" placeholder="验证码" type="text" class="layui-input" style="width:50%;" id="code">
            <div class="layui-col-xs5">
                <span class="layui-btn layui-btn-normal" id="sendcode" style="height: 50px;text-align:center;line-height:50px;">发送验证码</span>
            </div>
            <hr class="hr15">
            <input class="loginin" value="登录" lay-submit lay-filter="login" style="width:100%;" type="submit">
            <hr class="hr20">
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script type="text/javascript">
    layui.use(['form'], function() {
        var form = layui.form,
            $ = layui.jquery;
        let sendcode_state = true;
        let wait = 60;

        //验证码倒计时
        const countdown = (o) => {
            if (wait <= 0) {
                o.removeClass('layui-disabled');
                o.html("发送验证码");
                wait = 60;
                sendcode_state = true;
            } else {
                o.addClass('layui-disabled');
                o.html(wait + "秒后重获");
                wait--;
                setTimeout(() => {
                    countdown(o)
                }, 1000);
            }
        };

        //获取验证码
        $("#sendcode").click(() => {
            let phone = $("input[name='phone']").val();
            if( phone=='' ){
                layer.msg('请输入手机号', { time: 1000, icon: 2 });
            }
            if( sendcode_state ){
                if( phone.match(/(13\d|14[579]|15[^4\D]|17[^49\D]|18\d)\d{8}/)){
                    $.post("<?php echo url('union/auth/putCode'); ?>", {'phone':phone}, function(response) {
                        if (response.code == 0) {
                            layer.msg(response.msg, { time: 1000, icon: response.icon });
                        }else{
                            layer.msg(response.msg, { time: 1000, icon: response.icon });
                        }
                    }, 'json');
                }
            }else{
                alert('123');
            }
            return false;
        });


        //监听提交
        form.on('submit(login)', function(data) {
            // $.ajax({
            //     url: "<?php echo url('union/auth/checkCode'); ?>",
            //     type: "post",
            //     data: data.field,
            //     dataType: "json",
            //     success: function(msg) {
            //         layer.msg(msg.msg, { time: 1000 }, function() {
            //             if (msg.code == 0) {
            //                 location.href = "<?php echo url('union/index/index'); ?>";
            //             } else {
            //                 layer.msg(response.msg, { time: 1000, icon: response.icon });
            //             }
            //         })
            //     }
            // })

            $.post("<?php echo url('union/auth/checkCode'); ?>", data.field, function(response) {
                if (response.code == 0) {
                    layer.msg(response.msg, { time: 1000, icon: response.icon });
                    location.href = "<?php echo url('union/index/index'); ?>";
                }else{
                    layer.msg(response.msg, { time: 1000, icon: response.icon });
                }
            }, 'json');
            return false;
        });
    });
    </script>
    <!-- 底部结束 -->
</body>

</html>