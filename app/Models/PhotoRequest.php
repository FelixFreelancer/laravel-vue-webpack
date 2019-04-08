<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class PhotoRequest extends Model
{

    protected $fillable = [
        'user_id',
        'shipment_item_id',
        'completed_at',
    ];

	public static function search($input)
    {

      $request = PhotoRequest::select('photo_requests.user_id','shipments.parcel_number','photo_requests.created_at as requested_at','shipment_items.*',DB::raw('concat(users.first_name," ",users.last_name) as user_name'))
          ->leftJoin('users', 'users.id', '=', 'photo_requests.user_id')
          ->leftJoin('shipment_items', 'shipment_items.id', '=', 'photo_requests.shipment_item_id')
          ->leftJoin('shipments', 'shipments.id', '=', 'shipment_items.shipment_id');

      if(isset($input['page'])){
        $start = $input['limit'] * ($input['page'] - 1);
        $end = $input['limit'];
        $request->skip($start)->take($end);
      }

      $orderBy = isset($input['orderBy']) ? $input['orderBy'] : 'id';
      $order = "desc";
      if(isset($input['ascending']) && $input['ascending'] == "1"){
        $order = "asc";
      }
      $request->orderBy($orderBy,$order);

      $data['photo_requests'] = $request->whereNull('completed_at')->get();
      return $data;
    }

    public static function getCounter($input)
    {
      $photoRequest = PhotoRequest::select('*');
      if (isset($input['start_date']) && isset($input['end_date']) ) {
          $photoRequest->whereDate('created_at', '>=', date('Y-m-d', strtotime($input['start_date'])))
              ->whereDate('created_at', '<=', date('Y-m-d', strtotime($input['end_date'])));
      }

      if (isset($input['start_date'])&& !isset($input['end_date'])) {
          $photoRequest->whereDate('created_at', date('Y-m-d', strtotime($input['start_date'])));
      }
      return $photoRequest->count();
    }
}
