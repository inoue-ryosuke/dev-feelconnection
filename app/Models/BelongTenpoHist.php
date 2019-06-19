<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use App\Exceptions\IllegalParameterException;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Libraries\Auth\Authenticatable as AuthenticatableTrait;

class BelongTenpoHist extends BaseModel implements Authenticatable
{
    use AuthenticatableTrait;
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
