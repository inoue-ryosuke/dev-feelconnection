<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShiftMaster extends Model
{
    /** テーブル名 */
    protected $table = 'shift_master';
    /** 主キー */
    protected $primaryKey = 'shiftid';

    protected $guarded = [
        'updatetime',
        'shift_type',
        'patern',
        'wmid'
    ];

    public $timestamps = false;


    public static function getReservationModalResource(string $shiftIdHash) {
        return self::where('shiftid_hash', '=', $shiftIdHash)->first();
    }
}
