<?php

use Illuminate\Database\Seeder;

abstract class BaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @param $target
     * @return void
     */
    public function getConfig($target=null)
    {
        if (config('app.env') === 'production') {
            $config = require(base_path('database/seeds/default_production.php'));
            return array_get($config, $target);
        }
        $config = require(base_path('database/seeds/default.php'));
        return array_get($config, $target);
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
