<?php
namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use App\Exceptions\IllegalParameterException;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Libraries\Auth\Authenticatable as AuthenticatableTrait;

class Schedule extends BaseFormModel implements Authenticatable
//class Schedule extends SalesBaseFormModel implements Authenticatable
{
    
    /** 有効/無効フラグ */
    const VALID = 1; //有効
    const INVALID = 0; //無効

    use AuthenticatableTrait;

    /**
     * @var string テーブル名
     */
    protected $table = 'schedule';
    protected $primaryKey = 'sc_id';
//    protected $table = 'schedule__c';
//    protected $primaryKey = 'sc_id__c';
    const CREATED_AT = null;
    const UPDATED_AT = null;
    const DELETED_AT = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
				"sc_soid",
				"sc_flg",
				"appli_flg",
				"sc_cid",
				"sc_date",
				"sc_memtype",
				"sc_tenpo",
				"sc_regdate",
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

}
