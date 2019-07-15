<?php
namespace app\api\controller;
use app\api\controller\Auth;
use think\Db;
class Login extends Auth{
	public function _initialize(){
		$this->checkParam();
		$this->code = input('post.code')?input('post.code'):0;
		$this->phone = input('post.phone')?input('post.phone'):0;
		$this->type = input('post.type')?input('post.type'):1; //1 手机号登录 2微信登录
		$this->openid = input('post.openid')?input('post.openid'):0;
		if($this->type==1){
			$this->checkParam('code,phone');
		}else{
			$this->checkParam('openid');
		}
		$this->url='http://cdn.lanyushiting.com/';
	}

	public function index(){
		//手机号登录
		if($this->type==1){
			$this->checkCode($this->phone,$this->code);
			$list=Db::name('user')->field('uid,username,type,status,sex,mobile,birthday,info,headimg,level,addtime,currency,rongtoken')->where('mobile='.$this->phone)->find();
			if(!$list){	
				$newuid=$this->addUser();
				$this->II('200','请求成功',array('type'=>1,'info'=>$this->getUserInfo($newuid)));
			}
			if($list['status']==2){
				$this->II('201','用户被封禁请联系管理员');
			}
			if(!$list['rongtoken']){
				$url='http://api-cn.ronghub.com/user/getToken.json';
		        $content=http_build_query(array(
		            'userId'=>$list['uid'],
		            'name'=>$list['username'],
		            'portraitUri'=>$this->url.$list['headimg']
		        )); 
				$res=json_decode($this->tocurl($url,$content));
				if($res->token){
					Db::name('user')->where('uid='.$list['uid'])->update(array('rongtoken'=>$res->token));
					$list['rongtoken']=$res->token;
				}
			}
			$list['token']=md5(time().rand(1000,9999));
			Db::name('user')->where('mobile='.$this->phone)->update(array('token'=>$list['token']));
			$list['birthday']=date('Y-m-d',$list['birthday']);
			$list['addtime']=date('Y-m-d',$list['addtime']);
			$list['qiniuToken'] = (new Qiniu())->getToken();
			$list['headimg']=$this->url.$list['headimg'];
			$this->II('200','请求成功',array('type'=>2,'info'=>$list));
		//微信登录
		}else{
			$list=Db::name('user')->field('uid,username,type,status,sex,mobile,birthday,info,headimg,level,addtime,token,currency,rongtoken')->where("openid='".$this->openid."'")->find();
			if(!$list){
				$this->II('300','去绑定手机号');
			}
			if($list['status']==2){
				$this->II('201','用户被封禁请联系管理员');
			}
			$list['token']=md5(time().rand(1000,9999));
			Db::name('user')->where('mobile='.$this->phone)->update(array('token'=>$list['token']));
			$list['birthday']=date('Y-m-d',$list['birthday']);
			$list['addtime']=date('Y-m-d',$list['addtime']);
			$list['qiniuToken'] = (new Qiniu())->getToken();
			$list['headimg']=$this->url.$list['headimg'];
			$this->II('200','请求成功',array('type'=>2,'info'=>$list));
		}
		
	}

	public function addUser(){
		$usernmae='陪你'.rand(10000,99999);
		$arr=array(
			'username'		=>		$usernmae,
			'mobile'		=>		$this->phone,
			'birthday'		=>		time(),
			'info'			=>		'这个家伙什么都没写',
			'addtime'		=>		time(),
			'ip'			=>		$_SERVER['REMOTE_ADDR'],
			'token'			=>		md5(time().rand(1000,9999))
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