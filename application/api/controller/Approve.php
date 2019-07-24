<?php
/**
 * Created by PhpStorm.
 * User: csq
 * Date: 2019/7/9
 * Time: 10:39
 */
namespace app\api\controller;

use think\Db;
use think\Exception;
use think\exception\ErrorException;

/**
 * 用户认证成为陪玩
 * Class Approve
 * @package app\api\controller
 */
class Approve extends Auth{

    //认证页面信息
    public function index(){
        //初始化数组
        $data = [];
        //获取标签列表
        $label = $this->labelList();
        //获取游戏列表
        $game = $this->gameList();

        $data[] = [
            //标签
            'label' => json_decode($label,true),
            //游戏
            'game'   =>  json_decode($game,true)
        ];

        return json_encode($data,JSON_UNESCAPED_UNICODE);
    }

    //标签列表
    private function labelList(){
        //查询数据库
        $data = Db::name('label')->where('status=1')->field('lid,name')->select();
        return json_encode($data,JSON_UNESCAPED_UNICODE);
    }

    //游戏列表
    private function gameList(){
        //查询游戏表
        $game = Db::name('game')->where('status=1')->field('gid,name,tid')->select();
        //查询游戏价格
        $gamePrice = [];
        foreach($game as $item){
            $gamePrice[] = [
                'gameInfo' => $item,
                'price'    => Db::name('game_price')->where(['gid'=>$item['gid'],'status'=>1])->field('price')->select()
            ];
        }
        return json_encode($gamePrice,JSON_UNESCAPED_UNICODE);
    }

    public function add(){
        //参数验证
//        $this->checkParam();
        //token验证
//        $this->checkToken();

        //接收post传递的信息
        $post = input('post.');

        //验证真实姓名不能为空
        if(empty($post['name'])){
            $this->II('201','用户名不能为空',[]);
        }
        //验证用户名不能超过10个汉字
        if(!preg_match('/^[\x{4e00}-\x{9fa5}]{3,6}$/u',$post['name'])){
            $this->II('201','姓名格式输入不正确，请输入正确数据',[]);
        }

        //验证身份证号不能为空
        if(empty($post['idCode'])){
            $this->II('201','身份证号不能为空',[]);
        }

        //验证身份证正则
        if(!preg_match('/^[\d]{17}[xX\d]$/',$post['idCode'])){
            $this->II('201','身份证格式不正确，请输入正确数据',[]);
        }

        //判断是否用户身份证号相不相同
        $where = [
            'idcode' => $post['idCode'],
            'uid'    => $post['uid']
        ];
        $idcode = Db::name('approve')->where($where)->find();
        if(!empty($idcode)){
            echo json_encode(['code' => 1,'msg' => '经检测您已在我们平台注册，请登录','icon' => 2]);exit;
        }

        //验证支付宝账号不能为空
        if(empty($post['alipay'])){
            $this->II('201','支付宝账号不能为空',[]);
        }

        //验证身份证正面不能为空
        if(empty($post['idcodefront'])){
            $this->II('201','身份证正面不能为空',[]);
        }

        //验证身份证反面不能为空
        if(empty($post['idcodereverse'])){
            $this->II('201','身份证反面不能为空',[]);
        }

        //验证手持身份证不能为空
        if(empty($post['handidcode'])){
            $this->II('201','手持身份证不能为空',[]);
        }

        //验证手持身份证不能为空
        if(empty($post['labelIds'])){
            $this->II('201','标签不能为空',[]);
        }

        //验证手持身份证不能为空
        if(empty($post['game'])){
            $this->II('201','游戏不能为空 游戏价格不能为空',[]);
        }

        try{
            //调用添加用户认证信息方法
            $resApprove = $this->addApprove($post);

            if($resApprove){
                $resGameInfo = $this->addGameInfo($post['game'],$post['uid']);
                if($resGameInfo){
                    $this->II('200','添加成功',[$resGameInfo]);
                }else{
                    $this->II('201','添加失败',[$resGameInfo]);
                }
            }else{
                $this->II('201','添加失败',[$resApprove]);
            }
            //提交事务
            Db::commit();
        }catch (\Exception $e ){
            //回滚
            Db::rollback();
            $this->II('201','添加失败',[]);
        }
    }

    /**
     * 添加用户认证信息
     * @param $post
     * @return int|string
     */
    private function addApprove($post){

        //用户id
        $uid = $post['uid'];
        //用户真实姓名
        $name = $post['name'];
        //用户支付宝账号
        $alipayId = $post['alipay'];
        //身份证号
        $idCode = $post['idCode'];
        //用户身份证正面
        $idcodefront = $post['idcodefront'];
        //用户身份证反面
        $idcodereverse = $post['idcodereverse'];
        //用户手持身份证
        $handidcode = $post['handidcode'];
        //用户技能标签ids
        $labelIds = $post['labelIds'];
        //用户选择陪玩项目ids
        $gameArr = $post['game'];
        //将传递过来的字符串分隔成数据
        $data1 = $this->strArr($gameArr);
        if(!$data1){
            return false;
        }
        //游戏ids
        $ids = [];
        foreach($data1 as $key=>$val){
            //游戏ids
            $ids[] = $val['gameId'];
        }
        //视频连接
        $videoUrl = $post['videoUrl'];
        $img = $post['img'];
        //拼装数据
        $data = [
            'uid'   => $uid,
            'idcode' => $idCode,
            'idcodefront' => $idcodefront,
            'idcodereverse' => $idcodereverse,
            'handidcode' => $handidcode,
            'status' => 1,
            'addtime' => time(),
            'gameid' => implode(",",$ids),
            'labelid' => $labelIds,
            'username' => $name,
            'alipay' => $alipayId,
        ];
        //添加数据到认证表中
        $res = Db::name('approve')->insert($data);
        //将视频图片加到视频库中
        if($res){
            //拼装数据
            $videoData = [
                'uid' => $uid,
                'videourl' => $videoUrl,
                'img' => $img,
                'status' => 1,
                'addtime' => time()
            ];

            $result = Db::name('video')->insert($videoData);
            if($result){
                return $result;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function addGameInfo($gameArr,$uid){
        $data1 = $this->strArr($gameArr);
        foreach($data1 as $val){
            //用户id
            $data['uid'] = $uid;
            //游戏id
            $data['gameid'] = $val['gameId'];
            $data['price'] = $val['price'];
            //添加数据
            $res = Db::name('playinfo')->insert($data);
        }
        return $res;

    }


    private function strArr($gameArr){

        $arr = explode('||',trim($gameArr,'||'));
        $gameArr = [];

        foreach($arr as $val){
            $arrGame = explode(',',$val);
            foreach($arrGame as $item){
                $gameArr[] = explode('&gt;',$item);
            }
        }

        foreach($gameArr as $item ){
            $data[][$item[0]] = $item[1];
        }


        if(count($data) > 6 || count($data) < 2 ){
            return false;
        }

        $data1 = [];
        if(count($data) == 2){
            $data1[0] = [array_keys($data[0])[0] => array_values($data[0])[0],array_keys($data[1])[0] => array_values($data[1])[0]];
        }

        if(count($data) == 4){
            $data1[0] = [array_keys($data[0])[0] => array_values($data[0])[0],array_keys($data[1])[0] => array_values($data[1])[0]];
            $data1[1] = [array_keys($data[2])[0] => array_values($data[2])[0],array_keys($data[3])[0] => array_values($data[3])[0]];

        }

        if(count($data) == 6){
            $data1[0] = [array_keys($data[0])[0] => array_values($data[0])[0],array_keys($data[1])[0] => array_values($data[1])[0]];
            $data1[1] = [array_keys($data[2])[0] => array_values($data[2])[0],array_keys($data[3])[0] => array_values($data[3])[0]];
            $data1[3] = [array_keys($data[4])[0] => array_values($data[4])[0],array_keys($data[5])[0] => array_values($data[5])[0]];
        }

        return $data1;
    }
}
