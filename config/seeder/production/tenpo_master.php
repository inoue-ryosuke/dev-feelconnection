<?php 

		// 事前登録(店舗エリア・店舗・店舗区分) // php artisan db:seed --class=TenpoSeeder
return [
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
];