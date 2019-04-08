<?php

namespace App\Http\Requests\Admin\Warehouse;

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
          'uk_warehouse_address_line_1' => 'required|max:1000',
          'uk_warehouse_address_line_2' => 'required|max:1000',
          'uk_warehouse_country'        => 'required|max:1000',
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
