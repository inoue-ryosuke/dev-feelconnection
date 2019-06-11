<?php namespace App\Http\Controllers\Api;

use App\Exceptions\ApiHeaderException;
use App\Exceptions\IllegalParameterException;
use App\Exceptions\LogicNotFoundException;
use App\Libraries\Logic\Loader;
use App\Libraries\Logic\UserMaster\SelectLogic;

/**
 * API用ロジックに関するトレイト
 * Trait ApiLogicTrait
 * @package App\Http\Controllers\Api
 */
trait ApiLogicTrait {

    /**
     * ロジックを取得
     * @param $logicType
     * @param $logicKey
     * @return
     */
    public function getApiLogic($logicType, $logicKey) {
        if (empty($logicKey)) {
           throw new LogicNotFoundException();
        }
        $loader = new Loader($logicType);
        return $loader->getLogic($logicKey);
    }

    /**
     * UserMasterのSelectロジックを取得する
     * @return SelectLogic
     */
    public function getUserMasterSelectLogic() {
        return $this->getApiLogic(Loader::USER_MASTER, Loader::SELECT);
    }

}
