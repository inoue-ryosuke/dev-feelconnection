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

        //ログイン認証情報から関連情報を取得する
        $custinfo = auth("customer")->user();
        if (!$custinfo) {
            throw new BadRequestException(); // Hander経由レスポンスの場合
        }
        $response = [
            "result_code" => 0,
            "name" => $custinfo->name,    
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
            "memtype_name" => $custinfo->getMemTypeName(),  //"マンスリーメンバー",        // ここで会員種別変更があれば変更あり文言追加
            "store_name" => $custinfo->getStoreNames(), //"銀座（GNZ）、自由が丘（JYO）",　// ここで所属店舗変更があれば変更あり文言追加
            "dm_list" => $custinfo->getDmLists(),       //"1,,,,",
            "pc_conf" => $custinfo->getPcConf(),        // 1,
            "gmo_credit" => $custinfo->getGmoId(),       // "XXXXXXXXXXX",
            "pref" => PrefMaster::getPref("JPN") ?? []  // JSONレスポンス型の事を考えて、nullの時は空配列
        ];
        return $response;
    }

    public static function getUserInfo($cid) {

        if (!$cid) {
            throw new BadRequestException(); // Hander経由レスポンスの場合
        }
        $custinfo = Cust::getUserInfoById($cid);
        if (!$custinfo) {
            throw new BadRequestException(); // Hander経由レスポンスの場合
        }
        $response = [
            "result_code" => 0,
            "name" => $custinfo->name,    
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
            "memtype_name" => $custinfo->getMemTypeName(),  //"マンスリーメンバー",        // ここで会員種別変更があれば変更あり文言追加
            "store_name" => $custinfo->getStoreNames(), //"銀座（GNZ）、自由が丘（JYO）",　// ここで所属店舗変更があれば変更あり文言追加
            "dm_list" => $custinfo->getDmLists(),       //"1,,,,",
            "pc_conf" => $custinfo->getPcConf(),        // 1,
            "campaign_list" => $custinfo->getCampaignList(),    // TBD:現在スタブ
            "gmo_credit" => $custinfo->getGmoId(),       // "XXXXXXXXXXX",
            "pref" => PrefMaster::getPref("JPN") ?? []  // JSONレスポンス型の事を考えて、nullの時は空配列
        ];        
        return $response;
    }
}