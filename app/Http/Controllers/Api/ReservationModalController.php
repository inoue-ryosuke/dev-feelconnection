<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Libraries\Logic\ReservationModal\CommonLogic;
use App\Libraries\Logic\ReservationModal\VaidationLogic;
use App\Libraries\Logic\ReservationModal\ReservationModalMasterResource;
use App\Libraries\Logic\ReservationModal\ShiftCustMasterResource;
use App\Libraries\Logic\ReservationModal\ShiftTenpoCustMasterResource;
use App\Libraries\Logic\ReservationModal\SheetManager;
use App\Libraries\Logic\ReservationModal\ReservationLogic;
use App\Models\Constant\ReservationTransitionType;

/**
 * 予約モーダルコントローラー
 *
 * @Middleware({"api", "auth:customer"})
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
            return CommonLogic::getErrorJsonResponse(
                Response::HTTP_BAD_REQUEST,
                CommonLogic::getErrorArray('Invalid sid', '無効なレッスンスケジュールIDです、', $params)
            );
        }

        // 予約モーダルで必要なマスターデータ取得
        $resource = new ReservationModalMasterResource($sid);
        if(!$resource->createRedisResource()) {
            // Redisキャッシュの取得に失敗
            $resource->createDBResource();
        }
        // 未来の会員種別・所属店舗登録
        $resource->setFutureMemberTypeTenpos();

        $shiftMaster = $resource->getShiftMasterResource();
        $tenpoMaster = $resource->getTenpoMasterResource();
        $custMaster = $resource->getCustMasterResource();
        $futureMemberType = $resource->getFutureMemberType();

        // ネット予約公開日時が過去の日付かどうか
        if (!VaidationLogic::isOpenDateTimePassed($shiftMaster['open_datetime'])) {
            // ネット予約公開日時が未来
            return CommonLogic::getErrorJsonResponse(
                Response::HTTP_BAD_REQUEST,
                CommonLogic::getErrorArray(
                    'Invalid lesson',
                    '未公開のレッスンです。',
                    array_merge($params))
            );
        }

        // レッスン予約・キャンセル排他ロック(同時予約・キャンセルを防ぐ)
        if (VaidationLogic::isReserveLock($custMaster['reserve_lock'])) {
            return CommonLogic::getErrorJsonResponse(
                Response::HTTP_CONFLICT,
                CommonLogic::getErrorArray(
                    'Can not reserve lesson',
                    'レッスンを予約できません。',
                    array_merge($params))
                );
        }

        // 予約受付時間内かどうか
        if (!VaidationLogic::validateTimeLimit($shiftMaster['shift_date'], $shiftMaster['ls_st'], $shiftMaster['tlimit'])) {
            // 予約受付時間外
            return CommonLogic::getErrorJsonResponse(
                Response::HTTP_CONFLICT,
                CommonLogic::getErrorArray(
                    'Can not reserve sheet',
                    '予約受付時間外です。',
                    array_merge($params,
                        [ 'shift_date' => $shiftMaster['shift_date'], 'start_time' => $shiftMaster['ls_st'], 'time_limit' => $shiftMaster['tlimit'] ]))
                );
        }

        // ネット・トライアル会員が体験予約不可のレッスンを指定した場合エラー
        if (!VaidationLogic::canReserveByNetTrialMember($custMaster['memtype'], $shiftMaster['taiken_les_flg'])) {
            // 体験予約不可のレッスンを指定
            return CommonLogic::getErrorJsonResponse(
                Response::HTTP_BAD_REQUEST,
                CommonLogic::getErrorArray(
                    'Invalid lesson',
                    'ネット・トライアル会員が体験予約不可のレッスンを指定しました。',
                    array_merge($params, [ 'memtype' => $custMaster['memtype'], 'taiken_les_flg' => $shiftMaster['taiken_les_flg'] ]))
            );
        }

        // ログインユーザーの会員種別が、予約可能会員種別(tenpo_master.tenpo_memtype)かどうか。
        if (!VaidationLogic::isMemberTypeReservable($futureMemberType, $tenpoMaster['tenpo_memtype'])) {
            return CommonLogic::getErrorJsonResponse(
                Response::HTTP_BAD_REQUEST,
                CommonLogic::getErrorArray(
                    'Invalid lesson',
                    'レッスンの店舗を予約可能な会員種別ではありません。',
                    array_merge($params, [ 'memtype' => $futureMemberType ]))
            );
        }

        // 体験レッスン未受講状態のバリデーション
        $taikenResults = VaidationLogic::validateNotTakenTrialLesson($shiftMaster, $custMaster);
        if (!$taikenResults['result']) {
            return CommonLogic::getErrorJsonResponse(
                Response::HTTP_BAD_REQUEST,
                CommonLogic::getErrorArray(
                    'Invalid lesson',
                    $taikenResults['error_message'],
                    array_merge($params))
            );
        }

        // 座席情報取得
        $sheetManager = new SheetManager($shiftMaster['shiftid'], $custMaster['cid'], $custMaster['memtype']);
        $sheetManager->initStudio();
        $sheetManager->setSheetStatusByOrderLesson();
        $sheetManager->setSheetStatusBySheetLock();
        $sheetManager->fillNotSpecialTrialSheet();
        $sheetManager->setModalType();

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
            return CommonLogic::getErrorJsonResponse(
                Response::HTTP_BAD_REQUEST,
                CommonLogic::getErrorArray('Invalid sid', '無効なレッスンスケジュールIDです。', $params),
                CommonLogic::getErrorArray('Invalid sheet_no', '無効な座席番号です。', $params)
            );
        }

        // バイク予約状態取得APIで必要なマスターデータ取得
        $resource = new ShiftCustMasterResource($sid);
        if(!$resource->createRedisResource()) {
            // Redisキャッシュの取得に失敗
            $resource->createDBResource();
        }

        $shiftMaster = $resource->getShiftMasterResource();
        $custMaster = $resource->getCustMasterResource();

        // ネット予約公開日時が過去の日付かどうか
        if (!VaidationLogic::isOpenDateTimePassed($shiftMaster['open_datetime'])) {
            // ネット予約公開日時が未来
            return CommonLogic::getErrorJsonResponse(
                Response::HTTP_BAD_REQUEST,
                CommonLogic::getErrorArray(
                    'Invalid lesson',
                    '未公開のレッスンです。',
                    array_merge($params))
            );
        }

        // 座席情報取得
        $sheetManager = new SheetManager($shiftMaster['shiftid'], $custMaster['cid'], $custMaster['memtype']);
        $sheetManager->initStudio();
        $sheetManager->setSheetStatusByOrderLesson();
        $sheetManager->setSheetStatusBySheetLock();
        $sheetManager->fillNotSpecialTrialSheet();

        // TODO 座席番号は、スタジオの座席数を超えた数値はエラー
        if (!$sheetManager->isSheetNoValid($sheet_no)) {
            return CommonLogic::getErrorJsonResponse(
                Response::HTTP_BAD_REQUEST,
                CommonLogic::getErrorArray('Invalid sheet_no', '無効な座席番号です、', $params)
            );
        }

        // 座席予約状態取得
        $sheetStatus = $sheetManager->getSheetStatus($sheet_no);

        return response()->json([
            'response_code' => Response::HTTP_OK,
            'status' => $sheetStatus
        ])->setStatusCode(Response::HTTP_OK);
    }

    /**
     * バイク枠仮確保状態確認・更新API
     *
     * @POST("api/sheet_status_extend", as="api.sheet_status_extend.post")
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sheetStatusExtendApi(Request $request)
    {
        $params = $request->all();;

        // レッスンスケジュールIDハッシュ、座席番号のバリデーション
        if (!VaidationLogic::validateShiftIdHashAndSheetNo($params)) {
            // エラー
            return CommonLogic::getErrorJsonResponse(
                Response::HTTP_BAD_REQUEST,
                CommonLogic::getErrorArray('Invalid sid', '無効なレッスンスケジュールIDです', $params),
                CommonLogic::getErrorArray('Invalid sheet_no', '無効な座席番号です', $params)
            );
        }

        $sid = $params['sid'];
        $sheet_no = $params['sheet_no'];

        // バイク枠仮確保状態確認・更新APIで必要なマスターデータ取得
        $resource = new ShiftTenpoCustMasterResource($sid);
        if(!$resource->createRedisResource()) {
            // Redisキャッシュの取得に失敗
            $resource->createDBResource();
        }
        // 未来の会員種別・所属店舗登録
        $resource->setFutureMemberTypeTenpos();

        $shiftMaster = $resource->getShiftMasterResource();
        $tenpoMaster = $resource->getTenpoMasterResource();
        $custMaster = $resource->getCustMasterResource();
        $futureMemberType = $resource->getFutureMemberType();

        // ネット予約公開日時が過去の日付かどうか
        if (!VaidationLogic::isOpenDateTimePassed($shiftMaster['open_datetime'])) {
            // ネット予約公開日時が未来
            return CommonLogic::getErrorJsonResponse(
                Response::HTTP_BAD_REQUEST,
                CommonLogic::getErrorArray(
                    'Invalid lesson',
                    '未公開のレッスンです。',
                    array_merge($params))
            );
        }

        // 予約受付時間内かどうか
        if (!VaidationLogic::validateTimeLimit($shiftMaster['shift_date'], $shiftMaster['ls_st'], $shiftMaster['tlimit'])) {
            // 予約受付時間外
            return CommonLogic::getErrorJsonResponse(
                Response::HTTP_CONFLICT,
                CommonLogic::getErrorArray(
                    'Can not reserve sheet',
                    '予約受付時間外です。',
                    array_merge($params,
                        [ 'shift_date' => $shiftMaster['shift_date'], 'start_time' => $shiftMaster['ls_st'], 'time_limit' => $shiftMaster['tlimit'] ]))
                );
        }

        // ネット・トライアル会員が体験予約不可のレッスンを指定した場合エラー
        if (!VaidationLogic::canReserveByNetTrialMember($custMaster['memtype'], $shiftMaster['taiken_les_flg'])) {
            // 体験予約不可のレッスンを指定
            return CommonLogic::getErrorJsonResponse(
                Response::HTTP_BAD_REQUEST,
                CommonLogic::getErrorArray(
                    'Invalid lesson',
                    'ネット・トライアル会員が体験予約不可のレッスンを指定しました。',
                    array_merge($params, [ 'memtype' => $custMaster['memtype'], 'taiken_les_flg' => $shiftMaster['taiken_les_flg'] ]))
            );
        }

        // ログインユーザーの会員種別が、予約可能会員種別(tenpo_master.tenpo_memtype)かどうか。
        if (!VaidationLogic::isMemberTypeReservable($futureMemberType, $tenpoMaster['tenpo_memtype'])) {
            return CommonLogic::getErrorJsonResponse(
                Response::HTTP_BAD_REQUEST,
                CommonLogic::getErrorArray(
                    'Invalid lesson',
                    'レッスンの店舗を予約可能な会員種別ではありません。',
                    array_merge($params, [ 'memtype' => $futureMemberType ]))
            );
        }

        // 体験レッスン未受講状態のバリデーション
        $taikenResults = VaidationLogic::validateNotTakenTrialLesson($shiftMaster, $custMaster);
        if (!$taikenResults['result']) {
            return CommonLogic::getErrorJsonResponse(
                Response::HTTP_BAD_REQUEST,
                CommonLogic::getErrorArray(
                    'Invalid lesson',
                    $taikenResults['error_message'],
                    array_merge($params))
            );
        }

        // 座席情報取得
        $sheetManager = new SheetManager($shiftMaster['shiftid'], $custMaster['cid'], $custMaster['memtype']);
        $sheetManager->initStudio();
        $sheetManager->setSheetStatusByOrderLesson();
        $sheetManager->setSheetStatusBySheetLock();
        $sheetManager->fillNotSpecialTrialSheet();

        // TODO 座席番号は、スタジオの座席数を超えた数値はエラー
        if (!$sheetManager->isSheetNoValid($sheet_no)) {
            return CommonLogic::getErrorJsonResponse(
                Response::HTTP_BAD_REQUEST,
                CommonLogic::getErrorArray('Invalid sheet_no', '無効な座席番号です、', $params)
            );
        }

        // ログインユーザーがバイクを予約済みかどうか
        if ($sheetManager->isCustomerReserved()) {
            return CommonLogic::getErrorJsonResponse(
                Response::HTTP_CONFLICT,
                CommonLogic::getErrorArray(
                    'Can not reserve sheet',
                    'すでに予約済みのレッスンです。',
                    array_merge($params))
            );
        }

        // 指定されたバイクが、他のユーザーによって、枠確保済み・予約済みかどうか
        if ($sheetManager->isSheetReserved($sheet_no)) {
            return CommonLogic::getErrorJsonResponse(
                Response::HTTP_CONFLICT,
                CommonLogic::getErrorArray(
                    'Can not reserve sheet',
                    '指定された座席は、他のユーザーによって、枠確保済みまたは予約済みです。',
                    array_merge($params))
            );
        }

        // バイク枠延長
        if (!$sheetManager->extendSheetLock($sheet_no)) {
            return CommonLogic::getErrorJsonResponse(
                Response::HTTP_CONFLICT,
                CommonLogic::getErrorArray(
                    'Can not extend sheet',
                    'バイク枠を延長できません。',
                    array_merge($params))
            );
        }

        return response()->json([
            'response_code' => Response::HTTP_CREATED
        ])->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * 通常予約API
     *
     * @POST("api/normal_reservation", as="api.normal_reservation.post")
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function normalReservationApi(Request $request)
    {
        $params = $request->all();

        // レッスンスケジュールIDハッシュ、座席番号のバリデーション
        if (!VaidationLogic::validateShiftIdHashAndSheetNo($params)) {
            // エラー
            return CommonLogic::getErrorJsonResponse(
                Response::HTTP_BAD_REQUEST,
                CommonLogic::getErrorArray('Invalid sid', '無効なレッスンスケジュールIDです', $params),
                CommonLogic::getErrorArray('Invalid sheet_no', '無効な座席番号です', $params)
            );
        }

        $sid = $params['sid'];
        $sheet_no = $params['sheet_no'];

        // 通常予約APIで必要なマスターデータ取得
        $resource = new ShiftTenpoCustMasterResource($sid);
        if(!$resource->createRedisResource()) {
            // Redisキャッシュの取得に失敗
            $resource->createDBResource();
        }
        // 未来の会員種別・所属店舗登録
        $resource->setFutureMemberTypeTenpos();

        $shiftMaster = $resource->getShiftMasterResource();
        $tenpoMaster = $resource->getTenpoMasterResource();
        $custMaster = $resource->getCustMasterResource();
        $futureMemberType = $resource->getFutureMemberType();

        // ネット予約公開日時が過去の日付かどうか
        if (!VaidationLogic::isOpenDateTimePassed($shiftMaster['open_datetime'])) {
            // ネット予約公開日時が未来
            return CommonLogic::getErrorJsonResponse(
                Response::HTTP_BAD_REQUEST,
                CommonLogic::getErrorArray(
                    'Invalid lesson',
                    '未公開のレッスンです。',
                    array_merge($params))
            );
        }

        // 予約受付時間内かどうか
        if (!VaidationLogic::validateTimeLimit($shiftMaster['shift_date'], $shiftMaster['ls_st'], $shiftMaster['tlimit'])) {
            // 予約受付時間外
            return CommonLogic::getErrorJsonResponse(
                Response::HTTP_CONFLICT,
                CommonLogic::getErrorArray(
                    'Can not reserve sheet',
                    '予約受付時間外です。',
                    array_merge($params,
                        [ 'shift_date' => $shiftMaster['shift_date'], 'start_time' => $shiftMaster['ls_st'], 'time_limit' => $shiftMaster['tlimit'] ]))
                );
        }

        // ネット・トライアル会員が体験予約不可のレッスンを指定した場合エラー
        if (!VaidationLogic::canReserveByNetTrialMember($custMaster['memtype'], $shiftMaster['taiken_les_flg'])) {
            // 体験予約不可のレッスンを指定
            return CommonLogic::getErrorJsonResponse(
                Response::HTTP_BAD_REQUEST,
                CommonLogic::getErrorArray(
                    'Invalid lesson',
                    'ネット・トライアル会員が体験予約不可のレッスンを指定しました。',
                    array_merge($params, [ 'memtype' => $custMaster['memtype'], 'taiken_les_flg' => $shiftMaster['taiken_les_flg'] ]))
            );
        }

        // ログインユーザーの会員種別が、予約可能会員種別(tenpo_master.tenpo_memtype)かどうか。
        if (!VaidationLogic::isMemberTypeReservable($futureMemberType, $tenpoMaster['tenpo_memtype'])) {
            return CommonLogic::getErrorJsonResponse(
                Response::HTTP_BAD_REQUEST,
                CommonLogic::getErrorArray(
                    'Invalid lesson',
                    'レッスンの店舗を予約可能な会員種別ではありません。',
                    array_merge($params, [ 'memtype' => $futureMemberType ]))
            );
        }

        // 体験レッスン未受講状態のバリデーション
        $taikenResults = VaidationLogic::validateNotTakenTrialLesson($shiftMaster, $custMaster);
        if (!$taikenResults['result']) {
            return CommonLogic::getErrorJsonResponse(
                Response::HTTP_BAD_REQUEST,
                CommonLogic::getErrorArray(
                    'Invalid lesson',
                    $taikenResults['error_message'],
                    array_merge($params))
            );
        }

        // 座席情報取得
        $sheetManager = new SheetManager($shiftMaster['shiftid'], $custMaster['cid'], $custMaster['memtype']);
        $sheetManager->initStudio();
        $sheetManager->setSheetStatusByOrderLesson();
        $sheetManager->setSheetStatusBySheetLock();
        $sheetManager->fillNotSpecialTrialSheet();

        // TODO 座席番号は、スタジオの座席数を超えた数値はエラー
        if (!$sheetManager->isSheetNoValid($sheet_no)) {
            return CommonLogic::getErrorJsonResponse(
                Response::HTTP_BAD_REQUEST,
                CommonLogic::getErrorArray('Invalid sheet_no', '無効な座席番号です、', $params)
            );
        }

        // ログインユーザーがバイクを予約済みかどうか
        if ($sheetManager->isCustomerReserved()) {
            return CommonLogic::getErrorJsonResponse(
                Response::HTTP_CONFLICT,
                CommonLogic::getErrorArray(
                    'Can not reserve sheet',
                    'すでに予約済みのレッスンです。',
                    array_merge($params))
                );
        }

        // 指定されたバイクが、他のユーザーによって、枠確保済み・予約済みかどうか
        if ($sheetManager->isSheetReserved($sheet_no)) {
            return response()->json([
                'response_code' => Response::HTTP_RESET_CONTENT,
                'modal_type' => ReservationTransitionType::EXPLANATION_MODAL,
                'modal_text' => '指定された座席は、他のユーザーによって、枠確保済みまたは予約済みです。'
            ])->setStatusCode(Response::HTTP_RESET_CONTENT);
        }

        // バイク枠確保
        if (!$sheetManager->addSheetLock($sheet_no)) {
            // バイク枠確保失敗
            return response()->json([
                'response_code' => Response::HTTP_RESET_CONTENT,
                'modal_type' => ReservationTransitionType::EXPLANATION_MODAL,
                'modal_text' => 'バイク枠確保に失敗しました。'
            ])->setStatusCode(Response::HTTP_RESET_CONTENT);
        }

        // 遷移先取得
        $transitionType = ReservationLogic::getReservationTransitionType($shiftMaster, $custMaster, $tenpoMaster);


        return response()->json([
            'response_code' => Response::HTTP_CREATED,
            'all_resource' => $resource->getAllResource()
        ])->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * バイク位置変更API
     *
     * @POST("api/sheet_change", as="api.sheet_change.post")
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sheetChangeApi(Request $request) {
        $params = $request->all();

        // レッスンスケジュールIDハッシュ、座席番号のバリデーション
        if (!VaidationLogic::validateShiftIdHashAndSheetNo($params)) {
            // エラー
            return CommonLogic::getErrorJsonResponse(
                Response::HTTP_BAD_REQUEST,
                CommonLogic::getErrorArray('Invalid sid', '無効なレッスンスケジュールIDです', $params),
                CommonLogic::getErrorArray('Invalid sheet_no', '無効な座席番号です', $params)
            );
        }

        $sid = $params['sid'];
        $sheet_no = $params['sheet_no'];

        // バイク位置変更APIで必要なマスターデータ取得
        $resource = new ShiftCustMasterResource($sid);
        if(!$resource->createRedisResource()) {
            // Redisキャッシュの取得に失敗
            $resource->createDBResource();
        }

        $shiftMaster = $resource->getShiftMasterResource();
        $custMaster = $resource->getCustMasterResource();

        // ログインユーザーがバイク位置変更可能か
        if (!VaidationLogic::canSheetChange($custMaster['memtype'])) {
            return CommonLogic::getErrorJsonResponse(
                Response::HTTP_BAD_REQUEST,
                CommonLogic::getErrorArray(
                    'Can not change sheet',
                    'バイク変更できません。',
                    array_merge($params, [ 'memtype' => $custMaster['memtype'] ]))
            );
        }

        // 予約受付時間内かどうか
        if (!VaidationLogic::validateTimeLimit($shiftMaster['shift_date'], $shiftMaster['ls_st'], $shiftMaster['tlimit'])) {
            // 予約受付時間外
            return CommonLogic::getErrorJsonResponse(
                Response::HTTP_CONFLICT,
                CommonLogic::getErrorArray(
                    'Can not change sheet',
                    '予約受付時間外です。',
                    array_merge($params,
                        [ 'shift_date' => $shiftMaster['shift_date'], 'start_time' => $shiftMaster['ls_st'], 'time_limit' => $shiftMaster['tlimit'] ]))
            );
        }

        // 座席情報取得
        $sheetManager = new SheetManager($shiftMaster['shiftid'], $custMaster['cid'], $custMaster['memtype']);
        $sheetManager->initStudio();
        $sheetManager->setSheetStatusByOrderLesson();
        $sheetManager->setSheetStatusBySheetLock();
        $sheetManager->fillNotSpecialTrialSheet();

        // TODO 座席番号は、スタジオの座席数を超えた数値はエラー
        if (!$sheetManager->isSheetNoValid($sheet_no)) {
            return CommonLogic::getErrorJsonResponse(
                Response::HTTP_BAD_REQUEST,
                CommonLogic::getErrorArray('Invalid sheet_no', '無効な座席番号です、', $params)
            );
        }

        // ログインユーザーがバイクを予約済みかどうか
        if (!$sheetManager->isCustomerReserved()) {
            return CommonLogic::getErrorJsonResponse(
                Response::HTTP_BAD_REQUEST,
                CommonLogic::getErrorArray(
                    'Invalid lesson',
                    '予約済みでないレッスンです。',
                    array_merge($params))
            );
        }

        // 指定されたバイクが、他のユーザーによって、枠確保済み・予約済みかどうか
        if ($sheetManager->isSheetReserved($sheet_no)) {
            return CommonLogic::getErrorJsonResponse(
                Response::HTTP_CONFLICT,
                CommonLogic::getErrorArray(
                    'Can not change sheet',
                    '他のユーザーによって、枠確保済みまたは予約済みです。',
                    array_merge($params))
            );
        }

        // バイク位置変更
        if (!ReservationLogic::changeSheetNo($shiftMaster['shiftid'], $sheet_no, $custMaster['cid'])) {
            // throw new InternalErrorException();
            return response()->json([
                'response_code' => Response::HTTP_INTERNAL_SERVER_ERROR
            ])->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'response_code' => Response::HTTP_CREATED
        ])->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * レッスン予約キャンセルAPI
     *
     * @POST("api/reservation_cancel", as="api.reservation_cancel.post")
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reservationCancelApi(Request $request) {
        $params = $request->all();

        // レッスンスケジュールIDハッシュのバリデーション
        if (!VaidationLogic::validateShiftIdHash($params)) {
            // エラー
            return CommonLogic::getErrorJsonResponse(
                Response::HTTP_BAD_REQUEST,
                CommonLogic::getErrorArray('Invalid sid', '無効なレッスンスケジュールIDです、', $params)
            );
        }

        $sid = $params['sid'];

        // レッスン予約キャンセルAPIで必要なマスターデータ取得
        $resource = new ShiftCustMasterResource($sid);
        if(!$resource->createRedisResource()) {
            // Redisキャッシュの取得に失敗
            $resource->createDBResource();
        }

        $shiftMaster = $resource->getShiftMasterResource();
        $custMaster = $resource->getCustMasterResource();

        // ログインユーザーがレッスン予約キャンセル可能か
        if (!VaidationLogic::canReservationCancel($custMaster['memtype'])) {
            return CommonLogic::getErrorJsonResponse(
                Response::HTTP_BAD_REQUEST,
                CommonLogic::getErrorArray(
                    'Can not cancel lesson',
                    'レッスンをキャンセルできません。',
                    array_merge($params))
                );
        }

        // レッスン予約・キャンセル排他ロック(同時予約・キャンセルを防ぐ)
        if (VaidationLogic::isReserveLock($custMaster['reserve_lock'])) {
            return CommonLogic::getErrorJsonResponse(
                Response::HTTP_CONFLICT,
                CommonLogic::getErrorArray(
                    'Can not cancel lesson',
                    'レッスンをキャンセルできません。',
                    array_merge($params))
                );
        }

        // キャンセル受付時間内かどうか
        if (!VaidationLogic::validateTimeLimit($shiftMaster['shift_date'], $shiftMaster['ls_st'], $shiftMaster['tlimit_cancel'])) {
            // キャンセル受付時間外
            return CommonLogic::getErrorJsonResponse(
                Response::HTTP_CONFLICT,
                CommonLogic::getErrorArray(
                    'Can not cancel lesson',
                    '予約受付時間外です。',
                    array_merge($params,
                        [ 'shift_date' => $shiftMaster['shift_date'], 'start_time' => $shiftMaster['ls_st'], 'time_limit_cancel' => $shiftMaster['tlimit_cancel'] ]))
                );
        }

        // 予約キャンセル
        if (!ReservationLogic::cancelLesson($shiftMaster['shiftid'], $custMaster['cid'])) {
            // throw new InternalErrorException();
            return response()->json([
                'response_code' => Response::HTTP_INTERNAL_SERVER_ERROR
            ])->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'response_code' => Response::HTTP_CREATED,
            'all_resource' => $resource->getAllResource()
        ])->setStatusCode(Response::HTTP_CREATED);
    }
}
