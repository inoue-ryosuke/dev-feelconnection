<?php

namespace App\Models;

use App\Exceptions\IllegalParameterException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Models\Constant\ReservationModalTablePrefix;

class ShiftMaster extends Model
{
    /** LessonFeelのテーブルサフィックス */
    const LF = '';

    /** 有効/無効フラグ */
    const VALID = 1; //有効
    const INVALID = 0; //無効
    const FLAG_VALID = 'Y';
    const FLAG_INVALID = 'N';
    const FLAG_CANCELLED = 'C';

    /** テーブル名 */
    protected $table = 'shift_master' . self::LF;
    const TABLE = 'shift_master' . self::LF;

    /** 主キー */
    protected $primaryKey = 'shiftid';

    protected $guarded = [
        'updatetime',
        'shift_type',
        'patern',
        'wmid'
    ];

    public $timestamps = false;


    /**
     * 予約モーダルで必要なレッスン情報を取得
     *
     * @param string $shiftIdHash
     * @return \App\Models\ShiftMaster|null
     */
    public static function getReservationModalResource(string $shiftIdHash) {
        $shiftMasterTableName = (new ShiftMaster)->getTable();
        $lessonMasterTableName = (new LessonMaster)->getTable();
        $tenpoMasterTableName = 'tenpo_master';
        $lessonClass1TableName = 'lesson_class1';
        $lessonClass2TableName = 'lesson_class2';
        $lessonClass3TableName = 'lesson_class3';
        $userMasterTableName = 'user_master';

        $query = self::from("{$shiftMasterTableName} AS SM")
        ->select([
            "SM.shiftid AS " . ReservationModalTablePrefix::SM . "shiftid",
            "SM.flg AS " . ReservationModalTablePrefix::SM . "flg",
            "SM.open_datetime AS " . ReservationModalTablePrefix::SM . "open_datetime",
            "SM.taiken_mess AS " . ReservationModalTablePrefix::SM . "taiken_mess",
            "SM.tlimit AS " . ReservationModalTablePrefix::SM . "tlimit",
            "SM.shift_date AS " . ReservationModalTablePrefix::SM . "shift_date",
            "SM.ls_st AS " . ReservationModalTablePrefix::SM . "ls_st",
            "SM.shift_capa AS " . ReservationModalTablePrefix::SM . "shift_capa",
            "SM.taiken_capa AS " . ReservationModalTablePrefix::SM . "taiken_capa",
            "SM.gender AS " . ReservationModalTablePrefix::SM . "gender",
            "SM.ls_menu AS " . ReservationModalTablePrefix::SM . "ls_menu",
            "SM.ls_et AS " . ReservationModalTablePrefix::SM . "ls_et",
            "SM.shift_tenpoid AS " . ReservationModalTablePrefix::SM . "shift_tenpoid",
            "SM.teacher AS " . ReservationModalTablePrefix::SM . "eacher",
            "LM.lid AS " . ReservationModalTablePrefix::LM . "lid",
            "LM.lesson_class1 AS " . ReservationModalTablePrefix::LM . "lesson_class1",
            "LM.lesson_class2 AS " . ReservationModalTablePrefix::LM . "lesson_class2",
            "LM.lesson_class3 AS " . ReservationModalTablePrefix::LM . "lesson_class3",
            "LC1.id AS " . ReservationModalTablePrefix::LC1 . "id",
            "LC1.name AS " . ReservationModalTablePrefix::LC1 . "name",
            "LC2.id AS " . ReservationModalTablePrefix::LC2 . "id",
            "LC2.name AS " . ReservationModalTablePrefix::LC2 . "name",
            "LC3.id AS " . ReservationModalTablePrefix::LC3 . "id",
            "LC3.name AS " . ReservationModalTablePrefix::LC3 . "_name",
            "TM.tid AS " . ReservationModalTablePrefix::TM . "tid",
            "TM.tenpo_name AS " . ReservationModalTablePrefix::TM . "tenpo_name",
            "UM.uid AS " . ReservationModalTablePrefix::UM . "uid",
            "UM.user_name AS " . ReservationModalTablePrefix::UM . "user_name",
            //"UM.path_img AS UM_path_img",
        ])
        ->join(
            "{$lessonMasterTableName} AS LM",
            "SM.ls_menu",
            '=',
            'LM.lid'
        )
        ->join(
            "{$tenpoMasterTableName} AS TM",
            "SM.shift_tenpoid",
            '=',
            'TM.tid'
        )
        ->join(
            "{$lessonClass1TableName} AS LC1",
            "LM.lesson_class1",
            '=',
            'LC1.id'
        )
        ->join(
            "{$lessonClass2TableName} AS LC2",
            "LM.lesson_class2",
            '=',
            'LC2.id'
        )
        ->leftJoin(
            "{$lessonClass3TableName} AS LC3",
            "LM.lesson_class3",
            '=',
            'LC3.id'
        )
        ->join(
            "{$userMasterTableName} AS UM",
            "SM.teacher",
            '=',
            'UM.uid'
        )
        ->where('SM.shiftid_hash', '=', $shiftIdHash);

        return $query->get()->first();
    }

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

        $now = Carbon::now()->format('Y-m-d H:i:s');
        $query = self::where(self::TABLE.'.ls_menu', $lid)
            ->where(self::TABLE.'.flg', self::FLAG_VALID)
            ->where(self::TABLE.'.ls_st',  '>=', $now)
            ->where(self::TABLE.'.tlimit',  '>=', $now);

        // 体験レッスンの条件を付加
        if ($trial === LessonMaster::TRIAL) {
            $query->where(self::TABLE.'.taiken_les_flg', self::VALID);
        }

        return $query->exists();
    }
}
