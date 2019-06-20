<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CancelHist extends Model
{

    /** LessonFeelのテーブルサフィックス */
    const LF = '';

    /** テーブル名 */
    protected $table = 'cancel_hist' . self::LF;

    /** 主キー */
    protected $primaryKey = 'canid';

    protected $guarded = [
        'canid',
    ];

    const CREATED_AT = 'reg_date';
    const UPDATED_AT = null;
    const DELETED_AT = null;


}
