<?php

namespace App\Services;

class KMeansService
{
    private $k;
    private $maxIterations;
    private $labels = ['Sangat Baik', 'Baik', 'Cukup', 'Kurang'];

    public function __construct($k = 4, $maxIterations = 100)
    {
        $this->k = $k;
        $this->maxIterations = $maxIterations;
    }

    /**
     * Jalankan algoritma K-Means
     * @param array $dataset [ ['id' => 1, 'features' => [4.5, 3.2, ...]], ... ]
     * @return array
     */
    public function cluster(array $dataset)
    {
        if (empty($dataset) || count($dataset) < $this->k) {
            return [
                'error' => 'Dataset terlalu kecil untuk jumlah cluster yang diminta.',
                'clusters' => [],
                'centroids' => []
            ];
        }

        // 1. Inisialisasi Centroid (Ambil K data acak)
        $centroids = $this->initializeCentroids($dataset);
        $clusters = [];
        
        $iteration = 0;
        $converged = false;

        while (!$converged && $iteration < $this->maxIterations) {
            // 2. Assign data ke centroid terdekat
            $clusters = array_fill(0, $this->k, []);
            
            foreach ($dataset as $point) {
                $nearestIndex = $this->getNearestCentroid($point['features'], $centroids);
                $clusters[$nearestIndex][] = $point;
            }

            // 3. Update Centroid
            $newCentroids = $this->updateCentroids($clusters, $centroids);

            // Cek konvergensi
            $converged = $this->checkConvergence($centroids, $newCentroids);
            $centroids = $newCentroids;
            $iteration++;
        }

        // 4. Urutkan & Beri Label Centroid (Sangat Baik -> Kurang)
        $labeledResults = $this->labelClusters($centroids, $clusters);

        return [
            'iterations' => $iteration,
            'centroids' => $labeledResults['centroids'],
            'clusters' => $labeledResults['clusters']
        ];
    }

    private function initializeCentroids($dataset)
    {
        // Shuffle dataset dan ambil K item pertama
        shuffle($dataset);
        $centroids = [];
        for ($i = 0; $i < $this->k; $i++) {
            $centroids[] = $dataset[$i]['features'];
        }
        return $centroids;
    }

    private function getNearestCentroid($features, $centroids)
    {
        $minDist = PHP_FLOAT_MAX;
        $nearestIndex = 0;

        foreach ($centroids as $index => $centroid) {
            $dist = $this->euclideanDistance($features, $centroid);
            if ($dist < $minDist) {
                $minDist = $dist;
                $nearestIndex = $index;
            }
        }

        return $nearestIndex;
    }

    private function euclideanDistance($p1, $p2)
    {
        $sum = 0;
        foreach ($p1 as $i => $val) {
            // Jika ada missing value, asumsikan 0 (meskipun di sistem nyata harusnya ditangani)
            $v2 = $p2[$i] ?? 0; 
            $sum += pow($val - $v2, 2);
        }
        return sqrt($sum);
    }

    private function updateCentroids($clusters, $oldCentroids)
    {
        $newCentroids = [];
        $numFeatures = count($oldCentroids[0]);

        foreach ($clusters as $index => $clusterPoints) {
            if (empty($clusterPoints)) {
                // Jika cluster kosong, pertahankan centroid lama
                $newCentroids[$index] = $oldCentroids[$index];
                continue;
            }

            $sums = array_fill(0, $numFeatures, 0);
            foreach ($clusterPoints as $point) {
                foreach ($point['features'] as $i => $val) {
                    $sums[$i] += $val;
                }
            }

            $count = count($clusterPoints);
            $newCentroid = [];
            foreach ($sums as $sum) {
                $newCentroid[] = $sum / $count;
            }
            $newCentroids[$index] = $newCentroid;
        }

        return $newCentroids;
    }

    private function checkConvergence($old, $new)
    {
        // Jika selisih posisi sangat kecil, anggap konvergen
        foreach ($old as $i => $centroid) {
            if ($this->euclideanDistance($centroid, $new[$i]) > 0.0001) {
                return false;
            }
        }
        return true;
    }

    private function labelClusters($centroids, $clusters)
    {
        // Hitung rata-rata nilai dari centroid untuk menentukan rangking (Sangat Baik, Baik, dsb)
        $centroidScores = [];
        foreach ($centroids as $index => $features) {
            $avgScore = count($features) > 0 ? array_sum($features) / count($features) : 0;
            $centroidScores[$index] = [
                'index' => $index,
                'features' => $features,
                'score' => $avgScore
            ];
        }

        // Urutkan berdasarkan score descending
        usort($centroidScores, function($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        $finalCentroids = [];
        $finalClusters = [];

        foreach ($centroidScores as $rank => $data) {
            $label = $this->labels[$rank] ?? 'Kategori ' . ($rank + 1);
            
            $finalCentroids[$label] = [
                'features' => $data['features'],
                'overall_score' => $data['score']
            ];

            // Masukkan data siswa dan tambahkan label ke masing-masing item
            $points = $clusters[$data['index']] ?? [];
            foreach ($points as &$p) {
                $p['cluster'] = $label;
            }
            $finalClusters[$label] = $points;
        }

        return [
            'centroids' => $finalCentroids,
            'clusters' => $finalClusters
        ];
    }
}
