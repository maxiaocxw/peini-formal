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
        $this->checkParam();
        //token验证
        $this->checkToken();
        //接收post传递过来的参数
        $post = input('post.');
        //查询用的可用余额是否小于提现金额
        $userMoney = Db::name('user')->where(['uid' => $post['uid']])->field('earnings')->find();
        if($userMoney['earnings'] < $post['money']){
            $this->II('201','您的可用余额不足，请重新提现',[]);
        }
        //拼装参数
        $data = [
            'uid'   => $post['uid'],
            'tranno' => granTranNo(),
            'money' => $post['money'],
            'addtime' => time(),
            'status' => 1
        ];
        try{
            //添加数据到数据库
            $result = Db::name('withdrawal')->insert($data);
            if($result){
                $where = ['uid' => $post['uid']];
                $saveData = [
                    'earnings' => $userMoney['earnings'] - $post['money'],
                ];
                $res = Db::name('user')->where($where)->update($saveData);
                if($res){
                    $this->II('200','请求成功',[]);
                }else{
                    $this->II('201','请求失败',[]);
                }
                $this->II('200','请求成功',[]);
            }else{
                $this->II('201','请求失败',[]);
            }
            //提交事务
            Db::commit();
        }catch (\Exception $e ){
            //回滚
            Db::rollback();
            $this->II('201','请求失败',[]);
        }

    }
}