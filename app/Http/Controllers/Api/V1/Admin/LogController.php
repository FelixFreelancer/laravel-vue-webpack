<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Log;
use App\Models\User;
use App\Library\Api;
use DB;

class LogController extends Controller
{

    public function index()
    {
        $input = request()->all();
        $searchQuery = isset($input['query']) ? $input['query'] : [];

        $logs = Log::leftJoin('users','users.id','=','logs.user_id')
                    ->select(DB::raw('SQL_CALC_FOUND_ROWS users.id'),'logs.*',DB::raw('concat(first_name," ",last_name) as name'));
        if(isset($searchQuery['name'])){
          $logs->where(function($query) use($searchQuery){
            return $query->where('first_name','like','%'.$searchQuery['name'].'%')
                ->orWhere('last_name','like','%'.$searchQuery['name'].'%')
                ->orWhere(DB::raw('concat(first_name," ",last_name)'),'like','%'.$searchQuery['name'].'%');
          });
        }

        if(isset($searchQuery['id'])){
          $logs->where('logs.id',$searchQuery['id']);
        }

        if(isset($searchQuery['notes'])){
          $logs->where('notes','like','%'.$searchQuery['notes'].'%');
        }

        if(isset($input['page'])){
          $start = $input['limit'] * ($input['page'] - 1);
          $end = $input['limit'];
          $logs->skip($start)->take($end);
        }

        $orderBy = isset($input['orderBy']) ? $input['orderBy'] : 'logs.id';
        $order = "desc";
        if(isset($input['ascending']) && $input['ascending'] == "1"){
          $order = "asc";
        }
        $logs->orderBy($orderBy,$order);
        if(count($searchQuery) == 0){
          $loggedInUser = Api::getAuthenticatedUser();
          $name = $loggedInUser['user']['first_name']." ".$loggedInUser['user']['last_name'];
          addLog($loggedInUser['user']['id'],'Log Listing','<b>'.$name.'</b> has viewed <b>Log</b>.');
        }
        $logObj = $logs->get();
        foreach($logObj as  $key => $value ){
          $logObj[$key]['date'] = strtotime($value['created_at']);
        }
        $data['data']['count']  = DB::select(DB::raw("select FOUND_ROWS() as count"))[0]->count;
        $data['data']['logs'] = $logObj;
        return Api::ApiResponse($data);
    }

	public function destroy($id)
	{
		$statusCode = 200;
		$data['data'] = [];
		$log = Log::find($id);
		if($log){
		  $log->delete();
		  $loggedInUser = Api::getAuthenticatedUser();
		  $name = $loggedInUser['user']['first_name']." ".$loggedInUser['user']['last_name'];
		  addLog($loggedInUser['user']['id'],'Log Delete','A log <b>['.$id.']</b> has been deleted by <b>'.$name.'</b>');
		}else{
		  $statusCode = 422;
		  $data['data']['error'] = "Inquiry Not Found";
		}
		return Api::ApiResponse($data);
	}

	public function deleteAll()
	{
		$statusCode = 200;
		$data['data'] = [];
		$log = Log::whereNotNull('id')->delete();
		$loggedInUser = Api::getAuthenticatedUser();
        $name = $loggedInUser['user']['first_name']." ".$loggedInUser['user']['last_name'];
          addLog($loggedInUser['user']['id'],'Logs Delete','All logs has been deleted by <b>'.$name.'</b>');

		return Api::ApiResponse($data);
	}


}
