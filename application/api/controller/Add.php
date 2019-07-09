<?php
/**
 * Created by PhpStorm.
 * User: csq
 * Date: 2019/7/9
 * Time: 9:17
 */
namespace app\api\controller;


use think\Db;

class Add extends Auth{

    public function addVideo(){
        //参数验证
        $this->checkParam();
        //token验证
//        $this->checkToken();
        //获取post传递过来的值
        $post = input('post.');
        //用户id
        $uid = $post['uid'];
        //视频第一帧
        $img = $post['img'];
        //视频路径地址
        $videoUrl = $post['videoUrl'];
        //拼装数据
        $data = [
            'uid'   => $uid,
            'videourl' => $videoUrl,
            'img'      => $img,
            'status'   => 1,
            'addtime'  => time(),
            'updatetime'  => time(),
        ];
        //添加数据到数据库
        if(Db::name('video')->insert($data)){
            $this->II('200','添加成功',array());
        }else{
            $this->II('201','添加失败，稍后重试',array());
        }
    }


}