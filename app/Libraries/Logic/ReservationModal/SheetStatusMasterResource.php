<?php

namespace App\Libraries\Logic\ReservationModal;

use Illuminate\Support\Facades\Auth;
use App\Models\ShiftMaster;
use App\Models\Constant\ReservationTablePrefix;

/**
 * 予約モーダルで必要なRedis・DBのマスター情報
 *
 */
class SheetStatusMasterResource extends ReservationMasterResource {

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

        return true;
    }

    /** DBのマスタデータ取得 */
    public function createDBResource() {

    }

}