<?php

namespace WalkerChiu\Account\Models\Observers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use WalkerChiu\Currency\Models\Services\CurrencyService;

class ProfileObserver
{
    /**
     * Handle the model "retrieved" event.
     *
     * @param Model  $model
     * @return void
     */
    public function retrieved($model)
    {
        //
    }

    /**
     * Handle the model "creating" event.
     *
     * @param Model  $model
     * @return void
     */
    public function creating($model)
    {
        //
    }

    /**
     * Handle the model "created" event.
     *
     * @param Model  $model
     * @return void
     */
    public function created($model)
    {
        //
    }

    /**
     * Handle the model "updating" event.
     *
     * @param Model  $model
     * @return void
     */
    public function updating($model)
    {
        //
    }

    /**
     * Handle the model "updated" event.
     *
     * @param Model  $model
     * @return void
     */
    public function updated($model)
    {
        if (
            Auth::check()
            && Auth::id() == $model->user_id
        ) {
            if (!is_null($model->timezone)) {
                Session::put('timezone', $model->timezone);
            }
        }
    }

    /**
     * Handle the model "saving" event.
     *
     * @param Model  $model
     * @return void
     */
    public function saving($model)
    {
        if (!is_null($model->language)) {
            if (!in_array($model->language, config('wk-core.class.core.language')::getCodes()))
                return false;
        }
        if (!is_null($model->timezone)) {
            if (!in_array($model->timezone, config('wk-core.class.core.timeZone')::getValues()))
                return false;
        }
        if (config('wk-account.onoff.currency')) {
            $service = new CurrencyService();
            if (!is_null($model->currency_id)) {
                if (in_array($model->currency_id, $service->getEnabledSettingId())) {
                    Session::put('timezone', $model->currency_id);
                } else {
                    return false;
                }
            }
        }
    }

    /**
     * Handle the model "saved" event.
     *
     * @param Model  $model
     * @return void
     */
    public function saved($model)
    {
        //
    }

    /**
     * Handle the model "deleting" event.
     *
     * @param Model  $model
     * @return void
     */
    public function deleting($model)
    {
        //
    }

    /**
     * Handle the model "deleted" event.
     *
     * Its Lang will be automatically removed by database.
     *
     * @param Model  $model
     * @return void
     */
    public function deleted($model)
    {
        //
    }

    /**
     * Handle the model "restoring" event.
     *
     * @param Model  $model
     * @return void
     */
    public function restoring($model)
    {
        //
    }

    /**
     * Handle the model "restored" event.
     *
     * @param Model  $model
     * @return void
     */
    public function restored($model)
    {
        //
    }
}
