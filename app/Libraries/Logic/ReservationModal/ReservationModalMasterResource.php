<?php

namespace App\Libraries\Logic\ReservationModal;

use Illuminate\Support\Facades\Auth;
use App\Models\ShiftMaster;
use App\Models\Constant\ReservationTablePrefix;

/**
 * 予約モーダルAPI等で必要なRedis・DBのマスター情報
 * shift_master, lesson_master, tenpo_master, cust_master に対応する
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


        // TODO 会員情報取得処理
        $this->custMaster['cid'] = 1;
        $this->custMaster['memtype'] = 3;

        return true;
    }

    /** DBのマスタデータ取得 */
    public function createDBResource() {
        $model = ShiftMaster::getReservationModalResource($this->shiftIdHash);

        if (is_null($model)) {
            // エラー、不正なIDハッシュ値
        }

        $this->setShiftMasterResourceByModel($model);
        $this->setLessonMasterResourceByModel($model);
        $this->setTenpoMasterResourceByModel($model);

        // キャッシュ生成
        $this->createShiftMasterCache();
        $this->createLessonMasterCache();
        $this->createTenpoMasterCache();

        // TODO 会員情報取得処理
        $this->custMaster['cid'] = 1;
        $this->custMaster['memtype'] = 3;
    }

}