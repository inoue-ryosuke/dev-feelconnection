<?php

namespace App\Libraries\Logic\ReservationModal;

use Illuminate\Support\Facades\Auth;
use App\Models\ShiftMaster;

/**
 * バイク枠仮確保状態確認・更新APIで必要なRedis・DBのマスター情報
 *
 */
class SheetStatusExtendMasterResource extends ReservationMasterResource {

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
        $model = ShiftMaster::getShiftTenpoMasterResource($this->shiftIdHash);

        $this->setShiftMasterResourceByModel($model);
        $this->setTenpoMasterResourceByModel($model);

        // キャッシュ生成
        $this->createShiftMasterCache();
        $this->createTenpoMasterCache();

        // TODO 会員情報取得処理
        $this->custMaster['cid'] = 1;
        $this->custMaster['memtype'] = 3;
    }

}