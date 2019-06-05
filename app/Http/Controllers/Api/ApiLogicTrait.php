<?php namespace App\Http\Controllers\Api;

use App\Exceptions\ApiHeaderException;
use App\Exceptions\IllegalParameterException;
use App\Exceptions\LogicNotFoundException;
use App\Libraries\Logic\Loader;

/**
 * API用ロジックに関するトレイト
 * Trait ApiLogicTrait
 * @package App\Http\Controllers\Api
 */
trait ApiLogicTrait {

    /**
     * ロジックを取得
     * @param $logicKey
     */
    public function getApiLogic($logicKey = null) {
        if (empty($logicKey)) {
           throw new LogicNotFoundException();
        }
        $loader = new Loader(Loader::API, $this->getApiVersion());
        return $loader->getLogic($logicKey);
    }

}
