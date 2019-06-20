<?php namespace App\Libraries\Logic;

use App\Exceptions\LogicNotFoundException;
use App\Libraries\Logger;
use Log;

/**
 * APIロジックをロードするローダー
 *
 * @author    Takeo Noda
 */
class Loader
{
    // 種別
    const API           = "Api";            
    const URL           = 'Url';
    const Instructor    = 'Instructor';       // API-201 - API-299
    const Invite        = 'Invite';           // API-301 - API-399
    const Music         = 'Music';            // API-401 - API-499 
    const Auth          = "Authentication";   // API-001 - API-099
    const MailCheck     = "MailCheck";
    const MemtypeChange = "MemtypeChange";
    const ZipCode       = "ZipCode";          // API-501 - API-599
    const Tenpo         = "Tenpo";            // API-101 - API-199

    // 機能
    const SELECT = 'Select';
    const STORE = 'Store';
    const UPDATE = 'Update';
    const DELETE = 'Delete';

    private $type;

    private $logic = [];

    /**
     * 起動時コンストラクタ
     * @param $type　種別
     * @param $target
     */
    public function __construct($type = "", $target = [])
    {
        $this->type = $type;
        if (!is_array($target)) {
            $target = [$target];
        }
        foreach ($target as $logicName) {
            $this->loadLogic($logicName);
        }
    }

    /**
     * ロジックをロードする。
     * @param $logicName
     * @return BaseLogic ロジック
     * @throws LogicNotFoundException
     */
    protected function loadLogic($logicName)
    {
//        $versionList = config("constant.api.versions");
//        foreach ($versionList as $ver) {
//            if ($ver > $this->version) {
//                continue;
//            }
            $className = __NAMESPACE__ . '\\'.$this->type.  '\\'.$logicName.'Logic';
//            if (!class_exists($className)) {
//                continue;
//            }
            $this->logic[$logicName] = new $className;
            logger('Loading: '.$className);
            return $this->logic[$logicName];
//        }
        /** @throws APIで対応するロジックが見つからない場合 */
        Logger::writeSystemLog(Logger::LEVEL_ERROR, 40414,
            __FILE__.":".__LINE__, "Logic not found.");
        throw new LogicNotFoundException(config('errors.logic.notFound'));
    }

    /**
     * ロジックを取得する
     * @param $logicName ロジック名
     * @return BaseLogic ロジック
     */
    public function getLogic($logicName)
    {
        if (isset($this->logic[$logicName])) {
            return $this->logic[$logicName];
        }
        return $this->loadLogic($logicName);
    }
}
