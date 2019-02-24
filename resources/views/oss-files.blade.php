@foreach($files['prefixList'] as $folder)
    <tr class="folder" data-prefix="{{$folder}}" style="cursor:pointer;">
        <td>
            <span><i class="fa fa-folder-open"></i>{{$folder}}</span>
        </td>
    </tr>
@endforeach
@foreach($files['objectList'] as $file)
    <tr class="file" data-file="{{$file}}" style="cursor:pointer;">
        <td>
            <span><i class="fa fa-file-o"></i>{{$file}}</span>
        </td>
    </tr>
@endforeach