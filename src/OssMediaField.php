<?php

namespace Touge\OssMedia;

use Encore\Admin\Form\Field;

class OssMediaField extends Field
{
    protected $view = 'oss-media::media';
    protected static $css = [
        'vendor/touge/oss-media/uploader/css/jquery.dm-uploader.css',
    ];
    protected static $js = [
        'vendor/touge/oss-media/uploader/js/jquery.dm-uploader.min.js',
        'vendor/touge/oss-media/uploader/js/ui-main.js',
        'vendor/touge/oss-media/modal.js',
    ];

    public function render(){
        $url_prefix = admin_url('oss-media');
        $_token = csrf_token();

        $this->script = <<<EOT
$('div.dm-uploader-panel').off('click','.btn-selector-alioss').on('click','.btn-selector-alioss',function(e){
    var that = this
    $(this).addClass('disabled').attr('disabled','disabled')
    var upload_input_element = $('div.dm-uploader-panel').find('input.oss-file-path[type="text"]')
    var options= {}
    OssHelper.modal({
        url: "{$url_prefix}/oss-modal",
        data: options,
//        size: 'modal-lg',
        title: '选择文件',
        method: 'get',
        shown: function(element){
            $(that).removeClass('disabled').removeAttr('disabled')
            
            var options= {};
            var input_element_value = upload_input_element.val()
            if(input_element_value){
                options.prefix = input_element_value.slice(0 ,input_element_value.lastIndexOf('/')+1 )                
            }
            OssHelper.oss_files("{$url_prefix}/oss-files" ,options ,function(response){})
                                    
            $(".modal-body").off('click' ,'.folder').on('click', '.folder',function(){
                var options = {prefix: $(this).data('prefix')}
                OssHelper.oss_files("{$url_prefix}/oss-files" ,options ,function(response){})
            })
            
            $(".modal-body").off('click' ,'.file').on('click', ".file",function(){
                upload_input_element.val($(this).data('file'))
//                $('div.dm-uploader-panel').find('input.oss-file-path[type="text"]').val($(this).data('file'))
                $(this).parents("div.modal").modal('hide')
            });           
            
            $("div>ol.breadcrumb").off('click' ,'a').on('click', "a",function(e){
                e.preventDefault();
                var options = {prefix:$(this).data('prefix')}
                OssHelper.oss_files("{$url_prefix}/oss-files" ,options ,function(response){})
            });                       
        }
    })
});



$('.dm-uploader-panel').dmUploader({
    url: "{$url_prefix}/upload2oss",
    fieldName: "dm-uploader-file",
    dataType: "json",
    extraData: {
        _token: "{$_token}"
    },
//    maxFileSize: 3000000, // 3 Megs max
    multiple: false,
//    allowedTypes: 'image/*',
//    extFilter: ['jpg','jpeg','png','gif'],
    onBeforeUpload: function(id){
        dmUploaderUI.ui_single_update_progress(this, 0, true);
        dmUploaderUI.ui_single_update_active(this, true);
        dmUploaderUI.ui_single_update_status(this, '正在上传...');
    },
    onUploadProgress: function(id, percent){
        dmUploaderUI.ui_single_update_progress(this, percent);
    },
    onUploadSuccess: function(id, response){
        dmUploaderUI.ui_single_update_active(this, false);    
        if(response.status=='failed'){
            dmUploaderUI.ui_single_update_status(this, response.message, 'danger');
            return false;
        }
        dmUploaderUI.ui_single_update_status(this, '上传成功', 'success');

        // You should probably do something with the response data, we just show it
        this.find('input.oss-file-path[type="text"]').val(response.data.file_path);
        
        console.log(response.data)
    },
    onUploadError: function(id, xhr, status, message){
        dmUploaderUI.ui_single_update_active(this, false);
        dmUploaderUI.ui_single_update_status(this, '错误提示: ' + message, 'danger');
    },
    onFileSizeError: function(file){
        dmUploaderUI.ui_single_update_status(this, '文件超出大小限制', 'danger');
    },
    onFileTypeError: function(file){
        dmUploaderUI.ui_single_update_status(this, '文件类型不是图像', 'danger');
    },
    onFileExtError: function(file){
        dmUploaderUI.ui_single_update_status(this, '不允许文件扩展名', 'danger');
    }
});

EOT;

        return parent::render();
    }

    /**
     * 待扩展功能，是否预览图片
     * @return $this
     */
    public function preview(){
        view()->share('preview' ,true);
        return $this;
    }
}
