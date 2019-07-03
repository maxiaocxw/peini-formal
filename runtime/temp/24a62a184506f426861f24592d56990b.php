<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:86:"/data/wwwroot/default/peini/public/../application/index/view/index/reset_password.html";i:1561620028;}*/ ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>忘记密码</title>
    <link rel="stylesheet" href="/static/frame/layui/css/layui.css">
    <link rel="stylesheet" href="/static/frame/static/css/style.css">
    <style>
    html,
    body {
        width: 100%;
        height: 100%;
        overflow: hidden;
        background: #000;
    }
    
    #container {
        width: 100%;
        height: 100%;
    }
    
    input {
        background: none !important;
    }
    
    a,
    header,
    button,
    input {
        color: #fff !important;
    }
    
    ::-webkit-input-placeholder {
        color: #fff;
    }
    
    ::-moz-placeholder {
        color: #fff;
    }
    
    :-ms-input-placeholder {
        color: #fff;
    }
    
    input:-moz-placeholder {
        color: #fff;
    }
    
    #sendcode {
        margin-left: 38px;
        background: none;
        color: #fff;
    }
    
    .login-main {
        height: 200px;
        margin: auto
    }
    
    .layui-elip {
        margin-top: 0 !important;
    }
    </style>
</head>

<body>
    <div class="login-main">
        <header class="layui-elip">重置密码</header>
        <form class="layui-form layui-anim" id="verification">
            <div class="layui-input-inline">
                <input type="text" name="phone" lay-verify="required|phone" placeholder="手机" autocomplete="off" class="layui-input">
            </div>
            <div class="layui-form-item">
                <div class="layui-row">
                    <div class="layui-col-xs7">
                        <input type="text" name="code" lay-verify="required" placeholder="验证码" autocomplete="off" class="layui-input">
                    </div>
                    <div class="layui-col-xs5">
                        <div class="layui-btn layui-btn-primary" id="sendcode">发送验证码</div>
                    </div>
                </div>
            </div>
            <div class="layui-input-inline login-btn">
                <button type="submit" lay-submit lay-filter='verification' class="layui-btn layui-btn-normal">找回密码</button>
            </div>
        </form>
        <form class="layui-form layui-anim layui-hide" id="reset_password">
            <div class="layui-input-inline">
                <input type="password" name="password" lay-verify="required|pass" placeholder="新密码" autocomplete="off" class="layui-input">
            </div>
            <div class="layui-input-inline">
                <input type="password" name="repass" lay-verify="required|pass" placeholder="确认密码" autocomplete="off" class="layui-input">
            </div>
            <div class="layui-input-inline login-btn">
                <button type="submit" lay-submit lay-filter='reset_password' class="layui-btn layui-btn-normal">重置新密码</button>
            </div>
        </form>
    </div>
    <div id="container">
    </div>
    <script src='/static/canvas/background/js/three.min.js'></script>
    <script src='/static/canvas/background/js/CopyShader.js'></script>
    <script src='/static/canvas/background/js/EffectComposer.js'></script>
    <script src='/static/canvas/background/js/FilmPass.js'></script>
    <script src='/static/canvas/background/js/FilmShader.js'></script>
    <script src='/static/canvas/background/js/ShaderPass.js'></script>
    <script src='/static/canvas/background/js/RenderPass.js'></script>
    <script src="/static/canvas/background/js/index.js"></script>
    <script src="/static/frame/layui/layui.js"></script>
    <script type="text/javascript" src="/static/js/index.js"></script>
    <script type="text/javascript">
    layui.use(['form'], () => {
        var form = layui.form,
            $ = layui.jquery;
        let sendcode_state = true;
        let wait = 60;

        form.verify({
            pass: [
                /^[\S]{6,12}$/, '密码必须6到12位，且不能出现空格'
            ]
        });

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
        }

        //获取验证码
        $("#sendcode").click(() => {
            let phone = $("input[name='phone']").val();
                if (phone.match(/(13\d|14[579]|15[^4\D]|17[^49\D]|18\d)\d{8}/)) {
                    layuiPost("<?php echo url('Api/sendcode'); ?>", {
                        phone: phone
                    }, (obj) => {
                        if (obj.result) {
                            layer.msg(obj.msg, {
                                icon: 1
                            });
                            countdown($("#sendcode"));
                        } else {
                            layer.msg(obj.msg, {
                                icon: 5,
                                anim: 6
                            });
                        }
                    });
                } else {
                    layer.msg("请输入正确手机号", {
                        icon: 5,
                        anim: 6
                    });
                }
            return false;
        });

        //验证手机
        form.on('submit(verification)', (data) => {
            layuiPost("<?php echo url('Api/register_phone'); ?>", data.field, (obj) => {
                if (obj.result) {
                    layer.msg(obj.msg, {
                        icon: 5,
                        anim: 6
                    });
                }
            });
            return false;
        });

        //重置密码
        form.on('submit(reset_password)', (data) => {
            if (data.field.password !== data.field.repass) {
                return layer.msg("两次密码输入不一致", {
                    icon: 5,
                    anim: 6
                });
            }
            layuiPost("/login/reset_password", data.field, (obj) => {
                if (obj.result) {
                    layer.msg(obj.msg, {
                        icon: 1
                    });
                    setTimeout(() => {
                        window.location.href = obj.data;
                    }, 1200);
                } else {
                    layer.msg(obj.msg, {
                        icon: 5,
                        anim: 6
                    });
                }
            })
            return false;
        });
    });
    </script>
</body>

</html>
