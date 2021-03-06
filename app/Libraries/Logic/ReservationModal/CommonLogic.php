<?php

namespace App\Libraries\Logic\ReservationModal;

/**
 * 文字列操作など共通で使用するロジック
 *
 */
class CommonLogic
{
    const DAY_WEEK = array("日", "月", "火", "水", "木", "金", "土");

    /**
     * Redisのバイク枠確保レコード値を、座席番号とタイムスタンプにパース
     * Redisレコード値は「座席番号 タイムスタンプ」の形式
     * 例: 10 2019/01/01 10:00:00
     *
     * @param string $sheetLockRecord
     * @return array [ 'sheet_no' => 座席番号, 'timestamp' => タイムスタンプ(yyyy/mm/dd hh:ii:ss) ]
     */
    public static function pasrseSheetLockRecord(string $sheetLockRecord) {
        $index = strpos($sheetLockRecord, ' ');

        if ($index === FALSE) {
            return [];
        }

        $sheetNo = substr($sheetLockRecord, 0, $index);
        $dateTime = substr($sheetLockRecord, $index + 1);

        return [ 'sheet_no' => (int)$sheetNo, 'timestamp' => $dateTime ];
    }

    /**
     * 曜日取得
     *
     * @param string $dateTime yyyy/mm/dd
     * @return string
     */
    public static function getDayWeek(string $dateTime) {
        $dateTime = new \DateTime($dateTime);

        return self::DAY_WEEK[(int)$dateTime->format('w')];
    }

    /**
     * エラー配列取得
     *
     * @param string $code
     * @param string $message
     * @param array $paremeters
     * @return array
     */
    public static function getErrorArray(string $code, string $message, array $paremeters) {
        return [ 'code' => $code, 'message' => $message, 'parameters' => $paremeters  ];
    }

    /**
     * エラーレスポンスJson取得
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public static function getErrorJsonResponse(int $responseCode, array ...$errors) {
        // ログ出力
        logger()->debug("ErrorResponse\nresponse_code: " . $responseCode . "\n" . var_export($errors, true));

        return response()->json([
            'response_code' => $responseCode,
            'errors' => $errors
        ])->setStatusCode($responseCode);
    }
}