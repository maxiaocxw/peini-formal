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
			if(!$this->params['uid']){
				$this->II('100','参数错误');
			}
			$this->params['updatetime']=time();
            if($this->params['birthday']){
                $this->params['birthday']=strtotime($this->params['birthday']);
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

	public function updateHeadimg(){
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
	}


	public function uploadFile($file)
    {  
        // 获取表单上传文件  
        
        if(empty($file)) {  
            $this->error('请选择上传文件');  
        }
        $file_type = $file->getInfo()['type'];
        if(($file_type == "image/gif")|| ($file_type == "image/jpeg")|| ($file_type == "image/jpg")|| ($file_type == "image/pjpeg")|| ($file_type == "image/x-png")|| ($file_type == "image/png")){
            // 移动到框架应用根目录/public/uploads/ 目录下  
            $info = $file->move(ROOT_PATH.'public'.DS.'upload'); 
            //如果不清楚文件上传的具体键名，可以直接打印$info来查看  
            //获取文件（文件名），$info->getFilename()  ***********不同之处，笔记笔记哦
            //获取文件（日期/文件名），$info->getSaveName()  **********不同之处，笔记笔记哦
            $filename = $info->getSaveName();  //在测试的时候也可以直接打印文件名称来查看 
            if($filename){            
                $arr['code']=1;
                $arr['data']='upload/'.$filename;  
                return $arr;  
            }else{  
                $arr['code']=2;
                return $arr;
            }  
        }else{
            return $arr['code']=2;
        }
        //var_dump($file->info()['name']);die;
        
    }
}