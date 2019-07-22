<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:92:"D:\phpserver\wwwroot\default\peini-formal\public/../application/union\view\union\member.html";i:1563507538;}*/ ?>
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
        <a href="<?php echo url('union/union/member'); ?>">公会管理</a>
        <a>
          <cite>公会成员</cite></a>
      </span>
        <a class="layui-btn layui-btn-sm" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
				<i class="layui-icon" style="line-height:30px">ဂ</i></a>
    </div>
    <div class="weadmin-body">
        <div class="weadmin-block">
            <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量踢出</button>
            <a href="<?php echo url('union/union/InviteUnion'); ?>">
                <button class="layui-btn"><i class="layui-icon"></i>邀请</button>
            </a>
            <span class="fr" style="line-height:40px">共有数据：<?php echo $total; ?> 条</span>
        </div>
        <table class="layui-table">
            <thead>
                <tr>
                    <th>
                        <div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div>
                    </th>
                    <th>编号</th>
                    <th>昵称</th>
                    <th>用户头像</th>
                    <th>性别</th>
                    <th>出生年月</th>
                    <th>城市</th>
                    <th>手机号</th>
                    <th>用户类型</th>
                    <th>账号等级</th>
                    <th>所选游戏</th>
                    <th>标签</th>
                    <th>接单数量</th>
                    <th>收益总和</th>
                    <th>入会时间</th>
                    <th>上次登录时间</th>
                    <th>操作</th>
            </thead>
            <tbody>
                <?php foreach($data as $key => $value): ?>
                    <td>
                        <div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id="<?php echo $value['uid']; ?>"><i class="layui-icon">&#xe605;</i></div>
                    </td>
                    <td><?php echo $value['number']; ?></td>
                    <td><?php echo $value['username']; ?></td>
                    <td>
                        <div class="layui-upload-list">
                            <div class='imgBox'><img src="http://cdn.lanyushiting.com/<?php echo $value['headimg']; ?>" class='wh100 ml2 mr2 screen' style="width: 100px;height: 100px;"></div>
                        </div>
                    </td>
                    <td class="td-status">
                        <?php if($value['sex'] == 1): ?>
                        男
                        <?php elseif($value['status'] == 2): ?>
                        女
                        <?php else: ?>
                        保密
                        <?php endif; ?>
                    </td>
                    <td>
                        <?=date('Y-m-d H:i:s',$value['birthday'])?>
                    </td>
                    <td><?php echo $value['city']; ?></td>
                    <td><?php echo $value['mobile']; ?></td>
                <td class="td-status">
                    <?php if($value['type'] == 1): ?>
                    普通用户
                    <?php else: ?>
                    陪玩用户
                    <?php endif; ?>
                </td>
                    <td><?php echo $value['level']; ?></td>
                    <td class="td-status">
                        <?php if(($value['game'] == '')): ?>
                        暂无游戏
                        <?php else: ?>
                        <?php echo $value['game']; endif; ?>
                    </td>
                    <td class="td-status">
                        <?php if(($value['label'] == '')): ?>
                        暂无标签
                        <?php else: ?>
                        <?php echo $value['label']; endif; ?>
                    </td>
                    <td class="td-status">
                        <?php if(($value['num'] == '')): ?>
                        暂无接单
                        <?php else: ?>
                        <?php echo $value['num']; endif; ?>
                    </td>
                    <td class="td-status">
                        <?php if(($value['all_profit'] == 0)): ?>
                        暂无收益
                        <?php else: ?>
                        <?php echo $value['all_profit']; endif; ?>
                    </td>
                    <td>
                        <?=date('Y-m-d H:i:s',$value['uniontime'])?>
                    </td>
                    <td>
                        <?=date('Y-m-d H:i:s',$value['lasttime'])?>
                    </td>
                    <td class="td-manage">
                        <a onclick="updateUnion(<?php echo $value['uid']; ?>)" href="javascript:;" title="踢出"><button class="layui-btn layui-btn-danger layui-btn-sm">踢出</button></a>
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
    //批量踢出
    function delAll() {
        var data = tableCheck.getData();
        layer.confirm('确认要踢出选中成员吗？', function(index) {
            // //捉到所有被选中的，发异步进行删除
            $.post("<?php echo url('union/union/delAllUnion'); ?>", { id: data, table: 'user' }, function(response) {
                layer.msg(response.msg, { time: 1000, icon: response.icon }, function() {
                    location.reload();
                })
            },'json');
        });
    }
    //单个踢出成员
    function updateUnion(id) {
        $.post("<?php echo url('union/union/updateUnion'); ?>", { uid: id}, function(data) {
            layer.msg(data.msg, { time: 1000, icon: data.icon }, function() {
                location.reload();
            });
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