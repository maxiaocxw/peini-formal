<?php
namespace app\admin\controller;
use app\admin\controller\Auth;
use think\Db;
use think\Request;
use think\Session;

class User extends Auth{
	public function index(){
		$where=" status!=-1";
		if(input('username')){
			$where .=" and username like '%".input('username')."%'";
			$this->assign('username',input('username'));
		}
		$list = Db::name('user')->where($where)->paginate(30,false,['request' => request()->param()]);
		$data = $list->toArray();
		$this->assign('data',$data['data']);
		$this->assign('total',$data['total']);
		$this->assign('list',$list);
		return $this->fetch();
	}

	public function update(){
		$list=Db::name('user')->where('uid='.input('uid'))->find();
		$this->assign('list',$list);
		return $this->fetch();
	}
	public function updatedo(){
		$arr=input('post.');

		/*//审核通过
		if($arr['status']==1){
			if(Db::query("SELECT table_name FROM information_schema.TABLES WHERE table_name ='xt_question_".$arr['cid']."'")){
				echo 1;
			}else{
				$companyname=Db::name('company')->where('cid='.$arr['cid'])->value('name');
				Db::query('CREATE TABLE `xt_question_'.$arr['cid'].'` (
		            `qid` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT "id",
		            `newid` int(11) NOT NULL COMMENT "问题发布者id",
		            `pid` int(11) NOT NULL COMMENT "项目id",
		            `is_client` tinyint(3) NOT NULL DEFAULT "0" COMMENT "是不是客户0不是 1是",
		            `type` tinyint(3) NOT NULL COMMENT "问题类型（1，bug，2，优化，3新增，4其他）",
		            `title` varchar(255) NOT NULL DEFAULT "0" COMMENT "问题标题",
		            `content` text COMMENT "问题详情和具体复现步骤",
		            `important` tinyint(3) NOT NULL DEFAULT "1" COMMENT "重要程度（1，轻微，2，一般，3，严重，4，致命）",
		            `status` tinyint(3) NOT NULL DEFAULT "0" COMMENT "状态（0待处理，1进行中，2拒绝，3已解决等待内测，4通过内测，5，已验收，-1删除）",
		            `assignedid` int(11) NOT NULL DEFAULT "0" COMMENT "指派人id",
		            `unassignedid` int(11) NOT NULL DEFAULT "0" COMMENT "被指派人id",
		            `addtime` int(11) NOT NULL COMMENT "操作时间",
		            `acceptancetime` int(11) NOT NULL COMMENT "验收时间",
		            PRIMARY KEY (`qid`),
		            UNIQUE KEY `qid` (`qid`)) ENGINE = MyISAM DEFAULT CHARSET=utf8 COMMENT="'.$companyname.'";');
			}	
		}*/
		Db::name('user')->where('uid='.$arr['uid'])->update($arr);
		$this->success('修改成功','/admin/user/index','1');
	}
}