<?php
namespace app\admin\controller;
use app\admin\controller\Auth;
use think\Db;
use think\Request;
use think\Session;

class Admin extends Auth{
	public function index(){
		$start = empty(input('start')) ? 0 : strtotime(input('start'));
		$end = empty(input('end')) ? time() : strtotime(input('end'));
		if(!empty($start)){
			$this->assign('start',input('start'));
		}
		if(!empty($end)){
			$this->assign('end',input('end'));
		}
		if(!empty(input('username'))){
			$where['user_name'] = array('EQ',trim(input('username')));
			$this->assign('username',trim(input('username')));
		}
		$where['inputtime'] = array('BETWEEN',$start . ',' . $end);
		$list = Db::name('admin')->where($where)->paginate(30,false,['request' => request()->param()]);
		$data = $list->toArray();
		$this->assign('data',$data['data']);
		$this->assign('total',$data['total']);
		$this->assign('list',$list);
		return $this->fetch();
	}
	public function update(){
		if(request()->isPost()){
			$data = input('post.');
			$where['id'] = array('EQ',$data['id']);
			$result = Db::name('admin')->where($where)->update($data);
			if($result){
				echo json_encode(['code' => 0,'msg' => '修改成功']);
			}else{
				echo json_encode(['code' => 1,'msg' => '修改失败']);
			}
		}
	}
	public function delAdmin(){
		$id = input('post.id');
		if(empty($id)){
			echo json_encode(['code' => 1,'msg' => '禁止非法操作','icon' => 2]);
			exit;
		}
		$result = Db::name('admin')->where('id = ' . trim($id))->delete();
		if($result){
			echo json_encode(['code' => 0,'msg' => '删除成功','icon' => 1]);
		}else{
			echo json_encode(['code' => 1,'msg' => '删除失败','icon' => 3]);
		}
	}
	public function welcome(){
		return $this->fetch();
	}

	/*
	* 
	*/
	public function add(){
		if(request()->isAjax()){
			$param = check_param('user_name,user_account,user_password,user_role');
			if($param['res']){
				$data = input('post.');
				$data['user_password'] = md5($data['user_password']);
				$data['inputtime'] = time();
				$data['type'] = 2;
				$data['status'] = 1;
				$result = Db::name('admin')->insert($data);
				if($result){
					echo json_encode(['code' => 0,'msg' => '添加成功','icon'=> 1]);
				}else{
					echo json_encode(['code' => 1,'msg' => '添加失败','icon' => 3]);
				}
			}else{
				echo json_encode(['code' => 1,'msg' => '请将表单补充完整','icon' =>3]);
			}
		}else{
			$list = Db::name('role')->field('id,name')->select();
			$this->assign('list',$list);
			return $this->fetch();
		}
	}
	// 权限管理
	public function role(){
		$list = Db::name('role')->paginate(30,false,['request' => request()->param()]);
		$data = $list->toArray();
		foreach ($data['data'] as $key => &$value) {
			$value['role'] = Db::name('permission')->where('id = ' . $value['role_permission'])->value('name');
		}
		$this->assign('data',$data['data']);
		$this->assign('total',$data['total']);
		$this->assign('list',$list);
		return $this->fetch();
	}
	// 添加权限管理
	public function addRole(){
		if(request()->isAjax()){
			$data = input('post.');
			if(empty($data['name']) || empty($data['role_permission'])){
				echo json_encode(['code' => 1,'msg' => '请将表单补充完整','icon' => 2]);
				exit;
			}else{
				$result = Db::name('role')->insert($data);
				if($result){
					echo json_encode(['code' => 0,'msg' => '添加成功','icon' => 6]);
				}else{
					echo json_encode(['code' => 1,'msg' => '添加失败','icon' => 2]);
				}
			}
		}else{
			$list = Db::name('permission')->field('id,name')->select();
			$this->assign('list',$list);
			return $this->fetch();
		}
	}
	public function delRole(){
		$result = Db::name('role')->where('id = ' . input('post.id'))->delete();
		if($result){
			echo json_encode(['code' => 0,'msg' => '删除成功','icon' => 1]);
		}else{
			echo json_encode(['code' => 1,'msg' => '删除失败','icon' => 2]);
		}
	}
	// 权限管理
	public function permission(){
		$list = Db::name('permission')->paginate(30,false,['request'=>request()->param()]);
		$data = $list->toArray();
		foreach ($data['data'] as $key => &$value) {
			$where['id'] = array('IN',$value['menu_id']);
			$menu_arr = Db::name('menu')->field('title')->where($where)->select();
			$menu_a = array();
			foreach ($menu_arr as $k => $v) {
				$menu_a[] = $v['title'];
			}
			$value['menu'] = implode(',', $menu_a);
		}
		$this->assign('data',$data['data']);
		$this->assign('total',$data['total']);
		$this->assign('list',$list);
		return $this->fetch();
	}
	public function addPermission(){
		$list = Db::name('menu')->where('level = 1')->select();
		$list2 = Db::name('menu')->where('level = 2')->select();
		$this->assign('menu_list_one',$list);
		$this->assign('menu_list_two',$list2);
		return $this->fetch();
	}
	public function doAddPermission(){
		$param = check_param('name,content');
		if($param['res']){
			$menu_pid = input('post.menu_pid/a');
			$menu_id = input('post.menu_id/a');
			if(empty($menu_pid) || empty($menu_id)){
				echo json_encode(['code' => 1,'msg' => '请选择权限']);
				exit;
			}else{
				$data['menu_pid'] = implode(',', array_values($menu_pid));
				$data['menu_id'] = implode(',', array_values($menu_id));
				$data['name'] = trim(input('post.name'));
				$data['content'] = trim(input('post.content'));
				$result = Db::name('permission')->insert($data);
				if($result){
					echo json_encode(['code' => 0,'msg' => '添加成功']);
				}else{
					echo json_encode(['code' => 1,'msg' => '添加失败']);
				}
			}
		}else{
			echo json_encode(['code' => 1,'msg' => '请将表单补充完整']);
		}
	}
	// 批量删除
	public function delAll(){
		if(input('?post.table')){
			$id = input('post.id/a');
			if(empty($id)){
				echo json_encode(['code' => 1,'msg' => '请选择数据','icon' =>2]);
				exit;
			}else{
				$where['id'] = array('IN',implode(',', $id));
				$result =Db::name(trim(input('post.table')))->where($where)->delete();
				if($result){
					echo json_encode(['code' => 0,'msg' => '删除成功','icon' => 1]);
				}else{
					echo json_encode(['code' => 1,'msg' => '删除失败','icon' => 2]);
				}
			}
		}else{
			echo json_encode(['code' => 1,'msg' =>'禁止非法操作','icon' => 3]);
		}
	}

	// 提现申请
	public function voucher(){
		if(input('act') == 'edit'){
			$arr['voucher'] = input('voucher');
			$arr['status'] = input('status');
			$arr['updatetime'] = time();
			$res = Db::name('voucher')->where('id = '.input('id'))->update($arr);
			if($res){
				echo json_encode(['code' => 1,'msg' => '操作成功','icon' => 1]);
				exit;
			}else{
				echo json_encode(['code' => 0,'msg' => '操作失败','icon' => 2]);
				exit;
			}
		}
		$where=' 1=1 ';
		if(!empty(input('post.username'))){
			$user_id=Db::name('user')->where("user_nickname LIKE '%".input('post.username')."%'")->field('id')->find()['id'];
			$user_id=$user_id?$user_id:'0';
			$where.=" and v.userid=".$user_id;
		}
		if(!empty(input('post.start'))){
			$where.=" and v.createtime >= ".strtotime(input('post.start'));
		}
		if(!empty(input('post.end'))){
			$where.=" and v.createtime <= ".strtotime(input('post.end'));
		}
		if(!empty(input('post.status'))){
			$where.=" and v.status = ".input('post.status');
		}
		$voucher = Db::name('voucher')->where($where)->alias('v')->join('user u','v.userid = u.id')->field('v.*,u.user_nickname')->paginate(30,false,['request'=>request()->param()]);
		if(input('post.username')){
			$this->assign('username',input('post.username'));
		}
		if(input('post.start')){
			$this->assign('start',input('post.start'));
		}
		if(input('post.end')){
			$this->assign('end',input('post.end'));
		}
		if(input('post.status')){
			$this->assign('status',input('post.status'));
		}else{
			$this->assign('status','0');
		}
		$this->assign('voucher',$voucher);
		return $this->fetch();
	}
	// 提现审核
	public function voucher_examine(){
		$voucher = Db::name('voucher')->alias('v')->join('user u','v.userid = u.id')->field('v.*,u.user_nickname')->where('v.id = '.input('id'))->find();
		$this->assign('voucher',$voucher);
		return $this->fetch();
	}
	public function uploadimgs(){
		$userid = input('userid');
		//上传文件目录获取
		$dir = "upload/voucher/".$userid."/";
		$imgs = uploadimgs($dir,'','');
		echo json_encode($imgs);
	}
	// 删除图片
	public function deleteImg(){
		$dir = input('dir');
		$file = input('file');
		$rootpath = APP_PATH.'../public/'.$dir;
		unlink($rootpath.$file);
	}
	//管理员日志
	public function adminlog(){
		$list = Db::name('admin_log')->paginate(30,false,['request'=>request()->param()]);
		$data = $list->toArray();
		foreach ($data['data'] as $key => &$value) {
			$value['set_time'] = date('Y-m-d H:i:s',$value['set_time']);	
		}
		$this->assign('data',$data['data']);
		$this->assign('list',$list);
		return $this->fetch();
	}
	//管理员退出
	public function loginout(){
		
		$this->admin_user_id=session('admin.id');
		$this->user_name=session('admin.user_name');
		$this->set_name='退出后台';
		$this->addAdminLog();
		session(null);
		echo "<script>window.location.href='/admin/';</script>";die;
	}

	public function shouye(){
		$list=Db::name('deal_lifestyle')->where('is_index=1')->order('z_index asc')->paginate(30,false,['request'=>request()->param()]);
		$data = $list->toArray();
		foreach ($data['data'] as $key => &$value) {
			if($value['type']==1){
				$arr=Db::name('deal')->field('title,deal_id')->where('deal_id='.$value['id'])->find();
				$value['title']=$arr['title'];
				$value['lid']=$arr['deal_id'];
			}else{
				$arr=Db::name('lifestyle')->field('title,id')->where('id='.$value['id'])->find();
				$value['title']=$arr['title'];
				$value['lid']=$arr['id'];
			}
		}
		$this->assign('data',$data['data']);
		$this->assign('list',$list);
		return $this->fetch();
	}

	public function editLifestyleZindex(){
		if(input('id') && input('z_index') && input('z_type')){
			Db::name('deal_lifestyle')->where('id='.input('id').' and type='.input('z_type'))->update(array('z_index'=>input('z_index')));
			echo json_encode(['code' => 0,'msg' => '修改成功','icon' => 1]);die;
		}else{
			echo json_encode(['code' => 1,'msg' => '禁止非法操作','icon'=>3]);
			exit;
		}
	}
}