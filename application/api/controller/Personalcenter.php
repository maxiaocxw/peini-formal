<?php
namespace app\api\controller;
use app\api\controller\Auth;
use think\Db;
class Personalcenter extends Auth{
	public function _initialize(){
		$this->checkParam();
		$this->checkToken();
		$this->uid = input('post.uid')?input('post.uid'):0;
		$this->url='http://cdn.lanyushiting.com/';
	}
	public function index(){
		$list=Db::name('user')->field('uid,username,birthday,headimg,level,currency,sex,number,city,info,type,earnings')->where('uid='.$this->uid)->find();
		if($list['type']==1){
			if(Db::name('approve')->where('uid='.$list['uid'])->find()){
				$list['type']=3;
			}
		}
		$list['myincome']=$list['earnings'];
		$list['headimg']=$this->url.$list['headimg'];
		$list['age']=(date('Y',time()))-(date('Y',$list['birthday']));
		$list['birthday']=date('Y-m-d',$list['birthday']);
		$this->II('200','获取成功',$list);
	}
}