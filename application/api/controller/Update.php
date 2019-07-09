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
			if (Db::name('game_order')->where('id='.$data['id'])->update(array('status'=>3))) {
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