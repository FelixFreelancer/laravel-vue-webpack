<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
	use SoftDeletes;

  protected $fillable = [
      'entity_id',
      'entity_type',
      'invoice_name',
      'user_id',
      'total',
      'due_date',
      'invoice_number',
      'invoice_path',
  ];


    public static function search($input)
    {
      $searchQuery = isset($input['query']) ? $input['query'] : [];

      $invoice = Invoice::select(DB::raw('SQL_CALC_FOUND_ROWS invoices.id'),'invoices.*', DB::raw('concat(users.first_name," ",users.last_name) as user_name'))
          ->leftJoin('users', 'users.id', '=', 'invoices.user_id');

      if(isset($searchQuery['type'])){
        $invoice->where('entity_type',$searchQuery['type']);
      }

      if(isset($searchQuery['invoice_number'])){
        $invoice->where('invoice_number','like','%'.$searchQuery['invoice_number'].'%');
      }

      if(isset($searchQuery['user_name'])){
        $invoice->where(function($query) use($searchQuery){
          return $query->where('first_name','like','%'.$searchQuery['user_name'].'%')
              ->orWhere('last_name','like','%'.$searchQuery['user_name'].'%')
              ->orWhere(DB::raw('concat(first_name," ",last_name)'),'like','%'.$searchQuery['user_name'].'%');
        });
      }

      if(isset($searchQuery['user_id'])){
        $invoice->where('user_id',$searchQuery['user_id']);
      }

      if(isset($input['page'])){
        $start = $input['limit'] * ($input['page'] - 1);
        $end = $input['limit'];
        $invoice->skip($start)->take($end);
      }

      $orderBy = isset($input['orderBy']) ? $input['orderBy'] : 'id';
      $order = "desc";
      if(isset($input['ascending']) && $input['ascending'] == "1"){
        $order = "asc";
      }
      $invoice->orderBy($orderBy,$order);

      $data['invoices'] = $invoice->get();
      $data['count']  = DB::select(DB::raw("select FOUND_ROWS() as count"))[0]->count;
      return $data;
    }
}
