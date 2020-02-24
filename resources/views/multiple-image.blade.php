<style>
    .upload-preview{
        margin-bottom:10px;
        position: relative;
    }
    .image_box{
        position:relative;
        width:150px;
        margin-right:5px;
        margin-top: 10px;
        border:1px dashed #7c7c7c;
        display:inline-block;
        vertical-align: top;
    }
    .image_box img{
        width: 100%;
        max-width: 150px;
    }
    .image_box:hover{
        transform: scale(1,1);
        box-shadow: 0 0 5px #0000ff;
    }
    .image_box .file-footer-buttons{
        top: 5px;
        right: 5px;
        position: absolute;
    }
</style>



<div class="{{$viewClass['form-group']}} dm-uploader-panel {!! !$errors->has($errorKey) ? '' : 'has-error' !!}">
    <label for="{{$name}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>
    <div class="{{$viewClass['field']}} multiple-image">
        @include('admin::form.error')


        {{--a:{{dd($value)}}--}}

        <div class="upload-preview">
            @if(old($column, $value))
                @foreach($value as $key=>$item)
                    <div class="image_box multiple-image-{{$key}}" data-key="{{$key}}">
                        <div>
                            <img src="{{tougeOssMediaRemoteFileUrl($item)}}" alt="Attachment" style=""/>
                        </div>
                        <div class="file-footer-buttons">
                            <a class="btn btn-sm btn-danger btn-destroy-multiple" data-key="{{$key}}">
                                <i class="fa fa-trash"></i>
                            </a>
                        </div>
                    </div>

                @endforeach
            @endif


        </div>

        <div class="from-group input-group mb-2 multiple">
            <div class="multiple-input">
                @foreach($value as $key=>$item)
                    <input type="hidden"
                       class="form-control oss-file-path multiple-image-{{$key}}"
                       name="{{$name}}[]"
                       value="{{$item}}"
                       data-key="{{$key}}"
                       readonly="readonly"
                      style="width: 450px;"
                       placeholder="{{$placeholder}}"
                    >
                @endforeach
            </div>

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

            {{--暂关闭上传，目前没有需求--}}
            {{--@if($config["is_upload"])--}}
            {{--<div role="button" class="btn btn-sm btn-primary mr-2">--}}
                {{--<i class="fa fa-cloud-upload"></i> 上传图片--}}
                {{--<input type="file" class="uploader-file" title="Click to add image file" accept=".jpg,.jpeg,.png,.gif">--}}
            {{--</div>--}}
            {{--@endif--}}

            <div class="pull-left" style="padding-right:15px;">
                <a class="btn btn-success btn-sm btn-selector-multiple-image-alioss" data-type="multiple-image" data-field-name="{{$name}}">
                    <i class="fa fa-cloud"></i> 从服务器选择
                </a>
            </div>
        </div>
        <small class="status text-muted">Select a file or drag it over this area..</small>

        @include('admin::form.help-block')
    </div>
</div>
