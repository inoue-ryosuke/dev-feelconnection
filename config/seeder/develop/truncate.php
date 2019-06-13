<?php 

return [
	// この中にテーブル名を指定しているものは、一度トランケートする　php artisan db:seed --class=TruncateSeeder
    "account_arai", // 洗い替えファイル履歴
    "account_cust", // 会員別継続課金情報
    "account_laundering", // 継続課金ファイル履歴
    "account_prod", //継続課金商品
    "account_set", //継続課金状態
    "account_tlist", //継続課金履歴
    "ana_tally_lm", //レッスン別集計
    "ana_tally_t", //店舗集計
    "ana_tally_te", //先生別集計
    "ana_tally_tl", //レッスン／先生別集計
    "application", //休会／復会／解約申請
    "automember_lock", //会員番号採番ロック
    "baitai_master", //広告媒体
    "cancel_hist", //レッスンキャンセル履歴
    "card", //クレジット種別
    "card_oth", //その他種別
    "class_tenpo",	//商品分類表示
    "club_fee",	//会費
    "contract_change_hist",	//契約変更履歴
    "cust_kubun",	//顧客項目
    "cust_kubun1",	//顧客項目1
    "cust_kubun2",	//顧客項目2
    "cust_kubun4",	//顧客項目4
    "cust_master",	//会員
    "cust_memtype",	//会員種別
    "cust_set",	//項目表示設定
    "cust_syokugyo",	////顧客項目3
    "customer_img",	//会員写真
    "gtm_datalayer",	//データレイヤー出力用
    "keihi",	//小口現金
    "lesson_class1",	//レッスン分類1
    "lesson_class2",	//レッスン分類2
    "lesson_class3",	//レッスン分類3
    "lesson_comment",	//レッスンコメント
    "lesson_master",	//レッスン設定
    "lesson_master_tenpo",	//店舗レッスン設定
    "log_dataaccess",	//アクセス履歴
    "mail_send_list",	//販促メール配信履歴
    "mail_template",	//メールテンプレート
    "member_hist",	//会員種別履歴
    "mov_master",	//通知メール設定
    "one_time_coupon",	//ワンタイムクーポン
    "order_item",	//売上明細
    "order_lesson",	//レッスン予約
    "order_list",	//売上
    "perf_dtl",	//日報詳細
    "perf_log",	//日報更新履歴
    "preparation",	//開店時釣銭
    "prev_select",	//権限
    "prod_master",	//商品
    "prod_tenpo",	//商品表示
    "prodclass1_master",	//商品分類
    "rank",	//ランク
    "reason_admit",	//入会理由
    "reason_quit",	//解約理由
    "reason_rest",	//休会理由
    "recept_class",	//対応分類
    "recept_list",	//対応記録
    "regist_auth",	//メールアドレス登録管理
    "room",	//教室
    "schedule",	//自動変更処理スケジュール
    "security_settings",	//セキュリティポリシー設定
    "shift_master",	//レッスンスケジュール
    "shop_goods",	//ネット商品
    "shop_group_title",	//ネット商品グループ
    "shop_order",	//ネット売上
    "shop_order_tmp",	//ネット売上状態
    "site_h_conf",	//メニュー表示設定（本部）
    "site_t_conf",	//メニュー表示設定（店舗）
    "staff_sec",	//セキュリティ設定
    "stock_item",	//入出庫明細
    "stock_list",	//入出庫
    "stock_set_confirm",	//店舗他店在庫表示設定
    "stock_supplier",	//仕入先
    "stocktaking",	//棚卸
    "tbl_history",	//操作履歴
    "tbl_monthly_kiyaku",	//マンスリー規約
    "tbl_receipt",	//レシート設定
    "tbl_shop_setting",	//ネットショップ設定
    "tenpo_area_master",	//店舗エリア
    "tenpo_goal",	//目標
    "tenpo_kubun",	//店舗区分
    "tenpo_master",	//店舗
    "tenpo_perf",	//日報／点検
    "ticket_class",	//チケット分類
    "ticket_master",	//チケット
    "tmp_number",	//仮会員番号採番
    "user_master",	//スタッフ
    "zip_code"	//郵便番号データ
];