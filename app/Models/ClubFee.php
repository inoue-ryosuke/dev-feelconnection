<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use App\Libraries\Auth\Authenticatable as AuthenticatableTrait;

class ClubFee extends BaseFormModel implements Authenticatable
{
    use AuthenticatableTrait;

    /** Salesforceのテーブルサフィックス(__c) */
    const SF = '';

    /** テーブル名 */
    protected $table = 'club_fee' . self::SF;

    /** 主キー */
    protected $primaryKey = 'cfid';

    protected $guarded = [
        'cfid',
        'pay',
        'kessai',
        'card',
        'memo',
        'upid',
        'withdraw_fail',
        'withdraw_date'
    ];

    const CREATED_AT = null;
    const UPDATED_AT = null;
    const DELETED_AT = null;


}
