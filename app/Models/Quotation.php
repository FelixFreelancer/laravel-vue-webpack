<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Quotation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'store_name',
        'direct_link',
        'item_name',
        'user_price_currency',
        'user_price',
        'color',
        'quantity',
        'quotation_number',
        'admin_price_currency',
        'admin_price',
        'admin_id',
        'total',
        'status',
        'status_by',
    ];

    public static function validationRules()
    {
        return [
            'store_name.*'          => 'required|max:191',
            'direct_link.*'         => 'required|max:300',
            'item_name.*'           => 'required|max:191',
            'user_price_currency.*' => 'required|max:10',
            'user_price.*'          => 'required|numeric',
            'color.*'               => 'required|max:191',
            'quantity.*'            => 'required|numeric',
        ];
    }

    public static function validationMessages()
    {
        return [
            'store_name.*.required'          => 'This field is required.',
            'store_name.*.max'               => 'This field can only have 191 characters.',
            'direct_link.*.required'         => 'This field is required.',
            'direct_link.*.max'              => 'This field can only have 300 characters.',
            'item_name.*.required'           => 'This field is required.',
            'item_name.*.max'                => 'This field can only have 191 characters.',
            'user_price_currency.*.required' => 'This field is required.',
            'user_price_currency.*.max'      => 'This field can only have 10 characters.',
            'user_price.*.required'          => 'This field is required.',
            'user_price.*.numeric'           => 'This field can only have numeric values.',
            'color.*.required'               => 'This field is required.',
            'color.*.max'                    => 'This field can only have 191 characters.',
            'quantity.*.required'            => 'This field is required.',
            'quantity.*.numeric'             => 'This field can only have numeric values.',
        ];
    }

    public static function globalSearch($input)
    {
      $quotation = Quotation::select('quotations.*',DB::raw('concat(users.first_name," ",users.last_name) as user_name'),'users.customer_code')
                ->leftJoin('users', 'users.id', '=', 'quotations.user_id');
      if(isset($input['search'])){
        $quotation->where('quotation_number','like','%'.$input['search'].'%')
          ->orWhere('total','like','%'.$input['search'].'%');
      }
      if (isset($input['start_date']) && isset($input['end_date'])) {
          $quotation->where(DB::raw('date(created_at)'), '>=', date('Y-m-d', ($input['start_date'])))
              ->where(DB::raw('date(created_at)'), '<=', date('Y-m-d', ($input['end_date'])));
      }
      $start = 0;
      $end = config('site.pagination');
      $quotation->skip($start)->take($end);
      $quotation->orderBy('id','desc');
      return $quotation->get();
    }

    public static function getOldQuotations($input)
    {
        $start = isset($input['page']) ? $input['limit'] * ($input['page'] - 1) : 0;
        $end = isset($input['page']) ? $input['limit'] : config('site.pagination');
        $quotation = Quotation::leftJoin('users', 'users.id', '=', 'quotations.user_id')
              ->leftJoin('quotation_items', 'quotation_items.quotation_id', '=', 'quotations.id')
              ->select('quotations.*','quotation_items.user_price',DB::raw('concat(users.first_name," ",users.last_name) as username'))
              ->where('status',1)
              ->skip($start)
              ->take($end)
              ->get();
        return $quotation;
    }

    public static function search($input)
    {
      $searchQuery = isset($input['query']) ? $input['query'] : [];

      $quotation = Quotation::select(DB::raw('SQL_CALC_FOUND_ROWS quotations.id'),'quotations.quotation_number','quotations.created_at',
      'quotations.total as user_total_price','quotations.status',DB::raw('count(quotation_items.id) as total_items'),'quotations.user_id',DB::raw('concat(users.first_name," ",users.last_name) as user_name'),DB::raw('concat(u1.first_name," ",u1.last_name) as handled_by'))
          ->leftJoin('users', 'users.id', '=', 'quotations.user_id')
          ->leftJoin('users as u1', 'u1.id', '=', 'quotations.status_by')
          ->leftJoin('quotation_items', 'quotation_items.quotation_id', '=', 'quotations.id')
          ->groupBy('quotations.quotation_number', 'quotations.status', 'users.id','quotations.id');

      if(isset($searchQuery['quotation_number'])){
        $quotation->where('quotation_number','like','%'.$searchQuery['quotation_number'].'%');
      }

      if(isset($searchQuery['user_total_price'])){
        $quotation->where('quotations.total',$searchQuery['user_total_price']);
      }

      if(isset($searchQuery['status'])){
        $quotation->where('quotations.status',$searchQuery['status']);
      }

      if(isset($searchQuery['user_id'])){
        $quotation->where('quotations.user_id',$searchQuery['user_id']);
      }

      if(isset($searchQuery['user_name'])){
        $quotation->where(function($query) use($searchQuery){
          return $query->where('users.first_name','like','%'.$searchQuery['user_name'].'%')
              ->orWhere('users.last_name','like','%'.$searchQuery['user_name'].'%')
              ->orWhere(DB::raw('concat(users.first_name," ",users.last_name)'),'like','%'.$searchQuery['user_name'].'%');
        });
      }

      if(isset($input['page'])){
        $start = $input['limit'] * ($input['page'] - 1);
        $end = $input['limit'];
        $quotation->skip($start)->take($end);
      }
      $orderBy = isset($input['orderBy']) ? $input['orderBy'] : 'id';
      $order = "desc";
      if(isset($input['ascending']) && $input['ascending'] == "1"){
        $order = "asc";
      }
      $quotation->orderBy($orderBy,$order);
      $data['quotation'] = $quotation->get();
      $data['count']  = DB::select(DB::raw("select FOUND_ROWS() as count"))[0]->count;
      return $data;
    }

    public static function getCounter($input)
    {
      $quotation = Quotation::select('*');
      if (isset($input['start_date']) && isset($input['end_date']) ) {
          $quotation->where(DB::raw('date(created_at)'), '>=', date('Y-m-d', strtotime($input['start_date'])))
              ->where(DB::raw('date(created_at)'), '<=', date('Y-m-d', strtotime($input['end_date'])));
      }

      if (isset($input['start_date'])&& !isset($input['end_date'])) {
          $quotation->where(DB::raw('date(created_at)'), date('Y-m-d', strtotime($input['start_date'])));
      }
      return $quotation->count();
    }
}
