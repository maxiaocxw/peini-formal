<?php
namespace app\api\controller;
use app\api\controller\Auth;
use think\Request;
use think\Db;
class OrderEdit extends Auth{
	public function _initialize(){
		$this->checkParam();
		$this->checkToken();
		$this->uid = input('post.uid')?input('post.uid'):0;//普通用户id
		$this->id = input('post.id')?input('post.id'):0;//订单id
		$this->checkParam('id');
	}

	//陪玩接单
	public function putOrder(){
		//判断当前是否有未完成订单
		$this->isOver();
		if(Db::name('game_order')->where('id='.$this->id)->update(array('status'=>2,'receivetime'=>time()))){
			//添加定时任务

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
			Db::name('user')->where('uid='.$uid)->update(array('currency'=>$user_moeny+$user['amount']));
			Db::commit();
			$this->II('200','取消成功');
		} catch (\Exception $e) {
			Db::rollback();
			$this->II('201','取消失败');
		}
	}

	public function isOver(){
		if(Db::name('game_order')->where('pid='.$this->uid.' and status=2')->value('id')){
			$this->II('201','还有订单未完成，不能接单');
		}else{
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

}