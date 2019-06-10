<?php

namespace App\Libraries\Logic\ReservationModal;

use Illuminate\Support\Facades\Redis;

/**
 * 予約で必要なRedis・DBのマスター情報
 *
 */
abstract class ReservationMasterResource {
    /** 取得するshift_master.shiftid_hash */
    protected $shiftIdHash;
    protected $shiftMaster;
    protected $lessonMaster;
    protected $lessonClass1;
    protected $lessonClass2;
    protected $lessonClass3;
    protected $custMaster;
    protected $tenpoMaster;
    protected $userMaster;

    /**
     *
     * @param string $shiftIdHash shift_master.shiftid_hash
     */
    public function __construct(string $shiftIdHash) {
        $this->shiftIdHash = $shiftIdHash;
        $this->shiftMaster = array(
            'shiftid_hash' => $shiftIdHash, // shiftIdハッシュ値
            'shiftid' => NULL, // 主キー
            'flg' => NULL, // 有効フラグ(Y:有効、N:削除済み, C:休講)
            'open_datetime' => NULL, // ネット予約公開日時
            'taiken_mess' => NULL, // 体験制限表示(0:非表示, 1:表示)
            'taiken_les_flg' => NULL, // 体験予約可(0:不可, 1:可)
            'tlimit' => NULL, // tlimit分前まで予約可能
            'shift_date' => NULL, // レッスン開催日
            'ls_st' => NULL, // レッスン開始時間
            'shift_capa' => NULL, // 定員(人数)
            'taiken_capa' => NULL, // 体験定員(体験人数)
            'gender' => NULL, // 性別制限,
            'ls_menu' => NULL, // レッスンID(lesson_master.lid),
            'ls_et' => NULL, // レッスン終了時間
            'shift_tenpo_id' => NULL, // 店舗ID(tenpo_master.tid)
            'teacher' => NULL, // インストラクターID(user_master.uid)
        );

        $this->lessonMaster = array(
            'lid' => NULL, // 主キー
            'lesson_class1' => NULL, // lesson_class1.id,
            'lesson_class2' => NULL, // lesson_class2.id,
            'lesson_class3' => NULL, // lesson_class3.id,
        );

        $this->lessonClass1 = array(
            'id' => NULL, // 主キー,
            'name' => NULL, // 分類名
        );

        $this->lessonClass2 = array(
            'id' => NULL, // 主キー,
            'name' => NULL, // 分類名
        );

        $this->lessonClass3 = array(
            'id' => NULL, // 主キー,
            'name' => NULL, // 分類名
        );

        $this->custMaster = array(
            'cid' => NULL, // 主キー,
            'memtype' => NULL, // 会員種別(cust_memtype.mid)
        );

        $this->tenpoMaster = array(
            'tid' => NULL, // 主キー,
            'tenpo_name' => NULL, // 店舗名
        );

        $this->userMaster = array(
            'uid' => NULL, // 主キー,
            'user_name' => NULL, // インストラクター名,
            'path_img' => NULL, // インストラクター写真の画像パス
        );
    }

    /**
     * 各マスターのRedisキャッシュを取得
     * すべて成功=true、失敗=false
     *
     * @return boolean Redisからキャッシュ取得成功したかどうか
     */
    abstract public function createRedisResource();

    /** 各マスタのDBデータ取得 */
    abstract public function createDBResource();

    /**
     * 指定したキー名のRedisキャッシュ取得
     *
     * @param string $keyName Redisキー名
     * @param array $resource リソース連想配列
     * @return boolean 成功=true, 失敗=false
     */
    protected function createRedisResourceByKey(string $keyName, array &$resource) {
        $cache = Redis::hgetall($keyName);
        if (!empty($cache)) {
            foreach ($cache as $key => $value) {
                if (array_key_exists($key, $resource)) {
                    $resource[$key] = $value;
                }
            }

            return true;
        } else {
            return false;
        }
    }

    /**
     * @return array shift_masterのリソース
     */
    public function getShitMasterResource() {
        return $this->shiftMaster;
    }

    /**
     * @return array lesson_masterのリソース
     */
    public function getLessonMasterResource() {
        return $this->lessonMaster;
    }

    /**
     * @return array lesson_class1のリソース
     */
    public function getLessonClass1Resource() {
        return $this->lessonClass1;
    }

    /**
     * @return array lesson_class2のリソース
     */
    public function getLessonClass2Resource() {
        return $this->lessonClass2;
    }

    /**
     * @return array lesson_class3のリソース
     */
    public function getLessonClass3Resource() {
        return $this->lessonClass3;
    }

    /**
     * @return array cust_masterのリソース
     */
    public function getCustMasterResource() {
        return $this->custMaster;
    }

    /**
     * @return array tenpo_masterのリソース
     */
    public function getTenpoMasterResource() {
        return $this->tenpoMaster;
    }

    /**
     * @return array user_masterのリソース
     */
    public function getUserMasterResource() {
        return $this->userMaster;
    }
}