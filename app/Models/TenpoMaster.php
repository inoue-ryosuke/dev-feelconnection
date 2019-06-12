<?php

namespace App\Models;

use App\Exceptions\IllegalParameterException;
use Illuminate\Database\Eloquent\Model;
use App\Models\TenpoAreaMaster;
use App\Models\TenpoKubun;

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

    const CREATED_AT = null;
    const UPDATED_AT = null;
	const DELETED_AT = null;

    protected $fillable = [
				"tenpo_name",
				"iname",
				"itxtcol",
				"ibgcol",
				"tenpo_code",
				"tenpo_area_id",
				"honbu_prev",
				"tenpo_kubun",
				"zip",
				"tenpo_addr",
				"tenpo_tel",
				"res_tel",
				"tenpo_mail",
				"tenpo_url",
				"point",
				"header",
				"max_del_times",
				"del_times",
				"del_count_date",
				"rescount",
				"rescount_day",
				"nolimit",
				"flg",
				"seq",
				"m_price",
				"monthly_avail_all",
				"monthly_avail_tenpo",
				"monthly_free_exp",
				"monthly_fname",
				"mtenpo_explain",
				"tenpo_memtype",
    ];

    // モデル結合アクセサ（店舗エリア）
    public function joinAllTenpoArea() {
		return $this->hasOne(TenpoAreaMaster::Class,"id","tenpo_area_id");
	}
    public function hasOneTenpoArea() {
		return $this->joinAllTenpoArea->first();
	}
    public function hasManyTenpoArea() {
		return $this->joinAllTenpoArea->get();
	}
    // モデル結合アクセサ（店舗区分）
    public function joinAllTenpoKubun() {
		return $this->hasOne(TenpoKubun::Class,"tkid","tenpo_kubun");
	}
    public function hasOneTenpoKubun() {
		return $this->joinAllTenpoKubun->first();
	}
    public function hasManyTenpoKubun() {
		return $this->joinAllTenpoKubun->get();
    }
    
    /**
     * インストラクターが所属する店舗一覧取得
     * @param $instructorIds
     * @return
     */
    public static function findInstructorsShops ($instructorIds) {
        if (empty($instructorIds)) {
            throw new IllegalParameterException();
        }
        //user_master_histテーブルと結合
        $query = self::leftjoin('user_master_hist', 'tenpo_master.tid', 'user_master_hist.tid');

        $query->whereIn('user_master_hist.uid', $instructorIds)
            ->where('user_master_hist.flg', self::VALID);

        $query->select(
            'tenpo_master.*',
            'user_master_hist.uid'
        );

        return $query->get();
    }
    
}
