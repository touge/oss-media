{{--{{$preview}}--}}
<div class="{{$viewClass['form-group']}} dm-uploader-panel {!! !$errors->has($errorKey) ? '' : 'has-error' !!}">
    <label for="{{$name}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>
    <div class="{{$viewClass['field']}}">
        @include('admin::form.error')

        {{--<div data-id="preview-{{$name}}" style="margin-bottom:10px;">--}}
            {{--@if(old($column, $value) && $preview)--}}
                {{--<img src="{{$baseURL}}/{{$value}}" alt="Attachment" style="width:100px;"/>--}}
            {{--@endif--}}
        {{--</div>--}}

        <div class="from-group mb-2">
            {{--<input type="text" class="form-control uploader-text" value="{{old($column, $value)}}" name="attachment_path" placeholder="本地上传或者服务器中选择" readonly="readonly" />--}}
            <input type="text"
                   class="form-control oss-file-path"
                   data-id="media-{{$name}}"
                   name="{{$name}}"
                   value="{{old($column, $value)}}"
                   placeholder="本地上传或者服务器中选择"
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
            <div role="button" class="btn btn-primary mr-2">
                <i class="fa fa-cloud-upload"></i> 本地上传
                <input type="file" class="uploader-file" title="Click to add Files" {!! $attributes !!}>
            </div>
            <small class="status text-muted">Select a file or drag it over this area..</small>

            <div class="pull-left" style="padding-right:15px;">
                <a class="btn btn-success btn-selector-alioss">
                    <i class="fa fa-cloud"></i> 从服务器选择
                </a>
            </div>
        </div>
    </div>
</div>