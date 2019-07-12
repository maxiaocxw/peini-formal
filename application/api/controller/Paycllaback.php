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
use think\Db;
use think\Exception;
use think\Log;


/**
 * 支付回调类
 * Class Paycllaback
 * @package app\api\controller
 */
class Paycllaback extends Controller{

    public $uid;

    //支付宝回调
    public function aliPay(){
        $c = new AopClient;

        $result = $c->rsaCheckV1($_POST,$c->alipayrsaPublicKey,$_POST['sign_type']);
        if($result){
            //修改订单信息为已完成
            try{
                //修改订单信息
                $orderData = [
                    'paystatus' => 1,
                    'status'    => 1,
                    'paytime'   => time(),
                ];
                $orderWhere = ['tranno'=>$_POST['trade_no']];
                $orderResult = Db::name('recharge_order')->where($orderWhere)->update($orderData);
                if($orderResult){
                    //查询订单信息状态为已完成 并获取到到用户id
                    $userData = Db::name('recharge_order')->where('tranno='.$_POST['trade_no'])->find();
                    $this->uid = $userData['uid'];
                    if($userData['status'] == 1 && $userData['paystatus']){
                        //查询用户表
                        $user = Db::name('user')->where('uid='.$userData['uid'])->field('uid,currency')->find();
                        $saveData = [
                            //币数
                            'currency' => $user['currency']+$userData['currencynum'],
                        ];
                        $data = Db::name('user')->where('uid='.$user['uid'])->update($saveData);
                        if($data){
                            addLog($this->uid,$_POST['trade_no'],1,$_POST,'添加成功');
                        }else{
                            addLog($this->uid,$_POST['trade_no'],1,$_POST,'添加失败');
                        }
                    }
                }else{
                    addLog($this->uid,$_POST['trade_no'],1,$_POST,'添加失败');
                }
            }catch (\Exception $e){
                addLog($this->uid,$_POST['trade_no'],1,$_POST,'添加失败');
            }
        }
    }

    //微信回调

    //苹果回调


}