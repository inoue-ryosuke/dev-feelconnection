<?php

namespace App\Libraries\Logic\ReservationModal;

/**
 * 予約で必要なRedis・DBのマスター情報
 *
 * Redisキャッシュで使用するカラム一覧
 *
 * @shift_master:IDハッシュ値
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
 * @var string instructor_name インストラクター名
 * @var string instructor_path_img インストラクター写真の画像パス
 *
 * @lesson_master:ID
 * @var int lid 主キー
 * @var int lesson_class1 lesson_class1.id
 * @var int lesson_class2 lesson_class2.id
 * @var int lesson_class3 lesson_class3.id
 * @var string lesson_class1_name レッスン分類1名
 * @var string lesson_class2_name レッスン分類2名
 * @var string lesson_class3_name レッスン分類3名
 *
 * @tenpo_master:ID
 * @var int tid 主キー
 * @var string tenpo_name 店舗名
 * @var string monthly_avail_tenpo 利用可能マンスリー所属店 tenpo_master.tid ※カンマ区切りで複数 -1： 全店
 * @var string tenpo_memtype 予約可能会員種別 cust_memtype.mid ※カンマ区切りで複数
 *
 */
abstract class ReservationMasterResource {
    /** 取得するshift_master.shiftid_hash */
    protected $shiftIdHash;

    /** 各Redis・DBデータは連想配列で作成して、キー名はDBのカラム名とする */
    protected $shiftMaster;
    protected $lessonMaster;
    protected $tenpoMaster;
    protected $custMaster;

    /**
     *
     * @param string $shiftIdHash shift_master.shiftid_hash
     */
    public function __construct(string $shiftIdHash) {
        $this->shiftIdHash = $shiftIdHash;

        $this->shiftMaster = [];
        $this->lessonMaster = [];
        $this->tenpoMaster = [];
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
     * @return bool 成功=true, 失敗=false
     */
    protected function createRedisResourceByKey(string $keyName, array &$resource) {
        $resource = RedisWrapper::hGetAll($keyName);
        if (!empty($resource)) {
            return true;
        } else {
            $resource = [];

            return false;
        }
    }

    /**
     * @return array shift_masterのリソース
     */
    public function getShiftMasterResource() {
        return $this->shiftMaster;
    }

    /**
     * @return array lesson_masterのリソース
     */
    public function getLessonMasterResource() {
        return $this->lessonMaster;
    }

    /**
     * @return array tenpo_masterのリソース
     */
    public function getTenpoMasterResource() {
        return $this->tenpoMaster;
    }

    /**
     * @return array cust_masterのリソース
     */
    public function getCustMasterResource() {
        return $this->custMaster;
    }

    /**
     * @return array 予約のリソース
     */
    public function getAllResource() {
        return array(
            'shift_master' => $this->getShiftMasterResource(),
            'lesson_master' => $this->getLessonMasterResource(),
            'cust_master' => $this->getCustMasterResource(),
            'tenpo_master' => $this->getTenpoMasterResource(),
        );
    }

    /**
     * レッスン名取得
     *
     * @return string レッスン名
     */
    public function getLessonName() {
        return
           $this->lessonMaster['lesson_class1_name'] . ' '
             . $this->lessonMaster['lesson_class2_name'] . ' '
             . $this->lessonMaster['lesson_class3_name']
        ;
    }
}