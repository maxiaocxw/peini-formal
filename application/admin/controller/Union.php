<?php
namespace app\admin\controller;
use app\admin\controller\Auth;
use app\api\controller\Qiniu;
use think\Db;
use think\Request;
use think\Session;

class Union extends Auth{

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

    //对私公会
    public function privateunion(){
        //查询所有数据
        $work = Db::name('union')->where( 'type',2 )->order('status','asc')->order('addtime','desc')->paginate(30,false);
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

    //公会所有成员信息
    public function member(){
        //登陆时获取的公会id
        $unid = input('unid');
        //查询公会的所有成员
        $union = Db::name('user')->where('union',$unid)->order('uniontime','desc')->paginate(30,false);
        //将结果转换成数组
        $union_data = $union->toArray();
        $union_member = $union_data['data'];

        //查询成员的游戏标签信息
        foreach ($union_member as $k => $v) {
            //判断成员是否是陪玩用户  如果是的话 查询所选的游戏和标签
            if( $v['type'] == 2 ){
                //陪玩用户选择的游戏
                $member_info = Db::name('approve')->field('uid,gameid,labelid')->where('uid',$v['uid'])->find();
                $gameid_res = Db::name('game')->field('name')->where( 'gid', 'in',$member_info['gameid'])->select();
                $gameid_name = array_column($gameid_res, 'name');
                $game = implode( ',', $gameid_name );
                $union_member[$k]['game'] = $game;
                //陪玩用户选择的标签
                $labelid_res = Db::name('label')->field('name')->where( 'lid', 'in',$member_info['labelid'])->select();
                $labelid_rname = array_column($labelid_res, 'name');
                $label = implode( ',', $labelid_rname );
                $union_member[$k]['label'] = $label;
                //陪玩用户的接单数量
                $number_sql = 'SELECT COUNT(*) AS tp_count FROM `pn_game_order` WHERE pid = '.$v['uid'].' AND `status` = 3 OR pid = '.$v['uid'].' AND `status` = 4';
                $number_res = Db::query($number_sql);
                $union_member[$k]['num'] = intval($number_res[0]['tp_count']);
                //陪玩用户的所有收益
                //礼物收益
                $game_profit_res = Db::name('send_log')->where( [ 'acceptuid'=>$v['uid'], 'status'=>2 ] )->sum('amount');
                $gift_profit = intval($game_profit_res[0]['tp_sum']);
                //接单收益
                $game_profit_sql = 'SELECT SUM(amount) AS tp_sum FROM `pn_game_order` WHERE pid = '.$v['uid'].' AND `status` = 3 OR pid = '.$v['uid'].' AND `status` = 4';
                $game_profit_res = Db::query($game_profit_sql);
                $game_profit = intval($game_profit_res[0]['tp_sum']);
                //收益总和
                $union_member[$k]['all_profit'] = $gift_profit + $game_profit;
            }else{
                $union_member[$k]['game'] = '';
                $union_member[$k]['label'] = '';
                $union_member[$k]['num'] = '';
                $union_member[$k]['all_profit'] = 0;
            }
        }
        $this->assign('data',$union_member);
        $this->assign('total',$union_data['total']);
        $this->assign('list',$union);
        return $this->fetch();
    }
}