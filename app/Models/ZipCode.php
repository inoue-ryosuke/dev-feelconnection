<?php

namespace App\Models;

use App\Exceptions\IllegalParameterException;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use App\Libraries\Auth\Authenticatable as AuthenticatableTrait;

class ZipCode extends BaseFormModel implements Authenticatable
{
    use AuthenticatableTrait;
    /** Salesforceのテーブルサフィックス(__c) */
    const SF = '';

    /** テーブル名 */
    protected $table = 'zip_code' . self::SF;

    /** 主キー */
//    protected $primaryKey = '';

    protected $fillable = [
        'code',
        'address1',
        'address2',
        'address3',
    ];

    /**
     * 郵便番号がレコードに存在するか確認
     * @param $zipCode
     * @return
     */
    public static function isExistsZipCode($zipCode) {
        // 空もしくはnullの場合
        if(empty($zipCode) || is_null($zipCode)) {
            return false;
        }

        return self::where('zip_code.code', $zipCode)->exists();
    }

    /**
     * 郵便番号からレコードを取得
     * @param $zipCode
     * @return
     */
    public static function findByCode($zipCode) {
        // 空もしくはnullの場合
        if(empty($zipCode) || is_null($zipCode)) {
            throw new IllegalParameterException();
        }
        $record = self::where('zip_code.code', $zipCode)->first();
        return $record;
    }

}
