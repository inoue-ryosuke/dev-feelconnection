<?php

/**
 * ファイルを読み込む
 * @param $filename ファイル名
 * @return string 文字列
 */
function load_file( $filename ) {
    $ret = false;
    // ファイル名がない場合にはfalseを返す
    if (!file_exists($filename)) {
        return $ret;
    }

    $fp = fopen($filename,'rb');
    while(!feof($fp)){
        $line = fgets($fp, 4096);
        $ret .= $line;
    }
    fclose($fp);
    return $ret;
}

/**
 * ファイルを保存する
 * @param $filename
 * @param $data
 * @return int
 */
function save_file( $filename, $data ) {
    $fp = fopen($filename,'wb');
    fwrite($fp, $data);
    fclose($fp);
    return filesize($filename);
}

/**
 * ファイルを削除する
 * @param $filename ファイル名
 * @return bool
 */
function remove_file($filename) {
    $ret = false;
    // ファイル名がない場合にはfalseを返す
    if (!file_exists($filename)) {
        return $ret;
    }
    return unlink($filename);
}

/**
 * ディレクトリを作成します。
 * @param $dir ディレクトリ名
 * @return なし
 */
if (!function_exists('make_dir')) {
    function make_dir($dir) {
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
    }
}

/**
 * コマンドを実行する
 * @param 	String		$filename		ファイル名
 * @return 	Array		$ret			取得したデータ一覧
 */
if (!function_exists('execute_command')) {
    function execute_command( $command ) {
        $command .= ' 2>&1';

//        log_message('debug', 'CMD: '.$command);
        $fh = popen($command, 'r');
        $result = array();
        while (!feof($fh)) {
            $line = fread($fh, 1024);
            $result[] = $line;
        }
        pclose($fh);
//        log_message('debug', 'RESULT: '.join('', $result));
        return $result;
    }
}

/**
 * バイナリーファイルかどうかチェック
 */
if (!function_exists('is_binary')) {
    function is_binary($file)
    {
        $fp = fopen($file, 'r');
        while ($line = fgets($fp)) {
            if (strpos($line, '\0') !== false) {
                fclose($fp);
                return true;
            }
        }

        fclose($fp);
        return false;
    }
}

/**
 * ファイル出力メソッド
 * @param $path
 * @param int $chunkSize
 * @return bool
 */
if (!function_exists('read_stream')) {
    function read_stream($path, $chunkSize = 8192)
    {
        $handle = fopen($path, "rb");
        while (!feof($handle)) {
            echo fread($handle, $chunkSize);
            ob_flush();
            flush();
        }
        return fclose($handle);
    }
}
/**
 * ファイルの書き込み状態を見てストリーミングで書き込む処理
 * @param $fp ファイルポインタ
 * @param $string 書き込む内容
 * @return int 書き込みバイト数
 */
function fwrite_stream($fp, $string) {
    $length = strlen($string);
    if ($length <= 0) {
        return 0;
    }
    for ($written = 0; $written < $length; $written += $fwrite) {
        $fwrite = fwrite($fp, substr($string, $written));
        if ($fwrite === false) {
            return $written;
        }
    }
    return $written;
}

//PNG	89 50 4E 47	臼NG
//GIF(89a)	47 49 46 38 39 61	GIF89a
//GIF(87a)	47 49 46 38 37 61	GIF87a
//JPEG	FF D8 FF	.ﾘ.
/**
 * バイナリーのヘッダー情報からMIME-TYPEを返す
 * @param $header ヘッダー情報
 * @return string 文字列
 */
function check_mime_type($header) {
    if (strcmp("\x89\x50\x4e\x47", substr($header, 0, 4))) {
        return "image/png";
    } else if (strcmp("\x47\x49\x46\x38\x39\x61", substr($header, 0, 6))) {
        return "image/gif";
    } else if (strcmp("\x47\x49\x46\x38\x37\x61", substr($header, 0, 6))) {
        return "image/gif";
    } else if (strcmp("\xff\xd8\xff", substr($header, 0, 3))) {
        return "image/jpeg";
    }
    return "application/octet-stream";
}

/**
 * byte表記を追加する
 * @param value 値(byte)
 * @param division 1024で割る回数(PBまで対応)
 * @return string 文字列
 */
function convertByteUnit($value, $division){
    $units = array('KB', 'MB', 'GB', 'TB', 'PB');
    $retVal = ceil(($value / pow(1024, $division)));
    $retVal .= $units[$division - 1];
    return $retVal;
}
