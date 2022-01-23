<?php

namespace WalkerChiu\Account\Models\Repositories;

use Illuminate\Support\Facades\App;
use WalkerChiu\Core\Models\Repositories\Repository;
use WalkerChiu\Core\Models\Repositories\RepositoryTrait;
use WalkerChiu\Core\Models\Services\PackagingFactory;

class ProfileRepository extends Repository
{
    use RepositoryTrait;

    protected $instance;



    /**
     * Create a new repository instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->instance = App::make(config('wk-core.class.account.profile'));
    }

    /**
     * @param Array  $data
     * @param Bool   $auto_packing
     * @return Array|Collection|Eloquent
     */
    public function list(array $data, $auto_packing = false)
    {
        $instance = $this->instance;
        $data = array_map('trim', $data);
        $repository = $instance->when(
                                        config('wk-account.onoff.morph-address')
                                        && !empty(config('wk-core.class.morph-address.address'))
                                    , function ($query) {
                                        return $query->with(['addresses', 'addresses.langs']);
                                    })
                                ->when($data, function ($query, $data) {
                                        return $query->unless(empty($data['id']), function ($query) use ($data) {
                                                    return $query->where('id', $data['id']);
                                                })
                                                ->unless(empty($data['user_id']), function ($query) use ($data) {
                                                    return $query->where('user_id', $data['user_id']);
                                                })
                                                ->unless(empty($data['language']), function ($query) use ($data) {
                                                    return $query->where('language', $data['language']);
                                                })
                                                ->unless(empty($data['timezone']), function ($query) use ($data) {
                                                    return $query->where('timezone', $data['timezone']);
                                                })
                                                ->unless(empty($data['currency_id']), function ($query) use ($data) {
                                                    return $query->where('currency_id', $data['currency_id']);
                                                })
                                                ->unless(empty($data['area']), function ($query) use ($data) {
                                                    return $query->where('area', $data['area']);
                                                })
                                                ->when(isset($data['gender']), function ($query) use ($data) {
                                                    return $query->where('gender', $data['gender']);
                                                })
                                                ->when(isset($data['notice_login']), function ($query) use ($data) {
                                                    return $query->where('notice_login', $data['notice_login']);
                                                })
                                                ->when(isset($data['note']), function ($query) use ($data) {
                                                    return $query->where('note', 'LIKE', "%".$data['note']."%");
                                                })
                                                ->when(isset($data['remarks']), function ($query) use ($data) {
                                                    return $query->where('remarks', 'LIKE', "%".$data['remarks']."%");
                                                });
                                })
                                ->orderBy('updated_at', 'DESC');

        if ($auto_packing) {
            $factory = new PackagingFactory(config('wk-account.output_format'), config('wk-account.pagination.pageName'), config('wk-account.pagination.perPage'));
            return $factory->output($repository);
        }

        return $repository;
    }

    /**
     * @param Profile       $instance
     * @param Array|String  $code
     * @return Array
     */
    public function show($instance, $code): array
    {
    }
}
