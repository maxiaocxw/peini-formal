<?php
/**
 * Created by PhpStorm.
 * User: csq
 * Date: 2019/7/19
 * Time: 21:52
 */
namespace app\index\controller;

use think\Controller;

class Privacy extends Controller {

    public function index(){
        return $this->fetch();
    }
}