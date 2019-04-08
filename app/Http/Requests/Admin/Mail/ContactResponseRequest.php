<?php

namespace App\Http\Requests\Admin\Mail;

use Illuminate\Foundation\Http\FormRequest;
use App\Library\Api;

class ContactResponseRequest extends FormRequest
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
          'mail' => 'required',
          'subject' => 'required',
          'email' => 'required',
          'id' => 'required',
          'name' => 'required'
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
