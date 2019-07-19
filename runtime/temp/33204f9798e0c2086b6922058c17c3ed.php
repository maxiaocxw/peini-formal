<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:90:"D:\phpserver\wwwroot\default\peini-formal\public/../application/admin\view\cate\video.html";i:1563011607;}*/ ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>游戏列表</title>
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
        <a href="<?php echo url('admin/cate/video'); ?>">视频列表</a>
        <a>
          <cite>视频列表</cite></a>
      </span>
        <a class="layui-btn layui-btn-sm" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
				<i class="layui-icon" style="line-height:30px">ဂ</i></a>
    </div>
    <div id="outerdiv" style="position:fixed;top:0;left:0;background:rgba(0,0,0,0.7);z-index:2;width:100%;height:100%;display:none;">
        <div id="innerdiv" style="position:absolute;">
            <img id="bigimg" style="border:5px solid #fff;" src="" />
        </div>
    </div>
    <div class="weadmin-body">
        <div class="weadmin-block">
            <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>
            <!-- <a href="<?php echo url('admin/cate/addVideo'); ?>">
					<button class="layui-btn"><i class="layui-icon"></i>添加</button>
				</a> -->
            <span class="fr" style="line-height:40px">共有数据：<?php echo $total; ?> 条</span>
        </div>
        <table class="layui-table">
            <thead>
                <tr>
                    <th>
                        <div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div>
                    </th>
                    <th>ID</th>
                    <th>视频</th>
                    <th>视频封面</th>
                    <th>发布用户</th>
                    <th>排序</th>
                    <th>添加时间</th>
                    <th>是否推荐</th>
                    <th>状态</th>
                    <th>操作</th>
            </thead>
            <tbody>
                <?php foreach($data as $key => $value): ?>
                    <td>
                        <div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id="<?php echo $value['vid']; ?>"><i class="layui-icon">&#xe605;</i></div>
                    </td>
                    <td><?php echo $value['vid']; ?></td>
                    <td>
                        <video class="screen" width="300" height="250" controls="controls" src="http://cdn.lanyushiting.com/<?php echo $value['videourl']; ?>"></video>
                    </td>
                    <td>
                        <div class="layui-upload-list">
                            <div class='imgBox'><img src="http://cdn.lanyushiting.com/<?php echo $value['img']; ?>" class='wh100 ml2 mr2 screen'></div>
                        </div>
                    </td>
                    <td><?php echo $value['uname']; ?></td>
                    <td><?php echo $value['order']; ?></td>
                    <td>
                        <?=date('Y-m-d H:i:s',$value['addtime'])?>
                    </td>
                    <td>
                        <?php if(($value['isrecommend'] == 1)): ?>
                        <span>不推荐</span>
                        <?php else: ?>
                        <span>推荐</span>
                        <?php endif; ?>
                    </td>
                    <td class="td-status">
                        <?php if($value['status'] == 1): ?>
                            <span class="layui-btn layui-btn-normal layui-btn-sm">待审核</span>
                        <?php elseif($value['status'] == 2): ?>
                            <span class="layui-btn layui-btn-warm layui-btn-sm">已上线</span>
                        <?php elseif($value['status'] == 3): ?>
                            <span class="layui-btn layui-btn-danger layui-btn-sm">审核失败</span>
                        <?php else: ?>
                            <span class="layui-btn layui-btn-danger layui-btn-sm">已下线</span>
                        <?php endif; ?>
                    </td>
                    <td class="td-manage">
                        <?php if(($value['status'] == 1)): ?>
                            <a onclick="updateVideo(<?php echo $value['vid']; ?>,2)" href="javascript:;" title="审核成功"><button class="layui-btn layui-btn-warm layui-btn-sm">审核成功</button></a>
                            <a onclick="updateVideo(<?php echo $value['vid']; ?>,3)" href="javascript:;" title="审核失败"><button class="layui-btn layui-btn-danger layui-btn-sm">审核失败</button></a>
                        <?php elseif(($value['status'] == 2)): if(($value['isrecommend'] == 1)): ?>
                                <a onclick="upRecommend(<?php echo $value['vid']; ?>,2)" href="javascript:;" title="推荐"><button class="layui-btn layui-btn-normal layui-btn-sm">推荐</button></a>
                            <?php else: ?>
                                <a onclick="upRecommend(<?php echo $value['vid']; ?>,1)" href="javascript:;" title="不推"><button class="layui-btn layui-btn-danger layui-btn-sm">不推</button></a>
                            <?php endif; ?>
                            <a onclick="orderVideo(<?php echo $value['vid']; ?>)" href="javascript:;" title="排序"><button class="layui-btn layui-btn-sm">排序</button></a>
                            <a onclick="updateVideo(<?php echo $value['vid']; ?>,-1)" href="javascript:;" title="禁用"><button class="layui-btn layui-btn-danger layui-btn-sm">禁用</button></a>
                        <?php elseif(($value['status'] == 3)): ?>
                            <a onclick="updateVideo(<?php echo $value['vid']; ?>,-1)" href="javascript:;" title="删除"><button class="layui-btn layui-btn-danger layui-btn-sm">删除</button></a>
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
        var form = layui.form;
        var layer = layui.layer;
    });


    function orderVideo(id){
        str = "<div class='layui-row layui-form pd15'>";
        str += '<div class="layui-form">';
        str += "<div class='layui-form-item'><label class='layui-form-label'>视频ID:</label><div class='layui-input-inline'><input type='text' id='vid' autocomplete='off' readonly class='layui-input' readonly value='"+ id +"'></div></div>";
        str += "<div class='layui-form-item'><label class='layui-form-label'>排序:</label><div class='layui-input-inline'><input type='text' minlength='2' maxlength='3' id='order_num' placeholder='最小值为11 最大值为999 越小越排列在前' autocomplete='off' class='layui-input' value=''></div></div>";
        str += '</div>';
        str += '</div>';
        var index = layer.open({
            title:"编辑排序",
            area:['450px','325px'],
            content:str,
            btn:['增加','取消'],
            yes:function(){
                var vid = $.trim($('#vid').val());
                var order_num = $.trim($('#order_num').val());
                if( order_num == 0 || order_num == '' ){
                    layer.msg("请输入数字", {icon: 7, time:2000});
                    return false;
                }
                if( order_num<11 ){
                    layer.msg("最小值为11", {icon: 7, time:2000});
                    return false;
                }
                if( order_num>1000 ){
                    layer.msg("最大值为1000", {icon: 7, time:2000});
                    return false;
                }
                if(isNaN(order_num)){
                    layer.msg("请输入数字", {icon: 7, time:2000});
                    return false;
                }
                $.post(
                    "<?php echo url('admin/cate/upOrder'); ?>",
                    {
                        order:order_num,
                        vid:vid
                    },
                    function(response){
                        // console.log(response);return;
                        var response = $.parseJSON(response);
                        if(response.code == 0){
                            layer.msg(response.msg, {icon: response.icon, time:3000});
                            location.reload();
                        }else{
                            layer.msg(response.msg, { time: 1000, icon: response.icon });
                        }
                    }
                );
            },
            btn2:function(){
                layer.closeAll(index); //关闭当前窗口
            }
        });
    }

    //批量删除
    function delAll() {
        var data = tableCheck.getData();
        layer.confirm('确认要删除吗？', function(index) {
            // //捉到所有被选中的，发异步进行删除
            $.post("<?php echo url('admin/cate/delAllVideo'); ?>", { id: data, table: 'video' }, function(response) {
                layer.msg(response.msg, { time: 1000, icon: response.icon }, function() {
                    location.reload();
                })
            },'json');
        });
    }
    //修改状态
    function updateVideo(id, status) {
        $.post("<?php echo url('admin/cate/updateVideo'); ?>", { vid: id, status: status }, function(data) {
            layer.msg(data.msg, { time: 1000, icon: data.icon }, function() {
                location.reload();
            })
        }, 'json');
    }
    //修改推荐
    function upRecommend(id, status) {
        $.post("<?php echo url('admin/cate/upRecommend'); ?>", { vid: id, isrecommend: status }, function(data) {
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