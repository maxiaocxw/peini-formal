<?php
namespace app\api\controller;
use app\api\controller\Auth;
use think\Request;
use think\Db;
use think\cache\driver\File;
class Gift extends Auth{
	public function _initialize(){
		$this->checkParam();
		$this->checkToken();
		$this->file=new File();
	}
	public function index(){
		if($this->file->has('gift')){
			$this->II('200','获取成功',$this->file->get('gift'));
		}else{
			$list=Db::name('gift')->field('gid,name,price,img')->where('status=1')->order('order asc')->select();
			$this->file->set('gift',$list,21600);//Valid time 6 hours
			$this->II('200','获取成功',$list);
		}
	}
}