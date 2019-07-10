<?php
namespace app\index\controller;
use think\Controller;

class Index extends Controller {

    /**
     * 调转指定页面
     * @return mixed
     */
	public function index(){
	    $isMobile = $this->is_mobile();
	    if($isMobile){
            return $this->fetch('mobile');
        }else {
            return $this->fetch('web');
        }
	}

    /**
     * 判断是否是pc端访问还是手机端访问
     * @return false|int
     */
    public  function is_mobile(){

        // returns true if one of the specified mobile browsers is detected
        // 如果监测到是指定的浏览器之一则返回true

        $regex_match="/(nokia|iphone|android|motorola|^mot\-|softbank|foma|docomo|kddi|up\.browser|up\.link|";

        $regex_match.="htc|dopod|blazer|netfront|helio|hosin|huawei|novarra|CoolPad|webos|techfaith|palmsource|";

        $regex_match.="blackberry|alcatel|amoi|ktouch|nexian|samsung|^sam\-|s[cg]h|^lge|ericsson|philips|sagem|wellcom|bunjalloo|maui|";

        $regex_match.="symbian|smartphone|midp|wap|phone|windows ce|iemobile|^spice|^bird|^zte\-|longcos|pantech|gionee|^sie\-|portalmmm|";

        $regex_match.="jig\s browser|hiptop|^ucweb|^benq|haier|^lct|opera\s*mobi|opera\*mini|320x320|240x320|176x220";

        $regex_match.=")/i";

        // preg_match()方法功能为匹配字符，既第二个参数所含字符是否包含第一个参数所含字符，包含则返回1既true
        return preg_match($regex_match, strtolower($_SERVER['HTTP_USER_AGENT']));
    }
}