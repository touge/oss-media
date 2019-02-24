<?php

namespace Touge\OssMedia;

use Encore\Admin\Extension;

class OssMedia extends Extension
{
    public $name = 'oss-media';

    public $views = __DIR__.'/../resources/views';

    public $assets = __DIR__.'/../resources/assets';


    public static function config_path(){
        return __DIR__.'/../config';
    }
}