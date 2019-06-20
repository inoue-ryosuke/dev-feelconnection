<?php

namespace App\Models;

use App\Exceptions\IllegalParameterException;
use Illuminate\Database\Eloquent\Model;

class TenpoAreaMaster extends BaseModel
{
    //
    /**
     * @var string テーブル名
     */
    protected $table = 'tenpo_area_master';
    protected $primaryKey = 'id';
//    protected $table = 'tenpo_area_master__c';
//    protected $primaryKey = 'id__c';

    // 有効/無効フラグ
    const VALID = 1;
    const INVALID = 0;

    protected $fillable = [
		"flg",
		"name",
		"seq",
		"iname",
		"itxtcol",
		"ibgcol",
		"description"
    ];
    /*
    protected $fillable = [
		"flg__c",
		"name__c",
		"seq__c",
		"iname__c",
		"itxtcol__c",
		"ibgcol__c",
		"description__c"
	];*/
    const UPDATED_AT = null;
	const DELETED_AT = null;
}
