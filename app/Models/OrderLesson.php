<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Constant\OrderLessonFlg;
use App\Models\Constant\OrderLessonTrialFlg;
use App\Models\Constant\OrderLessonSbFlg;

class OrderLesson extends Model
{
    /** LessonFeelのテーブルサフィックス */
    const LF = '';

    /** テーブル名 */
    protected $table = 'order_lesson' . self::LF;
    /** 主キー */
    protected $primaryKey = 'oid';

    protected $guarded = [
        'experience',
    ];

    public $timestamps = false;

    /**
     * 体験レッスン(初回レッスン)受講状態、体験レッスン(初回レッスン)予約状態を取得
     *
     * @param int $customerId 会員ID
     * @return array [ 'trial_status' => 体験レッスン受講状態, 'reserved_status' => 体験レッスン予約状態,
     *                  'reserved_shift_date' => 予約済み体験レッスンの開催日時 ]
     */
    public static function getTrialLessonStatus(int $customerId) {
        $orderLessonTableName = (new OrderLesson)->getTable();
        $shiftMasterTableName = (new ShiftMaster)->getTable();

        // 会員が予約済み・受講済みの、体験レッスン予約レコードを取得
        $collection = self::from("{$orderLessonTableName} AS OL")
            ->select([
                "OL.flg AS flg",
                "SM.shift_date AS shift_date",
                "SM.ls_st AS ls_st"
            ])
            ->join("{$shiftMasterTableName} AS SM", "OL.sid", '=', "SM.shiftid")
            ->where('OL.customer_id', '=', $customerId)
            ->where('OL.trial_flg', '=', OrderLessonTrialFlg::TRIAL_LESSON)
            ->where('OL.sb_flg', '=', OrderLessonSbFlg::NORMAL)
            ->whereIn('OL.flg', [ OrderLessonFlg::RESERVED, OrderLessonFlg::ATTENDED ])
            ->get();

        if ($collection->isEmpty()) {
            // 体験レッスンを全く予約したことがない or 予約したがすべてキャンセル・休講した
            return [ 'trial_status' => false, 'reserved_status' => false, 'reserved_shift_date' => '' ];
        }

        if ($collection->contains('flg', OrderLessonFlg::ATTENDED)) {
            // 体験レッスン受講済み
            return [ 'trial_status' => true, 'reserved_status' => false, 'reserved_shift_date' => '' ];
        } else {
            // 体験レッスン受講済みでない、体験レッスン予約済み
            $model = $collection->last();
            $lessonDateTime = new \DateTime("{$model->shift_date} {$model->ls_et}");

            return [ 'trial_status' => false, 'reserved_status' => true, 'reserved_shift_date' => $lessonDateTime->format('Y-m-d H:i:s') ];
        }
    }

    /**
     * 予約済み座席一覧取得(通常予約)
     *
     * @param int $shiftId レッスンスケジュールID
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getReservedSheetList(int $shiftId) {
        return self::where('sid', '=', $shiftId)
            ->where('flg', '=', OrderLessonFlg::RESERVED)
            ->where('sb_flg', '=', OrderLessonSbFlg::NORMAL)
            ->get();
    }

    /**
     * 同時予約数取得
     *
     * @param int $customerId 会員ID
     * @return int
     */
    public static function getSimultaneousReservationCount(int $customerId) {
        $currentDateTime = new \DateTime();

        return self::where('customer_id', '=', $customerId)
            ->where('flg', '=', OrderLessonFlg::RESERVED)
            ->where('order_date', '>=', $currentDateTime->format('Y-m-d'))
            ->count();
    }

    /**
     * 予約済みレッスンを取得
     *
     * @param int $shiftId レッスンスケジュールID
     * @param int $customerId 会員ID
     * @return \App\Models\OrderLesson|null
     */
    public static function getReservedOrderLesson(int $shiftId, int $customerId) {
        return self::where('sid', '=', $shiftId)
            ->where('customer_id', '=', $customerId)
            ->where('flg', '=', OrderLessonFlg::RESERVED)
            ->first();
    }
}
