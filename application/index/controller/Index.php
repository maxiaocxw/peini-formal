<?php
namespace app\index\controller;
use think\Cache;
use think\Controller;
use think\Db;
use think\Session;

class Index extends Controller
{
	public function _initialize() {
		$this->db = Db::name('admin');
	}
    public function index(){
        return $this->fetch('login');
    }
    //登陆
	public function postLogin() {
		$req = $this->request->post();
		//print_r($req);die;
		if (!isset($req['account']) || !isset($req['password'])) {
			return json(['result' => false, 'msg' => "参数错误！", 'data' => "/admin"]);
		}
		$data = ['account' => $req['account'], 'password' => md5($req['password'])];
		$res = Db::name('admin')->where($data)->find();
		if ($res) {
			Session::set('admin_id', $res['id']);
			Db::name('admin')->where($data)->update(['last_login_time' => time()]);
			if($res['grade']==1){
				$url = url('Index/operateIndex','','',true);
				return json(['result' => true, 'msg' => "登陆成功！", 'data' => $url, 'icon' => '6']);
			}else if($res['grade']==2){
				$url = url('Index/skillIndex','','',true);
				return json(['result' => true, 'msg' => "登陆成功！", 'data' => $url, 'icon' => '6']);
			}else{
				$url = url('index/guildIndex','','',true);
				return json(['result' => true, 'msg' => "登陆成功！", 'data' => $url, 'icon' => '6']);
			}
		} else {
			return json(['result' => false, 'msg' => "账号或密码错误！", 'data' => "/"]);
		}
	}
	//运营后端
	public function operateIndex(){
        $this->assign('data', $this->db->where('id', Session::get('admin_id'))->find());
		return $this->fetch();
	}
	//技术后端
	public function skillIndex(){
		$this->assign('data', $this->db->where('id', Session::get('admin_id'))->find());
		return $this->fetch();
	}
	//会长后端
	public function guildIndex(){
		$this->assign('data', $this->db->where('id', Session::get('admin_id'))->find());
		return $this->fetch();
	}
	public function reset_password() {
		return $this->fetch();
	}
	
}
