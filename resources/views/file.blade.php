{{--{{$preview}}--}}
<div class="{{$viewClass['form-group']}} dm-uploader-panel {!! !$errors->has($errorKey) ? '' : 'has-error' !!}">
    <label for="{{$name}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>
    <div class="{{$viewClass['field']}}">
        @include('admin::form.error')


        <div class="from-group input-group mb-2">
            <span class="input-group-addon"><i class="fa fa-file fa-fw"></i></span>
            <input type="text"
                   class="form-control oss-file-path"
                   data-id="media-{{$name}}"
                   name="{{$name}}"
                   value="{{old($column, $value)}}"
                   placeholder="Local upload or server selection"
                   readonly="readonly"
                    {{--placeholder="{{$placeholder}}"--}}
            >

            <div class="progress mb-2 d-none" style="height:34px;">
                <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                     role="progressbar"
                     style="width: 0%;"
                     aria-valuenow="0" aria-valuemin="0" aria-valuemax="0">
                    0%
                </div>
            </div>
        </div>

        <div class="form-group dm-uploader" style="margin: 0px;">
            @if($config["is_upload"])
                <div role="button" class="btn btn-sm btn-info mr-2">
                    <i class="fa fa-cloud-upload"></i> 上传文件
                    <input type="file" class="uploader-file" title="Click to add file">
                </div>
            @endif

            <div class="pull-left" style="padding-right:15px;">
                <a class="btn btn-twitter btn-sm btn-selector-alioss">
                    <i class="fa fa-cloud"></i> 从服务器选择
                </a>
            </div>
        </div>
        <small class="status text-muted">Select a file or drag it over this area..</small>

        @include('admin::form.help-block')
    </div>
</div>