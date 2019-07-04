<?php
namespace app\index\controller;
header("Content-type: text/html; charset=utf-8");
use Qiniu\Auth;
use Qiniu\Processing\PersistentFop;
use think\Cache;
use think\Controller;
use think\Db;
use think\Session;

class Api extends Controller {
    //短信验证码发送
    public function sendcode(){
        $req = $this->request->post();
        if(empty($req)){
            return json(array('result' => '2', 'msg' => '参数错误', 'data' => ''));
        }
        $code = rand(100000, 999999);
        $send = send_sms($req['phone'], ['code' => $code]);
        if($send['Message'] != 'OK'){
            return json(array('result' => '2', 'msg' => "系统繁忙", 'data' => ''));
        }else{
            //如果短信验证码发送成功的话  往数据库的message表里存储一条信息 之后点击下一步判断验证码是否在正确的时间范围内
            $message_data['phone'] = $req['phone'];
            $message_data['code'] = $code;
            $message_data['send_time'] = time();
            $message_data['is_valid'] = 1; //这条短信验证是否已经使用过 1是没有 2是已经使用过
            $message_res = Db::name('message') -> insert($message_data);
            if($message_res && $send['Message'] = 'OK'){
                return json(array('result' => '1', 'msg' => "发送成功", 'data' => $req['phone']));
            }else{
                return json(array('result' => '2', 'msg' => '服务器繁忙', 'data' => ''));
            }
        }
    }

    //用户手机号注册登陆
    public function register_phone(){
        $req = $this->request->post();
        if(empty($req)){
            return json(array('result' => '2', 'msg' => '参数错误', 'data' => ''));
        }
        //判断验证码是否在有效的范围内正确
        if(empty($req['phone'])){
            return json(array('result'=>'2', 'msg'=>'请填写正确的手机', 'data'=>''));
        }
        if(empty($req['code'])){
            return json(array('result'=>'2', 'msg'=>'请输入验证码', 'data'=>''));
        }
        //判断手机号是否正确
        if(!preg_match("/^1[34578]\d{9}$/", $req['phone'])){
            return json( array( 'result'=>'2', 'msg'=>'请输入正确的手机号', 'data'=>'' ) );
        }
        //根据传值的手机号和验证码从数据库查找 判断是否正确并且在有效时间六十秒内
        $phone_data = Db::name('message')->where('phone',$req['phone'])->where('code',$req['code'])->find();
        if(!$phone_data){
            return json(array('result' => '2', 'msg' => '验证码错误', 'data' => ''));
        }else{
            $send_time = $phone_data['send_time'];
            $interval_time = intval(time()) - intval($send_time);
            if($interval_time > 60){
                //验证码失效直接删除这条数据
                $message_valid_res = Db::name('message')->where('phone',$req['phone'])->where('code',$req['code'])->delete();
                if($message_valid_res){
                    return json(array('result' => '2', 'msg' => '验证码失效', 'data' => ''));
                }else{
                    return json(array('result' => '2', 'msg' => '系统错误', 'data' => ''));
                }
            }
            //将这条短信记录删除
            $message_del_res = Db::name('message')->where('phone',$req['phone'])->where('code',$req['code'])->delete();
            //判断该手机号是否已经注册过  返回userID
            $user_info = Db::name('user') -> where( 'user_phone', $req['phone'] ) -> find();
            if( $user_info ){
                if( $message_del_res ){
                    return json( array( 'result' => '1', 'msg' => '登录成功', 'data' => $user_info['user_id'] ) );
                }else{
                    return json(array('result' => '2', 'msg' => '系统错误', 'data' =>''));
                }
            }else{
                //手机号不存在的话新增一条用户数据   ----昵称和id随机分配 头像默认软件图标
                $uid = get_user_id();
                $user_data['user_id'] = $uid;
                $user_data['user_name'] = '陪你用户'.$uid;
                $user_data['user_phone'] = $req['phone'];
                $user_data['head_img'] = "www.shayudj.com/static/images/head_img.png";
                $user_data['sex'] = ''; // 1-女生 2-男生 3-保密
                $user_data['area'] = '';
                $user_data['sign'] = '';
                $user_data['age'] = '';
                $user_data['state'] = 1; // 1-正常 2-封号
                $user_data['user_money'] = 0;
                $user_data['registertime'] = time();
                $user_add_res = Db::name('user')->insert($user_data);
                $usermoney_data['userid'] = $uid;
                $usermoney_data['usermoney'] = 0;
                $usermoney_add_res = Db::name('usermoney')->insert($usermoney_data);
                if($message_del_res && $user_add_res && $usermoney_add_res){
                    return json(array('result' => '1', 'msg' => '验证成功', 'data' => $uid));
                }else{
                    return json(array('result' => '2', 'msg' => '系统错误', 'data' =>''));
                }
            }
        }
    }

    //用户微信注册登录
    public function register_wechat(){}

}
?>
