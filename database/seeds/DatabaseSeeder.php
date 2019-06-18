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
            $this->call(PrefSeeder::class);
            $this->call(CustMemTypeSeeder::class);
            $this->call(TenpoSeeder::class);
            $this->call(CustSeeder::class);
            $this->call(LessonClassSeeder::class);
            /**  LessonMasterSeederは、LessonClassSeederとCustMemTypeSeederが処理されている前提*/
            $this->call(LessonMasterSeeder::class);
            /** UserMasterSeederは、TenpoSeederが処理されている前提*/
            $this->call(UserMasterSeeder::class);
            /** UserMasterHistSeederは、UserMasterSeeder,TenpoSeederが処理されている前提*/
            $this->call(UserMasterHistSeeder::class);
            /** ShiftMasterSeederは、TenpoSeeder、UserMasterSeeder、CustSeeder、LessonMasterSeeder、UserMasterHistSeederが処理されている前提*/
            $this->call(ShiftMasterSeeder::class);
            /** InviteSeederは、CustSeeder、LessonMasterSeederが処理されている前提*/
            $this->call(InviteSeeder::class);

        });
    }
}
