<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Library\Api;

class LoginRequest extends FormRequest
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
        'email' => 'required|email',
        'password' => 'required'
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
