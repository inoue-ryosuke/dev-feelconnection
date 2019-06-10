<?php

use Illuminate\Database\Seeder;
use App\Models\BaseFormModel;
use App\Http\Requests\Request;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Cust;

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

        DB::transaction(function() {
            foreach ($this->getConfig() as $record) {
                $this->insertRecord($record);
            }
        });
        echo "\n" . "CustSeeder Finished" . "\n";
    }

    protected function insertRecord($record = null) {

        $dao = new Cust();
        $dao->mergeRequest($record);
        $dao->save();
        
    }

}
