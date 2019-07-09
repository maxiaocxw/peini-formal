<?php
namespace app\api\controller;
use app\api\controller\Auth;
use think\Request;
use think\Db;
class Orderlist extends Auth{
	public function _initialize(){
		$this->checkParam();
		$this->checkToken();
		$this->uid = input('post.uid')?input('post.uid'):0;//普通用户id
		$this->page=input('post.page')?input('post.page'):1;
		$this->limit=10;
	}

	public function index(){
		$list=Db::name('game_order')->field('id,tranno,uid,pid,amount,num,gid,status')->where('uid='.$this->uid.' or pid='.$this->uid)->order('addtime desc')->page($this->page,$this->limit)->select();
		foreach($list as $key=>$val){
			if($val['uid']==$this->uid){
				$list[$key]['type']=1; //下的单
			}
			if($val['pid']==$this->uid){
				$list[$key]['type']=2; //接的单
			}
		}
		$this->II('200','获取成功',$list);
	}


}