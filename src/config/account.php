<?php

/**
 * @license MIT
 * @package WalkerChiu\Account
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Switch association of package to On or Off
    |--------------------------------------------------------------------------
    |
    | When you set someone On:
    |     1. Its Foreign Key Constraints will be created together with data table.
    |     2. You may need to change the corresponding class settings in the config/wk-core.php.
    |
    | When you set someone Off:
    |     1. Association check will not be performed on FormRequest and Observer.
    |     2. Cleaner and Initializer will not handle tasks related to it.
    |
    | Note:
    |     The association still exists, which means you can still access related objects.
    |
    */
    'onoff' => [
        'user' => 1,

        'currency'      => 0,
        'morph-address' => 0,
        'morph-comment' => 0,
        'morph-link'    => 0,
        'payment'       => 0,
        'role'          => 0
    ],

    /*
    |--------------------------------------------------------------------------
    | Output Data Format from Repository
    |--------------------------------------------------------------------------
    |
    | null:                  Query.
    | query:                 Query.
    | collection:            Query collection.
    | collection_pagination: Query collection with pagination.
    | array:                 Array.
    | array_pagination:      Array with pagination.
    |
    */
    'output_format' => null,

    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    |
    */
    'pagination' => [
        'pageName' => 'page',
        'perPage'  => 15
    ],

    /*
    |--------------------------------------------------------------------------
    | Command
    |--------------------------------------------------------------------------
    |
    | Location of Commands.
    |
    */
    'command' => [
        'cleaner' => 'WalkerChiu\Account\Console\Commands\AccountCleaner'
    ],

    /*
    |--------------------------------------------------------------------------
    | Default setting
    |--------------------------------------------------------------------------
    |
    | More default values can be set in config/wk-core.php
    |
    */
    'notice_login' => 0,

    /*
    |--------------------------------------------------------------------------
    | Edit table "users"
    |--------------------------------------------------------------------------
    |
    | Set to true if you want to modify this table.
    |
    */
    'users' => [
        'edit' => true
    ]
];
