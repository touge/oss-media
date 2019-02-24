var dmUploaderUI = {
    //重要，修改上传各UI状态
    ui_single_update_active :function(element, active)
    {
        element.find('div.progress').toggleClass('d-none', !active);
        element.find('input.oss-file-path[type="text"]').toggleClass('d-none', active);

        element.find('input.uploader-file[type="file"]').prop('disabled', active);

        element.find('div.btn').toggleClass('disabled', active);
        element.find('div.btn i').toggleClass('fa-circle-o-notch fa-spin', active);
        element.find('div.btn i').toggleClass('fa-cloud-upload', !active);
    },

    //上传进度条
    ui_single_update_progress: function(element, percent, active)
    {
        active = (typeof active === 'undefined' ? true : active);

        var bar = element.find('div.progress-bar');

        bar.width(percent + '%').attr('aria-valuenow', percent);
        bar.toggleClass('progress-bar-striped progress-bar-animated', active);

        if (percent === 0){
            bar.html('');
        } else {
            bar.html(percent + '%');
        }
    },
    //上传提示
    ui_single_update_status: function(element, message, color)
    {
        color = (typeof color === 'undefined' ? 'muted' : color);
        element.find('small.status').prop('class','status text-' + color).html(message);
    }
}
