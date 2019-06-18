<?php

namespace App\Models;

use App\Exceptions\IllegalParameterException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Models\Constant\ReservationTablePrefix;

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
     * 予約モーダルAPI等で必要なレッスン情報を取得
     * shift_master, lesson_master, tenpo_masterのRedisキャッシュに対応するDBカラム
     *
     * @param string $shiftIdHash
     * @return \App\Models\ShiftMaster|null
     */
    public static function getReservationModalResource(string $shiftIdHash) {
        $shiftMasterTableName = (new ShiftMaster)->getTable();
        $lessonMasterTableName = (new LessonMaster)->getTable();
        $tenpoMasterTableName = (new TenpoMaster)->getTable();
        $lessonClass1TableName = 'lesson_class1';
        $lessonClass2TableName = 'lesson_class2';
        $lessonClass3TableName = 'lesson_class3';
        $userMasterTableName = (new UserMaster)->getTable();

        $query = self::from("{$shiftMasterTableName} AS SM")
        ->select([
            "SM.shiftid AS " . ReservationTablePrefix::SM . "shiftid",
            "SM.flg AS " . ReservationTablePrefix::SM . "flg",
            "SM.open_datetime AS " . ReservationTablePrefix::SM . "open_datetime",
            "SM.taiken_mess AS " . ReservationTablePrefix::SM . "taiken_mess",
            "SM.taiken_les_flg AS " . ReservationTablePrefix::SM . "taiken_les_flg",
            "SM.tlimit AS " . ReservationTablePrefix::SM . "tlimit",
            "SM.shift_date AS " . ReservationTablePrefix::SM . "shift_date",
            "SM.ls_st AS " . ReservationTablePrefix::SM . "ls_st",
            "SM.shift_capa AS " . ReservationTablePrefix::SM . "shift_capa",
            "SM.taiken_capa AS " . ReservationTablePrefix::SM . "taiken_capa",
            "SM.gender AS " . ReservationTablePrefix::SM . "gender",
            "SM.ls_menu AS " . ReservationTablePrefix::SM . "ls_menu",
            "SM.ls_et AS " . ReservationTablePrefix::SM . "ls_et",
            "SM.shift_tenpoid AS " . ReservationTablePrefix::SM . "shift_tenpoid",
            "SM.teacher AS " . ReservationTablePrefix::SM . "teacher",
            "UM.user_name AS " . ReservationTablePrefix::SM . "instructor_name",
            //"UM.path_img AS " .  ReservationTablePrefix::SM . "instructor_path_img",
            "LM.lid AS " . ReservationTablePrefix::LM . "lid",
            "LM.lesson_class1 AS " . ReservationTablePrefix::LM . "lesson_class1",
            "LM.lesson_class2 AS " . ReservationTablePrefix::LM . "lesson_class2",
            "LM.lesson_class3 AS " . ReservationTablePrefix::LM . "lesson_class3",
            "LC1.name AS " . ReservationTablePrefix::LM . "lesson_class1_name",
            "LC2.name AS " . ReservationTablePrefix::LM . "lesson_class2_name",
            "LC3.name AS " . ReservationTablePrefix::LM . "lesson_class3_name",
            "LM.iname AS " . ReservationTablePrefix::LM . "iname",
            "TM.tid AS " . ReservationTablePrefix::TM . "tid",
            "TM.tenpo_name AS " . ReservationTablePrefix::TM . "tenpo_name",
            "TM.monthly_avail_tenpo AS " . ReservationTablePrefix::TM . "monthly_avail_tenpo",
            "TM.tenpo_memtype AS " . ReservationTablePrefix::TM . "tenpo_memtype",

        ])
        ->join("{$lessonMasterTableName} AS LM", "SM.ls_menu", '=', 'LM.lid')
        ->join("{$tenpoMasterTableName} AS TM", "SM.shift_tenpoid", '=', 'TM.tid')
        ->join("{$lessonClass1TableName} AS LC1", "LM.lesson_class1", '=', 'LC1.id')
        ->join("{$lessonClass2TableName} AS LC2", "LM.lesson_class2", '=', 'LC2.id')
        ->leftJoin("{$lessonClass3TableName} AS LC3", "LM.lesson_class3", '=', 'LC3.id')
        ->join("{$userMasterTableName} AS UM", "SM.teacher", '=', 'UM.uid')
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

    /**
     * バイク枠状態取得API等で必要なレッスン情報を取得
     * shift_masterのRedisキャッシュに対応するDBカラム
     *
     * @param string $shiftIdHash
     * @return \App\Models\ShiftMaster|null
     */
    public static function getShiftMasterResource(string $shiftIdHash) {
        $shiftMasterTableName = (new ShiftMaster)->getTable();
        $userMasterTableName = (new UserMaster)->getTable();

        $query = self::from("{$shiftMasterTableName} AS SM")
            ->select([
                "SM.shiftid AS " . ReservationTablePrefix::SM . "shiftid",
                "SM.flg AS " . ReservationTablePrefix::SM . "flg",
                "SM.open_datetime AS " . ReservationTablePrefix::SM . "open_datetime",
                "SM.taiken_mess AS " . ReservationTablePrefix::SM . "taiken_mess",
                "SM.taiken_les_flg AS " . ReservationTablePrefix::SM . "taiken_les_flg",
                "SM.tlimit AS " . ReservationTablePrefix::SM . "tlimit",
                "SM.shift_date AS " . ReservationTablePrefix::SM . "shift_date",
                "SM.ls_st AS " . ReservationTablePrefix::SM . "ls_st",
                "SM.shift_capa AS " . ReservationTablePrefix::SM . "shift_capa",
                "SM.taiken_capa AS " . ReservationTablePrefix::SM . "taiken_capa",
                "SM.gender AS " . ReservationTablePrefix::SM . "gender",
                "SM.ls_menu AS " . ReservationTablePrefix::SM . "ls_menu",
                "SM.ls_et AS " . ReservationTablePrefix::SM . "ls_et",
                "SM.shift_tenpoid AS " . ReservationTablePrefix::SM . "shift_tenpoid",
                "SM.teacher AS " . ReservationTablePrefix::SM . "teacher",
                "UM.user_name AS " . ReservationTablePrefix::SM . "instructor_name",
                //"UM.path_img AS " .  ReservationTablePrefix::SM . "instructor_path_img",
            ])
            ->join("{$userMasterTableName} AS UM", "SM.teacher", '=', 'UM.uid')
            ->where('SM.shiftid_hash', '=', $shiftIdHash);

        return $query->get()->first();
    }

    /**
     * バイク枠仮確保状態確認・更新API等で必要なレッスン情報を取得
     * shift_master, tenpo_masterのRedisキャッシュに対応するDBカラム
     *
     * @param string $shiftIdHash
     * @return \App\Models\ShiftMaster|null
     */
    public static function getShiftTenpoMasterResource(string $shiftIdHash) {
        $shiftMasterTableName = (new ShiftMaster)->getTable();
        $userMasterTableName = (new UserMaster)->getTable();
        $tenpoMasterTableName = (new TenpoMaster)->getTable();

        $query = self::from("{$shiftMasterTableName} AS SM")
        ->select([
            "SM.shiftid AS " . ReservationTablePrefix::SM . "shiftid",
            "SM.flg AS " . ReservationTablePrefix::SM . "flg",
            "SM.open_datetime AS " . ReservationTablePrefix::SM . "open_datetime",
            "SM.taiken_mess AS " . ReservationTablePrefix::SM . "taiken_mess",
            "SM.taiken_les_flg AS " . ReservationTablePrefix::SM . "taiken_les_flg",
            "SM.tlimit AS " . ReservationTablePrefix::SM . "tlimit",
            "SM.shift_date AS " . ReservationTablePrefix::SM . "shift_date",
            "SM.ls_st AS " . ReservationTablePrefix::SM . "ls_st",
            "SM.shift_capa AS " . ReservationTablePrefix::SM . "shift_capa",
            "SM.taiken_capa AS " . ReservationTablePrefix::SM . "taiken_capa",
            "SM.gender AS " . ReservationTablePrefix::SM . "gender",
            "SM.ls_menu AS " . ReservationTablePrefix::SM . "ls_menu",
            "SM.ls_et AS " . ReservationTablePrefix::SM . "ls_et",
            "SM.shift_tenpoid AS " . ReservationTablePrefix::SM . "shift_tenpoid",
            "SM.teacher AS " . ReservationTablePrefix::SM . "teacher",
            "UM.user_name AS " . ReservationTablePrefix::SM . "instructor_name",
            //"UM.path_img AS " .  ReservationTablePrefix::SM . "instructor_path_img",
            "TM.tid AS " . ReservationTablePrefix::TM . "tid",
            "TM.tenpo_name AS " . ReservationTablePrefix::TM . "tenpo_name",
            "TM.monthly_avail_tenpo AS " . ReservationTablePrefix::TM . "monthly_avail_tenpo",
            "TM.tenpo_memtype AS " . ReservationTablePrefix::TM . "tenpo_memtype",
        ])
        ->join("{$tenpoMasterTableName} AS TM", "SM.shift_tenpoid", '=', 'TM.tid')
        ->join("{$userMasterTableName} AS UM", "SM.teacher", '=', 'UM.uid')
        ->where('SM.shiftid_hash', '=', $shiftIdHash);

        return $query->get()->first();
    }
}
