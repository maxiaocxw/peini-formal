<?php
namespace app\api\controller;
use app\api\controller\Auth;
use think\Db;
class Bindphone extends Auth{
	public function _initialize(){
		$this->checkParam();
		$this->code = input('post.code')?input('post.code'):0;
		$this->phone = input('post.phone')?input('post.phone'):0;
		$this->openid = input('post.openid')?input('post.openid'):0;
		$this->checkParam('openid,code,phone');
		$this->url='http://cdn.lanyushiting.com/';
	}

	public function index(){
		$this->checkCode($this->phone,$this->code);
		$list=Db::name('user')->field('uid,username,type,status,sex,mobile,birthday,info,headimg,level,addtime,currency,number,rongtoken')->where('mobile='.$this->phone)->find();
		if(!$list){
			$newuid=$this->addUser();
			Db::name('user')->where('uid='.$newuid)->update(array("openid"=>$this->openid));
			$this->II('200','请求成功',array('type'=>1,'info'=>$this->getUserInfo($newuid)));
		}
		if($list['status']==2){
			$this->II('201','用户被封禁请联系管理员');
		}
		$list['token']=md5(time().rand(1000,9999));
		Db::name('user')->where('mobile='.$this->phone)->update(array('token'=>$list['token'],'openid'=>$this->openid));
		$list['birthday']=date('Y-m-d',$list['birthday']);
		$list['addtime']=date('Y-m-d',$list['addtime']);
		$list['qiniuToken'] = (new Qiniu())->getToken();
		$list['headimg']=$this->url.$list['headimg'];
		$this->II('200','请求成功',array('type'=>2,'info'=>$list));
	}

	public function addUser(){
		$usernmae='陪你'.rand(10000,99999);
		$number=mt_rand(10000,99999);
		if(Db::name('user')->where('number='.$number)->value('uid')){
			$number=mt_rand(100000,999999);
		}
		$arr=array(
			'username'		=>		$usernmae,
			'mobile'		=>		$this->phone,
			'birthday'		=>		time(),
			'info'			=>		'这个家伙什么都没写',
			'addtime'		=>		time(),
			'ip'			=>		$_SERVER['REMOTE_ADDR'],
			'token'			=>		md5(time().rand(1000,9999)),
			'number'		=>		$number
		);
		$newuid=Db::name('user')->insertGetId($arr);
		if($newuid){
			$url='http://api-cn.ronghub.com/user/getToken.json';
	        $content=http_build_query(array(
	            'userId'=>$newuid,
	            'name'=>$usernmae,
	            'portraitUri'=>$this->url.'image/1562745999/6178png'
	        )); 
			$res=json_decode($this->tocurl($url,$content));
			if($res->token){
				Db::name('user')->where('uid='.$newuid)->update(array('rongtoken'=>$res->token));
			}
			return $newuid;
		}else{
			$this->II('500','内部错误');
		}
	}

}