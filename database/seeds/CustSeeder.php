<?php

use Illuminate\Database\Seeder;
use App\Models\BaseFormModel;
use App\Http\Requests\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;
use App\Models\Cust as Cust;
use App\Models\CustMemType as CustMemType;
use App\Models\TenpoMaster as TenpoMaster;

class CustSeeder extends BaseSeeder
{

    public function __construct()
    {
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 削除
        //DB::statement('TRUNCATE cust_master CASCADE');

        echo "\n" . "CustSeeder Start" . "\n";
        DB::transaction(function() {
            // 登録単位ループ
            foreach ($this->getConfig("cust_master") as $records) {
                $this->insertRecord($records);
            }
        });
        echo "\n" . "CustSeeder Finished" . "\n";
    }
    /**
     * 登録単位でCUSTに関係する指定キーのみ登録
     */
    protected function insertRecord($record = []) {

            $memdao = $tenpo = null;
            // メインModel用の関連情報を取り除く
            if (isset($record["assign"])) {
                $assign = $record["assign"];
                unset($record["assign"]);
            }
            // 関連情報に店舗名がある場合、店舗を特定し紐づけ
            if (isset($assign["tenpo_name"])) {
                $tenpo = TenpoMaster::where("tenpo_name",$assign["tenpo_name"])->first();
            }
            // 関連情報に会員種別がある場合、登録
            if (isset($assign["cust_memtype"])) {
                $memdao = new CustMemType();
                $memdao->mergeRequest($assign["cust_memtype"]);

                $memdao->save();
                echo "\n   ---> " . "CustMemType Insert End [".$memdao->type_name."]" . "\n";
            }
            // 
            $dao = new Cust();
//        $record['password'] = Hash::make('password');
            $dao->mergeRequest($record);
//            $memdao->password = Hash::make('password');
            $dao->save();
            echo "\n   ---> " . "Cust Insert End [".$dao->name."]" . "\n";
            if ($memdao) {
                // Cust と CustMemTypeを紐づけ
                $dao->memtype = $memdao->mid;
                $dao->save();
                echo "\n" . "Linked CustMemId [".$dao->memtype."]" . "\n";
            }
            // 所属店舗情報の紐づけ
            if ($tenpo) {
                $dao->store_id = $tenpo->tid;
                $dao->save();
                echo "\n" . "Linked StoreId [".$dao->store_id."]" . "\n";
            }

    }

}
