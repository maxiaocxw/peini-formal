<?php
/**
 * Created by PhpStorm.
 * User: csq
 * Date: 2019/7/6
 * Time: 18:03
 */
namespace app\api\controller;
use think\Config;
use Qiniu\Storage\BucketManager;
use Qiniu\Storage\UploadManager;

vendor('php-sdk.autoload');

class Qiniu{

    public $qiniuConfig = [];

    public function __construct(){
        $this->qiniuConfig = Config::get('qiniu');
    }

    //获取token
    public function getToken(){

        //初始化鉴权对象
        $auth = new \Qiniu\Auth($this->qiniuConfig['accessKey'],$this->qiniuConfig['secretKey']);
        $time = 60*60*24*365*100;
        $token = $auth->uploadToken($this->qiniuConfig['bucket'],$time);
        return $token;
    }

    /**
     * 上传视频、图片
     * @param  string $prefix 前缀（例如：图片（image） 视频（video））
     * @param  string $suffix 后缀 （上传文件名的后缀 例如：图片（.png .jpg） 视频（ .mp4 .mov））
     * @param string $filePath 上传到服务器的路径
     * @return string
     */
    public function upload($prefix,$suffix,$filePath){
        //字符串
        $fileResource = fopen($filePath, 'r');
        $fileString =  fread($fileResource, filesize($filePath));

        //定义上传到七牛文件名
        $qiNiuKey = $prefix.'/'.time().'/'.rand(1000,9999).$suffix;
        $result = $this->uploadStrToQiniu($fileString,$qiNiuKey,$this->qiniuConfig['bucket']);
        return json_encode($result);
    }



    /**
     * 上传字符串到七牛
     * @param string $stringFile 要上传的字符串
     * @param string $qiNiuKey   文件七牛中的名称
     * @param string $bucket     要上传的bucket
     * @return array
     */
    private function uploadStrToQiniu($stringFile, $qiNiuKey, $bucket) {
        if ( !$stringFile || !$qiNiuKey || !$bucket ) {
            return [];
        }

        $token = $this->getToken($bucket);
        $isExist = $this->getQiniuStat($qiNiuKey, $bucket);

        if ( !$isExist ) {
            list($ret, $err) = (new UploadManager())->put($token, $qiNiuKey, $stringFile);
            if ( $err !== null ) {
                return [];
            }
            return $ret;
        }
        return $isExist;

    }

    /**
     * 删除七牛空间中的文件
     * @param $bucket
     * @param $fileKey
     * @return bool
     */
    public function deleteFileToQiniu($bucket, $fileKey) {
        $auth = new \Qiniu\Auth($this->qiniuConfig['accessKey'],$this->qiniuConfig['secretKey']);
        list(, $err) = (new BucketManager($auth))->delete($bucket, $fileKey);

        if ( $err !== null ) {
            return false;
        }
        return true;
    }


    /**
     * 获取文件的状态信息
     * @param string $bucket    七牛的bucket
     * @param string $path      七牛中文件路径(文件名) 样例video/201709/3011/s/59cf0968a85ba.mp4
     * @return array            样例['fsize'=>1, 'hash'=>'lnRey_ep', 'mimeType'=>'video/mp4', 'putTime'=>1506740,
     *                          'type'=>0]
     */
    public function getQiniuStat($bucket, $path) {
        $auth = new \Qiniu\Auth($this->qiniuConfig['accessKey'],$this->qiniuConfig['secretKey']);

        list($ret, $err) = (new BucketManager($auth))->stat($bucket, $path);
        if ( $err !== null ) {
            return [];
        }
        return $ret;
    }

}
