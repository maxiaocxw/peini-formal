<?php
namespace app\api\controller;
use think\Controller;
use think\Db;
use think\Session;

class Auth extends Controller{
	public function __construct(){
		parent::__construct();
	}


	
    public function II($code=0,$msg='',$data=array()){
        $arr=[
            'code'  => $code,
            'msg'   => $msg,
            'data'  => $data
        ];
        echo json_encode($arr);die;
    }
}