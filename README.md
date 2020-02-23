laravel-admin extension
======

> oss_image
```
$form->oss_image($column, $label = '')->...
```

> oss_video
```
$form->oss_vide($column, $label = '')->...
```

> oss_multiple_image 使用

在数据表中，设置字段为<code>text</code>类型，并在模型中如下设置：

```
//model文件中
/**
 * 设置images为json
 * @param $images
 */
public function setImagesAttribute($images){
    $this->attributes['images'] = json_encode($images,JSON_UNESCAPED_UNICODE);
}

/**
 * 取出时为array
 * @param $images
 * @return array|mixed
 */
public function getImagesAttribute($images)
{
    if($images){
        return json_decode($images, true);
    }
    return [];
}

//controller文件中
$form->oss_multiple_image('images')->...
```
