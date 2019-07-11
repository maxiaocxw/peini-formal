<?php
namespace app\api\controller;
use app\api\controller\Auth;
use think\Db;
class Personalcenter extends Auth{
	public function _initialize(){
		$this->checkParam();
		$this->checkToken();
		$this->uid = input('post.uid')?input('post.uid'):0;
	}
	public function index(){
		$list=Db::name('user')->field('uid,username,birthday,level,currency')->where('uid='.$this->uid)->find();
		$getgift=Db::name('send_log')->field('sum(amount) as amount')->where('acceptuid='.$this->uid)->find()['amount'];
		$getgift=$getgift?$getgift:0;
		$getorder=Db::name('game_order')->field('sum(amount) as amount')->where('pid='.$this->uid.' and status=3 or status=4')->find()['amount'];
		$getorder=$getorder?$getorder:0;
		$list['myincome']=$getorder+$getgift;
		$list['age']=(date('Y',time()))-(date('Y',$list['birthday']));
		$this->II('200','获取成功',$list);
	}
}