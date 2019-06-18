<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Constant\OrderLessonFlg;
use App\Models\Constant\OrderLessonTrialFlg;

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
     * @return array [ 体験レッスン受講状態, 体験レッスン予約状態, 予約済み体験レッスンの開催日時 ]
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
            ->whereIn('OL.flg', [ OrderLessonFlg::RESERVED, OrderLessonFlg::ATTENDED ])
            ->get();

        if ($collection->isEmpty()) {
            // 体験レッスンを全く予約したことがない or 予約したがすべてキャンセル・休講した
            return [ false, false, '' ];
        }

        if ($collection->contains('flg', OrderLessonFlg::ATTENDED)) {
            // 体験レッスン受講済み
            return [ true, false, '' ];
        } else {
            // 体験レッスン受講済みでない、体験レッスン予約済み
            $model = $collection->last();
            $lessonDateTime = new \DateTime("{$model->shift_date} {$model->ls_et}");

            return [ false, true, $lessonDateTime->format('Y-m-d H:i:s') ];
        }
    }

    /**
     * 予約済み座席一覧取得
     *
     * @param int $shiftId レッスンスケジュールID
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getReservedSheetList(int $shiftId) {
        return self::where('sid', '=', $shiftId)
            ->where('flg', '=', OrderLessonFlg::RESERVED)
            ->get();
    }
}
