<?php

namespace App\Models;

use App\Exceptions\IllegalParameterException;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use App\Libraries\Auth\Authenticatable as AuthenticatableTrait;

class LessonMaster extends BaseFormModel implements Authenticatable
{
    use AuthenticatableTrait;
    /** Salesforceのテーブルサフィックス(__c) */
    const SF = '';

    /** 体験レッスンフラグ */
    const TRIAL = 1; // 1は体験レッスン

    /** 有効/無効フラグ */
    const VALID = 1; //有効
    const INVALID = 0; //無効

    /** テーブル名 */
    protected $table = 'lesson_master' . self::SF;
    const TABLE = 'lesson_master' . self::SF;

    /** 主キー */
    protected $primaryKey = 'lid';

    protected $guarded = [
        'cid',
        'lid',
    ];

    const CREATED_AT = null;
    const UPDATED_AT = null;
    const DELETED_AT = null;

    /**
     * レッスンの予約が可能か判定
     * @param $lid
     * @param $trial
     * @return
     */
    public static function isReservableLesson($lid, $trial=null) {
        // 空もしくはnullの場合
        if(empty($lid) || is_null($lid)) {
            return false;
        }

        $query = self::where(self::TABLE.'.lid', $lid)
            ->where(self::TABLE.'.flg', self::VALID);
            // TODO reserve_limitには何分前(実際の値は数値の10)という情報しかないので、現在時刻との比較ができない。要API設計修正。
//            ->where(self::TABLE.'.reserve_limit',  '>=', Carbon::now()->format('Y-m-d H:i:s'));

        // 体験レッスンの条件を付加
        if ($trial === self::TRIAL) {
            $query->where(self::TABLE.'.mess_flg', $trial);
        }

        return $query->exists();
    }
}
