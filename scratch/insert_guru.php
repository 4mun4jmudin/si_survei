<?php

use App\Models\User;
use App\Models\Guru;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

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

$password = Hash::make('password123');
$count = 0;

DB::beginTransaction();
try {
    foreach ($names as $idx => $name) {
        $nip = "G" . str_pad($idx + 1, 3, '0', STR_PAD_LEFT); // G001, G002...

        $user = User::create([
            'name' => $name,
            'username' => $nip,
            'password' => $password,
            'role' => 'guru',
            'status_aktif' => true,
        ]);

        Guru::create([
            'user_id' => $user->id,
            'nip' => $nip,
            'nama_lengkap' => $name,
            'mapel' => '-', // Default
        ]);
        $count++;
    }
    DB::commit();
    echo "Successfully inserted $count teachers.\n";
} catch (\Exception $e) {
    DB::rollBack();
    echo "Error: " . $e->getMessage() . "\n";
}
