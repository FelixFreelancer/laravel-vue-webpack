<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Http\Requests\Admin\Setting\UpdateRequest;
use App\Library\Api;
use App\Transformers\Admin\SystemSettingTransformer;
use DB;

class SettingController extends Controller
{

    public function index()
    {
        $input = request()->all();
        $searchQuery = isset($input['query']) ? $input['query'] : [];

        $setting = Setting::select(DB::raw('SQL_CALC_FOUND_ROWS settings.id'),'settings.*');
        if(isset($searchQuery['name'])){
          $setting->where('name','like','%'.$searchQuery['name'].'%');
        }
        if(isset($searchQuery['value'])){
          $setting->where('value','like','%'.$searchQuery['value'].'%');
        }
        if(isset($input['page'])){
          $start = $input['limit'] * ($input['page'] - 1);
          $end = $input['limit'];
          $setting->skip($start)->take($end);
        }


        $logObj = $setting->get();
		$rst = [];
		foreach($logObj as $key => $value){
			$rst[] = SystemSettingTransformer::transform($value);
		}
		if(count($searchQuery) == 0){
			$loggedInUser = Api::getAuthenticatedUser();
			$name = $loggedInUser['user']['first_name']." ".$loggedInUser['user']['last_name'];
			addLog($loggedInUser['user']['id'],'System Setting Listing','<b>'.$name.'</b> has viewed <b>System Setting</b>.');
		}
        $data['data']['count']  = DB::select(DB::raw("select FOUND_ROWS() as count"))[0]->count;
        $data['data']['setting'] = $rst;
        return Api::ApiResponse($data);
    }

	public function update($id,UpdateRequest $request)
	{
		$statusCode = 200;
		$data['data'] = [];
		$setting = Setting::find($id);
		if($setting){
			$setting->value = $request->value;
		  $setting->save();
		  $loggedInUser = Api::getAuthenticatedUser();
		  $name = $loggedInUser['user']['first_name']." ".$loggedInUser['user']['last_name'];
		  addLog($loggedInUser['user']['id'],'System Setting Update','A system setting <b>['.$id.']</b> has been updated by <b>'.$name.'</b>');
		}else{
		  $statusCode = 422;
		  $data['data']['error'] = "System Setting Not Found";
		}
		return Api::ApiResponse($data);
	}


}
