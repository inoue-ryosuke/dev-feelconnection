<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use App\Exceptions\IllegalParameterException;
use App\Libraries\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Database\Eloquent\Model;

class TenpoAreaMaster extends BaseFormModel implements Authenticatable
{
    use AuthenticatableTrait;
    //
    /**
     * @var string テーブル名
     */
    protected $table = 'tenpo_area_master';
    protected $primaryKey = 'id';

    // 有効/無効フラグ
    const VALID = 1;
    const INVALID = 0;

    protected $fillable = [
		"flg",
		"name",
		"seq",
		"iname",
		"itxtcol",
		"ibgcol",
		"description"
	];
    const UPDATED_AT = null;
	const DELETED_AT = null;
}
