<?php

namespace App\Libraries\Logic\ReservationModal;

use App\Models\ShiftMaster;

/**
 * バイク予約状態取得API等で必要なRedis・DBのマスター情報
 * shift_master, cust_master に対応する
 *
 */
class ShiftCustMasterResource extends ReservationMasterResource {

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

        // 会員情報取得
        $user = auth('customer')->user();
        $this->custMaster['cid'] = $user->cid;
        $this->custMaster['memtype'] = $user->memtype;

        return true;
    }

    /** DBのマスタデータ取得 */
    public function createDBResource() {
        $model = ShiftMaster::getShiftMasterResource($this->shiftIdHash);

        if (is_null($model)) {
            // エラー、不正なIDハッシュ値
            // throw new InternalErrorException();
        }

        $this->setShiftMasterResourceByModel($model);

        // キャッシュ生成
        $this->createShiftMasterCache();

        // 会員情報取得
        $user = auth('customer')->user();
        $this->custMaster['cid'] = $user->cid;
        $this->custMaster['memtype'] = $user->memtype;
    }

}