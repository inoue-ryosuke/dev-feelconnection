<?php

// ------------------------------------------------------------------------

/**
 * CodeIgniter DateUtil Helpers
 *
 * date util helpers.
 *
 * @package     CodeIgniter
 * @subpackage  Helpers
 * @category    Helpers
 * @author      Takeo Noda
 * @link
 */
use Carbon\Carbon;
// --------------------------------------------------------------------
setlocale(LC_TIME, "ja_JP");

/**
 */
if (!function_exists('time2date')) {
    function time2date($time, $splitter = "-")
    {
        $dp = getdate(intval($time));
        return sprintf("%04d$splitter%02d$splitter%02d", $dp["year"], $dp["mon"], $dp["mday"]);
    }
}

// --------------------------------------------------------------------

/**
 */
if (!function_exists('time2datetime')) {
    function time2datetime($time, $dsplitter = "-", $tsplitter = ":")
    {
        $dp = getdate($time);
        return sprintf("%04d$dsplitter%02d$dsplitter%02d %02d$tsplitter%02d$tsplitter%02d", $dp["year"], $dp["mon"], $dp["mday"], $dp["hours"], $dp["minutes"], $dp["seconds"]);
    }
}


// --------------------------------------------------------------------
/**
 */
if (!function_exists('is_weekday')) {
    function is_weekday($date) {
        $wday = date2wday($date);
        return ($wday >= 1 && $wday <= 5); // 	0 (日曜) から 6 (土曜)
    }
}

/**
 */
if (!function_exists('is_weekday_by_time')) {
    function is_weekday_by_time($time) {
        $wday = time2wday($time);
        return ($wday >= 1 && $wday <= 5); // 	0 (日曜) から 6 (土曜)
    }
}

/**
 */
if (!function_exists('date2time')) {
    /**
     * @param $date
     * @return int
     */
    function date2time($date)
    {
        $splitted = preg_split('/[\/\: \-]/', $date);
        $matches = array();
        if (empty($date)) {
            return time();
            // strtotime should handle it
        } else if ($date > "2038-01-19") {
            return date2time("2038-01-19");
        } else if (count($splitted) == 3 && preg_match('/^[0-9]+([\/: -][0-9]+)+$/', $date)) {
            list($year, $month, $day) = $splitted;
            if (checkdate($month, $day, $year)) {
                return mktime(0, 0, 0, $month, $day, min(2038, $year));
            } else {
                return time();
            }
        } else if (preg_match('/^(\d{4})(\d{2})(\d{2})$/', $date, $matches)) {
            /** @var String $all */
            list($all, $year, $month, $day) = $matches;
            if (checkdate($month, $day, $year)) {
                return mktime(0, 0, 0, $month, $day, min(2038, $year));
            } else {
                return time();
            }
        } else {
            return time();
        }
    }
}


// --------------------------------------------------------------------
/**
 */
if (!function_exists('time2thisweek_date')) {
    function time2thisweek_date($time, $wday = 1)
    {
        $dinfo = getdate($time);
        $diff = $dinfo['wday'] - $wday;
        return prev_date(time2date($time), $diff);
    }
}

// --------------------------------------------------------------------
/**
 */
if (!function_exists('date2wday')) {
    function date2wday($date)
    {
        return time2wday(date2time($date));
    }
}

if (!function_exists('time2wday')) {
    function time2wday($datetime)
    {
        $dinfo = getdate(intval($datetime));
        return $dinfo['wday'];
    }
}

// --------------------------------------------------------------------
/**
 */
if (!function_exists('calendar_start_time')) {
    // $time は Y-m-01　の timestamp を指定する
    function calendar_start_time($time)
    {
        $wday = time2wday($time);
        $time = $time - $wday * 86400;
        return $time;
    }
}

/**
 */
if (!function_exists('calendar_end_time')) {
    function calendar_end_time($time)
    {
        $time = calendar_start_time($time) + 86400 * 42;
        return $time;
    }
}


// --------------------------------------------------------------------
/**
 */
if (!function_exists('prev_date')) {
    function prev_date($date, $diff = 1, $splitter = '-')
    {
        return time2date(date2time($date) - 86400 * $diff, $splitter);
    }
}


// --------------------------------------------------------------------

/**
 */
if (!function_exists('next_date')) {
    function next_date($date, $diff = 1, $splitter = '-')
    {
        return time2date(date2time($date) + 86400 * $diff, $splitter);
    }
}

// --------------------------------------------------------------------

/**
 */
if (!function_exists('next_month')) {
    function next_month($date, $ct = 1, $splitter = "-")
    {
        $recordset = preg_split('/[-\/]/', $date);
        if (count($recordset) < 2) {
            return "";
        }
        $yyyy = $recordset[0];
        $mm = $recordset[1];
        $bm = $ct % 12;
        $by = (int)($ct / 12);
        list($yyyy, $mm) = ($mm + $bm <= 12 ? array($yyyy + $by, $mm + $bm) : array($yyyy + $by + 1, $mm + $bm - 12));
        return sprintf("%04d$splitter%02d", $yyyy, $mm);
    }
}

if (!function_exists('prev_month')) {
    function prev_month($date, $ct = 1, $splitter = "-")
    {
        $recordset = preg_split('/[-\/]/', $date);
        if (count($recordset) < 2) {
            return "";
        }
        $yyyy = $recordset[0];
        $mm = $recordset[1];
        $bm = $ct % 12;
        $by = (int)($ct / 12);
        list($yyyy, $mm) = ($mm - $bm >= 1 ? array($yyyy - $by, $mm - $bm) : array($yyyy - $by - 1, $mm - $bm + 12));
        return sprintf("%04d$splitter%02d", $yyyy, $mm);
    }
}

// --------------------------------------------------------------------
/**
 */
if (!function_exists('round_week_date')) {
    // 0: 日曜日 1: 月曜日 2: 火曜日 3: 水曜日 4: 木曜日 5: 金曜日 6: 土曜日
    function round_week_date($wday, $date)
    {
        $dp = getdate(date2time($date));
        $wayback = $dp['wday'] >= $wday ? $dp['wday'] - $wday : $dp['wday'] + 7 - $wday; // 直近のターゲット日までの差分を調べる
        return prev_date($date, $wayback);
    }
}

/**
 */
if (!function_exists('date2yearmonth')) {
    /**
     * 指定した日付からY-mを取得する
     * @param    String $date タイムスタンプ
     * @param    String $splitter 区切り文字列
     * @return    Integer        $date        日時
     */
    function date2yearmonth($date, $splitter = '-')
    {
        $splitted = preg_split('/[\/\: \-]/', $date);
        if (count($splitted) >= 2 && checkdate($splitted[1], 1, $splitted[0])) {
            return sprintf("%04d$splitter%02d", $splitted[0], $splitted[1]);
        } else if (preg_match('/^(\d{4})(\d{2})(\d{2})$/', $date, $matches)) {
            /** @var String $all */
            list($all, $year, $month, $day) = $matches;
            if (checkdate($month, $day, $year)) {
                return sprintf("%04d$splitter%02d", $year, $month);
            }
            return "";
        } else if (preg_match('/^(\d{4})(\d{2})$/', $date, $matches)) {
            list($all, $year, $month) = $matches;
            if (checkdate($month, 1, $year)) {
                return sprintf("%04d$splitter%02d", $year, $month);
            }
            return "";
        } else {
            return "";
        }
    }
}

if (!function_exists('date2year')) {
    /**
     * 指定した日付からYを取得する
     * @param    String $time タイムスタンプ
     * @return    Integer        $date        日時
     */
    function date2year($date, $splitter = '-')
    {
        $splitted = preg_split('/[\/\: \-]/', $date);
        if (count($splitted) > 0) {
            return sprintf("%04d", $splitted[0]);
        } else {
            return "";
        }
    }
}

if (!function_exists('date2month')) {
    /**
     * 指定した日付からYを取得する
     * @param    String $date タイムスタンプ
     * @return    Integer        $date        日時
     */
    function date2month($date, $splitter = '-')
    {
        $splitted = preg_split('/[\/\: \-]/', $date);
        if (count($splitted) > 1) {
            return sprintf("%02d", $splitted[1]);
        } else {
            return "";
        }
    }
}

if (!function_exists('datetime2hour')) {
    /**
     * 指定した日付からYを取得する
     * @param    String $time タイムスタンプ
     * @return    Integer        $date        日時
     */
    function datetime2hour($datetime, $splitter = '-')
    {
        $splitted = preg_split('/[\/\: \-]/', $datetime);
        if (count($splitted) == 6 && preg_match('/^[0-9]+([\/: -][0-9]+)+$/', $datetime)) {
            list($year, $month, $day, $hour, $min, $sec) = $splitted;
            if (checkdate($month, $day, $year)) {
                return $hour;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }
}

if (!function_exists('datetime2min')) {
    /**
     * 指定した日付からYを取得する
     * @param    String $datetime タイムスタンプ
     * @return    Integer        $date        日時
     */
    function datetime2min($datetime, $splitter = '-')
    {
        $splitted = preg_split('/[\/\: \-]/', $datetime);
        if (count($splitted) == 6 && preg_match('/^[0-9]+([\/: -][0-9]+)+$/', $datetime)) {
            list($year, $month, $day, $hour, $min, $sec) = $splitted;
            if (checkdate($month, $day, $year)) {
                return $min;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }
}



// --------------------------------------------------------------------

/**
 */
if (!function_exists('convert_str2time')) {
    function convert_str2time($datetime)
    {
        $splitted = preg_split('/[\/\: \-]/', $datetime);
        $result = time();
        if (count($splitted) == 6 && preg_match('/^[0-9]+([\/: -][0-9]+)+$/', $datetime)) {
            list($year, $month, $day, $hour, $min, $sec) = $splitted;
            if (checkdate($month, $day, $year)) {
                $result = mktime($hour, $min, $sec, $month, $day, min(2038, $year));
            }
        } else if (count($splitted) == 3 && preg_match('/^[0-9]+([:][0-9]+)+$/', $datetime)) {
            list($hour, $min, $sec) = $splitted;
            $result = mktime($hour, $min, $sec, 1, 1, 1970);
        }
        return $result;
    }
}

/**
 */
if (!function_exists('convert_time2str')) {
    function convert_time2str($time)
    {
        $datetime = date_timestamp_set(date_create(), $time);
        $offset = timezone_offset_get(date_timezone_get($datetime), $datetime);
        return date("H:i:s", $time - $offset);
    }
}


/**
 */
if (!function_exists('datetime2time')) {
    function datetime2time($datetime)
    {
        $splitted = preg_split('/[\/\: \-]/', $datetime);
        if (count($splitted) == 6 && preg_match('/^[0-9]+([\/: -][0-9]+)+$/', $datetime)) {
            list($year, $month, $day, $hour, $min, $sec) = $splitted;
            if (checkdate($month, $day, $year)) {
                return mktime($hour, $min, $sec, $month, $day, min(2038, $year));
            } else {
                return time();
            }
        } else {
            return time();
        }
    }
}

// --------------------------------------------------------------------

/**
 */
if (!function_exists('is_valid_date')) {
    function is_valid_date($date)
    {
        $splitted = preg_split('/[\/\-]/', $date);
        if (count($splitted) != 3 || !preg_match('/^[0-9]+([\/\-][0-9]+)+$/', $date)) {
            return false;
        }
        list($year, $month, $day) = $splitted;
        return checkdate($month, $day, $year);
    }
}

/**
 */
if (!function_exists('datetime2date')) {
    function datetime2date($datetime, $splitter = '-')
    {
        $splitted = preg_split('/[\/\: \-]/', $datetime);
        if (count($splitted) == 6 && preg_match('/^[0-9]+([\/: -][0-9]+)+$/', $datetime)) {
            list($year, $month, $day, $hour, $min, $sec) = $splitted;
            if (checkdate($month, $day, $year)) {
                return sprintf("%04d$splitter%02d$splitter%02d", min(2038, $year), $month, $day);
            } else {
                return sprintf("%04d$splitter%02d$splitter%02d", $year, $month, $day);
            }
        } else {
            return $datetime;
        }
    }
}

if (!function_exists('datetime2hourmin')) {
    /**
     * 指定した日付からYを取得する
     * @param    String $datetime タイムスタンプ
     * @return    Integer        $date        日時
     */
    function datetime2hourmin($datetime, $splitter = '-')
    {
        $splitted = preg_split('/[\/\: \-]/', $datetime);
        $result = "00:00";
        if (count($splitted) == 6 && preg_match('/^[0-9]+([\/: -][0-9]+)+$/', $datetime)) {
            list($year, $month, $day, $hour, $min, $sec) = $splitted;
            if (checkdate($month, $day, $year)) {
                $result = $hour.":".$min;
            }
        } else if (count($splitted) == 3 && preg_match('/^[0-9]+([:][0-9]+)+$/', $datetime)) {
            list($hour, $min, $sec) = $splitted;
            $result = $hour.":".$min;
        }
        return $result;
    }
}

if (!function_exists('datetime2minsec')) {
    /**
     * 指定した日付からYを取得する
     * @param    String $datetime タイムスタンプ
     * @return    Integer        $date        日時
     */
    function datetime2minsec($datetime, $splitter = '-')
    {
        $splitted = preg_split('/[\/\: \-]/', $datetime);
        $result = "00:00";
        if (count($splitted) == 6 && preg_match('/^[0-9]+([\/: -][0-9]+)+$/', $datetime)) {
            list($year, $month, $day, $hour, $min, $sec) = $splitted;
            if (checkdate($month, $day, $year)) {
                $result = $min.":".$sec;
            }
        } else if (count($splitted) == 3 && preg_match('/^[0-9]+([:][0-9]+)+$/', $datetime)) {
            list($hour, $min, $sec) = $splitted;
            $result = $min.":".$sec;
        }
        return $result;
    }
}




// --------------------------------------------------------------------

/**
 */
if (!function_exists('parse_date')) {
    function parse_date($date, $splitter = '/[\/\-_\s]/')
    {
        if (preg_match('/^\d{8}$/', $date)) {
            $yy = substr($date, 0, 4);
            $mm = substr($date, 4, 2);
            $dd = substr($date, 6, 2);
        } else if ($date == '') {
            return false;
        } else {
            $recordset = preg_split($splitter, $date);
            if (count($recordset) >= 3) {
                list($yy, $mm, $dd) = $recordset;
            } else {
                return false;
            }
        }
        return array($yy, $mm, $dd);
    }
}
// --------------------------------------------------------------------

/**
 */
if (!function_exists('format_date')) {
    function format_date($date, $format, $nullDate = "")
    {
        if (is_null($date)) {
            return $nullDate;
        }
        if (!is_array($date)) {
            $date = preg_split('/[\/\: \-]/', $date);
        }
        $yy = 9999;
        $mm = 12;
        $dd = 31;
        if (count($date) > 2) {
            list($yy, $mm, $dd) = $date;
        }
        if ($yy == 9999 && $mm == 12 && $dd == 31) {
            return $nullDate;
        }
        return vsprintf($format, $date);
    }
}
// --------------------------------------------------------------------

/**
 */
if (!function_exists('date_to_age')) {
    function date_to_age($date, $splitter = '/[\/\-_\s]/')
    {
        if ($date == '') {
            return '';
        }
        list($yy, $mm, $dd) = parse_date($date, $splitter);
        $age = (int)((date('Ymd') - sprintf("%04d%02d%02d", $yy, $mm, $dd)) / 10000);
        return $age;
    }
}
// --------------------------------------------------------------------

/**
 */
if (!function_exists('injection_date_converter')) {
    function injection_date_converter($date, $format1, $format2)
    {
        $splitted = preg_split('/[\/\: \-]/', $date);
        if (count($splitted) == 3) {
            if ($splitted[2] == '96') {
                return sprintf("%s上旬", date($format2, date2time(sprintf("%04d-%02d-01", $splitted[0], $splitted[1]))));
            } else if ($splitted[2] == '97') {
                return sprintf("%s中旬", date($format2, date2time(sprintf("%04d-%02d-01", $splitted[0], $splitted[1]))));
            } else if ($splitted[2] == '98') {
                return sprintf("%s下旬", date($format2, date2time(sprintf("%04d-%02d-01", $splitted[0], $splitted[1]))));
            } else if ($splitted[2] >= 1 && $splitted[2] <= 31) {
                return date($format1, date2time(sprintf("%04d-%02d-%02d", $splitted[0], $splitted[1], $splitted[2])));
            } else {
                return sprintf("%s未定", date($format2, date2time(sprintf("%04d-%02d-01", $splitted[0], $splitted[1]))));
            }
        } else if (count($splitted) == 2) {
            return sprintf("%s中", date($format2, date2time(sprintf("%04d-%02d-01", $splitted[0], $splitted[1]))));
        }
        return "ERROR";
    }
}

// --------------------------------------------------------------------

/**
 */
if (!function_exists('date2astro')) {
    function date2astro($datetime)
    {
        $splitted = preg_split('/[\/\: \-]/', $datetime);
        if (preg_match('/^[0-9]+([\/: -][0-9]+)+$/', $datetime)) {
            list($year, $month, $day, $hour, $min, $sec) = $splitted;
            if (checkdate($month, $day, $year)) {
                $t = sprintf("%02d%02d", $month, $day);
                if ($t >= "0321" && $t <= "0419") {
                    return "おひつじ座";
                } else if ($t >= "0420" && $t <= "0520") {
                    return "おうし座";
                } else if ($t >= "0521" && $t <= "0621") {
                    return "ふたご座";
                } else if ($t >= "0622" && $t <= "0722") {
                    return "かに座";
                } else if ($t >= "0723" && $t <= "0822") {
                    return "しし座";
                } else if ($t >= "0823" && $t <= "0922") {
                    return "おとめ座";
                } else if ($t >= "0923" && $t <= "1023") {
                    return "てんびん座";
                } else if ($t >= "1024" && $t <= "1121") {
                    return "さそり座";
                } else if ($t >= "1122" && $t <= "1221") {
                    return "いて座";
                } else if (($t >= "1222" && $t <= "1231") || ($t >= "0101" && $t <= "0119")) {
                    return "やぎ座";
                } else if ($t >= "0120" && $t <= "0218") {
                    return "みずがめ座";
                } else if ($t >= "0219" && $t <= "0220") {
                    return "うお座";
                }
            }
        }
        return "";
    }
}

// --------------------------------------------------------------------

/**
 */
if (!function_exists('time2youbi')) {
    function time2youbi($time)
    {
        return strftime("%w", $time);
    }
}


/**
 * 日時を区切り文字区切りにする
 */
if (!function_exists('splitDateFormat')) {
    function splitDateFormat($text, $dateSplitter = '/', $timeSplitter = ':') {
        $temp = simplifyDateFormat($text);
        if (preg_match('/^(\d{4})(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})$/', $temp, $matches)) {
            array_shift($matches);
            return vsprintf("%s$dateSplitter%s$dateSplitter%s %s$timeSplitter%s$timeSplitter%s", $matches);
        } else if (preg_match('/^(\d{4})(\d{2})(\d{2})$/', $temp, $matches)) {
            array_shift($matches);
            return vsprintf("%s$dateSplitter%s$dateSplitter%s", $matches);
        } else if (preg_match('/^(\d{2})(\d{2})(\d{2})$/', $temp, $matches)) {
            array_shift($matches);
            return vsprintf("%s$timeSplitter%s$timeSplitter%s", $matches);
        }
        return $text;
    }
}


/* End of file dateutil_helper.php */
/* Location: ./application/helpers/dateutil_helper.php */