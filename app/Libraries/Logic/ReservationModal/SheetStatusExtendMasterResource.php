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

        // TODO 会員情報取得処理
        $this->custMaster['cid'] = 1;
        $this->custMaster['memtype'] = 3;

        return true;
    }

    /** DBのマスタデータ取得 */
    public function createDBResource() {
        $model = ShiftMaster::where('shiftid_hash', '=', $this->shiftIdHash)->first();
        $this->shiftMaster['shiftid'] = $model->shiftid;
        $this->shiftMaster['open_datetime'] = $model->open_datetime;
        $this->shiftMaster['taiken_mess'] = $model->taiken_mess;
        $this->shiftMaster['taiken_les_flg'] = $model->taiken_les_flg;
        $this->shiftMaster['tlimit'] = $model->tlimit;
        $this->shiftMaster['shift_date'] = $model->shift_date;
        $this->shiftMaster['ls_st'] = $model->ls_st;
        $this->shiftMaster['ls_et'] = $model->ls_et;
        $this->shiftMaster['shift_capa'] = $model->shift_capa;
        $this->shiftMaster['taiken_capa'] = $model->taiken_capa;

        // TODO 会員情報取得処理
        $this->custMaster['cid'] = 1;
        $this->custMaster['memtype'] = 3;
    }

}