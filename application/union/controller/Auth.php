<?php
// 权限控制类
namespace app\union\controller;
use think\Controller;
use think\Db;
use think\Session;

class Auth extends Controller{
	public function __construct(){
		parent::__construct();
		$controller = request()->controller();
		$action = request()->action();
		$action_arr = ['login','loginin'];
		$this->unid=cache('unid');
		if(strcmp($controller, 'Auth') != 0){    
			if(!in_array($action, $action_arr)){
				if(empty($this->unid)){
					$this->redirect('union/auth/login');
				}else{
                    $this->unid=cache('unid');
//					$this->assign('menu_role',explode(',', session('admin.role')));
				}
			}
		}

	}
	public function login(){
		return $this->fetch();
	}
    //发送验证码接口
    public function putCode(){
        $phone=input('post.phone');
        $code=mt_rand(100000,999999);
        if(!$phone){
            echo json_encode(['code' => 1,'msg' => '参数错误','icon' => 2]);
            die;
        }
        //判断该手机号是否是用户绑定的
        $user_res = Db::name('user')->where( [ 'mobile'=>$phone ] )->find();
        if( !$user_res ){
            echo json_encode(['code' => 1,'msg' => '手机号未绑定','icon' => 2]);
            die;
        }
        $api_auth = new \app\api\controller\Auth();
        $send_res = $api_auth->sendSms($phone,$code);
        if($send_res){
            cache($phone,$code,1800);
            echo json_encode(['code' => 0,'msg' => '发送成功','icon' => 1]);
            die;
        }else{
            echo json_encode(['code' => 1,'msg' => '发送失败','icon' => 2]);
            die;
        }
    }

    //验证码校验
    public function checkCode($phone,$code){
        if(!cache($phone)){
            echo json_encode(['code' => 1,'msg' => '验证码已过期','icon' => 2]);
            die;
        }
        //判断该手机号是否是用户绑定的
        $user_res = Db::name('user')->where( [ 'mobile'=>$phone ] )->find();
        if( !$user_res ){
            echo json_encode(['code' => 1,'msg' => '手机号未绑定','icon' => 2]);
            die;
        }
        if(cache($phone)==$code){
            //通过手机号查找到所属的公会存储到session
            $president = Db::name('union')->where( [ 'mobile'=>$phone ] )->find();
            if( !$president ){
                echo json_encode(['code' => 1,'msg' => '暂无所属公会','icon' => 2]);
                die;
            }
            //判断绑定手机号的用户所属公会和手机号查找的公会是否一致
            //if( $user_res['union'] == $president['unid'] ){
                //一致的话判断公会状态
                if( $president['status'] == 0 ){
                    echo json_encode(['code' => 1,'msg' => '公会未通过审核','icon' => 2]);
                    die;
                }elseif ( $president['status'] == 2 ){
                    echo json_encode(['code' => 1,'msg' => '公会审核被拒！请重新审核','icon' => 2]);
                    die;
                }elseif ( $president['status'] == 3 ){
                    echo json_encode(['code' => 1,'msg' => '公会已被封禁','icon' => 2]);
                    die;
                }else{
                    cache('unid',$president['unid']);
                    echo json_encode(['code' => 0,'msg' => '登陆成功','icon' => 1]);
                    die;
                }
            //}else{
               // echo json_encode(['code' => 1,'msg' => '禁止非法操作','icon' => 2]);
               // die;
            //}
        }else{
            echo json_encode(['code' => 1,'msg' => '验证码错误','icon' => 2]);
            die;
        }
    }
}