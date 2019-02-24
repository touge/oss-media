<?php

namespace Touge\OssMedia\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use Touge\OssMedia\Services\AliOSS;

class OssMediaController extends Controller
{
    public function index()
    {
        return view("oss-media::index");
    }


    /**
     * 上传到阿里云oss
     *
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function upload2oss(Request $request){
        if($upload_file = $request->file('dm-uploader-file')){
            $folder = date('Ym/d');

            //查看配置中上传时是否改名
            $upload_filename= null;
            if(config('alioss.change-uploader-filename')==false){
                $upload_filename = $upload_file->getClientOriginalName();
            }

            $oss_client = new AliOSS();


            $response = $oss_client->file_folder($folder)
                ->file_name($upload_filename)
                ->upload($upload_file);

            return ['status'=>'successful','data'=>$response];
        }
        return ['status'=>'failed','message'=>'上传文件失败'];
    }



    /**
     * 远程文件列表
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function oss_files(Request $request){
        $oss = new AliOSS();
        $files = $oss->listObjects($request->get('prefix',null));

        return view("oss-media::oss-files" ,compact('files'));
    }

    /**
     * 阿里云远程文件窗口
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function oss_modal(Request $request){
//        $response_name = $request->get('response-name');
//        $attachment_id = $request->get('attachment-id');
//        $item= $request->get('item');
//        $item_id = $request->get('item_id');
//
//        $compacts = compact('response_name','attachment_id','item','item_id');

        return view("oss-media::oss-modal");
    }
}