<?php
namespace app\admin\controller;
use app\admin\controller\Auth;
use app\api\controller\Qiniu;
use think\Db;
use think\Request;
use think\Session;

class Cate extends Auth{
    public function _initialize(){
        $this->url='http://cdn.lanyushiting.com/';
    }

    //七牛图片上传
    public function qinui_upload(){
        $qiniu = new Qiniu();
        $file = $_FILES['file'];
        if( is_uploaded_file( $file['tmp_name'] ) ){
            //获取文件路径和文件后缀名
            $ext = pathinfo( $file['name'], PATHINFO_EXTENSION );
            //调用方法将图片上传到七牛
            $qiniu_res = $qiniu->upload( 'image', $ext, $file['tmp_name'] );
            //判断结果并返回完整路径
            if( $qiniu_res ){
                //将结果转化成数组获取key
                $qiniu_arr = json_decode( $qiniu_res , true );
                echo json_encode(['code' => 0,'msg' => '上传成功','icon' => 1,'src'=>$this->url.$qiniu_arr['key']]);
            }else{
                echo json_encode(['code' => 1,'msg' => '上传失败','icon' => 2]);
            }
        }else{
            echo json_encode(['code' => 1,'msg' => '上传错误','icon' => 2]);
        }
    }

    public function qinui_upload_video(){
        $qiniu = new Qiniu();
        $file = $_FILES['file'];
        if( is_uploaded_file( $file['tmp_name'] ) ){
            //获取文件路径和文件后缀名
            $ext = pathinfo( $file['name'], PATHINFO_EXTENSION );
            //调用方法将图片上传到七牛
            $qiniu_res = $qiniu->upload( 'video', $ext, $file['tmp_name'] );
            //判断结果并返回完整路径
            if( $qiniu_res ){
                //将结果转化成数组获取key
                $qiniu_arr = json_decode( $qiniu_res , true );
                echo json_encode(['code' => 0,'msg' => '上传成功','icon' => 1,'src'=>$this->url.$qiniu_arr['key']]);
            }else{
                echo json_encode(['code' => 1,'msg' => '上传失败','icon' => 2]);
            }
        }else{
            echo json_encode(['code' => 1,'msg' => '上传错误','icon' => 2]);
        }
    }

    //职业展示
    public function work(){
        //查询职业表的所有数据
        $work = Db::name('work')->order('status','desc')->order('addtime','desc')->paginate(30,false);
        //将结果转换成数组
        $work_data = $work->toArray();
        $this->assign('data',$work_data['data']);
        $this->assign('total',$work_data['total']);
        $this->assign('list',$work);
        return $this->fetch();
    }
    //职业添加
    public function addWork(){
        if(request()->isAjax()){
            $name = input('post.name');
            if(empty($name)){
                echo json_encode(['code' => 1,'msg' => '参数错误','icon' => 2]);
                exit;
            }else{
                $work_res = Db::name('work')->where(['name'=>$name,'status'=>1])->find();
                if( $work_res ){
                    echo json_encode(['code' => 1,'msg' => '职业名称已存在','icon' => 2]);
                }else{
                    $work_data['name'] = $name;
                    $work_data['addtime'] = time();
                    $work_data['status'] = 1;
                    $result = Db::name('work')->insert($work_data);
                    if($result){
                        echo json_encode(['code' => 0,'msg' => '添加成功','icon' => 6]);
                    }else{
                        echo json_encode(['code' => 1,'msg' => '添加失败','icon' => 2]);
                    }
                }
            }
        }else{
            return $this->fetch();
        }
    }
    //职业批量删除
    public function delAllWork(){
        if(input('?post.table')){
            $id = input('post.wid/a');
            if(empty($id)){
                echo json_encode(['code' => 1,'msg' => '请选择数据','icon' =>2]);
                exit;
            }else{
                $where['wid'] = array('IN',implode(',', $id));
                $work_data['status'] = -1;
                $result =Db::name(trim(input('post.table')))->where($where)->update($work_data);
                if($result){
                    echo json_encode(['code' => 0,'msg' => '删除成功','icon' => 1]);
                }else{
                    echo json_encode(['code' => 1,'msg' => '删除失败','icon' => 2]);
                }
            }
        }else{
            echo json_encode(['code' => 1,'msg' =>'禁止非法操作','icon' => 3]);
        }
    }
    //职业修改状态
    public function updateWork(){
        if(request()->isPost()){
            $data = input('post.');
            $where['wid'] = array('EQ',$data['wid']);
            $result = Db::name('work')->where($where)->update($data);
            if($result){
                echo json_encode(['code' => 0,'msg' => '修改成功','icon' =>1]);
            }else{
                echo json_encode(['code' => 1,'msg' => '修改失败','icon' =>2]);
            }
        }
    }
    //用户职业修改内容
    public function updateWorks(){
        $work_data=Db::name('work')->where('wid='.input('wid'))->find();
        $this->assign('data',$work_data);
        return $this->fetch();
    }
    public function updateWorksDo(){
        $req=input('post.');
        if( $req ){
            $work_name = Db::name('work')->where( [ 'name'=>$req['name'], 'status'=>1 ] )->find();
            if( $work_name ){
                echo json_encode(['code' => 1,'msg' => '职业存在','icon' =>2]);
            }else{
                $res = Db::name('work')->where('wid='.$req['wid'])->update($req);
                if( $res ){
                    echo json_encode(['code' => 0,'msg' => '修改成功','icon' =>1]);
                }else{
                    echo json_encode(['code' => 1,'msg' => '修改失败','icon' =>2]);
                }
            }

        }else{
            echo (['code' => 1,'msg' => '参数错误','icon' =>2]);
        }

    }


    //兴趣展示
    public function interest(){
        //查询职业表的所有数据
        $interest = Db::name('interest')->order('status','desc')->order('addtime','desc')->paginate(30,false);
        //将结果转换成数组
        $work_data = $interest->toArray();
        $this->assign('data',$work_data['data']);
        $this->assign('total',$work_data['total']);
        $this->assign('list',$interest);
        return $this->fetch();
    }
    //兴趣添加
    public function addInterest(){
        if(request()->isAjax()){
            $name = input('post.name');
            if(empty($name)){
                echo json_encode(['code' => 1,'msg' => '参数错误','icon' => 2]);
                exit;
            }else{
                $interest_res = Db::name('interest')->where(['name'=>$name,'status'=>1])->find();
                if( $interest_res ){
                    echo json_encode(['code' => 1,'msg' => '兴趣名称已存在','icon' => 2]);
                }else{
                    $interest_data['name'] = $name;
                    $interest_data['addtime'] = time();
                    $interest_data['status'] = 1;
                    $result = Db::name('interest')->insert($interest_data);
                    if($result){
                        echo json_encode(['code' => 0,'msg' => '添加成功','icon' => 6]);
                    }else{
                        echo json_encode(['code' => 1,'msg' => '添加失败','icon' => 2]);
                    }
                }

            }
        }else{
            return $this->fetch();
        }
    }
    //兴趣批量删除
    public function delAllInterest(){
        if(input('?post.table')){
            $id = input('post.id/a');
            if(empty($id)){
                echo json_encode(['code' => 1,'msg' => '请选择数据','icon' =>2]);
                exit;
            }else{
                $where['nid'] = array('IN',implode(',', $id));
                $interest_data['status'] = -1;
                $result =Db::name(trim(input('post.table')))->where($where)->update($interest_data);
                if($result){
                    echo json_encode(['code' => 0,'msg' => '删除成功','icon' => 1]);
                }else{
                    echo json_encode(['code' => 1,'msg' => '删除失败','icon' => 2]);
                }
            }
        }else{
            echo json_encode(['code' => 1,'msg' =>'禁止非法操作','icon' => 3]);
        }
    }
    //兴趣修改
    public function updateInterest(){
        if(request()->isPost()){
            $data = input('post.');
            $where['nid'] = array('EQ',$data['nid']);
            $result = Db::name('interest')->where($where)->update($data);
            if($result){
                echo json_encode(['code' => 0,'msg' => '修改成功','icon' =>1]);
            }else{
                echo json_encode(['code' => 1,'msg' => '修改失败','icon' =>2]);
            }
        }
    }
    //用户职业修改内容
    public function updateInterests(){
        $interest_data=Db::name('interest')->where('nid='.input('nid'))->find();
        $this->assign('data',$interest_data);
        return $this->fetch();
    }
    public function updateInterestsDo(){
        $req=input('post.');
        if( $req ){
            $insterest_name = Db::name('work')->where( [ 'name'=>$req['name'], 'status'=>1 ] )->find();
            if( $insterest_name ){
                echo json_encode(['code' => 1,'msg' => '兴趣存在','icon' =>2]);
            }else{
                $res = Db::name('interest')->where('nid='.$req['nid'])->update($req);
                if( $res ){
                    echo json_encode(['code' => 0,'msg' => '修改成功','icon' =>1]);
                }else{
                    echo json_encode(['code' => 1,'msg' => '修改失败','icon' =>2]);
                }
            }

        }else{
            echo (['code' => 1,'msg' => '参数错误','icon' =>2]);
        }

    }


    //游戏展示
    public function game(){
        //查询所有数据
        $game = Db::name('game g')->field('g.*,t.name as tname')->join( 'game_type t', 'g.tid = t.tid' )->order('status','desc')->order('addtime','desc')->paginate(30,false);
        //将结果转换成数组
        $game_data = $game->toArray();
        $this->assign('data',$game_data['data']);
        $this->assign('total',$game_data['total']);
        $this->assign('list',$game);
        return $this->fetch();
    }
    //游戏添加
    public function addGame(){
        if(request()->isAjax()){
            $req = input('post.');
            if(empty($req)){
                echo json_encode(['code' => 1,'msg' => '参数错误','icon' => 2]);
                exit;
            }else{
                $game_exits = Db::name('game')->where(['name'=>$req['name'],'status'=>1])->find();
                if( $game_exits ){
                    echo json_encode(['code' => 1,'msg' => '游戏已存在！','icon' => 2]);
                }else{
                    $game_data['name'] = $req['name'];
                    $game_data['info'] = $req['info'];
                    $game_data['img'] = $req['img'];
                    $game_data['tid'] = $req['tid'];
                    $game_data['addtime'] = time();
                    $game_data['status'] = 1;
                    $result = Db::name('game')->insert($game_data);
                    if($result){
                        echo json_encode(['code' => 0,'msg' => '添加成功','icon' => 6]);
                    }else{
                        echo json_encode(['code' => 1,'msg' => '添加失败','icon' => 2]);
                    }
                }
            }
        }else{
            //查询所有数据
            $gametype = Db::name('game_type')->select();
            $this->assign('gametype_data',$gametype);
            return $this->fetch();
        }
    }
    //游戏删除
    public function delAllGame(){
        if(input('?post.table')){
            $id = input('post.id/a');
            if(empty($id)){
                echo json_encode(['code' => 1,'msg' => '请选择数据','icon' =>2]);
                exit;
            }else{
                $where['gid'] = array('IN',implode(',', $id));
                $game_data['status'] = -1;
                $result =Db::name(trim(input('post.table')))->where($where)->update($game_data);
                if($result){
                    echo json_encode(['code' => 0,'msg' => '删除成功','icon' => 1]);
                }else{
                    echo json_encode(['code' => 1,'msg' => '删除失败','icon' => 2]);
                }
            }
        }else{
            echo json_encode(['code' => 1,'msg' =>'禁止非法操作','icon' => 3]);
        }
    }
    //游戏修改状态
    public function updateGame(){
        if(request()->isPost()){
            $data = input('post.');
            $where['gid'] = array('EQ',$data['gid']);
            $result = Db::name('game')->where($where)->update($data);
            if($result){
                echo json_encode(['code' => 0,'msg' => '修改成功','icon' => 1]);
            }else{
                echo json_encode(['code' => 1,'msg' => '修改失败','icon' => 2]);
            }
        }
    }
    //游戏修改内容
    public function updateGames(){
        $game_data=Db::name('game')->where('gid='.input('gid'))->find();
        $gametype = Db::name('game_type')->select();
        $this->assign('gametype_data',$gametype);
        $this->assign('data',$game_data);
        return $this->fetch();
    }
    public function updateGamesDo(){
        $req=input('post.');
        if( $req ){
            //判断名称是否存在
            $game_name = Db::name('game')->where(['name'=>$req['name'],'stauts'=>1])->find();
            if( $game_name ){
                echo json_encode(['code'=>1,'msg'=>'游戏名称已存在','icon'=>2]);
            }else{
                $res = Db::name('game')->where('gid='.$req['gid'])->update($req);
                if( $res ){
                    echo json_encode(['code' => 0,'msg' => '修改成功','icon' =>1]);
                }else{
                    echo json_encode(['code' => 1,'msg' => '修改失败','icon' =>2]);
                }
            }
        }else{
            echo (['code' => 1,'msg' => '参数错误','icon' =>2]);
        }
    }


    //游戏分类展示
    public function gametype(){
        //查询所有数据
        $gametype = Db::name('game_type')->order('status','desc')->order('addtime','desc')->paginate(30,false);
        //将结果转换成数组
        $gametype_data = $gametype->toArray();
        $this->assign('data',$gametype_data['data']);
        $this->assign('total',$gametype_data['total']);
        $this->assign('list',$gametype);
        return $this->fetch();
    }
    //游戏分类添加
    public function addGametype(){
        if(request()->isAjax()){
            $name = input('post.name');
            if(empty($name)){
                echo json_encode(['code' => 1,'msg' => '参数错误','icon' => 2]);
                exit;
            }else{
                $interest_res = Db::name('game_type')->where(['name'=>$name,'status'=>1])->find();
                if( $interest_res ){
                    echo json_encode(['code' => 1,'msg' => '游戏分类已存在','icon' => 2]);
                }else{
                    $game_type_data['name'] = $name;
                    $game_type_data['addtime'] = time();
                    $game_type_data['status'] = 1;
                    $result = Db::name('game_type')->insert($game_type_data);
                    if($result){
                        echo json_encode(['code' => 0,'msg' => '添加成功','icon' => 6]);
                    }else{
                        echo json_encode(['code' => 1,'msg' => '添加失败','icon' => 2]);
                    }
                }

            }
        }else{
            return $this->fetch();
        }
    }
    //游戏分类删除
    public function delAllGametype(){
        if(input('?post.table')){
            $id = input('post.id/a');
            if(empty($id)){
                echo json_encode(['code' => 1,'msg' => '请选择数据','icon' =>2]);
                exit;
            }else{
                $where['tid'] = array('IN',implode(',', $id));
                $game_type_data['status'] = -1;
                $result =Db::name(trim(input('post.table')))->where($where)->update($game_type_data);
                if($result){
                    echo json_encode(['code' => 0,'msg' => '删除成功','icon' => 1]);
                }else{
                    echo json_encode(['code' => 1,'msg' => '删除失败','icon' => 2]);
                }
            }
        }else{
            echo json_encode(['code' => 1,'msg' =>'禁止非法操作','icon' => 3]);
        }
    }
    //游戏分类修改状态
    public function updateGametype(){
        if(request()->isPost()){
            $data = input('post.');
            $where['tid'] = array('EQ',$data['tid']);
            $result = Db::name('game_type')->where($where)->update($data);
            if($result){
                echo json_encode(['code' => 0,'msg' => '修改成功','icon' => 1]);
            }else{
                echo json_encode(['code' => 1,'msg' => '修改失败','icon' => 2]);
            }
        }
    }
    //游戏分类修改内容
    public function updateGametypes(){
        $game_type_data=Db::name('game_type')->where('tid='.input('tid'))->find();
        $this->assign('data',$game_type_data);
        return $this->fetch();
    }
    public function updateGametypesDo(){
        $req=input('post.');
        if( $req ){
            $type_name = Db::name('game_type')->where( ['name'=>$req['name'], 'status'=>1] )->find();
            if( $type_name ){
                echo json_encode(['code' => 1,'msg' => '分类已存在','icon' =>2]);
            }else{
                $res = Db::name('game_type')->where('tid='.$req['tid'])->update($req);
                if( $res ){
                    echo json_encode(['code' => 0,'msg' => '修改成功','icon' =>1]);
                }else{
                    echo json_encode(['code' => 1,'msg' => '修改失败','icon' =>2]);
                }
            }
        }else{
            echo json_encode(['code' => 1,'msg' => '参数错误','icon' =>2]);
        }
    }


    //游戏价格展示
    public function gameprice(){
        //查询所有数据
        $gameprice = Db::name('game_price p')->field('p.*,g.name as gname')->join( 'game g', 'p.gid = g.gid' )->order('status','desc')->order('price','asc')->paginate(30,false);
        //将结果转换成数组
        $gameprice_data = $gameprice->toArray();
        $this->assign('data',$gameprice_data['data']);
        $this->assign('total',$gameprice_data['total']);
        $this->assign('list',$gameprice);
        return $this->fetch();
    }
    //游戏价格添加
    public function addGameprice(){
        if(request()->isAjax()){
            $req = input('post.');
            if(empty($req)){
                echo json_encode(['code' => 1,'msg' => '参数错误','icon' => 2]);
                exit;
            }else{
                //先判断页面传过来的值是否是多个
//                if( strpos( $req['price'], ',' ) ){
//                    $price_data = explode( ',', $req['price'] );
//                    foreach( $price_data as $k => $v ){
//                        //判断每个单价是否已经存在
//                        $price_exits = Db::name('game_price') -> where( ['price'=>$v, 'gid'=>$req['gid']] ) -> find();
//                        if( $price_exits ){
//                            echo json_encode(['code' => 1,'msg' => '价格存在！请重新添加','icon' => 2]);
//                            die;
//                        }else{
//                            $game_price_data['price'] = $v;
//                            $game_price_data['gid'] = $req['gid'];
//                            $game_price_data['addtime'] = time();
//                            $game_price_data['status'] = 1;
//                            $result = Db::name('game_price')->insert($game_price_data);
//                            if($result){
//                                echo json_encode(['code' => 0,'msg' => '添加成功','icon' => 6]);
//                                die;
//                            }else{
//                                echo json_encode(['code' => 1,'msg' => '添加失败','icon' => 2]);
//                            }
//                        }
//                    }
//                }
                $price_exits_agin = Db::name('game_price')->where( ['price'=>$req['price'], 'gid'=>$req['gid'],'status'=>1] )->find();
                if( $price_exits_agin ){
                    echo json_encode(['code' => 1,'msg' => '价格'.$req['price'].'已存在！请重新添加','icon' => 2]);
                }else{
                    $game_price_data['price'] = $req['price'];
                    $game_price_data['gid'] = $req['gid'];
                    $game_price_data['addtime'] = time();
                    $game_price_data['status'] = 1;
                    $result = Db::name('game_price')->insert($game_price_data);
                    if($result){
                        echo json_encode(['code' => 0,'msg' => '添加成功','icon' => 6]);
                    }else{
                        echo json_encode(['code' => 1,'msg' => '添加失败','icon' => 2]);
                    }
                }
            }
        }else{
            //查询所有数据
            $gamedata = Db::name('game')->select();
            $this->assign('game_data',$gamedata);
            return $this->fetch();
        }
    }
    //游戏价格删除
    public function delAllGameprice(){
        if(input('?post.table')){
            $id = input('post.id/a');
            if(empty($id)){
                echo json_encode(['code' => 1,'msg' => '请选择数据','icon' =>2]);
                exit;
            }else{
                $where['pid'] = array('IN',implode(',', $id));
                $game_price_data['status'] = -1;
                $result =Db::name(trim(input('post.table')))->where($where)->update($game_price_data);
                if($result){
                    echo json_encode(['code' => 0,'msg' => '删除成功','icon' => 1]);
                }else{
                    echo json_encode(['code' => 1,'msg' => '删除失败','icon' => 2]);
                }
            }
        }else{
            echo json_encode(['code' => 1,'msg' =>'禁止非法操作','icon' => 3]);
        }
    }
    //游戏价格修改状态
    public function updateGameprice(){
        if(request()->isPost()){
            $data = input('post.');
            $where['pid'] = array('EQ',$data['pid']);
            $result = Db::name('game_price')->where($where)->update($data);
            if($result){
                echo json_encode(['code' => 0,'msg' => '修改成功','icon' => 1]);
            }else{
                echo json_encode(['code' => 1,'msg' => '修改失败','icon' => 2]);
            }
        }
    }
    //游戏价格修改内容
    public function updateGameprices(){
        $gameprice = Db::name('game_price p')->field('p.*,g.name as gname')->join( 'game g', 'p.gid = g.gid' )->where('p.pid='.input('pid'))->find();
        $this->assign('data',$gameprice);
        return $this->fetch();
    }
    public function updateGamepricesDo(){
        $req=input('post.');
        if( $req ){
            //判断该游戏下的价格是否存在
            $game_price = Db::name('game_price')->where( ['gid'=>$req['gid'], 'price'=>$req['price'], 'status'=>1] )->find();
            if( $game_price ){
                echo json_encode(['code' => 1,'msg' => '价格存在！','icon' =>2]);
            }else{
                $res = Db::name('game_price')->where('pid='.$req['pid'])->update($req);
                if( $res ){
                    echo json_encode(['code' => 0,'msg' => '修改成功','icon' =>1]);
                }else{
                    echo json_encode(['code' => 1,'msg' => '修改失败','icon' =>2]);
                }
            }

        }else{
            echo (['code' => 1,'msg' => '参数错误','icon' =>2]);
        }
    }


    //用户视频展示
    public function video(){
        //查询所有数据
        $video = Db::name('video v')->field('v.*,u.username as uname')->join( 'user u', 'v.uid = u.uid' )->order('status','asc')->order('isrecommend','desc')->order('order','desc')->paginate(30,false);
        //将结果转换成数组
        $video_data = $video->toArray();
        $this->assign('data',$video_data['data']);
        $this->assign('total',$video_data['total']);
        $this->assign('list',$video);
        return $this->fetch();
    }
    //视频删除
    public function delAllVideo(){
        if(input('?post.table')){
            $id = input('post.id/a');
            if(empty($id)){
                echo json_encode(['code' => 1,'msg' => '请选择数据','icon' =>2]);
                exit;
            }else{
                $where['vid'] = array('IN',implode(',', $id));
                $video_data['status'] = -1;
                $result =Db::name(trim(input('post.table')))->where($where)->update($video_data);
                if($result){
                    echo json_encode(['code' => 0,'msg' => '删除成功','icon' => 1]);
                }else{
                    echo json_encode(['code' => 1,'msg' => '删除失败','icon' => 2]);
                }
            }
        }else{
            echo json_encode(['code' => 1,'msg' =>'禁止非法操作','icon' => 3]);
        }
    }
    //视频修改状态
    public function updateVideo(){
        if(request()->isPost()){
            $data = input('post.');
            $where['vid'] = array('EQ',$data['vid']);
            $result = Db::name('video')->where($where)->update($data);
            if($result){
                echo json_encode(['code' => 0,'msg' => '修改成功','icon' => 1]);
            }else{
                echo json_encode(['code' => 1,'msg' => '修改失败','icon' => 2]);
            }
        }
    }
    //视频修改推荐
    public function upRecommend(){
        if(request()->isPost()){
            $data = input('post.');
            $where['vid'] = array('EQ',$data['vid']);
            $result = Db::name('video')->where($where)->update($data);
            if($result){
                echo json_encode(['code' => 0,'msg' => '修改成功','icon' => 1]);
            }else{
                echo json_encode(['code' => 1,'msg' => '修改失败','icon' => 2]);
            }
        }
    }
    //视频修改排序
    public function upOrder(){
        if(request()->isPost()){
            $data = input('post.');
            //判断当前排序数字是否存在
            $order_first = Db::name('video')->where( [ 'order'=>$data['order'], 'status'=>2 ] )->find();
            if( $order_first ){
                echo json_encode(['code' => 1,'msg' => '排序已存在','icon' => 2]);
            }else{
                $where['vid'] = array('EQ',$data['vid']);
                $result = Db::name('video')->where($where)->update($data);
                if($result){
                    echo json_encode(['code' => 0,'msg' => '修改成功','icon' => 1]);
                }else{
                    echo json_encode(['code' => 1,'msg' => '修改失败','icon' => 2]);
                }
            }
        }
    }
}