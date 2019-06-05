<?php

// ------------------------------------------------------------------------

/**
 * CodeIgniter ネット関連ヘルパー関数
 *
 * @package     CodeIgniter
 * @category    Helpers
 * @copyright   (c) 2012
 * @author      Takeo Noda
 */

// --------------------------------------------------------------------
if (!function_exists('fetch_url')) {
    function fetch_url($url, $method = "POST", $header = array(), $data = "")
    {
        $arr = parse_url($url);
        // query
        $arr['query'] = isset($arr['query']) ? '?' . $arr['query'] : '';
        // port
        $arr['port'] = isset($arr['port']) ? $arr['port'] : 80;
        if ($arr['port'] == 80) {
            $url_base = $arr['scheme'] . '://' . $arr['host'];
        } else {
            $url_base = $arr['scheme'] . '://' . $arr['host'] . ':' . $arr['port'];
        }
        $url_path = isset($arr['path']) ? $arr['path'] : '/';
        $url = $url_path . $arr['query'];

        $query = $method . ' ' . $url . " HTTP/1.0\r\n";
        $query .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $query .= "Host: " . $arr['host'] . "\r\n";
        foreach ($header as $key => $value) {
            $query .= $key . ": " . $value . "\r\n";
        }
        // POST 時は、urlencode したデータとする
        if (strtoupper($method) == 'POST') {
            $query .= 'Content-Length: ' . strlen($data) . "\r\n";
            $query .= "\r\n";
            $query .= $data;
        } else {
            $query .= "\r\n";
        }

        $response = '';
        $fp = fsockopen(
            $arr['host'],
            $arr['port'],
            $errno, $errstr, 30);
        if (!$fp) {
            return $response;
        }

        fputs($fp, $query);

        $body = false;
        while (!feof($fp)) {
            $target = fread($fp, 4096);
            $response .= $target;
        }
        fclose($fp);
        list($headerString, $body) = preg_split('/(\r\n){2}/', $response);
        return $body;
    }
}

if (!function_exists('fetch_curl')) {
    function fetch_curl($url, $method = "POST", $header = array(), $data = "")
    {
        $headers = array();
        foreach ($header as $key => $value) {
            $headers[] = $key . ": " . $value . "\r\n";
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $r = curl_exec($ch);
//		$info = curl_getinfo ($ch);

        // ボディ部分を取得
        return $r;
    }
}

if (!function_exists('strip_response_header')) {
    function strip_response_header($response)
    {
        return $response;
    }
}

if (!function_exists('make_query_string')) {
    function make_query_string($data, $exceptKeys = [], $encode = TRUE)
    {
        if (!is_array($exceptKeys)) {
            $exceptKeys = [$exceptKeys];
        }
        $result = "";
        if (is_array($data) && $encode) {
            foreach ($data as $key => $value) {
                if (in_array($key, $exceptKeys)) {
                    continue;
                }
                $result .= $key . "=" . urlencode($value) . "&";
            }
        } else if (is_array($data)) {
            foreach ($data as $key => $value) {
                if (in_array($key, $exceptKeys)) {
                    continue;
                }
                $result .= $key . "=" . $value . "&";
            }
        } else {
            $result = $data;
        }
        return preg_replace('/&$/', '', $result);
    }
}


/**
 * 妥当なURLパスであるかチェックする。
 * @param $link_url 外部誘導リンクURL
 * @return boolean
 */
if (!function_exists('is_url')) {
    function is_url($link_url)
    {
        return preg_match('/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(\.[A-Z0-9][A-Z0-9_-]*)+)/i', $link_url);
    }
}


/**
 * プライベートネットワークであるかチェックする。
 * @param $link_url 外部誘導リンクURL
 * @return boolean
 */
if (!function_exists('is_private_network')) {
    function is_private_network($serverName = null)
    {
        if (is_null($serverName)) {
            $serverName = $_SERVER['SERVER_NAME'];
        }
        return preg_match('/^(10\.|172\.16\.|192\.168\.)/', $serverName);
    }
}

/* End of file inflector_helper.php */
/* Location: ./application/helpers/inflector_helper.php */