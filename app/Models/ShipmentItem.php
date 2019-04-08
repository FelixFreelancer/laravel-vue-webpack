<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class ShipmentItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'shipment_id',
        'item_name',
        'qty',
        'amount',
        'desc',
        'dimension_length',
        'dimension_width',
        'dimension_height',
        'weight',
        'tracking_number',
    ];

    public static function validationRules()
    {
        return [
            'item_name'        => 'required',
            'qty'              => 'required|numeric',
            'amount'           => 'required|numeric',
            'desc'             => 'max:1000',
            'dimension_length' => 'required|numeric',
            'dimension_width'  => 'required|numeric',
            'dimension_height' => 'required|numeric',
            'tracking_number'  => 'required',
        ];
    }

    public static function globalSearch($input)
    {
      $shipment = ShipmentItem::select('shipment_items.*','shipments.user_id','shipments.name as shipment_name',DB::raw('concat(users.first_name," ",users.last_name) as user_name'),'users.customer_code')
                ->leftJoin('shipments','shipments.id','=','shipment_items.shipment_id')
                ->leftJoin('users','users.id','=','shipments.user_id');
      if(isset($input['search'])){
        $shipment->where('item_name','like','%'.$input['search'].'%')
          ->orWhere('tracking_number','like','%'.$input['search'].'%');
      }
      $start = 0;
      $end = config('site.pagination');
      $shipment->skip($start)->take($end);
      $shipment->orderBy('id','desc');
      return $shipment->get();
    }

}
