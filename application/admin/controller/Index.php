<?php
namespace app\admin\controller;
use app\admin\controller\Auth;
use think\Db;
use think\Request;
class Index extends Auth{
	public function index(){
		$this->userid = session('admin.id');
		$this->user_role=session('admin.user_role');
		$role_permission = Db::name('role')->where('id = '.$this->user_role)->value('role_permission');
		if(!empty($role_permission)){
			$role = Db::name('permission')->where('id = '.$role_permission)->find();
			$menu_list_one = Db::name('menu')->where('level = 1 and statu = 1 and id in('.$role['menu_pid'].')')->select();
			$menu_list_two = Db::name('menu')->where('level = 2 and statu = 1 and id in('.$role['menu_id'].')')->select();
		}
		else{
			$menu_list_one = Db::name('menu')->where('level = 1 and statu = 1')->select();
			$menu_list_two = Db::name('menu')->where('level = 2 and statu = 1')->select();
		}
		// echo "<pre>";
		// print_r($_SESSION);die;
		$this->assign('menu_list_one',$menu_list_one);
		$this->assign('menu_list_two',$menu_list_two);
		return $this->fetch();
	}
	public function welcome(){
		return $this->fetch();
	}
}