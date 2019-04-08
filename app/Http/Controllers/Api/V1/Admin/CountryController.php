<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Http\Requests\Admin\Country\ListRequest;
use App\Http\Requests\Admin\Country\StoreRequest;
use App\Http\Requests\Admin\Country\UpdateRequest;
use App\Library\Api;

/**
 * @resource Country
 *
 */

class CountryController extends Controller
{

    public function dropDown()
    {
        $country = Country::all();
        $cntry = [];
        $cntryObj= [];
        foreach($country as $key => $value){
          $cntry['key'] = $value['id'];
          $cntry['value'] = $value['name'];
          $cntryObj[] = $cntry;
        }
        $data['data'] = $cntryObj;
        return Api::ApiResponse($data);
    }

    public function index(ListRequest $request)
    {

        $loggedInUser = Api::getAuthenticatedUser();
        $name = $loggedInUser['user']['first_name']." ".$loggedInUser['user']['last_name'];
        addLog($loggedInUser['user']['id'],'Country Listing','<b>'.$name.'</b> has viewed <b>Countries Tab</b>');

        $data['data'] = Country::search(request()->all());
        return Api::ApiResponse($data);
    }

    public function store(StoreRequest $request)
    {
        $country = Country::create($request->toArray())->toArray();
		$data['data'] = $country;
		$loggedInUser = Api::getAuthenticatedUser();
        $name = $loggedInUser['user']['first_name']." ".$loggedInUser['user']['last_name'];
		addLog($loggedInUser['user']['id'],'Country Create','A new country <b>['.$country['country_code'].']</b> has been added by <b>'.$name.'</b>');

        return Api::ApiResponse($data);
    }


    public function show($id)
    {
        $data['data'] = Country::find($id);
        return Api::ApiResponse($data);
    }

    public function update($country,UpdateRequest $request)
    {
		$countryObj = Country::find($country);
		$countryObj->update($request->toArray());
		$loggedInUser = Api::getAuthenticatedUser();
		$name = $loggedInUser['user']['first_name']." ".$loggedInUser['user']['last_name'];
		addLog($loggedInUser['user']['id'],'Country Update','A country <b>['.$countryObj['country_code'].']</b> has been updated by <b>'.$name.'</b>');
        $data['data'] = Country::find($country)->toArray();
        return Api::ApiResponse($data);
    }


    public function delete($country)
    {
        $obj = Country::find($country);
        $statusCode = 200;
        $data['data'] = [];
        if($obj){
		  $loggedInUser = Api::getAuthenticatedUser();
		 $name = $loggedInUser['user']['first_name']." ".$loggedInUser['user']['last_name'];
		 addLog($loggedInUser['user']['id'],'Country Delete','A Country <b>['.$obj['country_code'].']</b> has been deleted by <b>'.$name.'</b>');
		 $obj->delete();
        }else{
          $statusCode = 422;
          $data['data']['error'] = "Country Not Found";
        }
        return Api::ApiResponse($data, $statusCode);
    }

}
