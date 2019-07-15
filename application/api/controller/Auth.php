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
     * 校验签名和参数
     */
    public function checkParam($param=''){
        if(!$param){
            $arr=input('post.');
            if(empty($arr['sign'])){
                $this->II('100','参数错误',array());
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
        }else{
            $param=explode(',',$param);
            foreach($param as $k=>$v){
                if(!input('post.'.$v)){
                    $this->II('100','参数错误');
                }
            }
            return true;
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
        if(empty($uid)){
            $this->II('104','缺少uid',array());
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
            $this->II('100','参数错误');
        }
        if($this->sendSms($phone,$code)){
            cache($phone,$code,1800);
            $this->II('200','发送成功');
        }else{
            $this->II('201','发送失败');
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
        $list=Db::name('user')->field('uid,username,type,status,sex,mobile,birthday,info,headimg,level,addtime,currency,token,rongtoken,number')->where('uid='.$uid)->find();
        $list['qiniuToken'] = (new Qiniu())->getToken();
        $list['birthday']=date('Y-m-d',$list['birthday']);
        $list['addtime']=date('Y-m-d',$list['addtime']);
        return $list;
    }

    /**
     * 添加队列任务
     *
     * @param string $job_name 队列执行的类路径 不带走类fire方法 带@方法 走类@的方法
     * @param array $data 传入数据
     * @param mixed $queue_name 队列名 null 或字符串
     * @param integer $delay  延迟执行的时间  单位秒
     * @return void
     */
    public function push_job($job_name, $data, $queue_name = null, $delay = 0){
        trace($queue_name);
        config('default_return_type', 'json');
        $class_name = \strstr($job_name, '@', true);
        if(class_exists($class_name)){
            if($delay > 0){
                $ret = \think\Queue::later($delay, $job_name, $data, $queue_name);
            }else{
                trace($job_name);
                $ret = \think\Queue::push($job_name, $data, $queue_name);;
            }
            trace(sprintf("加入任务%s, 时间%s", $job_name, time()));
            return $ret;
        }
        return $this->error('job类 '.$job_name.'不存在');
    
    }

    public function get_constellation($time)
    {
        $y   = date("Y").'-';
        $his = ' 00:00:00';
        $birth_month = date("m", $time);
        $birth_date  = date("d", $time);

        $userTime = strtotime($y.$birth_month.'-'.$birth_date.$his);

        $januaryS   = strtotime($y.'01-20'.$his);
        $januaryE   = strtotime($y.'02-18'.$his);
        $februaryS  = strtotime($y.'02-19'.$his);
        $februaryE  = strtotime($y.'03-20'.$his);
        $marchS     = strtotime($y.'03-21'.$his);
        $marchE     = strtotime($y.'04-19'.$his);
        $aprilS     = strtotime($y.'04-20'.$his);
        $aprilE     = strtotime($y.'05-20'.$his);
        $mayS       = strtotime($y.'05-21'.$his);
        $mayE       = strtotime($y.'06-21'.$his);
        $juneS      = strtotime($y.'06-22'.$his);
        $juneE      = strtotime($y.'07-22'.$his);
        $julyS      = strtotime($y.'07-23'.$his);
        $julyE      = strtotime($y.'08-22'.$his);
        $augustS    = strtotime($y.'08-23'.$his);
        $augustE    = strtotime($y.'09-22'.$his);
        $septemberS = strtotime($y.'09-23'.$his);
        $septemberE = strtotime($y.'10-23'.$his);
        $octoberS   = strtotime($y.'10-24'.$his);
        $octoberE   = strtotime($y.'11-22'.$his);
        $novemberS  = strtotime($y.'11-23'.$his);
        $novemberE  = strtotime($y.'12-21'.$his);

        if($userTime >= $januaryS && $userTime <= $januaryE){
            $constellation = '水瓶座';
        }elseif($userTime >= $februaryS && $userTime <= $februaryE){
            $constellation = '双鱼座';
        }elseif($userTime >= $marchS && $userTime <= $marchE){
            $constellation = '白羊座';
        }elseif($userTime >= $aprilS && $userTime <= $aprilE){
            $constellation = '金牛座';
        }elseif($userTime >= $mayS && $userTime <= $mayE){
            $constellation = '双子座';
        }elseif($userTime >= $juneS && $userTime <= $juneE){
            $constellation = '巨蟹座';
        }elseif($userTime >= $julyS && $userTime <= $julyE){
            $constellation = '狮子座';
        }elseif($userTime >= $augustS && $userTime <= $augustE){
            $constellation = '处女座';
        }elseif($userTime >= $septemberS && $userTime <= $septemberE){
            $constellation = '天秤座';
        }elseif($userTime >= $octoberS && $userTime <= $octoberE){
            $constellation = '天蝎座';
        }elseif($userTime >= $novemberS && $userTime <= $novemberE){
            $constellation = '射手座';
        }else{
            $constellation = '摩羯座';
        }

        return $constellation;
    }


    /**
     * 发送数据
     * @param String $url     请求的地址
     * @param Array  $header  自定义的header数据
     * @param Array  $content POST的数据
     * @return String
     */
    public  function tocurl($url,$content){
        $nowtime=time()*1000;
        $rand_=rand(100000,999999);
        $appsecret='rBBGXNtgRI4V4';
        $header=[
            'Content-Type:application/x-www-form-urlencoded',
            'App-Key:p5tvi9dspeoj4',//appkey
            'Nonce:'. $rand_,//随机数
            'Timestamp:'.$nowtime,//当前时间戳
            'Signature:'.sha1($appsecret.$rand_.$nowtime)
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);  // 从证书中检查SSL加密算法是否存在
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
        $response = curl_exec($ch);
        if($error=curl_error($ch)){
            die($error);
        }
        curl_close($ch);
        return $response;
    }

    public function addMessage($uid,$type,$title){
        Db::name('message')->insert(array(
            'uid'       =>      $uid,
            'type'      =>      $type,
            'title'     =>      $title,
            'content'   =>      $title,
            'addtime'   =>      time(),
            'status'    =>      1
        ));
        return true;
    }
}