<?php
namespace App\Models;

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
        foreach (static::$salesforceAccessor as $target) {
//            echo "TARGET[".$target."]<br />";
                self::setAccessor($target, $target."__c", 'get');
                self::setAccessor($target, $target."__c", 'set');
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
     *
     * @param string $key
     * @return mixed
     */
    public function getAttribute($key__c)
    {
        $method = array_search($key__c,static::$ex_getter);
        if ($method) {
            $salesforceAccessor = $this->attributes[$key__c] = $this->$method;
            return $salesforceAccessor;
        }
        return parent::getAttribute($key__c);
    }

    /**
     * setAttributeメソッドの書き換え　（モデル処理で $dao->column となっていても、
     * 接続先がsalesforceの場合 $dao->column__c としてアクセスする。
     * （）
     *
     * @param string $key   
     * @param mixed  $value
     * @return void
     */
    public function setAttribute($key, $value)
    {
        $method = data_get(static::$ex_setter,$key);
        if ($method) {
            // モデル側が__c無しで記述しており、ミューテータで__cを向かせる場合はすでにカラムがある前提。
            //$this->attributes[$method] = $value;
            $salesforceMutator = $this->attributes[$method] = $value;
            return $salesforceMutator;
        }
        return parent::setAttribute($key, $value);
    }
}