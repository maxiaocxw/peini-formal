<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:95:"D:\phpserver\wwwroot\default\peini-formal\public/../application/union\view\union\unioninfo.html";i:1563775359;}*/ ?>
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
          <cite>公会信息</cite></a>
      </span>
        <a class="layui-btn layui-btn-sm" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
				<i class="layui-icon" style="line-height:30px">ဂ</i></a>
    </div>
    <div class="weadmin-body">
        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
            <legend><?php echo $union['name']; ?>公会信息</legend>
        </fieldset>
        <form class="layui-form layui-form-pane" action="">
            <div class="layui-form-item">
                <label class="layui-form-label"><span style="color: red">*</span>公会名称</label>
                <div class="layui-input-inline">
                    <input type="text" name="username" lay-verify="required" placeholder="公会名称" autocomplete="off" class="layui-input" value="<?php echo $union['name']; ?>" readonly>
                </div>
            </div>
            <div class="layui-form-item layui-form-text">
                <label class="layui-form-label"><span style="color: red">*</span>公会介绍</label>
                <div class="layui-input-block">
                    <textarea placeholder="暂无介绍 点击编辑立即添加" readonly class="layui-textarea"><?php if(($union['notice'] != '')): ?><?php echo $union['notice']; else: endif; ?></textarea>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label"><span style="color: red">*</span>QQ</label>
                <div class="layui-input-inline">
                    <input type="text" name="username" lay-verify="required" placeholder="QQ" autocomplete="off" class="layui-input" value="<?php echo $union['qq']; ?>" readonly>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label"><span style="color: red">*</span>WeChat</label>
                <div class="layui-input-inline">
                    <input type="text" name="username" lay-verify="required" placeholder="WeChat" autocomplete="off" class="layui-input" value="<?php echo $union['wechat']; ?>" readonly>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label"><span style="color: red">*</span>手机号</label>
                <div class="layui-input-inline">
                    <input type="text" name="username" lay-verify="required" placeholder="手机号" autocomplete="off" class="layui-input" value="<?php echo $union['mobile']; ?>" readonly>
                </div>
            </div>
            <?php if(($union['bankcard'])): ?>
                <div class="layui-form-item">
                    <label class="layui-form-label"><span style="color: red">*</span>银行账号</label>
                    <div class="layui-input-inline">
                        <input type="text" name="username" lay-verify="required" placeholder="银行账号" autocomplete="off" class="layui-input" value="<?php echo $union['bankcard']; ?>" readonly>
                    </div>
                </div>
            <?php else: endif; if(($union['subbranch'])): ?>
            <div class="layui-form-item">
                <label class="layui-form-label"><span style="color: red">*</span>开户行</label>
                <div class="layui-input-inline">
                    <input type="text" name="username" lay-verify="required" placeholder="开户行" autocomplete="off" class="layui-input" value="<?php echo $union['subbranch']; ?>" readonly>
                </div>
            </div>
            <?php else: endif; if(($union['alpay'])): ?>
            <div class="layui-form-item">
                <label class="layui-form-label"><span style="color: red">*</span>支付宝</label>
                <div class="layui-input-inline">
                    <input type="text" name="username" lay-verify="required" placeholder="支付宝" autocomplete="off" class="layui-input" value="<?php echo $union['alpay']; ?>" readonly>
                </div>
            </div>
            <?php else: endif; ?>
            <div class="layui-form-item">
                <label class="layui-form-label"><span style="color: red">*</span>营业执照</label>
                <div class="layui-upload-list">
                    <div class='imgBox'><img src="http://cdn.lanyushiting.com/<?php echo $union['businesslicense']; ?>" style="width: 150px;height: 150px;" class='wh100 ml2 mr2 screen'></div>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label"><span style="color: red">*</span>注册时间</label>
                <div class="layui-input-inline">
                    <input type="text" name="username" lay-verify="required" placeholder="注册时间" autocomplete="off" class="layui-input" value="<?=date('Y-m-d H:i:s',$union['addtime'])?>" readonly>
                </div>
            </div>
            <div class="layui-form-item">
                <a title="修改"  href="/admin/cate/updateGames"><button class="layui-btn layui-btn-normal layui-btn">修改</button></a>
                <a title="解散"  href="/admin/cate/updateGames"><button class="layui-btn layui-btn-danger layui-btn">解散</button></a>
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
    //批量删除
    function delAll() {
        var data = tableCheck.getData();
        layer.confirm('确认要删除吗？', function(index) {
            // //捉到所有被选中的，发异步进行删除
            $.post("<?php echo url('admin/union/delAllUnion'); ?>", { id: data, table: 'union' }, function(response) {
                layer.msg(response.msg, { time: 1000, icon: response.icon }, function() {
                    location.reload();
                })
            },'json');
        });
    }
    //修改状态
    function updateUnion(id, status) {
        $.post("<?php echo url('admin/union/updateUnion'); ?>", { unid: id, status: status }, function(data) {
            layer.msg(data.msg, { time: 1000, icon: data.icon }, function() {
                location.reload();
            })
        }, 'json');
    }


    //图片放大展示
    $(".screen").bind('click', function() {
        var photos = $(this).parent().parent();
        layer.photos({
            photos: photos,
            anim: 5, //0-6的选择，指定弹出图片动画类型，默认随机（请注意，3.0之前的版本用shift参数）
            closeBtn: 1,
        });
    });
    </script>
</body>

</html>