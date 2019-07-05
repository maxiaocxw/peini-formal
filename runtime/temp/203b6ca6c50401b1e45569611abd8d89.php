<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:58:"D:\pei\public/../application/admin\view\cate\add_game.html";i:1562318995;}*/ ?>
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

<body>
    <div class="weadmin-body">
        <form action="" method="post" class="layui-form layui-form-pane">
            <div class="layui-form-item">
                <label for="user_name" class="layui-form-label">
                    <span class="we-red">*</span>游戏名称
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="name" name="name" required="" lay-verify="required" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="language" class="layui-form-label">
                    <span class="we-red">*</span>游戏封面
                </label>
                <div class="layui-input-block">
                    <button type="button" class="layui-btn" id="test1">上传图片</button>
                    <div class="layui-upload-list">
                        <img class="layui-upload-img" id="demo1" style="height: 200px;">
                        <p id="demoText"></p>
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <label for="user_name" class="layui-form-label">
                    <span class="we-red">*</span>游戏简介
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="info" name="info" required="" lay-verify="required" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="user_name" class="layui-form-label">
                    <span class="we-red">*</span>所属分类
                </label>
                <div class="layui-input-inline">
                    <select name="tid" lay-verify="required" autocomplete="off" class="layui-select">
                        <option value="">请选择分类</option>
                        <?php foreach($gametype_data as $key => $v): ?>
                            <option value="<?php echo $v['tid']; ?>"><?php echo $v['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <button class="layui-btn" lay-submit="" lay-filter="add">增加</button>
                &nbsp;&nbsp;&nbsp;&nbsp;<a href="/admin/cate/work"><button type="button" class="layui-btn">返回</button></a>
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

            upload.render({
                elem: '#test1',
                url: "/admin/cate/UploadImage",
                done: function(res) {
                    console.log(res);
                    if(res.code == 0){
                        var p_src = "http://www.shayudj.com"+res.src;
                        $("#demo1").attr('src', p_src);
                    }
                }
            });

        //监听提交
        form.on('submit(add)', function(data) {
            data.field.img = $("#demo1").attr('src');
            delete data.field.file;
            console.log(data.field);
            if(data.field.img == undefined){
                alert('请选择游戏封面');
            }else{
                $.post("<?php echo url('admin/cate/addGame'); ?>", data.field, function(response) {
                    if (response.code == 0) {
                        layer.alert("增加成功", {
                            icon: response.icon
                        }, function() {
                            if(response.code == 0){
                                location.href = "<?php echo url('admin/cate/game'); ?>";
                            }else{
                                return false;
                            }
                        });
                    }
                }, 'json');
            }

            //发异步，把数据提交给php
            return false;
        });

    });
    </script>
</body>
</html>