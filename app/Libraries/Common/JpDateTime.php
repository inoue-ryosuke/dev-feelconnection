<?php namespace App\Libraries\Common;

class JpDateTime {
    /** 元号用設定
     * 日付はウィキペディアを参照しました
     * http://ja.wikipedia.org/wiki/%E5%85%83%E5%8F%B7%E4%B8%80%E8%A6%A7_%28%E6%97%A5%E6%9C%AC%29
     */
    private static $gengoList = [
        ['name' => '令和', 'name_short' => 'R', 'timestamp' =>  1556636400],  // 2019-05-01,
        ['name' => '平成', 'name_short' => 'H', 'timestamp' =>  600188400],  // 1989-01-08,
        ['name' => '昭和', 'name_short' => 'S', 'timestamp' => -1357635600], // 1926-12-25'
        ['name' => '大正', 'name_short' => 'T', 'timestamp' => -1812186000], // 1912-07-30
        ['name' => '明治', 'name_short' => 'M', 'timestamp' => -3216790800], // 1868-01-25
    ];

    /** 日本語曜日設定 */
    private static $weekJp = [
        0 => '日',
        1 => '月',
        2 => '火',
        3 => '水',
        4 => '木',
        5 => '金',
        6 => '土',
    ];

    /** 午前午後 */
    private static $ampm = [
        'am' => '午前',
        'pm' => '午後',
    ];

    /**
     * 和暦などを追加したdate関数
     *
     * 追加した記号
     * J : 元号
     * b : 元号略称
     * K : 和暦年(1年を元年と表記)
     * k : 和暦年
     * x : 日本語曜日(0:日-6:土)
     * E : 午前午後
     */
    public static function date($format, $timestamp = null)
    {
        // 和暦関連のオプションがある場合は和暦取得
        $gengo = array();
        $timestamp = is_null($timestamp) ? time() : $timestamp;
        if (preg_match('/[J|b|K|k]/', $format)) {
            foreach (self::$gengoList as $g) {
                if ($g['timestamp'] <= $timestamp) {
                    $gengo = $g;
                    break;
                }
            }
            // 元号が取得できない場合はException
            if (empty($gengo)) {
                throw new Exception('Can not be converted to a timestamp : '.$timestamp);
            }
        }

        // J : 元号
        if (strpos($format, 'J') !== false) {
            $format = preg_replace('/J/', $gengo['name'], $format);
        }

        // b : 元号略称
        if (strpos($format, 'b') !== false) {
            $format = preg_replace('/b/', '¥¥' . $gengo['name_short'], $format);
        }

        // K : 和暦用年(元年表示)
        if (strpos($format, 'K') !== false) {
            $year = date('Y', $timestamp) - date('Y', $gengo['timestamp']) + 1;
            $year = $year == 1 ? '元' : $year;
            $format = preg_replace('/K/', $year, $format);
        }

        // k : 和暦用年
        if (strpos($format, 'k') !== false) {
            $year = date('Y', $timestamp) - date('Y', $gengo['timestamp']) + 1;
            $format = preg_replace('/k/', $year, $format);
        }

        // x : 日本語曜日
        if (strpos($format, 'x') !== false) {
            $w = date('w', $timestamp);
            $format = preg_replace('/x/', self::$weekJp[$w], $format);
        }

        // 午前午後
        if (strpos($format, 'E') !== false) {
            $a = date('a', $timestamp);
            $format = preg_replace('/E/', self::$ampm[$a], $format);
        }

        // 時。12時間単位。先頭にゼロを付けない。(0-11)
        if (strpos($format, 'p') !== false) {
            $hour = date('g', $timestamp);
            $hour = $hour == 12 ? 0 : $hour;
            $format = preg_replace('/p/', $hour, $format);
        }

        // 時。数字。12 時間単位。(00-11)
        if (strpos($format, 'q') !== false) {
            $hour = date('h', $timestamp);
            $hour = str_pad($hour == 12 ? 0 : $hour, 2, '0');
            $format = preg_replace('/q/', $hour, $format);
        }

        return date($format, $timestamp);
    }

}