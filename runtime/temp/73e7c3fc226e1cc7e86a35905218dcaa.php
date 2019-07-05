<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:56:"D:\pei\public/../application/admin\view\user\update.html";i:1561686904;}*/ ?>
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
                    用户名称
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="username" name="username" value="<?php echo $list['username']; ?>" required="" lay-verify="required" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="name" class="layui-form-label">
                    联系电话（座机）
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="tel" name="tel" value="<?php echo $list['tel']; ?>"  lay-verify="required" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="name" class="layui-form-label">
                    联系电话（手机）
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="mobile" name="mobile" value="<?php echo $list['mobile']; ?>" required="" lay-verify="required" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="name" class="layui-form-label">
                    qq
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="qq" name="qq" value="<?php echo $list['qq']; ?>"  lay-verify="required" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="name" class="layui-form-label">
                    微信
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="wechat" name="wechat" value="<?php echo $list['wechat']; ?>"  lay-verify="required" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="name" class="layui-form-label">
                    邮箱
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="email" name="email" value="<?php echo $list['email']; ?>" required="" lay-verify="required" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="name" class="layui-form-label">
                    地址
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="address" name="address" value="<?php echo $list['address']; ?>"  lay-verify="required" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="name" class="layui-form-label">
                    允许创建项目数
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="allow" name="allow" value="<?php echo $list['allow']; ?>" required="" lay-verify="required" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="name" class="layui-form-label">
                    余额
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="money" name="money" value="<?php echo $list['money']; ?>" required="" lay-verify="required" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="is_hot" class="layui-form-label">
                    <span class="we-red">*</span>类型
                </label>
                <div class="layui-input-block">
                    <input type="radio" <?php if($list['type']==1): ?> checked <?php endif; ?> name="type" lay-filter="type" value="1" title="用户"> 
                    <input type="radio" <?php if($list['type']==2): ?> checked <?php endif; ?> name="type" lay-filter="type" value="2" title="企业">
                </div> 
            </div> 
            <div class="layui-form-item">
                <label for="is_hot" class="layui-form-label">
                    <span class="we-red">*</span>状态
                </label>
                <div class="layui-input-block">
                    <input type="radio" <?php if($list['status']==1): ?> checked <?php endif; ?> name="status" lay-filter="type" value="1" title="审核通过"> 
                    <input type="radio" <?php if($list['status']==2): ?> checked <?php endif; ?> name="status" lay-filter="type" value="2" title="禁用">
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
    layui.use(['form', 'layer', 'admin', 'upload'], function() {
        var form = layui.form,
            admin = layui.admin,
            layer = layui.layer;
            upload = layui.upload,

            upload.render({
                elem: '#test1',
                url: "/admin/market/editUploadImage",
                done: function(res) {
                    console.log(res.data.src);
                    $("#demo1").attr('src', res.data.src);
                    $(".thumb").attr('value', res.data.src);
                }
            });
    });
    </script>
</body>

</html>