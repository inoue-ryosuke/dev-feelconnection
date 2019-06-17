<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Libraries\Logic\ReservationModal\CommonLogic;
use App\Libraries\Logic\ReservationModal\VaidationLogic;
use App\Libraries\Logic\ReservationModal\ReservationModalMasterResource;
use App\Libraries\Logic\ReservationModal\SheetStatusMasterResource;
use App\Libraries\Logic\ReservationModal\SheetStatusExtendMasterResource;
use App\Models\OrderLesson;

use App\Exceptions\BadRequestException;
use App\Exceptions\ApplicationException;
use App\Libraries\Logic\ReservationModal\SheetManager;

/**
 * 予約モーダルコントローラー
 *
 */
class ReservationModalController extends Controller
{
    /**
     * 予約モーダルAPI
     *
     * @GET("api/reservation_modal/{sid}", as="api.reservation_modal.get")
     * @param Request $request
     * @param int $sid レッスンスケジュールIDハッシュ値(shift_master.shiftid_hash)
     * @return \Illuminate\Http\JsonResponse
     */
    public function reservationModalApi(Request $request, $sid)
    {
        $params = array('sid' => $sid);

        // レッスンスケジュールIDハッシュのバリデーション
        if (!VaidationLogic::validateShiftIdHash($params)) {
            // エラー
            throw new BadRequestException('レッスンスケジュールIDが不正です。');
        }

        // 予約モーダルで必要なマスターデータ取得
        $resource = new ReservationModalMasterResource($sid);
        if(!$resource->createRedisResource()) {
            // Redisキャッシュの取得に失敗
            $resource->createDBResource();
        }

        $shiftMaster = $resource->getShiftMasterResource();
        $lessonMaster = $resource->getLessonMasterResource();
        $tenpoMaster = $resource->getTenpoMasterResource();
        $custMaster = $resource->getCustMasterResource();

        // ネット予約公開日時が過去の日付かどうか
        if (!VaidationLogic::isOpenDateTimePassed($shiftMaster['open_datetime'])) {
            // ネット予約公開日時が未来
            throw new BadRequestException('未公開のレッスンです。');
        }

        // レッスンスケジュールレッスン開催日時(shift_master.shift_date, shift_master.ls_st)が未来の日付かどうか
        if (!VaidationLogic::isShiftDateTimeComing($shiftMaster['shift_date'], $shiftMaster['ls_st'])) {
            throw new BadRequestException('無効なレッスンです。');
        }

        // ネット・トライアル会員が体験予約不可のレッスンを指定した場合エラー
        if (!VaidationLogic::canReserveByNetTrialMember($custMaster['memtype'], $shiftMaster['taiken_les_flg'])) {
            // 体験予約不可のレッスンを指定
            throw new BadRequestException('ネット・トライアル会員が体験予約不可のレッスンを指定しました。');
        }

        // 体験レッスン受講済みでない状態のバリデーション
        if (!VaidationLogic::validateNotTakenTrialLesson($shiftMaster, $custMaster)) {
            throw new BadRequestException('
                体験予約不可のレッスンが指定されました。\n
                予約済みの体験レッスンより後の日付を指定してください。
            ');
        }

        // 予約受付時間内かどうか
        if (!VaidationLogic::validateTimeLimit($shiftMaster['shift_date'], $shiftMaster['ls_st'], $shiftMaster['tlimit'])) {
            // 予約受付時間外
            throw new ApplicationException('予約受付時間外です。', 409);
        }

        // 座席情報取得
        $sheetManager = new SheetManager($shiftMaster['shiftid']);
        $sheetManager->initStudio();
        $sheetManager->setSheetStatusAndModalType($custMaster['cid']);
        $sheetManager->fillNotSpecialSheetTrial($custMaster['memtype']);

        return response()->json([
            'response_code' => Response::HTTP_OK,
            'modal_type' => $sheetManager->getReservationModalType(),
            'lesson_date' => $shiftMaster['shift_date'],
            'lesson_day_week' => CommonLogic::getDayWeek($shiftMaster['shift_date']),
            'lesson_start_time' => $shiftMaster['ls_st'],
            'lesson_end_time' => $shiftMaster['ls_et'],
            'store_name' => $tenpoMaster['tenpo_name'],
            'studio_image_path' => 'https://xxx/yyy/zzz', // TODO スタジオ画像パス
            'lesson_name' => $resource->getLessonName(),
            'instructor_name' => $shiftMaster['instructor_name'],
            'trial_capacity' => $shiftMaster['taiken_capa'],
            'instructor_image_path' => 'https://xxx/yyy/zzz', // TODO インストラクター画像パス
            'cancel_waiting_no' => $shiftMaster['taiken_capa'],
            'sheets' => $sheetManager->getResponseSheetsArray()

        ])->setStatusCode(Response::HTTP_OK);
    }

    /**
     * バイク予約状態取得API
     *
     * @GET("api/sheet_status/{sid}/{sheet_no}", as="api.sheet_status.get")
     * @param Request $request
     * @param int $sid レッスンスケジュールIDハッシュ値(shift_master.shiftid_hash)
     * @param int $sheet_no 座席番号
     * @return \Illuminate\Http\JsonResponse
     */
    public function sheetStatusApi(Request $request, $sid, $sheet_no)
    {
        $params = array('sid' => $sid, 'sheet_no' => $sheet_no);

        // レッスンスケジュールIDハッシュ、座席番号のバリデーション
        if (!VaidationLogic::validateShiftIdHashAndSheetNo($params)) {
            // エラー
            throw new BadRequestException('レッスンスケジュールID、座席番号が不正です。');
        }

        // バイク予約状態取得APIで必要なマスターデータ取得
        $resource = new SheetStatusMasterResource($sid);
        if(!$resource->createRedisResource()) {
            // Redisキャッシュの取得に失敗
            $resource->createDBResource();
        }

        $shiftMaster = $resource->getShiftMasterResource();
        $custMaster = $resource->getCustMasterResource();

        // ネット予約公開日時が過去の日付かどうか
        if (!VaidationLogic::isOpenDateTimePassed($shiftMaster['open_datetime'])) {
            // ネット予約公開日時が未来
            throw new BadRequestException('未公開のレッスンです。');
        }

        // 座席情報取得
        $sheetManager = new SheetManager($shiftMaster['shiftid']);
        $sheetManager->initStudio();

        // TODO 座席番号は、スタジオの座席数を超えた数値はエラー
        if (!$sheetManager->isSheetNoValid($sheet_no)) {

        }

        // 座席予約状態取得
        $sheetStatus = $sheetManager->getSheetStatus($sheet_no, $custMaster['cid'], $custMaster['memtype']);

        return response()->json([
            'response_code' => Response::HTTP_OK,
            'status' => $sheetStatus
        ])->setStatusCode(Response::HTTP_OK);
    }

    /**
     * バイク枠仮確保状態確認・更新API
     *
     * @POST("api/sheet_status_extend/{sid}/{sheet_no}", as="api.sheet_status_extend.get")
     * @param Request $request
     * @param int $sid レッスンスケジュールIDハッシュ値(shift_master.shiftid_hash)
     * @param int $sheet_no 座席番号
     * @return \Illuminate\Http\JsonResponse
     */
    public function sheetStatusExtendApi(Request $request, $sid, $sheet_no)
    {
        $params = array('sid' => $sid, 'sheet_no' => $sheet_no);

        // レッスンスケジュールIDハッシュ、座席番号のバリデーション
        if (!VaidationLogic::validateShiftIdHashAndSheetNo($params)) {
            // エラー
            throw new BadRequestException('レッスンスケジュールID、座席番号が不正です。');
        }

        // バイク枠仮確保状態確認・更新APIで必要なマスターデータ取得
        $resource = new SheetStatusExtendMasterResource($sid);
        if(!$resource->createRedisResource()) {
            // Redisキャッシュの取得に失敗
            $resource->createDBResource();
        }

        $shiftMaster = $resource->getShiftMasterResource();
        $custMaster = $resource->getCustMasterResource();

        // レッスンスケジュールレッスン開催日時(shift_master.shift_date, shift_master.ls_st)が未来の日付かどうか
        if (!VaidationLogic::isShiftDateTimeComing($shiftMaster['shift_date'], $shiftMaster['ls_st'])) {
            throw new BadRequestException('無効なレッスンです。');
        }

        // ネット・トライアル会員が体験予約不可のレッスンを指定した場合エラー
        if (!VaidationLogic::canReserveByNetTrialMember($custMaster['memtype'], $shiftMaster['taiken_les_flg'])) {
            // 体験予約不可のレッスンを指定
            throw new BadRequestException('ネット・トライアル会員が体験予約不可のレッスンを指定しました。');
        }

        // 体験レッスン受講済みでない状態のバリデーション
        if (!VaidationLogic::validateNotTakenTrialLesson($shiftMaster, $custMaster)) {
            throw new BadRequestException('
                体験予約不可のレッスンが指定されました。\n
                予約済みの体験レッスンより後の日付を指定してください。
            ');
        }

        // 予約受付時間内かどうか
        if (!VaidationLogic::validateTimeLimit($shiftMaster['shift_date'], $shiftMaster['ls_st'], $shiftMaster['tlimit'])) {
            // 予約受付時間外
            throw new ApplicationException('予約受付時間外です。', 409);
        }

        // 座席情報取得
        $sheetManager = new SheetManager($shiftMaster['shiftid']);
        $sheetManager->initStudio();

        // TODO 座席番号は、スタジオの座席数を超えた数値はエラー
        if (!$sheetManager->isSheetNoValid($sheet_no)) {

        }

        // 体験レッスンで体験バイクが指定されていない場合はエラー
        if ($shiftMaster['taiken_les_flg'] && !$sheetManager->isSheetNoValid($sheet_no)) {
            throw new BadRequestException('体験バイクが指定されていません。');
        }

        return response()->json([
            'response_code' => Response::HTTP_CREATED,
            'all_resource' => $resource->getAllResource()
        ])->setStatusCode(Response::HTTP_CREATED);
    }
}
