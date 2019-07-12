<?php
/**
 * Created by PhpStorm.
 * User: csq
 * Date: 2019/7/11
 * Time: 10:25
 */
namespace app\api\controller;

use think\Db;

vendor('aliyun-sdk.AopSdk');

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
                $result = $this->wxPay();
                $this->II('200','请求成功',$result);
                break;
            case 2:
                $result = $this->aliPay($info);
                $this->II('200','请求成功',$result);
                break;
            case 3:
                $result = $this->applePay();
                $this->II('200','请求成功',$result);
                break;
            default :
                $this->II('201','请求失败',[]);

        }

    }

    //添加订单
    public function add(){
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
            'addtime'   => time()
        ];
        $result = Db::name('recharge_order')->insert($data);
        if($result){
            $this->pay($tran_no);
        }
        $this->II('201','订单添失败',[]);
    }

    public function aliPay($info){
        //实例化类
        $c = new \AopClient;
        $request = new \AlipayTradeAppPayRequest();
        /**业务参数 start**/
        $content['subject'] = "用户充值".$info['money'];//商品的标题/交易标题/订单标题/订单关键字等。
        $content['out_trade_no'] = $info['tranno'];//商户网站唯一订单号
        $content['timeout_express'] = "30m";//该笔订单允许的最晚付款时间，逾期将关闭交易。取值范围：1m～15d。m-分钟，h-小时，d-天，1c-当天（1c-当天的情况下，无论交易何时创建，都在0点关闭）。 该参数数值不接受小数点， 如 1.5h，可转换为 90m。注：若为空，则默认为15d。
        $content['total_amount'] =number_format($info['money'],2,".","");//    订单总金额，单位为元，精确到小数点后两位，取值范围[0.01,100000000]，
        $content['product_code'] = "QUICK_MSECURITY_PAY";//    销售产品码，商家和支付宝签约的产品码，为固定值QUICK_MSECURITY_PAY
        $con = json_encode($content);//$content是biz_content的值,将之转化成字符串
        /**业务参数 end**/

        /**公共参数 **/
        $param = array();
        $param['app_id'] = $c->appId;//支付宝分配给开发者的应用ID
        $param['method'] = 'alipay.trade.app.pay';//接口名称
        $param['charset'] = 'utf-8';//请求使用的编码格式
        $param['sign_type'] = 'RSA2';//商户生成签名字符串所使用的签名算法类型
        $param['timestamp'] = date("Y-m-d H:i:s");//发送请求的时间，格式"yyyy-MM-dd HH:mm:ss"
        $param['version'] = '1.0';//调用的接口版本，固定为：1.0
        $param['notify_url'] = 'http://www.shyudj.com/api/pay/alinotify';//支付宝服务器主动通知地址
        $param['biz_content'] = $con;//业务请求参数的集合,长度不限,json格式
        /**公共参数**/

        //生成签名
        $paramStr = $c->getSignContent($param);
        $sign = $c->alonersaSign($paramStr,$c->rsaPrivateKey,'RSA2');

        $param['sign'] = $sign;
        $str = $c->getSignContentUrlencode($param);
        return $str;
    }

    public function wxPay(){

    }

    public function applePay(){

    }
}