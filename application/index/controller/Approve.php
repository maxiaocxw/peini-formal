<?php
namespace app\index\controller;
use think\Db;

/**
 * Created by PhpStorm.
 * User: csq
 * Date: 2019/7/12
 * Time: 20:30
 */

class Approve extends \think\Controller{

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
        //验证真实姓名不能为空
        if(empty($post['username'])){
            echo json_encode(['code' => 1,'msg' => '用户名不能为空','icon' => 2]);exit;
        }
        //验证用户名不能超过10个汉字
        if(!preg_match('/^[\x{4e00}-\x{9fa5}]{2,6}$/u',$post['username'])){
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