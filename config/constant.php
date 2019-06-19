<?php
use App\Models\Tax;
return [
    'fileSystemDriver' => env("FILE_SYSTEM_DRIVER", "public"),
    // UserAgentフィルターの設定
    'uaFilter' => env('APP_USER_AGENT_FILTER', false),
    // デフォルトのトークン有効期限
    'tokenExpiredDays' => 1, // 1日
    // サーバーエンコーディング
    'encoding' => 'UTF-8',
    // アプリケーション   取得限度数、オフセット数のデフォルト値
    'common' => [
        'limit'  => 100,
        'offset' => 0
    ],
    'loginRedirect' => [
        "customer" => 'top', // cust_master(会員マスタ)のログインルーティング
        "user_master" => 'top',// user_master(スタッフ)のログインルーティング
        "default" => 'top', // 会員でもスタッフでもない場合。会員のログインページへルーティング
    ],
    'app' => [
        'limit' => 50,
        'topLimit' => 20,
        'announceLimit' => 99,
        'exportLimit' => 500,
        'pageIndexLimit' => 10,
        'noticeLimit' => 99,
        'noticePerPageLimit' => 99,
        'historyPerPageLimit' => 50, // 履歴一覧表示数
        'itemLimit' => 1000,
        'stockMax' => 9999, // 在庫最大数
        'logMaxFiles' => 365,
        'csvMaxDownloadCount' => 1000,
        'storeUserRegisterTokenExpiredDays' => 7, // 7日
        'loginIdCookieExpiredDays' => 30, // ログインID Cookie有効日数

        'noImage' => 'images/1x1.png',
        'accountLockedCount' => env('APP_LOGIN_LOCKED_COUNT', 10),
        'dateFormat' => 'Y-m-d H:i:s',
        'displayDateFormat' => '%04d年%02d月%02d日%02d時%02d分',
        'nameFormat' => '%s %s',
        'loginFailedExpiredTime' => env('APP_LOGIN_FAILED_EXPIRED_TIME', 86400),
        'reauthExpiredTime' => 60, // 60秒
        // API-03向けトークン
        'qrTokenExpiredMinutes' => env('QR_TOKEN_EXPIRED_MIN', 30), // QR有効期限：30分
        // API-05向けトークン
        'prepaidTokenExpiredMinutes' => env('SETTLEMENT_TOKEN_EXPIRED_MIN', 30), // 30分
        'mailTokenExpiredMinutes' => 5, // メールアドレス変更有効期限
        'userGuideUrl' => '/guide/user/',
        'storeUserGuideUrl' => '/guide/store/guide.html',
        'prepaidUserGuideUrl' => '/guide/prepaid/guide.html',
        'defaultFontSize' => 11,//デフォルト文字サイズ
    ],
    'batch' => [
        'guideSync' => env('BATCH_GUIDE_SYNC'),
    	'goods_add_memory_limit' => '1G', //GoodsAddBatchのmemory_limit
    ],
    'html' => [
        'allowTags' => [
            'a',
        ],
        'closureTags' => [
            'a',
        ],
    ],
    'mailCheck' => [
        'regist' => [
            'subject'     => '登録認証メール',
            'from'        => 'toshifumi.kawai@xchange.jp',
            'expireHours' => '24',
        ],
        'passwdIssue' => [
            'subject'     => 'パスワード再発行認証メール',
            'from'        => 'toshifumi.kawai@xchange.jp',
            'expireHours' => '24',
        ],
        'mailReset' => [
            'subject'     => 'メールアドレス再設定認証メール',
            'from'        => 'toshifumi.kawai@xchange.jp',
            'expireHours' => '24',
        ],
    ],
];
