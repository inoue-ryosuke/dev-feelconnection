<?php

use Illuminate\Database\Seeder;
use App\Models\BaseFormModel;
use App\Http\Requests\Request;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\TenpoMaster as TenpoMaster;
use App\Models\TenpoAreaMaster as TenpoAreaMaster;
use App\Models\TenpoKubun as TenpoKubun;

class TenpoSeeder extends BaseSeeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 削除
        //DB::statement('TRUNCATE cust_master CASCADE');

        echo "\n" . "TenpoSeeder Start" . "\n";
        DB::transaction(function() {
            // 登録単位ループ
            foreach ($this->getConfig("tenpo_master") as $records) {
                $this->insertRecord($records);
            }
        });
        echo "\n" . "TenpoSeeder Finished" . "\n";
    }
    /**
     * 登録単位でCUSTに関係する指定キーのみ登録
     */
    protected function insertRecord($record = []) {

            $tenpoMdl = new TenpoMaster();
            // メインModel用の関連情報を取り除く
            if (isset($record["assign"])) {
                $assign = $record["assign"];
                unset($record["assign"]);
            }
            // 関連情報に店舗名がある場合、店舗エリア情報もセットで登録
            if (isset($assign["tenpo_area_name"])) {
                $tenpoAreaMdl = new TenpoAreaMaster();
                $area = TenpoAreaMaster::where($tenpoAreaMdl->convertKey("name"),$assign["tenpo_area_name"])->first();
                if (!$area) {
                    $area = $tenpoAreaMdl;
                }
                $mergeParam = [
                    "name" => $assign["tenpo_area_name"],
                    "seq" => (TenpoAreaMaster::query()->count()+1),
                ];
                $area->mergeRequest($mergeParam);
                $area->save();
                $record["tenpo_area_id"] = $area->id;
            }
            // 関連情報に店舗区分名がある場合、店舗区分情報もセットで登録
            if (isset($assign["tenpo_kubun_name"])) {
                $tenpoKbnMdl = new TenpoKubun();
                $kbn = TenpoKubun::where($tenpoKbnMdl->convertKey("tk_name"),$assign["tenpo_kubun_name"])->first();
                if (!$kbn) {
                    $kbn = $tenpoKbnMdl;
                }
                $mergeParam = [
                    "tk_name" => $assign["tenpo_kubun_name"],
                ];
                $kbn->mergeRequest($mergeParam);
                $kbn->save();
                $tenpo_kubun_id = $kbn->tkid;
                $record["tenpo_kubun"] = $kbn->tkid;
            }

            //echo "\n   ---> " . "Cust Insert Start" . "\n";
            $dao = TenpoMaster::where($tenpoMdl->convertKey("tenpo_name"),$record["tenpo_name"])->first();
            if (is_null($dao)) {
                $dao = $tenpoMdl;
            }
            $dao->mergeRequest($record);
            $dao->save();
            var_dump($dao->tenpo_name);
            echo "\n   ---> " . "Tenpo Insert End [".$dao->tenpo_name."]" . "\n";

    }

}
