<?php
/**
 * Created by PhpStorm.
 * User: csq
 * Date: 2019/7/11
 * Time: 10:25
 */
namespace app\api\controller;

use think\Db;

class Pay extends Auth{

    public function index(){
        //充值金额
        $money = Db::name('allow_recharge')->where('status = 1')->select();
        $this->II('200','请求成功',$money);
    }

    public function pay(){
        //订单号

        //充值钱数

        //支付方式

    }

    public function add(){
        //添加数据到订单列表中
        $post = input('post.');
        //订单号

        //支付类型

        //充值金额

        //用户id
    }
}