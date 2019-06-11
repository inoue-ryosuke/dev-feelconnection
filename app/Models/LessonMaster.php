<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LessonMaster extends Model
{
    /** Salesforceのテーブルサフィックス(__c) */
    const SF = '';

    /** テーブル名 */
    protected $table = 'lesson_master' . self::SF;
    /** 主キー */
    protected $primaryKey = 'lid';

    protected $guarded = [
        'lessontime',
    ];

    public $timestamps = false;
}
