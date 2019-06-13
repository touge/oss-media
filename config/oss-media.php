<?php
/**
 * Created by PhpStorm.
 * User: nick
 * Date: 2018/8/29
 * Time: 上午11:07
 */
return [
    'filesystem'=> 'network',
//    'filesystem'=> 'alioss',
    'alioss'=>[
        'accessKeyId'=> env('OSS_KEY_ID','******'),
        'accessKeySecret'=> env('OSS_KEY_SECRET','******'),

        'is_cname'=> false,
        'bucket'=>env('OSS_BUCKET','******'),

        'use_ssl'=> true,

        /**
         * 外网访问，开启使用此地址
         */
        'endpoint'=> env('OSS_ENDPOINT','oss-cn-shanghai.aliyuncs.com'),

        /**
         * 在ECS内网使用服务器访问时的地址
         */
        'vpc-endpoint'=> 'oss-cn-shanghai-internal.aliyuncs.com',
    ],
    'network'=> [
        'access_files_api_url'=> env("ACCESS_FILES_API_URL" ,'http://127.0.0.1:8001'),
        'access_files_url'=> env("ACCESS_FILES_URL" ,'http://127.0.0.1:8001/storage/'),
        'access_files_key'=> env("ACCESS_FILES_KEY", "******"),
        'access_files_expire'=> env("ACCESS_FILES_EXPIRE" ,300)
    ],

    /**
     * 是否开启上传
     */
    'is_upload'=> false,

    /**
     * 上传时是否改文件名
     */
    'change-uploader-filename' => true,
];