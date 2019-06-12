<?php

namespace App\Models;

use App\Exceptions\IllegalParameterException;
use Illuminate\Database\Eloquent\Model;

class Invite extends BaseModel
{
    //
    /**
     * @var string テーブル名
     */
    protected $table = 'invite';
    protected $primaryKey = '';

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
        $count = self::where('invite.invite_code', $inviteCode)->first();
        return $count > 0;
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
     * 体験レッスンの予約が可能か判定
     * @param $lid
     * @return
     */
    public static function isReserveLesson($lid) {
        // 空もしくはnullの場合
        if(empty($lid) || is_null($lid)) {
            throw new IllegalParameterException();
        }
//        $record = self::where('invite.invite_code', $inviteCode)->first();
//        return $record;
    }
}
