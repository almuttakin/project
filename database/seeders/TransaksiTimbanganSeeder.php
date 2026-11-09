<?php

namespace Database\Seeders;

use App\Models\LogIntegrasiSap;
use App\Models\TransaksiTimbangan;
use App\Models\UnitPks;
use Illuminate\Database\Seeder;

class TransaksiTimbanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan Unit PKS sudah tersedia
        $units = UnitPks::all()->keyBy('nama_unit');

        $transactions = [
            [
                'nota_no'          => 'NT-PKS-PBT-20260810-001',
                'pks_name'         => 'PKS Pabatu',
                'no_plat'          => 'BM 8492 AU',
                'jam_masuk'        => '08:15:00',
                'jam_keluar'       => '08:45:00',
                'gross'            => 24500,
                'tarra'            => 9200,
                'netto'            => 15300,
                'status_timbangan' => 'Normal',
                'xml_status'       => 'Generated',
                'status_sap'       => 'Sent',
                'sap_sent_at'      => '2026-08-10 08:50:12',
                'sap_doc_no'       => 'SAP-PTPN4-9920194',
                'created_at'       => '2026-08-10 08:15:00',
            ],
            [
                'nota_no'          => 'NT-PKS-SWS-20260810-002',
                'pks_name'         => 'PKS Sawit Seberang',
                'no_plat'          => 'BM 9102 CD',
                'jam_masuk'        => '09:10:00',
                'jam_keluar'       => '09:30:00',
                'gross'            => 18200,
                'tarra'            => 8100,
                'netto'            => 10100,
                'status_timbangan' => 'Mati/Offline',
                'xml_status'       => 'Generated',
                'status_sap'       => 'Pending',
                'sap_sent_at'      => null,
                'sap_doc_no'       => null,
                'created_at'       => '2026-08-10 09:10:00',
            ],
            [
                'nota_no'          => 'NT-PKS-BHJ-20260810-003',
                'pks_name'         => 'PKS Bah Jambi',
                'no_plat'          => 'BK 7721 DB',
                'jam_masuk'        => '10:05:00',
                'jam_keluar'       => '10:35:00',
                'gross'            => 29800,
                'tarra'            => 9500,
                'netto'            => 20300,
                'status_timbangan' => 'Normal',
                'xml_status'       => 'Generated',
                'status_sap'       => 'Sent',
                'sap_sent_at'      => '2026-08-10 10:40:05',
                'sap_doc_no'       => 'SAP-PTPN4-9920198',
                'created_at'       => '2026-08-10 10:05:00',
            ],
            [
                'nota_no'          => 'NT-PKS-MYG-20260810-004',
                'pks_name'         => 'PKS Mayang',
                'no_plat'          => 'BK 8810 AY',
                'jam_masuk'        => '11:20:00',
                'jam_keluar'       => '11:50:00',
                'gross'            => 22400,
                'tarra'            => 8600,
                'netto'            => 13800,
                'status_timbangan' => 'Normal',
                'xml_status'       => 'Generated',
                'status_sap'       => 'Pending',
                'sap_sent_at'      => null,
                'sap_doc_no'       => null,
                'created_at'       => '2026-08-10 11:20:00',
            ],
            [
                'nota_no'          => 'NT-PKS-TNH-20260810-005',
                'pks_name'         => 'PKS Tanah Hitam',
                'no_plat'          => 'BM 7312 WQ',
                'jam_masuk'        => '12:00:00',
                'jam_keluar'       => '12:25:00',
                'gross'            => 31000,
                'tarra'            => 9800,
                'netto'            => 21200,
                'status_timbangan' => 'Normal',
                'xml_status'       => 'Generated',
                'status_sap'       => 'Sent',
                'sap_sent_at'      => '2026-08-10 12:30:19',
                'sap_doc_no'       => 'SAP-PTPN4-9920205',
                'created_at'       => '2026-08-10 12:00:00',
            ],
            [
                'nota_no'          => 'NT-PKS-SGL-20260810-006',
                'pks_name'         => 'PKS Sei Galuh',
                'no_plat'          => 'BM 8122 QH',
                'jam_masuk'        => '13:10:00',
                'jam_keluar'       => '13:40:00',
                'gross'            => 26400,
                'tarra'            => 8900,
                'netto'            => 17500,
                'status_timbangan' => 'Normal',
                'xml_status'       => 'Generated',
                'status_sap'       => 'Sent',
                'sap_sent_at'      => '2026-08-10 13:45:10',
                'sap_doc_no'       => 'SAP-PTPN4-9920212',
                'created_at'       => '2026-08-10 13:10:00',
            ],
            [
                'nota_no'          => 'NT-PKS-SPG-20260810-007',
                'pks_name'         => 'PKS Sei Pagar',
                'no_plat'          => 'BM 9401 AG',
                'jam_masuk'        => '14:05:00',
                'jam_keluar'       => '14:35:00',
                'gross'            => 25800,
                'tarra'            => 9100,
                'netto'            => 16700,
                'status_timbangan' => 'Normal',
                'xml_status'       => 'Generated',
                'status_sap'       => 'Pending',
                'sap_sent_at'      => null,
                'sap_doc_no'       => null,
                'created_at'       => '2026-08-10 14:05:00',
            ],
            [
                'nota_no'          => 'NT-PKS-TDN-20260810-008',
                'pks_name'         => 'PKS Tandun',
                'no_plat'          => 'BM 8540 ZK',
                'jam_masuk'        => '14:50:00',
                'jam_keluar'       => '15:20:00',
                'gross'            => 32100,
                'tarra'            => 10000,
                'netto'            => 22100,
                'status_timbangan' => 'Normal',
                'xml_status'       => 'Generated',
                'status_sap'       => 'Sent',
                'sap_sent_at'      => '2026-08-10 15:25:08',
                'sap_doc_no'       => 'SAP-PTPN4-9920218',
                'created_at'       => '2026-08-10 14:50:00',
            ],
            [
                'nota_no'          => 'NT-PKS-LBD-20260810-009',
                'pks_name'         => 'PKS Lubuk Dalam',
                'no_plat'          => 'BM 7192 TX',
                'jam_masuk'        => '15:30:00',
                'jam_keluar'       => '16:00:00',
                'gross'            => 23500,
                'tarra'            => 8800,
                'netto'            => 14700,
                'status_timbangan' => 'Mati/Offline',
                'xml_status'       => 'Generated',
                'status_sap'       => 'Pending',
                'sap_sent_at'      => null,
                'sap_doc_no'       => null,
                'created_at'       => '2026-08-10 15:30:00',
            ],
            [
                'nota_no'          => 'NT-PKS-TRT-20260810-010',
                'pks_name'         => 'PKS Terantam',
                'no_plat'          => 'BM 8903 KY',
                'jam_masuk'        => '16:15:00',
                'jam_keluar'       => '16:40:00',
                'gross'            => 28900,
                'tarra'            => 9400,
                'netto'            => 19500,
                'status_timbangan' => 'Normal',
                'xml_status'       => 'Generated',
                'status_sap'       => 'Sent',
                'sap_sent_at'      => '2026-08-10 16:45:19',
                'sap_doc_no'       => 'SAP-PTPN4-9920224',
                'created_at'       => '2026-08-10 16:15:00',
            ],
            [
                'nota_no'          => 'NT-PKS-TPT-20260810-011',
                'pks_name'         => 'PKS Tanah Putih',
                'no_plat'          => 'BM 9024 WL',
                'jam_masuk'        => '17:00:00',
                'jam_keluar'       => '17:30:00',
                'gross'            => 27100,
                'tarra'            => 9300,
                'netto'            => 17800,
                'status_timbangan' => 'Normal',
                'xml_status'       => 'Generated',
                'status_sap'       => 'Pending',
                'sap_sent_at'      => null,
                'sap_doc_no'       => null,
                'created_at'       => '2026-08-10 17:00:00',
            ],
            [
                'nota_no'          => 'NT-PKS-STP-20260810-012',
                'pks_name'         => 'PKS Sei Tapung',
                'no_plat'          => 'BM 8311 RS',
                'jam_masuk'        => '17:45:00',
                'jam_keluar'       => '18:15:00',
                'gross'            => 30500,
                'tarra'            => 9600,
                'netto'            => 20900,
                'status_timbangan' => 'Normal',
                'xml_status'       => 'Generated',
                'status_sap'       => 'Sent',
                'sap_sent_at'      => '2026-08-10 18:20:00',
                'sap_doc_no'       => 'SAP-PTPN4-9920231',
                'created_at'       => '2026-08-10 17:45:00',
            ],
        ];

        foreach ($transactions as $data) {
            $unit = $units->get($data['pks_name']);
            if (!$unit) {
                continue;
            }

            $trx = TransaksiTimbangan::updateOrCreate(
                ['nota_no' => $data['nota_no']],
                [
                    'unit_pks_id'      => $unit->id,
                    'no_plat'          => $data['no_plat'],
                    'jam_masuk'        => $data['jam_masuk'],
                    'jam_keluar'       => $data['jam_keluar'],
                    'gross'            => $data['gross'],
                    'tarra'            => $data['tarra'],
                    'netto'            => $data['netto'],
                    'status_timbangan' => $data['status_timbangan'],
                    'xml_status'       => $data['xml_status'],
                    'status_sap'       => $data['status_sap'],
                    'sap_sent_at'      => $data['sap_sent_at'],
                    'sap_doc_no'       => $data['sap_doc_no'],
                    'created_at'       => $data['created_at'],
                    'updated_at'       => $data['created_at'],
                ]
            );

            // Jika status SAP adalah Sent, buat/update log integrasi SAP terkait
            if ($data['status_sap'] === 'Sent' && !empty($data['sap_doc_no'])) {
                LogIntegrasiSap::updateOrCreate(
                    [
                        'transaksi_id'     => $trx->id,
                        'no_referensi_sap' => $data['sap_doc_no'],
                    ],
                    [
                        'waktu_kirim'      => $data['sap_sent_at'] ?? now(),
                        'status_respons'   => 'SUCCESS',
                        'kode_http'        => '200',
                    ]
                );
            }
        }
    }
}
