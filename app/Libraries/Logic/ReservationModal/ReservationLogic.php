<?php

namespace App\Libraries\Logic\ReservationModal;

use App\Libraries\Logger;
use App\Models\OrderLesson;
use App\Models\CancelHist;
use App\Models\TicketMaster;
use Illuminate\Support\Facades\DB;
use App\Models\Constant\OrderLessonFlg;
use App\Models\Constant\OrderLessonSbFlg;
use App\Models\Constant\TicketMasterFlg;
use App\Models\Constant\ReserveLock;
use App\Models\ClubFee;
use App\Models\Cust;

/**
 * 予約登録、バイク位置変更、予約キャンセル ロジック
 *
 */
class ReservationLogic
{

    /**
     * バイク位置変更
     *
     * @param int $shiftId レッスンスケジュールID
     * @param int $sheetNo 変更先座席番号
     * @param int $customerId 会員ID
     * @return bool
     */
    public static function changeSheetNo(int $shiftId, int $sheetNo, int $customerId) {
        try {
            DB::transaction(function () use ($shiftId, $sheetNo, $customerId) {
                $model = OrderLesson::where('sid', '=', $shiftId)
                    ->where('customer_id', '=', $customerId)
                    ->where('flg', '=', OrderLessonFlg::RESERVED)
                    ->where('sb_flg', '=', OrderLessonSbFlg::NORMAL)
                    ->lockForUpdate()
                    ->first();

                $model->fill([ 'sheet' => $sheetNo ]);
                $model->save();
            });
        } catch (\Exception $e) {
            Logger::writeErrorLog($e->getMessage());

            return false;
        }

        return true;
    }

    /**
     * 予約済みレッスン情報取得
     *
     * @param int $shiftId レッスンスケジュールID
     * @param int $customerId 会員ID
     * @return array|null
     */
    public static function getReservedLesson(int $shiftId, int $customerId) {
        $model = OrderLesson::getReservedOrderLesson($shiftId, $customerId);

        if (is_null($model)) {
            // 予約済みでない
            return null;
        }

        return $model->toArray();
    }

    /**
     * 予約キャンセル(通常予約)
     *
     * @param int $shiftId レッスンスケジュールID
     * @param int $customerId 会員ID
     * @return bool
     */
    public static function cancelLesson(int $shiftId, int $customerId) {
        try {
            DB::transaction(function () use ($shiftId, $customerId) {
                // レッスン予約・キャンセル排他ロック、予約・キャンセルの同時実行を防ぐ
                $custMaster = Cust::find($customerId);
                $custMaster->fill([ 'reserve_lock' => ReserveLock::LOCK ]);
                $custMaster->save();

                // 予約済みレッスン取得
                $orderLesson = OrderLesson::where('sid', '=', $shiftId)
                    ->where('customer_id', '=', $customerId)
                    ->where('flg', '=', OrderLessonFlg::RESERVED)
                    ->lockForUpdate()
                    ->first();

                if (is_null($orderLesson)) {
                    // 予約済みレッスンでない
                    throw new \Exception("予約キャンセルエラー 予約済みレッスンでない" );
                }

                if (!VaidationLogic::isCancelProhibit($orderLesson->cancel_prohibit)) {
                    // キャンセル可のレッスンでない
                    throw new \Exception(
                        "予約キャンセルエラー キャンセル可のレッスンでない order_lesson => \n" . var_export($orderLesson->toArray(), true));
                }

                // レッスン予約更新カラム
                $orderLessonFill = [ 'flg' => OrderLessonFlg::CANCELD_DELETED ];

                // 会費 or チケットのステータスを戻す、TODO: Backlogの回答待ち
                $ctkid = $orderLesson->ctkid; // 受講に使用した会費ID
                $addUp = $orderLesson->add_up; // 受講に使用した回数増加チケットID
                $another = $orderLesson->another; // 受講に使用した他店利用(ドロップイン)チケットID
                $tkid = $orderLesson->tkid; // 受講に使用したチケットID

                // 使用回数を戻すチケット
                $cancelTicketId = null;

                if ($ctkid > 0) {
                    // 予約に会費を使用(マンスリー・リミテッド・従量課金会員)
                    // 会費の使用回数を戻す
                    $clubFee = ClubFee::where('cfid', '=', $ctkid)->lockForUpdate()->first();
                    $clubFee->fill([ 'tcount' => max($clubFee->tcount - 1, 0) ]);
                    $clubFee->save();
                } else if ($addUp > 0) {
                    // 予約に回数増加チケットを使用(トライアル会員を除く全会員)
                    $cancelTicketId = $addUp;
                    $orderLessonFill['cancel_tkid'] = $addUp;
                } else if ($another > 0) {
                    // 予約に他店利用(ドロップイン)チケットを使用(マンスリー・リミテッド・従量課金会員)
                    $cancelTicketId = $another;
                    $orderLessonFill['cancel_tkid'] = $another;
                } else if ($tkid > 0) {
                    // 予約にチケットを使用(トライアル・チケット・休会)
                    $cancelTicketId = $tkid;
                    $orderLessonFill['cancel_tkid'] = $tkid;
                } else {
                    // 予約キャンセルエラー
                    throw new \Exception(
                        "予約キャンセルエラー 使用会費・チケットが登録されていない order_lesson =>\n" . var_export($orderLesson->toArray(), true));
                }

                if (!is_null($cancelTicketId)) {
                    // チケットの使用回数、受講レッスンスケジュールIDを戻す、未使用に変更
                    $ticketMaster = TicketMaster::where('tkid', '=', $cancelTicketId)->lockForUpdate()->first();

                    // 受講したレッスンスケジュールID(カンマ区切り複数)
                    $shiftIdText = $ticketMaster->shiftid;
                    $updateText = '';
                    if (!empty($shiftIdText)) {
                        $updateText = CommonLogic::deleteValueFromCommaText($shiftIdText, (string)$orderLesson->sid);
                    }

                    $ticketMaster->fill(
                        [
                            'dflg' => TicketMasterFlg::UNUSE,
                            'tcount' => max($ticketMaster->tcount - 1, 0),
                            'shiftid' => $updateText
                        ]);
                    $ticketMaster->save();
                }

                // レッスン予約更新
                $orderLesson->fill($orderLessonFill);
                $orderLesson->save();

                // レッスンキャンセル履歴追加
                CancelHist::create([
                    'sid' => $orderLesson->sid,
                    'tenpo_id' => $orderLesson->tenpo_id,
                    'order_date' => $orderLesson->order_date,
                    'customer_id' => $orderLesson->customer_id,
                    'memtype' => $orderLesson->memtype,
                    'nflg' => $orderLesson->nflg,
                    'lid' => $orderLesson->lid,
                    'reg_uid' => 0 // キャンセル実行スタッフID、0:ユーザーキャンセル
                ]);

                // レッスン予約・キャンセル排他ロック、予約・キャンセルの同時実行を防ぐ
                $custMaster->fill([ 'reserve_lock' => ReserveLock::UNLOCK ]);
                $custMaster->save();
            });
        } catch (\Exception $e) {
            Logger::writeErrorLog($e->getMessage());

            return false;
        }

        return true;
    }

    /**
     * 予約確定フローで、遷移先画面・モーダルを決定するために使用
     * 通常予約・キャンセル待ち予約で使用
     *
     * @param array& $shiftMaster shift_masterのリソース
     * @param array& $custMaster cust_masterのリソース
     * @param array& $tenpoMaster tenpo_masterのリソース
     * @return array [ 'modal_type' => モーダル種別, 'modal_text' => モーダルテキスト ]
     */
    public static function getReservationTransitionType(array &$shiftMaster, array &$custMaster, array &$tenpoMaster) {

    }

}