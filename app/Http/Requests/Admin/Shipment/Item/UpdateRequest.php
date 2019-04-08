<?php

namespace App\Http\Requests\Admin\Shipment\Item;

use Illuminate\Foundation\Http\FormRequest;
use App\Library\Api;

class UpdateRequest extends FormRequest
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
          'item_name'        => 'required',
          'qty'              => 'required|numeric',
          'amount'           => 'required|numeric',
          'desc'             => 'max:1000',
          'dimension_length' => 'required|numeric',
          'dimension_width'  => 'required|numeric',
          'dimension_height' => 'required|numeric',
          'tracking_number'  => 'required',
          'weight' => 'required|numeric',
          'image.*'             => 'nullable|image|max:1000',
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
