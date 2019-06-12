<?php namespace App\Libraries\Logic\Invite;

use App\Exceptions\IllegalParameterException;
use App\Libraries\Logger;
use App\Libraries\Logic\BaseLogic;
use App\Models\LessonMaster;
use App\Models\ShiftMaster;
use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Invite;

class SelectLogic extends BaseLogic
{

    /**
     * 起動時コンストラクタ
     */
    public function __construct()
    {
//        logger('Category Logic construct');
    }

    /**
    * 紹介コードの有効性判定
    * @param $inviteCode
    * @return
    */
    public function validateInviteCode($inviteCode)
    {
        logger('Invite SelectLogic validateInviteCode start');
        logger('inviteCode');
        logger($inviteCode);
        // スタブレスポンス
        return $response = [
            'result_code' => 0,
            'lid' => 51,
            'lesson_flag' => 1,
            'friend_flag' => 0,
        ];

        // テーブルに存在しない紹介コードの場合
        if (!Invite::isExistsInviteCode($inviteCode)) {
            throw new IllegalParameterException();
        }

        //レコードを取得
        $invite = Invite::findByCodeOrFail($inviteCode);

        // レッスンIDの登録がない場合は友人紹介
        if (is_null($invite->lid)) {
            logger('Invite SelectLogic validateInviteCode friend');
            return $response = [
                'result_code' => 0,
                'friend_flag' => 1,
            ];
        }

        // lesson_master/shift_masterテーブルを参照して予約が可能か確認
        if ($this->isReservableLesson($invite)) {
            logger('Invite SelectLogic validateInviteCode lesson');
            // 予約可能
            return $response = [
                'result_code' => 0,
                'lid' => $invite->lid,
                'lesson_flag' => 1,
                'friend_flag' => 0,
            ];
        }

    }

    /**
     * LessonMasterとShiftMasterを参照してレッスン予約可能か判定
     * @param $invite
     * @return bool
     */
    private function isReservableLesson($invite): bool
    {
        return LessonMaster::isReservableLesson($invite->lid, LessonMaster::TRIAL) && ShiftMaster::isReservableLesson($invite->lid, LessonMaster::TRIAL);
    }


}
