<?php
/**
 * Created by PhpStorm.
 * User: csq
 * Date: 2019/7/13
 * Time: 9:29
 */
namespace app\api\controller;

use think\Db;

class Withdraw extends Auth{

    public function add(){

        //参数验证
//        $this->checkParam();
        //token验证
//        $this->checkToken();

        //接收post传递过来的参数
        $post = input('post.');
        //拼装参数
        $data = [
            'uid'   => $post['uid'],
            'tranno' => granTranNo(),
            'money' => $post['money'],
            'addtime' => time(),
            'status' => 1
        ];
        //添加数据到数据库
        $result = Db::name('withdrawal')->insert($data);
        if($result){
            $this->II('200','请求成功',[]);
        }else{
            $this->II('201','请求失败',[]);
        }
    }


}