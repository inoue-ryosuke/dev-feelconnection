<?php

namespace App\Models;

use App\Exceptions\IllegalParameterException;
use Illuminate\Database\Eloquent\Model;

class TenpoKubun extends BaseModel
{
    //
    /**
     * @var string テーブル名
     */
    protected $table = 'tenpo_kubun';
    protected $primaryKey = 'tkid';
//    protected $table = 'tenpo_kubun__c';
//    protected $primaryKey = 'tkid__c';

    // 有効/無効フラグ
    const VALID = 1;
    const INVALID = 0;

    protected $fillable = [
		"tk_name",
    ];
    /*
    protected $fillable = [
		"tk_name__c",
    ];
    */
    const CREATED_AT = null;
    const UPDATED_AT = null;
    const DELETED_AT = null;
}
