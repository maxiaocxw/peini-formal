<?php
namespace app\union\controller;
use app\union\controller\Auth;  
use think\Db;
use think\Request;
class Index extends Auth{
    public function index(){
//        $this->userid = session('union.unid');
        $menu_list_one = Db::name('menu_union')->where('level = 1 and statu = 1')->select();
        $menu_list_two = Db::name('menu_union')->where('level = 2 and statu = 1')->select();
//        $this->assign('union',session('union'));
        $this->assign('menu_list_one',$menu_list_one);
        $this->assign('menu_list_two',$menu_list_two);
        return $this->fetch();
    }
    public function welcome(){
        return $this->fetch();
    }
}