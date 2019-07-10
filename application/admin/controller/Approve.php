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
                $appData = ['status'=>2];
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



}