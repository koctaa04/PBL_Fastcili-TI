<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */

    public function run(): void
    {
        $this->call([
            LevelSeeder::class,
            UserSeeder::class,
            GedungSeeder::class,
            RuanganSeeder::class,
            FasilitasSeeder::class,
            StatusLaporanSeeder::class,
            LaporanKerusakanSeeder::class,
            KriteriaPenilaianSeeder::class,
            PenugasanTeknisiSeeder::class,
            pelaporLaporanSeeder::class,
        ]);
    }
}
