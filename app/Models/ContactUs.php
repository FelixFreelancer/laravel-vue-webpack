<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class ContactUs extends Model
{
    use SoftDeletes;

    protected $table='contact_uses';

    protected $fillable = [
        'name',
        'email',
        'user_id',
        'status',
        'phone_no',
        'subject',
        'message',
    ];

    public static function validationRules()
    {
        return [
            'name'     => 'required|max:30',
            'email'    => 'required|max:30',
            'phone_no' => 'required|digits_between:1,20|numeric',
            'subject'  => 'required|max:100',
            'message'  => 'required|max:1000',
        ];
    }

    public static function search($input)
    {
      $searchQuery = isset($input['query']) ? $input['query'] : [];

      $users = ContactUs::select(DB::raw('SQL_CALC_FOUND_ROWS contact_uses.id'),'contact_uses.*',DB::raw('concat(users.first_name," ",users.last_name) as username'))
        ->leftJoin('users','users.id','=','contact_uses.user_id');

      if(isset($searchQuery['name'])){
        $users->where('name','like','%'.$searchQuery['name'].'%');
      }
      if(isset($searchQuery['email'])){
        $users->where('contact_uses.email','like','%'.$searchQuery['email'].'%');
      }
      if(isset($searchQuery['subject'])){
        $users->where('contact_uses.subject','like','%'.$searchQuery['subject'].'%');
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

        $data['contact'] = $users->get();
        $data['count']  = DB::select(DB::raw("select FOUND_ROWS() as count"))[0]->count;
        return $data;
    }
}
