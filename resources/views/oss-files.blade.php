@foreach($files['prefixList'] as $folder)
    <tr class="folder" data-prefix="{{$folder}}" style="cursor:pointer;">
        <td>
            <span><i class="fa fa-folder-open"></i> {{ mb_substr(str_replace($prefix ,'', $folder),0,-1) }}</span>
        </td>
    </tr>
@endforeach
@foreach($files['objectList'] as $file)
    @if($prefix!==$file)
    <tr class="file" data-file="{{$file}}" style="cursor:pointer;">
        <td>
            <span><i class="fa fa-file-o"></i> {{str_replace($prefix,'',$file)}}</span>
        </td>
    </tr>
    @endif
@endforeach