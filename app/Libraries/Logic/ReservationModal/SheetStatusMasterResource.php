<?php

namespace App\Libraries\Logic\ReservationModal;

use Illuminate\Support\Facades\Auth;
use App\Models\ShiftMaster;

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

        // TODO 会員情報取得処理
        $this->custMaster['cid'] = 1;
        $this->custMaster['memtype'] = 2;

        return true;
    }

    /** DBのマスタデータ取得 */
    public function createDBResource() {
        $model = ShiftMaster::where('shiftid_hash', '=', $this->shiftIdHash)->first([ 'shiftid', 'open_datetime' ]);
        $this->shiftMaster['shiftid'] = $model->shiftid;
        $this->shiftMaster['open_datetime'] = $model->open_datetime;

        // TODO 会員情報取得処理
        $this->custMaster['cid'] = 1;
        $this->custMaster['memtype'] = 2;
    }

}