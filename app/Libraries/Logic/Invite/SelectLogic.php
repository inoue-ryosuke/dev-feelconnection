<?php namespace App\Libraries\Logic\Invite;

use App\Exceptions\IllegalParameterException;
use App\Libraries\Logger;
use App\Libraries\Logic\BaseLogic;
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
//        // テーブルに存在しない紹介コードの場合
//        if (!Invite::isExistsInviteCode($inviteCode)) {
//            throw new IllegalParameterException();
//        }
//
//        $invite = Invite::findByCode($inviteCode);
//        // レッスンIDの登録がない場合は友人紹介
//        if (is_null($invite->lid)) {
//            $response = [
//                'result_code' => 0,
//                'friend_flag' => 1,
//            ];
//        } else {
//            // lesson_master/shift_masterテーブルを参照して予約が可能か確認
////            $lessonFlag = Invite::isReserveLesson($invite->lid);
//            $lessonFlag = 1;
//            $response = [
//                'result_code' => 0,
//                'lid' => $invite->lid,
//                'lesson_flag' => $lessonFlag,
//                'friend_flag' => 0,
//            ];
//        }
        $response = [
            'result_code' => 0,
            'lid' => 51,
            'lesson_flag' => 1,
            'friend_flag' => 0,
        ];
        logger('Invite SelectLogic validateInviteCode end');
        return $response;

    }







}
