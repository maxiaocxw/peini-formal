<?php
namespace app\api\controller;
use app\api\controller\Auth;
use think\Request;
use think\Db;
class Getuser extends Auth{
	public function _initialize(){
		$this->checkParam();
		$this->checkToken();
		$this->checkParam('uid,token,pid');
		$this->pid=input('post.pid')?input('post.pid'):0;
		$this->url='http://cdn.lanyushiting.com/';
	}
	public function index(){
		$list=Db::name('user')->field('uid,username,headimg')->where('uid='.$this->pid)->find();
		$list['headimg']=$this->url.$list['headimg'];
		$this->II('200','获取成功',$list);
	}
}