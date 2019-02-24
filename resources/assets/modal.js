var OssHelper = {};
OssHelper.modal = function (params) {
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

OssHelper.oss_files = function (url,params,callback) {

    var list_element = '.oss-list'

    $(list_element).empty().html("<tr><td>正在载入...</td></tr>")

    var options = $.extend({
        prefix: null
    },params)
    $.ajax({
        url: url,
        dataType: "html",
        data: {prefix: options.prefix},
        success: function (response) {
            OssHelper.breadcrumb(options.prefix)
            if(typeof callback == 'function'){
                callback(response)
            }
            $(list_element).empty().html(response);
        }
    });
};

OssHelper.breadcrumb = function(prefix){
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
