<?php
namespace app\api\controller;
use app\api\controller\Auth;
use think\Request;
use think\Db;
class Givegift extends Auth{
	public function _initialize(){
		$this->checkParam();
		$this->checkToken();
		$this->uid = input('post.uid')?input('post.uid'):0;//普通用户id
		$this->acceptuid = input('post.acceptuid')?input('post.acceptuid'):0;//陪玩用户id
		$this->gid = input('post.gid')?input('post.gid'):0;//礼物id
		$this->num = input('post.num')?input('post.num'):0;//礼物数量
		$this->checkParam('uid,acceptuid,gid,num');

	}
	public function index(){
		$amount=$this->getAmount();
		$usable=Db::name('user')->where('uid='.$this->uid)->value('currency');
		$have=Db::name('user')->where('uid='.$this->acceptuid)->value('earnings');
		if($usable>=$amount){
			Db::startTrans();
			try {
				//扣掉用户余额
				Db::name('user')->where('uid='.$this->uid)->update(array('currency'=>$usable-$amount));
				//增加陪玩收益
				Db::name('user')->where('uid='.$this->acceptuid)->update(array('earnings'=>$have+$amount));
				//添加记录
				Db::name('send_log')->insert(array(
					'uid'		=>		$this->uid,
					'acceptuid' =>		$this->acceptuid,
					'gid'		=>		$this->gid,
					'num'		=>		$this->num,
					'amount'	=>		$amount,
					'addtime'	=>		time(),
					'status'	=>		2
				));
				Db::commit();
				$this->II('200','成功');
			} catch (\Exception $e) {
				Db::rollback();
				$this->II('201','失败');
			}
		}else{
			$this->II('202','余额不足');
		}
		
	}

	public function getAmount(){
		$price=Db::name('gift')->where('gid='.$this->gid)->value('price');
		$amount=$price*$this->num;
		return $amount;
	}
}