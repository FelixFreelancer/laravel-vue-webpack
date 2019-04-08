<?php

namespace App\Models;

use App\Mail\RegistrationSuccessful;
use App\Mail\UserVerification;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Library\Api;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes, HasRoles;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'gender',
        'is_active',
        'company_name',
        'cd_address',
        'cd_country',
        'cd_country_code',
        'cd_state',
        'cd_city',
        'cd_postalcode',
        'cd_phone',
        'ba_country',
        'ba_country_code',
        'ba_state',
        'ba_city',
        'ba_address',
        'ba_postalcode',
        'ba_phone',
        'google2fa_secret',
        'suite_number',
        'customer_number',
        'plan_type',
        'same_as_cd',
        'email_verified_at',
        'cd_phone_verified_at',
        'image_name',
        'image_path',
        'membership_validity',
        'started_at',
        'auto_renew',
        'last_activity'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

	protected $dates = ['deleted_at'];

    public static function validationRules($same_as_cd = 0, $id = 0)
    {
        $rules = [
            'first_name'      => 'required|alpha|max:191',
            'last_name'       => 'required|alpha|max:191',
            'email'           => 'required|email|max:191|unique:users,email,NULL,id,deleted_at,NULL',
            'password'        => [
                'required',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/'
            ],
            'gender'          => 'required|in:1,2,3',
            'company_name'    => 'nullable|max:191',
            'cd_address'      => 'required|max:1000',
            'cd_country'      => 'required|exists:countries,iso',
            'cd_country_code' => 'required|exists:countries,iso',
            'cd_state'        => 'required|max:191',
            'cd_city'         => 'required|max:191',
            'cd_postalcode'   => 'nullable|max:191',
            'cd_phone'        => 'nullable|numeric|unique:users,cd_phone,' . $id.',id,deleted_at,NULL',
        ];
        if ($same_as_cd == 0) {
            $rules += [
                'ba_address'      => 'required|max:1000',
                'ba_country'      => 'required|exists:countries,iso',
                'ba_country_code' => 'required|exists:countries,iso',
                'ba_state'        => 'required|max:191',
                'ba_city'         => 'required|max:191',
                'ba_postalcode'   => 'required|max:191',
                'ba_phone'        => 'nullable|numeric',
            ];
        }
        return $rules;
    }

    public static function profileValidationRules($type = '', $id = 0)
    {
        $rules = [];
        if ($type == 'cd') {
            $rules += [
                'cd_address'      => 'required|max:1000',
                'cd_country'      => 'required|exists:countries,id',
                'cd_country_code' => 'required|exists:countries,id',
                'cd_state'        => 'required|max:191',
                'cd_city'         => 'required|max:191',
                'cd_postalcode'   => 'required|max:191',
                'cd_phone'        => 'nullable|numeric|unique:users,cd_phone,' . $id,
            ];
        }
        if ($type == 'security') {
            $rules += [
                'password'          => [
                    'nullable',
                    'min:8',
                    'confirmed',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/'
                ],
                'password_confirmation' => 'nullable',
                'security_question' => 'required|exists:security_questions,id',
                'answer'            => 'required'
            ];
        }
        return $rules;
    }

    public static function loginValidationRules()
    {
        return [
            'email'    => 'required|email',
            'password' => 'required'
        ];
    }

    public static function googleValidationRules()
    {
        return [
            'auth_code.0' => 'required|numeric',
            'auth_code.1' => 'required|numeric',
            'auth_code.2' => 'required|numeric',
            'auth_code.3' => 'required|numeric',
            'auth_code.4' => 'required|numeric',
            'auth_code.5' => 'required|numeric',
            'user_id'     => 'required|numeric'
        ];
    }

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    public function securityQuestions()
    {
        return $this->belongsToMany(SecurityQuestion::class, 'user_security_questions')->withTimestamps();
    }

    public static function adminValidationRules($id = 0)
    {
        $rules = [
            'first_name' => 'required|alpha',
            'last_name'  => 'required|alpha',
            'email'      => 'required|email|unique:users,email,' . $id,
        ];

        if ($id == 0) {
            $rules += [
                'password' => [
                    'required',
                    'min:8',
                    'confirmed',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/'
                ],
            ];
        } else {
            $rules += [
                'password' => [
                    'required',
                    'min:8',
                    'confirmed',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/'
                ],
            ];
        }

        return $rules;
    }

    public static function globalSearch($input)
    {
		\DB::enableQueryLog();
      $users = User::leftJoin('countries','countries.id','=','users.cd_country')
      ->select('users.id',DB::raw('concat(first_name," ",last_name) as name'), 'email_verified_at', 'cd_phone_verified_at', 'email', 'cd_phone','countries.nice_name as country_name','cd_country_code', 'plan_type', 'customer_code');
      if(isset($input['search'])){
        $users->where('customer_code','like','%'.$input['search'].'%')
          ->orWhere('cd_phone','like','%'.$input['search'].'%')
          ->orWhere('cd_phone','like','%'.$input['search'].'%')
          ->orWhere('plan_type','like','%'.$input['search'].'%')
          ->orWhere('first_name','like','%'.$input['search'].'%')
          ->orWhere('last_name','like','%'.$input['search'].'%')
          ->orWhere(DB::raw('concat(first_name," ",last_name)'),'like','%'.$input['search'].'%')
          ->orWhere('email','like','%'.$input['search'].'%');
      }

      if (isset($input['start_date']) && isset($input['end_date'])) {
          $users->where(DB::raw('date(created_at)'), '>=', date('Y-m-d', $input['start_date']))
              ->where(DB::raw('date(created_at)'), '<=', date('Y-m-d', $input['end_date']));
      }
      $start = 0;
      $end = config('site.pagination');
      $users->skip($start)->take($end);
      $users->orderBy('id','desc');
      $data = $users->get();
	  return $data;
    }

    public static function search($input)
    {

      $searchQuery = isset($input['query']) ? $input['query'] : [];
      $users = User::select(DB::raw('SQL_CALC_FOUND_ROWS users.id'),
      'first_name', 'last_name', 'email', 'users.created_at', 'gender', 'company_name', 'cd_address', 'cd_country', 'cd_country_code',
      'cd_state', 'cd_city', 'cd_postalcode', 'cd_phone_verified_at', 'cd_phone', 'ba_country', 'ba_country_code', 'ba_state', 'ba_city', 'ba_address',
      'ba_postalcode', 'ba_phone',  'google2fa_secret', 'users.suite_number', 'customer_number', 'plan_type', 'same_as_cd', 'email_verified_at',
      'membership_validity', 'customer_code', 'customer_number','countries.nice_name as country','c2.nice_name as country_name')
      ->leftJoin('countries','countries.id','=','users.cd_country')
      ->leftJoin('countries as c2','c2.id','=','users.ba_country');


      if(isset($searchQuery['role'])){
        $role = Role::where('id',$searchQuery['role'])->first();
        $users->role($role['name']);
      }else{
        if(isset($searchQuery['type']) && $searchQuery['type'] == 'customer'){
          $users->role('Customer');
        }else{
			$loggedInUser =  Api::getAuthenticatedUser();
			if($loggedInUser['user']->hasRole('Super Admin')){
				$role = Role::where('id','!=',config('site.customer_role_id'))->pluck('name');
				$users->role($role);
			}
			else{
				$roles = $loggedInUser['user']->roles;
				$accesible_roles = explode(",",$roles[0]['access_roles']);
				$roleArray = Role::whereIn('id',$accesible_roles)->pluck('name');
				$users->role($roleArray);
			}
        }
      }

      if(isset($searchQuery['customer_code'])){
        $users->where('customer_code','like','%'.$searchQuery['customer_code'].'%');
      }

      if(isset($searchQuery['user_type'])){
          if($searchQuery['user_type'] == 1){
            $users->whereNull('email_verified_at')
                  ->whereNull('cd_phone_verified_at');
          }
          if($searchQuery['user_type'] == 2){
            $users->whereNotNull('email_verified_at')
                  ->whereNull('cd_phone_verified_at');
          }
          if($searchQuery['user_type'] == 3){
            $users->whereNotNull('email_verified_at')
                  ->whereNotNull('cd_phone_verified_at');
          }
      }

      if(isset($searchQuery['contact_number'])){
        $users->where('cd_phone','like','%'.$searchQuery['contact_number'].'%');
      }
      if(isset($searchQuery['plan_type'])){
        $users->where('plan_type',$searchQuery['plan_type']);
      }
      if(isset($searchQuery['name'])){
        $users->where(function($query) use($searchQuery){
          return $query->where('first_name','like','%'.$searchQuery['name'].'%')
              ->orWhere('last_name','like','%'.$searchQuery['name'].'%')
              ->orWhere(DB::raw('concat(first_name," ",last_name)'),'like','%'.$searchQuery['name'].'%');
        });
      }
      if(isset($searchQuery['email'])){
        $users->where('email','like','%'.$searchQuery['email'].'%');
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

      $data['users'] = $users->get();
      $data['count']  = DB::select(DB::raw("select FOUND_ROWS() as count"))[0]->count;
      return $data;
    }

    public function sendVerificationLink()
    {
        $token = str_random(64);

        DB::table(config('auth.passwords.users.table'))
            ->where('email', '=', $this->email)
            ->where('token_type', '=', '2')
            ->delete();

        DB::table(config('auth.passwords.users.table'))->insert([
            'email'      => $this->email,
            'token'      => $token,
            'token_type' => 2,
            'created_at' => date_time_database('now')
        ]);

        try {
           Mail::to($this->email)->send(new UserVerification($this, $token));
            //session()->flash('message', 'Your reset password request submitted successfully. Please check your registered email for reset password link.');
            //session()->flash('class', 'success');
        } catch (\Exception $e) {
          \Log::info(json_encode($e));
            session()->flash('message', 'Something went wrong try again later.');
            session()->flash('class', 'danger');
            return back();
        }
    }

    public function successfullRegistrationMail()
    {
        // try {
            Log::info("Registration successfull Mail Sent to ".$this->email);
            Mail::to($this->email)->send(new RegistrationSuccessful($this));
        // } catch (\Exception $e) {
        //     session()->flash('message', 'Something went wrong try again later.');
        //     session()->flash('class', 'danger');
        //     return back();
        // }
    }

    public function sendOtp($profile=false)
    {
        $client = twillioApi();
        $otp = rand(1000000, 9999999);
        Otp::where('user_id', '=', $this->id)->delete();
        Otp::create([
            'user_id' => $this->id,
            'otp'     => $otp
        ]);

        $phone = $this->cd_phone;

       // if($profile){
         $mobile = UserMobile::where('user_id', '=', $this->id)
        ->orderBy('id', 'desc')
        ->first();
        if($mobile){

            $phone = $mobile['new_mobile'];

            $mobile->otp = $otp;
            $mobile->save();
        }else{
            UserMobile::create([
            'user_id' => $this->id,
            'new_mobile' => $this->cd_phone,
            'otp' => $otp,
          ]);
        }

      //  }
        $country = Country::where('id', '=', $this->cd_country)->first();
        $country_code = '';
        if ($country != null) {
            $country_code = $country->country_code;
        }
        Log::info($phone);
         try {
             Log::info("OTP : ".$otp." sent to : ".$phone);
             $message = $client->messages->create(

                '+' . $country_code . $phone, // Text this number
                [
                    'from' => '+18338125096', // From a valid Twilio number
                    'body' => 'Your OTP for your GPF account is ' . $otp . '. Please DO NOT share with anyone.'
                ]
            );
        } catch (\Exception $e) {
            Log::info($e);
        }
    }

    public static function validationMessages()
    {
        return [
            'password.regex' => 'Passwords should not be less than 8 characters including uppercase, lowercase, at least one number and special character.',
            'password.min'   => 'Passwords should not be less than 8 characters including uppercase, lowercase, at least one number and special character.'
        ];
    }

    public static function getCounter($input)
    {
      $users = User::role('Customer');
	  if (isset($input['start_date']) && isset($input['end_date'])) {
		  $users->whereDate('created_at', '>=', date('Y-m-d', strtotime($input['start_date'])))
			  ->whereDate('created_at', '<=', date('Y-m-d', strtotime($input['end_date'])));
	  }
	  if (isset($input['start_date'])&& !isset($input['end_date'])) {
		  $users->whereDate('created_at','=', date('Y-m-d', strtotime($input['start_date'])));
	  }
      return $users->count();
    }
}
