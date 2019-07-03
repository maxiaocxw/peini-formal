<?php
// 权限控制类
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\Session;

class Auth extends Controller{
	public function __construct(){
		parent::__construct();
		$controller = request()->controller();
		$action = request()->action();
		$action_arr = ['login','loginin'];
		$this->userid=session('admin.id');
		if(strcmp($controller, 'auth') != 0){
			if(!in_array($action, $action_arr)){
				if(empty($this->userid)){
					$this->redirect('admin/auth/login');
				}else{
					$this->userid = session('admin.id');
					$this->assign('menu_role',explode(',', session('admin.role')));
				}
			}
		}

	}
	public function login(){
		return $this->fetch();
	}
	public function loginin(){
		$where['user_account'] = array('EQ',trim(input('post.username')));
		$where['user_password'] = array('EQ',trim(md5(input('post.password'))));
		$user_data=Db::name('admin')->where($where)->find();
		if($user_data)
		{
			$this->admin_user_id=$user_data['id'];
			$this->user_name=$user_data['user_name'];
			$this->set_name='登录后台';
			$this->addAdminLog();
			// Session::set('userid',$user_data['id']);
			// Session::set('username',$user_data['user_name']);
			// Session::set('role',$user_data['user_role']);
			Session::set('admin',$user_data);
			echo json_encode(['code' => 0,'msg' => '登录成功']);
		}else
		{
			echo json_encode(['code' => 1,'msg' => '账号错误或密码错误']);
		}
	}

	public function addAdminLog(){
		$arr=array(
			'admin_user_id'			=>			$this->admin_user_id,
			'admin_user_name'		=>			$this->user_name,
			'set_time'				=>			time(),
			'set_ip'				=>			$_SERVER['REMOTE_ADDR'],
			'set_name'				=>			$this->set_name
		);
		if(Db::name('admin_log')->insert($arr)){
			return true;
		}
		
	}
}