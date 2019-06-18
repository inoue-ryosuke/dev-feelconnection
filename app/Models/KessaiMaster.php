<?php
namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use App\Exceptions\IllegalParameterException;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Libraries\Auth\Authenticatable as AuthenticatableTrait;

class KessaiMaster extends BaseFormModel implements Authenticatable
{
    use AuthenticatableTrait;

    /** 有効/無効フラグ */
    const VALID = 1; //有効
    const INVALID = 0; //無効

    const TYPE_FRONT_ADMIN   = 1;// フロント、管理画面で表示・選択できる
    const TYPE_FRONT_DISABLE = 2;// フロント、管理画面で表示・選択できるがフロントで選択はできない
    const TYPE_ADMIN         = 3;// 管理でのみ表示・選択できる

    /**
     * @var string テーブル名
     */
    protected $table = 'kessai_master';
    protected $primaryKey = 'kessai_id';

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
     * 決済方法配列を返却する
     * 
     * @param array $type 表示タイプ
     * @return unknown
     */
    public static function getKessaiList($type = [])
    {
        $query = self::where('flg', self::VALID);
        $query->whereIn('type', $type);
        return $query->get();
    }
}
