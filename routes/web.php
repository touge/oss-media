<?php

use Touge\OssMedia\Http\Controllers\OssMediaController;

Route::get('oss-media', OssMediaController::class.'@index');
Route::post('oss-media/upload2oss', OssMediaController::class.'@upload2oss');

Route::get('oss-media/oss-modal', OssMediaController::class.'@oss_modal');
Route::get('oss-media/oss-files', OssMediaController::class.'@oss_files');
