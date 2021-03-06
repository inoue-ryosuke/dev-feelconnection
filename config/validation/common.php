<?php

return [
    /*
    |--------------------------------------------------------------------------
    | 共通
    |--------------------------------------------------------------------------
    */
    'errors' => [
        'required' => ':attributeは必須です。',
        'integer' => ':attributeは整数で入力してください。',
        'between' => [
            'numeric' => ':attributeは:min〜:maxまでにしてください。',
            'file'    => ':attributeは:min〜:max KBまでのファイルにしてください。',
            'string'  => ':attributeは:min〜:max文字にしてください。',
            'array'   => ':attributeは:min〜:max個までにしてください。',
        ],
        'file_required' => ':attributeは必須です。',
        'unique_filename' => '重複したファイル名が存在します。別名をアップロードしてください。',
        'same' => ':attributeと:otherが合致しません。',
        'check_token' => ':attributeが正しくありません。',
        'check_password' => ':attributeは現在のパスワードと合致しません。',
        'check_virus' => ':attributeはウィルス感染している可能性が高いため、アップロードできません。',
        'check_password_history' => '過去:count回前までに指定したパスワードは指定できません。',
        'required_if' => ':attributeは必須です。',
        'required_with' => ':valuesを入力する際は:attributeは必須です。',
        'required_without' => ':attributeは必須です。',
        'check_not_alone' => '最低1人以上の:typeを登録してください。',
        'check_user_type' => ':attributeに適切な値を指定してください。',
        'check_location_id' => ':attributeに適切な値を指定してください。',
        'check_mobile_login_id' => 'モバイルシステムで追加した:attributeを指定することはできません。',
        'unique_mobile_login_id' => 'すでに:attributeが使われています。別の:attributeを指定してください。',
        'file_name' => ':attributeに次の文字(\、/、:、,、;、?、*、"、<、>、{、})を使うことはできません。',
        'numeric' => ':attributeは数値で指定してください。',
        'min_version' => ':attributeは指定しているバージョンより新しいバージョンを指定してください。',
        'version' => ':attributeはメジャーバージョン.マイナーバージョンの書式で指定してください。',
        'digits' => ':attributeは:digits桁で指定してください。',
        'digits_between' => ':attributeは:min～:max桁で指定してください。',
        'unique' => ':attributeは既存と重複しない値を指定してください。',
        'unique_mobile_course_id_by_bank_id' => ':attributeはほかの商品と重複しない値を指定してください。',
        'unique_by_target_id' => ':attributeは対象ID内で重複しない値を指定してください。',
        'unique_by_grouping_target_id' => ':attributeは対象ID内で重複しない値を指定してください。',
        'alpha_num_dash' => ':attributeは半角英数およびハイフン・アンダースコアで指定してください。',
        'alpha_dash' => ':attributeは半角英数およびハイフン・アンダースコアで指定してください。',
        'alpha_num_mixed' => ':attributeは半角英数混合で指定してください。',
        'alpha_num' => ':attributeは半角英数字で指定してください。',
        'alpha' => ':attributeは半角英字で指定してください。',
        'min' => ':attributeは:min文字以上で指定してください。',
        'max' => ':attributeは:max文字以内で指定してください。',
        'date' => ':attributeを適切な日付で指定してください。',
        'check_date' => ':attributeを適切な日付で指定してください。',
        'check_datetime' => ':attributeを適切な日時で指定してください。',
        'html_tag' => ':attributeのHTMLに不正なタグが使用されています。',
        'html_tag_closure' => ':attributeのHTMLタグの開始/終了タグを確認してください。',
        'quote_closure' => ':attributeのクオート文字("/\')の対応を確認してください。',
        'no_javascript' => ':attributeにJavascriptを使用することはできません。削除してください。',
        'is_image_register' => '画像ファイルを登録してください。',
        'is_hankaku' => ':attributeを半角文字で入力してください。',
        'is_kana_zenkaku' => ':attributeをカタカナ全角文字で入力してください。',
        'is_zenkaku' => ':attributeは全角文字で入力してください。',
        'upload_failed' => 'ファイルが不正です。再アップロードしてください。',
        'no_binary' => 'ファイル形式が不正です。テキストファイルをアップロードしてください。',
        'email' => ':attributeの書式が不正です。',
        'phone' => ':attributeの書式が不正です。',
        'telephone' => ':attributeの書式が不正です。',
        'confirmed' => ':attributeが一致しません。',
        'regex' => ':attributeは正しい値を入力してください。',
        'exists' => ':attributeに誤りがあります。',
        'after' => ':attributeは開始日時以降で指定してください。',
        'display_priority.max' => ':attributeは:max以内で指定してください。',
        'url' => ':attributeに適切なURLを入力してください。',
        'is_date_after_now' => ':attributeは現在日時よりあとの日時を指定してください。',
        'is_zip' => ':attributeを指定してください。',
        'before' => ':attributeは現在より過去の日時で指定してください。',
        'line_max' => ':attributeは１行あたり:max文字以下で入力してください。',
        'row_max' => ':attributeは:max行以下で入力してください。',
        //カンマ区切り文字列をgetPayloadで配列化した際の判定用　TBD:日本語に置き換え
        'size' => [
            'numeric' => 'The :attribute must be :size.',
            'file' => 'The :attribute must be :size kilobytes.',
            'string' => 'The :attribute must be :size characters.',
            'array' => ':attributeは:size項目のパラメータが必要です.',
        ],
        'zenkaku_max' => ':attributeは全角:max文字以内で入力してください。',
        'duplicated_date' => '利用期間は重複して設定されています。',
        'not_campaign_pin' => 'キャンペーン向けプリペイド番号をポイント追加することはできません。',
        'not_payment_pin' => '販売向けプリペイド番号をキャンペーン向けに登録することはできません。',
        'not_payment_management_code' => '販売向け管理番号をキャンペーン向けに登録することはできません。',
        'sort_column' => ':attributeは適切な項目を指定してください。',
        'login_id_check' => 'すでに登録されているIDです。他のIDを入力してください。',
        'event_name_check' => 'すでに登録されているイベント名です。他のイベント名を入力してください。',
        'jan_code_check' => 'すでに登録されているJANコードです。他のJANコードを入力するか、空にしてください。',
        'after_or_equal' => ':attributeは開始日以前の日付で指定してください'
    ]


];
