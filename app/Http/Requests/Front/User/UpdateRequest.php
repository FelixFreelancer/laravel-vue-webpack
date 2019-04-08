<?php

namespace App\Http\Requests\Front\User;

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
      $loggedInUser =auth()->user();
        return [
          'cd_address'      => 'required|max:1000',
          'cd_country'      => 'required|exists:countries,id',
          'cd_country_code' => 'required|exists:countries,id',
          'cd_state'        => 'required|max:191',
          'cd_city'         => 'required|max:191',
          'cd_postalcode'   => 'required|max:191',
          'cd_phone'        => 'nullable|numeric|unique:users,cd_phone,' . $loggedInUser['id'],
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
