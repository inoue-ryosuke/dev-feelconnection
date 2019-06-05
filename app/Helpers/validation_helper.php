<?php

// ------------------------------------------------------------------------

/**
 * バリデーションヘルパー関数
 *
 * @package     Laravel
 * @category    Helpers
 * @copyright   (c) 2012
 * @author      Takeo Noda
 */

// --------------------------------------------------------------------
if (!function_exists('isValidTagElement')) {

    function isValidTagElement($value)
    {
        $matches = array();
        $text = preg_replace('/(\r\n|\r|\n)/', '', $value);
        preg_match_all('/<\/?([^<>\/\s]+)[^<>]*>/', $text, $matches);
        // 先頭はパターン全体にマッチしたテキストが入るため、破棄。
        array_shift($matches);
        // タグを一つずつチェック
        $targetTags = config('constant.html.allowTags');
        foreach (array_unique(array_shift($matches)) as $tag) {
            if (!in_array(strtolower($tag), $targetTags)) {
                return false;
            }
        }
        // タグの対応がとれてなくてもNG
        if (substr_count($text, '<') <> substr_count($text, '>')) {
            return false;
        }
        return true;
    }
}

/**
 * 独自バリデーション（タグ閉じチェック）
 */
if (!function_exists('isValidTagClosure')) {
    function isValidTagClosure($value)
    {
        $matches1 = array();
        $matches2 = array();
        $target = preg_replace('/(\r\n|\r|\n)/', '', $value);

        preg_match_all('/<([^<>\/\s]+)[^<>]*>/', $target, $matches1);
        preg_match_all('/<\/([^<>\s]+)[^<>]*>/', $target, $matches2);
        // 先頭はパターン全体にマッチしたテキストが入るため、破棄。
        array_shift($matches1);
        array_shift($matches2);
        // カウント
        $ct1 = array();
        $ct2 = array();
        $targetTags = config('constant.html.closureTags');
        foreach (array_shift($matches1) as $tag) {
            $key = strtolower($tag);
            if (!in_array($key, $targetTags)) {
                continue;
            }
            if (isset($ct1[$key])) {
                $ct1[$key]++;
            } else {
                $ct1[$key] = 1;
            }
        }
        foreach (array_shift($matches2) as $tag) {
            $key = strtolower($tag);
            if (!in_array($key, $targetTags)) {
                continue;
            }
            if (isset($ct2[$key])) {
                $ct2[$key]++;
            } else {
                $ct2[$key] = 1;
            }
        }

        foreach (array_unique(array_merge(array_keys($ct1), array_keys($ct2))) as $key) {
            if (!array_key_exists($key, $ct1) || !array_key_exists($key, $ct2) || $ct1[$key] !== $ct2[$key]) {
                return false;
            }
        }
        return true;
    }
}
/**
 * 独自バリデーション（javascriptのチェック）
 */
if (!function_exists('hasNoJavascript')) {
    function hasNoJavascript($value)
    {
        $matches = array();
        $text = preg_replace('/(\r\n|\r|\n)/', '', $value);
        preg_match_all('/<\/?[^<>\/\s]+([^<>]*)>/', $text, $matches);
        // 先頭はパターン全体にマッチしたテキストが入るため、破棄。
        array_shift($matches);
        // タグを一つずつチェック
        foreach (array_unique(array_shift($matches)) as $attr) {
            if (preg_match('/\s?on[^=\s]+=/i', $attr)
                || preg_match('/javascript\:/i', $attr)
            ) {
                return false;
            }
        }
        return true;
    }
}
/**
 * 独自バリデーション（クオート閉じチェック）
 */
if (!function_exists('isValidQuoteClosure')) {
    function isValidQuoteClosure($value)
    {
        $target = preg_replace('/(\r\n|\r|\n)/', '', $value);
        // quote check
        $matches3 = array();
        preg_match_all('/<([^<>]+)>/', $target, $matches3);
        array_shift($matches3);
        foreach (array_shift($matches3) as $elementAndAttr) {
            if (strstr("'", $elementAndAttr) || strstr('"', $elementAndAttr)) {
                continue;
            }
            if (substr_count($elementAndAttr, '"') % 2 != 0) {
                return false;
            } elseif (substr_count($elementAndAttr, "'") % 2 != 0) {
                return false;
            }
        }

        return true;
    }
}

/**
 * 独自バリデーション（日時チェック）
 */
if (!function_exists('isValidDate')) {
    function isValidDate($value)
    {
        if ($value === '0000-00-00' || $value === '0000-00-00 00:00:00' || $value === '0000-00-00 00:00') {
            return true;
        }
        $splitted_date = preg_split('/[\-\/\s\:]/', $value);
        if (count($splitted_date) >= 3 && count($splitted_date) <= 6) {
            $year = array_shift($splitted_date);
            $month = array_shift($splitted_date);
            $day = array_shift($splitted_date);
            $hour = 0;
            if (count($splitted_date) >= 4) {
                $hour = array_shift($splitted_date);
            }
            $min = 0;
            if (count($splitted_date) >= 5) {
                $min = array_shift($splitted_date);
            }
            $sec = 0;
            if (count($splitted_date) >= 6) {
                $sec = array_shift($splitted_date);
            }
            return checkdate($month, $day, $year)
                    && range_between($hour, 0, 23)
                    && range_between($min, 0, 59)
                    && range_between($sec, 0, 59);
        }
        return false;
    }
}


/**
 * 独自バリデーション（範囲チェック）
 */
if (!function_exists('range_between')) {
    function range_between($value, $min, $max) {
        return ($min <= $value) && ($value <= $max);
    }
}

/**
 * 独自バリデーション
 */
if (!function_exists('isEmptyOrNotNumeric')) {
    function isEmptyOrNotNumeric($value) {
        return is_null($value) || $value === "" || !is_numeric($value);
    }
}

/*
 * JSON文字列のチェック
 */
if (!function_exists('isJsonString')) {
    function isJsonString($value) {
        return empty(json_decode($value,true)) ? false : true;
    }
}
