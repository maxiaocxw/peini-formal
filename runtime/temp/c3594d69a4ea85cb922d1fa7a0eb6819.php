<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:56:"D:\pei\public/../application/index\view\money\index.html";i:1563177639;}*/ ?>
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

<body background="http://cdn.lanyushiting.com/logo.jpg
">

<div class="weadmin-body" id="header">
    <div id="Layer1" style="position:fixed; left:0px; top:0px; width:100%; height:100%">
        <img src="http://bannerdesign.cn/wp-content/uploads/2015/02/20150207021212982.jpg" width="100%" height="100%"/>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <h1><img src="http://cdn.lanyushiting.com/logo.jpg" alt="" style="height: 50px;width: 50px;"><font color="red">城市网吧代理提交</font></h1>
        </div>
    </div>

    <form action="" method="post" class="layui-form layui-form-pane">

        <div class="layui-form-item">
            <label for="user_name" class="layui-form-label">
                <span class="we-red">*</span>网吧名称
            </label>
            <div class="layui-input-inline">
                <input type="text" id="name" name="name" required="" lay-verify="required" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label for="language" class="layui-form-label">
                <span class="we-red">*</span>营业执照
            </label>
            <div class="layui-input-block">
                <button type="button" class="layui-btn" id="ying1">上传营业执照</button>
                <div class="layui-upload-list">
                    <img class="layui-upload-img" id="wang1" style="height: 200px;">
                    <p id="demoText"></p>
                </div>
            </div>
        </div>

        <div class="layui-form-item">
            <label for="language" class="layui-form-label" style="width:140px">
                <span class="we-red">*</span>网络经营许可证
            </label>
            <div class="layui-input-block">
                <button type="button" class="layui-btn" id="ying2">上传网络经营许可证</button>
                <div class="layui-upload-list">
                    <img class="layui-upload-img" id="wang2" style="height: 200px;">
                    <p id="demoText"></p>
                </div>
            </div>
        </div>

        <div class="layui-form-item">
            <label for="user_name" class="layui-form-label">
                <span class="we-red">*</span>地区
            </label>
            <div class="layui-input-inline">
                <input type="text" id="area" name="area" required="" lay-verify="required" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label for="user_name" class="layui-form-label">
                <span class="we-red">*</span>负责人姓名
            </label>
            <div class="layui-input-inline">
                <input type="text" id="area" name="fzname" required="" lay-verify="required" autocomplete="off" class="layui-input">
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
                <span class="we-red">*</span>收款码
            </label>
            <div class="layui-input-block">
                <div class="layui-upload-list">
                    <img class="layui-upload-img" src="http://cdn.lanyushiting.com/money.jpg" style="height: 300px;">
                    <p id="demoText"></p>
                </div>
            </div>
        </div>

        <div class="layui-form-item">
            <label for="language" class="layui-form-label">
                <span class="we-red">*</span>付钱账号
            </label>
            <div class="layui-input-block">
                <div class="layui-input-inline">
                    <input type="text" id="price" name="ide" required="" lay-verify="required" autocomplete="off" class="layui-input">
                </div>
            </div>
        </div>


        <div class="layui-form-item">

            <div class="layui-input-block">
                <button type="button" class="layui-btn" lay-submit="" lay-filter="add">提交</button>
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
                url: "/index/money/qinui_upload",
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
            url: "/index/money/qinui_upload",
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
            elem: '#ying1',
            url: "/index/money/qinui_upload",
            done: function(res) {
                if(res.code == 0){
                    $("#wang1").attr('src', res.src);
                    layer.msg(res.msg, { time: 1000, icon: res.icon });
                }else{
                    layer.msg(res.msg, { time: 1000, icon: res.icon });
                }
            }
        });

        upload.render({
            elem: '#ying2',
            url: "/index/money/qinui_upload",
            done: function(res) {
                if(res.code == 0){
                    $("#wang2").attr('src', res.src);
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
            data.field.img3 = $("#wang1").attr('src');
            data.field.img4 = $("#wang2").attr('src');

            delete data.field.file;
            if(data.field.img1 == undefined){
                alert('请选择身份证正面照片');
            }

            if(data.field.img2 == undefined){
                alert('请选择身份证反面照片');
            }

            if(data.field.img3 == undefined){
                alert('请选择营业执照');
            }
            if(data.field.img4 == undefined){
                alert('请选择网络经营许可证');
            }


            $.post("<?php echo url('index/money/add'); ?>", data.field, function(response) {
                if (response.code == 0) {
                    layer.alert("添加成功", { icon: 6 }, function() {
                        window.location.href = "";
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