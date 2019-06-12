<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function() {

            // $this->call(UsersTableSeeder::class);
            //DB::statement('TRUNCATE sessions CASCADE');
            //$this->call(UserMasterSeeder::class);
            $this->call(TenpoSeeder::class);
            $this->call(CustSeeder::class);

        });
    }
}
