<?php

namespace WalkerChiu\Account\Models\Forms;

use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use WalkerChiu\Core\Models\Forms\FormRequest;
use WalkerChiu\Currency\Models\Services\CurrencyService;

class ProfileFormRequest extends FormRequest
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
            'user_id'      => ['required','integer','min:1','exists:'.config('wk-core.table.user').',id'],
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
            $rules = array_merge($rules, ['id' => ['required','integer','min:1','exists:'.config('wk-core.table.user').',id','same:user_id']]);
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
            'id.required'           => trans('php-core::validation.required'),
            'id.integer'            => trans('php-core::validation.integer'),
            'id.min'                => trans('php-core::validation.min'),
            'id.exists'             => trans('php-core::validation.exists'),
            'id.same'               => trans('php-core::validation.same'),
            'user_id.required'      => trans('php-core::validation.required'),
            'user_id.integer'       => trans('php-core::validation.integer'),
            'user_id.min'           => trans('php-core::validation.min'),
            'user_id.exists'        => trans('php-core::validation.exists'),
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
    }
}
