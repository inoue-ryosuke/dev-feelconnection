<?php

namespace App\Libraries\Logic\ReservationModal;

use Illuminate\Support\Facades\Auth;
use App\Models\ShiftMaster;
use App\Models\Constant\ReservationTablePrefix;

/**
 * 予約モーダルで必要なRedis・DBのマスター情報
 *
 */
class ReservationModalMasterResource extends ReservationMasterResource {

    public function __construct(string $shiftIdHash) {
        parent::__construct($shiftIdHash);
    }

    /**
     * 各マスターのRedisキャッシュを取得
     * すべて成功=true、失敗=false
     *
     * @return bool Redisからキャッシュ取得成功したかどうか
     */
    public function createRedisResource() {
        if (!$this->createRedisResourceByKey(
            'shift_master:' . $this->shiftIdHash, $this->shiftMaster)) {
            // shift_masterのRedisキャッシュ取得失敗
            return false;
        }

        /*
        if (!$this->createRedisResourceByKey(
            'lesson_master:' . $this->shiftMaster['ls_menu'], $this->lessonMaster)) {
            // lesson_masterのRedisキャッシュ取得失敗
            return false;
        }

        if (!$this->createRedisResourceByKey(
            'lesson_class1:' . $this->lessonMaster['lesson_class1'], $this->lessonClass1)) {
            // lesson_class1のRedisキャッシュ取得失敗
            return false;
        }

        if (!$this->createRedisResourceByKey(
            'lesson_class2:' . $this->lessonMaster['lesson_class2'], $this->lessonClass2)) {
            // lesson_class2のRedisキャッシュ取得失敗
            return false;
        }

        if (!$this->createRedisResourceByKey(
            'lesson_class3:' . $this->lessonMaster['lesson_class3'], $this->lessonClass3)) {
            // lesson_class3のRedisキャッシュ取得失敗
            return false;
        }

        if (!$this->createRedisResourceByKey(
            'cust_master:' . Auth::id(), $this->custMaster)) {
            // cust_masterのRedisキャッシュ取得失敗
            return false;
        }

        if (!$this->createRedisResourceByKey(
            'tenpo_master:' . $this->shiftMaster['shift_tenpoid'], $this->tenpoMaster)) {
            // tenpo_masterのRedisキャッシュ取得失敗
            return false;
        }

        if (!$this->createRedisResourceByKey(
            'user_master:' . $this->shiftMaster['teacher'], $this->userMaster)) {
            // user_masterのRedisキャッシュ取得失敗
            return false;
        }
        */

        return true;
    }

    /** DBのマスタデータ取得 */
    public function createDBResource() {
        $model = ShiftMaster::getReservationModalResource($this->shiftIdHash);

        if (is_null($model)) {
            // エラー、不正なIDハッシュ値
        }

        $attributes = $model->attributesToArray();

        $this->shiftMaster = [];
        $this->lessonMaster = [];
        $this->lessonClass1 = [];
        $this->lessonClass2 = [];
        $this->lessonClass3 = [];
        $this->custMaster = [];
        $this->tenpoMaster = [];
        $this->userMaster = [];

        $smLength = strlen(ReservationTablePrefix::SM);
        $lmLength = strlen(ReservationTablePrefix::LM);
        $lc1Length = strlen(ReservationTablePrefix::LC1);
        $lc2Length = strlen(ReservationTablePrefix::LC1);
        $lc3Length = strlen(ReservationTablePrefix::LC3);
        $tmLength = strlen(ReservationTablePrefix::TM);
        $umLength = strlen(ReservationTablePrefix::UM);

        foreach ($attributes as $key => $value) {
            if (strpos($key, ReservationTablePrefix::SM) !== FALSE) {
                // shift_masterのカラム
                $this->shiftMaster[substr($key, $smLength)] = $value;
                continue;
            }

            if (strpos($key, ReservationTablePrefix::LM) !== FALSE) {
                // lesson_masterのカラム
                $this->lessonMaster[substr($key, $lmLength)] = $value;
                continue;
            }

            if (strpos($key, ReservationTablePrefix::LC1) !== FALSE) {
                // lesson_class1のカラム
                $this->lessonClass1[substr($key, $lc1Length)] = $value;
                continue;
            }

            if (strpos($key, ReservationTablePrefix::LC2) !== FALSE) {
                // lesson_class2のカラム
                $this->lessonClass2[substr($key, $lc2Length)] = $value;
                continue;
            }

            if (strpos($key, ReservationTablePrefix::LC3) !== FALSE) {
                // lesson_class3のカラム
                $this->lessonClass3[substr($key, $lc3Length)] = $value;
                continue;
            }

            if (strpos($key, ReservationTablePrefix::TM) !== FALSE) {
                // tenpo_masterのカラム
                $this->tenpoMaster[substr($key, $tmLength)] = $value;
                continue;
            }

            if (strpos($key, ReservationTablePrefix::UM) !== FALSE) {
                // user_masterのカラム
                $this->userMaster[substr($key, $umLength)] = $value;
            }
        }

        // TODO cust_masterの必要カラムをDBから取得
        $this->custMaster['cid'] = 1;
        $this->custMaster['memtype'] = 2; // マンスリー会員
    }

}