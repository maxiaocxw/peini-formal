<?php
/**
 * Created by PhpStorm.
 * User: csq
 * Date: 2019/7/11
 * Time: 10:25
 */
namespace app\api\controller;

use app\api\controller\WxPay;
use think\Config;
use think\Db;


class Pay extends Auth{

    public function index(){
        //充值金额
        $money = Db::name('allow_recharge')->where('status = 1')->select();
        $this->II('200','请求成功',$money);
    }

    private function pay($tran_no){

        //根绝订单号查询充值钱数和支付方式
        $info = Db::name('recharge_order')->where('tranno='.$tran_no)->find();
        if(empty($info)){
            $this->II('201','没有查询到订单信息',[]);
        }

        //判断支付方式
        switch ($info['type']){
            //微信
            case 1:
                $result = $this->wxPay($info);
                $this->II('200','请求成功',$result);
                break;
            case 2:
                $result = $this->aliPay($info);
                $this->II('200','请求成功',$result);
                break;
            case 3:
                $this->II('200','请求成功',$tran_no);
                break;
            default :
                $this->II('201','请求失败',[]);
        }

    }

    //添加订单
    public function add(){
        //参数验证
        $this->checkParam();
//        token验证
        $this->checkToken();
        //添加数据到订单列表中
        $post = input('post.');
        //根据传递过来的金额id做一下2次校验
        $moneyInfo = Db::name('allow_recharge')->where([
            'aid'   => $post['aid'],
            'status' => 1
        ])->find();
        if($moneyInfo['money'] != $post['money']){
            $this->II('201','充值金额错误',[]);
        }
        //订单号
        $tran_no = granTranNo();
        //支付类型
        $pay_type = $post['pay_type'];
        //充值金额
        $money = $post['money'];
        //用户id
        $uid = $post['uid'];

        //将数据添加到数据库中
        $data = [
            'aid' => $post['aid'],
            'tranno' => $tran_no,
            'uid' => $uid,
            'money' => $money,
            'paystatus' => 0,
            'status'    => 0,
            'type'      => $pay_type,
            'addtime'   => time(),
            'currencynum' => $money
        ];
        $result = Db::name('recharge_order')->insert($data);
        if($result){
            $this->pay($tran_no);
        }
        $this->II('201','订单添失败',[]);
    }

    /**
     * 调取支付宝支付
     * @param $info
     * @return string
     */
    public function aliPay($info){
        $alipay = new Alipay();
        $result = $alipay ->alipay('用户充值',  $info['money'], $info['tranno'], Config::get('alipay')['notify_url']);
        return $result;
    }

    public function wxPay($info){
        //实例化微信类
        $wx = new WxPay();
        $result = $wx ->getPrePayOrder('用户充值',  $info['tranno'],$info['money']*100, 'http://www.shayudj.com/api/paycllaback/wxPay');
        if ($result['prepay_id']){//判断返回参数中是否有prepay_id
            $order1 = $wx->getOrder($result['prepay_id']);//执行二次签名返回参数
            return $order1;
        } else {
            return $result['err_code_des'];
        }
    }
}