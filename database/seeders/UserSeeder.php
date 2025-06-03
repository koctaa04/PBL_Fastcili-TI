<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['nama' => 'Admin JTI', 'akses' => '1', 'password' => Hash::make('password'), 'email' => 'admin@jti.com', 'id_level' => 1],
            ['nama' => 'Admin JTI 2', 'akses' => '1', 'password' => Hash::make('password'), 'email' => 'admin2@jti.com', 'id_level' => 1],
            ['nama' => 'Admin JTI 3', 'akses' => '1', 'password' => Hash::make('password'), 'email' => 'admin3@jti.com', 'id_level' => 1],
        ];

        for ($i = 1; $i <= 5; $i++) {
            $data[] = ['nama' => "Mahasiswa $i", 'akses' => '1', 'password' => Hash::make('password'), 'email' => "mhs$i@jti.com", 'id_level' => 4];
            $data[] = ['nama' => "Dosen $i",     'akses' => '1', 'password' => Hash::make('password'), 'email' => "dosen$i@jti.com", 'id_level' => 5];
            $data[] = ['nama' => "Tendik $i",    'akses' => '1', 'password' => Hash::make('password'), 'email' => "tendik$i@jti.com", 'id_level' => 6];
            $data[] = ['nama' => "Sarpras $i",   'akses' => '1', 'password' => Hash::make('password'), 'email' => "sarpras$i@jti.com", 'id_level' => 2];
        }

        DB::table('users')->insert($data);
    }
}
