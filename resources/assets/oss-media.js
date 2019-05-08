var OssMedia = {};
OssMedia.modal = function (params) {
    var default_options = {
        url: null,
        keyboard: false,
        data: {},
        dataType: 'html',
        modal_id: null,
        title: 'Modal Window',
        size: '',
        method: 'get',
        shown: undefined,
        hidden: undefined
    };
    var options = $.extend(default_options, params);
    if (options.modal_id == null) {
        options.modal_id = "__boost_win_id_" + $(".modal").length;
    }

    if ($(".modal-backdrop").length > 0) {
        $(".modal-backdrop").remove()
    }

    $.ajax({
        url: options.url,
        dataType: options.dataType,
        data: options.data,
        method: options.method,
        success: function (response) {
            var modal_tmpl = '    <div class="modal-dialog ' + options.size + '">\n' +
                '        <div class="modal-content">\n' +
                '            <div class="modal-header">\n' +
                '                <button type="button" class="close" data-dismiss="modal" aria-label="Close">\n' +
                '                    <span aria-hidden="true">&times;</span></button>\n' +
                '                <h4 class="modal-title">' + options.title + '</h4>\n' +
                '            </div>\n' + response +
                '            </div>\n' +
                '        </div>\n' +
                '    </div>';
            $(document.body).append('<div class="modal fade" id="' + options.modal_id + '"></div>')
            $("#" + options.modal_id).append(modal_tmpl).modal({
                keyboard: false,
                // backdrop: 'static',
            }).modal('show').on("shown.bs.modal", function (e) {
                e.preventDefault()
                typeof options.shown == 'function' && options.shown(this)
            }).on("hidden.bs.modal", function () {
                typeof options.hidden == 'function' && options.hidden(this)
                $(this).remove()
            })
        }
    });
};

/**
 * 文件列表
 * @param url
 * @param params
 * @param callback
 */
OssMedia.oss_files = function (params) {
    // console.log(params);

    var list_element = '.oss-list'
    $(list_element).empty().html("<tr><td>正在载入...</td></tr>")

    var options = $.extend({
        url: null,
        prefix: null,
        success: undefined,
    },params)
    $.ajax({
        url: options.url,
        dataType: "html",
        data: {
            prefix: options.prefix
        },
        success: function (response) {
            OssMedia.change_breadcrumb(options.prefix)

            if(typeof options.success == 'function'){
                options.success(response)
            }
            $(list_element).empty().html(response);
        }
    });
};

/**
 * 改变导航列表
 * @param prefix
 * @returns {OssMedia}
 */
OssMedia.change_breadcrumb = function(prefix){
    var breadcrumb = '<li><a href="#" data-prefix=""><i class="fa fa-home"></i></a></li>';
    if(prefix!=null){
        var folders = prefix.split('/')
        var folder_length = folders.length-1

        var _prefix = '';
        for(var i=0;i<folder_length;i++){
            if(folder_length-1 == i) {
                breadcrumb+= '<li> '+folders[i]+'</li>';
            }else{
                _prefix+= folders[i] + '/'
                breadcrumb+= '<li><a href="#" data-prefix="'+_prefix+'"> '+folders[i]+'</a></li>';
            }
        }
    }
    $('div.modal-body > div > ol.breadcrumb').empty().html(breadcrumb);
    return this
};


/**
 * 文件是否为图片文件
 * @param file
 * @returns boolean
 */
OssMedia.is_image = function(file){
    return ['jpg','jpeg','gif','png','bmp','webp','psd','svg','tiff']
        .indexOf(file.substr(file.lastIndexOf(".")+1).toLowerCase()) !== -1
};

/**
 * 导航列表
 * @param url
 */
OssMedia.breadcrumb =function(url){
    $("div>ol.breadcrumb").off('click' ,'a').on('click', "a",function(e){
        e.preventDefault();
        var options = {
            url: url,
            prefix: $(this).data('prefix')
        }
        $.cookie('oss-browser-prefix', options.prefix);
        OssMedia.oss_files(options)
    });
};

/**
 * 切换目录
 */
OssMedia.selector_folder = function(url,callback){
    $('.modal-body').off('click','.folder').on('click','.folder',function(){
        var options = {
            url: url,
            prefix: $(this).data('prefix'),
            success: callback
        }
        $.cookie('oss-browser-prefix', options.prefix);
        OssMedia.oss_files(options)
    })
};

/**
 * 选择文件时操作
 */
OssMedia.selector_file = function(callback){
    $('.modal-body').off('click','.file').on('click','.file',function(){
        callback($(this).data('file'))
    });
}


/**
 * 图片预览
 * @param params
 */
OssMedia.preview = function(params){
    var options = $.extend({
        url: null,
        file: null,
        success: undefined
    },params);

    if(OssMedia.is_image(options.file)){
        $.ajax({
            url: options.url,//url_prefix + '/oss-file-url',
            data: {
                object:options.file
            },
            success:function(response){
                if(response.status=='failed'){
                    alert('获得oss地址失败')
                    return;
                }

                if(typeof options.success=='function'){
                    return options.success(response.url)
                }
            }
        })
    }
}

//选择阿里云资源
OssMedia.selector_alioss = function(element ,url_prefix){
    var column_element = $(element).parent().parent().parent().find('input.oss-file-path')
    $(element).addClass('disabled').attr('disabled','disabled')

    OssMedia.modal({
        url: url_prefix + '/oss-modal',
        data: {},
        title: '选择文件',
        method: 'get',
        shown: function(modal_element){
            $(element).removeClass('disabled').removeAttr('disabled')

            /**
             * 默认打开的列表
             * @type {string}
             */
            var file_url = url_prefix + '/oss-files'
            var options= { url: file_url };

            var input_element_value = column_element.val()
            var cookie_prefix= $.cookie('oss-browser-prefix', options.prefix)

            if(input_element_value){
                options.prefix= input_element_value.slice(0 ,input_element_value.lastIndexOf('/')+1 )
            } else if( cookie_prefix ){
                options.prefix= cookie_prefix
            }
            OssMedia.oss_files(options)


            /**
             * 选择目录时，切换的
             */
            OssMedia.selector_folder(file_url)

            /**
             * 选择文件
             */
            OssMedia.selector_file(function(file){
                column_element.val(file)
                var preview_file= encodeURIComponent(file)
                OssMedia.preview({
                    url: url_prefix + '/oss-file-url',
                    file: preview_file,
                    success: function(image_url){
                        var preview_element = $(element).parent().parent().parent();
                        preview_element.find('div.image-preview').empty().html('<img src="'+image_url+'" style="width: 100px">')
                    }
                })

                $(modal_element).modal('hide')
            });

            OssMedia.breadcrumb(url_prefix + "/oss-files")
        }
    })
};

//本地上传文件
OssMedia.dmUploader = function(url_prefix){
    $('div.dm-uploader-panel').dmUploader({
        url: url_prefix + "/upload2oss",
        fieldName: "dm-uploader-file",
        dataType: "json",
        extraData: {
            _token: LA.token
        },
        maxFileSize: 3000000, // 3 Megs max
        multiple: false,
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

            if(response.data.preview){
                this.find('div.image-preview').empty().html('<img src="'+response.data.preview+'" style="width: 100px">')
            }
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
};