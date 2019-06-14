<?php

use Illuminate\Database\Seeder;
use App\Models\BaseFormModel;
use App\Http\Requests\Request;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Cust as Cust;
use App\Models\CustMemType as CustMemType;
use App\Models\TenpoMaster as TenpoMaster;
use App\Models\CustTenpo as CustTenpo;


class CustMemTypeSeeder extends BaseSeeder
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

        echo "\n" . "CustMemTypeSeeder Start" . "\n";
        DB::transaction(function() {
            // 登録単位ループ
            foreach ($this->getConfig("cust_memtype") as $records) {
                $this->insertRecord($records);
            }
        });
        echo "\n" . "CustMemTypeSeeder Finished" . "\n";
    }
    /**
     * 登録単位でCUSTに関係する指定キーのみ登録
     */
    protected function insertRecord($record = []) {

            $memdao = $tenpo = null;
            $tenpoModels = collect();
            $memdao = new CustMemType();
            $memdao->mergeRequest($record);
            $memdao->save();
            echo "\n   ---> " . "CustMemType Insert End [".$memdao->type_name."]" . "\n";
    }

}
