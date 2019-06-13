<?php

use Illuminate\Database\Seeder;
use App\Models\BaseFormModel;
use App\Http\Requests\Request;
use Illuminate\Database\Eloquent\SoftDeletes;

class TruncateSeeder extends BaseSeeder
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
        // テーブルは残し、内容を消す
        echo "\n" . "TruncateSeeder Start" . "\n";

        // 登録単位ループ
        //var_dump($this->getConfig("truncate")); exit;
        foreach ($this->getConfig("truncate") as $tablename) {
            DB::statement('TRUNCATE '.$tablename.' CASCADE');
            echo "\n\t\t---> ".$tablename. "\n";
        }
        echo "\n" . "TruncateSeeder Finished" . "\n";
    }

}
