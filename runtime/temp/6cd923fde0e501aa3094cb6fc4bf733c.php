<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:91:"D:\phpserver\wwwroot\default\peini-formal\public/../application/admin\view\user\update.html";i:1563279818;}*/ ?>
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
        <form class="layui-form" action="/admin/user/updatedo" method="post">
            <div class="layui-form-item">
                <input type="hidden" name="uid" value="<?php echo $list['uid']; ?>">
                <label for="name" class="layui-form-label">
                    用户编号
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="number" name="number" value="<?php echo $list['number']; ?>" required="" lay-verify="required" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="name" class="layui-form-label">
                    用户名
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="username" name="username" value="<?php echo $list['username']; ?>"  lay-verify="required" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="is_hot" class="layui-form-label">
                    <span class="we-red">*</span>性别
                </label>
                <div class="layui-input-block">
                    <input type="radio" <?php if($list['sex']==1): ?> checked <?php endif; ?> name="sex" lay-filter="sex" value="1" title="男"> 
                    <input type="radio" <?php if($list['sex']==2): ?> checked <?php endif; ?> name="sex" lay-filter="sex" value="2" title="女">
                    <input type="radio" <?php if($list['sex']==3): ?> checked <?php endif; ?> name="sex" lay-filter="sex" value="3" title="未知">
                </div> 
            </div>
            <div class="layui-form-item">
                <label for="name" class="layui-form-label">
                    手机号
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="mobile" name="mobile" value="<?php echo $list['mobile']; ?>" required="" lay-verify="required" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="name" class="layui-form-label">
                    出生年月
                </label>
                <div class="layui-input-inline-ll">
                <input type="text" name="birthday" <?php if((isset($list['birthday']) && !empty($list['birthday']))): ?> value="<?=date('Y-m-d H:i:s',$list['birthday'])?>" <?php endif; ?> class="layui-input" id="test1-1" placeholder="yyyy-MM-dd">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="name" class="layui-form-label">
                    个人介绍
                </label>
                <div class="layui-input-inline">
                    <textarea type="text" id="info" name="info"  lay-verify="required" autocomplete="off" class="layui-input"><?php echo $list['info']; ?></textarea>
                </div>
            </div>
            <div class="layui-input-block">
                <button type="button" class="layui-btn" id="test1">上传头像</button>
                <div class="layui-upload-list">
                    <img class="layui-upload-img" src="http://cdn.lanyushiting.com/<?php echo $list['headimg']; ?>" id="demo1" style="height: 200px;">
                    <input class="headimg" type="hidden" name="headimg" value="<?php echo $list['headimg']; ?>" />
                </div>
            </div>
            <div class="layui-form-item">
                <label for="name" class="layui-form-label">
                    等级
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="level" name="level" value="<?php echo $list['level']; ?>" required="" lay-verify="required" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="name" class="layui-form-label">
                    余额
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="currency" name="currency" value="<?php echo $list['currency']; ?>" required="" lay-verify="required" autocomplete="off" class="layui-input">
                </div>
            </div>
            <?php if($list['type']==2): ?>
            <div class="layui-form-item">
                <label for="is_hot" class="layui-form-label">
                    <span class="we-red">*</span>是否允许无限接单
                </label>
                <div class="layui-input-block">
                    <input type="radio" <?php if($list['isunlimited']==1): ?> checked <?php endif; ?> name="isunlimited" lay-filter="isunlimited" value="1" title="是"> 
                    <input type="radio" <?php if($list['isunlimited']==2): ?> checked <?php endif; ?> name="isunlimited" lay-filter="isunlimited" value="2" title="否">
                </div> 
            </div>
            <?php endif; ?>
            <div class="layui-form-item">
                <label for="is_hot" class="layui-form-label">
                    <span class="we-red">*</span>状态
                </label>
                <div class="layui-input-block">
                    <input type="radio" <?php if($list['status']==1): ?> checked <?php endif; ?> name="status" lay-filter="type" value="1" title="正常"> 
                    <input type="radio" <?php if($list['status']==2): ?> checked <?php endif; ?> name="status" lay-filter="type" value="2" title="禁用">
                    <input type="radio" <?php if($list['status']==3): ?> checked <?php endif; ?> name="status" lay-filter="type" value="3" title="冻结">
                </div> 
            </div> 
            <div class="layui-form-item">
                <label for="L_repass" class="layui-form-label">
                </label>
                <button class="layui-btn" tyoe="submit" >修改</button>
                &nbsp;&nbsp;&nbsp;&nbsp;<a href="/admin/user/index"><button type="button" class="layui-btn">返回</button></a>
            </div>
        </form>
    </div>
    <script src="/static/admin/lib/layui/layui.js" charset="utf-8"></script>
    <script src="/static/admin/static/js/jquery.js" charset="utf-8"></script>
    <script type="text/javascript">
    layui.extend({
        admin: '/static/admin/static/js/admin'
    });
    layui.use(['form', 'layer', 'admin', 'upload','laydate'], function() {
        var form = layui.form,
            admin = layui.admin,
            layer = layui.layer;
            upload = layui.upload;
            laydate=layui.laydate;
            upload.render({
                elem: '#test1',
                url: "/admin/user/qinui_upload",
                done: function(res) {
                    $("#demo1").attr('src', 'http://cdn.lanyushiting.com/'+res.src);
                    $(".headimg").attr('value', res.src);
                }
            });
        laydate.render({
            elem: '#test1-1',
            lang: 'en',
            type : 'datetime'
        });
    });

    </script>
</body>

</html>