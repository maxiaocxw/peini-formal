<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:75:"E:\Project\peini-formal\public/../application/admin\view\index\welcome.html";i:1562150096;}*/ ?>
<!DOCTYPE html>
<html>

	<head>
		<meta charset="UTF-8">
		<title>Norrh  -  USA后台管理系统-1.0</title>
		<meta name="renderer" content="webkit">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
		<link rel="stylesheet" href="/static/admin/static/css/font.css">
		<link rel="stylesheet" href="/static/admin/static/css/weadmin.css">


	</head>

	<body>
		<div class="weadmin-body">
			<blockquote class="layui-elem-quote">欢迎使用Norrh  -  USA 后台模版！</blockquote>
			
			<div class="layui-col-lg12 layui-collapse" style="border: none;">
				<div class="layui-col-lg12 layui-col-md12">
					<!--统计信息展示-->
					<fieldset class="layui-elem-field" style="padding: 5px;">
						<div class="">
							
							<table class="layui-table">
								<thead>
									<tr>
										<th colspan="2" scope="col">服务器信息</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>服务器IP地址</td>
										<td><?php echo $_SERVER['SERVER_ADDR'];?></td>
									</tr>
									<tr>
										<td>服务器域名</td>
										<td><?php echo $_SERVER['SERVER_NAME'];?></td>
									</tr>
									<tr>
										<td>系统时间 </td>
										<td ><?php echo date("Y-m-d H:i:s",time());?></td>
									</tr>
								</tbody>
							</table>
						</div>
					</fieldset>
				</div>

			</div>

		</div>
	</body>
	<script type="text/javascript" src="/static/admin/lib/layui/layui.js" charset="utf-8"></script>
	<script type="text/javascript">
		layui.extend({
			admin: '/static/admin/static/js/admin',
		});
		layui.use(['jquery', 'element','util', 'admin', 'carousel'], function() {
			var element = layui.element,
				$ = layui.jquery,
				carousel = layui.carousel,
				util = layui.util,
				admin = layui.admin;
			//建造实例
			carousel.render({
				elem: '.weadmin-shortcut'
				,width: '100%' //设置容器宽度				
				,arrow: 'none' //始终显示箭头	
				,trigger: 'hover'
				,autoplay:false
			});
			
			carousel.render({
				elem: '.weadmin-notice'
				,width: '100%' //设置容器宽度				
				,arrow: 'none' //始终显示箭头	
				,trigger: 'hover'
				,autoplay:true
			});
			
			$(function(){
				setTimeAgo(2018,0,1,13,14,0,'#firstTime');
				setTimeAgo(2018,2,28,16,0,0,'#lastTime');
			});
			function setTimeAgo(y, M, d, H, m, s,id){
			    var str = util.timeAgo(new Date(y, M||0, d||1, H||0, m||0, s||0));
			    $(id).html(str);
			    // console.log(str);
			 };
		});
	</script>

</html>