<?php

namespace App\Libraries\Logic\ReservationModal;

use Illuminate\Support\Facades\Auth;
use App\Models\ShiftMaster;
use App\Models\Constant\ReservationTablePrefix;

/**
 * 予約モーダルAPIで必要なRedis・DBのマスター情報
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
            'tenpo_master:' . $this->shiftMaster['shift_tenpoid'], $this->tenpoMaster)) {
            // tenpo_masterのRedisキャッシュ取得失敗
            return false;
        }
        */

        // TODO 会員情報取得処理
        $this->custMaster['cid'] = 1;
        $this->custMaster['memtype'] = 2; // マンスリー会員

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
        $tmLength = strlen(ReservationTablePrefix::TM);

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

            if (strpos($key, ReservationTablePrefix::TM) !== FALSE) {
                // tenpo_masterのカラム
                $this->tenpoMaster[substr($key, $tmLength)] = $value;
                continue;
            }
        }

        // TODO 会員情報取得処理
        $this->custMaster['cid'] = 1;
        $this->custMaster['memtype'] = 2; // マンスリー会員
    }

}