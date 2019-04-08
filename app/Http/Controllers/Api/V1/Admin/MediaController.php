<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Library\Api;

class MediaController extends Controller
{
    public function store($type)
    {
        $data = [];
        if (request('image')) {
            $rst = base64ToJpeg(request('image'), $type);
            $media = Media::create($rst);
            $data['data'] = [
              'id' => $media->id,
              'media' => asset($media->media_path . $media->media_name),
            ];
        } else {
            $data['data']['error'] = "Media required";
            $statusCode = 422;
        }
        return $data;
    }

    public function delete($id)
    {
        $statusCode = 200;
        $data['data'] = [];
        $media = Media::find($id);
        if($media){
          $media->delete();
        }else{
          $statusCode = 422;
          $data['data']['error'] = "Media Not Found";
        }
        return Api::ApiResponse($data, $statusCode);
    }
}
