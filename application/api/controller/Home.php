<?php
/**
 * Created by PhpStorm.
 * User: csq
 * Date: 2019/7/4
 * Time: 17:35
 */
namespace app\api\controller;

use think\Db;

class Home extends Auth {

    //喜欢
    public function like(){
        $this->checkParam();
//        $this->checkToken();
        //接收参数
        $arr=input('post.');
        //分页数据
        $limit = 10;
        //判断page参数存不存在
        $page = isset($arr['page']) ? $arr['page'] : 1;
        //查询关注人数
        $data = Db::name('like')->where(
            [
                'uid'   => $arr['uid'],
                'status'  => 1
            ]
        )->field('acceptuid')->page($page,$limit)->select();
        //用户信息
        $userData = [];
        foreach($data as $val){
            //查询用户信息
            $userInfo = DB::name('user')->where(['uid'=>$val['acceptuid'],'status'=>1,'type'=>2])->field('uid,username,birthday,headimg')->find();
            $label = Db::name('label_user')
                ->alias('labelUser')
                ->join('label la','labelUser.lid = la.lid')
                ->where(['labelUser.uid'=>$userInfo['uid'],'la.status'=>1])
                ->field('name')
                ->select();
            if(!empty($userInfo)){
                $userData[] = [
                    'uid'  => $userInfo['uid'],
                    'name'=>$userInfo['username'],
                    'birthday'=>$userInfo['birthday'],
                    'headimg' =>$userInfo['headimg'],
                    'label' => $label
                ];
            }
        }

        $this->II('200','请求成功',$userData);
    }

    //推荐
    public function recommend(){
        $this->checkParam();
        //接收参数
        $arr=input('post.');
        //分页数据
        $limit = 10;
        //判断page参数存不存在
        $page = isset($arr['page']) ? $arr['page'] : 1;
        //推荐数据
        $data = Db::name('video')->where([
            'status' => 1, #查询视频是否正常
            'isrecommend' => 2, #查询是否是推荐
        ])->order('order ASC')->page($page,$limit)->field('uid,videourl,img')->select();

        //初始化数组
        $userData = [];
        foreach($data as $val){
            //查询用户数据
            $userInfo = Db::name('user')->where(['uid'=>$val['uid'],'status'=>1])->field('uid,username,headimg')->find();
            //查询标签数据
            $label = Db::name('label_user')
                        ->alias('labelUser')
                        ->join('label la','labelUser.lid = la.lid')
                        ->where(['labelUser.uid'=>$userInfo['uid'],'la.status'=>1])
                        ->field('name')
                        ->select();
            if(!empty($userInfo)){
                $userData[] = [
                    'uid'  => $userInfo['uid'],
                    'name'  => $userInfo['username'],
                    'headimg' => $userInfo['headimg'],
                    'videourl' => $val['videourl'],
                    'img'   => $val['img'],
                    'label' => $label
                ];
            }
        }

        //返回数据
        $this->II('200','请求成功',$userData);
    }

    //栏目
    public function column(){
        //查询数据
        $data = Db::name('game')->where('status=1')->field('gid,name,img,info')->select();
        //返回数据
        $this->II('200','请求成功',$data);
    }

    //首页数据
    public function homeData(){
        $this->checkParam();
        $arr=input('post.');
        //分页数据
        $limit = 10;
        //判断page参数存不存在
        $page = isset($arr['page']) ? $arr['page'] : 1;
        //推荐数据
        $data = Db::name('video')->where([
            'status' => 1, #查询视频是否正常
            'isrecommend' => 1, #查询是否是推荐
        ])->page($page,$limit)->field('uid,videourl,img')->select();
        //初始化数组
        $userData = [];
        foreach($data as $val) {
            //查询用户数据
            $userInfo = Db::name('user')->where(['uid' => $val['uid'], 'status' => 1])->field('uid,username,headimg')->find();
            //查询标签数据
            $label = Db::name('label_user')
                ->alias('labelUser')
                ->join('label la', 'labelUser.lid = la.lid')
                ->where(['labelUser.uid' => $userInfo['uid'], 'la.status' => 1])
                ->field('name')
                ->select();
            if (!empty($userInfo)) {
                $userData[] = [
                    'uid'  => $userInfo['uid'],
                    'name' => $userInfo['username'],
                    'headimg' => $userInfo['headimg'],
                    'videourl' => $val['videourl'],
                    'img' => $val['img'],
                    'label' => $label
                ];
            }
        }

        //返回数据
        $this->II('200','请求成功',$userData);
    }

}