<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use App\Exceptions\IllegalParameterException;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Libraries\Auth\Authenticatable as AuthenticatableTrait;

class BelongTenpoHist extends BaseModel implements Authenticatable
{
    use AuthenticatableTrait;

    const TABLE = 'belong_tenpo_hist__c';
    const TID = 'tid__c';
    const UID = 'uid__c';
    const FLAG = 'flg__c';
    const TABLE_TID = self::TABLE.'.'.self::TID;
    const TABLE_UID = self::TABLE.'.'.self::UID;
    const TABLE_FLAG = self::TABLE.'.'.self::FLAG;
    /**
     * @var string テーブル名
     */
    protected $table = 'belong_tenpo_hist__c';
    protected $primaryKey = 'id__c';
    protected $fillable = [

    ];

    // テーブルにtimestamps系カラムがないのでfalseを設定
    public $timestamps = false;

    const VALID = 1;

}
