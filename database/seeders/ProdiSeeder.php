<?php

namespace Database\Seeders;

use App\Models\Prodi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProdiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prodi = [
            [
                'kprodi' => '202351',
                'name' => 'SIIO',
                'code' => 'SIIO',

            ],
            [
                'kprodi' => '202352',
                'name' => 'ABO',
                'code' => 'ABO',

            ],
            [
                'kprodi' => '202353',
                'name' => 'TIO',
                'code' => 'TIO',

            ],   [
                'kprodi' => '202354',
                'name' => 'TRO',
                'code' => 'TRO',

            ],   [
                'kprodi' => '202355',
                'name' => 'TKP',
                'code' => 'TKP',

            ]
        ];
        foreach ($prodi as $item) {
            Prodi::create($item);
        }
    }
}
