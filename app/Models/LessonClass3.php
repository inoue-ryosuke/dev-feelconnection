<?php

namespace App\Models;

use App\Exceptions\IllegalParameterException;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Libraries\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Database\Eloquent\Model;

class LessonClass3 extends BaseFormModel implements Authenticatable
{
    use AuthenticatableTrait;
    /** Salesforceのテーブルサフィックス(__c) */
    const SF = '';

    /** テーブル名 */
    protected $table = 'lesson_class3' . self::SF;

    /** 主キー */
    protected $primaryKey = 'id';

    protected $fillable = [
        'flg',
        'name',
        'seq',
    ];
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'modified_at';
    const DELETED_AT = 'deleted_at';


}
