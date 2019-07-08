<?php
namespace app\api\controller;
use app\api\controller\Auth;
use think\Request;
use think\Db;
class Selectorder extends Auth{
	public function _initialize(){
		$this->checkParam();
		$this->checkToken();
		$this->acceptuid = input('post.acceptuid')?input('post.acceptuid'):0;//陪玩用户id
		$this->checkParam('acceptuid');
	}
	public function index(){
		$gamesid=Db::name('approve')->where('status=2 and uid='.$this->acceptuid)->value('gameid');
		if(!$gamesid){
			$this->II('201','失败');
		}
		$games=Db::name('game')->field('gid,name,img,info')->where('gid in ('.$gamesid.')')->select();
		foreach ($games as $key => $value) {
			$price=Db::name('playinfo')->where('uid='.$this->acceptuid.' and gameid='.$value['gid'])->value('price');
			if($price){
				$games[$key]['price']=$price;
			}else{
				unset($games[$key]);
			}
		}
		if($games){
			$this->II('200','获取成功',$games);
		}else{
			$this->II('201','获取失败');
		}
	}
}