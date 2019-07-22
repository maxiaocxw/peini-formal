<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:97:"D:\phpserver\wwwroot\default\peini-formal\public/../application/admin\view\union\publicunion.html";i:1563006313;}*/ ?>
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
        <a href="">首页</a>
        <a href="<?php echo url('admin/union/publicunion'); ?>">公会管理</a>
        <a>
          <cite>对公公会</cite></a>
      </span>
        <a class="layui-btn layui-btn-sm" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
				<i class="layui-icon" style="line-height:30px">ဂ</i></a>
    </div>
    <div class="weadmin-body">
        <div class="weadmin-block">
            <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>
            <span class="fr" style="line-height:40px">共有数据：<?php echo $total; ?> 条</span>
        </div>
        <table class="layui-table">
            <thead>
                <tr>
                    <th>
                        <div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div>
                    </th>
                    <th>ID</th>
                    <th>公会名称</th>
                    <th>身份证正面</th>
                    <th>身份证反面</th>
                    <th>手持身份证</th>
                    <th>QQ</th>
                    <th>微信</th>
                    <th>电话</th>
                    <th>银行账号</th>
                    <th>开户行</th>
                    <th>许可证</th>
                    <th>添加时间</th>
                    <th>状态</th>
                    <th>操作</th>
            </thead>
            <tbody>
                <?php foreach($data as $key => $value): ?>
                    <td>
                        <div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id="<?php echo $value['unid']; ?>"><i class="layui-icon">&#xe605;</i></div>
                    </td>
                    <td><?php echo $value['unid']; ?></td>
                    <td><?php echo $value['name']; ?></td>
                    <td>
                        <div class="layui-upload-list">
                            <div class='imgBox'><img src="http://cdn.lanyushiting.com/<?php echo $value['idcodefront']; ?>" class='wh100 ml2 mr2 screen'></div>
                        </div>
                    </td>
                    <td>
                        <div class="layui-upload-list">
                            <div class='imgBox'><img src="http://cdn.lanyushiting.com/<?php echo $value['idcodereverse']; ?>" class='wh100 ml2 mr2 screen'></div>
                        </div>
                    </td>
                    <td>
                        <div class="layui-upload-list">
                            <div class='imgBox'><img src="http://cdn.lanyushiting.com/<?php echo $value['handidcode']; ?>" class='wh100 ml2 mr2 screen'></div>
                        </div>
                    </td>
                    <td><?php echo $value['qq']; ?></td>
                    <td><?php echo $value['wechat']; ?></td>
                    <td><?php echo $value['mobile']; ?></td>
                    <td><?php echo $value['bankcard']; ?></td>
                    <td><?php echo $value['subbranch']; ?></td>
                    <td>
                        <div class="layui-upload-list">
                            <div class='imgBox'><img src="http://cdn.lanyushiting.com/<?php echo $value['paymentname']; ?>" class='wh100 ml2 mr2 screen'></div>
                        </div>
                    </td>
                    <td>
                        <?=date('Y-m-d H:i:s',$value['addtime'])?>
                    </td>
                    <td class="td-status">
                        <?php if($value['status'] == 0): ?>
                        <span class="layui-btn layui-btn-normal layui-btn-sm">待审核</span>
                        <?php elseif($value['status'] == 1): ?>
                        <span class="layui-btn layui-btn-warm layui-btn-sm">已上线</span>
                        <?php elseif($value['status'] == 2): ?>
                        <span class="layui-btn layui-btn-danger layui-btn-sm">审核失败</span>
                        <?php else: ?>
                        <span class="layui-btn layui-btn-danger layui-btn-sm">封禁</span>
                        <?php endif; ?>
                    </td>
                    <td class="td-manage">
                        <?php if(($value['status'] == 0)): ?>
                        <a onclick="updateUnion(<?php echo $value['unid']; ?>,1)" href="javascript:;" title="审核成功"><button class="layui-btn layui-btn-warm layui-btn-sm">审核成功</button></a>
                        <a onclick="updateUnion(<?php echo $value['unid']; ?>,2)" href="javascript:;" title="审核失败"><button class="layui-btn layui-btn-danger layui-btn-sm">审核失败</button></a>
                        <?php elseif(($value['status'] == 1)): ?>
                        <a onclick="updateUnion(<?php echo $value['unid']; ?>,3)" href="javascript:;" title="禁用"><button class="layui-btn layui-btn-danger layui-btn-sm">禁用</button></a>
                        <?php else: ?>
                        <a onclick="updateUnion(<?php echo $value['unid']; ?>,3)" href="javascript:;" title="删除"><button class="layui-btn layui-btn-danger layui-btn-sm">删除</button></a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="page">
            <div>
                <?php echo $list->render(); ?>
            </div>
        </div>
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