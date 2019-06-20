<?php

return [
    // lesson_master(レッスン設定)シーダー
    [
        "seq" => 1, // 表示順
        "ticket_class" =>1, //適用チケット分類ID ticket_class.tid TODO ticket_classから情報を取得するように調整
        "res_count" => 5, //予約回数
        "reserve_limit" => 10, //ネット予約受付時間（～分前まで）0： 制限なし
        "cancel_limit" => 30, //ネット予約取消時間（～分前まで）0： 制限なし
        "flg" => 1, //0： 削除済み 1： 有効
        "mess_flg" => 1, // 体験予約 0： 不可 1： 可
    ],
    [
        "seq" => 1,
        "ticket_class" =>1, //適用チケット分類ID ticket_class.tid
        "res_count" => 1, //予約回数
        "reserve_limit" => 0, //ネット予約受付時間（～分前まで）0： 制限なし
        "cancel_limit" => 0, //ネット予約取消時間（～分前まで）0： 制限なし
        "flg" => 1, //0： 削除済み 1： 有効
        "mess_flg" => 0, // 体験予約 0： 不可 1： 可
    ],


];