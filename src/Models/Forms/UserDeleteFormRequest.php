<?php

namespace WalkerChiu\Account\Models\Forms;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use WalkerChiu\Core\Models\Forms\FormRequest;

class UserDeleteFormRequest extends FormRequest
{
    /**
     * @Override Illuminate\Foundation\Http\FormRequest::getValidatorInstance
     */
    protected function getValidatorInstance()
    {
        $request = Request::instance();
        $data = $this->all();
        $data['id'] = (int) $request->id;
        $this->getInputSource()->replace($data);

        return parent::getValidatorInstance();
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return Array
     */
    public function attributes()
    {
        return parent::attributes();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return Array
     */
    public function rules()
    {
        return [
            'id' => ['required','integer','min:1','exists:'.config('wk-core.table.user').',id']
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return Array
     */
    public function messages()
    {
        return [
            'id.required' => trans('php-core::validation.required'),
            'id.integer'  => trans('php-core::validation.integer'),
            'id.min'      => trans('php-core::validation.min'),
            'id.exists'   => trans('php-core::validation.exists')
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after( function ($validator) {
            $data = $validator->getData();
            if (
                $data['id'] == 1
                || $data['id'] == Auth::id()
            ) {
                $validator->errors()->add('id', trans('php-core::validation.not_allowed'));
            }
        });
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation($validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
