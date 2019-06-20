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
            'dm_list.1' => "nullable|string|in:''",
            'dm_list.2' => "nullable|string|in:''",
            'dm_list.3' => "nullable|string|in:''",
            'dm_list.4' => "nullable|string|in:5",
            'pc_conf' => 'required|integer|in:0,1'
        ],
        'attributes' => [
            'cid' => '会員ID',
            'dm_list' => '案内メール設定',
            'pc_conf' => '予約確認メール',
        ]
    ],
    'store' => [
        'rules' => [
            'type' => 'required|integer|between:1,3',// 1:入力・確認、２：変更、３：登録
            'name' => 'required|string',
            'kana' => 'required|string',
            'b_year' => 'required|integer',
            'b_month' => 'required|integer',
            'b_day' => 'required|integer',
            'sex' => 'required|integer|between:1,2', //１：男性、２：女性
            'h_zip' => 'required|string',
            'h_pref' => 'required|string',
            'h_addr' => 'required|string',
            'h_tel' => 'required|string',
            'dm' => 'required|integer', // 0：可　1：不可
            'pc_conf' => 'required|integer', //0：可　1：不可
            'login_pass' => 'required|string',
            'login_pass_confirmation' => 'required|string',
            'campaign_code' => 'required|string',
            'pc_mail' => 'required|string',
        ],
        'attributes' => [
            'type' => '処理タイプ',// 1:入力・確認、２：変更、３：登録
            'name' => '名前（漢字）',
            'kana' => '名前（カタカナ）',
            'b_year' => '生年月日（年）',
            'b_month' => '生年月日（月）',
            'b_day' => '生年月日（日）',
            'sex' => '性別', //１：男性、２：女性
            'h_zip' => '郵便番号',
            'h_pref' => '都道府県',
            'h_addr' => '番地、建物名',
            'h_tel' => '電話番号・市外局番',
            'dm' => 'ご案内メール受信許可', // 0：可　1：不可
            'pc_conf' => '事前予約案内メール受信許可', //0：可　1：不可
            'login_pass' => 'パスワード',
            'login_pass_confirmation' => 'パスワード（確認）',
            'campaign_code' => 'キャンペーンコード',
            'pc_mail' => 'メールアドレス',
        ]
    ],
];
