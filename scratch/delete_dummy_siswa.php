<?php

use App\Models\User;
use App\Models\Siswa;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$dummyUsers = User::where('name', 'like', 'Siswa %')->get();
$count = 0;

DB::beginTransaction();
try {
    foreach ($dummyUsers as $user) {
        // Delete related siswa record first if exists
        Siswa::where('user_id', $user->id)->delete();
        
        // Delete user
        $user->delete();
        $count++;
    }
    DB::commit();
    echo "Successfully deleted $count dummy student records.\n";
} catch (\Exception $e) {
    DB::rollBack();
    echo "Error: " . $e->getMessage() . "\n";
}
