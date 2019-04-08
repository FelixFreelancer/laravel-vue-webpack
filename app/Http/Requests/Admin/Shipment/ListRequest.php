<?php

namespace App\Http\Requests\Admin\Shipment;

use Illuminate\Foundation\Http\FormRequest;
use App\Library\Api;

class ListRequest extends FormRequest
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
          'limit' => 'integer',
          'query' => '',
          'byColumn' => '',
          'orderBy' => '',
          'ascending' => '',
          'page' => 'integer',
          'fields' => '',
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
