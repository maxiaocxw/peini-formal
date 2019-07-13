<?php
namespace app\index\controller;
use think\Controller;
use app\api\controller\Qiniu;
use think\Db;
class Union extends Controller {

    /**
     * 调转指定页面
     * @return mixed
     */
	public function index(){
        return $this->fetch();
	}

    //七牛图片上传
    public function qinui_upload(){
        $qiniu = new Qiniu();
        $file = $_FILES['file'];
        if( is_uploaded_file( $file['tmp_name'] ) ){
            //获取文件路径和文件后缀名
            $ext = pathinfo( $file['name'], PATHINFO_EXTENSION );
            //调用方法将图片上传到七牛
            $qiniu_res = $qiniu->upload( 'image', $ext, $file['tmp_name'] );
            //判断结果并返回完整路径
            if( $qiniu_res ){
                //将结果转化成数组获取key
                $qiniu_arr = json_decode( $qiniu_res , true );
                echo $qiniu_arr['key'];
            }else{
                echo "上传失败";
            }
        }else{
            echo "上传失败";
        }
    }

    public function uploaddo(){
        $arr=input('post.');
        if($arr['type']==1){
            if(!$arr['name'] || !$arr['idcodefront'] || !$arr['idcodereverse'] || !$arr['handidcode'] || !$arr['qq'] || !$arr['wechat'] || !$arr['mobile'] || !$arr['paymentname'] || !$arr['subbranch'] || !$arr['bankcard'] || !$arr['businesslicense']){
                echo "<script>alert('缺少必填项！');window.history.back(-1);</script>";die;
            }
        }
        if($arr['type']==2){
            if(!$arr['name'] || !$arr['idcodefront'] || !$arr['idcodereverse'] || !$arr['handidcode'] || !$arr['qq'] || !$arr['wechat'] || !$arr['mobile'] || !$arr['alpay']){
                echo "<script>alert('缺少必填项！');window.history.back(-1);</script>";die;
            }
        }

        $arr['addtime']=time();
        $arr['status']=0;
        if(Db::name('union')->insert($arr)){
            $this->success('添加成功','/index/union');
        }else{
            $this->success('添加失败','/index/union');
        }
    }
}