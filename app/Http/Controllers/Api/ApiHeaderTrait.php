<?php namespace App\Http\Controllers\Api;

use App\Exceptions\ApiHeaderException;
use App\Exceptions\IllegalParameterException;
use Auth;

/**
 * ヘッダー情報を取得するトレイト
 * Trait ApiHeaderTrait
 * @package App\Http\Controllers\Api
 */
trait ApiHeaderTrait {

    /**
     * APIのバージョン情報を取得
     *
     * @return string
     */
    protected static function getApiVersion() {
        // デフォルトバージョン
        $version = request()->header('X-FeelConnection-Version');
        if (empty($version)) {
            throw new ApiHeaderException();
        }
        $ver = $version;
        return $ver;
    }

    /**
     * デバイス情報を取得
     *
     * @return string
     */
    protected static function getDevice() {
        $deviceId = request()->header('X-FeelConnection-Device');
        if (empty($deviceId)) {
            throw new ApiHeaderException();
        }
        return $deviceId;
    }

    /**
     * デバイス情報を取得
     *
     * @return string
     */
    protected static function getLanguage() {
        $lang = request()->header('X-FeelConnection-Lang');
        if (empty($lang)) {
            $lang = "ja_jp";
        }
        return $lang;
    }

    /**
     * ユーザー情報を取得
     *
     * @return string
     */
    protected static function getGuid() {
        $userId = request()->header('X-FeelConnection-GuardUID');
        if (empty($userId)) {
            $userId = "";
        }
        return $userId;
    }


    /**
     * 認証で使用された$guardを取得
     * @return mixed
     */
    protected static function getGurd()
    {
        $guardUid =self::getGuid();
        $guardUid = explode(':', $guardUid);
        $guard = $guardUid[0];

        return $guard;
    }

    /**
     * 認証したユーザーのuserを取得
     * @return $user|NULL
     */
    protected  static function getAuthUserInfo()
    {
        $guard = self::getGurd();
        $user = auth($guard)->user();

        return $user;
    }


}
