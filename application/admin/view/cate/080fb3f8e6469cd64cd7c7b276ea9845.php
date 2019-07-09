<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:62:"D:\pei\public/../application/admin\view\cate\add_interest.html";i:1562318995;}*/ ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
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
                    <span class="we-red">*</span>兴趣名称
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="name" name="name" required="" lay-verify="required" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <button class="layui-btn" lay-submit="" lay-filter="add">增加</button>
                &nbsp;&nbsp;&nbsp;&nbsp;<a href="/admin/cate/interest"><button type="button" class="layui-btn">返回</button></a>
            </div>
        </form>
    </div>
    <script src="/static/admin/static/js/jquery.js" charset="utf-8"></script>
    <script src="/static/admin/lib/layui/layui.js" charset="utf-8"></script>
    <script type="text/javascript">
    layui.use(['form', 'layer'], function() {
        var form = layui.form,
            admin = layui.admin,
            layer = layui.layer;

        //监听提交
        form.on('submit(add)', function(data) {
            $.post("<?php echo url('admin/cate/addInterest'); ?>", data.field, function(response) {
                if (response.code == 0) {
                    layer.alert("增加成功", {
                        icon: response.icon
                    }, function() {
                        if(response.code == 0){
                            location.href = "<?php echo url('admin/cate/interest'); ?>";
                        }else{
                            return false;
                        }
                    });
                }
            }, 'json');
            //发异步，把数据提交给php
            return false;
        });

    });
    </script>
</body>
</html>