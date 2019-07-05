<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:90:"D:\phpserver\wwwroot\default\peini-formal\public/../application/admin\view\gift\index.html";i:1562322771;}*/ ?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title>管理员列表-WeAdmin Frame型后台管理系统-WeAdmin 1.0</title>
	<meta name="renderer" content="webkit">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
	<link rel="stylesheet" href="/static/admin/static/css/font.css">
	<link rel="stylesheet" href="/static/admin/static/css/weadmin.css">
</head>

<body>
<div class="weadmin-nav">
        <span class="layui-breadcrumb">
        <a href="">首页</a>
        <a href="">礼物管理</a>
        <a>
          <cite>礼物管理列表</cite></a>
      </span>
	<a class="layui-btn layui-btn-sm" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
		<i class="layui-icon" style="line-height:30px">ဂ</i></a>
</div>
<div class="weadmin-body">
	<div class="weadmin-block">
		<button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>
		<a title="修改"  href="/admin/gift/add"><button class="layui-btn admin_add"><i class="layui-icon"></i>添加礼物</button></a>
		<span class="fr" style="line-height:40px">共有数据：<?php echo $total; ?> 条</span>
	</div>
	<table class="layui-table">
		<thead>
		<tr>
			<th>
				<div class="layui-unselect header layui-form-checkbox" id="c_all" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div>
			</th>
			<th>ID</th>
			<th>礼物名称</th>
			<th>礼物价格</th>
			<th>礼物图片</th>
			<th>排序</th>
			<th>状态</th>
			<th>注册时间</th>
			<th>操作</th>
		</thead>
		<tbody>
		<?php foreach($list as $key => $value): ?>
		<tr>
			<td>
				<div class="layui-unselect layui-form-checkbox" id="admin_id" lay-skin="primary" data-id="<?php echo $value['gid']; ?>"><i class="layui-icon">&#xe605;</i></div>
			</td>
			<td><?php echo $value['gid']; ?></td>
			<td><?php echo $value['name']; ?></td>
			<td><?php echo $value['price']; ?></td>
			<td><img src="<?php echo $value['img']; ?>" style="height: 100px;max-width: 360px;"></td>
			<td><?php echo $value['order']; ?></td>
			<td>
				<?php if($value['status'] == 1): ?>
					<span class="layui-btn layui-btn-danger layui-btn-xs">正常</span></td>
				<?php elseif($value['status'] == 2): ?>
					<span class="layui-btn layui-btn-danger layui-btn-xs">禁用</span></td>
				<?php elseif($value['status'] == -1): ?>
					<span class="layui-btn layui-btn-danger layui-btn-xs">已删除</span></td>
				<?php endif; ?>
			</td>
			<td>
				<?=date('Y-m-d H:i:s',$value['addtime'])?>
			</td>
			<td class="td-manage">
				<?php if(($value['status'] == 1)): ?>
				<a onclick="updateWork(<?php echo $value['gid']; ?>,-1)" href="javascript:;" title="禁用"><button class="layui-btn layui-btn-danger layui-btn-sm">禁用</button></a>
				<?php else: ?>
				<a onclick="updateWork(<?php echo $value['gid']; ?>,1)" href="javascript:;" title="启用"><button class="layui-btn layui-btn-normal layui-btn-sm">启用</button></a>
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
<script src="/static/admin/static/js/weadmin.js" charset="utf-8"></script>
<script type="text/javascript">
	layui.use(['layer', 'laydate','form'], function() {
		var laydate = layui.laydate;
		var form = layui.form;
		//执行一个laydate实例
		laydate.render({
			elem: '#start' //指定元素
		});
		laydate.render({
			elem: '#end' //指定元素
		});
	});
	function delAll(){
		var data = tableCheck.getData();
		layer.confirm('确认要删除吗？',function(index){
			$.post("<?php echo url('admin/gift/delAll'); ?>",{id:data,table:'gift'},function(response){
				layer.msg(response.msg,{time:1000,icon:response.icon},function(){
					location.reload();
				})
			},'json');
		});
	}

	function updateWork(id, status) {
		$.post("<?php echo url('admin/gift/update'); ?>", { gid: id, status: status }, function(data) {
			layer.msg(data.msg, { time: 1000, data:icon }, function() {
				location.reload();
			})
		}, 'json');
	}




</script>
</body>

</html>