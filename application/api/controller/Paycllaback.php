<?php
/**
 * Created by PhpStorm.
 * User: csq
 * Date: 2019/7/12
 * Time: 10:47
 */
namespace app\api\controller;

use AopClient;
use think\Controller;
use think\Log;


vendor('aliyun-sdk.AopSdk');

/**
 * 支付回调类
 * Class Paycllaback
 * @package app\api\controller
 */
class Paycllaback extends Controller{

    //支付宝回调
    public function aliPay(){

        $c = new AopClient;

        $result = $c->rsaCheckV1($_POST,$c->alipayrsaPublicKey,$_POST['sign_type']);


        Log::record(json_encode($_POST,JSON_UNESCAPED_UNICODE));
        Log::record($result);


        //支付成功修改订单信息
//        if($result){

            //修改订单信息为已完成


            //修改用户的账户余额


//        }
    }

    //微信回调

    //苹果回调


}