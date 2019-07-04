<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:82:"E:\Project\peini-formal\public/../application/admin\view\admin\add_permission.html";i:1562150096;}*/ ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>角色管理-WeAdmin Frame型后台管理系统-WeAdmin 1.0</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
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
                <label for="name" class="layui-form-label">
                    <span class="we-red">*</span>权限名
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="name" name="name" required="" lay-verify="required" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="content" class="layui-form-label">
                    描述
                </label>
                <div class="layui-input-block">
                    <textarea placeholder="请输入内容" id="content" name="content" class="layui-textarea"></textarea>
                </div>
            </div>

            <div class="layui-form-item">
                <label for="content" class="layui-form-label">
                    权限分配
                </label>
                <br><br>
                <div class="layui-input-block">
                <?php foreach($menu_list_one as $menu_key => $menu_value): if((\think\Session::get('admin.type') == 1 || in_array($menu_value['id'],$menu_role))): ?>
                    <input type="checkbox" name="menu_pid[]" class="level_<?php echo $menu_value['id']; ?>" value="<?php echo $menu_value['id']; ?>" title="<?php echo $menu_value['title']; ?>" lay-skin="primary" lay-filter="menu_pid">
                    <br/>
                    <?php foreach($menu_list_two as $menu_key_two => $menu_value_two): if(($menu_value_two['mid'] == $menu_value['id'] && (\think\Session::get('admin.type') == 1 || in_array($menu_value_two['id'],$menu_role) ))): ?>
                            <span class="one"><span class="two"></span>L
                                <input type="checkbox" class='menu level_<?php echo $menu_value['id']; ?>' name="menu_id[]" value="<?php echo $menu_value_two['id']; ?>" title="<?php echo $menu_value_two['title']; ?>" lay-skin="primary" lay-filter="menu_id">
                            </span>
                        <?php endif; endforeach; ?>
                    <br/>
                    <?php endif; endforeach; ?>
                <br>
                </div>
            </div>
            <div class="layui-form-item">
                <button class="layui-btn" lay-submit="" lay-filter="add">增加</button>
                &nbsp;&nbsp;&nbsp;&nbsp;<a href="/admin/admin/role"><button type="button" class="layui-btn">返回</button></a>
            </div>
        </form>
    </div>
    <style>
        .one{width: 260px;height: 36px;display: inline-block;}
        .two{display: inherit; width: 40px;}
    </style>
    <script src="/static/admin/static/js/jquery.js" charset="utf-8"></script>
    <script src="/static/admin/lib/layui/layui.js" charset="utf-8"></script>
    <script type="text/javascript">
    layui.use(['form', 'layer'], function() {
        var form = layui.form,
            admin = layui.admin,
            layer = layui.layer;

        form.on('checkbox(menu_id)', function(data){
            console.log(data.elem.parent().find("input[name='menu_pid']").html()); //得到checkbox原始DOM对象
            // console.log(data.elem.checked); //是否被选中，true或者false
            // console.log(data.value); //复选框value值，也可以通过data.elem.value得到
            // console.log(data.othis); //得到美化后的DOM对象
        }); 
        //监听提交
        form.on('submit(add)', function(data) {
            // console.log(data.field)
            // return false;
            $.post("<?php echo url('doAddPermission'); ?>", data.field, function(response) {
                if (response.code == 0) {
                    layer.alert("增加成功", {
                        icon: response.icon
                    }, function() {
                    	location.reload();
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