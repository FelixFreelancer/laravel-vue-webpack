<?php

namespace App\Http\Requests\Front\User;

use Illuminate\Foundation\Http\FormRequest;
use App\Library\Api;

class SecurityUpdateRequest extends FormRequest
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
          'password'          => [
              'nullable',
              'min:8',
              'confirmed',
              'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/'
          ],
          'password_confirmation' => 'nullable',
          'security_question.*' => 'nullable|required_with:answer|exists:security_questions,id|distinct',
          'answer.*'            => 'nullable|required_with:security_question|min:4'
        ];
    }

    public function messages()
    {
        return [
            'password.regex' => 'Passwords should not be less than 8 characters including uppercase, lowercase, at least one number and special character.',
            'password.min'   => 'Passwords should not be less than 8 characters including uppercase, lowercase, at least one number and special character.',
			'security_question.*.distinct' => 'The security question has a duplicate value.',
			'answer.*.required_with' => 'Answer is required when security question is present.',
			// 'answer.*.required_with:security_question' =>  'Answer is required when security question is present.',
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
