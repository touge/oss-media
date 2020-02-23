<?php
/**
 * Created by PhpStorm.
 * User: nick
 * Date: 2019-02-25
 * Time: 11:10
 */

namespace Touge\OssMedia;


use Encore\Admin\Form\Field;

class OssField extends Field
{
    protected static $css = [
        'vendor/touge/oss-media/uploader/css/jquery.dm-uploader.css',
    ];
    protected static $js = [
        'vendor/touge/oss-media/uploader/js/jquery.dm-uploader.min.js',
        'vendor/touge/oss-media/uploader/js/ui-main.js',
        'vendor/touge/oss-media/oss-media.js',
        'vendor/touge/oss-media/jquery.cookie.min.js'
    ];
}