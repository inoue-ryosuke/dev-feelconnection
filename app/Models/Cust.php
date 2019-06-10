<?php
namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use App\Exceptions\IllegalParameterException;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Libraries\Auth\Authenticatable as AuthenticatableTrait;

class Cust extends BaseFormModel implements Authenticatable
{
//    use AuthenticatableTrait, ListingTrait, SoftDeletes, TokenizerTrait,UserTrait, UserStatusTrait;
    use AuthenticatableTrait;

    /**
     * @var string テーブル名
     */
    protected $table = 'cust_master';
    protected $primaryKey = 'cid';
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

}