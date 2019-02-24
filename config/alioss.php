<?php
/**
 * Created by PhpStorm.
 * User: nick
 * Date: 2018/8/29
 * Time: 上午11:07
 */
return [
    'accessKeyId'=> env('OSS_KEY_ID','*'),
    'accessKeySecret'=> env('OSS_KEY_SECRET','*'),

    'is_cname'=> false,
    'bucket'=>env('OSS_BUCKET','*'),

    /**
     * 外网访问，开启使用此地址
     */
    'endpoint'=> env('OSS_ENDPOINT','oss-cn-shanghai.aliyuncs.com'),

    /**
     * 在ECS内网使用服务器访问时的地址
     */
    'vpc-endpoint'=> 'oss-cn-shanghai-internal.aliyuncs.com',

    /**
     * 上传时是否改文件名
     */
    'change-uploader-filename' => true,
];