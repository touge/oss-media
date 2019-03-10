<?php
/**
 * Created by PhpStorm.
 * User: nick
 * Date: 2019-02-25
 * Time: 09:49
 */

if(!function_exists('oss_sign_url')){
    /**
     * 带验签的oss地址路径
     * @param $object
     * @return string
     * @throws \OSS\Core\OssException
     */
    function oss_sign_url($object){
        return (new \Touge\OssMedia\Services\AliOSS())->signUrl($object);
    }
}



if(!function_exists('oss_no_sign_url')){
    /**
     * 无验证的oss文件地址
     * @param $object
     * @return string
     */
    function oss_no_sign_url($object){
        $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
        $alioss = config('alioss');
        return  $http_type. $alioss['bucket'] . '.' . $alioss['endpoint'] . '/' .$object;
    }
}

if(!function_exists('is_image')){
    /**
     * 检查是否为图片文件的后辍名
     *
     * @param $file
     * @return bool
     */
    function is_image($file){
        $extensions = ['jpg','jpeg','gif','png','bmp','webp','psd','svg','tiff'];

        return in_array(substr(strrchr($file ,'.'),1) ,$extensions);
    }
}