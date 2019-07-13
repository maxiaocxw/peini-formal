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
		$list=Db::name('user')->field('uid,username,type,status,sex,mobile,birthday,info,headimg,level,addtime,currency')->where('mobile='.$this->phone)->find();
		if(!$list){
			$newuid=$this->addUser();
			Db::name('user')->where('uid='.$newuid)->update(array('openid='.$this->openid));
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

}