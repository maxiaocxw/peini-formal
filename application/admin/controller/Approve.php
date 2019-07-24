<?php
/**
 * Created by PhpStorm.
 * User: csq
 * Date: 2019/7/10
 * Time: 9:24
 */

namespace app\admin\controller;

use think\Controller;
use think\Db;

/**
 * 用户申请成为陪玩审核类
 * Class Approve
 * @package app\admin\controller
 */
class Approve extends Controller {

    //审核列表
    public function index(){
        $listInfo = [];
        $list = Db::name('approve')->order('id desc')->paginate(30,false,['request' => request()->param()]);

        foreach ($list as $val){
            $gamePrice = [];
            //查询游戏对应的价格
            $price = Db::name('playinfo')->where('uid='.$val['uid'])->select();
            //查询游戏名称
            foreach($price as $item){
                $name = Db::name('game')->where('gid='.$item['gameid'])->find();
                $gamePrice[] = [
                    'gameName'  => $name['name'],
                    'price'     => $item['price']
                ];
            }
            $listInfo[] = [
                //列表信息
                'list'  => $val,
                //标签
                'label' => Db::name('label')->where("lid",'in',$val['labelid'])->field('lid,name')->select(),
                //游戏名称，游戏价格
                'game'  => $gamePrice,
            ];
        }
        $data = $list->toArray();
        $this->assign('data',$data['data']);
        $this->assign('total',$data['total']);
        $this->assign('listInfo',$listInfo);
        $this->assign('list',$list);
        return $this->fetch();
    }

    //通过审核
    public function review($id){
        //查询陪玩数据
        $approve = Db::name('approve')->where('id='.$id)->find();
        //查询此用户是否是陪玩用户 是不审核
        $userType = Db::name('user')->where('uid='.$approve['uid'])->field('type')->find();
        if($userType['type'] === 2){
            echo "<script>alert('此用户已经成为陪玩');window.location.href=\"/admin/approve/index\"</script>";
        }
        //获取到标签id添加到用户和标签关联表中
        $labelIds = explode(',',$approve['labelid']);
        try{
            foreach($labelIds as $val){
                $data = [
                    'uid' => $approve['uid'],
                    'lid' => $val
                ];
                Db::name('label_user')->insert($data);
            }

            //修改用户信息
            $saveData = ['type'=>2];
            $result = Db::name('user')->where('uid='.$approve['uid'])->update($saveData);

            if($result){
                //修改认证表
                $appData = [
                    'status'=>2,
                    'audittime' => time()
                ];
                $res = Db::name('approve')->where('id='.$id)->update($appData);
                if($res){
                    echo "<script>alert('审核成功');window.location.href=\"/admin/approve/index\" </script>";
                }else{
                    echo "<script>alert('审核失败');window.location.href=\"/admin/approve/index\"</script>";
                }

            }else{
                echo "<script>alert('审核失败');window.location.href=\"/admin/approve/index\"</script>";
            }
            Db::commit();
        }catch (\Exception $e){
            Db::rollback();
            echo "<script>alert('审核失败');window.location.href=\"/admin/approve/index\"</script>";
        }

    }

    //审核未通过
    public function noReview($id){

        //修改数据
        $saveData = [
            'status'    => 3
        ];
        //where条件
        $where = "id=".$id;
        $result = Db::name('approve')->where($where)->update($saveData);
        if($result){
            echo "<script>alert('成功');window.location.href=\"/admin/approve/index\"</script>";
        }else{
            echo "<script>alert('失败');window.location.href=\"/admin/approve/index\"</script>";
        }
    }

    //添加认证信息
    public function add(){
        //查询游戏
        $game = $this->gameList();
        //查询标签
        $label = $this->labelList();        //var_dump(json_decode($label,true));exit;

        $this->assign('game',json_decode($game,true));
        $this->assign('label',json_decode($label,true));
        return $this->fetch();

    }

    private function labelList(){
        //查询数据库
        $data = Db::name('label')->where('status=1')->field('lid,name')->select();
        return json_encode($data,JSON_UNESCAPED_UNICODE);
    }

    //游戏列表
    public function gameList(){
        //查询游戏表
        $game = Db::name('game')->where('status=1')->field('gid,name,tid')->select();
        //查询游戏价格
        $gamePrice = [];
        foreach($game as $item){
            $gamePrice[] = [
                'gameInfo' => $item,
                'price'    => Db::name('game_price')->where(['gid'=>$item['gid'],'status'=>1])->field('gid,price')->select()
            ];
        }
        return json_encode($gamePrice,JSON_UNESCAPED_UNICODE);
    }

    public function getGamePrice(){
        $post = input('post.');
        $data =  Db::name('game_price')->where(['gid'=>$post['gid'],'status'=>1])->field('pid,price')->select();
        echo json_encode(['code' => 0,'msg' => '','data' => $data]);

    }

    /**
     * 添加陪玩认证
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function addDo(){
        $post = input('post.');
//        echo "<pre>";
//        var_dump($post);exit;
        //验证真实姓名不能为空
        if(empty($post['username'])){
            echo json_encode(['code' => 1,'msg' => '用户名不能为空','icon' => 2]);exit;
        }
        //验证用户名不能超过10个汉字
        if(!preg_match('/^[\x{4e00}-\x{9fa5}]{3,6}$/u',$post['username'])){
            echo json_encode(['code' => 1,'msg' => '姓名格式输入不正确，请输入正确数据','icon' => 2]);exit;
        }

        //验证身份证号不能为空
        if(empty($post['idcode'])){
            echo json_encode(['code' => 1,'msg' => '身份证号不能为空','icon' => 2]);exit;
        }

        //验证身份证正则
        if(!preg_match('/^[\d]{17}[xX\d]$/',$post['idcode'])){
            echo json_encode(['code' => 1,'msg' => '身份证格式不正确，请输入正确数据','icon' => 2]);exit;
        }

        //验证支付宝账号不能为空
        if(empty($post['alipay'])){
            echo json_encode(['code' => 1,'msg' => '支付宝账号不能为空','icon' => 2]);exit;
        }

        //验证身份证正面不能为空
        if(empty($post['img1'])){
            echo json_encode(['code' => 1,'msg' => '身份证正面不能为空','icon' => 2]);exit;
        }

        //验证身份证反面不能为空
        if(empty($post['img2'])){
            echo json_encode(['code' => 1,'msg' => '身份证反面不能为空','icon' => 2]);exit;
        }

        //验证手持身份证不能为空
        if(empty($post['img3'])){
            echo json_encode(['code' => 1,'msg' => '手持身份证不能为空','icon' => 2]);exit;
        }
        if(empty($post['img4'])){
            echo json_encode(['code' => 1,'msg' => '视频不能为空','icon' => 2]);exit;
        }
        if(empty($post['img5'])){
            echo json_encode(['code' => 1,'msg' => '视频封面图不能为空','icon' => 2]);exit;
        }

        if(empty($post['label'])){
            echo json_encode(['code' => 1,'msg' => '标签不能为空','icon' => 2]);exit;
        }

        if(empty($post['qq'])){
            echo json_encode(['code' => 1,'msg' => 'QQ不能为空','icon' => 2]);exit;
        }

        if(empty($post['wx'])){
            echo json_encode(['code' => 1,'msg' => '微信不能为空','icon' => 2]);exit;
        }

        if(empty($post['phone'])){
            echo json_encode(['code' => 1,'msg' => '手机不能为空','icon' => 2]);exit;
        }

        $phone = Db::name('user')->where('mobile='.$post['phone'])->find();
        if(!empty($phone)){
            echo json_encode(['code' => 1,'msg' => '手机重复','icon' => 2]);exit;
        }

        //增加用户
        $userData = [
            'username' => $post['phone'],
            'realname' => $post['username'],
            'type'     => 1,
            'mobile'   => $post['phone'],
            'interestid' => implode(',',$post['label']),
        ];
        try{
            $user =  Db::name('user')->insert($userData);
            if($user){
                //获取uid
                $userId = Db::name('user')->getLastInsID();
                //添加陪玩信息
                $peiData = [
                    'uid' => $userId,
                    'idcode' => $post['idcode'],
                    'idcodefront' => $post['img1'],
                    'idcodereverse' => $post['img2'],
                    'handidcode'    => $post['img3'],
                    'status'        => 1,
                    'addtime'       => time(),
                    'labelid'       => implode(',',$post['label']),
                    'username'      => $post['username'],
                    'alipay'        => $post['alipay'],
                    'qq'            => $post['qq'],
                    'wx'            => $post['wx']
                ];
                $peiResult = Db::name('approve')->insert($peiData);
                if($peiResult){
                    //添加视频信息
                    $videoData = [
                        'uid'   => $userId,
                        'videourl' => $post['img4'],
                        'img'      => $post['img5'],
                        'status'   => 1,
                        'addtime'  => time(),
                    ];
                    $videoResult = Db::name('video')->insert($videoData);
                    if($videoResult){
                        echo json_encode(['code' => 0,'msg' => '添加成功','icon' => 2]);exit;
                    }else{
                        echo json_encode(['code' => 1,'msg' => '添加失败','icon' => 2]);exit;
                    }
                }else{
                    echo json_encode(['code' => 1,'msg' => '添加失败','icon' => 2]);exit;
                }
            }else{
                echo json_encode(['code' => 1,'msg' => '添加失败','icon' => 2]);exit;
            }
        }catch (\Exception $e){
            echo json_encode(['code' => 1,'msg' => '添加失败','icon' => 2]);exit;
        }


    }

}