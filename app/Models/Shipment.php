<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Shipment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'custom_shipping_price',
        'parcel_number',
        'parcel_desc',
        'dimension_length',
        'dimension_width',
        'dimension_height',
        'parcel_weight',
        'postal_company',
        'shipping_in_company',
        'shipping_in_service',
        'shipping_in_amount',
        'shipping_in_tracking',
        'shipping_out_company',
        'shipping_out_service',
        'shipping_out_amount',
        'shipping_out_tracking',
        'shipping_out_logo',
        'shipping_tracking_link',
        'delivered_at',
        'shipping_out_at',
        'gpf_charge',
        'insurance_charge',
        'total',
        'received_on',
        'status',
        'status_by',
    ];

    public static function validationRules()
    {
        return [
            'user_id'             => 'required|exists:users,id',
            'name'                => 'required',
            'parcel_number'       => 'required',
            'parcel_desc'         => 'max:1000',
            'dimension_length'    => 'required|numeric',
            'dimension_width'     => 'required|numeric',
            'dimension_height'    => 'required|numeric',
            'parcel_weight'       => 'required|numeric',
            'postal_company'      => 'required',
            'received_on'         => 'required|date_format:d-m-Y H:i:s',
            'shipping_in_company' => 'required',
            'shipping_in_service' => 'required',
            'shipping_in_amount'  => 'required|numeric',
        ];
    }

    protected $hidden = [
        'updated_at',
        'deleted_at',
    ];

    public static function shippingOptionvalidationRules()
    {
        return [
            'company_name'          => 'required',
            'company_logo'          => 'required',
            'shipment_price'        => 'required|numeric',
            'accepted_product_info' => 'required|in:1'
        ];
    }

    public static function globalSearch($input)
    {
      $shipment = Shipment::leftJoin('users', 'users.id', '=', 'shipments.user_id')
            ->select('shipments.*',DB::raw('concat(users.first_name," ",users.last_name) as username'));
      if(isset($input['search'])){
        $shipment->where('name','like','%'.$input['search'].'%')
          ->orWhere('parcel_number','like','%'.$input['search'].'%');
      }
      if (isset($input['start_date']) && isset($input['end_date'])) {
          $shipment->where(DB::raw('date(created_at)'), '>=', date('Y-m-d',$input['start_date']))
              ->where(DB::raw('date(created_at)'), '<=', date('Y-m-d', $input['end_date']));
      }
      $start = 0;
      $end = config('site.pagination');
      $shipment->skip($start)->take($end);
      $shipment->orderBy('id','desc');
      return $shipment->get();
    }

    public static function getOldShipments($input)
    {
        $start = isset($input['page']) ? $input['limit'] * ($input['page'] - 1) : 0;
        $end = isset($input['page']) ? $input['limit'] : config('site.pagination');
        $shipment = Shipment::leftJoin('users', 'users.id', '=', 'shipments.user_id')
              ->select('shipments.*',DB::raw('concat(users.first_name," ",users.last_name) as username'))
              ->where('status','!=',5)
              ->where('status','!=',6)
              ->skip($start)
              ->take($end)
              ->get();
        return $shipment;
    }

    public static function search($input)
    {
      $searchQuery = isset($input['query']) ? $input['query'] : [];

      $shipment = Shipment::select(DB::raw('SQL_CALC_FOUND_ROWS shipments.id'),'shipments.*', DB::raw('concat(users.first_name," ",users.last_name) as user_name'))
          ->leftJoin('users', 'users.id', '=', 'shipments.user_id');

      if(isset($searchQuery['email'])){
        $shipment->where('email','like','%'.$searchQuery['email'].'%');
      }

      if(isset($searchQuery['status'])){
        $shipment->where('status',$searchQuery['status']);
      }
      if(isset($searchQuery['user_id'])){
        $shipment->where('user_id',$searchQuery['user_id']);
      }
      if(isset($searchQuery['name'])){
        $shipment->where('name','like','%'.$searchQuery['name'].'%');
      }
      if(isset($searchQuery['parcel_desc'])){
        $shipment->where('parcel_desc','like','%'.$searchQuery['parcel_desc'].'%');
      }
      if(isset($searchQuery['postal_company'])){
        $shipment->where('postal_company','like','%'.$searchQuery['postal_company'].'%');
      }
      if(isset($searchQuery['parcel_number'])){
        $shipment->where('parcel_number','like','%'.$searchQuery['parcel_number'].'%');
      }

      if(isset($searchQuery['username'])){
        $shipment->where(function($query) use($searchQuery){
          return $query->where('first_name','like','%'.$searchQuery['username'].'%')
              ->orWhere('last_name','like','%'.$searchQuery['username'].'%')
              ->orWhere(DB::raw('concat(first_name," ",last_name)'),'like','%'.$searchQuery['username'].'%');
        });
      }

      if(isset($input['page'])){
        $start = $input['limit'] * ($input['page'] - 1);
        $end = $input['limit'];
        $shipment->skip($start)->take($end);
      }

      $orderBy = isset($input['orderBy']) ? $input['orderBy'] : 'id';
      $order = "desc";
      if(isset($input['ascending']) && $input['ascending'] == "1"){
        $order = "asc";
      }
      $shipment->orderBy($orderBy,$order);

      $data['shipments'] = $shipment->get();
      $data['count']  = DB::select(DB::raw("select FOUND_ROWS() as count"))[0]->count;
      return $data;
    }


    public static function getCounter($input)
    {
      $shipment = Shipment::select('*');
      if (isset($input['start_date']) && isset($input['end_date']) ) {
          $shipment->where(DB::raw('date(created_at)'), '>=', date('Y-m-d',strtotime($input['start_date'])))
              ->where(DB::raw('date(created_at)'), '<=', date('Y-m-d',strtotime($input['end_date'])));
      }

      if (isset($input['start_date'])&& !isset($input['end_date'])) {
          $shipment->where(DB::raw('date(created_at)'), date('Y-m-d',strtotime($input['start_date'])));
      }
      return $shipment->count();
    }

    public static function getShipment($id)
    {
      return Shipment::leftJoin('users','users.id','=','shipments.user_id')
         ->select('shipments.*',DB::raw('concat(first_name," ",last_name) as user_name'))
         ->where('shipments.id',$id)
         ->first();
    }

}
