<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Siswa;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SiswaImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Abaikan jika nama atau username kosong
            if (!isset($row['nama']) || !isset($row['username'])) continue;

            $user = User::firstOrCreate(
                ['username' => $row['username']],
                [
                    'name' => $row['nama'],
                    'email' => $row['email'] ?? null,
                    'password' => Hash::make($row['password'] ?? 'password123'), // Default password
                    'role' => 'siswa'
                ]
            );

            Siswa::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nis' => $row['nis'] ?? null,
                    'nisn' => $row['nisn'] ?? null,
                    'kelas' => $row['kelas'] ?? null,
                ]
            );
        }
    }
}
