<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan: {{ $laporan->judul_laporan }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background: #f0f0f0;
        }
        .page-a4 {
            width: 210mm;
            min-height: 297mm;
            margin: 20mm auto;
            background: #fff;
            padding: 25mm 20mm;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            box-sizing: border-box;
        }
        .header-kop {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 25px;
        }
        .header-kop h1 { margin: 0; font-size: 24px; text-transform: uppercase; }
        .header-kop p { margin: 5px 0 0; font-size: 12px; color: #555; }
        .doc-title { text-align: center; margin-bottom: 30px; }
        .doc-title h2 { margin: 0; font-size: 18px; text-decoration: underline; }
        .doc-title p { margin: 5px 0 0; font-size: 14px; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 25px; font-size: 12px; }
        th, td { border: 1px solid #000; padding: 8px 10px; text-align: left; }
        th { background: #f9f9f9; font-weight: 600; }
        .text-center { text-align: center; }
        
        .section-title { font-size: 14px; font-weight: bold; margin-bottom: 10px; margin-top: 30px; text-transform: uppercase; border-bottom: 1px solid #ccc; padding-bottom: 5px; }
        .summary-box { border: 1px solid #000; padding: 15px; margin-bottom: 25px; font-size: 13px; }
        
        .footer-ttd { margin-top: 50px; text-align: right; font-size: 14px; }
        .footer-ttd p { margin: 0 0 70px 0; }
        .footer-ttd strong { border-bottom: 1px solid #000; padding-bottom: 2px; }

        @media print {
            body { background: #fff; }
            .page-a4 { margin: 0; padding: 10mm; box-shadow: none; border: none; width: 100%; }
            .no-print { display: none !important; }
        }

        .btn-print {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background: #4f46e5;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            border: none;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

    <button onclick="window.print()" class="btn-print no-print">Cetak PDF / Print</button>

    <div class="page-a4">
        <!-- KOP Surat Instansi -->
        <div class="header-kop">
            <h1>Sistem Informasi Survei Akademik</h1>
            <p>Portal Laporan Resmi Evaluasi Kepuasan Pembelajaran dan Layanan Sekolah</p>
        </div>

        <!-- Judul Dokumen -->
        <div class="doc-title">
            <h2>{{ strtoupper($laporan->judul_laporan) }}</h2>
            <p>Tanggal Cetak: {{ \Carbon\Carbon::now()->format('d F Y') }}</p>
        </div>

        <!-- Bagian 1: Informasi Survei -->
        <div class="section-title">A. INFORMASI SURVEI</div>
        <table>
            <tr>
                <th width="30%">Nama Survei / Kuesioner</th>
                <td>{{ $kuesioner->nama_kuesioner }}</td>
            </tr>
            <tr>
                <th>Periode Pelaksanaan</th>
                <td>{{ \Carbon\Carbon::parse($kuesioner->periode_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($kuesioner->periode_selesai)->format('d M Y') }}</td>
            </tr>
            <tr>
                <th>Total Responden (Siswa)</th>
                <td>{{ $statistik['total_responden'] }} Orang</td>
            </tr>
            <tr>
                <th>Rata-rata Skor Keseluruhan</th>
                <td><strong>{{ number_format($statistik['rata_rata_skor'], 2) }}</strong> (Skala 5.0)</td>
            </tr>
        </table>

        <!-- Bagian 2: Hasil K-Means Clustering -->
        <div class="section-title">B. HASIL EVALUASI OTOMATIS (K-MEANS CLUSTERING)</div>
        @if($clusteringResult)
            <p style="font-size: 13px; text-align: justify; margin-bottom: 15px;">
                Berdasarkan pengolahan data menggunakan algoritma <strong>K-Means Clustering</strong>, responden dikelompokkan menjadi 4 kategori tingkat kepuasan. Berikut adalah matriks nilai pusat (Centroid) untuk masing-masing kategori:
            </p>
            <table>
                <thead>
                    <tr>
                        <th class="text-center" width="20%">Kategori Kualitas</th>
                        <th class="text-center" width="20%">Jumlah Siswa</th>
                        <th class="text-center" width="60%">Rata-rata Nilai Pusat Keseluruhan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clusteringResult['clusters'] as $label => $points)
                        <tr>
                            <td class="text-center"><strong>{{ strtoupper($label) }}</strong></td>
                            <td class="text-center">{{ count($points) }} Siswa</td>
                            <td class="text-center">{{ number_format($clusteringResult['centroids'][$label]['overall_score'], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="summary-box">
                <em>* Data responden tidak cukup untuk diproses menggunakan algoritma K-Means Clustering (minimal 4 responden).</em>
            </div>
        @endif

        <!-- Bagian 3: Evaluasi & Rekomendasi Otomatis -->
        <div class="section-title">C. EVALUASI DAN REKOMENDASI OTOMATIS (PER INDIKATOR)</div>
        <table>
            <thead>
                <tr>
                    <th class="text-center" width="20%">Kategori/Indikator</th>
                    <th class="text-center" width="15%">Rata-rata Skor</th>
                    <th class="text-center" width="20%">Kriteria</th>
                    <th class="text-center" width="45%">Rekomendasi Tindakan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($evaluasiOtomatis as $eval)
                    <tr>
                        <td><strong>{{ $eval['indikator'] }}</strong></td>
                        <td class="text-center">{{ number_format($eval['rata_rata'], 2) }}</td>
                        <td class="text-center">
                            @if($eval['rata_rata'] >= 4.21)
                                <span style="color: #166534; font-weight: bold;">{{ $eval['predikat'] }}</span>
                            @elseif($eval['rata_rata'] >= 3.41)
                                <span style="color: #1d4ed8; font-weight: bold;">{{ $eval['predikat'] }}</span>
                            @elseif($eval['rata_rata'] >= 2.61)
                                <span style="color: #ca8a04; font-weight: bold;">{{ $eval['predikat'] }}</span>
                            @else
                                <span style="color: #b91c1c; font-weight: bold;">{{ $eval['predikat'] }}</span>
                            @endif
                        </td>
                        <td>{{ $eval['rekomendasi'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Bagian 4: Distribusi Jawaban Pilihan Ganda -->
        @if(count($distribusiPG) > 0)
        <div class="section-title" style="page-break-before: always;">D. DISTRIBUSI JAWABAN PILIHAN GANDA</div>
        @foreach($distribusiPG as $qText => $dist)
            <div style="margin-bottom: 20px;">
                <p style="font-size: 12px; font-weight: bold; margin-bottom: 5px;">{{ $loop->iteration }}. {{ $qText }}</p>
                @php
                    $rawOptions = explode("\n", str_replace("\r", "", $dist['opsi']));
                    $optionLabels = [];
                    foreach($rawOptions as $line) {
                        if(trim($line) == "") continue;
                        if(str_contains($line, '|')) {
                            $parts = explode('|', $line);
                            $optionLabels[trim($parts[1])] = trim($parts[0]);
                        } else {
                            $optionLabels[] = trim($line);
                        }
                    }
                    $totalQ = $dist['data']->sum('total');
                @endphp
                <table style="margin-bottom: 10px;">
                    <thead>
                        <tr>
                            <th>Opsi Jawaban</th>
                            <th class="text-center" width="20%">Jumlah</th>
                            <th class="text-center" width="20%">Persentase</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dist['data'] as $d)
                            <tr>
                                <td>{{ $optionLabels[$d->nilai_jawaban] ?? 'Opsi ' . $d->nilai_jawaban }}</td>
                                <td class="text-center">{{ $d->total }}</td>
                                <td class="text-center">{{ $totalQ > 0 ? round(($d->total / $totalQ) * 100, 1) : 0 }}%</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
        @endif

        <!-- Bagian 5: Analisis Jawaban Esai -->
        @if(count($jawabanEsai) > 0)
        <div class="section-title" style="page-break-before: always;">E. KUMPULAN JAWABAN ESAI (KRITIK & SARAN)</div>
        @foreach($jawabanEsai as $qText => $answers)
            <div style="margin-bottom: 20px;">
                <p style="font-size: 12px; font-weight: bold; margin-bottom: 10px; background: #f0f0f0; padding: 5px;">{{ $qText }}</p>
                <ul style="font-size: 11px; color: #555; padding-left: 20px;">
                    @foreach($answers as $ans)
                        <li style="margin-bottom: 8px;"><em>"{{ $ans->jawaban_teks }}"</em></li>
                    @endforeach
                </ul>
            </div>
        @endforeach
        @endif

        <!-- Bagian 6: Ringkasan Analisis Administrator -->
        <div class="section-title">F. CATATAN & KESIMPULAN ADMINISTRATOR</div>
        <div class="summary-box">
            @if($laporan->ringkasan)
                {!! nl2br(e($laporan->ringkasan)) !!}
            @else
                <em>(Tidak ada catatan kesimpulan tambahan yang dilampirkan oleh administrator saat arsip laporan ini dibuat).</em>
            @endif
        </div>

        <!-- Tanda Tangan -->
        <div class="footer-ttd">
            <p>Mengetahui,<br>Administrator Sistem</p>
            <br>
            <strong>{{ Auth::user()->name }}</strong>
        </div>
    </div>

    <!-- Auto trigger print dialog (Opsional) -->
    <!-- <script> window.onload = function() { window.print(); } </script> -->
</body>
</html>
