<style>
    .upload-preview{
        margin-bottom:10px;
        position: relative;
    }
    .upload-preview video{
        width: 100%;
        max-width: 350px;
        border:1px dashed #7c7c7c
    }
    .upload-preview video:hover{
        max-width:350px;
        transform: scale(1,1);
        box-shadow: 0 0 5px #0000ff;
    }
</style>
{{--{{$preview}}--}}
<div class="{{$viewClass['form-group']}} dm-uploader-panel {!! !$errors->has($errorKey) ? '' : 'has-error' !!}">
    <label for="{{$name}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>
    <div class="{{$viewClass['field']}}">
        @include('admin::form.error')

        <div class="upload-preview">
            @if( old($column, $value) && tougeOssMediaIsVideo($value) )
                <video src="{{tougeOssMediaRemoteFileUrl(urlencode($value))}}" controls="controls" class="media"></video>
            @endif
        </div>

        <div class="from-group input-group mb-2">
            <span class="input-group-addon"><i class="fa fa-video-camera fa-fw"></i></span>
            <input type="text"
                   class="form-control oss-file-path"
                   data-id="media-{{$name}}"
                   name="{{$name}}"
                   value="{{old($column, $value)}}"
                   placeholder="Local upload or server selection"
                   readonly="readonly"
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
            <div class="pull-left" style="padding-right:15px;">
                <a class="btn btn-success btn-sm btn-selector-alioss" data-type="media" data-field-name="{{$name}}">
                    <i class="fa fa-cloud"></i> 从服务器选择视频
                </a>
            </div>


        </div>
        <small class="status text-muted">Select a file or drag it over this area..</small>

        @include('admin::form.help-block')
    </div>
</div>
