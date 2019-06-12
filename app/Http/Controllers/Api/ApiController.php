<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Exceptions\MalformedPayloadException;
use App\Exceptions\IllegalParameterException;
use App\Libraries\Logger;

/**
 */
class ApiController extends Controller
{
    use ApiHeaderTrait, ApiLogicTrait;

    private $type;

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        $this->type = Controller::TYPE_API;
    }

    public function __destruct()
    {
//        \Debugbar::disable();
//        logger('debugbar off');
    }

    /**
     * リクエストペイロードに指定されている内容を読み込む。
     * APIではJSONフォーマットのため、json_decodeして判定する。
     * @return mixed
     * @throws MalformedPayloadException
     */
    protected function getPayload() {
        // TESTケース時に失敗するため、php://inputからは取得しない
        // $body = file_get_contents('php://input');
        $body = request()->getContent();
        $file = request()->files;
        //logger($body);
        $json =  json_decode($body, true);
        if (!empty($body) && is_null($json)) {
            /** @throws APIリクエストペイロードのJSONが不正な場合 */
            $message = config('error.api.illFormedPayload');
            Logger::writeSystemLog(Logger::LEVEL_ERROR, 10001,
                __FILE__.":".__LINE__, $message);
            throw new MalformedPayloadException($message);
        }
        if (is_null($json)) {
            $json = [];
        }
        if (isset($file) && $file->has('image')) {
            $json['image'] = $file->get( 'image', null);
        }
        return $json;
    }

    /**
     * APIペイロードを検査する
     * @param $key
     * @param  $payload
     * @throws IllegalParameterException
     */
    protected function validateApiPayload($key, $payload) {
        if (is_null($payload)) {
            $payload = [];
        }

        $rules = config('validation.'.$key.'.rules', []);
//        $mapper = config('validation.'.$key.'.mapper', []);
//
//        // バリデーションルールに validation.*.mapperのデータ組み込みロジックをもとに調整。
//        foreach ($mapper as $key => $record) {
//            $recordMapper = array_map(function ($v) use ($record) {
//                if (!isset($record->{$v})) {
//                    return "";
//                }
//                return $record->{$v};
//            }, $mapper);
//            $value = data_get($rules, $key);
//            $value = vsprintf($value, $recordMapper);
//            data_set($rules, $key, $value);
//        }

        $validator = validator(
            $payload,
            $rules,
            config('validation.common.errors'),
            config('validation.'.$key.'.attributes')
        );
        if ($validator->fails()) {
            logger()->debug($validator->errors());
            $errors = json_encode($validator->errors(), JSON_UNESCAPED_UNICODE);
            throw new IllegalParameterException($errors);
        }
    }


}
