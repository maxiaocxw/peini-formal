<?php
namespace app\api\controller;
use app\api\controller\Auth;
use think\Request;
use think\Db;
use think\cache\driver\File;
class Gainlabel extends Auth{
	public function _initialize(){
		$this->checkParam();
		$this->checkToken();
		$this->file=new File();
	}
	public function index(){
		if($this->file->has('label')){
			$this->II('200','获取成功',$this->file->get('label'));
		}else{
			$list=Db::name('label')->field('lid,name')->where('status=1')->select();
			$this->file->set('label',$list,21600);//Valid time 6 hours
			$this->II('200','获取成功',$list);
		}
	}
}