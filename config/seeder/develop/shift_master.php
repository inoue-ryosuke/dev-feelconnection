<?php

return [
    // shift_master(レッスンスケジュール)シーダー
    [
        "flg" => 'Y', //"Y： 有効 / N： 削除済み / C： 休講"
        "wflg" => 'Y', 	//"ネット予約公開 Y： 公開する / 空文字： 公開しない"
        "tlimit" => 0,//"ネット予約受付時間（～分前まで） 0： 制限なし"
        "tlimit_cancel" => 0, //	"ネット予約取消時間（～分前まで）0： 制限なし"
        "room" => 1, // "教室ID room.rid" todo 店舗に紐づく教室を取得する
        "gender" => 0,//	"性別制限 0： 制限なし / 1： 女性のみ / 2： 男性のみ"
        "useup_cnt"	=> 5, //予約回数増加（プラスワン）チケット使用予約数
        "shift_capa" => 10,//	定員（人数）
        "taiken_capa" => 10,//体験定員（体験人数）
        "taiken_mess" => 1, //"体験制限表示0： 非表示 / 1： 表示"
        "taiken_les_flg" => 1, //"体験予約 0： 不可 / 1： 可"
        "cancel" => 5, //消化件数
        "patern" => 0, //※未使用
        "wmid" => 0, //※未使用
        "cancel_mail" => 0, //"休講メール 0： 未配信 / 1： 配信済み"
        "reserve_lock" => 'Y',//"レッスン予約・キャンセル排他ロック Y： ロック中 / N： ロック中でない"

    ],

];