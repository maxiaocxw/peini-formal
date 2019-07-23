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
		$this->uid=input('post.uid')?input('post.uid'):0;
		$this->checkParam('uid');
		//$this->file=new File();
		$this->url='http://cdn.lanyushiting.com/';
	}
	public function index(){
		/*if($this->file->has('gift')){
			$this->II('200','获取成功',$this->file->get('gift'));
		}else{*/
		$list['giftinfo']=Db::name('gift')->field("gid,name,price,CONCAT('".$this->url."',img)as img")->where('status=1')->order('order asc')->select();
		$list['currency']=Db::name('user')->where('uid='.$this->uid)->value('currency');
		$list['currency']=$list['currency']?$list['currency']:0;
		//$this->file->set('gift',$list,21600);//Valid time 6 hours
		$this->II('200','获取成功',$list);
		//}
	}
}