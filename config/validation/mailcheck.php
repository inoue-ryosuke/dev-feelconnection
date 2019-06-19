<?php

return [
    /*
     |--------------------------------------------------------------------------
     | アカウント登録
     |--------------------------------------------------------------------------
     */
     'accountRegist' => [
         'rules' => [
            'mail_address' => 'bail|required|email|unique:cust_master,pc_mail', 
         ],
         'attributes' => [
            'mail_address' => 'メールアドレス', 
         ]
     ],
    /*
     |--------------------------------------------------------------------------
     | パスワード再発行
     |--------------------------------------------------------------------------
     */
     'passwdIssue' => [
         'rules' => [
            'mail_address' => 'bail|required|email|exists:cust_master,pc_mail', 
         ],
         'attributes' => [
            'mail_address' => 'メールアドレス', 
         ]
     ],
    /*
     |--------------------------------------------------------------------------
     | メールアドレス再設定
     |--------------------------------------------------------------------------
     */
     'mailaddressReset' => [
         'rules' => [
            'mail_address' => 'bail|required|email|exists:cust_master,pc_mail', 
         ],
         'attributes' => [
            'mail_address' => 'メールアドレス', 
         ]
     ],
];
