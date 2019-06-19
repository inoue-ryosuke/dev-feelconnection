<?php

namespace App\Models;
use DB;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Exceptions\IllegalParameterException;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Libraries\Auth\Authenticatable as AuthenticatableTrait;
use App\Models\BelongTenpoHist;

class UserMaster extends BaseModel implements Authenticatable
{
    use AuthenticatableTrait;
    /**
     * @var string テーブル名
     */
    protected $table = 'user_master';
    protected $primaryKey = 'uid';
    protected $fillable = [
        'seq','login_id',
        'login_pass','prev_id',
        'alive_flg', 'user_name',
        'teacher','salt'
    ];
    // テーブルにtimestamps系カラムがないのでfalseを設定
    public $timestamps = false;

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
     * @param $type
     * @param $freeWord
     * @return
     */
    public static function findInstructors ($limit, $offset, $type,$freeWord=null) {
        $query = self::makeQueryFindInstructors($freeWord);
        logger($query->toSql());
        // レッスンスケジュールテーブルと結合
        $query->leftjoin('shift_master', 'user_master.uid', 'shift_master.teacher')
            ->whereNotNull('shift_master.shift_date');

        $query->groupBy('user_master.uid')
            ->select(
                DB::raw('min(user_master.uid) as uid'),
                DB::raw('min(user_master.user_name) as name'),
//                DB::raw('min(user_master.self_introduction) as self_introduction'),
//                DB::raw('min(user_master.image_path) as image_path'),
                DB::raw('min(shift_master.shift_date) as assigned_at')
            );

        // ソートを設定
        if($type ==UserMaster::SORT_TYPE_YEAR) {
            // デビュー年順(古い在籍順)
            $query->orderBy('assigned_at', 'asc');
        } else if($type ==UserMaster::SORT_TYPE_NAME) {
            // 名前順(ABC順)
            $query->orderBy('user_name', 'asc');
        }

        $list = [
            'count' => count($query->get()),
            'record' =>$query->skip($offset)->take($limit)->get()
        ];
        return $list;
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
        $query->leftjoin(BelongTenpoHist::TABLE, 'user_master.uid', BelongTenpoHist::TABLE_UID)
            ->leftjoin('tenpo_master', BelongTenpoHist::TABLE_TID, 'tenpo_master.tid');
        // 検索ワードがリクエストされている場合、スタッフ名と店舗名で検索
        if ($freeWord) {
            $query->where(function ($obj) use ($freeWord) {
                //
                $obj->where("user_master.user_name", "LIKE", "%" . $freeWord . "%");
                $obj->orWhere("tenpo_master.tenpo_name", "LIKE", "%" . $freeWord . "%");
            });
        }

        return $query;
    }
}
