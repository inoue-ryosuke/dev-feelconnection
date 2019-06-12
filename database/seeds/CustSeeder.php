<?php

use Illuminate\Database\Seeder;
use App\Models\BaseFormModel;
use App\Http\Requests\Request;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Cust as Cust;
use App\Models\CustMemType as CustMemType;

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
            foreach ($this->getConfig() as $records) {
                $this->insertRecord($records);
            }
        });
        echo "\n" . "CustSeeder Finished" . "\n";
    }
    /**
     * 登録単位でCUSTに関係する指定キーのみ登録
     */
    protected function insertRecord($recordset = []) {

        // 
        if (isset($recordset["cust_master"]) && isset($recordset["cust_memtype"])) {

            //echo "\n   ---> " . "Cust Insert Start" . "\n";
            $dao = new Cust();
            $dao->mergeRequest($recordset["cust_master"]);
            $dao->save();
            echo "\n   ---> " . "Cust Insert End [".$dao->name."]" . "\n";

            //echo "\n   ---> " . "CustMemType Insert Start" . "\n";
            $memdao = new CustMemType();
            $memdao->mergeRequest($recordset["cust_memtype"]);
            $memdao->save();
            echo "\n   ---> " . "CustMemType Insert End [".$memdao->type_name."]" . "\n";

            // Cust と CustMemTypeを紐づけ
            $dao->memtype = $memdao->mid;
            $dao->save();
            echo "\n" . "Lincked CustMemId [".$memdao->mid."] == [".$dao->memtype."]" . "\n";
        }

    }

}
