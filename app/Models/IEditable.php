<?php
/**
 * Created by PhpStorm.
 * User: 健夫
 * Date: 2015/07/18
 * Time: 13:07
 */
namespace App\Models;

/**
 * 編集可能インターフェイス
 * 管理画面で編集可能なオブジェクトはこれを実装する。
 * Interface IEditable
 * @package App
 */
interface IEditable
{
    /**
     * 入力リクエストをオブジェクトにマージする
     * @param $request 入力リクエスト値
     */
    function mergeRequest($request);
}