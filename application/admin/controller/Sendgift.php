<?php
namespace app\admin\controller;
use app\admin\controller\Auth;
use think\Db;
class Sendgift extends Auth{
	public function index(){
		$where['status'] = array('neq','-1');
		$list = Db::name('send_log')->where($where)->paginate(30,false,['param' => request()->param()]);
		$data = $list->toArray();
		foreach($data['data'] as $k=>&$v){
			$v['uname']=Db::name('user')->where('uid='.$v['uid'])->value('username');
			$v['pname']=Db::name('user')->where('uid='.$v['acceptuid'])->value('username');
			$v['giftname']=Db::name('gift')->where('gid='.$v['gid'])->value('name');
			$v['addtime']=date('Y-m-d H:i:s',$v['addtime']);
		}
		$this->assign('data',$data['data']);
		$this->assign('total',$data['total']);
		$this->assign('list',$list);
		return $this->fetch();
	}

	public function update(){
		$list=Db::name('send_log')->where('lid='.input('lid'))->find();
		$this->assign('list',$list);
		return $this->fetch();
	}
	public function updatedo(){
		$arr=input('post.');
		Db::name('send_log')->where('lid='.$arr['lid'])->update($arr);
		$this->success('修改成功','/admin/tag/index','1');
	}

	public function delTag(){
		if(request()->isAjax()){
			if(empty(input('post.lid/a'))){
				echo json_encode(['code' => 1,'msg' => '请选择数据','icon' => 3]);
				exit;
			}else{
				$where['lid'] = array('IN',implode(',', input('post.lid/a')));
				$result = Db::name('label')->where($where)->update(['status' => -1]);
				if($result){
					echo json_encode(['code' => 0,'msg' => '删除成功','icon' => 1]);
				}else{
					echo json_encode(['code' => 1,'msg' => '删除失败','icon' => 3]);
				}
			}
		}
	}
}