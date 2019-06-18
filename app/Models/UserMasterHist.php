<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use App\Exceptions\IllegalParameterException;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Libraries\Auth\Authenticatable as AuthenticatableTrait;

class UserMasterHist extends BaseModel implements Authenticatable
{
    use AuthenticatableTrait;
    /**
     * @var string テーブル名
     */
    protected $table = 'user_master_hist';
    protected $primaryKey = 'id';
    protected $fillable = [

    ];

    // テーブルにtimestamps系カラムがないのでfalseを設定
    public $timestamps = false;

    const VALID = 1;

}
