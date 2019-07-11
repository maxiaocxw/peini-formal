<?php
/**
 * Created by PhpStorm.
 * User: csq
 * Date: 2019/7/11
 * Time: 18:36
 */
namespace app\api\controller;
use think\Db;

class Game extends Auth
{

    public function index()
    {
        //获取游戏id
        $gameId = input('post.');


        $where['gameid'] = ['like', '%' . $gameId['gid'] . '%'];
        $where['status'] = ['=', '2'];
        //查询用户认证表中的游戏ids 获取到用户id
        $userData = Db::name('approve')->where($where)->field('id,uid')->select();
        $userInfo = [];
        foreach ($userData as $item) {
            //根据用户id查询视频表中的第一帧图片 返回给客户端
            $user = Db::name('user')->where([
                'uid' => $item['uid'],
                'status' => 1,
                'type' => 2
            ])->field('uid,username,birthday,headimg,sex')->find();
            $img = Db::name('video')->where([
                'status' => 2,
                'uid' => $user['uid']
            ])->field('img')->find();
            $userInfo[] = [
                'age' => birthday($user['birthday']),
                'uid' => $user['uid'],
                'username' => $user['username'],
                'headimg'  => $user['headimg'],
                'sex'      => $user['sex'] == 1 ? '男' : ($user['sex'] == 2 ? '女' : '未知'),
                'img' => 'http://cdn.lanyushiting.com/' . $img['img']
            ];
        }
        $this->II('200','请求成功',$userInfo);
    }



}