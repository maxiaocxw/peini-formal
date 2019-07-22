<?php
namespace app\admin\controller;
use app\admin\controller\Auth;
use app\api\controller\Qiniu;
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
		$union=Db::name('union')->field('unid,name')->where('status=1')->select();
		$this->assign('union',$union);
		$this->assign('list',$list);
		return $this->fetch();
	}
	public function updatedo(){
		$arr=input('post.');
		$arr['birthday']=strtotime($arr['birthday']);
		unset($arr['file']);
		Db::name('user')->where('uid='.$arr['uid'])->update($arr);
		$this->success('修改成功','/admin/user/index','1');
	}

	//七牛图片上传
    public function qinui_upload(){
        $qiniu = new Qiniu();
        $file = $_FILES['file'];
        if( is_uploaded_file( $file['tmp_name'] ) ){
            //获取文件路径和文件后缀名
            $ext = pathinfo( $file['name'], PATHINFO_EXTENSION );
            //调用方法将图片上传到七牛
            $qiniu_res = $qiniu->upload( 'image', '.'.$ext, $file['tmp_name'] );
            //判断结果并返回完整路径
            if( $qiniu_res ){
                //将结果转化成数组获取key
                $qiniu_arr = json_decode( $qiniu_res , true );
                echo json_encode(['code' => 0,'msg' => '上传成功','icon' => 1,'src'=>$qiniu_arr['key']]);
            }else{
                echo json_encode(['code' => 1,'msg' => '上传失败','icon' => 2]);
            }
        }else{
            echo json_encode(['code' => 1,'msg' => '上传错误','icon' => 2]);
        }
    }
}