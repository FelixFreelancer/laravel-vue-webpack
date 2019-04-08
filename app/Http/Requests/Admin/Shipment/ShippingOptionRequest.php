<?php

namespace App\Http\Requests\Admin\Shipment;

use Illuminate\Foundation\Http\FormRequest;
use App\Library\Api;

class ShippingOptionRequest extends FormRequest
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
			'user_id'             => 'required|exists:users,id',
            'name'                => 'required',
            'parcel_number'       => 'required',
            'parcel_desc'         => 'max:1000',
            'dimension_length'    => 'required|numeric',
            'dimension_width'     => 'required|numeric',
            'dimension_height'    => 'required|numeric',
            'parcel_weight'       => 'required|numeric',
            'postal_company'      => 'required',
            'received_on'         => 'required|date_format:d-m-Y H:i:s',
            'image.*'               => 'nullable|image|max:1000',
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
