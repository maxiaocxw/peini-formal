<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
use think\Db;
//检测参数
/**
 *@param name string or array 参数名称,字符串间以,隔开
 * @param type 传参方式 默认post
 * @param is_need 是否为必传参数， true 为是(默认) false 为否
 * @return  success:array error:json 且停止执行
 */
function check_param($name,$is_need=true,$type="post"){
    if(!is_array($name)){
        $name   = explode(',',$name);
    }
    $data = array();
    foreach($name as $k => $v){
        $str = $type.'.'.$v;
        $param = input($str);
        if(empty($param) && $is_need){
            $data[] = $v;
        }
    }
    if(empty($data)){
        return ['res' => true,'param' => ''];
    }else{
        return ['res' => false,'param' => implode(',', $data)];
    }
}
//生成唯一token
function setLoginToken($user_account=''){
    $str=md5(uniqid(md5(microtime(true)), true));
    $str=sha1($str.$user_account);
    return $str;
}
// 计算时间
function calculate_time($time){
    $cha_time=time()-$time;
    if($cha_time>60*24*60) {
        $cha_data=intval($cha_time/60/60/24)."天前发布";
    } elseif ($cha_time>60*60) {
        $cha_data=intval($cha_time/60/60)."小时前发布";
    } else {
        $cha_data=intval($cha_time/60).'分钟前发布';     
    }
    return $cha_data;
}

// 返回结果输出
function json_echo($code,$mes,$data = array()){
    echo json_encode(array("code"=>$code,"msg"=> $mes,"data"=> $data));
    exit;
}
// 递归创建文件夹
function creatfile($path){
    if (!file_exists($path)){
        creatfile(dirname($path));
        mkdir($path, 0777);
    }
}
// base64处理图片
function base64_image($images,$path){
    creatfile($path);
    if(is_array($images)){
        foreach ($images as $img => $imgs) {
            $img = explode(',', $imgs); //截取data:image/png;base64, 这个逗号后的字符
            $files = time().rand(10000,99999).'.png';
            $path_file = $path.'/'.$files;
            //服务器文件存储路径
            if (file_put_contents($path_file, base64_decode($img[1]))){
                $baseimgs[] = $path_file;
            }
        }
    }else{
        // $img = explode(',', $images); //截取data:image/png;base64, 这个逗号后的字符
        $files = time().rand(10000,99999).'.png';
        $path_file = $path.'/'.$files;
        //服务器文件存储路径
        if (file_put_contents($path_file, base64_decode($images))){
            $baseimgs[] = $path_file;
        }
    }
    $imgstr = '';
    foreach ($baseimgs as $i => $img) {
        $imgstr .= '/'.$img.',';
    }
    $imgstr = rtrim($imgstr,',');
    return $imgstr;
}
// 上传图片
/**
 *@param dir 路径
 *@param goods_id 商品id用于生成缩略图
 *@param thumb  true 为是(默认) false 为否
 */
function uploadimgs($dir,$goods_id = NULL,$thumb = false){
    $file_info = $_FILES['file'];
    $suffix = substr(strrchr($file_info['name'], '.'), 1); 
    $file_error = $file_info['error'];
    if(!is_dir($dir))//判断目录是否存在
    {
        mkdir ($dir,0777,true);//如果目录不存在则创建目录
    };
    $file = $dir.$_FILES["file"]["name"];
    $filename = time().rand(111111,999999);
    $pathfiles = $dir.$filename.'.'.$suffix;
    if(!file_exists($file))
    {
        if($file_error == 0){
            if(move_uploaded_file($_FILES["file"]["tmp_name"],$pathfiles)){
                if(!empty($thumb)){
                    $file_thumb = zoom_image($dir."/thumb/",$pathfiles,300,300,$goods_id,$suffix,$filename);
                }
                
                $arr['msg'] ="上传成功";
            }else{
                $arr['msg'] = "上传失败";
            }
        }else{
            $arr['msg'] = "上传失败";
        }
    }
    else
    {
        $arr['code'] ="1"; 
        $arr['msg'] = "当前目录中，文件".$file."已存在";
    }

    //初始化返回数组
    $arr = array(
    'code' => 0,
    'msg'=> '',
    'data' =>array(
         'src' => 'http://'.$_SERVER['SERVER_NAME'].'/'. $pathfiles,
         'dir' => $dir,
         'file' => $filename.'.'.$suffix,
         ),
    );
    if(!empty($thumb)){
        $arr['data']['src_thumb'] = 'http://'.$_SERVER['SERVER_NAME'].'/'. $file_thumb;
    }
    return $arr;
    // echo json_encode($arr);
}
/**
* 图像的裁剪
* @param string $dir        缩略图路径
* @param string $file        文件完整路径+文件名
* @param int  $width      裁剪的宽度/限制的高度或宽度，当有$height值时此值为图片的宽度，否则为限制的宽度或高度
* @param int  $height     [可选]裁剪的高度
* @param int  $goods_id       [可选]商品id(可选)
* @param int  $suffix       [可选]文件后缀
* @param int  $filename       [可选]不含后缀的文件名
*/
function zoom_image($dir,$file,$width = null,$height = null,$goods_id = NULL,$suffix,$filename){
    if(empty($goods_id)){
        $dir = $dir;
    }else{
        $dir = "upload/market/".$goods_id."/thumb/";
    }
    $goods_thumb_name = $filename."_{$width}_{$height}";
    require_once APP_PATH.'../thinkphp/library/think/Image.php';
    $image = new \Think\Image();
    $image->open($file);  //打开上传图片
    $imgWidth = $image->width();
    $imgHeight = $image->height();
    
    if(!is_dir($dir))//判断目录是否存在
    {
        mkdir ($dir,0777,true);//如果目录不存在则创建目录
    };
    $goods_thumb_name = $goods_thumb_name . '.' . $suffix;
    $image->thumb($width, $height,3)->save($dir . $goods_thumb_name, NULL, 100); //按照原图的比例生成一个最大为$width*$height的缩略图并保存
    return $dir . $goods_thumb_name;
}
/**
* 编辑器上传图片
* @param string $dir        路径
* @param string $file_info  上传的文件
*/
function editupimgs($file_info,$dir){
    $suffix = substr(strrchr($file_info['name'], '.'), 1); 
    $file_error = $file_info['error'];
    if(!is_dir($dir))//判断目录是否存在
    {
        mkdir ($dir,0777,true);//如果目录不存在则创建目录
    };
    $file = $dir.$_FILES["file"]["name"];
    $filename = time().rand(111111,999999);
    $pathfiles = $dir.$filename.'.'.$suffix;
    if(!file_exists($file))
    {
        if($file_error == 0){
            if(move_uploaded_file($_FILES["file"]["tmp_name"],$pathfiles)){
                $arr['msg'] ="上传成功";
            }else{
                $arr['msg'] = "上传失败";
            }
        }else{
            $arr['msg'] = "上传失败";
        }
    }
    else
    {
        $arr['code'] ="1"; 
        $arr['msg'] = "当前目录中，文件".$file."已存在";
    }
    //初始化返回数组
    $arr = array(
    'code' => 0,
    'msg'=> '',
    'data' =>array(
         'src' => 'http://'.$_SERVER['SERVER_NAME'].'/'. $pathfiles,
         'dir' => $dir,
         'file' => $filename.'.'.$suffix,
         ),
    );
    return $arr;
}
// 随机生成订单
function generate_dingdan( $length = 5 ) {
    // 密码字符集，可任意添加你需要的字符
    $chars = '0123456789';

    $password = '';
    for ( $i = 0; $i < $length; $i++ )
    {
        // 这里提供两种字符获取方式
        // 第一种是使用 substr 截取$chars中的任意一位字符；
        // 第二种是取字符数组 $chars 的任意元素
        // $password .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        $password .= $chars[ mt_rand(0, strlen($chars) - 1) ];
    }

    return $password;
}
// 数据去重
function assoc_unique($arr, $key) {  
    $tmp_arr = array();  
    foreach($arr as $k => $v) {  
        if(in_array($v[$key], $tmp_arr)) {  
            unset($arr[$k]);  
        } else {  
            $tmp_arr[] = $v[$key];  
        }  
    }  
    sort($arr);  
    return $arr;  
}
function curl_get($url){
    $ch = curl_init();
    //设置选项，包括URL
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);

    //执行并获取HTML文档内容
    $output = curl_exec($ch);
    //释放curl句柄
    curl_close($ch);
    return $output;
}
// 二维数组排序
function my_sort($arrays, $sort_key, $sort_order = SORT_DESC){
    if (is_array($arrays)) {
        foreach ($arrays as $array) {
            if (is_array($array)) {
                $key_arrays[] = $array[$sort_key];
            } else {
                return false;
            }
        }
    } else {
        return false;
    }
    if(empty($key_arrays)){
        $key_arrays = array();
    }
    array_multisort($key_arrays, SORT_DESC, $arrays);
    return $arrays;
}
function userlevel($num){
    // $biglevel = floor($num / 50);
    // $imgstr = 's';
    // switch ($biglevel) {
    //     case 0:
    //         $imgstr .= 1;
    //         break;
    //     case 1:
    //         $imgstr .= 1;
    //         break;
    //     case 2:
    //         $imgstr .= 2;
    //         break;
    //     case 3:
    //         $imgstr .= 3;
    //         break;
    //     case 4:
    //         $imgstr .= 4;
    //         break;
    //     case 5:
    //         $imgstr .= 5;
    //         break;
    //     default:
    //         $imgstr .= 5;
    //         break;
    // }
    // $smaillevel = $num % 50;
    // if($biglevel > 5){
    //     if($smaillevel <50 && $smaillevel > 40){
    //         $imgstr .= '5';
    //     }else if($smaillevel <= 40 && $smaillevel > 30){
    //         $imgstr .= '4';
    //     }else if($smaillevel <= 30 && $smaillevel > 20){
    //         $imgstr .= '3';
    //     }else if($smaillevel <= 20 && $smaillevel > 10){
    //         $imgstr .= '2';
    //     }else{
    //         $imgstr .= '1';
    //     }
    // }else{
    //     $imgstr .= '5';
    // }
    $level = floor($num / 10) < 0 ? 0 : floor($num / 10);
    return $level;
}

/**
 * 返回数组中指定的一列
 * @param $input            需要取出数组列的多维数组（或结果集）
 * @param $columnKey        需要返回值的列，它可以是索引数组的列索引，或者是关联数组的列的键。 也可以是NULL，此时将返回整个数组（配合index_key参数来重置数组键的时候，非常管用）
 * @param null $indexKey    作为返回数组的索引/键的列，它可以是该列的整数索引，或者字符串键值。
 * @return array            返回值
 */
function _array_column($input, $columnKey, $indexKey = null)
{
    if (!function_exists('array_column')) {
        $columnKeyIsNumber = (is_numeric($columnKey)) ? true : false;
        $indexKeyIsNull = (is_null($indexKey)) ? true : false;
        $indexKeyIsNumber = (is_numeric($indexKey)) ? true : false;
        $result = array();
        foreach ((array)$input as $key => $row) {
            if ($columnKeyIsNumber) {
                $tmp = array_slice($row, $columnKey, 1);
                $tmp = (is_array($tmp) && !empty($tmp)) ? current($tmp) : null;
            } else {
                $tmp = isset($row[$columnKey]) ? $row[$columnKey] : null;
            }
            if (!$indexKeyIsNull) {
                if ($indexKeyIsNumber) {
                    $key = array_slice($row, $indexKey, 1);
                    $key = (is_array($key) && !empty($key)) ? current($key) : null;
                    $key = is_null($key) ? 0 : $key;
                } else {
                    $key = isset($row[$indexKey]) ? $row[$indexKey] : 0;
                }
            }
            $result[$key] = $tmp;
        }
        return $result;
    } else {
        return array_column($input, $columnKey, $indexKey);
    }
}

/**
 * 生成订单号
 * @return string
 */
function granTranNo(){
    $str = date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    return $str;
}

/**
 * 计算年龄
 * @param $birthday
 * @return bool|false|int
 */
function birthday($birthday)
{
    $date =date('Ymd',$birthday);
    $age = strtotime($date);
    if ($age === false) {
        return false;
    }
    list($y1, $m1, $d1) = explode("-", date("Y-m-d", $age));
    $now = strtotime("now");
    list($y2, $m2, $d2) = explode("-", date("Y-m-d", $now));
    $age = $y2 - $y1;
    if ((int)($m2 . $d2) < (int)($m1 . $d1))
        $age -= 1;
    return $age;
}

function addLog($uid,$tranNo,$type,$content,$msg){
    $data = [
        'type' =>$type,
        'content' => json_encode($content,JSON_UNESCAPED_UNICODE),
        'info' => $msg,
        'uid'  => $uid,
        'tranno' => $tranNo,
        'addtime'  => time()
    ];

    Db::name('log')->insert($data);
}

function addMessage($uid,$type,$title,$content){
    $data = [
        'uid'   => $uid,
        'type'  => $type,
        'title' => $title,
        'content' => $content,
        'addtime' => time(),
    ];
    $res = Db::name('message')->insert($data);
    return $res;
}