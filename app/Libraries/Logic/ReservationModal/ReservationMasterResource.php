<?php

namespace App\Libraries\Logic\ReservationModal;

use App\Models\Schedule;
use App\Models\Constant\ReservationTablePrefix;

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
 * @var int shift_tenpoid 店舗ID(tenpo_master.tid)
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
 * @var string iname レッスン名
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

    /**
     *
     * @var int cid 会員ID
     * @var int memtype 会員種別
     */
    protected $custMaster;

    /** 未来の会員種別 */
    protected $futureMemberType;
    /** 未来の所属店舗 */
    protected $futureTenpos;

    /** shift_masterのキャッシュキープレフィックス */
    const SHIFT_MASTER_HASH_PREFIX = 'shift_master:';
    /** lesson_masterのキャッシュキープレフィックス */
    const LESSON_MASTER_HASH_PREFIX = 'lesson_master:';
    /** tenpo_masterのキャッシュキープレフィックス */
    const TENPO_MASTER_HASH_PREFIX = 'tenpo_master:';

    /**
     *
     * @param string $shiftIdHash shift_master.shiftid_hash
     */
    public function __construct(string $shiftIdHash) {
        $this->shiftIdHash = $shiftIdHash;

        $this->shiftMaster = [];
        $this->lessonMaster = [];
        $this->tenpoMaster = [];
        $this->custMaster = [];

        $this->futureMemberType = null;
        $this->futureTenpos = [];
    }

    /**
     * 各マスターのRedisキャッシュを取得
     * すべて成功=true、失敗=false
     *
     * @return bool Redisからキャッシュ取得成功したかどうか
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
     * @return int 未来の会員種別
     */
    public function getFutureMemberType() {
        return $this->futureMemberType;
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
        return $this->lessonMaster['iname'];
    }

    /**
     * shift_masterのキャッシュ生成
     *
     */
    public function createShiftMasterCache() {
        $hashKey = self::SHIFT_MASTER_HASH_PREFIX . $this->shiftIdHash;

        RedisWrapper::hmSet($hashKey, $this->shiftMaster);
        // ログ出力
        logger()->debug("Create RedisCache {$hashKey} ");
    }

    /**
     * lesson_masterのキャッシュ生成
     *
     */
    public function createLessonMasterCache() {
        $hashKey = self::LESSON_MASTER_HASH_PREFIX . $this->lessonMaster['lid'];

        RedisWrapper::hmSet($hashKey, $this->lessonMaster);
        // ログ出力
        logger()->debug("Create RedisCache {$hashKey}");
    }

    /**
     * tenpo_masterのキャッシュ生成
     *
     */
    public function createTenpoMasterCache() {
        $hashKey = self::TENPO_MASTER_HASH_PREFIX . $this->tenpoMaster['tid'];

        RedisWrapper::hmSet($hashKey, $this->tenpoMaster);
        // ログ出力
        logger()->debug("Create RedisCache {$hashKey}");
    }

    /**
     * Eloquentモデルを渡して、shift_masterのリソースにセット
     * キャッシュに必要なすべてのカラムが必要
     * Eloquentモデルのカラム名は、「ReservationTablePrefix::SM + カラム名」とする
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    public function setShiftMasterResourceByModel(\Illuminate\Database\Eloquent\Model $model) {
        $this->shiftMaster = [];
        $this->shiftMaster['shiftid'] = null;
        $this->shiftMaster['flg'] = null;
        $this->shiftMaster['open_datetime'] = null;
        $this->shiftMaster['taiken_mess'] = null;
        $this->shiftMaster['taiken_les_flg'] = null;
        $this->shiftMaster['tlimit'] = null;
        $this->shiftMaster['shift_date'] = null;
        $this->shiftMaster['ls_st'] = null;
        $this->shiftMaster['shift_capa'] = null;
        $this->shiftMaster['taiken_capa'] = null;
        $this->shiftMaster['gender'] = null;
        $this->shiftMaster['ls_menu'] = null;
        $this->shiftMaster['ls_et'] = null;
        $this->shiftMaster['shift_tenpoid'] = null;
        $this->shiftMaster['teacher'] = null;
        $this->shiftMaster['instructor_name'] = null;
        $this->shiftMaster['instructor_path_img'] = null;

        foreach ($this->shiftMaster as $key => $value) {
            $column = ReservationTablePrefix::SM . $key;
            $this->shiftMaster[$key] = $model->$column;
        }
    }

    /**
     * Eloquentモデルを渡して、shift_masterのリソースにセット
     * キャッシュに必要なすべてのカラムが必要
     * Eloquentモデルのカラム名は、「ReservationTablePrefix::LM + カラム名」とする
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    public function setLessonMasterResourceByModel(\Illuminate\Database\Eloquent\Model $model) {
        $this->lessonMaster = [];
        $this->lessonMaster['lid'] = null;
        $this->lessonMaster['lesson_class1'] = null;
        $this->lessonMaster['lesson_class2'] = null;
        $this->lessonMaster['lesson_class3'] = null;
        $this->lessonMaster['lesson_class1_name'] = null;
        $this->lessonMaster['lesson_class2_name'] = null;
        $this->lessonMaster['lesson_class3_name'] = null;
        $this->lessonMaster['iname'] = null;

        foreach ($this->lessonMaster as $key => $value) {
            $column = ReservationTablePrefix::LM . $key;
            $this->lessonMaster[$key] = $model->$column;
        }
    }

    /**
     * Eloquentモデルを渡して、shift_masterのリソースにセット
     * キャッシュに必要なすべてのカラムが必要
     * Eloquentモデルのカラム名は、「ReservationTablePrefix::TM + カラム名」とする
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    public function setTenpoMasterResourceByModel(\Illuminate\Database\Eloquent\Model $model) {
        $this->tenpoMaster = [];
        $this->tenpoMaster['tid'] = null;
        $this->tenpoMaster['tenpo_name'] = null;
        $this->tenpoMaster['monthly_avail_tenpo'] = null;
        $this->tenpoMaster['tenpo_memtype'] = null;

        foreach ($this->tenpoMaster as $key => $value) {
            $column = ReservationTablePrefix::TM . $key;
            $this->tenpoMaster[$key] = $model->$column;
        }
    }

    /**
     * 未来の会員種別、所属店舗登録
     * TODO: 未来の所属店舗複数
     *
     */
    public function setFutureMemberTypeTenpos() {
        $collection = Schedule::getFutureMemberTypeList($this->custMaster['cid']);

        // レッスン開催日
        $shiftDateTime = new \DateTime($this->shiftMaster['shift_date']);

        // 未来の会員種別
        $futureMemberType = $this->custMaster['memtype'];
        // 未来の所属店舗
        $futureTenpos = [];

        foreach ($collection as $model) {
            $scheduleDateTime = new \DateTime($model->sc_date);

            if ($scheduleDateTime > $shiftDateTime) {
                // スケジュール実行日がレッスン開催日より後
                break;
            }

            $futureMemberType = $model->sc_memtype;
            $futureTenpos[0] = $model->sc_tepo;
        }

        $this->futureMemberType = $futureMemberType;
        $this->futureTenpos = $futureTenpos;
    }
}