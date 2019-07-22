<?php
namespace app\api\controller;
use app\api\controller\Auth;
use think\Request;
use think\Db;
class OrderEdit extends Auth{
	public function _initialize(){
		$this->checkParam();
		$this->checkToken();
		$this->uid = input('post.uid')?input('post.uid'):0;//用户id
		$this->id = input('post.id')?input('post.id'):0;//订单id
		$this->checkParam('id');
	}

	//陪玩接单
	public function putOrder(){
		//判断当前是否有未完成订单
		$this->isOver();
		//判断是不是陪玩
		$this->isPeiwan();
		if(Db::name('game_order')->where('id='.$this->id)->update(array('status'=>2,'receivetime'=>time()))){
			$uid=Db::name('game_order')->where('id='.$this->id)->value('uid');
			$num=Db::name('game_order')->where('id='.$this->id)->value('num');
			$this->addMessage($uid,2,'您有一个订单陪玩已经接单');
			//添加定时任务
			$this->push_job('app\api\controller\Update@updateOrderStatus', ['id'=>$this->id,'type'=>1], $queue_name = null, $delay = $num*3600);
			$this->II('200','接单成功');
		}else{
			$this->II('201','接单失败');
		}
	}


	//取消订单
	public function cancelOrder(){
		//判断是否已允许取消
		$user=$this->isCancel();
		$user_moeny=Db::name('user')->where('uid='.$user['uid'])->value('currency');
		Db::startTrans();
		try {
			//修改状态为已取消
			Db::name('game_order')->where('id='.$this->id)->update(array('status'=>6));
			//增加余额
			Db::name('user')->where('uid='.$user['uid'])->update(array('currency'=>$user_moeny+$user['amount']));
			Db::commit();
			$this->II('200','取消成功');
		} catch (\Exception $e) {
			Db::rollback();
			$this->II('201','取消失败');
		}
	}

	//评论
	public function putComment(){
		$pid = input('post.pid')?input('post.pid'):0;//陪玩id
		$gid = input('post.gid')?input('post.gid'):0;//游戏id
		$isanonymity = input('post.isanonymity')?input('post.isanonymity'):1;//是否匿名  1否 2是
		$this->checkParam('score,pid,gid');
		$score=input('post.score');
		$tagids='3,5';
		//是否评论
		if(Db::name('comment')->field('cid')->where('orderid='.$this->id)->find()){
			$this->II('201','已经评论');
		}
		if(Db::name('comment')->insert(array(
			'uid'		=>		$this->uid,
			'orderid'	=>		$this->id,
			'score'		=>		$score,
			'tagids'	=>		$tagids,
			'addtime'	=>		time(),
			'pid'		=>		$pid,
			'gid'		=>		$gid,
			'isanonymity'=>		$isanonymity
		))){
			Db::name('game_order')->where('id='.$this->id)->update(array('status'=>4));
			$this->II('200','评论成功');
		}else{
			$this->II('201','评论失败');
		}
	}

	public function isOver(){
		if(Db::name('user')->where('uid='.$this->uid)->value('isunlimited')==2){
			return true;
		}
		if(Db::name('game_order')->where('pid='.$this->uid.' and status=2')->value('id')){
			$this->II('201','还有订单未完成，不能接单');
		}else{
			if(Db::name('game_order')->where('id='.$this->id)->value('status')==2){
				$this->II('201','已经接单请勿重复操作');
			}
			return true;
		}
	}

	public function isCancel(){
		$iscancel=Db::name('game_order')->field('uid,status,amount')->where('id='.$this->id)->find();
		if($iscancel['status']==6){
			$this->II('201','该订单已取消');
		}
		if($iscancel['status']==2){
			$this->II('201','该订单已接单不能取消');
		}
		if($iscancel['status']==1){
			return $iscancel;
		}else{
			$this->II('201','取消失败');
		}
	}

	public function isPeiwan(){
		$type=Db::name('user')->where('uid='.$this->uid)->value('type');
		if($type==1){
			$this->II('201','用户不能操作接单');
		}
		return true;
	}

}