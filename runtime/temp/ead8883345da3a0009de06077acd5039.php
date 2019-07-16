<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:91:"F:\phpserver\wwwroot\default\peini-formal\public/../application/admin\view\index\index.html";i:1563293877;s:81:"F:\phpserver\wwwroot\default\peini-formal\application\admin\view\public\menu.html";i:1563293877;}*/ ?>
<!doctype html>
<html lang="en">

	<head>
		<meta charset="UTF-8">
		<title>后台管理系统</title>
		<meta name="renderer" content="webkit|ie-comp|ie-stand">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
		<meta http-equiv="Cache-Control" content="no-siteapp" />
		<link rel="shortcut icon" href="./favicon.ico" type="image/x-icon" />
		<link rel="stylesheet" href="/static/admin/static/css/font.css">
		<link rel="stylesheet" href="/static/admin/static/css/weadmin.css">
		<script type="text/javascript" src="/static/admin/lib/layui/layui.js" charset="utf-8"></script>

	</head>

	<body>
		<!-- 顶部开始 -->
		<div class="container">
			<div class="logo">
				<a href="<?php echo url('admin/index/index'); ?>">陪你</a>
			</div>
			<div class="left_open">
				<i title="展开左侧栏" class="iconfont">&#xe699;</i>
			</div>
			
			<ul class="layui-nav right" lay-filter="">
				<li class="layui-nav-item">
					<a href="javascript:;">Admin</a>
					<!-- <dl class="layui-nav-child">
						<dd>
							<a onclick="WeAdminShow('个人信息','http://www.baidu.com')">个人信息</a>
						</dd>
						<dd>
							<a onclick="WeAdminShow('切换帐号','./login.html')">切换帐号</a>
						</dd>
						<dd>
							<a class="loginout" href="login.html">退出</a>
						</dd>
					</dl> -->
				</li>
				<li class="layui-nav-item to-index">
					<a href="/">前台首页</a>
				</li>
				<li class="layui-nav-item to-index">
					<a href="/admin/admin/loginout">退出</a>
				</li>
			</ul>
		</div>
		<!-- 顶部结束 -->
		<!-- 中部开始 -->
		<!-- 左侧菜单开始 -->
		<div class="left-nav">
			<div id="side-nav">
				<ul id="nav">
					<?php foreach($menu_list_one as $menu_key => $menu_value): if((\think\Session::get('admin.type') || in_array($menu_value['id'],$menu_role))): ?>
					<li>
						<a href="javascript:;">
							<cite><?php echo $menu_value['title']; ?></cite>
							<i class="iconfont nav_right">&#xe697;</i>
						</a>
						<ul class="sub-menu">
							<?php foreach($menu_list_two as $menu_key_two => $menu_value_two): if(($menu_value_two['mid'] == $menu_value['id'] && (\think\Session::get('admin.type') || in_array($menu_value_two['id'],$menu_role) ))): ?>
							<li>
								<a _href="<?php echo $menu_value_two['action']; ?>">
									<i class="iconfont">&#xe6a7;</i>
									<cite><?php echo $menu_value_two['title']; ?></cite>
								</a>
							</li>
							<?php endif; endforeach; ?>
						</ul>
					</li>
					<?php endif; endforeach; ?>
					<!-- <li>
						<a href="javascript:;">
							<i class="iconfont">&#xe705;</i>
							<cite>帖子管理</cite>
							<i class="iconfont nav_right">&#xe697;</i>
						</a>
						<ul class="sub-menu">
							<li>
								<a _href="<?php echo url('admin/article/index'); ?>">
									<i class="iconfont">&#xe6a7;</i>
									<cite>deal列表</cite>
								</a>
							</li>
							<li>
								<a _href="<?php echo url('admin/article/lifestyle'); ?>">
									<i class="iconfont">&#xe6a7;</i>
									<cite>liftstyle列表</cite>
								</a>
							</li>
						</ul>
					</li>
					<li>
						<a href="javascript:;">
							<i class="iconfont">&#xe723;</i>
							<cite>商品管理</cite>
							<i class="iconfont nav_right">&#xe697;</i>
						</a>
						<ul class="sub-menu">
							<li>
								<a _href="<?php echo url('admin/market/index'); ?>">
									<i class="iconfont">&#xe6a7;</i>
									<cite>market列表</cite>
								</a>
							</li>
						</ul>
					</li>
					<li>
						<a href="javascript:;">
							<i class="iconfont">&#xe723;</i>
							<cite>订单管理</cite>
							<i class="iconfont nav_right">&#xe697;</i>
						</a>
						<ul class="sub-menu">
							<li>
								<a _href="<?php echo url('admin/order/index'); ?>">
									<i class="iconfont">&#xe6a7;</i>
									<cite>订单列表</cite>
								</a>
							</li>
							<li>
								<a _href="<?php echo url('admin/order/putforward'); ?>">
									<i class="iconfont">&#xe6a7;</i>
									<cite>提现列表</cite>
								</a>
							</li>
						</ul>
					</li>
					<li>
						<a href="javascript:;">
							<i class="iconfont">&#xe726;</i>
							<cite>管理员管理</cite>
							<i class="iconfont nav_right">&#xe697;</i>
						</a>
						<ul class="sub-menu">
							<li>
								<a _href="<?php echo url('admin/admin/index'); ?>">
									<i class="iconfont">&#xe6a7;</i>
									<cite>管理员列表</cite>
								</a>
							</li>
							<li>
								<a _href="<?php echo url('admin/admin/role'); ?>">
									<i class="iconfont">&#xe6a7;</i>
									<cite>角色管理</cite>
								</a>
							</li>
							<li>
								<a _href="<?php echo url('admin/admin/permission'); ?>">
									<i class="iconfont">&#xe6a7;</i>
									<cite>权限管理</cite>
								</a>
							</li>
						</ul>
					</li>
					<li>
						<a href="javascript:;">
							<i class="iconfont">&#xe6ce;</i>
							<cite>系统统计</cite>
							<i class="iconfont nav_right">&#xe697;</i>
						</a>
						<ul class="sub-menu">
							<li>
								<a _href="./pages/echarts/echarts1.html">
									<i class="iconfont">&#xe6a7;</i>
									<cite>拆线图</cite>
								</a>
							</li>
							<li>
								<a _href="./pages/echarts/echarts2.html">
									<i class="iconfont">&#xe6a7;</i>
									<cite>柱状图</cite>
								</a>
							</li>
							<li>
								<a _href="./pages/echarts/echarts3.html">
									<i class="iconfont">&#xe6a7;</i>
									<cite>地图</cite>
								</a>
							</li>
							<li>
								<a _href="./pages/echarts/echarts4.html">
									<i class="iconfont">&#xe6a7;</i>
									<cite>饼图</cite>
								</a>
							</li>
							<li>
								<a _href="./pages/echarts/echarts5.html">
									<i class="iconfont">&#xe6a7;</i>
									<cite>雷达图</cite>
								</a>
							</li>
							<li>
								<a _href="./pages/echarts/echarts6.html">
									<i class="iconfont">&#xe6a7;</i>
									<cite>k线图</cite>
								</a>
							</li>
							<li>
								<a _href="./pages/echarts/echarts7.html">
									<i class="iconfont">&#xe6a7;</i>
									<cite>热力图</cite>
								</a>
							</li>
							<li>
								<a _href="./pages/echarts/echarts8.html">
									<i class="iconfont">&#xe6a7;</i>
									<cite>仪表图</cite>
								</a>
							</li>
							<li>
								<a _href="./pages/echarts/echarts9.html">
									<i class="iconfont">&#xe6a7;</i>
									<cite>地图DIY实例</cite>
								</a>
							</li>
						</ul>
					</li> -->
				</ul>
			</div>
		</div>
		<!-- <div class="x-slide_left"></div> -->
		<!-- 左侧菜单结束 -->
		
		<!-- 右侧主体开始 -->
		<div class="page-content">
			<div class="layui-tab tab" lay-filter="wenav_tab" id="WeTabTip" lay-allowclose="true">
				<ul class="layui-tab-title" id="tabName">
					<li>我的桌面</li>
				</ul>
				<div class="layui-tab-content">
					<div class="layui-tab-item layui-show">
						<iframe src="<?php echo url('admin/index/welcome'); ?>" frameborder="0" scrolling="yes" class="weIframe"></iframe>
					</div>
				</div>
			</div>
		</div>
		<div class="page-content-bg"></div>
		<!-- 右侧主体结束 -->
		<!-- 中部结束 -->
		<!-- 底部开始 -->
		<div class="footer">
			<div class="copyright">Copyright ©2018 WeAdmin v1.0 All Rights Reserved</div>
		</div>
		<!-- 底部结束 -->
		<script type="text/javascript">
//			layui扩展模块的两种加载方式-示例
//		    layui.extend({
//			  admin: '{/}../.__PUBLIC_ADMIN_STATIC__/static/js/admin' // {/}的意思即代表采用自有路径，即不跟随 base 路径
//			});
//			//使用拓展模块
//			layui.use('admin', function(){
//			  var admin = layui.admin;
//			});
			layui.config({
			  base: '/static/admin/static/js/'
			  ,version: '101100'
			}).use('admin');
			// layui.use(['jquery','admin'], function(){
				// var $ = layui.jquery;
				// $(function(){
				// 	var login = JSON.parse(localStorage.getItem("login"));
				// 	if(login){
				// 		if(login=0){
				// 			window.location.href='./login.html';
				// 			return false;
				// 		}else{
				// 			return false;
				// 		}
				// 	}else{
				// 		window.location.href='./login.html';
				// 		return false;
				// 	}
				// });
			// });

		</script>
	</body>
	<!--Tab菜单右键弹出菜单-->
	<ul class="rightMenu" id="rightMenu">
        <li data-type="fresh">刷新</li>
        <li data-type="current">关闭当前</li>
        <li data-type="other">关闭其它</li>
        <li data-type="all">关闭所有</li>
    </ul>

</html>