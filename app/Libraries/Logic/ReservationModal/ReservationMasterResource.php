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
     * Redisキャッシュで使用するカラム一覧
     *
     * shift_master:IDハッシュ値
     * @var int shiftid 主キー
     * @var string flg 有効フラグ(Y:有効、N:削除済み, C:休講)
     * @var datetime open_datetime ネット予約公開日時
     * @var int taiken_mess 体験制限表示(0:非表示, 1:表示)
     * @var int taiken_les_flg 体験予約可(0:不可, 1:可)
     * @var int tlimit tlimit分前まで予約可能
     * @var date shift_date レッスン開催日
     * @var time ls_st レッスン開始時間
     * @var int shift_capa 定員(人数)
     * @var int taiken_capa 体験定員(体験人数)
     * @var int gender 性別制限(0:制限なし、1:女性のみ、2:男性のみ)
     * @var int ls_menu レッスンID(lesson_master.lid)
     * @var time ls_et レッスン終了時間
     * @var int shift_tenpo_id 店舗ID(tenpo_master.tid)
     * @var int teacher インストラクターID(user_master.uid)
     *
     * lesson_master:ID
     * @var int lid 主キー
     * @var int lesson_class1 lesson_class1.id
     * @var int lesson_class2 lesson_class2.id
     * @var int lesson_class3 lesson_class3.id
     *
     * lesson_class1:ID
     * @var int id 主キー
     * @var string name 分類名
     *
     * lesson_class2:ID
     * @var int id 主キー
     * @var string name 分類名
     *
     * lesson_class3:ID
     * @var int id 主キー
     * @var string name 分類名
     *
     * cust_master:ID
     * @var int cid 主キー
     * @var int memtype 会員種別(cust_memtype.mid)
     *
     * tenpo_master:ID
     * @var int tid 主キー
     * @var string tenpo_name 店舗名
     *
     * user_master:ID
     * @var int uid 主キー
     * @var string user_name インストラクター名
     * @var string path_img インストラクター写真の画像パス
     */

    /**
     *
     * @param string $shiftIdHash shift_master.shiftid_hash
     */
    public function __construct(string $shiftIdHash) {
        $this->shiftIdHash = $shiftIdHash;

        $this->shiftMaster = null;
        $this->lessonMaster = null;
        $this->lessonClass1 = null;
        $this->lessonClass2 = null;
        $this->lessonClass3 = null;
        $this->custMaster = null;
        $this->tenpoMaster = null;
        $this->userMaster = null;
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
        $resource = Redis::hgetall($keyName);
        if (!empty($resource)) {
            return true;
        } else {
            $resource = null;

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