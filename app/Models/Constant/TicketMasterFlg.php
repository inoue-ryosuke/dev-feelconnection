<?php

namespace App\Models\Constant;

/**
 * ticket_master.flgの値一覧
 */
class TicketMasterFlg
{
    /** 未使用 */
    const UNUSE = 'Y';
    /** 使用済み */
    const USED = 'N';

    protected static $data = [
        ['id' => self::UNUSE, 'name' => '未使用'],
        ['id' => self::USED, 'name' => '使用済み'],
    ];

    /**
     * 一覧取得
     */
    public static function getList()
    {
        return self::$data;
    }

    /**
     * 一覧取得(key,value形式)
     */
    public static function getListKV()
    {
        return array_column(self::$data, 'name', 'id');
    }

    /**
     * 名称取得
     *
     * @param int $id
     * @return NULL|mixed
     */
    public static function getName($id)
    {
        $array = self::getListKV();
        return isset($array[(int)$id]) ? $array[(int)$id] : NULL;
    }
}