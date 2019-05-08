<?php
/**
 * Created by PhpStorm.
 * User: nick
 * Date: 2018/8/29
 * Time: 上午11:07
 */

namespace Touge\OssMedia\Services;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\UploadedFile;
use OSS\Core\OssException;
use OSS\OssClient;
use Ramsey\Uuid\Uuid;

class AliOSS
{
    /**
     * @var \Illuminate\Config\Repository|mixed
     */
    protected $config;

    /**
     * @var \OSS\OssClient
     */
    protected $oss_client = null;

    /**
     * AliOSS constructor.
     */
    public function __construct(){
        $config= config("oss-media");

//        $this->config = config('alioss');
        $this->config = $config["alioss"];

        if($this->oss_client==null){
            $this->oss_client();
        }
    }

    /**
     *
     */
    protected function oss_client(){
        try {
            $this->oss_client = new OssClient(
                $this->config['accessKeyId'],
                $this->config['accessKeySecret'],
                $this->config['endpoint']
            );
        }catch (OssException $ossException){
            throw (new HttpResponseException(response()->json([
                'status'=> 'failed',
                'message'=> $ossException->getMessage(),
                'code'=> $ossException->getCode()
            ])));
        }
    }

    /**
     * @var String
     */
    protected $file_folder;

    /**
     * @var string
     */
    protected $file_name = null;

    /**
     * 设置上传文件路径
     *
     * @param $file_folder
     * @return $this
     */
    public function file_folder($file_folder){
        $this->file_folder = $file_folder;
        return $this;
    }


    /**
     * @param $file_name
     * @return $this
     */
    public function file_name($file_name){
        $this->file_name = $file_name;
        return $this;
    }

    /**
     * 获得上传文件 /path/filename.extension
     *
     * @param $file_ext
     * @return string
     * @throws \Exception
     */
    private function object($file_ext){
        if($this->file_name==null){
            $this->file_name = Uuid::uuid1()->toString() . '.' . $file_ext;
        }
        return $this->file_folder . '/' . $this->file_name;
    }

    /**
     * @param OssException $ossException
     */
    public function throw_error(OssException $ossException){
        throw (new HttpResponseException(response()->json([
            'status'=> 'failed',
            'code'=> $ossException->getCode(),
            'message'=> $ossException->getMessage(),
        ])));
    }


    /**
     * @param $object
     * @param int $timeout
     * @return string
     * @throws OssException
     */
    public function signUrl($object,$timeout=3600){
        return $this->oss_client->signUrl($this->config['bucket'],$object,$timeout,'GET');
    }

    /**
     * @param $object
     * @return bool
     */
    public function deleteObject($object){
        try{
            $this->oss_client->deleteObject($this->config['bucket'],$object);
            return true;
        }catch (OssException $ossException){
            return $this->throw_error($ossException);
        }
    }

    /**
     * @param $object
     * @return bool
     */
    public function doesObjectExist($object){
        return $this->oss_client->doesObjectExist($this->config['bucket'],$object);
    }

    /**
     * @param UploadedFile $file
     * @return array|bool
     */
    /**
     * @param UploadedFile $file
     * @return array|bool
     * @throws \Exception
     */
    public function upload(UploadedFile $file){
        if(!$file->isValid()){
            return false;
        }
        $options = [
            'original_name'=> $file->getClientOriginalExtension(),
            'mime_type'=> $file->getClientMimeType(),
            'extension'=> $file->getClientOriginalExtension(),
        ];

        $real_file_path = $file->getRealPath();
        try{
            $object = $this->object($options['extension']);
            $this->oss_client->uploadFile(
                $this->config['bucket'],
                $object,
                $real_file_path
            );
            return [
                'original_name'=> $file->getClientOriginalName(),
                'mime_type'=> $file->getClientMimeType(),
                'extension'=> $file->getClientOriginalExtension(),
                'file_path'=> $object,
                'object_name'=> '',
                'size'=> $file->getSize()
            ];
        }catch (OssException $e){
            return $this->throw_error($e);
        }
    }

    /**
     * 通过php内置函数取得文件信息
     *
     * @param $file
     * @return array
     */
    protected function file_info($file){
        $pathinfo = pathinfo($file);
        return [
            'extension'=>array_key_exists('extension',$pathinfo) ? $pathinfo['extension'] : '',
            'original_name'=>$pathinfo['basename']
        ];
    }

    /**
     * 获得oss中存储的文件元数据
     *
     * @param $object
     * @return array
     */
    public function getObjectMeta($object){
        $file_info = $this->file_info($object);
        $object_meta = $this->oss_client->getObjectMeta($this->config['bucket'],$object);
        $meta_options = [
            'extension'=>$file_info['extension'],
            'original_name'=>$file_info['original_name'],
            'mime_type'=>$object_meta['content-type'],
            'size'=>$object_meta['content-length'],
            'file_path'=> $object
        ];
        return $meta_options;
    }

    /**
     * oss中拷贝文件
     *
     * @param $from_object
     * @param $to_object
     * @return bool
     */
    public function copyObject($from_object,$to_object){
        try{
            $this->oss_client->copyObject($this->config['bucket'], $from_object, $this->config['bucket'], $to_object);
            return true;
        }catch (OssException $e){
            return $this->throw_error($e);
        }
    }

    /**
     * 移动oss中的一个文件并返回文件相关信息
     *
     * @param $object
     * @return array|bool
     * @throws \Exception
     */
    public function moveObject($object){
        $object_metas = $this->getObjectMeta($object);
        $to_object = $this->object($object_metas['extension']);

        if($this->copyObject($object,$to_object)==false){
            return false;
        }

        if( $this->deleteObject($object) ){
            $object_metas['file_path'] = $to_object;
            return $object_metas;
        }
        return false;
    }


    public function listObjects($prefix=null){
        $bucket = $this->config['bucket'];

        $options = [];
        if($prefix){
            $options['prefix'] = $prefix;
        }

        $objectList = [];
        $prefixList = [];
        while (true) {
            try {
                $listObjectInfo = $this->oss_client->listObjects($bucket,$options);
            } catch (OssException $e) {
                printf(__FUNCTION__ . ": FAILED\n");
                printf($e->getMessage() . "\n");
                return;
            }
            // 得到nextMarker，从上一次listObjects读到的最后一个文件的下一个文件开始继续获取文件列表。
            $nextMarker = $listObjectInfo->getNextMarker();
            $listObject = $listObjectInfo->getObjectList();
            $listPrefix = $listObjectInfo->getPrefixList();

            if (!empty($listObject)) {
                foreach ($listObject as $objectInfo) {
                    array_push($objectList,$objectInfo->getKey());
                }
            }
            if (!empty($listPrefix)) {
                foreach ($listPrefix as $prefixInfo) {
                    array_push($prefixList,$prefixInfo->getPrefix());
                }
            }
            if ($nextMarker === '') {
                break;
            }
        }

        return ['objectList'=>$objectList,'prefixList'=>$prefixList];
    }
}