<?php
namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use App\Exceptions\IllegalParameterException;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Libraries\Auth\Authenticatable as AuthenticatableTrait;
use Carbon\Carbon;

use App\Libraries\Common\JpDateTime as JpDateTime;

use App\Models\TenpoMaster as TenpoMaster;
use App\Models\CustTenpo as CustTenpo;
use App\Models\Schedule as Schedule;

class Cust extends BaseFormModel implements Authenticatable
//class Cust extends SalesBaseFormModel implements Authenticatable
{
	use AuthenticatableTrait;

	/**
     * @var string テーブル名
     */
    protected $table = 'cust_master';
    protected $primaryKey = 'cid';
//    protected $table = 'cust_master__c';
//    protected $primaryKey = 'cid__c';
    const CREATED_AT = null;
    const UPDATED_AT = null;
    const DELETED_AT = null;
    // 入会可能年齢
    const MEMBER_POSSIBLE_AGE = 16;

    const PROCESS_VALIDATE = 1; //1:入力・確認
    const PROCESS_UPDATE = 2; // 2:変更
    const PROCESS_STORE = 3; // 3:登録
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
	/*
	protected $fillable = [
		"flg__c",
		"webmem__c",
		"login_pass__c",
		"res_permit_cnt__c",
		"gmo_credit__c",
		"memberid__c",
		"memberid2__c",
		"reason_admit__c",
		"admit_date__c",
		"admit_onday__c",
		"reason_rest__c",
		"rest_date__c",
		"quit__c",
		"quit_date__c",
		"readmit_date__c",
		"resumption__c",
		"recess__c",
		"idm__c",
		"rank__c",
		"memtype__c",
		"experience_flg__c",
		"store_id__c",
		"last_tenpo__c",
		"sex__c",
		"kana__c",
		"name__c",
		"nickname__c",
		"caution__c",
		"b_code__c",
		"b_name__c",
		"br_code__c",
		"br_name__c",
		"c_code__c",
		"c_name__c",
		"a_name__c",
		"a_number__c",
		"y_code__c",
		"y_number__c",
		"ya_name__c",
		"bank_type__c",
		"transfer_date__c",
		"b_year__c",
		"b_month__c",
		"b_day__c",
		"kubun1_id__c",
		"kubun2_id__c",
		"kubun3_id__c",
		"kubun4_id__c",
		"kubun5__c",
		"kubun6__c",
		"kubun7__c",
		"kubun8__c",
		"hw__c",
		"h_zip__c",
		"h_addr1__c",
		"h_addr2__c",
		"h_buil__c",
		"ng_h_hagaki__c",
		"h_tel1__c",
		"h_tel2__c",
		"h_tel3__c",
		"cellph1__c",
		"cellph2__c",
		"cellph3__c",
		"fax1__c",
		"fax2__c",
		"fax3__c",
		"c_mail__c",
		"c_conf__c",
		"ng_c_mail__c",
		"pc_mail__c",
		"pc_conf__c",
		"ng_pc_mail__c",
		"job_id__c",
		"jobkind_id__c",
		"syoujo_sub1__c",
		"syoujo_sub2__c",
		"t_change__c",
		"t_comment__c",
		"kana_g__c",
		"name_g__c",
		"fam_relation__c",
		"h_zip_g__c",
		"h_addr1_g__c",
		"h_addr2_g__c",
		"h_buil_g__c",
		"ng_h_hagaki_g__c",
		"h_tel1_g__c",
		"h_tel2_g__c",
		"h_tel3_g__c",
		"cellph1_g__c",
		"cellph2_g__c",
		"cellph3_g__c",
		"fax1_g__c",
		"fax2_g__c",
		"fax3_g__c",
		"c_mail_g__c",
		"pc_mail_g__c",
		"memberflg__c",
		"type_edit_date__c",
		"dm_list__c",
		"w_report__c",
		"m_pub__c",
		"wpatern__c",
		"week_id__c",
		"first_buy__c",
		"first_lesson__c",
		"last_lesson__c",
		"eraser_name__c",
		"reg_user__c",
		"edit_tenpoid__c",
		"edit_date__c",
		"edit_time__c",
		"del_date__c",
		"del_time__c",
		"raiten__c",
		"reedit_date__c",
		"unpay_total__c",
		"mov_id__c",
		"mov_id_all__c",
		"info_boad__c",
		"mail_delivery__c",
		"no_mess__c",
		"reserve_lock__c",
		"salt__c",
		"password_change_datetime__c",
		"login_trial_count__c"
	];*/
	
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
		$newMemType  = new CustMemType();
		$memtype_col = $this->cKey("memtype");
        //return $this->hasOne(CustMemType::Class,$memtype_primary,$memtype_col);
        return CustMemType::where($newMemType->cKey("mid"),$this->memtype)->get();
	}
	/*
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
	*/
    public function hasOneMemType() {
		if (!$this->joinAllMemType()->count()) {
			return null;
		}
		return $this->joinAllMemType()->first();
	}
    public function hasManyMemType() {
		if (!$this->joinAllMemType()->count()) {
			return null;
		}
		return $this->joinAllMemType();
	}	
	// モデル結合アクセサ（ではなく単なるモデル抽出）（所属店舗：複数対応）
    public function joinAllStoreTenpo() {
//		return $this->belongsToMany(TenpoMaster::Class,CustTenpo::class,$this->primaryKey,"tenpo_id");
		$ids = CustTenpo::where((new CustTenpo)->cKey("cid"),$this->cid)
						->where((new CustTenpo)->cKey("flg"),1)->get()
						->pluck((new CustTenpo)->cKey("tenpo_id"))->unique();
		return TenpoMaster::whereIn((new TenpoMaster)->cKey("tid"),$ids)->get();
    }
    public function hasOneStoreTenpo() {
		var_dump($this->joinAllStoreTenpo()); exit;
		if (!$this->joinAllStoreTenpo()->count()) {
			return null;
		}
		return $this->joinAllStoreTenpo()->first();
	}
    public function hasManyStoreTenpo() {
		if (!$this->joinAllStoreTenpo()->count()) {
			return null;
		}
//		print "<pre>"; print_r($this->joinAllStoreTenpo()->get()); print "</pre>"; exit;
//		return $this->joinAllStoreTenpo()->get();
		return $this->joinAllStoreTenpo();
	}
	// モデル結合アクセサ（ではなく単なるモデル抽出）（登録店舗）
    public function joinAllLastTenpo() {
		//return $this->hasOne(TenpoMaster::Class,(new TenpoMaster)->cKey("tid"),$this->cKey("last_tenpo"));
		return TenpoMaster::where((new TenpoMaster)->cKey("tid"),$this->last_tenpo)->get();
	}
    public function hasOneLastTenpo() {
		if (!$this->joinAllLastTenpo()->count()) {
			return null;
		}
		return $this->joinAllLastTenpo()->first();
	}
    public function hasManyLastTenpo() {
		if (!$this->joinAllLastTenpo()->count()) {
			return null;
		}
		return $this->joinAllLastTenpo();
	}
	// 全店舗情報（所属店舗＋登録店舗）
    public function hasManyAllTenpo() {
		return $this->hasManyStoreTenpo() ?? collect([]);
	}
	// モデル結合アクセサ（ではなく単なるモデル抽出）
	// 1.ログインユーザーの会員ID、契約変更履歴の会員IDからレコード抽出。
    public function joinSchedule() {
//		return $this->hasMany(Schedule::Class,(new Schedule)->cKey("sc_cid"),$this->cKey("cid"));
		return Schedule::where((new Schedule)->cKey("sc_cid"),$this->cid)->get();
	}
    public function getChangeSchedule() {
		if (!$this->joinSchedule()->count()) {
			return null;
		}
		// 所属店舗ID一覧を取得する
		$allTenpo   = $this->hasManyAllTenpo();
		$allTenpoId = $allTenpo->implode("tid",",");
		// 
//		return $this->joinSchedule->where("sc_flg",1); 
		return $this->joinSchedule()->where((new Schedule)->cKey("sc_flg"),1);
	}

	/**
	 * IDでcust情報を取得する
	 */
	public static function getUserInfoById($cid,$lock=false) {
		if (empty($cid)) {
			return null;
		}
		if ($lock) {
		    $authcust = Cust::lockForUpdate()->find($cid);
		} else {
		    $authcust = Cust::find($cid);
		}
        if (is_null($authcust)) {
			return null;
		}
		return $authcust;
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
		if (!$this->{$this->cKey("h_tel1")} || !$this->{$this->cKey("h_tel2")} || !$this->{$this->cKey("h_tel3")}) {
			return "";
		}
		return $this->{$this->cKey("h_tel1")}.$separater.$this->{$this->cKey("h_tel2")}.$separater.$this->{$this->cKey("h_tel3")};
	}
	/**
	 * 会員種別を返却する
	 */
	public function getMemTypeName() {
		$memtype = $append = "";
		// 会員種別情報取得
		$memtype = $this->hasOneMemType();
        // 変更スケジュール情報取得
		$changeSchedule = $this->getChangeSchedule() ?? collect([]);
		$allTenpo = $this->hasManyAllTenpo();
		// スケジュールがない場合、会員種別名をそのまま返却
	    if ($changeSchedule->isEmpty()) {
			if (!isset($memtype->{$memtype->cKey("type_name")})) {
			    return "";
			}
			return $memtype->{$memtype->cKey("type_name")};
		}
		// 会員種別変更があった場合、会員名に変更文言を付加
		// 前提：変更scheduleデータがある場合
	    if ($changeSchedule->count()) {
            foreach ($changeSchedule as $schedule) {
				$okTenpoIds = $allTenpo->pluck($this->cKey("tid"))->unique();
				// 店舗の変更履歴ではなく（所属店舗ID配列内に変更履歴の店舗IDがある）、会員種別変更時（現在の種別IDと違う）
				if (
					$schedule->{$schedule->cKey("sc_memtype")} != $memtype->{$schedule->cKey("mid")} && 
					in_array($schedule->{$schedule->cKey("sc_tenpo")},$okTenpoIds->toArray())
				) {
					$append = "（変更登録あり）";
					$memtype = $memtype->{$memtype->cKey('type_name')};
					break;
			    }
			}
		}
		return $memtype.$append;


		if (is_null($memtype)) {
			return "";
		}
		return $memtype->{$memtype->cKey('type_name')} ?? "";
	}
	/**
	 * 所属店舗を返却する（TBD:他箇所で取得する処理があればそれを用いる）
	 */
	public function getStoreNames() {
        $append = "";
		//"銀座（GNZ）、自由が丘（JYO）",	
		$all = $this->hasManyAllTenpo();
		if ($all->isEmpty()) {
			return "";
		}
        $memtype = $this->hasOneMemType();		
		$okTenpoIds = $all->pluck($this->cKey("tid"))->unique();

		// 変更スケジュール情報を取得
		$changeSchedule = $this->getChangeSchedule() ?? collect([]);
	    if ($changeSchedule->count()) {
            foreach ($changeSchedule as $schedule) {
				// 会員種別が現在と同一かつ、所属店舗ID配列内に変更履歴の店舗IDがない場合
				if (
					$schedule->{$schedule->cKey("sc_memtype")} == $memtype->{$schedule->cKey("mid")} && 
					!in_array($schedule->{$schedule->cKey("sc_tenpo")},$okTenpoIds->toArray())
				) {
					$append = "（変更登録あり）";
					break;
			    }
			}
		}
		// 銀座（GNZ）という文字列を作る
		$tenpostr = $all->map(function($tenpo) {
		    return ["tenpo_str" => $tenpo->{$this->cKey("tenpo_name")}];
		});
		return $tenpostr->implode("tenpo_str","、").$append;
	}
	/**
	 * 案内メール設定を返却する
	 */
	public function getDmLists() {
		//"1,,,,",
		return $this->{$this->cKey("dm_list")} ?? ",,,,5";
	}
	/**
	 * PCメールアドレス予約確認メール設定を返却する
	 */
	public function getPcConf() {
		return $this->{$this->cKey("pc_conf")} ?? 0;
	}
	/**
	 * GMO会員IDを返却する
	 */
	public function getGmoId() {
		return $this->{$this->cKey("gmo_credit")} ?? null;
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

    /**
     * ユーザーの年齢を取得する
     * @param $year
     * @param $month
     * @param $day
     * @return
     */
    public static function getUserAge($year,$month,$day) {
        // どれか一つでも空の場合
        if (empty($year) || empty($month) || empty($day)) {
            return null;
        }
        if (is_null($year) || is_null($month) || is_null($day)) {
            return null;
        }
        // パースして年齢を取得
        $age = Carbon::parse($year.'/'.$month.'/'.$day)->age;
        return $age;
    }

    /**
     * ユーザーの年齢を判定する
     * @param $age
     * @return
     */
    public static function validateUserAge($age) {
        // 空/nullの場合
        if (empty($age) || is_null($age)) {
            throw new IllegalParameterException();
        }
        // 16歳未満は例外
        if ($age < self::MEMBER_POSSIBLE_AGE) {
            throw new IllegalParameterException('16歳未満は登録できません。');
        }
        return true;
    }

}