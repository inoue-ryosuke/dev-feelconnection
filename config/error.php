<?php

return [
    ////////////////////////// エラーコード向け設定
    'code' => [
        'DEBUG' => '0',
        'INFO' => '1',
        'WARNING' => '2',
        'ERROR' => '3',
        'FATAL' => '4',
        'NOTICE' => '5',
    ],
    //0 ・・・ 共通サーバ機能
    //1 ・・・ インターネット向け中間サーバ機能
    //2 ・・・ アプリ基本機能サーバ機能
    //3 ・・・ 個別サービスサーバ機能
    //9 ・・・ サービス固有アプリ
    'layerCode' => '3', //個別サービスサーバなのでこれ
    ///////////////////////////

    'systemError' => 'システムエラーが発生しました。',
    'connectionFailed' => '接続に失敗しました。',
    'api' => [
        'tokenExpired' => '有効期限が切れました。再度ログインし直してください。',
        'badRequest' => 'リクエストが不正です。',
        'illigalToken' => '要求されたトークンが不正です',
        'notAcceptable' => 'リクエストを受理できませんでした。',
        'notFound' => 'APIが見つかりません。',
        'header' => 'ヘッダーが不足しています。',
        'invalidHeader' => 'ヘッダーが不正です。',
        'config' => 'APIキーが無効です。',
        'illFormedPayload' => 'リクエストの内容が不正です。',
        'illFormedClientResponse' => 'レスポンス内容が不正です',
        'illegalParameter' => 'パラメータが不正です。',
        'methodNotAllowed' => '許可されていない問い合わせです。',
        'outOfOrder' => 'PINが売り切れました。',
        'unsupportedOperation' => 'サポートされていない操作です。',
        'failedDependency' => '依存関係が不正です。',
        'unauthorized' => '認証に失敗しました。',
        'required' => '入力値が不足しています(%s)。',
        'defaultRequired' => '入力値が不足しています。',
        'serviceUnavailable' => 'メンテナンスのため利用できません。',
        'partialServiceUnavailable' => 'メンテナンスのため一部利用できません。',
        'paymentServiceUnavailable' => '購入予約に失敗しました。しばらく時間を置いてから、再度ご購入をお試しください。',
        'notFoundAuthInfo' => '認証情報がありません。',
        'permissionDenied'  =>  'この機能を使用する権限がありません',
        'alreadyAggregated'  =>  '既に集計済みの検品情報が含まれています。',
        'maxFileLength'      => 'ファイル名は50文字以内にしてください',
        'unsupportedFile' => 'サポートされていないファイルです。',
    ],

    'app' => [
        'unauthorized' => '認証に失敗しました。',
        'typingError' => 'ログインＩＤまたはパスワードに誤りがあります。',
        'accountLocked' => 'アカウントがロックされています。',
        'unauthorizedAccount' => 'ログインが許可されていないアカウントです。',
        'lockStatusAnnounce' => '回ログインに失敗するとアカウントがロックされます。',
    ],

    'user' => [
        'notFound' =>'ユーザー情報が見つかりません。'
    ]

];
