<?php
namespace app\api\controller;
use app\api\controller\Auth;
use think\Db;
class EditInformation extends Auth{
	public function _initialize(){
		$this->checkParam();
		$this->checkToken();
		$this->params = input('post.')?input('post.'):0;
	}
	public function index(){
		if($this->params){
            unset($this->params['sign']);
			$this->params['updatetime']=time();
            if($this->params['birthday']){
                $this->params['birthday']=strtotime($this->params['birthday']);
            }
            if($this->params['username']){
            	if(Db::name('user')->where("username='".$this->params['username']."'")->value('uid')){
            		$this->II('201','已有昵称');
            	}
            }
			if(Db::name('user')->update($this->params)){
				$this->II('200','修改成功',array($this->getUserInfo($this->params['uid'])));
			}else{
				$this->II('201','修改失败');
			}
		}else{
			$this->II('100','参数错误');
		}
	}

/*	public function updateHeadimg(){
		$file = request()->file('headimg');
        if(!$this->params['uid'] || !$file){
			$this->II('100','参数错误');
		}
        $res=$this->uploadFile($file);
        if($res['code']==1){
            $is=Db::name('user')->where('uid='.$this->params['uid'])->update(array('headimg'=>$res['data']));
            if($is){
                $this->II('200','修改成功',array('headimg'=>$res['data']));
            }else{
                $this->II('201','修改失败');
            }
        }else{
            $this->II('201','上传文件失败');
        }
	}*/

}