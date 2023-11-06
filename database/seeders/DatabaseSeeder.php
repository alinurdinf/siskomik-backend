<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        try {
            DB::beginTransaction();
            $this->call(StarterSeeder::class);
            $this->call(ProdiSeeder::class);

            DB::commit();
        } catch (\Exception $e) {
            report($e);
            DB::rollBack();
            printf("\tan error occured.\n\t %s %s %s\n", $e->getMessage(), $e->getLine(), $e->getFile());
        }
    }
}
