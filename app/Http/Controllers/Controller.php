<?php

namespace App\Http\Controllers;

use App\ValidatorTrait;
use App\Exceptions\MalformedPayloadException;
use App\Exceptions\IllegalParameterException;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\MessageBag;

class Controller extends BaseController
{
//  use AuthorizesRequests, DispatchesJobs, ValidatesRequests, ValidatorTrait;
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // アプリケーション種別を設定する
    const TYPE_API = "api";
    const TYPE_ADMIN = "admin";
    const TYPE_WEB = "web";
    const TYPE_EMPTY = null;

    private $data = [];

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        $userAgent = request()->header('User-Agent');
        if (preg_match('/PhantomJS/', $userAgent) || preg_match('/Dusk/', $userAgent)) {
            \Debugbar::disable();
            logger('debugbar off (PhantomJS)');
        }
    }

    /**
     * ビューにマージするデータを取得
     * @param $key
     * @return array
     */
    protected function getData($key = null) {
        if (is_null($key)) {
            return $this->data;
        }
        return array_get($this->data, $key, null);
    }

    /**
     * ビュー用のデータに値をマージする
     * @param array $data
     */
    protected function mergeData($data = []) {
        foreach ($data as $key => $value) {
            // バリデータの場合、エラーを取り出してセット
            if ($key === 'validator') {
                $errors = new MessageBag();
                if (!is_null($value)) {
                    $errors = $value->getMessageBag();
                }
                logger($errors);
                $this->data['errors'] = $errors;
            }
            // 値を組み込み
            $this->data[$key] = $value;
        }
    }

    /**
     * @param $input
     * @param $record
     * @param $form
     * @param $validator
     * @param $key
     * @return array
     */
    protected function setupData($input, $record, $form, $key = "", $validator = null)
    {
        $id = array_get($record, 'id', null);
        $errors = new MessageBag();
        if (!is_null($validator)) {
            $errors = $validator->getMessageBag();
        }
        $user = $this->getUser();
        $templateKey = $key;
        $this->mergeData(compact('record', 'input', 'id', 'form', 'validator', 'errors', 'templateKey', 'user'));
        return $this->getData();
    }

    /**
     * ユーザー情報を取得
     * @return mixed ユーザー情報
     */
    protected function getUser() {
        $user = auth('users')->user();
        return $user;
    }

    /**
     * ビューを表示する
     * @param $viewName
     * @param $data
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View レスポンス
     */
    protected function view($viewName, $data = []) {
        return view($viewName, $data);
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
        $mapper = config('validation.'.$key.'.mapper', []);

        // バリデーションルールに validation.*.mapperのデータ組み込みロジックをもとに調整。
        foreach ($mapper as $key => $record) {
            $recordMapper = array_map(function ($v) use ($record) {
                if (!isset($record->{$v})) {
                    return "";
                }
                return $record->{$v};
            }, $mapper);
            $value = array_get($rules, $key);
            $value = vsprintf($value, $recordMapper);
            array_set($rules, $key, $value);
        }

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