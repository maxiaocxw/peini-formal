<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:56:"D:\pei\public/../application/index\view\approve\add.html";i:1563520378;}*/ ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <link rel="stylesheet" href="/static/admin/static/css/font.css">
    <link rel="stylesheet" href="/static/admin/static/css/weadmin.css">
    <!-- 让IE8/9支持媒体查询，从而兼容栅格 -->
    <!--[if lt IE 9]>
    <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
    <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<style>
    #header{
        position: relative;
        width: 778px;
        height: 250px;
        background-color: #999;
        margin: 0 auto;

    }
</style>

<body>
<div class="weadmin-body" id="header">
    <div id="Layer1" style="position:fixed; left:0px; top:0px; width:100%; height:100%">
        <img src="http://cdn.lanyushiting.com/web.png" width="100%" height="100%"/>
    </div>


    <div class="layui-form-item">

        <div class="layui-input-block">
            <h1>陪玩资料认证</h1>
        </div>
    </div>


    <form action="" method="post" class="layui-form layui-form-pane">

        <div class="layui-form-item">
            <label for="user_name" class="layui-form-label">
                <span class="we-red">*</span>手机号
            </label>
            <div class="layui-input-inline">
                <input type="text" id="name" name="phone" required="" lay-verify="required" autocomplete="off" class="layui-input"><span class="we-red">登陆时使用</span>
            </div>
        </div>


        <div class="layui-form-item">
            <label for="user_name" class="layui-form-label">
                <span class="we-red">*</span>真实姓名
            </label>
            <div class="layui-input-inline">
                <input type="text" id="name" name="username" required="" lay-verify="required" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label for="user_name" class="layui-form-label">
                <span class="we-red">*</span>身份证号
            </label>
            <div class="layui-input-inline">
                <input type="text" id="price" name="idcode" required="" lay-verify="required" autocomplete="off" class="layui-input">

            </div>
        </div>
        <div class="layui-form-item">
            <label for="user_name" class="layui-form-label">
                <span class="we-red">*</span>支付宝账号
            </label>
            <div class="layui-input-inline">
                <input type="text" id="price" name="alipay" required="" lay-verify="required" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label for="user_name" class="layui-form-label">
                <span class="we-red">*</span>QQ号
            </label>
            <div class="layui-input-inline">
                <input type="text" id="price" name="qq" required="" lay-verify="required" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label for="user_name" class="layui-form-label">
                <span class="we-red">*</span>微信
            </label>
            <div class="layui-input-inline">
                <input type="text" id="price" name="wx" required="" lay-verify="required" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label for="user_name" class="layui-form-label">
                <span class="we-red">*</span>标签
            </label>
            <div class="layui-input-inline">
                <?php foreach($label as $val): ?>
                <input type="checkbox" name="label[]" value="<?php echo $val['lid']; ?>" title="<?php echo $val['name']; ?>">
                <?php endforeach; ?>
            </div>
        </div>



        <div class="layui-form-item">
            <label for="language" class="layui-form-label">
                <span class="we-red">*</span>身份证正面
            </label>
            <div class="layui-input-block">
                <button type="button" class="layui-btn" id="test1">上传身份证正面</button>
                <div class="layui-upload-list">
                    <img class="layui-upload-img" id="demo1" style="height: 200px;">
                    <p id="demoText"></p>
                </div>
            </div>
        </div>

        <div class="layui-form-item">
            <label for="language" class="layui-form-label">
                <span class="we-red">*</span>身份证反面
            </label>
            <div class="layui-input-block">
                <button type="button" class="layui-btn" id="test2">身份证反面</button>
                <div class="layui-upload-list">
                    <img class="layui-upload-img" id="demo2" style="height: 200px;">
                    <p id="demoText"></p>
                </div>
            </div>
        </div>

        <div class="layui-form-item">
            <label for="language" class="layui-form-label">
                <span class="we-red">*</span>手持身份证
            </label>
            <div class="layui-input-block">
                <button type="button" class="layui-btn" id="test3">手持身份证</button>
                <div class="layui-upload-list">
                    <img class="layui-upload-img" id="demo3" style="height: 200px;">
                    <p id="demoText"></p>
                </div>
            </div>
        </div>

        <div class="layui-form-item">
            <label for="language" class="layui-form-label">
                <span class="we-red">*</span>视频 15s以内 3秒以上
            </label>
            <div class="layui-input-block">
                <button type="button" class="layui-btn" id="test4">上传视频</button>
                <div class="layui-upload-list">

                    <video class="layui-upload-video" id="demo4" style="height: 200px;" src="" /></video>
                    <!--<img class="layui-upload-img" id="demo4" style="height: 200px;">-->
                    <p id="demoText"></p>
                </div>
            </div>
        </div>

        <div class="layui-form-item">
            <label for="language" class="layui-form-label">
                <span class="we-red">*</span>视频封面图
            </label>
            <div class="layui-input-block">
                <button type="button" class="layui-btn" id="test5">视频封面图</button>
                <div class="layui-upload-list">
                    <img class="layui-upload-img" id="demo5" style="height: 200px;">
                    <p id="demoText"></p>
                </div>
            </div>
        </div>


        <div class="layui-form-item">

            <div class="layui-input-block">
                <button type="button" class="layui-btn" lay-submit="" lay-filter="add">增加</button>
            </div>
        </div>

    </form>
</div>
<script src="/static/admin/static/js/jquery.js" charset="utf-8"></script>
<script src="/static/admin/lib/layui/layui.js" charset="utf-8"></script>
<script type="text/javascript">
    layui.use(['form', 'layer','upload'], function() {
        var form = layui.form,
            admin = layui.admin,
            layer = layui.layer;
        upload = layui.upload,

            //图片上传
            upload.render({
                elem: '#test1',
                url: "/index/approve/qinui_upload",
                done: function(res) {
                    console.log(res);
                    if(res.code == 0){
                        $("#demo1").attr('src', res.src);
                        layer.msg(res.msg, { time: 1000, icon: res.icon });
                    }else{
                        layer.msg(res.msg, { time: 1000, icon: res.icon });
                    }
                }
            });

        upload.render({
            elem: '#test2',
            url: "/index/approve/qinui_upload",
            done: function(res) {
                if(res.code == 0){
                    $("#demo2").attr('src', res.src);
                    layer.msg(res.msg, { time: 1000, icon: res.icon });
                }else{
                    layer.msg(res.msg, { time: 1000, icon: res.icon });
                }
            }
        });

        upload.render({
            elem: '#test3',
            url: "/index/approve/qinui_upload",
            done: function(res) {
                if(res.code == 0){
                    $("#demo3").attr('src', res.src);
                    layer.msg(res.msg, { time: 1000, icon: res.icon });
                }else{
                    layer.msg(res.msg, { time: 1000, icon: res.icon });
                }
            }
        });

        upload.render({
            elem: '#test4',
            url: "/index/approve/qinui_upload_video",
            field:"layuiVideo",
            accept: 'video',
            done: function(res) {
                if(res.code == 0){
                    $("#demo4").attr('src', res.src);
                    layer.msg(res.msg, { time: 1000, icon: res.icon });
                }else{
                    layer.msg(res.msg, { time: 1000, icon: res.icon });
                }
            }
        });

        upload.render({
            elem: '#test5',
            url: "/index/approve/qinui_upload",
            done: function(res) {
                if(res.code == 0){
                    $("#demo5").attr('src', res.src);
                    layer.msg(res.msg, { time: 1000, icon: res.icon });
                }else{
                    layer.msg(res.msg, { time: 1000, icon: res.icon });
                }
            }
        });


        //监听提交
        form.on('submit(add)', function(data) {
            data.field.img1 = $("#demo1").attr('src');
            data.field.img2 = $("#demo2").attr('src');
            data.field.img3 = $("#demo3").attr('src');
            data.field.img4 = $("#demo4").attr('src');
            data.field.img5 = $("#demo5").attr('src');

            delete data.field.file;
            if(data.field.img1 == undefined){
                alert('请选择身份证正面照片');
            }

            if(data.field.img2 == undefined){
                alert('请选择身份证反面照片');
            }

            if(data.field.img3 == undefined){
                alert('请选择手持身份证照片');
            }


            $.post("<?php echo url('index/approve/addDo'); ?>", data.field, function(response) {
                if (response.code == 0) {
                    layer.alert("添加成功", { icon: 6 }, function() {
                        window.location.href = "http://www.shayudj.com/";
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