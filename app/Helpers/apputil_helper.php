<?php
//require_once 'Crypt/Blowfish.php';

// ------------------------------------------------------------------------

/**
 * CodeIgniter アプリケーションヘルパー関数
 *
 * @package        CodeIgniter
 * @category       Helpers
 * @copyright      (c) 2012
 * @author         Takeo Noda
 */

// --------------------------------------------------------------------

if (!function_exists('isAjax')) {

    /**
     * Ajaxによるリクエストかどうか判定
     *
     * @return boolean True or False
     */
    function isAjax()
    {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            return true;
        }
        return false;
    }
}

if (!function_exists('format_empty')) {
    function format_empty($text, $nullText = null) {
        if (is_null($nullText)) {
            $nullText = config('constant.nullValue');
        }

        if ($text === '' || is_null($text)) {
            return $nullText;
        }
        return $text;
    }
}

if ( ! function_exists('isApiRequest'))
{

    /**
     * Apiによるリクエストかどうか判定
     *
     * @return boolean True or False
     */
    function isApiRequest() {
        $currentUrl = url()->current();
        $urlKey = "api.get";
        $urlAdminKey = "api.admin.get";
        $headers = apache_request_headers();
        try {
            if (strpos($currentUrl, route($urlAdminKey)) !== FALSE) {
                // アプリはauth:apiで認証をかけているのでauth:webの認証をしない。
                // 管理画面APIはすべてセッション認証の為、API判定はfalseで返す。
                return true;
            } else if (strpos($currentUrl, route($urlKey)) !== FALSE) {
                return true;
            } else if(!is_null($headers) && isset($headers['X-Everegi-Version'])){
                return true;
            }
        } catch (\InvalidArgumentException $e) {
            return false;
        }
        return false;
    }
}


if (!function_exists('apache_request_headers')) {
    function apache_request_headers() {
        $headers = [];
        $rule = '/\AHTTP_/';
        foreach($_SERVER as $key => $val) {
            if(!preg_match($rule, $key) ) {
                continue;
            }
            $requestHeaderKey = preg_replace($rule, '', $key);
            // do some nasty string manipulations to restore the original letter case
            // this should work in most cases
            $matches = explode('_', $requestHeaderKey);
            if( count($matches) > 0 && strlen($requestHeaderKey) > 2 ) {
                foreach($matches as $maKey => $maVal) {
                    $matches[$maKey] = ucfirst($maVal);
                }
                $requestHeaderKey = implode('-', $matches);
            }
            $headers[$requestHeaderKey] = $val;
        }
        return $headers;
    }
}

/**
 */
if (!function_exists('h')) {
    function h($text)
    {
        return htmlspecialchars($text, ENT_QUOTES);
    }
}

/**
 * 日時を短縮する
 */
if (!function_exists('simplifyDateFormat')) {
    function simplifyDateFormat($text) {
        return str_replace(['-',':', ' '], '', $text);
    }
}


/**
 * URL にリンクを付与する
 */
if (!function_exists('addTagA')) {
    function addTagA($text)
    {
        $text = preg_replace('/((?:https?|ftp):\/\/[-_.!~*\'()a-zA-Z0-9;\/?:@&=+$,%#]+)/u', '<a href="$1" target="_blank">$1</a>', $text);
        return $text;
    }
}

/**
 * エクスポート用ヘッダーを出力する
 */
if (!function_exists('makeExportHeader')) {
    function makeExportHeader($mapper, $splitter = "\t")
    {
        if (empty($mapper)) {
            return;
        }
        $header = array_map(function ($v) use ($mapper) {
            return array_get($mapper, $v.'.name', '-');
        }, array_keys($mapper));
        return implode($splitter, $header);
    }
}

/**
 * エクスポート用ヘッダーを出力する
 */
if (!function_exists('outputExportHeader')) {
    function outputExportHeader($mapper, $splitter = "\t")
    {
        echo makeExportHeader($mapper, $splitter)."\n";
    }
}

/**
 * @param $mapper
 * @param $record
 */
if (!function_exists('outputExportRecord')) {
    function outputExportRecord($mapper, $record, $form, $splitter = "\t")
    {
        // レコード一覧から変換処理
        foreach ($mapper as $key => $rule) {
            $value = array_get($record, $key);
            if (!empty($rule['table'])) {
                $value = array_get(array_get($form, $rule['table'], []), $value);
            }
            $value = preg_replace("/(\r\n|\r|\n)/", "\\n", $value);
            echo $value . $splitter;
        }
        echo "\n";
    }
}


if (!function_exists('strtohex')) {
    function strtohex($x)
    {
        $s='';
        foreach (str_split($x) as $c) $s.=sprintf("%02X",ord($c));
        return($s);
    }
}


if (!function_exists('encrypt_openssl'))
{
    /**
     * OPENSSLで可逆暗号化
     * @param $text 暗号化文字列
     * @param $key 暗号化キー
     * @return mixed
     */
    function encrypt_openssl($text, $key = null)
    {
        if (empty($text)) {
            return $text;
        }
        // 暗号化キー
        if (is_null($key)) {
            $key = config('constant.passPhrase');
        }
        $encrypt = openssl_encrypt($text, 'AES-128-ECB', $key);
        return $encrypt;
    }
}
if (!function_exists('decrypt_openssl'))
{
    /**
     * OPENSSLで複合化
     * @param $encrypt 暗号化文字列
     * @param $key 暗号化キー
     * @return mixed
     */
    function decrypt_openssl($encrypt, $key = null)
    {
        if (empty($encrypt)) {
            return $encrypt;
        }
        // 暗号化キー
        if (is_null($key)) {
            $key = config('constant.passPhrase');
        }
        $text = openssl_decrypt($encrypt, 'AES-128-ECB', $key);
        return $text;
    }
}


if (!function_exists("convert_encoding")) {
    /**
     * convert_encoding
     * mb_convert_encodingの拡張
     *
     * @param  mixed  $target       arrayかstring
     * @param  string $toEncoding   エンコード先
     * @param  string $fromEncoding エンコード元(default:null)
     * @return mixed  arrayが来たらarrayを、stringが来たらstringを
     */
    function convert_encoding($target, $toEncoding, $fromEncoding = null){
        if (is_array($target)) {
            foreach ($target as $key => $val) {
                if (is_null($fromEncoding)) {
                    $fromEncoding = mb_detect_encoding($val);
                }
                $target[$key] = convert_encoding($val, $toEncoding, $fromEncoding);
            }
        } else {
            if  (is_null($fromEncoding)) {
                $fromEncoding = mb_detect_encoding($target);
            }
            $target = mb_convert_encoding($target, $toEncoding, $fromEncoding);
        }
        return $target;
    }

}

if (!function_exists('number_random')) {
    function number_random($length = 16)
    {
        $pool = '0123456789';
        return substr(str_shuffle(str_repeat($pool, $length)), 0, $length);
    }
}

if (!function_exists('fixed_asset')) {
    /**
     * HTTPS の状態をみて適切なレスポンスを返す
     * @param $path
     * @return mixed
     */
    function fixed_asset($path)
    {
        return (isset($_SERVER['HTTPS']) && (FALSE === empty($_SERVER['HTTPS'])) && ('off' !== $_SERVER['HTTPS'])) ?
            secure_asset($path) : asset($path);
    }
}


/**
 * jsonデータからのデータ取得
 */
if (!function_exists('json_convert'))
{
    function json_convert($json,$key)
    {
        $decode_data = json_decode($json,true);
        return $decode_data[$key];
    }
}


if (!function_exists('lzerofill')) {
    function lzerofill($str, $length) {
        $textLength = mb_strlen($str, 'UTF-8');
        if ($textLength > $length) {
            return $str;
        }
        return $str.str_repeat("0", $length - $textLength);
    }
}


if (! function_exists('has_route')) {
    /**
     * Generate the URL to a named route.
     *
     * @param  string  $name
     * @param  array   $parameters
     * @param  bool    $absolute
     * @return string
     */
    function has_route($name, $parameters = [], $absolute = true)
    {
        try {
            app('url')->route($name, $parameters, $absolute);
        } catch (\InvalidArgumentException $e) {
            return false;
        }
        return true;
    }
}

/**
 * Twig 向け関数
 * static 関数を呼び出す便利メソッド
 */
if (!function_exists('staticCall')) {
    function staticCall($class, $function, $args = array())
    {
        if (class_exists($class) && method_exists($class, $function))
            return call_user_func_array(array($class, $function), $args);
        return null;
    }
}

if (!function_exists('call_in_background')) {
    function call_in_background($command, $before = null, $after = null)
    {
        return Command::factory($command, $before, $after)->runInBackground();
    }
}
// 連番数字の日付有効性チェック
if (!function_exists('isDateOfSerialNumberType')) {
    function is_serialdate($str) {
        if (!preg_match("#^([0-9]{4})([0-9]{2})([0-9]{2})$#",$str,$match)) {
            return false;
        }
        return checkdate($match[2],$match[3],$match[1]);
    }
}

/**
 * 数値チェック
 * [チェック内容]
 * 数字以外の文字が含まれていないこと
 * 有効範囲内の数値であること(min <=  $str <= max)
 */
if(!function_exists('numericRangeCheck')){
    function numericRangeCheck($str,$min = null,$max = null){

        if(is_decimal($str) === false){

            return false;
        }
        
        if((is_null($min) === false) &&( (int)$str < $min)){

            return false;
        }
        
        if((is_null($max) === false) && ((int)$str > $max)){

            return false;
        }
    
        return true;
    }
    
}

/**
 * 文字列がすべて数値であることのチェック
 */
if(!function_exists('is_decimal')){
    function is_decimal($value){
        return filter_var($value, FILTER_VALIDATE_INT) !== false;
    }
    
}
