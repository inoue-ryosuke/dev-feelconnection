<?php 

		// 事前登録(店舗エリア・店舗・店舗区分) // php artisan db:seed --class=TenpoSeeder
return [
	    [
	            "tenpo_name" => "銀座 1st(GNZ 1st)",
	            "tenpo_code" => "111",
	//					"tenpo_area_id" => ,    // 紐付する
	            "monthly_avail_all" => 0,
	            "monthly_avail_tenpo" => "-1",
	            "monthly_free_exp" => "2020-01-01",
	            "monthly_fname" => "",
	            "mtenpo_explain" => "",
	            "tenpo_memtype" => "",
	            "address"   => "東京都中央区銀座5-8-9\nBINO銀座B2F",
	            "station"   => "地下鉄 銀座駅 A3出口 徒歩3分 / A5出口 徒歩2分",
				"timetable" => "月～木　7:00 - 23:00\n金曜日　定休日\n土日祝　10:00 - 19:30",
				"lat" => "35.670577",
				"lng" => "139.761979",
	            // シーダーで登録する
	            "assign" => [
	                "tenpo_area_name"  => "EAST",
	                "tenpo_kubun_name" => "直営",
	            ]
	    ],
	    [
	            "tenpo_name" => "自由が丘(JYO)",
	            "tenpo_code" => "122",
	//					"tenpo_area_id" => ,    // 紐付する
	            "monthly_avail_all" => 0,
	            "monthly_avail_tenpo" => "-1",
	            "monthly_free_exp" => "2020-01-01",
	            "monthly_fname" => "",
	            "mtenpo_explain" => "",
	            "tenpo_memtype" => "",
	            "address"   => "東京都目黒区自由が丘 1-25-20\nミュービル B1F",
	            "station"   => "自由が丘駅 正面口 徒歩3分",
				"timetable" => "月～木　7:00 - 23:00\n金曜日　定休日\n土日祝　10:00 - 19:30",
				"lat" => "35.6094793",
				"lat" => "139.6670202",
	            // シーダーで登録する
	            "assign" => [
	                "tenpo_area_name"  => "EAST",
	                "tenpo_kubun_name" => "FC店",
	            ]
		],
	    [
	            "tenpo_name" => "栄(SKE)",
	            "tenpo_code" => "110",
	//					"tenpo_area_id" => ,    // 紐付する
	            "monthly_avail_all" => 0,
	            "monthly_avail_tenpo" => "-1",
	            "monthly_free_exp" => "2020-01-01",
	            "monthly_fname" => "",
	            "mtenpo_explain" => "",
	            "tenpo_memtype" => "",
	            "address"   => "愛知県名古屋市中区栄4-3-7\nシエルブルー栄 2F",
	            "station"   => "名城線/東山線 栄駅 12番出口 徒歩3分",
	            "timetable" => "月～木　7:00 - 23:00\n金曜日　定休日\n土日祝　10:00 - 19:30",
				"lat" => "35.1685004",
				"lng" => "136.9094235",
	//					"tenpo_memtype"         // 紐づけする
	            // シーダーで登録する
	            "assign" => [
	                "tenpo_area_name"  => "WEST",
	                "tenpo_kubun_name" => "直営",
	            ]
	    ],
	    [
	            "tenpo_name" => "札幌(SPR)", 
	            "tenpo_code" => "141",
	//					"tenpo_area_id" => ,    // 紐付する
	            "monthly_avail_all" => 0,
	            "monthly_avail_tenpo" => "-1",
	            "monthly_free_exp" => "2020-01-01",
	            "monthly_fname" => "",
	            "mtenpo_explain" => "",
	            "tenpo_memtype" => "",
	            "address"   => "北海道札幌市中央区南一条西6-4-1\nあおばアネックス3F",
	            "station"   => "札幌市営地下鉄 大通駅 1番出口 徒歩2分\n札幌市電 西4丁目駅 徒歩4分",
				"timetable" => "月～木　7:00 - 23:00\n金曜日　定休日\n土日祝　10:00 - 20:30",
				"lat" => "43.0582897",
				"lng" => "141.3466324",
	//					"tenpo_memtype"         // 紐づけする
	            // シーダーで登録する
	            "assign" => [
	                "tenpo_area_name"  => "NORTH",
	                "tenpo_kubun_name" => "直営",
	            ]
	    ],
		
];