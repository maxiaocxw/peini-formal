<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:89:"D:\phpserver\wwwroot\default\peini-formal\public/../application/admin\view\cate\work.html";i:1562569005;}*/ ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>职业列表</title>
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
        <a href="<?php echo url('admin/cate/work'); ?>">职业列表</a>
        <a>
          <cite>职业列表</cite></a>
      </span>
        <a class="layui-btn layui-btn-sm" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
				<i class="layui-icon" style="line-height:30px">ဂ</i></a>
    </div>
    <div class="weadmin-body">
        <!-- <div class="layui-row">
				<form class="layui-form layui-col-md12 we-search">
					<div class="layui-inline">
						<input class="layui-input" placeholder="开始日" name="start" id="start">
					</div>
					<div class="layui-inline">
						<input class="layui-input" placeholder="截止日" name="end" id="end">
					</div>
					<div class="layui-inline">
						<input type="text" name="username" placeholder="请输入用户名" autocomplete="off" class="layui-input">
					</div>
					<button class="layui-btn" lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
				</form>
			</div> -->
        <div class="weadmin-block">
            <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>
            <a href="<?php echo url('admin/cate/addWork'); ?>">
					<button class="layui-btn"><i class="layui-icon"></i>添加</button>
				</a>
            <span class="fr" style="line-height:40px">共有数据：<?php echo $total; ?> 条</span>
        </div>
        <table class="layui-table">
            <thead>
                <tr>
                    <th>
                        <div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div>
                    </th>
                    <th>ID</th>
                    <th>职业名称</th>
                    <th>添加时间</th>
                    <th>状态</th>
                    <th>操作</th>
            </thead>
            <tbody>
                <?php foreach($data as $key => $value): ?>
                    <td>
                        <div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id="<?php echo $value['wid']; ?>"><i class="layui-icon">&#xe605;</i></div>
                    </td>
                    <td><?php echo $value['wid']; ?></td>
                    <td><?php echo $value['name']; ?></td>
                    <td>
                        <?=date('Y-m-d H:i:s',$value['addtime'])?>
                    </td>
                    <td class="td-status">
                        <?php if(($value['status'] == 1)): ?>
                        <span class="layui-btn layui-btn-normal layui-btn-sm">已启用</span>
                    <?php else: ?>
                    <span class="layui-btn layui-btn-danger layui-btn-sm">已停用</span>
                    <?php endif; ?>
                    </td>
                    <td class="td-manage">
                        <a title="修改"  href="/admin/cate/updateWorks/wid/<?php echo $value['wid']; ?>"><button class="layui-btn layui-btn-normal layui-btn-sm">修改</button></a>
                        <?php if(($value['status'] == 1)): ?>
                        <a onclick="updateWork(<?php echo $value['wid']; ?>,-1)" href="javascript:;" title="禁用"><button class="layui-btn layui-btn-danger layui-btn-sm">禁用</button></a>
                        <?php else: ?>
                        <a onclick="updateWork(<?php echo $value['wid']; ?>,1)" href="javascript:;" title="启用"><button class="layui-btn layui-btn-normal layui-btn-sm">启用</button></a>
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
            $.post("<?php echo url('admin/cate/delAllWork'); ?>", { wid: data, table: 'work' }, function(response) {
                layer.msg(response.msg, { time: 1000, icon: response.icon }, function() {
                    location.reload();
                })
            },'json');
        });
    }
    //单条删除
    function delWork(id){
        $.post('/admin/cate/delWork',{id:id},function(data){
            layer.msg(data.msg,{icon:data.icon,time:1000},function(){
                location.reload();
            })
        },'json');
    }
    //修改状态
    function updateWork(id, status) {
        $.post("<?php echo url('admin/cate/updateWork'); ?>", { wid: id, status: status }, function(data) {
            layer.msg(data.msg, { time: 1000, data:icon }, function() {
                location.reload();
            })
        }, 'json');
    }
    //修改数据信息
    function updateWorks(id) {
        $.post("<?php echo url('admin/cate/updateWorks'); ?>", { wid: id }, function(data) {
            layer.msg(data.msg, { time: 1000, data:icon }, function() {
                location.reload();
            })
        }, 'json');
    }
    </script>
</body>

</html>