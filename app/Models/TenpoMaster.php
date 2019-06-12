<?php

namespace App\Models;

use App\Exceptions\IllegalParameterException;
use Illuminate\Database\Eloquent\Model;

class TenpoMaster extends BaseModel
{
    //
    /**
     * @var string テーブル名
     */
    protected $table = 'tenpo_master';
    protected $primaryKey = 'tid';

    // 有効/無効フラグ
    const VALID = 1;
    const INVALID = 0;

    /**
     * インストラクターが所属する店舗一覧取得
     * @param $instructorIds
     * @return
     */
    public static function findInstructorsShops ($instructorIds) {
        if (empty($instructorIds)) {
            return [];
        }
        //user_master_histテーブルと結合
        $query = self::leftjoin('user_master_hist', 'tenpo_master.tid', 'user_master_hist.tid');

        $query->whereIn('user_master_hist.uid', $instructorIds)
            ->where('user_master_hist.flg', self::VALID);

        $query->select(
            'tenpo_master.*',
            'user_master_hist.uid'
        );

        return $query->orderBy('prefecture_code', 'ASC')->get();
    }
}
