<?php

namespace App\Models;

use App\Exceptions\IllegalParameterException;
use Illuminate\Database\Eloquent\Model;

class Invite extends BaseFormModel
{
    use TokenizerTrait;
    //
    /**
     * @var string テーブル名
     */
    protected $table = 'invite';
    protected $primaryKey = 'id';
    protected $cidKey = 'cid';

    protected $fillable = [
        'cid',
        'lid'
    ];

    const CID = 'cid';

    /**
     * 紹介コードがレコードに存在するか確認
     * @param $inviteCode
     * @return
     */
    public static function isExistsInviteCode($inviteCode) {
        // 空もしくはnullの場合
        if(empty($inviteCode) || is_null($inviteCode)) {
            return false;
        }
//        $count = self::where('invite.invite_code', $inviteCode)->first();
//        return $count > 0;
        return self::where('invite.invite_code', $inviteCode)->exists();
    }

    /**
     * 紹介コードからレコードを取得
     * @param $inviteCode
     * @return
     */
    public static function findByCode($inviteCode) {
        // 空もしくはnullの場合
        if(empty($inviteCode) || is_null($inviteCode)) {
            throw new IllegalParameterException();
        }
        $record = self::where('invite.invite_code', $inviteCode)->first();
        return $record;
    }

    /**
     * 紹介コードからレコードを取得。存在しない場合は例外。
     * @param $inviteCode
     * @return
     */
    public static function findByCodeOrFail($inviteCode) {
        // 空もしくはnullの場合
        if(empty($inviteCode) || is_null($inviteCode)) {
            throw new IllegalParameterException();
        }
        $query = self::where('invite.invite_code', $inviteCode);

        if ($query->exists()) {
            // レコードが存在する場合
            return $query->first();
        } else {
            // レコードが存在しない場合は例外
            throw new IllegalParameterException();
        }

    }

}
