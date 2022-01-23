<?php

namespace WalkerChiu\Account\Models\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;
use WalkerChiu\Core\Models\Entities\DateTrait;
use WalkerChiu\Core\Models\Entities\UuidTrait;
use WalkerChiu\MorphRegistration\Models\Entities\RegistrationTrait;

trait UserTrait
{
    use DateTrait;
    use RegistrationTrait;
    use SoftDeletes;
    use UuidTrait;

    /**
     * @param String  $attr
     * @return Bool
     */
    public function hasAttribute(string $attr): bool
    {
        return array_key_exists($attr, $this->attributes);
    }

    /**
     * @param $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfEnabled($query)
    {
        return $query->where('is_enabled', 1);
    }

    /**
     * @param $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfDisabled($query)
    {
        return $query->where('is_enabled', 0);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function profile()
    {
        return $this->hasOne(config('wk-core.class.account.profile'),
                             'user_id',
                             'id');
    }
}
