<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Account
    |--------------------------------------------------------------------------
    |
    */

    'header' => '我的帳戶',

    'user' => [
        'name'                 => '暱稱',
        'email'                => '電子信箱',
        'provider'             => 'Provider',
        'provider_id'          => 'Provider ID',
        'password'             => '密碼',
        'password_confirm'     => '密碼確認',
        'password_current'     => '目前密碼',
        'password_new'         => '新密碼',
        'password_new_confirm' => '新密碼確認',
        'is_enabled'           => '是否啟用',
        'created_at'           => '註冊時間',
        'login_at'             => '最後登入'
    ],

    'profile' => [
        'header'      => '我的檔案',
        'language'    => '偏好語言',
        'timezone'    => '偏好時區',
        'currency_id' => '偏好貨幣',
        'gender' => [
            'header'  => '性別',
            'options' => [
                'null'   => '無',
                'female' => '女性',
                'male'   => '男性'
            ]
        ],
        'notice_login' => '登入通知',
        'note'         => '備註',
        'remarks'      => '備註（後端）',
        'addresses'    => '地址',
        'images'       => '圖片'
    ],

    'list' => '會員清單',
    'edit' => '會員修改',

    'form' => [
        'account'     => '個人檔案',
        'certificate' => '帳號資料',
        'basicInfo'   => '基本資料',
        'preference'  => '偏好設定',
        'password'    => '變更密碼',

        'role_permission' => '角色權限'
    ],

    'delete' => [
        'header' => '刪除會員',
        'body'   => '確定要刪除這位會員嗎？'
    ]
];
