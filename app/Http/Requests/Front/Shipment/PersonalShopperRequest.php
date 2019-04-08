<?php

namespace App\Http\Requests\Front\Shipment;

use Illuminate\Foundation\Http\FormRequest;
use App\Library\Api;

class PersonalShopperRequest extends FormRequest
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
          'store_name.*'          => 'required|max:191',
          'direct_link.*'         => 'required|max:300',
          'item_name.*'           => 'required|max:191',
          'user_price_currency.*' => 'required|max:10',
          'user_price.*'          => 'required|numeric',
          'color.*'               => 'required|max:191',
          'quantity.*'            => 'required|numeric',
        ];
    }

	public function messages()
	{
		return [
			'store_name.*.required' => 'Store Name is required.',
			'direct_link.*.required' => 'Item Direct Link is required.',
			'item_name.*.required' => 'Item Name is required.',
			'user_price.*.required' => 'Price is required.',
			'user_price_currency.*.required' => 'Currency is required.',
			'color.*.required' => 'Color is required.',
			'quantity.*.required' => 'Quantity is required.',
			'quantity.*.numeric' => 'Quantity must be a number.',
			'user_price.*.numeric' => 'Price must be a number.',
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
