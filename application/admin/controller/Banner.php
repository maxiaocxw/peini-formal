<?php
namespace app\admin\controller;
use app\admin\controller\Auth;
use think\Db;
class Banner extends Auth{
	public function articleBanner(){
		$type = empty(input('type')) ? 0 : input('type');
		if(!empty($type)){
			$where['type'] = array('EQ',$type);			
		}
		else{
			$where['type'] = array('neq',3);			
		}
		$is_app = empty(input('is_app')) ? 0 : input('is_app');
		if(!empty($is_app)){
			$where['is_app'] = array('EQ',$is_app);			
		}
		$language = empty(input('language')) ? 0 : input('language');
		if(!empty($language)){
			$where['language'] = array('EQ',$language);			
		}
		$where['status'] = array('neq','-1');
		$list = Db::name('right_deal_lifestyle')->where($where)->paginate(30,false,['param' => request()->param()]);
		$this->assign('list',$list);
		return $this->fetch();
	}

	public function addArticleBanner(){
		if(request()->isAjax()){
			$data = input('post.');
			$data['status'] = 1;
			if(input('banner') == 1){
				unset($data['banner']);
				$result = Db::name('banner')->insert($data);
			}else{
				unset($data['banner']);
				if(input('type') == 3){
					$data['classify'] = input('classify');
				}
				
				$result = Db::name('right_deal_lifestyle')->insert($data);
			}
			
			if($result){
				echo json_encode(['code'=>0,'msg' =>'成功','icon' => 1]);
			}else{
				echo json_encode(['code' =>1,'msg' => '失败','icon' => 3]);
			}
		}else{
			$type = empty(input('type')) ? 0 : input('type');
			if($type == 3){
				$classify = Db::name('classify')->where('is_index = 1 and pid = 0 and status = 1 and type = 3 and is_hot = 1')->select();
				$this->assign('classify',$classify);
			}
			return $this->fetch();
		}
	}
	public function editArticleBanner(){
		if(request()->isAjax()){
			$data = input('post.');
			$data['status'] = 1;
			$data['classify'] = input('classify');
			unset($data['id']);
			$result = Db::name('right_deal_lifestyle')->where('id = '.input('id'))->update($data);
			// echo Db::name('right_deal_lifestyle')->getLastsql();die;
			if($result){
				echo json_encode(['code'=>0,'msg' =>'成功','icon' => 1]);
			}else{
				echo json_encode(['code' =>1,'msg' => '失败','icon' => 3]);
			}
		}else{
			$type = empty(input('type')) ? 0 : input('type');
			$advers = Db::name('right_deal_lifestyle')->where('id = '.input('id'))->find();
			$classify = Db::name('classify')->where('is_index = 1 and pid = 0 and status = 1 and type = 3 and is_hot = 1')->select();
			$this->assign('advers',$advers);
			$this->assign('id',input('id'));
			$this->assign('classify',$classify);
			return $this->fetch();
		}
	}
	public function delBanner(){
		if(request()->isAjax()){
			if(empty(input('post.id/a'))){
				echo json_encode(['code' => 1,'msg' => '请选择数据','icon' => 3]);
				exit;
			}else{
				$where['id'] = array('IN',implode(',', input('post.id/a')));
				$result = Db::name('banner')->where($where)->update(['status' => -1]);
				if($result){
					echo json_encode(['code' => 0,'msg' => '删除成功','icon' => 1]);
				}else{
					echo json_encode(['code' => 1,'msg' => '删除失败','icon' => 3]);
				}
			}
		}
	}

	public function delrightBanner(){
		if(request()->isAjax()){
			if(empty(input('post.id/a'))){
				echo json_encode(['code' => 1,'msg' => '请选择数据','icon' => 3]);
				exit;
			}else{
				$where['id'] = array('IN',implode(',', input('post.id/a')));
				$result = Db::name('right_deal_lifestyle')->where($where)->update(['status' => -1]);
				if($result){
					echo json_encode(['code' => 0,'msg' => '删除成功','icon' => 1]);
				}else{
					echo json_encode(['code' => 1,'msg' => '删除失败','icon' => 3]);
				}
			}
		}
	}

	public function delindexBanner(){
		if(request()->isAjax()){
			if(empty(input('post.id/a'))){
				echo json_encode(['code' => 1,'msg' => '请选择数据','icon' => 3]);
				exit;
			}else{
				$where['id'] = array('IN',implode(',', input('post.id/a')));
				$result = Db::name('index_right_banner')->where($where)->update(['status' => -1]);
				if($result){
					echo json_encode(['code' => 0,'msg' => '删除成功','icon' => 1]);
				}else{
					echo json_encode(['code' => 1,'msg' => '删除失败','icon' => 3]);
				}
			}
		}
	}

	public function Bannerlist(){
		$type = empty(input('type')) ? 0 : input('type');
		if(!empty($type)){
			$where['type'] = array('EQ',$type);			
		}
		$is_app = empty(input('is_app')) ? 0 : input('is_app');
		if(!empty($is_app)){
			$where['is_app'] = array('EQ',$is_app);			
		}
		$language = empty(input('language')) ? 0 : input('language');
		if(!empty($language)){
			$where['language'] = array('EQ',$language);			
		}
		$where['status'] = array('NEQ','-1');
		$list = Db::name('banner')->where($where)->paginate(30,false,['param' => request()->param()]);
		$this->assign('list',$list);
		return $this->fetch();
	}

	public function bannerEdit(){
		$list=Db::name('banner')->where('id='.input('id'))->find();
		$this->assign('list',$list);
		return $this->fetch();
	}

	public function bannerEditDo(){
		unset($_POST['file']);
		$list=Db::name('banner')->update($_POST);
		if($list){
			$this->success('修改成功','/admin/banner/bannerlist','1');
		}else{
			$this->success('修改失败');
		}
	}
	public function indexRightBanner(){
		$is_app = empty(input('is_app')) ? 0 : input('is_app');
		if(!empty($is_app)){
			$where['is_app'] = array('EQ',$is_app);			
		}
		$language = empty(input('language')) ? 0 : input('language');
		if(!empty($language)){
			$where['language'] = array('EQ',$language);			
		}
		$where['status'] = array('EQ','1');
		$list = Db::name('index_right_banner')->where($where)->paginate(30,false,['request' => request()->param()]);
		$this->assign('list',$list);
		return $this->fetch();
	}
	public function addIndexRightBanner(){
		if(request()->isPost()){
			$data = input('post.');
			$data['status'] = 1;
			$result = Db::name('index_right_banner')->insert($data);
			if($result){
				echo json_encode(['code' => 0,'msg' => '添加成功','icon' => 1]);
			}else{
				echo json_encode(['code' => 1,'msg' => '添加失败','icon' => 2]);
			}
		}else{
			return $this->fetch();
		}
		
	}
	public function editIndexRightBanner(){
		if(request()->isPost()){
			$data = input('post.');
			$result = Db::name('index_right_banner')->where('id = ' . $data['id'])->update($data);
			if($result){
				echo json_encode(['code' => 0,'msg' => '操作成功','icon' => 1]);
			}else{
				echo json_encode(['code' => 1,'msg' => '操作失败','icon' => 2]);
			}
		}else{
			$data = Db::name('index_right_banner')->where('id = ' . input('id'))->find();
			$this->assign('data',$data);
			return $this->fetch();
		}
	}
}