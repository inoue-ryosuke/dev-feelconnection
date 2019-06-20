<?php
namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use App\Exceptions\IllegalParameterException;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Libraries\Auth\Authenticatable as AuthenticatableTrait;
use App\Models\Constant\ScheduleScFlg;

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
    /*
    protected $fillable = [
				"sc_soid__c",
				"sc_flg__c",
				"appli_flg__c",
				"sc_cid__c",
				"sc_date__c",
				"sc_memtype__c",
				"sc_tenpo__c",
				"sc_regdate__c",
    ];*/
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    /**
     * 未来の会員種別一覧取得
     *
     * @param int $customerId 会員ID
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getFutureMemberTypeList(int $customerId) {
        return self::where('sc_cid', '=', $customerId)
        ->where('sc_flg', '=', ScheduleScFlg::BEFORE_EXECUTION)
        ->orderBy('sc_date', 'asc')
        ->get();
    }
}
