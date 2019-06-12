<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderLesson extends Model
{
    /** LessonFeelのテーブルサフィックス */
    const LF = '';

    /** テーブル名 */
    protected $table = 'order_lesson' . self::LF;
    /** 主キー */
    protected $primaryKey = 'oid';

    protected $guarded = [
        'experience',
    ];

    public $timestamps = false;


}
