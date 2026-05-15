<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Guru Testing',
            'username' => 'guru1',
            'email' => 'guru@example.com',
            'password' => bcrypt('password'),
            'role' => 'guru',
        ]);

        User::create([
            'name' => 'Siswa Testing',
            'username' => 'siswa1',
            'email' => 'siswa@example.com',
            'password' => bcrypt('password'),
            'role' => 'siswa',
        ]);

        $this->call([
            SiswaSeeder::class,
            GuruSeeder::class,
            PertanyaanSeeder::class,
            // SurveySimulationSeeder::class, // Matikan simulasi dummy
        ]);
    }
}
