<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert([
            ['nama' => 'Admin JTI', 'akses' => '1',   'password' => Hash::make('password'), 'email' => 'admin@jti.com',  'id_level' => 1],
            ['nama' => 'Mahasiswa 1', 'akses' => '1', 'password' => Hash::make('password'),  'email' => 'mhs@jti.com',  'id_level' => 4],
            ['nama' => 'Dosen 1', 'akses' => '1', 'password' => Hash::make('password'),  'email' => 'dosen@jti.com',  'id_level' => 4],
            ['nama' => 'Tendik 1', 'akses' => '1', 'password' => Hash::make('password'),  'email' => 'tendik1@jti.com',  'id_level' => 4],
            ['nama' => 'Sarpras', 'akses' => '1', 'password' => Hash::make('password'),  'email' => 'tendik2@jti.com',  'id_level' => 2],
            ['nama' => 'Teknisi', 'akses' => '1', 'password' => Hash::make('password'),  'email' => 'teknisi@jti.com',  'id_level' => 3],
        ]);
    }
}
