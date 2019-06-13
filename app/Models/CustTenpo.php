<?php
namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use App\Exceptions\IllegalParameterException;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Libraries\Auth\Authenticatable as AuthenticatableTrait;

class CustTenpo extends BaseFormModel implements Authenticatable
{
    
    /** 有効/無効フラグ */
    const VALID = 1; //有効
    const INVALID = 0; //無効

    use AuthenticatableTrait;

    /**
     * @var string テーブル名
     */
    protected $table = 'cust_tenpo';
    protected $primaryKey = 'ctenpo_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    /**
     * 所属店舗情報を取得する
     * 
     * @param unknown $cid 会員ID
     * @return unknown 所属店舗情報
     */
    public static function getCustTenpo($cid)
    {
        $query = self::join('tenpo_master', 'tenpo_master.tid', '=', 'cust_tenpo.tenpo_id');
        $query->where('cust_tenpo.cid', $cid);
        $query->where('cust_tenpo.flg', self::VALID);
        return $query->get();
    }
}
