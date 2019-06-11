<?php namespace App\Libraries\Logic;

use App\Models\BaseModel;
use Carbon\Carbon;

class BaseLogic {

    const ASC = 1;
    const DESC = 2;

    /**
     * 起動時コンストラクタ
     */
    public function __construct() {

    }

    /**
     * リクエスト値が空かチェック
     * @param $request
     * @return
     */
    public function unsetEmptyValue ($request) {
        // 空の場合
        if (empty($request)) {
            return $request;
        }
        // 値が空の場合はリクエストからキーを削除
        foreach($request as $key => $value) {
            if (empty($value)) {
                unset($request[$key]);
            }
        }

        if (empty($request)) {
            $request = null;
        }
        return $request;
    }

}
