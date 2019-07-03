<?php

use Aliyun\Core\Config;
use Aliyun\Core\Profile\DefaultProfile;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;
use think\Db;

//发送短信方法
function send_sms($tel,$code){
    require_once('../extend/alisms/vendor/autoload.php');
    Config::load();
    // $config='';
    $accessKeyId = 'LTAIyqG7sDqEkEgA';
    $accessKeySecret = 'fzll52W1aDMSCCkFv4e2jCli9qCjCt';
    $templateParam = $code;
    $signName = '陪你陪玩';
    $templateCode = 'SMS_169103077';
    //短信API产品名（短信产品名固定，无需修改）
     $product = "Dysmsapi";
     //短信API产品域名（接口地址固定，无需修改）
     $domain = "dysmsapi.aliyuncs.com";
     //暂时不支持多Region（目前仅支持cn-hangzhou请勿修改）
     $region = "cn-hangzhou";
     // 初始化用户Profile实例
     $profile = DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);
     // 增加服务结点
     DefaultProfile::addEndpoint("cn-hangzhou", "cn-hangzhou", $product, $domain);
     // 初始化AcsClient用于发起请求
     $acsClient = new DefaultAcsClient($profile);
     // 初始化SendSmsRequest实例用于设置发送短信的参数
     $request = new SendSmsRequest();
     // 必填，设置雉短信接收号码
     $request->setPhoneNumbers($tel);
     // 必填，设置签名名称
     $request->setSignName($signName);
     // 必填，设置模板CODE
     $request->setTemplateCode($templateCode);
     // 可选，设置模板参数
     if ($templateParam) {
         $request->setTemplateParam(json_encode($templateParam));
     }
     //发起访问请求
     $acsResponse = $acsClient->getAcsResponse($request);
     //返回请求结果
     $result = json_decode(json_encode($acsResponse), true);
     // 具体返回值参考文档：https://help.aliyun.com/document_detail/55451.html?spm=a2c4g.11186623.6.563.YSe8FK
     return $result;
}

//获取用户id规则
function get_user_id(){//从用户表里查询最大的id
    $max_uid = Db::name('user')->max('user_id');
    if($max_uid){
        $uid = intval($max_uid) + intval(rand(1,10));
    }else{
        $uid = 10000 + intval(rand(1,10));
    }
    return $uid;
}
