<?php

return [
    // lesson_master(レッスン設定)シーダー
    [
        "seq" => 1, // 表示順
        "lessonname" => "miyazatoStaff", //スタッフコード（ログインID）
        "lesson_class1" => 1, // レッスン分類1 ID lesson_class1.id
        "lesson_class2" => 1, // レッスン分類1 ID lesson_class1.id
        "lesson_class3" => 1, // レッスン分類1 ID lesson_class1.id
        "ticket_class" =>1, //適用チケット分類ID ticket_class.tid
        "res_count" => 1, //予約回数
        "reserve_limit" => 0, //ネット予約受付時間（～分前まで）0： 制限なし
        "cancel_limit" => 0, //ネット予約取消時間（～分前まで）0： 制限なし
        "flg" => 1, //0： 削除済み 1： 有効
        "mess_flg" => 1, // 体験予約 0： 不可 1： 可
        "memtype" => '1,10,3,11,4,2,12,8,6,13,7,14,15,16,17,18,19,20,21',// 予約可能会員種別ID cust_memtype.mid ※カンマ区切りで複数
    ],
    [
        "seq" => 1,
        "lessonname" => "miyazatoStaff", //スタッフコード（ログインID）
        "lesson_class1" => 1, // レッスン分類1 ID lesson_class1.id
        "lesson_class2" => 1, // レッスン分類1 ID lesson_class1.id
        "lesson_class3" => 1, // レッスン分類1 ID lesson_class1.id
        "ticket_class" =>1, //適用チケット分類ID ticket_class.tid
        "res_count" => 1, //予約回数
        "reserve_limit" => 0, //ネット予約受付時間（～分前まで）0： 制限なし
        "cancel_limit" => 0, //ネット予約取消時間（～分前まで）0： 制限なし
        "flg" => 1, //0： 削除済み 1： 有効
        "mess_flg" => 0, // 体験予約 0： 不可 1： 可
        "memtype" => '1,10,3,11,4,2,12,8,6,13,7,14,15,16,17,18,19,20,21',// 予約可能会員種別ID cust_memtype.mid ※カンマ区切りで複数
    ],


];