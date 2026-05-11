<?php

namespace App\Exports;

use App\Models\Jawaban;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class JawabanExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $kuesioner_id;

    public function __construct($kuesioner_id)
    {
        $this->kuesioner_id = $kuesioner_id;
    }

    public function query()
    {
        return Jawaban::query()
            ->with(['user.siswa', 'pertanyaan', 'kuesioner'])
            ->where('kuesioner_id', $this->kuesioner_id)
            ->orderBy('user_id');
    }

    public function headings(): array
    {
        return [
            'ID Jawaban',
            'Nama Responden',
            'Role',
            'Kelas / Jabatan',
            'Nama Survei',
            'Indikator Pertanyaan',
            'Teks Pertanyaan',
            'Nilai Jawaban',
            'Waktu Pengisian',
        ];
    }

    public function map($jawaban): array
    {
        $kelasJabatan = '-';
        if ($jawaban->user->role == 'siswa' && $jawaban->user->siswa) {
            $kelasJabatan = $jawaban->user->siswa->kelas;
        } elseif ($jawaban->user->role == 'guru' && $jawaban->user->guru) {
            $kelasJabatan = $jawaban->user->guru->jabatan;
        }

        return [
            $jawaban->id,
            $jawaban->user->name ?? '-',
            ucfirst($jawaban->user->role),
            $kelasJabatan,
            $jawaban->kuesioner->nama_kuesioner ?? '-',
            $jawaban->pertanyaan->indikator ?? '-',
            $jawaban->pertanyaan->teks_pertanyaan ?? '-',
            $jawaban->nilai_jawaban,
            $jawaban->created_at->format('d/m/Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']], 'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF4F46E5']]],
        ];
    }
}
