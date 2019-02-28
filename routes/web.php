<?php

use Touge\OssMedia\Http\Controllers\OssMediaController;

//Route::get('oss-media', ['uses'=>OssMediaController::class.'@index' ,'as'=>'oss-media.index']);
Route::post('oss-media/upload2oss', ['uses'=>OssMediaController::class.'@upload2oss' ,'as'=>'oss-media.upload2oss']);

Route::get('oss-media/oss-modal', ['uses'=>OssMediaController::class.'@oss_modal','as'=>'oss-media.oss-modal']);
Route::get('oss-media/oss-files', ['uses'=>OssMediaController::class.'@oss_files' ,'as'=>'oss-media.oss-files']);
Route::get('oss-media/oss-file-url', ['uses'=>OssMediaController::class.'@oss_file_url' ,'as'=>'oss-media.oss-file-url']);

/**
 * ckeditor插件
 */
Route::get('oss-media/ckeditor-image-browser', ['uses'=>OssMediaController::class.'@ckeditor_image_browser' ,'as'=>'oss-media.ckeditor-image-browser']);
Route::post('oss-media/ckeditor-image-upload', ['uses'=>OssMediaController::class.'@ckeditor_image_upload' ,'as'=>'oss-media.ckeditor-image-upload']);
