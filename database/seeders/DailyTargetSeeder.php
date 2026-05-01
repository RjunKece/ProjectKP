<?php

namespace Database\Seeders;

use App\Models\DailyTarget;
use App\Models\Division;
use Illuminate\Database\Seeder;

class DailyTargetSeeder extends Seeder
{
    public function run(): void
    {
        $targets = [
            'Marketing' => [
                ['title' => 'Menjalankan campaign iklan digital',       'target_count' => 3,  'unit' => 'campaign'],
                ['title' => 'Update konten media sosial',               'target_count' => 5,  'unit' => 'post'],
                ['title' => 'Analisis data marketing',                  'target_count' => 1,  'unit' => 'laporan'],
            ],
            'Sales' => [
                ['title' => 'Follow up prospek klien',                  'target_count' => 10, 'unit' => 'prospek'],
                ['title' => 'Presentasi produk ke calon customer',      'target_count' => 3,  'unit' => 'presentasi'],
                ['title' => 'Closing deal & pembuatan invoice',         'target_count' => 2,  'unit' => 'deal'],
            ],
            'Keuangan' => [
                ['title' => 'Input data transaksi harian',              'target_count' => 20, 'unit' => 'transaksi'],
                ['title' => 'Verifikasi reimbursement',                 'target_count' => 5,  'unit' => 'dokumen'],
                ['title' => 'Rekonsiliasi bank statement',              'target_count' => 1,  'unit' => 'laporan'],
            ],
            'Konten Kreator' => [
                ['title' => 'Upload video konten produk',               'target_count' => 15, 'unit' => 'video'],
                ['title' => 'Editing dan rendering video',              'target_count' => 10, 'unit' => 'video'],
                ['title' => 'Pembuatan thumbnail dan caption',          'target_count' => 15, 'unit' => 'thumbnail'],
                ['title' => 'Riset trend konten terbaru',               'target_count' => 1,  'unit' => 'riset'],
            ],
            'Gudang' => [
                ['title' => 'Pengecekan stok barang masuk',             'target_count' => 1,  'unit' => 'pengecekan'],
                ['title' => 'Update data inventaris di sistem',         'target_count' => 10, 'unit' => 'item'],
                ['title' => 'Packing dan pengiriman order',             'target_count' => 20, 'unit' => 'paket'],
            ],
            'CRM' => [
                ['title' => 'Update data pelanggan di CRM',             'target_count' => 15, 'unit' => 'data'],
                ['title' => 'Follow up feedback pelanggan',             'target_count' => 10, 'unit' => 'feedback'],
                ['title' => 'Setup automation email campaign',          'target_count' => 2,  'unit' => 'campaign'],
            ],
            'YouTube' => [
                ['title' => 'Upload video ke channel YouTube',          'target_count' => 3,  'unit' => 'video'],
                ['title' => 'Optimasi SEO title, desc & tags',          'target_count' => 5,  'unit' => 'video'],
                ['title' => 'Monitoring komentar dan engagement',       'target_count' => 1,  'unit' => 'sesi'],
                ['title' => 'Pembuatan script video baru',              'target_count' => 2,  'unit' => 'script'],
            ],
            'Admin Marketplace' => [
                ['title' => 'Update listing produk di marketplace',     'target_count' => 10, 'unit' => 'listing'],
                ['title' => 'Membalas chat dan pertanyaan pembeli',     'target_count' => 30, 'unit' => 'chat'],
                ['title' => 'Proses pesanan dan pengiriman',            'target_count' => 20, 'unit' => 'pesanan'],
                ['title' => 'Monitoring dan update harga produk',       'target_count' => 1,  'unit' => 'sesi'],
            ],
        ];

        foreach ($targets as $divisionName => $items) {
            $division = Division::where('nama_divisi', $divisionName)->first();
            if (!$division) continue;

            foreach ($items as $item) {
                DailyTarget::updateOrCreate(
                    [
                        'division_id' => $division->id,
                        'title'       => $item['title'],
                    ],
                    [
                        'target_count' => $item['target_count'],
                        'unit'         => $item['unit'],
                        'is_default'   => true,
                        'is_active'    => true,
                    ]
                );
            }
        }
    }
}
