<?php

namespace App\Libraries\Logic\Authentication;

use App\Exceptions\BadRequestException;
use App\Models\Cust;
use App\Models\PrefMaster;
/**
 * 認証で使用するバリデーション
 *
 */
class SelectLogic
{
    /**
     * 
     */
    public static function getAuthInfo() {


        // TBD:認証情報からCustを特定する
        $cid = Cust::first()->cid;
        $custinfo = Cust::getAuthInfo($cid);
        if (!$custinfo) {
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
            "memtype_name" => $custinfo->getMemTypeName(),  //"マンスリーメンバー",
            "store_name" => $custinfo->getStoreNames(), //"銀座（GNZ）、自由が丘（JYO）",
            "dm_list" => $custinfo->getDmLists(),       //"1,,,,",
            "pc_conf" => $custinfo->getPcConf(),        // 1,
            "gmo_credit" => $custinfo->getGmoId(),       // "XXXXXXXXXXX",
            "pref" => PrefMaster::getPref("JPN") ?? []  // JSONレスポンス型の事を考えて、nullの時は空配列
        ];
        return $response;
    }

    public static function getUserMoreInfo($cid) {
        // TBD:認証情報からCustを特定する
        if (!$cid) {
            throw new BadRequestException(); // Hander経由レスポンスの場合
        }
        $cid = Cust::first()->cid;
        $custinfo = Cust::getAuthInfo($cid);
        if (!$custinfo) {
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
            "memtype_name" => $custinfo->getMemTypeName(),  //"マンスリーメンバー",
            "store_name" => $custinfo->getStoreNames(), //"銀座（GNZ）、自由が丘（JYO）",
            "dm_list" => $custinfo->getDmLists(),       //"1,,,,",
            "pc_conf" => $custinfo->getPcConf(),        // 1,
            "campaign_list" => $custinfo->getCampaignList(),    // TBD:現在スタブ
            "gmo_credit" => $custinfo->getGmoId(),       // "XXXXXXXXXXX",
            "pref" => PrefMaster::getPref("JPN") ?? []  // JSONレスポンス型の事を考えて、nullの時は空配列
        ];        
        return $response;
    }
}