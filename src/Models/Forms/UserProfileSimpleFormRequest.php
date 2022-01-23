<?php

namespace WalkerChiu\Account\Models\Forms;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use WalkerChiu\Core\Models\Forms\FormRequest;
use WalkerChiu\Currency\Models\Services\CurrencyService;

class UserProfileSimpleFormRequest extends FormRequest
{
    /**
     * @Override Illuminate\Foundation\Http\FormRequest::getValidatorInstance
     */
    protected function getValidatorInstance()
    {
        $request = Request::instance();
        $data = $this->all();
        if (
            $request->isMethod('put')
            && empty($data['id'])
            && isset($request->id)
        ) {
            $data['id'] = (int) $request->id;
            $this->getInputSource()->replace($data);
        }

        return parent::getValidatorInstance();
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return Array
     */
    public function attributes()
    {
        return [
            'email'            => trans('php-account::system.user.email'),
            'password'         => trans('php-account::system.user.password'),
            'password_confirm' => trans('php-account::system.user.password_confirm'),
            'password_current' => trans('php-account::system.user.password_current'),

            'language'     => trans('php-account::system.profile.language'),
            'timezone'     => trans('php-account::system.profile.timezone'),
            'currency_id'  => trans('php-account::system.profile.currency_id'),
            'gender'       => trans('php-account::system.profile.gender'),
            'notice_login' => trans('php-account::system.profile.notice_login'),
            'note'         => trans('php-account::system.profile.note'),
            'remarks'      => trans('php-account::system.profile.remarks'),
            'addresses'    => trans('php-account::system.profile.addresses'),
            'images'       => trans('php-account::system.profile.images')
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return Array
     */
    public function rules()
    {
        $rules = [
            'email'            => ['required','email'],
            'password'         => 'nullable|string|min:8|max:12|same:password_confirm',
            'password_confirm' => 'same:password',
            'password_current' => 'nullable|string|min:8|max:12',

            'language'     => ['required', Rule::in(config('wk-core.class.core.language')::getCodes())],
            'timezone'     => ['nullable', Rule::in(config('wk-core.class.core.timeZone')::getValues())],
            'gender'       => '',
            'notice_login' => 'required|boolean',
            'note'         => '',
            'remarks'      => '',
            'addresses'    => 'nullable|json',
            'images'       => 'nullable|json'
        ];
        if (config('wk-account.onoff.currency')) {
            $service = new CurrencyService();
            $rules = array_merge($rules, [
                'currency_id' => ['required', Rule::in($service->getEnabledSettingId())]
            ]);
        } else {
            $rules = array_merge($rules, [
                'currency_id' => ''
            ]);
        }

        $request = Request::instance();
        if (
            $request->isMethod('put')
            && isset($request->id)
        ) {
            $rules = array_merge($rules, ['id' => ['required','integer','min:1','exists:'.config('wk-core.table.user').',id']]);
            $rules['email'] = array_merge($rules['email'], ['unique:'.config('wk-core.table.user').',email,'.$request->id.',id']);
        } else {
            $rules['email'] = array_merge($rules['email'], ['unique:'.config('wk-core.table.user').',email,id']);
        }

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return Array
     */
    public function messages()
    {
        return [
            'id.required'             => trans('php-core::validation.required'),
            'id.integer'              => trans('php-core::validation.integer'),
            'id.min'                  => trans('php-core::validation.min'),
            'id.exists'               => trans('php-core::validation.exists'),
            'email.required'          => trans('php-core::validation.required'),
            'email.email'             => trans('php-core::validation.email'),
            'email.unique'            => trans('php-core::validation.unique'),
            'password.string'         => trans('php-core::validation.string'),
            'password.min'            => trans('php-core::validation.min'),
            'password.max'            => trans('php-core::validation.max'),
            'password.same'           => trans('php-core::validation.same'),
            'password_confirm.same'   => trans('php-core::validation.same'),
            'password_current.string' => trans('php-core::validation.string'),
            'password_current.min'    => trans('php-core::validation.min'),
            'password_current.max'    => trans('php-core::validation.max'),

            'language.required'     => trans('php-core::validation.required'),
            'language.in'           => trans('php-core::validation.in'),
            'timezone.in'           => trans('php-core::validation.in'),
            'currency_id.required'  => trans('php-core::validation.required'),
            'currency_id.in'        => trans('php-core::validation.in'),
            'notice_login.required' => trans('php-core::validation.required'),
            'notice_login.boolean'  => trans('php-core::validation.boolean'),
            'addresses.json'        => trans('php-core::validation.json'),
            'images.json'           => trans('php-core::validation.json')
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
            $attributes = $this->attributes();
            $data = $validator->getData();
            if (
                isset($data['id'])
                && $data['id'] == 1
                && isset($data['is_enabled'])
                && !$data['is_enabled']
            ) {
                $validator->errors()->add('id', trans('php-core::validation.not_allowed'));
            }

            if (
                isset($data['password'])
                && !empty($data['password'])
                && !preg_match('/^((?=.*[0-9])(?=.*[a-z|A-Z]))^.*$/', $data['password'])
            ) {
                $validator->errors()->add('password', trans('php-core::validation.password_rule', ['attribute' => $attributes['password']]));
            }
            if (
                isset($data['password_current'])
                && !empty($data['password_current'])
            ) {
                if (!preg_match('/^((?=.*[0-9])(?=.*[a-z|A-Z]))^.*$/', $data['password_current'])) {
                    $validator->errors()->add('password_current', trans('php-core::validation.password_rule', ['attribute' => $attributes['password_current']]));
                } elseif (
                    isset($data['id'])
                    && !empty($data['id'])
                ) {
                    $user = config('wk-core.class.user')::find($data['id']);
                    if (
                        $user
                        && !Hash::check($data['password_current'], $user->password)
                    ) {
                        $validator->errors()->add('password_current', trans('php-core::validation.password'));
                    }
                }
            }
        });
    }
}
