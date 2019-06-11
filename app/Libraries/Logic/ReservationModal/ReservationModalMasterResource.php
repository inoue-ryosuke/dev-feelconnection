<?php

namespace App\Libraries\Logic\ReservationModal;

use Illuminate\Support\Facades\Auth;
use App\Models\ShiftMaster;

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
    }
}