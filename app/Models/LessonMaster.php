<?php

namespace App\Models;

use App\Exceptions\IllegalParameterException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class LessonMaster extends BaseFormModel
{
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

    public $timestamps = false;

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
            ->where(self::TABLE.'.flg', self::VALID)
            ->where(self::TABLE.'.reserve_limit',  '>=', Carbon::now()->format('Y-m-d H:i:s'));

        // 体験レッスンの条件を付加
        if ($trial === self::TRIAL) {
            $query->where(self::TABLE.'.mess_flg', $trial);
        }

        return $query->exists();
    }
}
