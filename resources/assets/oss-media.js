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

OssMedia.is_media = function(file){
    return ['mp4']
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
OssMedia.selector_file = function(element, callback){
    $('.modal-body').off('click','.file').on('click','.file',function(){
        var selected_file= $(this).data('file')
        if(!OssMedia.check_allow_file_type(element ,selected_file)){
            return alert('选择的文件类型错误')
        }else{
            callback(selected_file)
        }
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
        element:null,
        success: undefined
    },params);

    if(OssMedia.is_image(options.file) || OssMedia.is_media(options.file)){
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
    }else{
        params.element.find('div.image-preview').empty()
    }
}

/**
 * 判断文件是否为允许选择的类型
 * @param upload_file_type
 * @param file
 * @returns {*}
 */
OssMedia.check_allow_file_type= function(element ,file){
    var action_type= $(element).data('type')

    if(action_type=='image' || action_type=='multiple-image'){
        return OssMedia.is_image(file)
    }
    if(action_type=='media'){
        return OssMedia.is_media(file)
    }
    return true
}

/**
 * 当前插件的文件存储input表单
 * @param element
 * @returns {jQuery}
 */
OssMedia.ossFilePathElement= function(element){
    return $(element).parent().parent().parent().find('input.oss-file-path')
}


/**
 * 设置对象disabled状态
 *
 * @param element
 * @param status=disabled,enable
 */
OssMedia.changeButtonDisableStatus= function(element, status = 'disabled'){
    if(status=='disabled'){
        $(element).addClass('disabled').attr('disabled','disabled')
    }else{
        $(element).removeClass('disabled').removeAttr('disabled')
    }
}

/**
 * 当前操作的类型
 *
 * @param element
 * @returns {string} single:单文件, multiple: 多文件
 */
OssMedia.dealActionType= function(element){
    var allow_file_type= $(element).data('type')
    if(allow_file_type=='image' || allow_file_type=='media'){
        return 'single'
    }else{
        return 'multiple'
    }
}

/**
 * 预览模板
 * @type {{image: (function(*): string), imageMultiple: (function(*): string), video: (function(*): string)}}
 */
OssMedia.template= {
    video: function(file){
        return '<video src="' + file + '" class="media" controls="controls"/>'
    },
    image: function(file){
        return '<img src="' + file + '"/>'
    },
    imageMultiple: function (element, file) {
        var form_multiples= OssMedia.currentElement(element).find('div.upload-preview')


        var last_child_element= form_multiples.children('div:last-child')
        var next_key= parseInt(last_child_element.data('key')) + 1


        return '<div class="image_box multiple-image-'+ next_key +'" data-key="'+ next_key +'">' +
            '    <div>' +
            '        <img src="' + file + '" alt="Attachment" style=""/>' +
            '    </div>' +
            '    <div class="file-footer-buttons">' +
            '        <a class="btn btn-sm btn-danger" target="_blank">' +
            '            <i class="fa fa-trash"></i>' +
            '        </a>' +
            '    </div>' +
            '</div>'
    }
}

/**
 * 添加多文件上传表单
 * @param element
 * @param file
 */
OssMedia.putMultipleColumn= function(element, file){
    var form_multiples= this.currentElement(element).find('div.multiple-input')


    //最后一个input对象
    var last_child_element= form_multiples.children('input:last-child')
    var next_key= parseInt(last_child_element.data('key')) + 1

    //当前字段名称
    var currentFieldName= this.currentFieldName(element)

    var tpl= '<input type="text"' +
        '   class="form-control oss-file-path multiple-image-' + next_key + '"' +
        '   data-key="'+ next_key +'"' +
        '   name="'+ currentFieldName +'[]"' +
        '   value="' + file + '"' +
        '   data-key="{{$key}}"' +
        '   readonly="readonly"' +
        '>'
    form_multiples.append(tpl)
}

/**
 * 当前操作的表单对象
 * @param element
 * @returns {jQuery}
 */
OssMedia.currentElement= function(element){
    return $(element).parent().parent().parent();
}



OssMedia.destroyMultipleImage= function(element){
    var key= $(element).data('key')
    var destroyClass= '.multiple-image-' + key

    var current_element = $(element).parent().parent().parent().parent()
    current_element.find(destroyClass).remove()
}

/**
 * 表单名称
 * @param element
 * @returns {jQuery}
 */
OssMedia.currentFieldName= function(element){
    return $(element).data('field-name')
}

//选择阿里云资源
OssMedia.selector_alioss = function(element ,url_prefix){
    var column_element= this.ossFilePathElement(element)

    /**
     * 置当前按钮状态为禁用状态
     */
    this.changeButtonDisableStatus(element, 'disabled')

    OssMedia.modal({
        url: url_prefix + '/oss-modal',
        data: {},
        title: '选择文件',
        method: 'get',
        shown: function(modal_element){
            OssMedia.changeButtonDisableStatus(element, 'enabled');

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

            //文件列表
            OssMedia.oss_files(options)

            /**
             * 选择目录时，切换的
             */
            OssMedia.selector_folder(file_url)


            // 文件预览对象
            var current_element = OssMedia.currentElement(element)// $(element).parent().parent().parent();

            /**
             * 选择文件
             */
            OssMedia.selector_file(element, function(file){
                /**
                 * 发送到表单中
                 */
                if(OssMedia.dealActionType(element)=='single'){
                    column_element.val(file)
                }else{
                    OssMedia.putMultipleColumn(element, file)
                }

                //判定当前执行的类型
                // console.log(OssMedia.dealActionType(element))

                var preview_file= encodeURIComponent(file)
                OssMedia.preview({
                    url: url_prefix + '/oss-file-url',
                    file: preview_file,
                    element: current_element,
                    success: function(file_url){

                        var upload_preview_element= current_element.find('div.upload-preview')
                            ,append_html

                        if(OssMedia.is_media(file_url)){
                            append_html= OssMedia.template.video(file_url)//'<video src="' + file_url + '" class="media" controls="controls"/>';
                            upload_preview_element.empty().html(append_html)
                        }
                        if(OssMedia.is_image(file_url)){
                            var action_type= OssMedia.dealActionType(element)
                            if(action_type=='single'){
                                append_html= OssMedia.template.image(file_url)
                                upload_preview_element.empty().html(append_html)
                            }
                            if(action_type=='multiple'){
                                append_html= OssMedia.template.imageMultiple(element,  file_url)
                                upload_preview_element.append(append_html)
                            }
                        }
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
                this.find('div.upload-preview').empty().html('<img src="'+response.data.preview+'" style="width: 100px">')
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