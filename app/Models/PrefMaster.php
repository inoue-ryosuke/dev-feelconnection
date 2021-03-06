<?php
namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use App\Exceptions\IllegalParameterException;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Libraries\Auth\Authenticatable as AuthenticatableTrait;
use Carbon\Carbon;

class PrefMaster extends BaseFormModel implements Authenticatable
{
//    use AuthenticatableTrait, ListingTrait, SoftDeletes, TokenizerTrait,UserTrait, UserStatusTrait;
    use AuthenticatableTrait;

    /**
     * @var string テーブル名
     */
    protected $table = 'pref_master';
    protected $primaryKey = 'pid';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
		"name",
		"coutry",
	];
    const CREATED_AT = null;
    const UPDATED_AT = null;
    const DELETED_AT = null;
    /**
     * 国に対応する都道府県一覧を返却する
     */
    public static function getPref($country="JPN") {
        return self::where("country",$country)->get()->map(function($item) {
            return $item->only(["pid","name"]);
            /* 項目名を変えてレスポンスしたい場合はこちら
            return [
                "pid" =>$item->pid,
                "name"=>$item->name
            ];*/
        });
    }
}
