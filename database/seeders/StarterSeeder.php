<?php

namespace Database\Seeders;

use App\Models\AppConfig;
use App\Models\Mahasiswa;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StarterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // SADMIN role
        $sadmin = Role::create([
            'name' => 'sadmin',
            'display_name' => 'PUSDATA',
            'description' => 'PUSDATA',
        ]);

        // ADMIN role
        $adminRole = Role::create([
            'name' => 'admin',
            'display_name' => 'AKADEMIK',
            'description' => 'AKADEMIK',
        ]);

        // Bagian Umum role
        $bagUmumRole = Role::create([
            'name' => 'bag-umum',
            'display_name' => 'BAGIAN UMUM',
            'description' => 'BAGIAN UMUM',
        ]);

        // Mahasiswa role
        $mahasiswaRole = Role::create([
            'name' => 'mahasiswa',
            'display_name' => 'MAHASISWA',
            'description' => 'MAHASISWA',
        ]);

        // KAPRODI role
        $kprodiRole = Role::create([
            'name' => 'kprodi',
            'display_name' => 'KAPRODI',
            'description' => 'KAPRODI',
        ]);

        // DOSEN role
        $dosenRole = Role::create([
            'name' => 'dosen',
            'display_name' => 'DOSEN',
            'description' => 'DOSEN',
        ]);

        // DIREKTUR role
        $direkturRole = Role::create([
            'name' => 'direktur',
            'display_name' => 'DIREKTUR',
            'description' => 'DIREKTUR',
        ]);

        // DOSEN role
        $pudir1Role = Role::create([
            'name' => 'pudir-1',
            'display_name' => 'PUDIR-1',
            'description' => 'PUDIR-1',
        ]);

        // Create PUSDATA User
        $sadmin = User::create([
            'identifier' => 20231,
            'username' => 'PUSDATA',
            'name' => 'PUSDATA',
            'profile_photo_path' => NULL,
            'email' => 'pusdata@stmi.ac.id',
            'email_verified_at' => now(),
            'password' => bcrypt('only4SIIO'),
            'remember_token' => Str::random(36),
        ]);
        $sadmin->syncRolesWithoutDetaching([$sadmin]);

        // Create AKADEMIK User
        $akademik = User::create([
            'identifier' => 20232,
            'username' => 'AKADEMIK',
            'name' => 'AKADEMIK-ACCOUNT',
            'profile_photo_path' => NULL,
            'email' => 'akademik@stmi.ac.id',
            'email_verified_at' => now(),
            'password' => bcrypt('only4SIIO'),
            'remember_token' => Str::random(36),
        ]);
        $akademik->syncRolesWithoutDetaching([$adminRole]);

        // Create AKADEMIK User
        $bagumum = User::create([
            'identifier' => 20233,
            'username' => 'BAGIAN-UMUM',
            'name' => 'HUMAS-ACCOUNT',
            'profile_photo_path' => NULL,
            'email' => 'humas@stmi.ac.id',
            'email_verified_at' => now(),
            'password' => bcrypt('only4SIIO'),
            'remember_token' => Str::random(36),
        ]);
        $bagumum->syncRolesWithoutDetaching([$bagUmumRole]);

        // Create MAHASISWA User
        $mahasiswa = User::create([
            'identifier' => 20234,
            'username' => 'NISA',
            'name' => 'NISA',
            'profile_photo_path' => NULL,
            'email' => 'nisa@stmi.ac.id',
            'email_verified_at' => now(),
            'password' => bcrypt('only4SIIO'),
            'remember_token' => Str::random(36),
        ]);
        $mahasiswa->syncRolesWithoutDetaching([$mahasiswaRole]);

        // Create KAPRODI User
        $kaprodiSiio = User::create([
            'identifier' => 202351,
            'username' => 'Lucky Heriyanto',
            'name' => 'Luck Heriyanto',
            'profile_photo_path' => NULL,
            'email' => 'kaprodi.siio@stmi.ac.id',
            'email_verified_at' => now(),
            'password' => bcrypt('only4SIIO'),
            'remember_token' => Str::random(36),
        ]);
        $kaprodiSiio->syncRolesWithoutDetaching([$kprodiRole]);

        $kaprodiAbo = User::create([
            'identifier' => 202352,
            'username' => 'Usman',
            'name' => 'Usman',
            'profile_photo_path' => NULL,
            'email' => 'kaprodi.abo@stmi.ac.id',
            'email_verified_at' => now(),
            'password' => bcrypt('only4SIIO'),
            'remember_token' => Str::random(36),
        ]);
        $kaprodiAbo->syncRolesWithoutDetaching([$kprodiRole]);

        $kaprodiTio = User::create([
            'identifier' => 202353,
            'username' => 'Harun',
            'name' => 'Harun',
            'profile_photo_path' => NULL,
            'email' => 'kaprodi.tio@stmi.ac.id',
            'email_verified_at' => now(),
            'password' => bcrypt('only4SIIO'),
            'remember_token' => Str::random(36),
        ]);
        $kaprodiTio->syncRolesWithoutDetaching([$kprodiRole]);

        $kaprodiTro = User::create([
            'identifier' => 202354,
            'username' => 'Hatta',
            'name' => 'Hatta',
            'profile_photo_path' => NULL,
            'email' => 'kaprodi.tro@stmi.ac.id',
            'email_verified_at' => now(),
            'password' => bcrypt('only4SIIO'),
            'remember_token' => Str::random(36),
        ]);
        $kaprodiTro->syncRolesWithoutDetaching([$kprodiRole]);

        $kaprodiTkp = User::create([
            'identifier' => 202355,
            'username' => 'Rajasa',
            'name' => 'Rajasa',
            'profile_photo_path' => NULL,
            'email' => 'kaprodi.tkp@stmi.ac.id',
            'email_verified_at' => now(),
            'password' => bcrypt('only4SIIO'),
            'remember_token' => Str::random(36),
        ]);
        $kaprodiTkp->syncRolesWithoutDetaching([$kprodiRole]);

        // Create DOSEN User
        $dosen = User::create([
            'identifier' => 20236,
            'username' => 'Triana Fatmawati',
            'name' => 'Triana Fatmawati',
            'profile_photo_path' => NULL,
            'email' => 'triana@stmi.ac.id',
            'email_verified_at' => now(),
            'password' => bcrypt('only4SIIO'),
            'remember_token' => Str::random(36),
        ]);
        $dosen->syncRolesWithoutDetaching([$dosenRole]);

        // Create DIREKTUR User
        $direktur = User::create([
            'identifier' => 20237,
            'username' => 'MUSTOFA',
            'name' => 'MUSTOFA',
            'profile_photo_path' => NULL,
            'email' => 'mustofa@stmi.ac.id',
            'email_verified_at' => now(),
            'password' => bcrypt('only4SIIO'),
            'remember_token' => Str::random(36),
        ]);
        $direktur->syncRolesWithoutDetaching([$direkturRole]);

        // Create DIREKTUR User
        $pudir = User::create([
            'identifier' => 20238,
            'username' => 'Sony',
            'name' => 'Sony',
            'profile_photo_path' => NULL,
            'email' => 'sony@stmi.ac.id',
            'email_verified_at' => now(),
            'password' => bcrypt('only4SIIO'),
            'remember_token' => Str::random(36),
        ]);
        $pudir->syncRolesWithoutDetaching([$pudir1Role]);

        $appConfigs = [
            [
                'identifier' => 20237,
                'name' => 'Mustofa',
                'position' => 'DIREKTUR',
                'is_active' => true,
            ],
            [
                'identifier' => 20231,
                'name' => 'Pusdata-Account',
                'position' => 'PUSDATA',
                'is_active' => true,
            ],
            [
                'identifier' => 20232,
                'name' => 'Akademik-Account',
                'position' => 'AKADEMIK',
                'is_active' => true,
            ],
            [
                'identifier' => 20233,
                'name' => 'Bagian-Umum-Account',
                'position' => 'BAG-UMUM',
                'is_active' => true,
            ],
            [
                'identifier' => 20238,
                'name' => 'Sony',
                'position' => 'PUDIR-1',
                'is_active' => true,
            ],
        ];
        foreach ($appConfigs as $appConfig) {
            AppConfig::create($appConfig);
        }

        $mahasiswaData = Mahasiswa::create([
            'identifier' => 20234,
            'name' => 'Annisa Eka Nur K',
            'email' => 'nisa@stmi.ac.id',
            'telp' => null,
            'prodi' => 'SIIO',
            'created_by' => 20231,
            'join_date' => '01-01-2021',
            'dob' => null,
            'semester' => 5,
            'address' => null,
        ]);
    }
}
