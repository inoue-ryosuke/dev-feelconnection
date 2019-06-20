<?php

use Illuminate\Database\Seeder;
use App\Models\BaseFormModel;
use App\Http\Requests\Request;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\ZipCode;

class ZipCodeSeeder extends BaseSeeder
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
        echo "\n" . "ZipCodeSeeder Start" . "\n";
        DB::transaction(function() {
            // 登録単位ループ
            foreach ($this->getConfig("zip_code") as $records) {
                $this->insertRecord($records);
            }
        });
        echo "\n" . "ZipCodeSeeder Finished" . "\n";
    }

    protected function insertRecord($record = []) {

            $zipCode = new ZipCode();
            $zipCode->code = $record[0];
            $zipCode->address1 = $record[1];
            $zipCode->address2 = $record[2];
            $zipCode->address3 = $record[3];
            $zipCode->save();

    }

}
