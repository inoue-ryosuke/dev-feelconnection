<?php
namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use App\Exceptions\IllegalParameterException;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Libraries\Auth\Authenticatable as AuthenticatableTrait;
use Carbon\Carbon;
use App\Libraries\Common\JpDateTime as JpDateTime;

use App\Models\TenpoMaster as TenpoMaster;

class Cust extends BaseFormModel implements Authenticatable
{
//    use AuthenticatableTrait, ListingTrait, SoftDeletes, TokenizerTrait,UserTrait, UserStatusTrait;
    use AuthenticatableTrait;

    /**
     * @var string テーブル名
     */
    protected $table = 'cust_master';
    protected $primaryKey = 'cid';
    protected $loginKey = 'cid';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
		"flg",
		"webmem",
		"login_pass",
		"res_permit_cnt",
		"gmo_credit",
		"memberid",
		"memberid2",
		"reason_admit",
		"admit_date",
		"admit_onday",
		"reason_rest",
		"rest_date",
		"quit",
		"quit_date",
		"readmit_date",
		"resumption",
		"recess",
		"idm",
		"rank",
		"memtype",
		"experience_flg",
		"store_id",
		"last_tenpo",
		"sex",
		"kana",
		"name",
		"nickname",
		"caution",
		"b_code",
		"b_name",
		"br_code",
		"br_name",
		"c_code",
		"c_name",
		"a_name",
		"a_number",
		"y_code",
		"y_number",
		"ya_name",
		"bank_type",
		"transfer_date",
		"b_year",
		"b_month",
		"b_day",
		"kubun1_id",
		"kubun2_id",
		"kubun3_id",
		"kubun4_id",
		"kubun5",
		"kubun6",
		"kubun7",
		"kubun8",
		"hw",
		"h_zip",
		"h_addr1",
		"h_addr2",
		"h_buil",
		"ng_h_hagaki",
		"h_tel1",
		"h_tel2",
		"h_tel3",
		"cellph1",
		"cellph2",
		"cellph3",
		"fax1",
		"fax2",
		"fax3",
		"c_mail",
		"c_conf",
		"ng_c_mail",
		"pc_mail",
		"pc_conf",
		"ng_pc_mail",
		"job_id",
		"jobkind_id",
		"syoujo_sub1",
		"syoujo_sub2",
		"t_change",
		"t_comment",
		"kana_g",
		"name_g",
		"fam_relation",
		"h_zip_g",
		"h_addr1_g",
		"h_addr2_g",
		"h_buil_g",
		"ng_h_hagaki_g",
		"h_tel1_g",
		"h_tel2_g",
		"h_tel3_g",
		"cellph1_g",
		"cellph2_g",
		"cellph3_g",
		"fax1_g",
		"fax2_g",
		"fax3_g",
		"c_mail_g",
		"pc_mail_g",
		"memberflg",
		"type_edit_date",
		"dm_list",
		"w_report",
		"m_pub",
		"wpatern",
		"week_id",
		"first_buy",
		"first_lesson",
		"last_lesson",
		"eraser_name",
		"reg_user",
		"edit_tenpoid",
		"edit_date",
		"edit_time",
		"del_date",
		"del_time",
		"raiten",
		"reedit_date",
		"unpay_total",
		"mov_id",
		"mov_id_all",
		"info_boad",
		"mail_delivery",
		"no_mess",
		"reserve_lock",
		"salt",
		"password_change_datetime",
		"login_trial_count"
    ];

    const CREATED_AT = 'edit_date';
    const UPDATED_AT = 'reedit_date';
    const DELETED_AT = 'del_date';
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

	// モデル結合アクセサ（会員区分）
    public function joinAllMemType() {
		return $this->hasOne(CustMemType::Class,"mid","memtype");
	}
    public function hasOneMemType() {
		if (!$this->joinAllMemType) {
			return null;
		}
		return $this->joinAllMemType->first();
	}
    public function hasManyMemType() {
		if (!$this->joinAllMemType) {
			return null;
		}
		return $this->joinAllMemType->get();
	}
	// モデル結合アクセサ（所属店舗）
    public function joinAllStoreTenpo() {
		return $this->hasOne(TenpoMaster::Class,"tid","store_id");
	}
    public function hasOneStoreTenpo() {
		if (!$this->joinAllStoreTenpo) {
			return null;
		}
		return $this->joinAllStoreTenpo->first();
	}
    public function hasManyStoreTenpo() {
		if (!$this->joinAllStoreTenpo) {
			return null;
		}
		return $this->joinAllStoreTenpo->get();
	}
	// モデル結合アクセサ（登録店舗）
    public function joinAllLastTenpo() {
		return $this->hasOne(TenpoMaster::Class,"tid","last_tenpo");
	}
    public function hasOneLastTenpo() {
		if (!$this->joinAllLastTenpo) {
			return null;
		}
		return $this->joinAllLastTenpo->first();
	}
    public function hasManyLastTenpo() {
		if (!$this->joinAllLastTenpo) {
			return null;
		}
		return $this->joinAllLastTenpo->get();
	}
	// 全店舗情報（所属店舗＋登録店舗）
    public function hasManyAllTenpo() {

		$all = collect([]);
		if (!$this->store_id && !$this->last_tenpo) {
			return null;
		}
		$store  = $this->hasOneStoreTenpo();
		if ($store) {
             $all->add($store);
		} 
		//print "<pre>"; print_r($store); print "</pre>";
		$last   = $this->hasOneLastTenpo();
		if ($last) {
             $all->add($last);
		} 
		return $all;
	}

    /**
	 * IDでcust情報を取得する
	 */
	public static function getAuthInfo($cid) {
		if (empty($cid)) {
			return null;
		}
		$authcust = Cust::find($cid);
        if (is_null($authcust)) {
			return  null;
		}
		return $authcust;
	}

	/**
	 * 変更登録情報を不可する判定結果により文言を加えた名前を返却する
	 */
    public function getNameInfo() {
		if (is_null($this->type_edit_date)) {
			return $this->name;
		}
		$append = "";
		$c = Carbon::parse($this->type_edit_date);
		// 変更日が当日かそれ以下（過去）の場合
		if ($c->lte(Carbon::today())) {
			$append = "（変更登録あり）";
		}
		return $this->name.$append;
	}

	/**
	 * 年,月,日から誕生日文字列を出力する。
	 */
	public function getBirthDayWord($mode="jp") {
		$y  = $this->b_year;
		$m  = $this->b_month;
		$d  = $this->b_day;
		if (!$y || !$m || !$d ) {
			return "";
		}
		// 和暦変換返却
    	if ($mode="jp") {
			$c = Carbon::createFromDate($y,$m,$d,'Asia/Tokyo');
			return JpDateTime::date("JK年n月j日",$c->timestamp);
		}
		// 西暦返却
		return sprintf("%04d",$y).sprintf("%02d",$m).sprintf("%02d",$d);
	}
    /**
	 * 電話番号文字列を返却する
	 */
	public function getTelNo($separater="") {
		if (!$this->h_tel1 || !$this->h_tel2 || !$this->h_tel3) {
			return "";
		}
		return $this->h_tel1.$separater.$this->h_tel2.$separater.$this->h_tel3;
	}
	/**
	 * 会員種別を返却する
	 */
	public function getMemTypeName() {
		$memtype = $this->hasOneMemType();
		if (is_null($memtype)) {
			return "";
		}
		return $memtype->type_name ?? "";
	}
	/**
	 * 所属店舗を返却する（TBD:他箇所で取得する処理があればそれを用いる）
	 */
	public function getStoreNames() {
		//"銀座（GNZ）、自由が丘（JYO）",	
		$all = $this->hasManyAllTenpo();
		if ($all->isEmpty()) {
			return "";
		}
		// 銀座（GNZ）という文字列を作る
		$tenpostr = $all->map(function($tenpo) {
			return ["tenpo_str" => $tenpo->tenpo_name."(".$tenpo->tenpo_code.")"];
		});
		return $tenpostr->implode("tenpo_str","、");
	}
	/**
	 * 案内メール設定を返却する
	 */
	public function getDmLists() {
		//"1,,,,",
		return $this->dm_list ?? ",,,,5";
	}
	/**
	 * PCメールアドレス予約確認メール設定を返却する
	 */
	public function getPcConf() {
		return $this->pc_conf ?? 0;
	}
	/**
	 * GMO会員IDを返却する
	 */
	public function getGmoId() {
		return $this->gmo_credit ?? null;
	}
	/**
	 * キャンペーン一覧を取得返却する
	 */
	public function getCampaignList() {
		//TBD:テーブル構造次第。今はスタブ返却
		$campaign = [
			[
				"code" => "XXXXXXXXXX",
				"name" => "NEW YEARキャンペーン",
				"flag" => 1,
			],
			[
				"code" => "XXXXXXXXXX",
				"name" => "お友達紹介キャンペーン",
				"flag" => 1,
			]
		];
        return $campaign;
	}

}
