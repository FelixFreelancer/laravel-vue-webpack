<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Foundation\Http\FormRequest;
use App\Library\Api;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
          'role_id' => 'required',
          'first_name' => 'required|alpha',
          'last_name'  => 'required|alpha',
          'email'      => 'required|email|unique:users,email,NULL,id,deleted_at,NULL',
          'cd_phone'        => 'nullable|numeric|unique:users,cd_phone,NULL,id,deleted_at,NULL',
          'password' => [
              'required',
              'min:8',
              'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/'
          ]
        ];
    }


    public function messages()
  	{
  	    return [
           'password.regex' => 'Password should not be less than 8 characters including uppercase, lowercase, at least one number and special character.'
  	    ];
  	}


    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
      $data['data']['error'] = $validator->errors();
      $statusCode = 422;
      $rst = Api::ApiResponse($data,$statusCode);
      throw new \Illuminate\Validation\ValidationException($validator, $rst);
    }
}
