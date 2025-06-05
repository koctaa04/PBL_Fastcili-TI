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
        // 1
        $data = [
            ['nama' => 'Admin JTI', 'akses' => '1', 'password' => Hash::make('password'), 'email' => 'admin@jti.com', 'id_level' => 1],
        ];

        // 2-8
        for ($i = 1; $i <= 7; $i++) {
            $data[] = ['nama' => "Sarpras $i",   'akses' => '1', 'password' => Hash::make('password'), 'email' => "sarpras$i@jti.com", 'id_level' => 2];
        }

        //9-15
        for ($i = 1; $i <= 7; $i++) {
            $data[] = ['nama' => "Teknisi $i",   'akses' => '1', 'password' => Hash::make('password'), 'email' => "teknisi$i@jti.com", 'id_level' => 3];
        }

        //16-25
        for ($i = 1; $i <= 10; $i++) {
            $data[] = ['nama' => "Dosen $i",     'akses' => '1', 'password' => Hash::make('password'), 'email' => "dosen$i@jti.com", 'id_level' => 5];
        }

        //26-30
        for ($i = 1; $i <= 5; $i++) {
            $data[] = ['nama' => "Tendik $i",    'akses' => '1', 'password' => Hash::make('password'), 'email' => "tendik$i@jti.com", 'id_level' => 6];
        }

        //31-50
        for ($i = 1; $i <= 20; $i++) {
            $data[] = ['nama' => "Mahasiswa $i", 'akses' => '1', 'password' => Hash::make('password'), 'email' => "mhs$i@jti.com", 'id_level' => 4];
        }

        DB::table('users')->insert($data);
    }
}
