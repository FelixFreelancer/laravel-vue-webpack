<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Mail\ContactResponseRequest;
use App\Models\User;
use App\Mail\SendMail;
use App\Models\ContactUs;
use App\Transformers\Admin\ContactTransformer;
use App\Library\Api;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{

    public function index()
    {
        $contact = ContactUs::search(request()->all());
        $obj = [];
        foreach($contact['contact'] as $key => $value){
          $obj[] = ContactTransformer::transform($value);
        }
  		$loggedInUser = Api::getAuthenticatedUser();
  		$name = $loggedInUser['user']['first_name']." ".$loggedInUser['user']['last_name'];
  		addLog($loggedInUser['user']['id'],'Inquiry Listing','<b>'.$name.'</b> has viewed <b>Inquiry Tab</b>.');
        $data['data']['contact'] = $obj;
        $data['data']['count'] = $contact['count'];
        return Api::ApiResponse($data);
    }

    public function response(ContactResponseRequest $request)
    {
		$data['data'] = [];
		$input = [
			'userObj' => [
				'name' => $request->name,
				'email' => $request->email,
			],
			'subject' => $request->subject,
			'mail' => $request->mail,
		];
		$contact = ContactUs::find($request->id);
		$contact->status = 1;
		$contact->save();
        Mail::to($request->email)->send(new SendMail($input));
        return Api::ApiResponse($data);
    }


    public function delete($id)
    {
		$statusCode = 200;
        $data['data'] = [];
        $contact = ContactUs::find($id);
        if($contact){
		  $loggedInUser = Api::getAuthenticatedUser();
		  $name = $loggedInUser['user']['first_name']." ".$loggedInUser['user']['last_name'];
		  $log = 'A Inquiry for <b>['.$contact['subject'].']</b>';
		  if($contact['user_id'] != ''){
		  	$user = User::find($contact['user_id'] );
		 	$username = $user['first_name']." ".$user['last_name'];
			$log .= " from <b>".$username." [". $user['customer_code']."]</b>";
		  }
		  $log .= ' has been deleted by <b>'.$name.'</b>';
		  addLog($loggedInUser['user']['id'],'Inquiry Delete',$log);
		  $contact->delete();
        }else{
          $statusCode = 422;
          $data['data']['error'] = "Inquiry Not Found";
        }
        return Api::ApiResponse($data);
    }

}
