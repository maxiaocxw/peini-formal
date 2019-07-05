<?php
namespace app\admin\controller;
use app\admin\controller\Auth;
use think\Db;
use think\Request;
use think\Session;

class User extends Auth{
	public function index(){
		$where=" status!=-1";
		if(input('username')){
			$where .=" and username like '%".input('username')."%'";
			$this->assign('username',input('username'));
		}
		$list = Db::name('user')->where($where)->paginate(30,false,['request' => request()->param()]);
		$data = $list->toArray();
		$this->assign('data',$data['data']);
		$this->assign('total',$data['total']);
		$this->assign('list',$list);
		return $this->fetch();
	}

	public function update(){
		$list=Db::name('user')->where('uid='.input('uid'))->find();
		$this->assign('list',$list);
		return $this->fetch();
	}
	public function updatedo(){
		$arr=input('post.');
		Db::name('user')->where('uid='.$arr['uid'])->update($arr);
		$this->success('修改成功','/admin/user/index','1');
	}
}