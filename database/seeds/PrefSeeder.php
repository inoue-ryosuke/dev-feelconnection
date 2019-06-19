<?php

use Illuminate\Database\Seeder;
use App\Models\BaseFormModel;
use App\Http\Requests\Request;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\PrefMaster as PrefMaster;

class PrefSeeder extends BaseSeeder
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
        echo "\n" . "PrefSeeder Start" . "\n";
        DB::transaction(function() {
            // 登録単位ループ
            foreach ($this->getConfig("pref_master") as $records) {
                $this->insertRecord($records);
            }
        });
        echo "\n" . "PrefSeeder Finished" . "\n";
    }

    protected function insertRecord($record = []) {

            $pref = new PrefMaster;
            $pref->mergeRequest($record);
            $pref->save();

    }

}
