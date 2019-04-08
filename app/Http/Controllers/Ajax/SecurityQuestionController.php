<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\SecurityQuestion;
use App\Models\UserSecurityQuestion;
use App\Http\Requests\Front\User\SecurityUpdateRequest;
use App\Library\Api;
use Illuminate\Support\Facades\Mail;
use App\Mail\SecurityPasswordChange;

class SecurityQuestionController extends Controller
{

    public function index()
    {
        $securityQuestion = SecurityQuestion::get();
        $answer = UserSecurityQuestion::where('user_id', '=', auth()->id())->pluck('answer','security_question_id');
        $question = [];
        foreach($securityQuestion as $key => $value){
          $question[$key]['id']= $value['id'];
          $question[$key]['question'] = $value['questions'];
          $question[$key]['answer'] = isset($answer[$value['id']]) ? $answer[$value['id']] : NULL;
        }
        $data['data'] = $question;
        return Api::ApiResponse($data);
    }

    public function securityUpdate(SecurityUpdateRequest $request)
    {
      $user = auth()->user();
      if(request('password')){
        if (!$user->update(request()->only('password'))) {
          $statusCode = 422;
          $data['data']['error'] = 'Something went wrong.';
          return Api::ApiResponse($data,$statusCode);
        }
        Mail::to($user->email)->send(new SecurityPasswordChange($user));
      }
      if(request('security_question')){
        UserSecurityQuestion::where('user_id', '=', $user->id)->delete();
		foreach($request->security_question as $key=> $value){
			UserSecurityQuestion::create([
				'user_id'              => $user->id,
				'security_question_id' => $value,
				'answer'               => $request->answer[$key]
			]);
		}
      }
      $info = 'Security settings updated successfully';
        $data['data'] =  $user->toArray();
        return Api::ApiResponse($data,200,$info);
    }
}
