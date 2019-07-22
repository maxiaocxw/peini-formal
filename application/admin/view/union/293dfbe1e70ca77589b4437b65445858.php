<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:97:"D:\phpserver\wwwroot\default\peini-formal\public/../application/union\view\union\update_info.html";i:1563785399;}*/ ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>公会管理</title>
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
    <div class="weadmin-nav">
        <span class="layui-breadcrumb">
        <a href="<?php echo url('union/union/unioninfo'); ?>">公会管理</a>
        <a>
          <cite>编辑公会</cite></a>
      </span>
        <a class="layui-btn layui-btn-sm" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
				<i class="layui-icon" style="line-height:30px">ဂ</i></a>
    </div>
    <div class="weadmin-body">
        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
            <legend>编辑<?php echo $union['name']; ?>公会信息</legend>
        </fieldset>
        <form class="layui-form layui-form-pane" action="">
            <input type="hidden" name="unid" value="<?php echo $union['unid']; ?>">
            <div class="layui-form-item">
                <label class="layui-form-label"><span style="color: red">*</span>公会名称</label>
                <div class="layui-input-inline">
                    <input type="text" name="name" lay-verify="required" placeholder="公会名称" autocomplete="off" class="layui-input" value="<?php echo $union['name']; ?>">
                </div>
            </div>
            <div class="layui-form-item layui-form-text">
                <label class="layui-form-label"><span style="color: red">*</span>公会介绍</label>
                <div class="layui-input-block">
                    <textarea placeholder="暂无介绍 立即添加" name="notice" class="layui-textarea"><?php if(($union['notice'] != '')): ?><?php echo $union['notice']; else: endif; ?></textarea>
                </div>
            </div>
            <div class="layui-form-item">
                <button class="layui-btn" lay-submit="" lay-filter="upda">保存</button>
                &nbsp;&nbsp;&nbsp;&nbsp;<a href="/union/union/unioninfo"><button type="button" class="layui-btn">返回</button></a>
            </div>
        </form>
    </div>
    <script src="/static/admin/static/js/jquery.js" charset="utf-8"></script>
    <script src="/static/admin/lib/layui/layui.js" charset="utf-8"></script>
    <script type="text/javascript" src="/static/admin/static/js/weadmin.js"></script>
    <script type="text/javascript">
    layui.use(['layer'], function() {
        var layer = layui.layer;
    });

    layui.use(['form', 'layer', 'upload'], function() {
        var form = layui.form,
            admin = layui.admin,
            layer = layui.layer;

        //监听提交
        form.on('submit(upda)', function(data) {
            $.post("<?php echo url('union/union/updateInfoDo'); ?>", data.field, function(response) {
                if (response.code == 0) {
                    layer.alert("修改成功", {
                        icon: response.icon
                    }, function() {
                        if(response.code == 0){
                            location.href = "<?php echo url('union/union/unioninfo'); ?>";
                        }else{
                            return false;
                        }
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