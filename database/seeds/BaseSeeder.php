<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

abstract class BaseSeeder extends Seeder
{
    const ConfigFolderPath = "config/seeder";

    /**
     * config/seeder/(develop or other)配下のSeederファイルをconfigヘルパで取得する
     */
    public function getConfig($target=null) {
        if (config('app.env') === 'production') {
            if ($target) {
                return config("seeder.production.".$target);
            }
            return config("seeder.production");
        }
        if ($target) {
            return config("seeder.develop.".$target);
        }
        return config("seeder.develop");
    }

    /**
     * @param $filename
     * @return array
     */
    public function getCsv($filename)
    {
        $filepath = base_path('database/seeds/'.$filename);
        $file = new SplFileObject($filepath); 
        $file->setFlags(SplFileObject::READ_CSV);
        return $file;
    }
}
