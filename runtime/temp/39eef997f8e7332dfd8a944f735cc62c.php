<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:97:"D:\phpserver\wwwroot\default\peini-formal\public/../application/admin\view\recharge\recharge.html";i:1563011185;}*/ ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>充值记录</title>
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
        <a href="<?php echo url('admin/recharge/recharge'); ?>">订单管理</a>
        <a>
          <cite>充值记录</cite></a>
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
                    <th>充值编号</th>
                    <th>充值金额</th>
                    <th>充值币数</th>
                    <th>支付方式</th>
                    <th>下单时间</th>
                    <th>支付时间</th>
                    <th>支付状态</th>
                    <th>订单状态</th>
            </thead>
            <tbody>
                <?php foreach($data as $key => $value): ?>
                    <td><?php echo $value['rid']; ?></td>
                    <td><?php echo $value['uname']; ?></td>
                    <td><?php echo $value['tranno']; ?></td>
                    <td><?php echo $value['money']; ?></td>
                    <td><?php echo $value['currencynum']; ?></td>
                    <td>
                        <?php if(($value['type']==1)): ?>
                        <span>微信</span>
                        <?php elseif(($value['type']==2)): ?>
                        <span>支付宝</span>
                        <?php else: ?>
                        <span>苹果</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?=date('Y-m-d H:i:s',$value['addtime'])?>
                    </td>
                    <td>
                        <?php if(($value['paytime']=='' || $value['paytime']==0)): ?>
                            <span>暂未支付</span>
                        <?php else: ?>
                            <?=date('Y-m-d H:i:s',$value['paytime']);endif; ?>
                    </td>
                <td>
                    <?php if(($value['paystatus']==1)): ?>
                    <span class="layui-btn layui-btn-warm layui-btn-sm">已支付</span>
                    <?php else: ?>
                    <span class="layui-btn layui-btn-danger layui-btn-sm">未支付</span>
                    <?php endif; ?>
                </td>
                    <td  class="td-status">
                        <?php if(($value['status']==1)): ?>
                            <span class="layui-btn layui-btn-normal layui-btn-sm">已完成</span>
                        <?php else: ?>
                            <span class="layui-btn layui-btn-danger layui-btn-sm">未完成</span>
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
    </script>
</body>

</html>