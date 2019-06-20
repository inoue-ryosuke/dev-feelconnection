<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use App\Exceptions\IllegalParameterException;
use App\Libraries\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\TenpoAreaMaster;
use App\Models\TenpoKubun;
use App\Models\BelongTenpoHist;

class TenpoMaster extends BaseFormModel implements Authenticatable
//class TenpoMaster extends SalesBaseFormModel implements Authenticatable
{
    use AuthenticatableTrait;
    //
    /**
     * @var string テーブル名
     */
    protected $table = 'tenpo_master';
    protected $primaryKey = 'tid';
//    protected $table = 'tenpo_master__c';
//    protected $primaryKey = 'tid__c';

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
    /*
    protected $fillable = [
				"tenpo_name__c",
				"iname__c",
				"itxtcol__c",
				"ibgcol__c",
				"tenpo_code__c",
				"tenpo_area_id__c",
				"honbu_prev__c",
				"tenpo_kubun__c",
				"zip__c",
				"tenpo_addr__c",
				"tenpo_tel__c",
				"res_tel__c",
				"tenpo_mail__c",
				"tenpo_url__c",
				"point__c",
				"header__c",
				"max_del_times__c",
				"del_times__c",
				"del_count_date__c",
				"rescount__c",
				"rescount_day__c",
				"nolimit__c",
				"flg__c",
				"seq__c",
				"m_price__c",
				"monthly_avail_all__c",
				"monthly_avail_tenpo__c",
				"monthly_free_exp__c",
				"monthly_fname__c",
				"mtenpo_explain__c",
				"tenpo_memtype__c",
    ];*/

    // モデル結合アクセサ（店舗エリア）
    public function joinAllTenpoArea() {
//		return $this->hasOne(TenpoAreaMaster::Class,"id","tenpo_area_id");
		return TenpoAreaMaster::where((new TenpoAreaMaster)->cKey("id"),$this->cKey("tenpo_area_id"))->get();
	}
    public function hasOneTenpoArea() {
//		return $this->joinAllTenpoArea->first();
		return $this->joinAllTenpoArea()->first();
	}
    public function hasManyTenpoArea() {
//		return $this->joinAllTenpoArea->get();
		return $this->joinAllTenpoArea();
	}
    // モデル結合アクセサ（店舗区分）
    public function joinAllTenpoKubun() {
//		return $this->hasOne(TenpoKubun::Class,"tkid","tenpo_kubun");
		return TenpoKubun::where((new TenpoKubun)->cKey("tkid"),$this->cKey("tenpo_kubun"));
	}
    public function hasOneTenpoKubun() {
//		return $this->joinAllTenpoKubun->first();
		return $this->joinAllTenpoKubun()->first();
	}
    public function hasManyTenpoKubun() {
//		return $this->joinAllTenpoKubun->get();
		return $this->joinAllTenpoKubun();
    }
    /**
     * 最寄り駅取得
     */
    public function getStationName() {
        return $this->{$this->cKey("station")} ?? "";
    }
    /**
     * 店舗画像一覧取得
     */
    public function getTenpoImageFileInfo() {
        // TBD:Storage経由のファイル一覧取得と返却
        return [
            "filename" => "http://xxx.xxx.xxx.xxx/image/".$this->{$this->cKey("tid")}."/hogehoge.jpg",
            "seq" => 1,
            "tid" => $this->{$this->cKey("tid")},
        ];
    }
    /**
     * 店舗オプション画像一覧取得
     */
    public function getTenpoOptionImageFileInfo() {
        // TBD:Storage経由のファイル一覧取得と返却
        return [
            "filename" => "http://xxx.xxx.xxx.xxx/image_option/".$this->{$this->cKey("tid")}."/testtest.jpg",
            "seq" => 1,
            "tid" => $this->{$this->cKey("tid")},
        ];
    }
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
        $query = self::leftjoin(BelongTenpoHist::TABLE, 'tenpo_master.tid', BelongTenpoHist::TABLE_TID);

        $query->whereIn(BelongTenpoHist::TABLE_UID, $instructorIds)
            ->where(BelongTenpoHist::TABLE_FLAG, self::VALID);

        $query->select(
            'tenpo_master.*',
            BelongTenpoHist::TABLE_UID
        );
        return $query->orderBy('tenpo_master.tid', 'ASC')->get();
//        return $query->orderBy('prefecture_code', 'ASC')->get();
    }

    /**
     * 店舗レコードを返却する
     * 
     * @param unknown $tid 店舗ID
     * @return unknown
     */
    public static function getShopById($tid) {
        $record = self::find($tid);
        return $record;
    }
}
