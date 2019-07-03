<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:77:"/data/wwwroot/default/peini/public/../application/index/view/index/login.html";i:1561619888;}*/ ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>陪你</title>
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
    </style>
</head>

<body>
    <div class="login-main" style="margin:auto;height:240px;">
        <header class="layui-elip" style="margin-top: 0">陪你</header>
        <form class="layui-form">
            <div class="layui-input-inline">
                <input type="text" name="account" required lay-verify="required" placeholder="请输入账号" autocapitalize="on" autocomplete="off" class="layui-input">
            </div>
            <div class="layui-input-inline">
                <input type="password" name="password" required lay-verify="required" placeholder="请输入密码" autocomplete="off" class="layui-input">
            </div>
            <div class="layui-input-inline login-btn">
                <button class="layui-btn layui-btn-normal" lay-submit lay-filter='submit'>登录</button>
            </div>
            <hr/>
            <p class="layui-clear"><a href="/register" class="fl">立即注册</a><a href="<?php echo url('Index/reset_password'); ?>" target="_blank" class="fr">忘记密码？</a></p>
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
        form.on('submit(submit)', (data) => {
            layuiPost("<?php echo url('index/index/postLogin'); ?>", data.field, (obj) => {
                if (obj.result) {
                    layer.msg(obj.msg, {
                        icon: obj.icon
                    });
                    setTimeout(() => {
                        window.location.href = obj.data;
                    }, 800);
                } else {
                    layer.msg(obj.msg, {
                        icon: obj.icon,
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
