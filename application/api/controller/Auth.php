<?php
namespace app\api\controller;
use think\Controller;
use think\Db;
use app\api\controller\SignatureHelper;
class Auth extends Controller{
	public function __construct(){
		parent::__construct();
	}

    /**
     * 接口数据返回
     * @param string    $code 状态码
     * @param string     $msg 提示信息
     * @param array    $data 数据
     * @return mixed
     */
    public function II($code=0,$msg='',$data=array()){
        $arr=[
            'code'  => $code,
            'msg'   => $msg,
            'data'  => $data
        ];
        echo json_encode($arr);die;
    }

    /**
     * 校验签名
     */
    public function checkParam($param=''){
        $arr=input('post.');
        if(empty($arr['sign'])){
            $this->II('102','参数错误',array());
        }
        $sign=$arr['sign'];
        unset($arr['sign']);
        $str='';
        ksort($arr);
        reset($arr);
        foreach ($arr as $key => $value) {
            $str.=$key.'='.$value.'&';
        }
        $str=trim($str,'&').'peini';
        if($sign==md5($str)){
            return true;
        }else{
            $this->II('101','签名错误',array());
        }
    }


    /**
     * token校验
     * @return mixed
     */
    public function checkToken(){
        $uid=input('post.uid');
        $token=input('post.token');
        if(empty($token)){
            $this->II('104','缺少token',array());
        }
        if(Db::name('user')->where("token='".$token."' and uid=".$uid)->find()){
            return true;
        }else{
            $this->II('103','token错误',array());
        }
    }

    //发送验证码接口
    public function putCode(){
        $this->checkParam();
        $phone=input('post.phone');
        $code=mt_rand(100000,999999);
        if(!$phone){
            $this->II('100','参数错误',array());
        }
        if($this->sendSms($phone,$code)){
            cache($phone,$code,30);
            json_echo(200,'发送成功');
        }else{
            json_echo(501,'发送失败,请稍后重试');
        }
    }

    //验证码校验
    public function checkCode($phone,$code){
        if(!cache($phone)){
            $this->II('106','验证码已过期',array());
        }
        if(cache($phone)==$code){
            return true;
        }else{
            $this->II('105','验证码错误',array());
        }
    }

    public function sendSms($phone,$code){
        $params = array ();
        //阿里云的AccessKey
        $accessKeyId = 'LTAIyqG7sDqEkEgA';
        //阿里云的Access Key Secret
        $accessKeySecret = 'fzll52W1aDMSCCkFv4e2jCli9qCjCt';
        //要发送的手机号
        $params["PhoneNumbers"] = $phone;
        //签名，第三步申请得到
        $params["SignName"] = '陪你陪玩';
        //模板code，第三步申请得到
        $params["TemplateCode"] = 'SMS_169103077';
        //模板的参数，注意code这个键需要和模板的占位符一致
        $params['TemplateParam'] = Array (
            "code" => $code
        );
        // *** 需用户填写部分结束, 以下代码若无必要无需更改 ***
        if(!empty($params["TemplateParam"]) && is_array($params["TemplateParam"])) {
            $params["TemplateParam"] = json_encode($params["TemplateParam"], JSON_UNESCAPED_UNICODE);
        }
        // 初始化SignatureHelper实例用于设置参数，签名以及发送请求
        $helper = new SignatureHelper();
        try{
            // 此处可能会抛出异常，注意catch
            $content = $helper->request(
                $accessKeyId,
                $accessKeySecret,
                "dysmsapi.aliyuncs.com",
                array_merge($params, array(
                    "RegionId" => "cn-hangzhou",
                    "Action" => "SendSms",
                    "Version" => "2017-05-25",
                ))
            // fixme 选填: 启用https
            // ,true
            );
            $res=array('errCode'=>0,'msg'=>'ok');
            if($content->Message!='OK'){
                return false;
            }
            return true;
        }catch(\Exception $e){
            return false;
        }
    }

    //获取用户信息
    public function getUserInfo($uid){
        $list=Db::name('user')->field('uid,username,type,status,sex,mobile,birthday,info,headimg,level,addtime,token')->where('uid='.$uid)->find();
        $list['birthday']=date('Y-m-d',$list['birthday']);
        $list['addtime']=date('Y-m-d',$list['addtime']);
        return $list;
    }
}