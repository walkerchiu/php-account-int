<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Account
    |--------------------------------------------------------------------------
    |
    */

    'header' => 'My Account',

    'user' => [
        'name'                 => 'Name',
        'email'                => 'Email',
        'provider'             => 'Provider',
        'provider_id'          => 'Provider ID',
        'password'             => 'Password',
        'password_confirm'     => 'Password Confirm',
        'password_current'     => 'Current Password',
        'password_new'         => 'New Password',
        'password_new_confirm' => 'New Password Confirm',
        'is_enabled'           => 'Is Enabled',
        'created_at'           => 'Registed At',
        'login_at'             => 'Last Login'
    ],

    'profile' => [
        'header'      => 'Profile',
        'language'    => 'Language',
        'timezone'    => 'Timezone',
        'currency_id' => 'Currency',
        'gender' => [
            'header'  => 'Gender',
            'options' => [
                'null'   => 'None',
                'female' => 'Female',
                'male'   => 'Male'
            ]
        ],
        'notice_login' => 'Notify when login',
        'note'         => 'Note',
        'remarks'      => 'Remarks',
        'addresses'    => 'Addresses',
        'images'       => 'Images'
    ],

    'list' => 'Member List',
    'edit' => 'Edit Member',

    'form' => [
        'account'     => 'Account',
        'certificate' => 'Certificate',
        'basicInfo'   => 'Profile',
        'preference'  => 'Preference',
        'password'    => 'Change Password',

        'role_permission' => 'Role & Permission'
    ],

    'delete' => [
        'header' => 'Delete Member',
        'body'   => 'Are you sure you want to delete this member?'
    ]
];
