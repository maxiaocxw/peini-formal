<?php
namespace app\api\controller;
use app\api\controller\Auth;
use think\Db;
class Login extends Auth{
	public function _initialize(){
		$this->checkParam();
		$this->code = input('post.code')?input('post.code'):0;
		$this->phone = input('post.phone')?input('post.phone'):0;
		$this->checkParam('code,phone');
	}

	public function index(){
		$this->checkCode($this->phone,$this->code);
		$list=Db::name('user')->field('uid,username,type,status,sex,mobile,birthday,info,headimg,level,addtime,token,currency')->where('mobile='.$this->phone)->find();
		if(!$list){
			$newuid=$this->addUser();
			$this->II('200','请求成功',array('type'=>1,'info'=>$this->getUserInfo($newuid)));
		}
		if($list['status']==2){
			$this->II('201','用户被封禁请联系管理员');
		}
		$list['birthday']=date('Y-m-d',$list['birthday']);
		$list['addtime']=date('Y-m-d',$list['addtime']);
		$this->II('200','请求成功',array('type'=>2,'info'=>$list));
	}

	public function addUser(){
		$arr=array(
			'username'		=>		'陪你'.rand(10000,99999),
			'mobile'		=>		$this->phone,
			'birthday'		=>		time(),
			'info'			=>		'这个家伙什么都没写',
			'addtime'		=>		time(),
			'ip'			=>		$_SERVER['REMOTE_ADDR'],
			'token'			=>		md5(time().rand(1000,9999))
		);
		$newuid=Db::name('user')->insertGetId($arr);
		if($newuid){
			return $newuid;
		}else{
			$this->II('500','内部错误');
		}
	}

}