<?php

return [
    /*
     |--------------------------------------------------------------------------
     | キャスト認証
     |--------------------------------------------------------------------------
    */
    'auth' => [
         'rules' => [
         ],
         'attributes' => [
         ]
    ],
    'auth_user' => [
         'rules' => [
            'cid' => 'required|integer',
         ],
         'attributes' => [
            'cid' => '会員ID',
         ]
    ],
    'dm_update' => [
        'rules' => [
            'cid'     => 'required|integer',
            'dm_list' => 'required|array|size:5',
            'dm_list.0' => "nullable|string|in:1",
            'dm_list.1' => "nullable|string|in:2",
            'dm_list.2' => "nullable|string|in:3",
            'dm_list.3' => "nullable|string|in:4",
            'dm_list.4' => "nullable|string|in:5",
            'pc_conf' => 'required|integer|in:0,1'
        ],
        'attributes' => [
            'cid' => '会員ID',
            'dm_list' => '案内メール設定',
            'pc_conf' => '予約確認メール',
        ]
    ]
];
