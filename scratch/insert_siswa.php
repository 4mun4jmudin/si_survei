<?php

use App\Models\User;
use App\Models\Siswa;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$data = [
    'X RPL 1' => [
        ['nis' => '252610001', 'nama' => 'ANDRI RISMA NURITA'],
        ['nis' => '252610002', 'nama' => 'DEDE IHSAN HUDA'],
        ['nis' => '252610003', 'nama' => 'DERI FEBRIANSYAH ARDI'],
        ['nis' => '252610004', 'nama' => 'FAZLI NORDIKZY NUGRAHA'],
        ['nis' => '252610005', 'nama' => 'FERDIAN'],
        ['nis' => '252610006', 'nama' => 'GIBRAN MUSYAKIB'],
        ['nis' => '252610007', 'nama' => 'JEFRI AL FAJAR'],
        ['nis' => '252610008', 'nama' => 'MUHAMMAD PRATAMA RYOSA'],
        ['nis' => '252610009', 'nama' => 'MUHAMMAD REFA'],
        ['nis' => '252610010', 'nama' => 'MUHAMMAD ZEINA ARIP'],
        ['nis' => '252610011', 'nama' => 'MUHAMMAD ZULFIKAR'],
        ['nis' => '252610012', 'nama' => 'NOVI NENGSIH'],
        ['nis' => '252610013', 'nama' => 'NUNUNG SANTI NURFENI'],
        ['nis' => '252610014', 'nama' => 'SEPTIANA'],
        ['nis' => '252610015', 'nama' => 'SRI KHAZANAH'],
        ['nis' => '252610016', 'nama' => 'SUGIANTI'],
        ['nis' => '252610017', 'nama' => 'VICKY ALZIEN F'],
    ],
    'XI RPL 1' => [
        ['nis' => '242510002', 'nama' => 'AHMAD TSABIT'],
        ['nis' => '242510003', 'nama' => 'AGNIA NURUL HASANAH'],
        ['nis' => '242510004', 'nama' => 'AGUNG NOVA KURNIAWAN'],
        ['nis' => '242510005', 'nama' => 'AHMAD RIYADI'],
        ['nis' => '242510006', 'nama' => 'AL MUSTAFAHATUNNISA'],
        ['nis' => '242510007', 'nama' => 'ANNISA NUR AZIZAH'],
        ['nis' => '242510008', 'nama' => 'AVRILIA DEWANTI'],
        ['nis' => '242510009', 'nama' => 'CAHYA NUR AINI'],
        ['nis' => '242510010', 'nama' => 'DIKI AHMAD RAMDAN'],
        ['nis' => '242510011', 'nama' => 'DITA PUSPITA'],
        ['nis' => '242510012', 'nama' => 'FADZANA ABDUL AZIZ'],
        ['nis' => '242510013', 'nama' => 'FIKRI ABDUL AZIZ'],
        ['nis' => '242510014', 'nama' => 'HUSNA QONITAH AZAHRA'],
        ['nis' => '242510015', 'nama' => 'KENETH ASFA APRILIA'],
        ['nis' => '242510016', 'nama' => 'KHOLIFATUL JANNAH'],
        ['nis' => '242510017', 'nama' => 'MAYLAH NIZAYA AZALIA'],
        ['nis' => '242510018', 'nama' => 'MOCH. SANDI FAUZI'],
        ['nis' => '242510019', 'nama' => 'MUHAMMAD AGIL RAMDHANA'],
        ['nis' => '242510020', 'nama' => 'MUHAMMAD AKBAR TAUFIK'],
        ['nis' => '242510021', 'nama' => 'MUHAMMAD DAVA FADILAH'],
        ['nis' => '242510022', 'nama' => 'MUKHLIS IBADURAHMAN'],
        ['nis' => '242510023', 'nama' => 'MUHAMMAD RIZKI'],
        ['nis' => '242510024', 'nama' => 'NOVAL SEVENTIN GUNAWAN'],
        ['nis' => '242510025', 'nama' => 'NUR FAUZAN'],
        ['nis' => '242510026', 'nama' => 'REHAN RAMADONA WIDIAWAN'],
        ['nis' => '242510027', 'nama' => 'RIZKA OCTAVIA FITRI'],
        ['nis' => '242510028', 'nama' => 'ROVI SAPUTRA'],
        ['nis' => '242510029', 'nama' => 'SITI ALIYAH MAULANI'],
        ['nis' => '242510030', 'nama' => 'SHEREN AGUSTINA'],
        ['nis' => '242510031', 'nama' => 'TANTI DEWI NURJANAH'],
        ['nis' => '242510032', 'nama' => 'TIARA KHOLILATULLOHIAH MS'],
        ['nis' => '242510033', 'nama' => 'TIARA NURSYA AINI'],
    ],
    'XII RPL 1' => [
        ['nis' => '232410001', 'nama' => 'AUDI FITRI YULIA'],
        ['nis' => '232410002', 'nama' => 'ABDURAH EL SHOFWA'],
        ['nis' => '232410003', 'nama' => 'ALFIANI LESTARI'],
        ['nis' => '232410005', 'nama' => 'AJENG ALFARISTY TATA AZZAHRA'],
        ['nis' => '232410006', 'nama' => 'ANISA MASYITOH'],
        ['nis' => '232410007', 'nama' => 'BILLA SIFA YON ANWAR'],
        ['nis' => '232410008', 'nama' => 'DELISA PUTRI KAMILLA'],
        ['nis' => '232410009', 'nama' => 'DILA AYU NINGRUM'],
        ['nis' => '232410013', 'nama' => 'DWI FITRIANI'],
        ['nis' => '232410014', 'nama' => 'FITRAH FIRMAN SYAH'],
        ['nis' => '232410015', 'nama' => 'HANI NUR HASANAH'],
        ['nis' => '232410020', 'nama' => 'ILHAA FAJAR PADILAH'],
        ['nis' => '232410021', 'nama' => 'KENIA SYIFA RAMADHANI'],
        ['nis' => '232410030', 'nama' => 'M. ALI MUFLIH KHASANI'],
        ['nis' => '232410037', 'nama' => 'RAIHAN ABDUL YASIN'],
        ['nis' => '232410038', 'nama' => 'SALSABILA NURAZIZAH'],
        ['nis' => '232410031', 'nama' => 'NOVAL RIZKI'],
        ['nis' => '232410032', 'nama' => 'NURUL AMELIA'],
        ['nis' => '232410035', 'nama' => 'RAFLI AHMAD ALFARIG'],
        ['nis' => '232410036', 'nama' => 'RAJA AGUNG RAHARJA'],
        ['nis' => '232410052', 'nama' => 'SALMA KAMILA'],
        ['nis' => '232410053', 'nama' => 'SHIFA RIZKI AMALIATI'],
        ['nis' => '232410048', 'nama' => 'VAYHA HANDIKA'],
    ],
    'XII RPL 2' => [
        ['nis' => '232410001B', 'nama' => 'ARNI NURAINI'], // Differentiate if duplicate
        ['nis' => '232410037B', 'nama' => 'CEPI WILDAN'],
        ['nis' => '232410004', 'nama' => 'DWI LESTARI'],
        ['nis' => '232410010', 'nama' => 'ELTIDA SUSANTI'],
        ['nis' => '232410011', 'nama' => 'ENJAL JUNIAR'],
        ['nis' => '232410018', 'nama' => 'ENJUL AINI'],
        ['nis' => '232410019', 'nama' => 'EVA NURRAHMAH'],
        ['nis' => '232410021B', 'nama' => 'FERNI ASTUTI'],
        ['nis' => '232410022', 'nama' => 'KESHA NURAFIAH'],
        ['nis' => '232410025', 'nama' => 'KOHLAMA DILLA FABRIZA'],
        ['nis' => '232410026', 'nama' => 'M. ALFIAN TRI KURNIAWAN'],
        ['nis' => '232410023', 'nama' => 'MUHAMAD DOSTHY QOUSQULZAH'],
        ['nis' => '232410024', 'nama' => 'MUHAMAD RIZKI'],
        ['nis' => '232410033', 'nama' => 'NUR ROFIQ FEBRI ABDULLAH'],
        ['nis' => '232410036B', 'nama' => 'NURUL HIDAYAH'],
        ['nis' => '232410026B', 'nama' => 'MUHAMAD AL FAKHRI FATURROHIM'],
        ['nis' => '232410038B', 'nama' => 'RAHMAT'],
        ['nis' => '232410042', 'nama' => 'RIFDI WILHAMAD MA\'DUDDIN'],
        ['nis' => '232410043', 'nama' => 'RIKO WIJAYA'],
        ['nis' => '232410044', 'nama' => 'RIZAL MUHAMMAD FAUZI'],
        ['nis' => '232410045', 'nama' => 'SELVI NURJANNAH'],
        ['nis' => '232410046', 'nama' => 'SITI ANGGINI'],
        ['nis' => '232410047', 'nama' => 'TIAN NUR INSYAH'],
        ['nis' => '232410049', 'nama' => 'TIDORI PUTRA RAMADHAN'],
    ],
];

$password = Hash::make('password123');
$count = 0;

DB::beginTransaction();
try {
    foreach ($data as $kelas => $siswas) {
        foreach ($siswas as $s) {
            $username = $s['nis'];

            $user = User::create([
                'name' => $s['nama'],
                'username' => $username,
                'password' => $password,
                'role' => 'siswa',
                'status_aktif' => true,
            ]);

            Siswa::create([
                'user_id' => $user->id,
                'nis' => $username,
                'nama_lengkap' => $s['nama'],
                'kelas' => $kelas,
            ]);
            $count++;
        }
    }
    DB::commit();
    echo "Successfully inserted $count students.\n";
} catch (\Exception $e) {
    DB::rollBack();
    echo "Error: " . $e->getMessage() . "\n";
}
