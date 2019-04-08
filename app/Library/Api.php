<?php
namespace App\Library;

class Api
{
    public static function ApiResponse($data, $statusCode = '200', $specialMsg = array())
    {
      $returnArray = [];
        if (isset($data['data'])) {
            if (isset($data['header'])) {
                foreach ($data['header'] as $key => $value) {
                    $headers[$key] = $value;
                }
                unset($data['header']);
            }
            if($statusCode == 200){
              $returnArray = [
                'status' => $statusCode,
                'msg' => "success",
                'info' => (! empty($specialMsg)) ? $specialMsg : NULL,
                'data' => $data['data'],
              ];
              // if(count($data['data']) > 0){
              //   $returnArray += [
              //     'data' => $data['data'],
              //   ];
              // }
            }else{
              $returnArray = [
                'status' => $statusCode,
                'msg' => "error",
                'data' => [
                  'error' => $data['data']['error'],
                ],
              ];
            }
        }

        $headers['Content-Type'] =  'application/json';

        return \Response::make(json_encode(
                            $returnArray, JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK
                        ), $returnArray['status'], $headers);
    }

    public static function getAuthenticatedUser()
    {
        $data = [];

        try {
            if (! $user = \JWTAuth::parseToken()->authenticate()) {
                $data['error'] = 'User Not Found';
            }
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            $data['error'] = 'Token Expired';
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            $data['error'] = 'Token Invalid';
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            $data['error'] = 'Token Absent';
        }

        $data['status'] = true;
        if (isset($data['error'])) {
            $data['status'] = false;
        } else {
            $data['user'] = $user;
        }
        return $data;
    }
}
