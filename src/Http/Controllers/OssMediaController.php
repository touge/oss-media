<?php

namespace Touge\OssMedia\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use Touge\OssMedia\Services\AliOSS;

class OssMediaController extends Controller
{

    public function ckeditor_image_upload(Request $request){

        if($request->hasFile('upload')){
            $upload_file = $request->file('upload');

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

            if (is_image($response['file_path'])){
                $response['preview'] = $oss_client->signUrl($response['file_path']);
            }


            return [
                "uploaded"=> 1,   //写死的
                "fileName"=> $response['original_name'],  //图片名
                "url"=> $response['preview']  //上传服务器的图片的url
            ];

        }

        return [
            "uploaded"=> 0,   //写死的
            "fileName"=> "exampe.png",  //图片名
            "url"=> "example"  //上传服务器的图片的url
        ];


    }

    /**
     * ckeditor 图片浏览
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function ckeditor_image_browser(){
        return view('oss-media::ckeditor');
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

            if (is_image($response['file_path'])){
                $response['preview'] = $oss_client->signUrl($response['file_path']);
            }

            return ['status'=>'successful','data'=>$response];
        }
        return ['status'=>'failed','message'=>'上传文件失败'];
    }


    /**
     * 获得访问文件路径
     *
     * @param Request $request
     * @return array
     */
    public function oss_file_url(Request $request){
        $object = $request->get('object',null);
        if($object==null){
            return ['status'=>'failed','message'=>'object file empty'];
        }

        $url = (config('alioss.use_ssl') ? "https://" : "http://") .config('alioss.bucket') . '.' . config('alioss.endpoint') . '/' . $object;
        return ['status'=>'successful','url'=>$url];
    }

    /**
     * 获得带有签名的文件地址,当文件为私有时，需要使用此接口
     *
     * @return array
     * @throws \OSS\Core\OssException
     */
    public function sign_oss_file_url(Request $request){
        $object = $request->get('object',null);
        if($object==null){
            return ['status'=>'failed','message'=>'object file empty'];
        }
        return ['status'=>'successful','url'=>(new AliOSS())->signUrl($object)];
    }

    /**
     * 远程文件列表
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function oss_files(Request $request){
        $oss = new AliOSS();

        $prefix = $request->get('prefix',null);
        $files = $oss->listObjects($prefix);

        return view("oss-media::oss-files" ,compact('files' ,'prefix'));
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