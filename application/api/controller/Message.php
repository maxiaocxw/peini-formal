<?php
/**
 * Created by PhpStorm.
 * User: csq
 * Date: 2019/7/15
 * Time: 9:12
 */
namespace app\api\controller;

use think\Db;

class Message extends Auth{

    //读取订单信息
    public function orderIndex(){
        //参数验证
        $this->checkParam();
        //token验证
        $this->checkToken();
        //接收参数
        //接收参数
        $post = input('post.');
        //根据传递过来的uid查询数据
        $data = Db::name('message')->where(['uid'=>$post['uid'],'type'=>2])->select();
        //返回数据
        $this->II('200','请求成功',$data);
    }

    //读取系统消息
    public function systemIndex(){
        $data = Db::name('message')->where('type=1 and status != -1')->select();
        $this->II('200','请求成功',$data);
    }

    //修改订单消息为已读 系统消息除外
    public function update(){
        //参数验证
        $this->checkParam();
        //token验证
        $this->checkToken();
        //接收参数
        $post = input('post.');

        $saveData = [
            'status' => 2
        ];
        $where = ['mid'=>$post['mid'],'uid'=>$post['uid']];
        $data = Db::name('message')->where($where)->update($saveData);
        if($data){
            $this->II('200','修改成功',$data);
        }else{
            $this->II('201','修改失败',$data);
        }
    }

}
