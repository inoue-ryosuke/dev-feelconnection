<?php

$now = date('Y-m-d H:i:s');


return [
		// この中にテーブル名を指定しているものは、一度トランケートする　php artisan db:seed --class=TruncateSeeder
		"truncate" => [
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
		],
		// 事前登録(店舗エリア・店舗・店舗区分) // php artisan db:seed --class=TenpoSeeder
		"pref_master" => [
			["cid" => 1,	"name"=>"北海道",	"country"=>"JPN"],
			["cid" => 2,	"name"=>"青森県",	"country"=>"JPN"],
			["cid" => 3,	"name"=>"岩手県",	"country"=>"JPN"],
			["cid" => 4,	"name"=>"宮城県",	"country"=>"JPN"],
			["cid" => 5,	"name"=>"秋田県",	"country"=>"JPN"],
			["cid" => 6,	"name"=>"山形県",	"country"=>"JPN"],
			["cid" => 7,	"name"=>"福島県",	"country"=>"JPN"],
			["cid" => 8,	"name"=>"茨城県",	"country"=>"JPN"],
			["cid" => 9,	"name"=>"栃木県",	"country"=>"JPN"],
			["cid" => 10,	"name"=>"群馬県",	"country"=>"JPN"],
			["cid" => 11,	"name"=>"埼玉県",	"country"=>"JPN"],
			["cid" => 12,	"name"=>"千葉県",	"country"=>"JPN"],
			["cid" => 13,	"name"=>"東京都",	"country"=>"JPN"],
			["cid" => 14,	"name"=>"神奈川県",	"country"=>"JPN"],
			["cid" => 15,	"name"=>"新潟県",	"country"=>"JPN"],
			["cid" => 16,	"name"=>"富山県",	"country"=>"JPN"],
			["cid" => 17,	"name"=>"石川県",	"country"=>"JPN"],
			["cid" => 18,	"name"=>"福井県",	"country"=>"JPN"],
			["cid" => 19,	"name"=>"山梨県",	"country"=>"JPN"],
			["cid" => 20,	"name"=>"長野県",	"country"=>"JPN"],
			["cid" => 21,	"name"=>"岐阜県",	"country"=>"JPN"],
			["cid" => 22,	"name"=>"静岡県",	"country"=>"JPN"],
			["cid" => 23,	"name"=>"愛知県",	"country"=>"JPN"],
			["cid" => 24,	"name"=>"三重県",	"country"=>"JPN"],
			["cid" => 25,	"name"=>"滋賀県",	"country"=>"JPN"],
			["cid" => 26,	"name"=>"京都府",	"country"=>"JPN"],
			["cid" => 27,	"name"=>"大阪府",	"country"=>"JPN"],
			["cid" => 28,	"name"=>"兵庫県",	"country"=>"JPN"],
			["cid" => 29,	"name"=>"奈良県",	"country"=>"JPN"],
			["cid" => 30,	"name"=>"和歌山県",	"country"=>"JPN"],
			["cid" => 31,	"name"=>"鳥取県",	"country"=>"JPN"],
			["cid" => 32,	"name"=>"島根県",	"country"=>"JPN"],
			["cid" => 33,	"name"=>"岡山県",	"country"=>"JPN"],
			["cid" => 34,	"name"=>"広島県",	"country"=>"JPN"],
			["cid" => 35,	"name"=>"山口県",	"country"=>"JPN"],
			["cid" => 36,	"name"=>"徳島県",	"country"=>"JPN"],
			["cid" => 37,	"name"=>"香川県",	"country"=>"JPN"],
			["cid" => 38,	"name"=>"愛媛県",	"country"=>"JPN"],
			["cid" => 39,	"name"=>"高知県",	"country"=>"JPN"],
			["cid" => 40,	"name"=>"福岡県",	"country"=>"JPN"],
			["cid" => 41,	"name"=>"佐賀県",	"country"=>"JPN"],
			["cid" => 42,	"name"=>"長崎県",	"country"=>"JPN"],
			["cid" => 43,	"name"=>"熊本県",	"country"=>"JPN"],
			["cid" => 44,	"name"=>"大分県",	"country"=>"JPN"],
			["cid" => 45,	"name"=>"宮崎県",	"country"=>"JPN"],
			["cid" => 46,	"name"=>"鹿児島県",	"country"=>"JPN"],
			["cid" => 47,	"name"=>"沖縄県",	"country"=>"JPN"],

		],
		// 事前登録(店舗エリア・店舗・店舗区分) // php artisan db:seed --class=TenpoSeeder
		"tenpo_master" => [
			[
					"tenpo_name" => "銀座", // 銀座（GNZ）、自由が丘（JYO）
					"tenpo_code" => "GNZ",
//					"tenpo_area_id" => ,    // 紐付する
					"monthly_avail_all" => 0,
					"monthly_avail_tenpo" => "-1",
					"monthly_free_exp" => "2020-01-01",
					"monthly_fname" => "",
					"mtenpo_explain" => "",
					"tenpo_memtype" => "",
					// シーダーで登録する
					"assign" => [
			            "tenpo_area_name" => "銀座エリア",
					    "tenpo_kubun_name" => "区分１",
					]
			],
			[
					"tenpo_name" => "自由が丘", // 銀座（GNZ）、自由が丘（JYO）
					"tenpo_code" => "JYO",
//					"tenpo_area_id" => ,    // 紐付する
					"monthly_avail_all" => 0,
					"monthly_avail_tenpo" => "-1",
					"monthly_free_exp" => "2020-01-01",
					"monthly_fname" => "",
					"mtenpo_explain" => "",
					"tenpo_memtype" => "",
//					"tenpo_memtype"         // 紐づけする
                    // シーダーで登録する
					"assign" => [
			            "tenpo_area_name"  => "自由が丘エリア",
						"tenpo_kubun_name" => "区分２",
					]
			],
		],
		// 登録単位にデータ配列を["table_name"=>[],"table_name"=>[]]と用意する。登録単位でループしてシーダーは登録する 
		// php artisan db:seed --class=CustSeeder
        "cust_master"=>[
			[
				"flg"=>"Y",
				"webmem"=>"Y",
				"login_pass"=>"",
				"res_permit_cnt"=>0,
				"gmo_credit"=>"abcdefg1234567890",
				"memberid"=>1,
				"memberid2"=>"1",
				"reason_admit"=>0,
				"admit_date"=>"2019-06-10",
				"admit_onday"=>0,
				"reason_rest"=>0,
				"rest_date"=>"2019-06-10",
				"quit"=>0,
				"quit_date"=>"2019-06-10",
				"readmit_date"=>"2019-06-10",
				"resumption"=>"2019-06-10",
				"recess"=>"2019-06-10",
				"idm"=>"",
				"rank"=>"Z",
				"memtype"=>1,
				"experience_flg"=>0,
				"store_id"=>1,
				"last_tenpo"=>1,
				"sex"=>"1",
				"kana"=>"テスト",
				"name"=>"テスト",
				"nickname"=>"テスト",
				"caution"=>"",
				"b_code"=>"",
				"b_name"=>"",
				"br_code"=>"",
				"br_name"=>"",
				"c_code"=>"",
				"c_name"=>"",
				"a_name"=>"",
				"a_number"=>"",
				"y_code"=>"",
				"y_number"=>"",
				"ya_name"=>"",
				"bank_type"=>1,
				"transfer_date"=>null,
				"b_year"=>2000,
				"b_month"=>1,
				"b_day"=>11,
				"kubun1_id"=>0,
				"kubun2_id"=>0,
				"kubun3_id"=>0,
				"kubun4_id"=>0,
				"kubun5"=>"0",
				"kubun6"=>"0",
				"kubun7"=>"0",
				"kubun8"=>"0",
				"hw"=>"1",
				"h_zip"=>"108-0073",
				"h_addr1"=>"東京都港区三田",
				"h_addr2"=>"99-9-9",
				"h_buil"=>"テストビル4F",
				"ng_h_hagaki"=>"",
				"h_tel1"=>"",
				"h_tel2"=>"",
				"h_tel3"=>"",
				"cellph1"=>"",
				"cellph2"=>"",
				"cellph3"=>"",
				"fax1"=>"",
				"fax2"=>"",
				"fax3"=>"",
				"c_mail"=>"nuzigepyo@eay.jp",
				"c_conf"=>1,
				"ng_c_mail"=>0,
				"pc_mail"=>"hobyo53@neko2.net",
				"pc_conf"=>1,
				"ng_pc_mail"=>0,
				"job_id"=>1,
				"jobkind_id"=>null,
				"syoujo_sub1"=>"",
				"syoujo_sub2"=>"",
				"t_change"=>"",
				"t_comment"=>"",
				"kana_g"=>"保護者氏名",
				"name_g"=>"ホゴシャシメイ",
				"fam_relation"=>"父",
				"h_zip_g"=>"",
				"h_addr1_g"=>"",
				"h_addr2_g"=>"",
				"h_buil_g"=>"",
				"ng_h_hagaki_g"=>"",
				"h_tel1_g"=>"",
				"h_tel2_g"=>"",
				"h_tel3_g"=>"",
				"cellph1_g"=>"",
				"cellph2_g"=>"",
				"cellph3_g"=>"",
				"fax1_g"=>"",
				"fax2_g"=>"",
				"fax3_g"=>"",
				"c_mail_g"=>"",
				"pc_mail_g"=>"",
				"memberflg"=>"N",
				"type_edit_date"=>null,
				"dm_list"=>"1,2",
				"w_report"=>"",
				"m_pub"=>0,
				"wpatern"=>0,
				"week_id"=>"0",
				"first_buy"=>null,
				"first_lesson"=>null,
				"last_lesson"=>null,
				"eraser_name"=>"",
				"reg_user"=>"999",
				"edit_tenpoid"=>0,
				"edit_date"=>null,
				"edit_time"=>"",
				"del_date"=>null,
				"del_time"=>"",
				"raiten"=>null,
				"reedit_date"=>null,
				"unpay_total"=>null,
				"mov_id"=>"",
				"mov_id_all"=>"",
				"info_boad"=>"",
				"mail_delivery"=>"",
				"no_mess"=>0,
				"reserve_lock"=>"N",
				"salt"=>"abcdefg1234567890",
				"password_change_datetime"=>"2019-06-10 16:02:43.563",
				"login_trial_count"=>0,
				"assign" => [
					"tenpo_name" => "銀座",
			        "cust_memtype" => [
	//                  "mid" => 1,
				        "type_name" => "マンスリーメンバー",
				        "status" => "ステータス名",
				        "flg" => "Y",
				        "rescnt_mem" => 0,
				        "resspan" => 0,
				        "attend_count" => 0,
				        "seq" => 0,
					    "mem_prod" => 0
				    ],
				],
			],
		]
];
