<?php

function fileUpload($media, $media_path)
{
    $data = [];
    if (!file_exists(base_path() . '/uploads' )) {
        mkdir(base_path() . '/uploads');
    }
    if (!file_exists(base_path() . '/uploads/'.$media_path )) {
        mkdir(base_path() . '/uploads/'.$media_path );
    }
    $path = 'uploads/' . $media_path . '/' . date('d-m-Y') . '/';
    $filename = 'image_' . time() .base64_encode(rand(1, 100)) . '.jpg';
  
    $outputFile = base_path() . '/' . $path . '/' . $filename;
    if (!file_exists(base_path() . '/' . $path)) {
        mkdir(base_path() . '/' . $path);
    }
    $media->move(base_path($path), $filename);
    $rst['status'] = 1;
    $rst['media_name'] = $filename;
    $rst['media_path'] = $path;
    $rst['media_type'] = 'image/jpeg';
    $rst['type'] = ucwords(camel_case($media_path));
    return $rst;
}

function base64ToJpeg($base64String, $media_path)
{
    $date = new \DateTime();
    $basePath = 'uploads/' . $media_path;
    $path = $basePath . '/' . date('d-m-Y') . '/';
    $filename = 'image_' . $date->getTimestamp() . '.jpg';
    $outputFile = base_path() . '/' . $path . '/' . $filename;
    if (!file_exists(base_path() . '/' . $basePath)) {
        mkdir(base_path() . '/' . $basePath);
    }
    if (!file_exists(base_path() . '/' . $path)) {
        mkdir(base_path() . '/' . $path);
    }

    $img = $base64String;
    $img = str_replace('data:image/png;base64,', '', $img);
    $img = str_replace(' ', '+', $img);
    $data = base64_decode($img);
    $image = imagecreatefromstring($data);
    @imageinterlace($image, true);
    imagejpeg($image, base_path() . '/' . $path . '/' . $filename, 100);
    @imagedestroy($image);
    $rst['status'] = 1;
    $rst['media_name'] = $filename;
    $rst['media_path'] = $path;
    $rst['media_type'] = 'image/jpeg';
    $rst['type'] = ucwords(camel_case($media_path));
    return $rst;
}
