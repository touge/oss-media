<?php
/**
 * Created by PhpStorm.
 * User: nick
 * Date: 2019-02-25
 * Time: 09:49
 */

if(!function_exists('tougeOssMediaRemoteFileUrl')){
    /**
     * 无验证的oss文件地址
     * @param $object
     * @return string
     */
    function tougeOssMediaRemoteFileUrl($object){
        $filesystem_config= config('oss-media');
        if($filesystem_config['filesystem']=='alioss'){
            $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
            $alioss = $filesystem_config['alioss'];//config('oss-media');
            return  $http_type. $alioss['bucket'] . '.' . $alioss['endpoint'] . '/' .$object;
        }

        return $filesystem_config['network']['access_files_url'] . $object;
    }
}

if(!function_exists('tougeOssMediaIsImage')){
    /**
     * 检查是否为图片文件的后辍名
     *
     * @param $file
     * @return bool
     */
    function tougeOssMediaIsImage($file){
        $extensions = ['jpg','jpeg','gif','png','bmp','webp','psd','svg','tiff'];
        return in_array(substr(strrchr($file ,'.'),1) ,$extensions);
    }
}

if(!function_exists('tougeOssMediaIsVideo')){
    /**
     * 检查是否为图片文件的后辍名
     *
     * @param $file
     * @return bool
     */
    function tougeOssMediaIsVideo($file){
        $extensions = ['mp4'];
        return in_array(substr(strrchr($file ,'.'),1) ,$extensions);
    }
}

if (!function_exists("tougeOssMediaFormatModalFolder")){
    /**
     * 格式化弹窗文件列表，阿里云与自建文件服务器的文件目录
     *
     * @param $folder
     * @return string
     */
    function tougeOssMediaFormatModalFolder($folder){
        $filesystem_config= config('oss-media');
        if($filesystem_config['filesystem']=='alioss') {
            return $folder;
        }
        return $folder. "/";
    }
}