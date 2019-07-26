<?php
namespace app\api\controller;
use think\Request;
use think\Db;
class Update {
	public function index(){
		$this->checkParam();
		$this->checkToken();
	}
	public function updateOrderStatus($job,$data){
		if($data['type']==1){
			$order=Db::name('game_order')->field('pid,amount')->where('id='.$data['id'])->find();
			$currency=Db::name('user')->where('uid='.$order['pid'])->value('earnings');
			if (Db::name('game_order')->where('id='.$data['id'])->update(array('status'=>3))) {
				Db::name('user')->where('uid='.$order['pid'])->update(array('earnings'=>$currency+$order['amount']));
	            $job->delete();
	        } else {
	            $try_nums = $job->attempts();
	            trace("报警内容：".__FUNCTION__.",报警失败{$try_nums }次, 执行时间:". time());
	        }
		}elseif($data['type']==2){
			if (Db::name('game_order')->where('id='.$data['id'])->update(array('status'=>3))) {
	            $job->delete();
	        } else {
	            $try_nums = $job->attempts();
	            trace("报警内容：".__FUNCTION__.",报警失败{$try_nums }次, 执行时间:". time());
	        }
		}else{
			return false;
		}
		return true;	
	}
}