<?php
/**
 * Created by PhpStorm.
 * User: csq
 * Date: 2019/7/12
 * Time: 10:47
 */
namespace app\api\controller;

//use AopClient;

use think\Controller;
use think\Db;


/**
 * 支付回调类
 * Class Paycllaback
 * @package app\api\controller
 */
class Paycllaback extends Controller{

    public $uid;

    //支付宝回调
    public function aliPay(){

        $res = json_encode($_REQUEST);
        $arr=json_decode($res,true);
        $userData = Db::name('recharge_order')->where('tranno='.$arr['out_trade_no'])->find();

        if(empty($userData)){
            addLog(0,0,1,$_REQUEST,'非法请求');exit;
        }

        if($userData['status'] == 1 && $userData['paystatus'] == 1 ){
            addLog(0,0,1,[$userData],'订单已完成');exit;
        }

//        $this->uid = $userData['uid'];

        try{
            //修改订单信息
            $orderData = [
                'paystatus' => 1,
                'status'    => 1,
                'paytime'   => time(),
            ];
            $orderWhere = ['tranno'=>$userData['tranno']];
            $orderResult = Db::name('recharge_order')->where($orderWhere)->update($orderData);
            if($orderResult){
                //查询用户表
                $user = Db::name('user')->where('uid='.$userData['uid'])->field('uid,currency')->find();
                $money = $user['currency']+$userData['currencynum'];
                $saveData = [
                    //币数
                    'currency' => $money,
                ];
                $data = Db::name('user')->where('uid='.$user['uid'])->update($saveData);
                if($data){
                    $content = "支付宝充值成功，到账金额：".$userData['currencynum']."账户余额：".$money;
                    $this->addMessage($userData['uid'],2,'支付宝充值成功',$content);
                    addLog($userData['uid'],$userData['tranno'],1,$_REQUEST,'添加成功');
                    exit;
                }else{
                    $content = "支付宝充值失败，请拨打客服电话：01053323933";
                    $this->addMessage($this->uid,2,'支付宝充值失败',$content);
                    addLog($this->uid,$userData['tranno'],1,$_REQUEST,'添加失败');
                    exit;
                }
            }else{
                $content = "支付宝充值失败，请拨打客服电话：01053323933";
                $this->addMessage($userData['uid'],2,'支付宝充值失败',$content);
                addLog($userData['uid'],$userData['tranno'],1,$_REQUEST,'添加失败');
                exit;
            }
        }catch (\Exception $e){
            $content = "支付宝充值失败，请拨打客服电话：01053323933";
            $this->addMessage($userData['uid'],2,'支付宝充值失败',$content);
            addLog($userData['uid'],$userData['tranno'],1,$_REQUEST,'添加失败');
            exit;
        }
    }

    //微信回调

    public function wxPay(){

        $post = $_REQUEST;
        if ($post == null) {
            $post = file_get_contents("php://input");
        }

        if ($post == null) {
            $post = isset($GLOBALS['HTTP_RAW_POST_DATA']) ? $GLOBALS['HTTP_RAW_POST_DATA'] : '';
        }

        if (empty($post) || $post == null || $post == '') {
            //阻止微信接口反复回调接口  文档地址 https://pay.weixin.qq.com/wiki/doc/api/H5.php?chapter=9_7&index=7，下面这句非常重要!!!
            $str='<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
            echo $str;
            exit('Notify 非法回调');
        }

        libxml_disable_entity_loader(true); //禁止引用外部xml实体

        $xml = simplexml_load_string($post, 'SimpleXMLElement', LIBXML_NOCDATA);//XML转数组

        $post_data = (array)$xml;
        //订单号
        $out_trade_no = isset($post_data['out_trade_no']) && !empty($post_data['out_trade_no']) ? $post_data['out_trade_no'] : 0;
        //根据订单号查询订单信息
        $userData = Db::name('recharge_order')->where('tranno='.$out_trade_no)->find();
        $this->uid = $userData['uid'];

        if(count($userData) > 0){
            $wxpay_key = "Sign=WXPay";
            //接收到的签名
            $post_sign = $post_data['sign'];
            unset($post_data['sign']);
            //重新生成签名
            $newSign = $this->MakeSign($post_data,$wxpay_key);

            //签名统一，则更新数据库
            if($post_sign == $newSign){
                if($post_data['return_code'] == 'SUCCESS' && $post_data['result_code'] == 'SUCCESS'){
                    //修改订单信息
                    $orderData = [
                        'paystatus' => 1,
                        'status'    => 1,
                        'paytime'   => time(),
                    ];
                    $orderWhere = ['tranno'=>$out_trade_no];
                    $orderResult = Db::name('recharge_order')->where($orderWhere)->update($orderData);
                    if($orderResult){
                        if($userData['status'] == 1 && $userData['paystatus'] == 1){
                            //查询用户表
                            $user = Db::name('user')->where('uid='.$userData['uid'])->field('uid,currency')->find();
                            $money = $user['currency']+$userData['currencynum'];
                            $saveData = [
                                //币数
                                'currency' => $money,
                            ];
                            $data = Db::name('user')->where('uid='.$user['uid'])->update($saveData);
                            if($data){
                                $content = "微信充值失败，请拨打客服电话：01053323933";
                                $this->addMessage($this->uid,2,'微信充值成功',$content);
                                addLog($this->uid,$out_trade_no,2,$post_data,'添加成功');
                                exit;
                            }else{
                                $content = "微信充值失败，请拨打客服电话：01053323933";
                                $this->addMessage($this->uid,2,'微信充值失败',$content);
                                addLog($this->uid,$out_trade_no,2,$post_data,'添加失败');
                                exit;
                            }
                        }
                    }else{
                        $content = "微信充值失败，请拨打客服电话：01053323933";
                        $this->addMessage($this->uid,2,'微信充值失败',$content);
                        addLog($this->uid,$out_trade_no,2,$post_data,'添加失败');
                        exit;
                    }
                }
            }else{
                $content = "微信充值失败，请拨打客服电话：01053323933";
                $this->addMessage($this->uid,2,'微信充值失败',$content);
                addLog($this->uid,$out_trade_no,2,$post_data,'添加失败');
                exit;
            }
        }else{
            $content = "微信充值失败，请拨打客服电话：01053323933";
            $this->addMessage($this->uid,2,'微信充值失败',$content);
            addLog($this->uid,$out_trade_no,2,$post_data,'添加失败');
            exit;
        }
    }
    //苹果回调




    public function MakeSign($params,$key){
        //签名步骤一：按字典序排序数组参数
        ksort($params);
        $string = $this->ToUrlParams($params);  //参数进行拼接key=value&k=v
        //签名步骤二：在string后加入KEY
        $string = $string . "&key=".$key;
        //签名步骤三：MD5加密
        $string = md5($string);
        //签名步骤四：所有字符转为大写
        $result = strtoupper($string);
        return $result;
    }

    public function ToUrlParams( $params ){
        $string = '';
        if( !empty($params) ){
            $array = array();
            foreach( $params as $key => $value ){
                $array[] = $key.'='.$value;
            }
            $string = implode("&",$array);
        }
        return $string;
    }


    //添加消息
    public function addMessage($uid,$type,$title,$content){
        $data = [
            'uid'   => $uid,
            'type'  => $type,
            'title' => $title,
            'content' => $content,
            'addtime' => time(),
        ];
        $res = Db::name('message')->insert($data);
        return $res;
    }

    //苹果内购验签
    public function apple(){
        //实例化类库
        $auth = new Auth();
        $receipt_data = preg_replace('/\s/', '+', input('post.apple_receipt'));
        $orderId = input('post.orderid');
        //获取订单信息
        $orderInfo = Db::name('recharge_order')->where('tranno='.$orderId)->find();
        if (!empty($orderInfo) && ($orderInfo['status'] == 0 && $orderInfo['paystatus'] == 0)) {
            // 验证支付状态
            $result = (new ApplePay())->validate_apple_pay($receipt_data);
            if($result['status']){
                // 验证通过后订单处理等逻辑
                $orderData = [
                    'paystatus' => 1,
                    'status'    => 1,
                    'paytime'   => time(),
                ];
                $orderWhere = ['tranno'=>$orderId];
                $orderResult = Db::name('recharge_order')->where($orderWhere)->update($orderData);
                if($orderResult){

                    $user = Db::name('user')->where('uid='.$orderInfo['uid'])->field('uid,currency')->find();
                    $money = $user['currency']+$orderInfo['currencynum'];
                    $saveData = [
                        //币数
                        'currency' => $money,
                    ];
                    $data = Db::name('user')->where('uid='.$user['uid'])->update($saveData);
                    if($data){
                        $content = "苹果充值成功，到账金额：".$orderInfo['currencynum']."账户余额：".$money;
                        $this->addMessage($orderInfo['uid'],2,'苹果充值成功',$content);
                        addLog($orderInfo['uid'],$orderInfo['tranno'],1,$receipt_data,'添加成功');
                        $auth->II('200','验签成功',[]);
                        exit;
                    }else{
                        $content = "苹果充值失败，请拨打客服电话：01053323933";
                        $this->addMessage($orderInfo['uid'],2,'苹果充值失败',$content);
                        addLog($orderInfo['uid'],$orderInfo['tranno'],1,$receipt_data,'添加失败');
                        $auth->II('201','验签成功',[]);
                        exit;
                    }

                }
            }else{
                // 验证不通过
                $auth->II('201','验证不通过',$result['message']);
            }
        }else{
            $auth->II('201','订单不存在或订单已处理',[]);
        }
    }
}