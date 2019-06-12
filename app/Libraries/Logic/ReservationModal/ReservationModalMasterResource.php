<?php

namespace App\Libraries\Logic\ReservationModal;

use Illuminate\Support\Facades\Auth;
use App\Models\ShiftMaster;
use App\Models\Constant\ReservationModalTablePrefix;

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
     * @return boolean Redisからキャッシュ取得成功したかどうか
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

        foreach ($attributes as $key => $value) {
            if (strpos($key, ReservationModalTablePrefix::SM) !== FALSE) {
                // shift_masterのカラム
                $this->shiftMaster[substr($key, strlen(ReservationModalTablePrefix::SM))] = $value;
                continue;
            }

            if (strpos($key, ReservationModalTablePrefix::LM) !== FALSE) {
                // lesson_masterのカラム
                $this->lessonMaster[substr($key, strlen(ReservationModalTablePrefix::LM))] = $value;
                continue;
            }

            if (strpos($key, ReservationModalTablePrefix::LC1) !== FALSE) {
                // lesson_class1のカラム
                $this->lessonClass1[substr($key, strlen(ReservationModalTablePrefix::LC1))] = $value;
                continue;
            }

            if (strpos($key, ReservationModalTablePrefix::LC2) !== FALSE) {
                // lesson_class2のカラム
                $this->lessonClass2[substr($key, strlen(ReservationModalTablePrefix::LC2))] = $value;
                continue;
            }

            if (strpos($key, ReservationModalTablePrefix::LC3) !== FALSE) {
                // lesson_class3のカラム
                $this->lessonClass3[substr($key, strlen(ReservationModalTablePrefix::LC3))] = $value;
                continue;
            }

            if (strpos($key, ReservationModalTablePrefix::TM) !== FALSE) {
                // tenpo_masterのカラム
                $this->tenpoMaster[substr($key, strlen(ReservationModalTablePrefix::TM))] = $value;
                continue;
            }

            if (strpos($key, ReservationModalTablePrefix::UM) !== FALSE) {
                // user_masterのカラム
                $this->userMaster[substr($key, strlen(ReservationModalTablePrefix::UM))] = $value;
            }
        }

        // cust_masterの必要カラムを取得
    }

}