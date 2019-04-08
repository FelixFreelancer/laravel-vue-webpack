<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Mail\StoreRequest;
use App\Transformers\Admin\MailTransformer;
use App\Models\UserMail;
use App\Models\User;
use App\Mail\SendMail;
use App\Library\Api;
use DB;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{

    public function index()
    {
        $input = request()->all();
        $searchQuery = isset($input['query']) ? $input['query'] : [];

        $mails = UserMail::leftJoin('users','users.id','=','mails.user_id')
              ->leftJoin('users as u2','u2.id','=','mails.sent_by')
              ->select(DB::raw('SQL_CALC_FOUND_ROWS mails.id'),'mails.*'
              ,DB::raw('concat(users.first_name," ",users.last_name) as to_user')
            ,DB::raw('concat(u2.first_name," ",u2.last_name) as from_user'));

        if(isset($searchQuery['user_id'])){
          $mails->where('user_id',$searchQuery['user_id']);
        }

        if(isset($searchQuery['subject'])){
          $mails->where('subject','like','%'.$searchQuery['subject'].'%');
        }

        if(isset($searchQuery['mail'])){
          $mails->where('mail','like','%'.$searchQuery['mail'].'%');
        }

        if(isset($searchQuery['from'])){
          $mails->where(function($query) use($searchQuery){
            return $query->where('u2.first_name','like','%'.$searchQuery['from'].'%')
                ->orWhere('u2.last_name','like','%'.$searchQuery['from'].'%')
                ->orWhere(DB::raw('concat(u2.first_name," ",u2.last_name)'),'like','%'.$searchQuery['from'].'%');
          });
        }

        if(isset($searchQuery['to'])){
          $mails->where(function($query) use($searchQuery){
            return $query->where('users.first_name','like','%'.$searchQuery['to'].'%')
                ->orWhere('users.last_name','like','%'.$searchQuery['to'].'%')
                ->orWhere(DB::raw('concat(users.first_name," ",u2.last_name)'),'like','%'.$searchQuery['to'].'%');
          });
        }

        if(isset($input['page'])){
          $start = $input['limit'] * ($input['page'] - 1);
          $end = $input['limit'];
          $mails->skip($start)->take($end);
        }

        $orderBy = isset($input['orderBy']) ? $input['orderBy'] : 'mails.id';
        $order = "desc";
        if(isset($input['ascending']) && $input['ascending'] == "1"){
          $order = "asc";
        }
        $mails->orderBy($orderBy,$order);

        $mailObject  = $mails->get();
        $data['data']['count']  = DB::select(DB::raw("select FOUND_ROWS() as count"))[0]->count;
        $mailObj = [];
        foreach($mailObject as $key => $value){
          $mailObj[] = MailTransformer::transform($value);
        }
        $data['data']['mails'] = $mailObj;
        return Api::ApiResponse($data);
    }

    public function store(StoreRequest $request)
    {
      $loggedInUser = Api::getAuthenticatedUser();
      $inputs = $request->all();
      $inputs['sent_by'] = $loggedInUser['user']->id;
      $mail = UserMail::create($inputs);
      $user = User::find($request->user_id);
      Mail::to($user->email)->send(new SendMail($mail));
      $mail['from_user'] = $loggedInUser['user']->first_name." ".$loggedInUser['user']->last_name;
      $mail['to_user'] = $user->first_name." ".$user->last_name;
      $data['data'] = MailTransformer::transform($mail->toArray());
      return Api::ApiResponse($data);
    }

}
