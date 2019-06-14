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
	//					"tenpo_memtype"         // 紐づけする
	            // シーダーで登録する
	            "assign" => [
	                "tenpo_area_name"  => "NORTH",
	                "tenpo_kubun_name" => "直営",
	            ]
	    ],
		
];