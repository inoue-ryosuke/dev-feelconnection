<?php
namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use App\Exceptions\IllegalParameterException;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Libraries\Auth\Authenticatable as AuthenticatableTrait;
use Carbon\Carbon;

class CustMemType extends BaseFormModel implements Authenticatable
//class CustMemType extends SalesBaseFormModel implements Authenticatable
{
//    use AuthenticatableTrait, ListingTrait, SoftDeletes, TokenizerTrait,UserTrait, UserStatusTrait;
    use AuthenticatableTrait;

    /**
     * @var string テーブル名
     */
    protected $table = 'cust_memtype';
    protected $primaryKey = 'mid';
//    protected $table = 'cust_memtype__c';
//    protected $primaryKey = 'mid__c';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
		"type_name",
		"status",
		"flg",
		"iname",
		"itxtcol",
		"ibgcol",
		"rescnt_mem",
		"resspan",
		"attend_count",
		"seq",
		"mem_prod",
	];
    const CREATED_AT = null;
    const UPDATED_AT = null;
	const DELETED_AT = null;

   /**
    * 会員種別レコードを返却する
    * 
    * @param unknown $mid 会員種別ID
    * @return unknown
    */
	public static function getMemTypeById($mid) {
        $record = self::find($mid);
        return $record;
    }
}
