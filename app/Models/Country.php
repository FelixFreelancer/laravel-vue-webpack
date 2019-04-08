<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Country extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'nice_name',
        'short_code',
        'country_code',
        'suite_number',
        'iso'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public static function validationRules($id = 0)
    {
        return [
            'name'         => 'required',
            'country_code' => 'required|numeric|unique:countries,country_code,' . $id,
            'suite_number' => 'required|unique:countries,suite_number,' . $id,
            'iso'          => 'required|unique:countries,iso,' . $id
        ];
    }

    public static function getShortCodeByIsoCode($iso)
    {
        return Country::where('iso',$iso)->first();
    }

    public static function search($input)
    {
      $searchQuery = isset($input['query']) ? $input['query'] : [];

      $users = Country::select(DB::raw('SQL_CALC_FOUND_ROWS countries.id'),'name', 'nice_name', 'short_code', 'country_code',
      'suite_number', 'iso');

      if(isset($searchQuery['short_code'])){
        $users->where('short_code',$searchQuery['short_code']);
      }

      if(isset($searchQuery['country_code'])){
        $users->where('country_code',$searchQuery['country_code']);
      }

      if(isset($searchQuery['name'])){
        $users->where('name','like','%'.$searchQuery['name'].'%');
      }

      if(isset($searchQuery['nice_name'])){
        $users->where('nice_name','like','%'.$searchQuery['nice_name'].'%');
      }

      if(isset($searchQuery['suite_number'])){
        $users->where('suite_number','like','%'.$searchQuery['suite_number'].'%');
      }

      if(isset($searchQuery['iso'])){
        $users->where('iso','like','%'.$searchQuery['iso'].'%');
      }

      if(isset($input['page'])){
        $start = $input['limit'] * ($input['page'] - 1);
        $end = $input['limit'];
        $users->skip($start)->take($end);
      }

      $orderBy = isset($input['orderBy']) ? $input['orderBy'] : 'id';
      $order = "desc";
      if(isset($input['ascending']) && $input['ascending'] == "1"){
        $order = "asc";
      }
      $users->orderBy($orderBy,$order);

        $data['country'] = $users->get();
        $data['count']  = DB::select(DB::raw("select FOUND_ROWS() as count"))[0]->count;
        return $data;
    }
}
