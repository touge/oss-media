var OssHelper = {};
OssHelper.uuid = function (len, radix) {
    var chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'.split('');
    var uuid = [], i;
    radix = radix || chars.length;

    if (len) {
        // Compact form
        for (i = 0; i < len; i++) uuid[i] = chars[0 | Math.random() * radix];
    } else {
        // rfc4122, version 4 form
        var r;

        // rfc4122 requires these characters
        uuid[8] = uuid[13] = uuid[18] = uuid[23] = '-';
        uuid[14] = '4';

        // Fill in random data. At i==19 set the high bits of clock sequence as
        // per rfc4122, sec. 4.1.5
        for (i = 0; i < 36; i++) {
            if (!uuid[i]) {
                r = 0 | Math.random() * 16;
                uuid[i] = chars[(i == 19) ? (r & 0x3) | 0x8 : r];
            }
        }
    }

    return uuid.join('');
}
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
    $.ajax({
        url: url,
        dataType: "html",
        data: params || {},
        success: function (response) {
            callback(response)
            // $(".oss-list").html(response)
        }
    });
}