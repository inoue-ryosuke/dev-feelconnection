<?php

return [
    // user_master(スタッフ)シーダー
    [
        "seq" => 1,
        "login_id" => "miyazatoStaff", //スタッフコード（ログインID）
        "login_pass" => "password", //ログインパスワード
        "prev_id" => 1, //権限prev_select.ps_id
        "alive_flg" => "Y", // Y： 有効 / N： 退職済み
        "user_name" => "miyazato", // スタッフ名
        "teacher" => 1, // 0： 先生でない / 1： 先生
        "salt" => "",// パスワードソルト
    ],
    [
        "seq" => 2,
        "login_id" => "higumaStaff", //スタッフコード（ログインID）
        "login_pass" => "password", //ログインパスワード
        "prev_id" => 1, //権限prev_select.ps_id
        "alive_flg" => "Y", // Y： 有効 / N： 退職済み
        "user_name" => "higuma", // スタッフ名
        "teacher" => 1, // 0： 先生でない / 1： 先生
        "salt" => "",//パスワードソルト
    ],


];