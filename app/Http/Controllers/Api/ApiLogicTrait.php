<?php namespace App\Http\Controllers\Api;

use App\Exceptions\ApiHeaderException;
use App\Exceptions\IllegalParameterException;
use App\Exceptions\LogicNotFoundException;
use App\Libraries\Logic\Loader;
use App\Libraries\Logic\Instructor\SelectLogic as InstructorSelectLogic;
use App\Libraries\Logic\Invite\SelectLogic as InviteSelectLogic;
use App\Libraries\Logic\Music\SelectLogic as MusicSelectLogic;

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
     * InstructorのSelectロジックを取得する
     * @return InstructorSelectLogic
     */
    public function getInstructorSelectLogic() {
        return $this->getApiLogic(Loader::Instructor, Loader::SELECT);
    }

    /**
     * InviteのSelectロジックを取得する
     * @return InviteSelectLogic
     */
    public function getInviteSelectLogic() {
        return $this->getApiLogic(Loader::Invite, Loader::SELECT);
    }

    /**
     * MusicのSelectロジックを取得する
     * @return MusicSelectLogic
     */
    public function getMusicSelectLogic() {
        return $this->getApiLogic(Loader::Music, Loader::SELECT);
    }

}
