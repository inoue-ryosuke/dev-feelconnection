<?php

namespace App\Libraries\Logic\Authentication;

use app\Models\Cust;

/**
 * 認証で使用するバリデーション
 *
 */
class DataProviderLogic
{
    /**
     * 
     */
    public static function getAuthInfo($cid) {

        $request->get("cid");
        $custinfo = Cust::getAuthInfo($cid);
        if (is_null($custinfo)) {
            throw new BadRequestException(); // Hander経由レスポンスの場合
        }

        $response = [
            "result_code" => 0,
            "name" => $custinfo->getNameInfo(),
            "kana" => $custinfo->kana,
            "pc_mail" => $custinfo->pc_mail,
            "b_birthday" => $custinfo->getBirthDayWord("jp"), //"昭和55年1月1日",
            "b_year" => $custinfo->b_year,
            "b_month" => $custinfo->b_month,
            "b_day" => $custinfo->b_day,
            "sex" => $custinfo->sex,
            "h_zip" => $custinfo->h_zip,
            "h_pref" => $custinfo->h_addr1,
            "h_addr" => $custinfo->h_addr2,
            "h_tel" => $custinfo->getTelNo(),
            "memtype_name" => $custinfo->getMemType(),  //"マンスリーメンバー",
            "store_name" => $custinfo->getStoreNames(), //"銀座（GNZ）、自由が丘（JYO）",
            "dm_list" => $custinfo->getDmLists(),       //"1,,,,",
            "pc_conf" => $custinfo->getPcConf(),        // 1,
            "gmo_credit" => $custinfo->getGmoId()       // "XXXXXXXXXXX",
        ];
        return $response;
    }
}