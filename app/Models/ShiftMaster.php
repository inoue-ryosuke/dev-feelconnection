<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Constant\ReservationTablePrefix;

class ShiftMaster extends Model
{
    /** LessonFeelのテーブルサフィックス */
    const LF = '';

    /** テーブル名 */
    protected $table = 'shift_master' . self::LF;
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
            "SM.teacher AS " . ReservationTablePrefix::SM . "eacher",
            "LM.lid AS " . ReservationTablePrefix::LM . "lid",
            "LM.lesson_class1 AS " . ReservationTablePrefix::LM . "lesson_class1",
            "LM.lesson_class2 AS " . ReservationTablePrefix::LM . "lesson_class2",
            "LM.lesson_class3 AS " . ReservationTablePrefix::LM . "lesson_class3",
            "LC1.id AS " . ReservationTablePrefix::LC1 . "id",
            "LC1.name AS " . ReservationTablePrefix::LC1 . "name",
            "LC2.id AS " . ReservationTablePrefix::LC2 . "id",
            "LC2.name AS " . ReservationTablePrefix::LC2 . "name",
            "LC3.id AS " . ReservationTablePrefix::LC3 . "id",
            "LC3.name AS " . ReservationTablePrefix::LC3 . "_name",
            "TM.tid AS " . ReservationTablePrefix::TM . "tid",
            "TM.tenpo_name AS " . ReservationTablePrefix::TM . "tenpo_name",
            "UM.uid AS " . ReservationTablePrefix::UM . "uid",
            "UM.user_name AS " . ReservationTablePrefix::UM . "user_name",
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
}
