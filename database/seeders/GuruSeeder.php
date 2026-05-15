<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GuruSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = [
            "Andriani, ST., M.Kom",
            "Neneng Nurlatifah, S.Pd",
            "Hany Hurul ‘Ain, M.Pd",
            "Iwan Kurniawan, S.H., M.M., M.Pd",
            "Yani Andriani, S.Pd",
            "Ade Hermawati, S.Pd",
            "Juita Erdassari, S.Pd",
            "R. Hani Rustiyah, A.Ks",
            "Puput Nurholipah, S.Psi",
            "Dony Romdhony M M.Ag",
            "Qony Qonyatul Qurany, ST",
            "Hilma Solihat, S.Pd",
            "Nany Nurainy M., Ss",
            "Nadya Nur Auliaunnisa",
            "Windra Rohyati, S.Kom",
            "Agus Setiawan, S.Pd",
            "Nuri Nurianti, S.T",
        ];

        $password = \Illuminate\Support\Facades\Hash::make('password123');

        foreach ($names as $idx => $name) {
            $nip = "G" . str_pad($idx + 1, 3, '0', STR_PAD_LEFT); // G001, G002...

            // Skip if exists
            if (\App\Models\User::where('username', $nip)->exists()) continue;

            $user = \App\Models\User::create([
                'name' => $name,
                'username' => $nip,
                'password' => $password,
                'role' => 'guru',
                'status_aktif' => true,
            ]);

            \App\Models\Guru::create([
                'user_id' => $user->id,
                'nip' => $nip,
                'nama_lengkap' => $name,
                'mapel' => '-', 
            ]);
        }
    }
}
