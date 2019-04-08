<?php

namespace App\Http\Requests\Admin\Country;

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
          'name'         => 'required',
          'country_code' => 'required|numeric|unique:countries,country_code,NULL,id,deleted_at,NULL',
          'suite_number' => 'required|unique:countries,suite_number,NULL,id,deleted_at,NULL',
          'iso'          => 'required|unique:countries,iso,NULL,id,deleted_at,NULL'
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
