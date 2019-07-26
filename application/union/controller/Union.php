<?php
namespace app\union\controller;
use app\union\controller\Auth;
use app\api\controller\Qiniu;
use think\Db;
use think\Request;
use think\Session;
use think\cache;

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
        //接受条件 判断是否有条件查询
        $where_data = input('post.');
        $where['union'] = $unid;
        if( $where_data ){
            $username = $where_data['username'];
            $time_data = $where_data['time_data'];
            if($time_data){
                //根据~号分割成为数组 开始 结束
                $time = explode('~',$time_data);
                //开始时间
                $start_time = strtotime($time[0]);
                //结束时间
                $end_time = strtotime($time[1]);
            }
            //判断条件是时间还是名称
            if( !empty($where_data['username']) && !empty($where_data['time_data']) ){
                //时间和名称
                $where['username'] = $where_data['username'];
                $number_profit['receivetime'] = array( 'between', "$start_time,$end_time" );
            }elseif ( !empty($where_data['username']) && $where_data['time_data'] == '' ){
                //单独名称
                $where['username'] = $where_data['username'];
            }elseif(!empty($where_data['time_data']) && $where_data['username'] == ''){
                //单独时间
                $number_profit['receivetime'] = array( 'between', "$start_time,$end_time" );
            }
                $union = Db::name('user')->where($where)->order('uniontime','desc')->paginate(15,false);

        }else{
            $username = '';
            $time_data = '';
            //查询公会的所有成员
            $union = Db::name('user')->where($where)->order('uniontime','desc')->paginate(15,false);
        }
        //将结果转换成数组
        $union_data = $union->toArray();
        $union_member = $union_data['data'];

        //查询成员的游戏标签信息
        foreach ($union_member as $k => $v) {
            //将用户的手机号变为*号
            $union_member[$k]['mobile'] = substr_replace($v['mobile'], '****', 3, 4);
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
                $gift_profit_res = Db::name('send_log')->where( [ 'acceptuid'=>$v['uid'], 'status'=>2 ] )->sum('amount');
                $gift_profit = intval($gift_profit_res[0]['tp_sum']);
                //接单收益
                $game_profit_sql = 'SELECT SUM(amount) AS tp_sum FROM `pn_game_order` WHERE pid = '.$v['uid'].' AND `status` = 3 OR pid = '.$v['uid'].' AND `status` = 4';
                $game_profit_res = Db::query($game_profit_sql);
                $game_profit = intval($game_profit_res[0]['tp_sum']);
                //收益总和
                $union_member[$k]['all_profit'] = $gift_profit + $game_profit;
                //判断条件里是否有时间选择
                if( !empty( $number_profit ) ){
                    $number_profit['pid'] = $v['uid'];
                    //周期接单数量
                    $time_number_res = Db::name('game_order')->where($number_profit)->select();
                    $time_number_count = count($time_number_res);
                    $union_member[$k]['num'] = intval($time_number_count);
                    //周期收益总和
                    //礼物
                    $whereA = [
                        'acceptuid'=>$v['uid'],
                        'status'=>2
                    ];

                    $time_gift_profit = Db::name('send_log')->where($whereA)->whereTime('addtime', 'between', [$start_time, $end_time])->sum('amount');
                    //接单
                    $time_game_profit = Db::name('game_order')->where($number_profit)->sum('amount');
                    $union_member[$k]['all_profit'] = intval($time_gift_profit + $time_game_profit);
                }
            }else{
                $union_member[$k]['game'] = '';
                $union_member[$k]['label'] = '';
                $union_member[$k]['num'] = '';
                $union_member[$k]['all_profit'] = 0;
            }
        }
        $this->assign('username',$username);
        $this->assign('time_data',$time_data);
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
    //解散公会
    public function unionDismiss(){
        if(input('?post.table')){
            $id = input('post.id');
            if(empty($id)){
                echo json_encode(['code' => 1,'msg' => '参数错误','icon' =>2]);
                exit;
            }else{
                //将用户表里的所有unid的数据都清为0
                $user_union['union'] = 0;
                $user_union['uniontime'] = 0;
                $user_res =Db::name(trim(input('post.table')))->where('union',$id)->update($user_union);
                //删除公会表里的数据
                $union_data['status'] = 7;
                $union_res= Db::name('union')->where( 'unid', $id )->update($union_data);
                if($user_res && $union_res){
                    cache::clear();
                    echo json_encode(['code' => 0,'msg' => '解散成功','icon' => 1]);
                }else{
                    echo json_encode(['code' => 1,'msg' => '系统错误','icon' => 2]);
                }
            }
        }else{
            echo json_encode(['code' => 1,'msg' =>'禁止非法操作','icon' => 3]);
        }
    }
}