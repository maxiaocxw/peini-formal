<?php
namespace app\api\controller;
use think\Controller;
use think\Db;
use think\Session;

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
<<<<<<< HEAD
=======
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
        if(Db::name('user')->where('token='$token.' and uid='.$uid)->find()){
            return true;
        }else{
            $this->II('103','token错误',array());
        }
>>>>>>> 210dc8cdb6c19ef6bbb4ce99b7e7a63a84a15609
    }
}