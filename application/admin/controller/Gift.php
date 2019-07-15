<?php
/**
 * Created by PhpStorm.
 * User: csq
 * Date: 2019/7/5
 * Time: 16:29
 */
namespace app\admin\controller;

use think\Controller;
use think\Db;

class Gift extends Controller{

    //礼物列表
    public function index(){
        //查询列表
        $list = DB::name('gift')->paginate(30,false,['request' => request()->param()]);
        $data = $list->toArray();
        $this->assign('data',$data['data']);
        $this->assign('total',$data['total']);
        $this->assign('list',$list);
        return $this->fetch();
    }


    public function add(){
        //渲染页面
        return $this->fetch();
    }

    public function addDo(){

        $data = input('post.');
        if(empty($data['name'])){
            echo json_encode(['code' => 1,'msg' => '礼物名称不能为空','icon' => 2]);
        }

        if(empty($data['price'])){
            echo json_encode(['code' => 1,'msg' => '礼物价格不能为空','icon' => 2]);
        }

        if(empty($data['order'])){
            echo json_encode(['code' => 1,'msg' => '排序不能为空','icon' => 2]);
        }

        $game_data['name'] = $data['name'];
        $game_data['price'] = $data['price'];
        $game_data['img'] = str_replace('http://cdn.lanyushiting.com/','',$data['img']);
        $game_data['order'] = $data['order'];
        $game_data['status'] = $data['status'];
        $game_data['addtime'] = time();
        $result = Db::name('gift')->insert($game_data);
        if($result){
            echo json_encode(['code' => 0,'msg' => '添加成功','icon' => 6]);
        }else{
            echo json_encode(['code' => 1,'msg' => '添加失败','icon' => 2]);
        }

    }


    //图片上传
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



    //删除
    public function delAll(){

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


    //修改
    public function update(){
        if(request()->isPost()){
            $data = input('post.');
            var_dump($data);exit;
            $where['gid'] = $data['gid'];
            $result = Db::name('gift')->where($where)->update($data);
            if($result){
                echo json_encode(['code' => 0,'msg' => '修改成功','icon' =>1]);
            }else{
                echo json_encode(['code' => 1,'msg' => '修改失败','icon' =>2]);
            }
        }
    }


}