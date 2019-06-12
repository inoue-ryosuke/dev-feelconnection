<?php

namespace App\Models;

class UserMaster extends BaseModel
{
    /**
     * @var string テーブル名
     */
    protected $table = 'user_master';
    protected $primaryKey = 'uid';

    // インストラクター紹介一覧取得APIで使用するソート対象
    const SORT_TYPE_SHOP = 1;
    const SORT_TYPE_YEAR = 2;
    const SORT_TYPE_NAME = 3;
    // alive_flg(退職済みフラグ)
    const ALIVE_VALID = 'Y';
    const ALIVE_INVALID = 'N';
    // teacher(先生(インストラクター)フラグ)
    const TEACHER_VALID = 1;
    const TEACHER_INVALID = 0;

    /**
     * インストラクター一覧取得
     * @param $limit
     * @param $offset
     * @param $freeword
     * @return
     */
    public static function findInstructors ($limit, $offset, $freeword=null) {
        // 退職済みでないかつ先生フラグが1
        $query = self::where('user_master.alive_flg', self::ALIVE_VALID)
            ->where('user_master.teacher', self::TEACHER_VALID);

        if ($freeword) {
            $query->where(function($obj) use($freeword) {
                // 商品名またはJANコード
                $obj->where("user_master.name","LIKE","%".$freeword."%");
//                $obj->orWhere("tenpo_master.tenpo_name","LIKE","%".$freeword."%");
            });
        }
        // レッスンスケジュールテーブルと結合
        $query->leftjoin('shift_master', 'user_master.uid', 'shift_master.teacher');
        //user_master_histテーブルと結合
//        $query->leftjoin('user_master_hist', 'user_master.uid', 'user_master_hist.uid');
        //店舗テーブルと結合
//        $query->leftjoin('tenpo_master', 'user_master_hist.tid', 'tenpo_master.tid');

        $query->groupBy('shift_master.teacher')
            ->select(
                'shift_master.teacher as shift_master_uid',
                'user_master.*',
                DB::raw('min(shift_master.shift_date) as assigned_at')
            );

        $query->skip($offset)->take($limit)->get();
    }
}
