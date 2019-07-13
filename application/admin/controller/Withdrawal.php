<?php
namespace app\admin\controller;
use app\admin\controller\Auth;
use app\api\controller\Qiniu;
use think\Db;
use think\Request;
use think\Session;

class Withdrawal extends Auth{

    //对公公会
    public function withdrawal(){
        //查询所有数据
        $withdrawal = Db::name('withdrawal w')->field('w.*,u.username as uname')->join( 'user u', 'w.uid = u.uid' )->order('status','asc')->order('addtime','desc')->paginate(30,false);
        //将结果转换成数组
        $withdrawal_data = $withdrawal->toArray();
        $this->assign('data',$withdrawal_data['data']);
        $this->assign('total',$withdrawal_data['total']);
        $this->assign('list',$withdrawal);
        return $this->fetch();
    }
    //提现记录批量删除假删
    public function delAllWith(){
        if(input('?post.table')){
            $id = input('post.id/a');
            if(empty($id)){
                echo json_encode(['code' => 1,'msg' => '请选择数据','icon' =>2]);
                exit;
            }else{
                $where['wid'] = array('IN',implode(',', $id));
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
    //提现记录审核
    public function updateWith(){
        if(request()->isPost()){
            $data = input('post.');
            $where['wid'] = array('EQ',$data['wid']);
            $data['audittime'] = time();
            $result = Db::name('withdrawal')->where($where)->update($data);
            if($result){
                echo json_encode(['code' => 0,'msg' => '修改成功','icon' =>1]);
            }else{
                echo json_encode(['code' => 1,'msg' => '修改失败','icon' =>2]);
            }
        }
    }
}