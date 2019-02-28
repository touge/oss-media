<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin | Create</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <link href="{{admin_asset("vendor/laravel-admin/AdminLTE/dist/css/skins/".config('admin.skin').".min.css")}}" rel="stylesheet">
    <link href="{{admin_asset("vendor/laravel-admin/AdminLTE/bootstrap/css/bootstrap.min.css")}}" rel="stylesheet">
    <link href="{{admin_asset("vendor/laravel-admin/font-awesome/css/font-awesome.min.css")}}" rel="stylesheet">
    <script src="{{admin_asset('vendor/laravel-admin/AdminLTE/plugins/jQuery/jQuery-2.1.4.min.js')}}"></script>

</head>

<div class="modal-body">
    <div>
        <ol class="breadcrumb" style="margin:-15px -5px 10px -5px;">
            <li><a href="#" data-prefix=""><i class="fa fa-home"></i></a></li>
        </ol>
    </div>

    <div>
        <table class="table table-bordered table-hover">
            <tbody class="oss-list"></tbody>
        </table>
    </div>
</div>
<script type="text/javascript">
    function getUrlParam(paramName) {
        var reParam = new RegExp('(?:[\?&]|&amp;)' + paramName + '=([^&]+)', 'i') ;
        var match = window.location.search.match(reParam) ;
        return (match && match.length > 1) ? match[1] : '' ;
    }

    $(function(){
        var url_prefix = "{{admin_url("oss-media")}}/"

        OssMedia.oss_files({
            url: url_prefix + 'oss-files',//"{{admin_url('oss-media/oss-files')}}"
        })

        /**
         * 选择目录时，切换的
         */
        OssMedia.selector_folder(url_prefix + 'oss-files')

        /**
         * 导航
         */
        OssMedia.breadcrumb(url_prefix + "oss-files")

        /**
         * 选择文件
         */
        OssMedia.selector_file(function(file){
            var funcNum = getUrlParam('CKEditorFuncNum');
            if(!OssMedia.is_image(file)){
                alert('请选择图片文件')
                return;
            }
            OssMedia.preview({
                url: url_prefix + 'oss-file-url',
                file: file ,
                success: function(image_url){
                    window.opener.CKEDITOR.tools.callFunction(funcNum,image_url)
                    window.close()
                }
            })
        });
    })
</script>
<script src="{{admin_asset("vendor/laravel-admin/AdminLTE/bootstrap/js/bootstrap.min.js")}}"></script>
<script src="{{admin_asset("vendor/touge/oss-media/oss-media.js")}}"></script>
</body>
</html>