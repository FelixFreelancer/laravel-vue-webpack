<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;

class MediaController extends Controller
{
    public function store($type)
    {
        $data = [];
        if (request('image')) {
            $rst = base64ToJpeg(request('image'), $type);
            $media = Media::create($rst);
            $data['status'] = true;
            $data['id'] = $media->id;
            $data['media'] = asset($media->media_path . $media->media_name);
        } else {
            $data['status'] = false;
            $data['message'] = 'Media required.';
        }
        return $data;
    }

    public function destroy(Media $media)
    {
        $data=[];
        $data['status']= $media->delete();
        return $data;
    }
}
