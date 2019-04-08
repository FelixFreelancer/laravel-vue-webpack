<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'payment_type', // 1=>Shipment , 2=>Quotation, 3=>Subscription
        'payment_gateway_type', // 1=>Paypal , 2=>Epdq
        'amount',
        'entity_id',
        'payment_gateway_response',
        'payment_gateway_status',
        'transaction_id',
        'seller_account_id',
        'merchant_account_id',
        'status', // 1=>Payment Start , 2=>Error, 3=>Success, 4=>Cancel
        'payment_entity',
        'start_date',
        'end_date',
    ];

    protected $hidden = [
        'updated_at',
        'deleted_at',
        'payment_gateway_response'
    ];


    public static function getRevenue($input)
    {

     	$payment = Payment::select('payments.*')->leftJoin('users',function($query){
			$query->on('users.id','=','payments.payment_type')
				->whereNull('users.deleted_at');
		});
        if (isset($input['start_date']) && isset($input['end_date'])) {
            $payment->whereDate('payments.created_at', '>=', date('Y-m-d', strtotime($input['start_date'])))
                ->whereDate('payments.created_at', '<=', date('Y-m-d', strtotime($input['end_date'])));
        }
        if (isset($input['start_date'])&& !isset($input['end_date'])) {
            $payment->whereDate('payments.created_at','=', date('Y-m-d', strtotime($input['start_date'])));
        }
        $rst = $payment->where('payments.status','=','3')->sum('payments.amount');
		return $rst;
    }


    public static function search($input)
    {
      $searchQuery = isset($input['query']) ? $input['query'] : [];

      $payment = Payment::select(DB::raw('SQL_CALC_FOUND_ROWS payments.id'),'payments.*',
      DB::raw('concat(first_name," ",last_name) as name'))
        ->leftJoin('users','users.id','=','payments.user_id');
      if(isset($searchQuery['transaction_id'])){
        $payment->where('transaction_id','like','%'.$searchQuery['transaction_id'].'%');
      }
      if(isset($searchQuery['payment_gateway_type'])){
        $payment->where('payment_gateway_type',$searchQuery['payment_gateway_type']);
      }
      if(isset($searchQuery['payment_type'])){
        $payment->where('payment_type',$searchQuery['payment_type']);
      }
      if(isset($searchQuery['name'])){
        $payment->where(function($query) use($searchQuery){
          return $query->where('users.first_name','like','%'.$searchQuery['name'].'%')
              ->orWhere('users.last_name','like','%'.$searchQuery['name'].'%')
              ->orWhere(DB::raw('concat(users.first_name," ",users.last_name)'),'like','%'.$searchQuery['name'].'%');
        });
      }

      if(isset($input['page'])){
        $start = $input['limit'] * ($input['page'] - 1);
        $end = $input['limit'];
        $payment->skip($start)->take($end);
      }

      $orderBy = isset($input['orderBy']) ? $input['orderBy'] : 'id';
      $order = "desc";
      if(isset($input['ascending']) && $input['ascending'] == "1"){
        $order = "asc";
      }
      $payment->orderBy($orderBy,$order);

      $data['payment'] = $payment->get();
      $data['count']  = DB::select(DB::raw("select FOUND_ROWS() as count"))[0]->count;
      return $data;
    }
}
