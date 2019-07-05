<?php
namespace app\admin\controller;
use app\admin\controller\Auth;
use think\Db;
use think\Request;
use think\Session;

class Cate extends Auth{

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
            $work_data['name'] = $name;
            $work_data['addtime'] = time();
            $work_data['status'] = 1;
            if(empty($name)){
                echo json_encode(['code' => 1,'msg' => '参数错误','icon' => 2]);
                exit;
            }else{
                $result = Db::name('work')->insert($work_data);
                if($result){
                    echo json_encode(['code' => 0,'msg' => '添加成功','icon' => 6]);
                }else{
                    echo json_encode(['code' => 1,'msg' => '添加失败','icon' => 2]);
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
    //职业修改
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
    //用户职业单条删除
    /**public function delWork(){
        $id = input('post.id');
        if(empty($id)){
            echo json_encode(['code' => 1,'msg' => '禁止非法操作','icon' => 2]);
            exit;
        }
        $update['status'] = -1;
        $result = Db::name('work')->where('wid = ' . trim($id))->update($update);
        if($result){
            echo json_encode(['code' => 0,'msg' => '删除成功','icon' => 1]);
        }else{
            echo json_encode(['code' => 1,'msg' => '删除失败','icon' => 3]);
        }
    }**/


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
            $interest_data['name'] = $name;
            $interest_data['addtime'] = time();
            $interest_data['status'] = 1;
            if(empty($name)){
                echo json_encode(['code' => 1,'msg' => '参数错误','icon' => 2]);
                exit;
            }else{
                $result = Db::name('interest')->insert($interest_data);
                if($result){
                    echo json_encode(['code' => 0,'msg' => '添加成功','icon' => 6]);
                }else{
                    echo json_encode(['code' => 1,'msg' => '添加失败','icon' => 2]);
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
    //游戏修改
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
    //游戏图片上传
    public function UploadImage(){
        $file = $_FILES['file'];
        if( is_uploaded_file( $file['tmp_name'] ) ){
            $path = './uploads/game';
            if( !file_exists($path) ){
                mkdir( $path,0777,true );
                chmod( $path, 777 );
            }
            //新文件名  避免文件名相同覆盖
            $uniname = md5( uniqid( microtime(true), true ) );
            //获取上传文件的后缀名
            $ext = pathinfo( $file['name'], PATHINFO_EXTENSION );
            //拼接要上传的完整的路径名称
            $destination = $path.'/'.$uniname.'.'.$ext;
            if( move_uploaded_file( $file['tmp_name'], $destination ) ){
                echo json_encode(['code' => 0,'msg' => '上传成功','icon' => 1,'src'=>$destination]);
            }else{
                echo json_encode(['code' => 1,'msg' => '上传失败','icon' => 2]);
            }
        }else{
            echo json_encode(['code' => 1,'msg' => '上传失败','icon' => 2]);
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
            $game_type_data['name'] = $name;
            $game_type_data['addtime'] = time();
            $game_type_data['status'] = 1;
            if(empty($name)){
                echo json_encode(['code' => 1,'msg' => '参数错误','icon' => 2]);
                exit;
            }else{
                $result = Db::name('game_type')->insert($game_type_data);
                if($result){
                    echo json_encode(['code' => 0,'msg' => '添加成功','icon' => 6]);
                }else{
                    echo json_encode(['code' => 1,'msg' => '添加失败','icon' => 2]);
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
    //游戏分类修改
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

}