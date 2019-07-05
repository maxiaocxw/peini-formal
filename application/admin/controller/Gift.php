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
            echo "<script>alert('名称不能为空')</script>";
        }

        if(empty($data['price'])){
            echo "<script>alert('名称不能为空')</script>";
        }

        if(empty($data['img'])){
            echo "<script>alert('名称不能为空')</script>";
        }

        if(empty($data['order'])){
            echo "<script>alert('名称不能为空')</script>";
        }

        if(empty($data['status'])){
            echo "<script>alert('名称不能为空')</script>";
        }


    }

}