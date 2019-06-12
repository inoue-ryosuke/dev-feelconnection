<?php

return [
    /*
     |--------------------------------------------------------------------------
     | アカウント登録
     |--------------------------------------------------------------------------
     */
     'accountRegist' => [
         'rules' => [
            'mail_address' => 'required|email|unique:cust_master,pc_mail', 
            'type' => 'integer|between:1,3', 
         ],
         'attributes' => [
            'mail_address' => 'メールアドレス', 
         ]
     ]
];
