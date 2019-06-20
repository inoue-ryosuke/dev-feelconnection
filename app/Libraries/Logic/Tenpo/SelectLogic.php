<?php

namespace App\Libraries\Logic\Tenpo;

use App\Exceptions\BadRequestException;
use App\Models\TenpoMaster;
use App\Models\PrefMaster;
/**
 * 店舗情報取得（利用権限：認証済アクセスのみ）
 */
class SelectLogic
{
    /**
     * 
     */
    public static function getTenpoInfo($tid) {
        if (!$tid) {
            throw new BadRequestException(); // Hander経由レスポンスの場合
        }
        $tenpoinfo = TenpoMaster::getTenpoInfoById($tid);
        if (!$tenpoinfo) {
            throw new BadRequestException(); // Hander経由レスポンスの場合
        }
        $response = [
            "result_code"     => 0,
            "tenpo_name"      => $tenpoinfo->{$tenpoinfo->cKey("tenpo_name")},
            "near_station"    => $tenpoinfo->getStationName(),
            "lat"             => $tenpoinfo->{$tenpoinfo->cKey("lat")},
            "lng"             => $tenpoinfo->{$tenpoinfo->cKey("lng")},
            "address"         => $tenpoinfo->{$tenpoinfo->cKey("address")},
            "timetable"       => $tenpoinfo->{$tenpoinfo->cKey("timetable")},
            "tenpo_filename"  => $tenpoinfo->getTenpoImageFileInfo(),
            "option_filename" => $tenpoinfo->getTenpoOptionImageFileInfo()
        ];        
        return $response;
    }
}