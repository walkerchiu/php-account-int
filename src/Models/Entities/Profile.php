<?php

namespace WalkerChiu\Account\Models\Entities;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var Array
     */
    protected $fillable = [
        'user_id',
        'language', 'timezone', 'currency_id',
        'gender', 'notice_login',
        'note', 'remarks',
        'addresses', 'images'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var Array
     */
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var Array
     */
    protected $casts = [
        'notice_login' => 'boolean',
        'addresses'    => 'json',
        'images'       => 'json'
    ];



    /**
     * Create a new instance.
     *
     * @param Array  $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        $this->table = config('wk-core.table.account.profiles');

        parent::__construct($attributes);
    }

    /**
     * @return String
     */
    public function languageText(): string
    {
        return config('wk-core.class.core.language')::all()[$this->language];
    }

    /**
     * @return String
     */
    public function genderText(): string
    {
        switch ($this->gender) {
            case "woman":
                return trans('php-account::system.profile.gender.options.woman');
            case "man":
                return trans('php-account::system.profile.gender.options.man');
            default:
                return '-';
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(config('wk-core.class.user'), 'user_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currency()
    {
        return $this->belongsTo(config('wk-core.class.currency.currency'), 'currency_id', 'id');
    }

    /**
     * @param String  $type
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function addresses($type = null)
    {
        return $this->morphMany(config('wk-core.class.morph-address.address'), 'morph')
                    ->when($type, function ($query, $type) {
                                return $query->where('type', $type);
                            });
    }

    /**
     * @param String  $type
     * @param String  $category
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function links($type = null, $category = null)
    {
        return $this->morphMany(config('wk-core.class.morph-link.link'), 'morph')
                    ->when($type, function ($query, $type) {
                                return $query->where('type', $type);
                            })
                    ->when($category, function ($query, $category) {
                                return $query->where('category', $category);
                            });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function payments()
    {
        return $this->morphMany(config('wk-core.class.payment.payment'), 'morph');
    }
}
