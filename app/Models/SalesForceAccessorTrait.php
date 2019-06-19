<?php
namespace App\Models;

use App\Exceptions\InternalErrorException;
use DB;

trait SalesForceAccessorTrait
{

    protected static $ex_getter = [];
    protected static $ex_setter = [];

    /**
     * boot時に呼ばれ、定義されたフィールドをアクセサ・ミューテタ経由アクセス可能にする
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();
        $tablename = (new static)->getTable();
        var_dump($tablename);
        $primary   = (new static)->primaryKey;
        $accessorList = static::$salesforceAccessor;
        // モデルのテーブル名が__c付きになってない場合、後続処理はさせない
        if (!preg_match("#^(.+)__c$#",$tablename)) {
            throw new InternalErrorException("Salesforce用のテーブル構成になっていません");
        }
        // モデルのプライマリキーが__c付きになってない場合、後続処理はさせない
        if (!preg_match("#^(.+)__c$#",$primary)) {
            throw new InternalErrorException("Salesforce用の主キー構成になっていません");
        }
        if (empty($accessorList)) {
            // TBD:カラム定義がない場合は、テーブルの全カラムを取得する
            //
            // テーブルの全てのカラム実体は「__c付き」なので、__cなしでアクセスした場合__c付きでアクセスしたものと同一にする
            //   「__c無し」でも「__c付き」を参照可能にする：__c無しのアクセサ準備
            //   「__c無し」でも「__c付き」へ保存可能にする：__c無しのアクセサ準備
//            $attributes = (new static)->fillable;
            $newMdl = (new static);
            var_dump($newMdl); exit;
            if (empty($attributes)) {
                throw new InternalErrorException("Salesforce用の登録許可カラム一覧が定義されていません");
            }
            // migrationは__c付きで登録されているはずなので、指定キーから__cを取り除き、__cなしキーをアクセサ作成対象にする
            foreach ($attributes as $key) {
                if (preg_match("#^(.+)__c$#",$key)) {
                    static::$salesforceAccessor[] = preg_replace("#^(.+)__c$#","$1",$key);
                } else {
                    static::$salesforceAccessor[] = $key;
                }
            }
        }
        // アクセサ・ミューテタ定義
        foreach (static::$salesforceAccessor as $target) {
//            echo "TARGET[".$target."]<br />";
                self::setAccessor($target."__c", $target, 'get');
                self::setAccessor($target."__c", $target, 'set');
        }
    }

    /**
     * アクセサを定義する
     *
     * @param string $key    対象のフィールド
     * @param string $method 紐付けるメソッド
     * @param string $mode   get|set
     * @return void
     */
    public static function setAccessor($key, $method, $mode = 'get')
    {
        $arr = 'ex_' . $mode . 'ter';
        static::$$arr[$key] = $method;
        static::$append[] = $method;
    }

    /**
     * getAttributeメソッドの書き換え
     * Salesforceスキーマは__cが存在し、__c無しは存在しない。
     * なので、アクセサとしては__cなしを準備して、参照先を__c付き（実態）にする
     *
     * @param string $key
     * @return mixed
     */
    public function getAttribute($method)
    {
        $key__c = array_search($method,static::$ex_getter);
        if ($key__c) {
            $salesforceAccessor = $this->attributes[$method] = $this->$key__c;
            return $salesforceAccessor;
        }
        return parent::getAttribute($method);
    }

    /**
     * setAttributeメソッドの書き換え　（モデル処理で $dao->column となっていても、
     * 接続先がsalesforceの場合 $dao->column__c としてアクセスする。
     *
     * @param string $key   
     * @param mixed  $value
     * @return void
     */
    public function setAttribute($method, $value)
    {
        $key__c = array_search($method,static::$ex_setter);
        if ($key__c) {
            // モデル側が__c無しで記述しており、ミューテータで__cを向かせる場合はすでにカラムがある前提。
            $salesforceMutator = $this->attributes[$key__c] = $value;
            return $salesforceMutator;
        }
        return parent::setAttribute($method, $value);
    }
}