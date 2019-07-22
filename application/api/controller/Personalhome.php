<?php
namespace app\api\controller;
use app\api\controller\Auth;
use think\Db;
class Personalhome extends Auth{
	public function _initialize(){
		$this->checkParam();
		$this->checkToken();
		$this->uid = input('post.uid')?input('post.uid'):0;//用户id
		$this->pid = input('post.pid')?input('post.pid'):0;//陪玩id
		$this->url = 'http://cdn.lanyushiting.com/';
		$this->checkParam('pid');
	}
	public function index(){
		$list=Db::name('user')->field('uid,username,headimg,birthday,level,sex,info,interestid,workid,city')->where('uid='.$this->pid)->find();
		$list['headimg']=$this->url.$list['headimg'];
		$list['age']=(date('Y',time()))-(date('Y',$list['birthday']));
		$userapprove=Db::name('approve')->where('uid='.$this->pid)->value('gameid');
		if(!$userapprove){
			$userapprove=0;
		}
		$list['games']=Db::name('game')->field('gid,name,img')->where('gid in('.$userapprove.')')->select();
		if($list['games']){
			foreach($list['games'] as $k=>$v){
				$list['games'][$k]['price']=Db::name('playinfo')->where('uid='.$this->pid.' and gameid='.$v['gid'])->value('price');
				$order=Db::name('game_order')->field('id')->where('pid='.$this->pid.' and gid='.$v['gid'].' and status=3 or status=4')->select();
				$list['games'][$k]['ordernums']=count($order);
				$lids=Db::name('comment')->where('pid='.$this->pid)->value('tagids');
				if($lids){
					$tag=Db::name('label')->field('name')->where('lid in('.$lids.')')->limit(2)->select();
				}else{
					$tag=array();
				}
				
				$list['games'][$k]['tags']=$tag;
			}
		}else{
			$list['games']=array();
		}
		//总评价数
		$list['countcomment']=Db::name('comment')->where('pid='.$this->pid)->count();
		//评价平均分数
		$avgsxore=Db::name('comment')->field('AVG(score) as score')->where('pid='.$this->pid)->find()['score'];
		$avgsxore=$avgsxore?(int)$avgsxore:0;
		$list['averagescore']=$avgsxore;
		//获取星座
		if($list['birthday']){
			$list['constellation']=$this->get_constellation($list['birthday']);
		}else{
			$list['constellation']='未知';
		}
		if(!$list['city']){
			$list['city']='未知';
		}
		//获取兴趣
		if($list['interestid']){
			/*$insert=Db::name('interest')->field('name')->where('nid in('.$list['interestid'].')')->select();
			$inserting='';
			if($insert){
				foreach($insert as $kk=>$vv){
					$inserting.=$vv['name'].',';
				}
				$inserting=trim(',',$inserting);
			}{
				$inserting='未知';
			}*/
			$list['interest']=$list['interestid'];
		}else{
			$list['interest']='未知';
		}
		if($list['workid']){
			$list['work']=Db::name('work')->where('wid='.$list['workid'])->value('name');
		}else{
			$list['work']='未知';
		}
		if(Db::name('like')->where('uid='.$this->uid.' and acceptuid='.$this->pid.' and status=1')->value('lid')){
			$list['islike']=1;
		}else{
			$list['islike']=0;
		}
		$this->II('200','获取成功',$list);
	}
}