<?php
namespace app\api\controller;
use app\api\controller\Auth;
use think\Request;
use think\Db;
class Orderlist extends Auth{
	public function _initialize(){
		$this->checkParam();
		$this->checkToken();
		$this->uid = input('post.uid')?input('post.uid'):0;//普通用户id
		$this->page=input('post.page')?input('post.page'):1;
		$this->type=input('post.type')?input('post.type'):0;//类别 1为用户展示的  2为陪玩展示的
		$this->limit=10;
		$this->url='http://cdn.lanyushiting.com/';
	}

	public function index(){
		$this->checkParam('type');
		if($this->type==1){
			$wherelist='uid='.$this->uid;
		}elseif($this->type==2){
			$wherelist='pid='.$this->uid;
		}else{
			$this->II('201','数据错误');
		}
		$list=Db::name('game_order')->field('id,tranno,uid,pid,amount,num,gid,addtime,status')->where($wherelist)->order('addtime desc')->page($this->page,$this->limit)->select();
		foreach($list as $key=>$val){
			if($this->type==1){
				$userinfo=$this->getContent($val['pid']);
				$list[$key]['headimg']=$userinfo['headimg'];
				$list[$key]['username']=$userinfo['username'];
			}elseif($this->type==2){
				$userinfo=$this->getContent($val['uid']);
				$list[$key]['headimg']=$userinfo['headimg'];
				$list[$key]['username']=$userinfo['username'];
			}
			$list[$key]['addtime']=date('Y-m-d H:i',$val['addtime']);
			$list[$key]['gamename']=Db::name('game')->where('gid='.$val['gid'])->value('name');	
			if($val['status']==4){
				$list[$key]['comment']=Db::name('comment')->where('orderid='.$val['id'])->value('score');
			}else{
				$list[$key]['comment']=0;
			}
		}
		$this->II('200','获取成功',$list);
	}


	//订单详情
	public function orderDetail(){
		$this->checkParam('id');
		$id=input('post.id');
		$info=Db::name('game_order')->field('id,uid,pid,num,gid,status')->where('id='.$id)->find();
		if($info['uid']==$this->uid){
			$info['type']=1;//下的单
		}
		if($info['pid']==$this->uid){
			$info['type']=2;//接的单
		}
		$user=Db::name('user')->field('username,sex,birthday,headimg')->where('uid='.$info['pid'])->find();
		$info['name']=$user['username'];
		$info['headimg']=$this->url.$user['headimg'];
		$info['age']=(date('Y',time()))-(date('Y',$user['birthday']));
		$info['sex']=$user['sex'];
		$info['game']=Db::name('game')->where('gid='.$info['gid'])->value('name');
		if($info['status']==4){
			$comment=Db::name('comment')->field('score,tagids,addtime')->where('orderid='.$info['id'])->find();
			$info['score']=$comment['score'];
			if($comment['tagids']){
				$info['tags']=Db::name('label')->field('name')->where('lid in ('.$comment['tagids'].')')->select();
			}else{
				$info['tags']=array();
			}
			
			$info['addtime']=date('Y-m-d H:i',$comment['addtime']);
		}else{
			$info['score']=0;
			$info['tags']=array();
			$info['addtime']='0';
		}
		$this->II('200','获取成功',$info);
	}

	public function getContent($uid){
		$contentinfo=Db::name('user')->field('username,headimg')->where('uid='.$uid)->find();
		$contentinfo['headimg']=$this->url.$contentinfo['headimg'];
		return $contentinfo;
	}
}