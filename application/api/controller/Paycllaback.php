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

        $this->uid = $userData['uid'];

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
                    $this->addMessage($this->uid,2,'支付宝充值成功',$content);
                    addLog($this->uid,$userData['tranno'],1,$_REQUEST,'添加成功');
                    exit;
                }else{
                    $content = "支付宝充值失败，请拨打客服电话：01053323933";
                    $this->addMessage($this->uid,2,'支付宝充值失败',$content);
                    addLog($this->uid,$userData['tranno'],1,$_REQUEST,'添加失败');
                    exit;
                }
            }else{
                $content = "支付宝充值失败，请拨打客服电话：01053323933";
                $this->addMessage($this->uid,2,'支付宝充值失败',$content);
                addLog($this->uid,$userData['tranno'],1,$_REQUEST,'添加失败');
                exit;
            }
        }catch (\Exception $e){
            $content = "支付宝充值失败，请拨打客服电话：01053323933";
            $this->addMessage($this->uid,2,'支付宝充值失败',$content);
            addLog($this->uid,$userData['tranno'],1,$_REQUEST,'添加失败');
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


    /**
     * 苹果内购服务器验证
     * @return string
     */
    public function CheckApplePay ()
    {
        // 苹果内购的验证收据,由客户端传过来
        $apple_receipt = input("post.");
        $password = input("post.password");
        if (empty($password)) {
            $jsonData = array("receipt-data"=>$apple_receipt);
            unset($password);
        }else{
            $jsonData = array("receipt-data"=>$apple_receipt, "password" =>$password);
        }
        $jsonData1 = json_encode($jsonData);
        $response = $this->http_post_data($jsonData1, ApplePayURLStatus);
        if($response->status==21007) {
            $response = $this->http_post_data($jsonData1, false);
        }else if ($response->status==21008) {
            $response = $this->http_post_data($jsonData1, true);
        }
        if($response->status == 0){
            // 允许名单数组
            $bundlelist=[];
            $bundleid= $response->receipt->bundle_id;
            if($bundleid && in_array($bundleid,$bundlelist)) {
                $in_app = $response->receipt->in_app;
                if($in_app && !empty($in_app)){
                    // 取出第一个支付时间
                    $firsttime = $in_app[0]->purchase_date;
                    foreach($in_app as $k=>$v){
                        if($firsttime < $v->purchase_date){
                            $firsttime = $v->purchase_date;
                        }
                    }
                    foreach($in_app as $key=>$value){
                        if($value->purchase_date == $firsttime){
                            $arr = $value;
                        }
                    }
                    // 产品的ID
                    $product_id = $arr->product_id;
                    // 原始购买时间毫秒
                    $purchase_date_pst = $arr->original_purchase_date_ms;
                    // 到期时间毫秒
                    $expires_date_formatted = $arr->expires_date_ms;
                    // 支付时间毫秒
                    $purchase_date_ms = $arr->purchase_date_ms;
                    if(empty($expires_date_formatted)){
                        $expires_date_formatted = 0;
                    }
                    if($product_id && !empty($product_id)){
                        // 产品ID数组
                        $productids = [];
                        if(in_array($product_id, $productids)){
                            // 自动订阅类型
                            $product_id_arr = [];
                            if(in_array($product_id, $product_id_arr)){
                                $purchase_date = strtotime(DateUtility::GetBeiJingTime($purchase_date_ms));
                                $difftime = time() - $purchase_date;
                                if($difftime > 10*60){
                                    return self::run(4,"支付时间过期");
                                }else{
                                    // 把自动订阅型的记录到数据库，方便检测订阅是否到期和继续订阅的操作
                                }
                            }else{
                                // 记录到数据库
                            }
                            // 写确认订单的业务逻辑
                            return self::run(0, "支付成功");
                        } else{
                            return self::run(6, "非法product_id");
                        }
                    }else{
                        return self::run(3, "produce_id不存在伪造充值");
                    }
                }else{
                    return self::run(2, "伪造充值");
                }
            }else{
                return self::run(1, "凭据bundleid不在白名单之内");
            }
        }else{
            $code = $response->status;
            $messagearr[21000] = "App Store无法读取你提供的JSON数据";
            $messagearr[21002] = "收据数据不符合格式";
            $messagearr[21003] = "收据无法被验证";
            $messagearr[21004] = "你提供的共享密钥和账户的共享密钥不一致";
            $messagearr[21005] = "收据服务器当前不可用";
            $messagearr[21006] = "收据是有效的，但订阅服务已经过期。当收到这个信息时，解码后的收据信息也包含在返回内容中";
            $messagearr[21007] = "收据信息是测试用（sandbox），但却被发送到产品环境中验证";
            $messagearr[21008] = "收据信息是产品环境中使用，但却被发送到测试环境中验证";
            return self::run($code, $messagearr[$code]);
        }
    }

    /**
     * curl请求苹果app_store验证地址
     * @param $data_string      验证字符串
     * @param $istest           是否是测试地址 true正式地址 false测试地址
     * @return mixed
     */
    private function http_post_data($data_string, $istest) {
        if ($istest) {
            // 正式验证地址
            $url = 'https://buy.itunes.apple.com/verifyReceipt';
        } else {
            // 测试验证地址
            $url = 'https://sandbox.itunes.apple.com/verifyReceipt';
        }
        $curl_handle=curl_init();
        curl_setopt($curl_handle,CURLOPT_URL, $url);
        curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_handle,CURLOPT_HEADER, 0);
        curl_setopt($curl_handle,CURLOPT_POST, true);
        curl_setopt($curl_handle,CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl_handle,CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl_handle,CURLOPT_SSL_VERIFYPEER, 0);
        $response_json =curl_exec($curl_handle);
        $response =json_decode($response_json);
        curl_close($curl_handle);
        return $response;
    }



}