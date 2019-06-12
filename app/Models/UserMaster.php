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
     * @param $freeWord
     * @return
     */
    public static function findInstructors ($limit, $offset, $freeWord=null) {
        $query = self::makeQueryFindInstructors($freeWord);
        // レッスンスケジュールテーブルと結合
        $query->leftjoin('shift_master', 'user_master.uid', 'shift_master.teacher');

        $query->groupBy('shift_master.teacher')
            ->select(
                'shift_master.teacher as shift_master_uid',
                DB::raw('min(user_master.uid) as uid'),
                DB::raw('min(user_master.name) as name'),
                DB::raw('min(user_master.self_introduction) as self_introduction'),
                DB::raw('min(user_master.image_path) as image_path'),
                DB::raw('min(shift_master.shift_date) as assigned_at')
            );

        return $query->skip($offset)->take($limit)->get();
    }

    /**
     * インストラクター一覧全件数を取得
     * @param $freeWord
     * @return
     */
    public static function countInstructors ($freeWord=null) {
        $query = self::makeQueryFindInstructors($freeWord);

        return $query->count();
    }

    /**
     * インストラクター一覧取得対象を抽出するクエリを返却
     * @param $freeWord
     * @return mixed
     */
    private static function makeQueryFindInstructors($freeWord)
    {
        // 退職済みでないかつ先生フラグが1
        $query = self::where('user_master.alive_flg', self::ALIVE_VALID)
            ->where('user_master.teacher', self::TEACHER_VALID);

        // 検索ワードがリクエストされている場合、スタッフ名と店舗名で検索
        if ($freeWord) {
            $query->where(function ($obj) use ($freeWord) {
                // 商品名またはJANコード
                $obj->where("user_master.name", "LIKE", "%" . $freeWord . "%");
                $obj->orWhere("tenpo_master.tenpo_name", "LIKE", "%" . $freeWord . "%");
            });
        }

        return $query;
    }
}
