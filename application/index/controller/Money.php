<?php
/**
 * Created by PhpStorm.
 * User: csq
 * Date: 2019/7/15
 * Time: 13:56
 */
namespace app\index\controller;
use app\api\controller\Qiniu;
use think\Controller;
use think\Db;


class Money extends Controller {

    public $url;

    public function _initialize(){
        $this->url='http://cdn.lanyushiting.com/';
    }

    //渲染页面
    public function index(){
        return $this->fetch();
    }

    public function add(){
        //接收post传递过来的值
        $post = input('post.');
        //验证负责人姓名不能为空
        if(empty($post['fzname'])){
            echo json_encode(['code' => 1,'msg' => '负责人姓名不能为空','icon' => 2]);exit;
        }
        //验证用户名不能超过10个汉字
        if(!preg_match('/^[\x{4e00}-\x{9fa5}]{2,10}$/u',$post['fzname'])){
            echo json_encode(['code' => 1,'msg' => '负责人姓名格式输入不正确，请输入正确数据','icon' => 2]);exit;
        }

        //验证身份证正则
        if(!preg_match('/^[\d]{17}[xX\d]$/',$post['idcode'])){
            echo json_encode(['code' => 1,'msg' => '身份证格式不正确，请输入正确数据','icon' => 2]);exit;
        }

        $data = [
            'cafe_name' => $post['name'],//网吧名称
            'business' => str_replace('http://cdn.lanyushiting.com/','',$post['img3']),//营业执照
            'network' => str_replace('http://cdn.lanyushiting.com/','',$post['img4']),//网络经营许可证
            'area'    => $post['area'],//地区
            'principal_name' => $post['fzname'],//负责人姓名
            'id_code' => $post['idcode'],//身份证号
            'id_code_front' =>str_replace('http://cdn.lanyushiting.com/','',$post['img1']) ,//身份证正面
            'id_code_side' => str_replace('http://cdn.lanyushiting.com/','',$post['img2']),//身份证反面
            'account' => $post['ide'],
        ];

        $result = Db::name('money')->insert($data);
        if($result){
            echo json_encode(['code' => 0,'msg' => '添加成功','icon' => 1]);exit;
        }else{
            echo json_encode(['code' => 1,'msg' => '添加失败','icon' => 1]);exit;
        }




    }

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
                echo json_encode(['code' => 0,'msg' => '上传成功','icon' => 1,'src'=>$this->url.$qiniu_arr['key']]);
            }else{
                echo json_encode(['code' => 1,'msg' => '上传失败','icon' => 2]);
            }
        }else{
            echo json_encode(['code' => 1,'msg' => '上传错误','icon' => 2]);
        }
    }

}




