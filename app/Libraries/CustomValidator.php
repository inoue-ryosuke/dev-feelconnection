<?php namespace App\Libraries;

use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Validator;
use DB;
use App\Models\User;
use App\Models\Product;
use App\Models\Company;
use App\Libraries\Logic\Loader;
use App\Http\Controllers\Api\ApiHeaderTrait;
//use App\Http\Controllers\Admin\WebLogicTrait;

/**
 * 独自バリデータクラス
 * Class CustomValidator
 * @package App
 */
class CustomValidator extends Validator
{
//    use ApiHeaderTrait,WebLogicTrait;
    use ApiHeaderTrait;

    /**
     * 日本の郵便番号検査
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return int
     */
    public function validateJpZipCode($attribute, $value, $parameters)
    {
        return preg_match('/^[0-9]{7}+$/i', $value);
    }


    /**
     * Validate the guessed extension of a file upload is in a set of file extensions.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  array   $parameters
     * @return bool
     */
    public function validateCheckMimes($attribute, UploadedFile $value, $parameters)
    {
        logger()->debug("***validateMimes***");
        if ($value instanceof UploadedFile) {
//            logger()->debug($value->getBasename());
//            logger()->debug($value->getClientMimeType());
            $basename = $value->getBasename();
            $uploadName = base_path(config('constant.admin.uploadDir')).DIRECTORY_SEPARATOR.$basename;
            $matchedMimes = array_filter($parameters,
                function($target) use ($value) {
                    return str_contains($value->getClientMimeType(), $target);
                });
//            logger()->debug($matchedMimes);
//            logger()->debug($uploadName);
            if (file_exists($uploadName) && count($matchedMimes) > 0) {
                return true;
            }
        }
        return false;
    }

    /**
     * 日付けが登録されているかチェック
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return date
     */
    public function validateIsDateRegister($attribute, $value, $parameters)
    {
        $parameters[0] = DB::table('help')->min('opened_at');
        $parameters[1] = DB::table('help')->max('closed_at');
        if ( strtotime($parameters[0]) > strtotime(date( "Y-m-d H:i:s", time() ))){
            return strtotime($value) < strtotime($parameters[0])  || strtotime($value) > strtotime($parameters[1]);
        }else{
            return strtotime($value) > strtotime($parameters[1]);
        }
    }

    /**
     * 英数字アンダースコア入力チェック
     * @param $attribute
     * @param $value
     * @return bool|int
     */
    protected function validateAlphaUnderScore($attribute, $value)
    {
        if (! is_string($value) && ! is_numeric($value)) {
            return false;
        }

        return preg_match('/^[\pL\pM\pN_]+$/u', $value);
    }

    /**
     * 英数字アンダースコア入力チェック
     * @param $attribute
     * @param $value
     * @return bool|int
     */
    protected function validateFileName($attribute, $value)
    {
        if (!is_string($value)) {
            return false;
        }

        return !preg_match('/[\\\\\/\:,;<>\{\}\*\?"]/u', $value);
    }

    /**
     * Validate that an attribute contains only alpha-numeric characters, dashes, and underscores.
     * @param type $attribute
     * @param type $value
     * @return boolean
     */
    public function validateAlphaNum($attribute, $value)
    {
        if (! is_string($value) && ! is_numeric($value)) {
            return false;
        }

        return preg_match('/^[0-9a-zA-Z]+$/u', $value);
    }

    /**
     * Validate that an attribute contains only alpha-numeric characters, dashes, and underscores.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @return bool
     */
    protected function validateAlphaNumDash($attribute, $value)
    {
        if (! is_string($value) && ! is_numeric($value)) {
            return false;
        }

        return preg_match('/^[0-9a-zA-Z_-]+$/u', $value);
    }

    /**
     * 日付けが現時刻と同じか後に設定する
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return date
     */
    public function validateIsDateAfterNow($attribute, $value, $parameters)
    {
        // 年月にも対応
        if (preg_match('/^(\d{4})(\d{2})$/', $value, $matches)) {
            /** @var String $all */
            list($all, $year, $month) = $matches;
            if (checkdate($month, 1, $year)
                    && $year.$month >= date("Ym", time())) {
                return true;
            }
            return false;
        }
        // それ以外
        return strtotime($value) >= strtotime(date( "Y-m-d", time() ));
    }

    /**
     * 6文字の年月が対象カラムより同じかより大きいか判定する。
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool|int
     */
    public function validateAfterOrEqualYm($attribute, $value, $parameters)
    {
        $target = "";
        if (isset($parameters[0]) && request()->has($parameters[0])) {
            $target = request()->input($parameters[0]);
            $target = $this->shortenDate($target, 6);
        } else if (isset($parameters[0])) {
            $target = $parameters[0];
            $target = $this->shortenDate($target, 6);
        }
        $value = $this->shortenDate($value, 6);
        logger()->debug('validate after or equal ym:'.$target.'|'.$value);
        if (!empty($target) && preg_match('/^(\d{4})(\d{2})$/', $value, $matches)) {
            /** @var String $all */
            list($all, $year, $month) = $matches;
            if (checkdate($month, 1, $year)
                    && $year.$month >= $target) {
                return true;
            }
            return false;
        }
        return $value >= $target;
    }

    /**
     * 8文字の年月日が対象カラムより同じかより大きいか判定する。
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool|int
     */
    public function validateAfterOrEqualYmd($attribute, $value, $parameters)
    {
        $target = "";
        if (isset($parameters[0])) {
            $target = $parameters[0];
            $target = $this->shortenDate($target, 8);
        }
        $value = $this->shortenDate($value, 8);
        logger()->debug('validate after or equal ym:'.$target.'|'.$value);
        return $value >= $target;
    }

    /**
     * ファイルのアップロードチェック
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return date
     */
    public function validateFileRequired($attribute, $value, $parameters)
    {
        logger()->debug("****file required");
        // 設定がおかしい場合はエラー
        if (!isset($parameters[0])) {
            logger()->debug("****file required end");
            return false;
        }
        // ファイル情報から必須チェック
        if ($attribute === 'encrypted_file_info') {
            $target = json_decode(base64_decode($parameters[0]), true);
            logger()->debug($target);
            if (!is_array($target)) {
                logger()->debug("****file required end");
                return false;
            }
            for ($xi = 1; $xi < count($parameters); $xi++) {
                $key = $parameters[$xi];
                if (array_key_exists($key, $target)) {
                    logger()->debug("****file required end");
                    return true;
                }
            }
            logger()->debug("****file required end");
            return false;
        }
        logger()->debug("****file required end");
        return true;
    }


    /**
     * ファイルのユニークチェック
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return date
     */
    public function validateUniqueFilename($attribute, $value, $parameters)
    {
        logger()->debug("****check unique filename");
        // 設定がおかしい場合はエラー
        if (!isset($parameters[0])) {
            logger()->debug("****check unique filename end");
            return false;
        }
        // ファイル情報の属性チェックのみ
        if (preg_match('/^file\d+$/', $attribute)) {
            $target = json_decode(base64_decode($parameters[0]), true);
            logger()->debug($target);
            if (!is_array($target)) {
                logger()->debug("****check unique filename end");
                return true; // 名前のユニークチェックをしない
            }
            if (!array_key_exists($attribute, $target)) {
                logger()->debug("****check unique filename end");
                return true; // アップロードがなければスルー。
            }
            // 比較するファイル名
            $targetOriginalName = trim(array_selector($attribute, $target, '', 'originalName'));
            logger()->debug('target original name:'.$targetOriginalName);
            if (empty($targetOriginalName)) {
                logger()->debug("****check unique filename end");
                return true; // 空値の場合、スルー。
            }

            // 重複チェック
            for ($xi = 1; $xi < count($parameters); $xi++) {
                $key = $parameters[$xi];
                logger()->debug('validete unique filename from '.$attribute.' to '.$key);
                if (!array_key_exists($key, $target)) {
                    continue; // アップロードがなければスルー。
                }
                $originalName = trim(array_selector($key, $target, '', 'originalName'));
                logger()->debug('original name:'.$originalName);
                if ($targetOriginalName === $originalName) {
                    logger()->debug("****check unique filename end");
                    return false;
                }
            }
            logger()->debug("****check unique filename end");
            return true;
        }
        logger()->debug("****check unique filename end");
        return true;
    }

    /**
     * 全角の文字長チェックを行う。
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    public function validateZenkakuMax($attribute, $value, $parameters) {
        $this->requireParameterCount(1, $parameters, 'max');
        $max = $parameters[0];
        $length = strlen(mb_convert_encoding($value, "sjis"));
        if ($length > $max * 2) {
            return false;
        }
        return true;
    }

    /**
     * Replace all place-holders for the zenkaku max rule.
     *
     * @param  string  $message
     * @param  string  $attribute
     * @param  string  $rule
     * @param  array   $parameters
     * @return string
     */
    protected function replaceZenkakuMax($message, $attribute, $rule, $parameters)
    {
        return str_replace(':max', $parameters[0], $message);
    }

    /**
     * Replace all place-holders for the line max rule.
     *
     * @param  string  $message
     * @param  string  $attribute
     * @param  string  $rule
     * @param  array   $parameters
     * @return string
     */
    protected function replaceLineMax($message, $attribute, $rule, $parameters)
    {
        return str_replace(':max', $parameters[0], $message);
    }


    /**
     * 最大行数をチェックする
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    public function validateRowMax($attribute, $value, $parameters)
    {
        $this->requireParameterCount(1, $parameters, 'max');
        $max = $parameters[0];
        $list = preg_split('/(\r\n|\r|\n)/', $value);
        if (empty($list)) {
            return true;
        }
        return !empty($list) && count($list) <= $max;
    }

    /**
     * Replace all place-holders for the row max rule.
     *
     * @param  string  $message
     * @param  string  $attribute
     * @param  string  $rule
     * @param  array   $parameters
     * @return string
     */
    protected function replaceRowMax($message, $attribute, $rule, $parameters)
    {
        return str_replace(':max', $parameters[0], $message);
    }



    /**
     * @param $attribute
     * @param $value
     * @param $paramaters
     * @return bool
     */
    public function validateCheckPassword($attribute, $value, $paramaters)
    {
        $user = auth('web')->user();
        return !empty($user) && bcrypt($value) === $user->password;
    }

    /**
     * 電話番号の検査
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    public function validateTelephone($attribute, $value, $parameters)
    {
        return trim($value)==="" || preg_match('%^(?:(?:\(?(?:00|\+)([1-4]\d\d|[1-9]\d?)\)?)?[\-\.\ \\\/]?)?((?:\(?\d{1,}\)?[\-\.\ \\\/]?){0,})(?:[\-\.\ \\\/]?(?:#|ext\.?|extension|x)[\-\.\ \\\/]?(\d+))?$%i', $value) && strlen($value) >= 10;
    }

    /**
     * 期間の重複チェック
     * @param $attribute
     * @param $value
     * @param $paramaters
     * @return bool
     */
    public function validateDuplicatedDate($attribute, $value, $parameters) {
        logger()->debug("validateDuplicatedDate($attribute,$value):".print_r($parameters,true));

        $key1_s = $attribute;
        $key1_e = $parameters[0];

        if(empty(request()->input($key1_s))) return true;

        $val1_s = request()->input($key1_s);
        $val1_e = request()->input($key1_e);

        foreach(range(1,5,1) as $val2) {
            $key2_s = 'schedule_'.$val2.'s';
            $key2_e = 'schedule_'.$val2.'e';
            if(empty(request()->input($key2_s))) continue;
            if($key1_s == $key2_s) continue;

            $val2_s = request()->input($key2_s);
            $val2_e = request()->input($key2_e);

            if($val1_s <= $val2_e && $val1_e >= $val2_s) return false;
        }
        return true;
    }

    /**
     * ログインIDユニークチェック
     * @param type $attribute
     * @param type $value
     * @param type $parameters
     * @return boolean
     */
    public function validateUniqueUserLoginId($attribute, $value, $parameters) {
        $user = User::findByLoginId($parameters[0]);

        if(empty($user)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * アップロードされたCSVファイルチェック
     * @param type $attribute
     * @param type $value
     * @param type $parameters
     */
    public function validateCheckCsvFileType($attribute, $value, $parameters) {
        $finfo  = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $value->path());
        finfo_close($finfo);
        $ext = pathinfo($value->getClientOriginalName(), PATHINFO_EXTENSION);

        // テキストファイルかつファイルの拡張子がCSVファイルかをチェック
        if($mimeType == 'text/plain' && $ext == 'csv') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * リレーショングループのサービス値チェック
     * @param type $attribute
     * @param type $value
     * @param type $parameters
     * @return boolean
     */
    public function validateCheckServiceId($attribute, $value, $parameters) {
        $serviceIdList = config('form.serviceList');

        foreach($value as $serviceId) {
            if(!isset($serviceIdList[$serviceId])) {
                return false;
            }
        }
        return true;
    }

    /*
     * 年月チェック(YYYYMM形式)
     */
    public function validateIsYearMonthDate($attribute, $value, $parameters) {
        if(preg_match('/^[0-9]{6}+$/i', $value)) {
            $year = substr($value, 0, 4);
            $month = substr($value, 4, 2);

            if (!checkdate($month, 1, $year)) {
                return false;
            }
        } else if(preg_match('/^[0-9]{4}[\-\/][0-9]{2}$/i', $value)) {
            $year = substr($value, 0, 4);
            $month = substr($value, 5, 2);

            if(!checkdate($month, 1, $year)) {
                return false;
            }
        } else {
            return false;
        }
        return true;
    }

    /**
     * 選択リストチェック
     * @param type $attribute
     * @param type $value
     * @param type $parameters
     * @return boolean
     */
    public function validateSelectList($attribute, $value, $parameters) {
        $list = config('form.' . $parameters[0]);
        if(!isset($list[$value])) {
            return false;
        }
        return true;
    }

    /**
     * 日付の桁数を調整する
     * @param $value
     * @param $digit
     * @return bool|null|string|string[]
     */
    private function shortenDate($value, $digit = 8)
    {
        $value = preg_replace('/[\-\:\/ ]/', '', $value);
        $value = substr($value, 0, $digit);
        return $value;
    }

    /**
     * 配列の値でバリデーション
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return mixed
     */
    public function validateIndexSort($attribute, $value, $parameters)
    {
        $sortable = $parameters[0]::getSortable($parameters[1]);
        logger('ここだよ');
        logger($sortable);
        if (in_array($value,$sortable)) {
            return true;
        }
        request()->merge([
            'sort' => '',
            'order' => '',
        ]);
        return false;
    }

    /**
     * JANコード重複チェック
     * productTokenが渡ってきている場合、自身のデータはチェック対象外とする
     * @param $attribute
     * @param $value
     * @param $params
     * @param $validator
     * @return boolean
     */
    public function validateJanCodeCheck($attribute, $value, $params, $validator)
    {
        $data = $validator->getData();
        logger("validateJanCodeCheck()");
        logger($data);
        $inputJanCode  = $data['jan_code'] ?? null;
        $productToken = $data['token'] ?? null;

        if (!is_null($productToken)) {
            $product = Product::findByTokenOrFail($productToken);
        } else {
            $product = new Product();
            $user = $this->getAuthUserInfo();
            $event = $user->getMyEvent();
            if (is_null($event)) {
                return false;
            }
            $product->event_id = $event->id;
        }
        return (boolean)$product->isDuplicateJanCode($inputJanCode);
    }

    /**
     * 修正処理時のログインIDのチェック
     * userTokenで自身のデータはチェック対象外とする
     * @param $attribute
     * @param $value
     * @param $params
     * @param $validator
     * @return boolean
     */
    public function validateLoginIdCheck($attribute, $value, $params, $validator)
    {
        $data = $validator->getData();

        $inputLoginId = $data['login_id'];
        $userToken = $data['userToken'];

        $count = User::countByLoginId($inputLoginId,$userToken);

        if($count > 0){
            return false;
        }

        return true;

    }

    /**
     * 配列の値でバリデーション
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return mixed
     */
    public function validateSortColumn($attribute, $value, $parameters)
    {
        if (in_array($value,$parameters)) {
            return true;
        }

        return false;
    }

    /**
     * イベント名重複チェック
     * API-85bを使用してチェック
     * @param $attribute
     * @param $value
     * @param $params
     * @param $validator
     * @return boolean
     */
    public function validateEventNameCheck($attribute, $value, $params, $validator)
    {

        $data = $validator->getData();

        $type = $data['type'];

        if($type != User::TYPE_EVENT){
            return true;
        }

        //ログインユーザから引き継ぐ情報を取得
        $loginUser = $this->getAuthUserInfo();
        $loginUserCompanyId = $loginUser->company_id;

        $companyToken = null;
        if(is_null($loginUserCompanyId) == false){
            $company = Company::findById($loginUserCompanyId);
            $companyToken = $company->token;
        }

        $response = $this->getApiLogic(Loader::USER)->duplicationCheckOfEventName($companyToken,$data);

        $isExist = $response['is_exist'];

        $nothing = $this->getApiLogic(Loader::USER)::EVENTNAME_NOTHING;

        if($isExist == $nothing){
            return true;
        }

        return false;
    }

}
