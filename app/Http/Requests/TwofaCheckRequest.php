<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Library\Api;

class TwofaCheckRequest extends FormRequest
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
        'auth_code.0' => 'required|numeric',
        'auth_code.1' => 'required|numeric',
        'auth_code.2' => 'required|numeric',
        'auth_code.3' => 'required|numeric',
        'auth_code.4' => 'required|numeric',
        'auth_code.5' => 'required|numeric',
        'user_id'     => 'required|numeric'
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
