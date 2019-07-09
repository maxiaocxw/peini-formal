<?php
namespace app\api\controller;
use think\Request;
class Index {
	public function index(){

		$this->checkParam();
		$this->checkToken();
		$this->II('200','请求成功','nihao');
	}
	public function welcome(){
		return $this->fetch();
	}

}