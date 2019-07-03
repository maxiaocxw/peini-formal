<?php
namespace app\index\controller;
header("Content-type: text/html; charset=utf-8");
use Qiniu\Auth;
use Qiniu\Processing\PersistentFop;
use think\Cache;
use think\Controller;
use think\Db;
use think\Session;
class Operate extends Controller {
	//该class为运营的所有方法
	//运营用户信息
	public function user_information_list(){
		$req = $this->request->get();
		if (!isset($req['page']) || !isset($req['limit'])) {
			return $this->fetch();
		} else if (!isset($req['query']) || empty($req['query'])) {
			//查询用户所有信息
			$data = Db::name('player')
				->order("registertime", "desc")
				->page($req['page'], $req['limit'])
				->select();
			$count = Db::name('player')->count();
			foreach ($data as $key => $value) {
				$data[$key]['registertime'] = date('Y-m-d H:i:s', $value['registertime']);
				$data[$key]['page'] = $req['page'];
				$data[$key]['limit'] = $req['limit'];
				$playermoney = Db::name('playermoney')->where('player_id',$value['player_id'])->value('player_money');
				$data[$key]['money'] = $playermoney;
			}
			return json(['result' => true, 'code' => 0, 'msg' => '查询成功！', 'count' => $count, 'data' => $data]);
		} else {
			$data = Db::name('player')
				->where('task_id', intval($req['query']))
				->whereOr('task_name', 'like', $req['query'])
				->order("registertime", "desc")
				->page($req['page'], $req['limit'])
				->select();
			$count = Db::name('player')
				->where('task_id', intval($req['query']))
				->whereOr('task_name', $req['query'])
				->count();
			foreach ($data as $key => $value) {
				$data[$key]['registertime'] = date('Y-m-d H:i:s', $value['registertime']);
				$data[$key]['page'] = $req['page'];
				$data[$key]['limit'] = $req['limit'];
				$playermoney = Db::name('playermoney')->where('player_id',$value['player_id'])->value('player_money');
				$data[$key]['money'] = $playermoney;
			}
			return json(['result' => true, 'code' => 0, 'msg' => '查询成功！', 'count' => $count, 'data' => $data]);
		}
	}
	//运营主播信息
	public function anchor_information_list(){
		$req = $this->request->get();
		if (!isset($req['page']) || !isset($req['limit'])) {
			return $this->fetch();
		} else if (!isset($req['query']) || empty($req['query'])) {
			//查询用户所有信息
			$data = Db::name('player')
				->order("registertime", "desc")
				->page($req['page'], $req['limit'])
				->select();
			$count = Db::name('player')->count();
			foreach ($data as $key => $value) {
				$data[$key]['registertime'] = date('Y-m-d H:i:s', $value['registertime']);
				$data[$key]['page'] = $req['page'];
				$data[$key]['limit'] = $req['limit'];
				$playermoney = Db::name('playermoney')->where('player_id',$value['player_id'])->value('player_money');
				$data[$key]['money'] = $playermoney;
			}
			return json(['result' => true, 'code' => 0, 'msg' => '查询成功！', 'count' => $count, 'data' => $data]);
		} else {
			$data = Db::name('player')
				->where('task_id', intval($req['query']))
				->whereOr('task_name', 'like', $req['query'])
				->order("registertime", "desc")
				->page($req['page'], $req['limit'])
				->select();
			$count = Db::name('player')
				->where('task_id', intval($req['query']))
				->whereOr('task_name', $req['query'])
				->count();
			foreach ($data as $key => $value) {
				$data[$key]['registertime'] = date('Y-m-d H:i:s', $value['registertime']);
				$data[$key]['page'] = $req['page'];
				$data[$key]['limit'] = $req['limit'];
				$playermoney = Db::name('playermoney')->where('player_id',$value['player_id'])->value('player_money');
				$data[$key]['money'] = $playermoney;
			}
			return json(['result' => true, 'code' => 0, 'msg' => '查询成功！', 'count' => $count, 'data' => $data]);
		}
	}
	//运营主播审核
	public function anchor_information_audit(){
		$req = $this->request->get();
		if (!isset($req['page']) || !isset($req['limit'])) {
			return $this->fetch();
		} else if (!isset($req['query']) || empty($req['query'])) {
			//查询用户所有信息
			$data = Db::name('player')
				->order("registertime", "desc")
				->page($req['page'], $req['limit'])
				->select();
			$count = Db::name('player')->count();
			foreach ($data as $key => $value) {
				$data[$key]['registertime'] = date('Y-m-d H:i:s', $value['registertime']);
				$data[$key]['page'] = $req['page'];
				$data[$key]['limit'] = $req['limit'];
				$playermoney = Db::name('playermoney')->where('player_id',$value['player_id'])->value('player_money');
				$data[$key]['money'] = $playermoney;
			}
			return json(['result' => true, 'code' => 0, 'msg' => '查询成功！', 'count' => $count, 'data' => $data]);
		} else {
			$data = Db::name('player')
				->where('task_id', intval($req['query']))
				->whereOr('task_name', 'like', $req['query'])
				->order("registertime", "desc")
				->page($req['page'], $req['limit'])
				->select();
			$count = Db::name('player')
				->where('task_id', intval($req['query']))
				->whereOr('task_name', $req['query'])
				->count();
			foreach ($data as $key => $value) {
				$data[$key]['registertime'] = date('Y-m-d H:i:s', $value['registertime']);
				$data[$key]['page'] = $req['page'];
				$data[$key]['limit'] = $req['limit'];
				$playermoney = Db::name('playermoney')->where('player_id',$value['player_id'])->value('player_money');
				$data[$key]['money'] = $playermoney;
			}
			return json(['result' => true, 'code' => 0, 'msg' => '查询成功！', 'count' => $count, 'data' => $data]);
		}
	}
}
?>