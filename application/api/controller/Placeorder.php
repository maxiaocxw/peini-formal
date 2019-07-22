<?php
namespace app\api\controller;
use app\api\controller\Auth;
use think\Request;
use think\Db;
class Placeorder extends Auth{
	public function _initialize(){
		$this->checkParam();
		$this->checkToken();
		$this->uid = input('post.uid')?input('post.uid'):0;//普通用户id
		$this->acceptuid = input('post.acceptuid')?input('post.acceptuid'):0;//陪玩用户id
		$this->price = input('post.price')?input('post.price'):0;//单价
		$this->amount = input('post.amount')?input('post.amount'):0;//总价
		$this->num = input('post.num')?input('post.num'):0;//小时
		$this->gid = input('post.gid')?input('post.gid'):0;//游戏id
		$this->regional = input('post.regional')?input('post.regional'):0;//游戏区域
		$this->nickname = input('post.nickname')?input('post.nickname'):0;//游戏昵称
		$this->checkParam('uid,acceptuid,price,amount,num,gid');
	}
	public function index(){
		//对比价格是否相同
		$this->checkPrice();
		//判断陪玩是否还有其他订单没完成
		//$this->isOver();
		//判断当前已经下了多少单 限制3单
		$this->isNum(3);
		$this->amount=$this->num*$this->price;
		$usable=Db::name('user')->where('uid='.$this->uid)->value('currency');
		if($usable>=$this->amount){
			Db::startTrans();
			try {
				//扣掉用户余额
				Db::name('user')->where('uid='.$this->uid)->update(array('currency'=>$usable-$this->amount));
				//添加订单
				Db::name('game_order')->insert(array(
					'tranno'	=>		date('Ymd').mt_rand(10000,99999),
					'uid'		=>		$this->uid,
					'pid'		=>		$this->acceptuid,
					'price'		=>		$this->price,
					'num'		=>		$this->num,
					'amount'	=>		$this->amount,
					'gid'		=>		$this->gid,
					'regional'	=>		$this->regional,
					'nickname'	=>		$this->nickname,
					'addtime'	=>		time(),
					'status'	=>		1
				));
				//插入定时 超过多长时间没接单自动取消
				
				//添加订单消息
				$this->addMessage($this->acceptuid,2,'您有一个订单请查看');
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

	public function checkPrice(){
		$price=Db::name('playinfo')->where('uid='.$this->acceptuid.' and gameid='.$this->gid)->value('price');
		if($price==$this->price){
			return true;
		}else{
			$this->II('201','价格有误');
		}
	}

	public function isOver(){
		$isover=Db::name('game_order')->field('id')->where('pid='.$this->acceptuid.' and status=2')->find();
		if($isover){
			$this->II('201','该陪玩还有订单没完成，不能下单');
		}
		return true;
	}

	public function isNum($num){
		$isnum=Db::name('game_order')->field('id')->where('uid='.$this->uid.' and (status=2 or status=1 or status=2)')->count();
		if($isnum>=$num){
			$this->II('201','最多能下'.$num.'单');
		}else{
			return true;
		}
	}
}