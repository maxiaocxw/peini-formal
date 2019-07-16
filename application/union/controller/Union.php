<?php
namespace app\union\controller;
use app\admin\controller\Auth;
use app\api\controller\Qiniu;
use think\Db;
use think\Request;
use think\Session;

class Union extends Auth{

    //公会基本信息
    public function unioninfo(){
        //查询所有数据
        $union = Db::name('union')->where( 'unid',cache('unid') )->find();
        $this->assign('union',$union);
        return $this->fetch();
    }

    //公会所有成员信息
    public function member(){
        //登陆时获取的公会id
        $unid = cache('unid');
        //查询公会的所有成员
        $union = Db::name('user')->where('union',$unid)->paginate(30,false);
        //将结果转换成数组
        $union_data = $union->toArray();
        $union_member = $union_data['data'];

        //查询成员的游戏标签信息
        foreach ($union_member as $k => $v) {
            //判断成员是否是陪玩用户  如果是的话 查询所选的游戏和标签
            if( $v['type'] == 2 ){
                $member_info = Db::name('approve')->field('gameid,labelid')->where('uid',$v['uid'])->find();
                $gameid_res = Db::name('game')->where( 'gid', 'in', member_info['gameid'] )->find('name');
                $gameid = implode(',', $gameid_res);
                $union_member[$k]['game'] = $gameid;
                $labelid_res = Db::name('label')->where( 'lid', 'in', member_info['labelid'] )->find('name');
                $labelid = implode(',', $labelid_res);
                $union_member[$k]['label'] = $labelid;
            }
        }
        var_dump($union_member);
        die;
        $this->assign('data',$union_member);
        $this->assign('total',$union_data['total']);
        $this->assign('list',$union);
        return $this->fetch();
    }

    //工会成员收益信息
    public function profit(){
        
    }
    //对公公会
    public function publicunion(){
        //查询所有数据
        $work = Db::name('union')->where( 'type',1 )->order('status','asc')->order('addtime','desc')->paginate(30,false);
        //将结果转换成数组
        $work_data = $work->toArray();
        $this->assign('data',$work_data['data']);
        $this->assign('total',$work_data['total']);
        $this->assign('list',$work);
        return $this->fetch();
    }
    //公会批量删除假删
    public function delAllUnion(){
        if(input('?post.table')){
            $id = input('post.id/a');
            if(empty($id)){
                echo json_encode(['code' => 1,'msg' => '请选择数据','icon' =>2]);
                exit;
            }else{
                $where['unid'] = array('IN',implode(',', $id));
                $union_data['status'] = 3;
                $result =Db::name(trim(input('post.table')))->where($where)->update($union_data);
                if($result){
                    echo json_encode(['code' => 0,'msg' => '删除成功','icon' => 1]);
                }else{
                    echo json_encode(['code' => 1,'msg' => '删除失败','icon' => 2]);
                }
            }
        }else{
            echo json_encode(['code' => 1,'msg' =>'禁止非法操作','icon' => 3]);
        }
    }
    //公会修改状态(审核和删除)
    public function updateUnion(){
        if(request()->isPost()){
            $data = input('post.');
            $where['unid'] = array('EQ',$data['unid']);
            $result = Db::name('union')->where($where)->update($data);
            if($result){
                echo json_encode(['code' => 0,'msg' => '修改成功','icon' =>1]);
            }else{
                echo json_encode(['code' => 1,'msg' => '修改失败','icon' =>2]);
            }
        }
    }
}