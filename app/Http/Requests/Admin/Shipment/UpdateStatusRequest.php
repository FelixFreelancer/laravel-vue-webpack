<?php

namespace App\Http\Requests\Admin\Shipment;

use Illuminate\Foundation\Http\FormRequest;
use App\Library\Api;

class UpdateStatusRequest extends FormRequest
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
        switch (request('status')) {
            case '5':
                return [
                  'tracking_no' => 'required',
                  'tracking_link'=> 'required|url'
                ];
                break;
            case '6':
                return [
                  //'delivered_at' => 'required',
                ];
                break;
            default:
                return [
                  'status' => 'required'
                ];
        }
        return [
          'status' => 'required'
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
