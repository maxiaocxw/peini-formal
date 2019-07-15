<?php
/**
 * Created by PhpStorm.
 * User: csq
 * Date: 2019/7/15
 * Time: 10:45
 */

namespace app\api\controller;

/**
 * 苹果验证
 * Class ApplePay
 * @package app\api\controller
 */
class ApplePay{

    /**
     * curl请求
     * @param $url
     * @param string $data
     * @return bool|string
     */
    public function curlHtml($url, $data = ''){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        if(!empty($data)) {
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        $output = curl_exec($ch);
        //释放curl句柄
        curl_close($ch);
        return $output;
    }

    /**
     * 验证AppStore内付
     * @param  string $receipt_data 付款后凭证
     * @return array                验证是否成功
     */
    public function validate_apple_pay($receipt_data) {
        /**
         * 21000 App Store不能读取你提供的JSON对象
         * 21002 receipt-data域的数据有问题
         * 21003 receipt无法通过验证
         * 21004 提供的shared secret不匹配你账号中的shared secret
         * 21005 receipt服务器当前不可用
         * 21006 receipt合法，但是订阅已过期。服务器接收到这个状态码时，receipt数据仍然会解码并一起发送
         * 21007 receipt是Sandbox receipt，但却发送至生产系统的验证服务
         * 21008 receipt是生产receipt，但却发送至Sandbox环境的验证服务
         */

        // 验证参数
        if (strlen($receipt_data)<20){
            $result = ['status'=>false, 'message'=>'非法参数'];
            return $result;
        }
        // 请求验证
        $html = $this->acurl($receipt_data);
        $data = json_decode($html,true);

        // 如果是沙盒数据 则验证沙盒模式
        if($data['status'] == '21007'){
            // 请求验证
            $html = $this->acurl($receipt_data, 1);
            $data = json_decode($html,true);
            $data['sandbox'] = '1';
        }

        if (isset($_GET['debug'])) {
            exit(json_encode($data));
        }

        // 判断是否购买成功
        if(intval($data['status']) === 0){
            $result = ['status'=>true, 'message'=>'购买成功'];
        }else{
            $result = ['status'=>false, 'message' => '购买失败 status:'.$data['status'] ];
        }
        return $result;
    }

    public function acurl($receipt_data, $sandbox=0) {
        //小票信息
        $POSTFIELDS = array("receipt-data" => $receipt_data);
        $POSTFIELDS = json_encode($POSTFIELDS);

        //正式购买地址 沙盒购买地址
        $url_buy     = "https://buy.itunes.apple.com/verifyReceipt";
        $url_sandbox = "https://sandbox.itunes.apple.com/verifyReceipt";
        $url = $sandbox ? $url_sandbox : $url_buy;
        return $this->curlHtml($url, $POSTFIELDS);
    }

}