<?php
namespace app\union\controller;
use app\union\controller\Auth;
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
        $unid = session('union.unid');
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

    //工会成员收益信息
    public function profit(){
        
    }
    //公会批量踢出成员
    public function delAllUnion(){
        if(input('?post.table')){
            $id = input('post.id/a');
            if(empty($id)){
                echo json_encode(['code' => 1,'msg' => '请选择数据','icon' =>2]);
                exit;
            }else{
                $where['uid'] = array('IN',implode(',', $id));
                $user_data['union'] = 0;
                $user_data['uniontime'] = 0;
                $result =Db::name(trim(input('post.table')))->where($where)->update($user_data);
                if($result){
                    echo json_encode(['code' => 0,'msg' => '踢出成功','icon' => 1]);
                }else{
                    echo json_encode(['code' => 1,'msg' => '系统失败','icon' => 2]);
                }
            }
        }else{
            echo json_encode(['code' => 1,'msg' =>'禁止非法操作','icon' => 3]);
        }
    }
    //公会踢出成员单个
    public function updateUnion(){
        if(request()->isPost()){
            $data = input('post.');
            if(!$data){
                echo json_encode(['code' => 0,'msg' => '参数错误','icon' =>1]);
                die;
            }else{
                $where['uid'] = array('EQ',$data['uid']);
                $update['union'] = 0;
                $update['uniontime'] = 0;
                $result = Db::name('user')->where($where)->update($update);
                if($result){
                    echo json_encode(['code' => 0,'msg' => '踢出成功','icon' =>1]);
                    die;
                }else{
                    echo json_encode(['code' => 1,'msg' => '系统错误','icon' =>2]);
                    die;
                }
            }
        }
    }
    //会长邀请新的成员
    public function inviteUnion(){
        $username = input('post.username');
        if(!empty($username)){
            $where =" username like '%".$username."%' and status = 1 and type = 2";
            $union_data = Db::name('user')->where($where)->select();
            //关联查询陪玩的游戏标签和接单数
            foreach($union_data as $k => $v){
                //陪玩用户选择的游戏
                $member_info = Db::name('approve')->field('uid,gameid,labelid')->where('uid',$v['uid'])->find();
                $gameid_res = Db::name('game')->field('name')->where( 'gid', 'in',$member_info['gameid'])->select();
                $gameid_name = array_column($gameid_res, 'name');
                $game = implode( ',', $gameid_name );
                $union_data[$k]['game'] = $game;
                //陪玩用户选择的标签
                $labelid_res = Db::name('label')->field('name')->where( 'lid', 'in',$member_info['labelid'])->select();
                $labelid_rname = array_column($labelid_res, 'name');
                $label = implode( ',', $labelid_rname );
                $union_data[$k]['label'] = $label;
                //陪玩用户的接单数量
                $number_sql = 'SELECT COUNT(*) AS tp_count FROM `pn_game_order` WHERE pid = '.$v['uid'].' AND `status` = 3 OR pid = '.$v['uid'].' AND `status` = 4';
                $number_res = Db::query($number_sql);
                $union_data[$k]['num'] = intval($number_res[0]['tp_count']);
                if(!empty($v['union'])){
                    //判断陪玩是否已有公会
                    $union_data[$k]['invite'] = 2;
                }else{
                    $union_data[$k]['invite'] = 1;
                }
            }
            $this->assign('username',$username);
        }else{
            $union_data = [];
        }

        $this->assign('union_data',$union_data);
        return $this->fetch();
    }
    //编辑公会基本信息
    public function updateInfo(){
        $union_data=Db::name('union')->where('unid='.input('unid'))->find();
        $this->assign('union',$union_data);
        return $this->fetch();
    }
    public function updateInfoDo(){
        $req=input('post.');
        if( $req ){
            //判断公会名称是否存在
            $union_sql = 'SELECT name FROM pn_union where unid <> '.$req['unid'].' and name = "'.$req['name'].'"' ;
            $union_res = Db::query($union_sql);
            if( $union_res ){
                echo json_encode(['code'=>1,'msg'=>'公会名称已存在','icon'=>2]);
            }else{
                $res = Db::name('union')->where('unid='.$req['unid'])->update($req);
                if( $res || $res === 0 ){
                    echo json_encode(['code' => 0,'msg' => '修改成功','icon' =>1]);
                }else{
                    echo json_encode(['code' => 1,'msg' => '修改失败','icon' =>2]);
                }
            }
        }else{
            echo (['code' => 1,'msg' => '参数错误','icon' =>2]);
        }
    }
}