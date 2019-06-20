<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\DB;
use App\Libraries\Auth\Authenticatable as AuthenticatableTrait;
use App\Models\Constant\ClubFeeFlg;

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

    /**
     * 月間予約枠があるか確認
     * TODO: 要現行仕様確認
     *
     * @param int $customerId 会員ID
     * @return bool
     */
    public static function hasMonthlyReservationCount(int $customerId) {
        $currentDateTime = new \DateTime();
        $currentDateText = $currentDateTime->format('Y-m-d');

        $count = self::where('customer_id', '=', $customerId)
            ->where('flg', '=', ClubFeeFlg::VALID)
            ->where('tcount', '<', DB::raw('tcount_max'))
            ->where('start_date', '>=', $currentDateText)
            ->where(function($query) use ($currentDateText){
                $query->whereNull('expire')
                    ->orWhere('expire', '>', $currentDateText);
            })
            ->count();

         return $count > 0;
    }

}
