<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:101:"D:\phpserver\wwwroot\default\peini-formal\public/../application/admin\view\withdrawal\withdrawal.html";i:1563009710;}*/ ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>提现管理</title>
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
        <a href="<?php echo url('admin/withdrawal/withdrawal'); ?>">订单管理</a>
        <a>
          <cite>提现管理</cite></a>
      </span>
        <a class="layui-btn layui-btn-sm" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
				<i class="layui-icon" style="line-height:30px">ဂ</i></a>
    </div>
    <div class="weadmin-body">
        <div class="weadmin-block">
            <span class="fr" style="line-height:40px">共有数据：<?php echo $total; ?> 条</span>
        </div>
        <table class="layui-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>用户昵称</th>
                    <th>提现编号</th>
                    <th>提现金额</th>
                    <th>申请时间</th>
                    <th>审核时间</th>
                    <th>状态</th>
                    <th>操作</th>
            </thead>
            <tbody>
                <?php foreach($data as $key => $value): ?>
                    <td><?php echo $value['wid']; ?></td>
                    <td><?php echo $value['uname']; ?></td>
                    <td><?php echo $value['tranno']; ?></td>
                    <td><?php echo $value['money']; ?></td>
                    <td>
                        <?=date('Y-m-d H:i:s',$value['addtime'])?>
                    </td>
                    <td>
                        <?php if(($value['audittime']=='' || $value['audittime']==0)): ?>
                            <span>暂未审核</span>
                        <?php else: ?>
                            <?=date('Y-m-d H:i:s',$value['audittime']);endif; ?>
                    </td>
                    <td class="td-status">
                        <?php if($value['status'] == 1): ?>
                        <span class="layui-btn layui-btn-normal layui-btn-sm">待审核</span>
                        <?php elseif($value['status'] == 2): ?>
                        <span class="layui-btn layui-btn-warm layui-btn-sm">审核成功</span>
                        <?php elseif($value['status'] == 3): ?>
                        <span class="layui-btn layui-btn-danger layui-btn-sm">审核失败</span>
                        <?php else: ?>
                        <span class="layui-btn layui-btn-normal layui-btn-sm">已完结</span>
                        <?php endif; ?>
                    </td>
                    <td class="td-manage">
                        <?php if(($value['status'] == 1)): ?>
                        <a onclick="updateWith(<?php echo $value['wid']; ?>,2)" href="javascript:;" title="审核成功"><button class="layui-btn layui-btn-warm layui-btn-sm">审核成功</button></a>
                        <a onclick="updateWith(<?php echo $value['wid']; ?>,3)" href="javascript:;" title="审核失败"><button class="layui-btn layui-btn-danger layui-btn-sm">审核失败</button></a>
                        <?php elseif(($value['status']==2)): ?>
                        <a href="javascript:;" title="待打款"><button class="layui-btn layui-btn-disabled layui-btn-sm">待打款</button></a>
                        <?php else: ?>
                        <a href="javascript:;" title="完结"><button class="layui-btn layui-btn-disabled layui-btn-sm">完结</button></a>
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
            $.post("<?php echo url('admin/withdrawal/delAllWith'); ?>", { id: data, table: 'withdrawal' }, function(response) {
                layer.msg(response.msg, { time: 1000, icon: response.icon }, function() {
                    location.reload();
                })
            },'json');
        });
    }
    //修改状态
    function updateWith(id, status) {
        $.post("<?php echo url('admin/withdrawal/updateWith'); ?>", { wid: id, status: status }, function(data) {
            layer.msg(data.msg, { time: 1000, icon: data.icon }, function() {
                location.reload();
            })
        }, 'json');
    }
    </script>
</body>

</html>