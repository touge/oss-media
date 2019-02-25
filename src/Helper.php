<?php
/**
 * Created by PhpStorm.
 * User: nick
 * Date: 2019-02-25
 * Time: 09:49
 */

if(!function_exists('oss_sign_url')){
    function oss_sign_url($object){
        return (new \Touge\OssMedia\Services\AliOSS())->signUrl($object);
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