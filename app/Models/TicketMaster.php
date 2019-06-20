<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketMaster extends Model
{

    /** LessonFeelのテーブルサフィックス */
    const LF = '';

    /** テーブル名 */
    protected $table = 'ticket_master' . self::LF;

    /** 主キー */
    protected $primaryKey = 'tkid';

    protected $guarded = [
        'tkid',
        //'at_flg'
    ];

    const CREATED_AT = null;
    const UPDATED_AT = null;
    const DELETED_AT = null;


}
