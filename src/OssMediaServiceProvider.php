<?php

namespace Touge\OssMedia;

use Illuminate\Support\ServiceProvider;
use Encore\Admin\Admin;
use Encore\Admin\Form;
class OssMediaServiceProvider extends ServiceProvider
{

    /**
     * {@inheritdoc}
     */
    public function boot(OssMedia $extension)
    {
        if (! OssMedia::boot()) {
            return ;
        }

        if ($views = $extension->views()) {
            $this->loadViewsFrom($views, 'oss-media');
        }

        if ($this->app->runningInConsole() && $assets = $extension->assets()) {
            $this->publishes(
                [
                    $assets => public_path('vendor/touge/oss-media'),
                    OssMedia::config_path() => config_path()
                ],
                'oss-media'
            );
        }




        Admin::booting(function () {
            Form::extend('oss_media', OssMediaField::class);
        });


        $this->app->booted(function () {
            OssMedia::routes(__DIR__.'/../routes/web.php');
        });
    }
}