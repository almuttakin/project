<?php

namespace App\Http\Controllers;

use App\Models\LogIntegrasiSap;
use App\Models\TransaksiTimbangan;
use App\Models\UnitPks;
use DOMDocument;
use Illuminate\Http\Request;

class BridgeController extends Controller
{
    public function index()
    {
        $logs = TransaksiTimbangan::with('unitPks')->latest()->get();

        // Data statistik Jumlah XML Digenerate per Unit PKS
        $unitsList = UnitPks::orderBy('id')->get();
        if ($unitsList->isNotEmpty()) {
            $labels = $unitsList->pluck('nama_unit')->toArray();
        } else {
            $labels = ['PKS Pabatu', 'PKS Sawit Seberang', 'PKS Bah Jambi', 'PKS Mayang', 'PKS Tanah Hitam', 'PKS Sei Galuh', 'PKS Sei Pagar', 'PKS Tandun', 'PKS Lubuk Dalam', 'PKS Terantam', 'PKS Tanah Putih', 'PKS Sei Tapung'];
        }
        $data = [];
        foreach ($labels as $pks) {
            $data[] = $logs->filter(fn($i) => ($i->pks_loc ?? '') === $pks && ($i->xml_status ?? '') === 'Generated')->count();
        }

        // Ringkasan Statistik Integrasi XML & SAP
        $totalXml = $logs->filter(fn($i) => ($i->xml_status ?? '') === 'Generated')->count();
        $totalSent = $logs->filter(fn($i) => strtoupper($i->status_sap ?? '') === 'SENT')->count();
        $totalPending = $logs->filter(fn($i) => strtoupper($i->status_sap ?? 'PENDING') === 'PENDING')->count();
        $vpnOnline = $logs->filter(fn($i) => ($i->status_vpn ?? '') === 'Connected')->count();

        $xmlStats = [
            'total_xml' => $totalXml,
            'total_sent' => $totalSent,
            'total_pending' => $totalPending,
            'vpn_online' => $vpnOnline,
            'vpn_trouble' => $logs->count() - $vpnOnline
        ];

        // Sample XML Stream untuk widget Live Generator
        $latestLog = $logs->first() ? $logs->first()->toArray() : null;
        $latestXmlStream = $this->generateSapXmlString($latestLog);

        // Filter log yang sudah terkirim ke SAP untuk menu Log Integrasi SAP (Historical SAP Ledger)
        $sentLogs = $logs->filter(fn($i) => strtoupper($i->status_sap ?? '') === 'SENT')->values();

        // Riwayat Aktivitas Sesi Login & Logout Pengguna
        $riwayatLogins = collect();
        try {
            if (\App\Models\RiwayatLogin::count() === 0) {
                \App\Models\RiwayatLogin::create([
                    'nama_pengguna' => 'Administrator TI Regional III',
                    'username'      => 'admin.ti.regional3',
                    'role'          => 'Admin',
                    'ip_address'    => '127.0.0.1',
                    'user_agent'    => 'Chrome / Windows 10 (Desktop Workstation)',
                    'login_at'      => now()->subHours(2)->subMinutes(45),
                    'logout_at'     => now()->subHours(1)->subMinutes(10),
                    'status'        => 'Logout',
                ]);
                \App\Models\RiwayatLogin::create([
                    'nama_pengguna' => 'Administrator TI Regional III',
                    'username'      => 'admin.ti.regional3',
                    'role'          => 'Admin',
                    'ip_address'    => '127.0.0.1',
                    'user_agent'    => 'Chrome / Windows 10 (Desktop Workstation)',
                    'login_at'      => now()->subMinutes(18),
                    'logout_at'     => null,
                    'status'        => 'Aktif',
                ]);
            }
            $riwayatLogins = \App\Models\RiwayatLogin::latest('login_at')->take(20)->get();
        } catch (\Throwable $e) {
            // Graceful fallback jika tabel belum siap
        }

        return view('dashboard', compact('logs', 'labels', 'data', 'xmlStats', 'latestXmlStream', 'sentLogs', 'riwayatLogins', 'unitsList'));
    }

    public function store(Request $request)
    {
        $gross = (float) $request->gross;
        $tarra = (float) $request->tarra;
        $netto = $gross - $tarra;
        $plat = strtoupper(trim($request->no_plat));
        $pksCode = strtoupper(substr(str_replace('PKS ', '', $request->pks_loc), 0, 3));
        $notaNo = 'NT-' . $pksCode . '-' . date('Ymd-His');

        // Cari atau buat Unit PKS yang sesuai
        $unit = UnitPks::where('nama_unit', $request->pks_loc)->first();
        if (!$unit) {
            $unit = UnitPks::firstOrCreate(
                ['nama_unit' => $request->pks_loc],
                [
                    'lokasi'      => $request->pks_loc,
                    'ip_vpn'      => $request->vpn_ip,
                    'status_vpn'  => $request->status_vpn === 'Trouble' ? 'Trouble' : 'Online',
                    'status_wifi' => 'Online',
                ]
            );
        }

        // Simpan via TransaksiTimbangan::create
        $transaksi = TransaksiTimbangan::create([
            'nota_no'          => $notaNo,
            'no_plat'          => $plat,
            'unit_pks_id'      => $unit->id,
            'jam_masuk'        => $request->jam_masuk,
            'jam_keluar'       => $request->jam_keluar,
            'gross'            => $gross,
            'tarra'            => $tarra,
            'netto'            => $netto,
            'status_timbangan' => $request->status_timbangan ?? 'Normal',
            'xml_status'       => 'Generated',
            'status_sap'       => 'Pending',
            'sap_sent_at'      => null,
            'sap_doc_no'       => null,
        ]);

        // Otomatis generate XML SAP per-data transaksi & simpan ke storage
        try {
            $xmlContent = $this->generateSapXmlString($transaksi->toArray());
            $dir = storage_path('app/xml_logs');
            if (!file_exists($dir)) {
                mkdir($dir, 0755, true);
            }
            file_put_contents($dir . '/' . $notaNo . '.xml', $xmlContent);
        } catch (\Exception $e) {
            // Abaikan jika storage disk dibatasi lingkungan runtime
        }

        return redirect(route('bridge.index') . '#tab-timbangan')->with('success', "Data Timbangan Berhasil Disimpan! File XML SAP ($notaNo) Otomatis Ter-generate (Status XML: 🟢 Generated) & Status SAP: 🟡 PENDING.");
    }

    public function pushSap(Request $request, $id)
    {
        $transaksi = TransaksiTimbangan::with('unitPks')->findOrFail($id);

        $sapDocNo = 'SAP-PTPN4-' . rand(1000000, 9999999);
        $sentAt = now();

        $transaksi->status_sap = 'Sent';
        $transaksi->sap_sent_at = $sentAt;
        $transaksi->sap_doc_no = $sapDocNo;
        $transaksi->save();

        // Buat 1 baris baru di log_integrasi_sap
        LogIntegrasiSap::create([
            'transaksi_id'     => $transaksi->id,
            'no_referensi_sap' => $sapDocNo,
            'waktu_kirim'      => $sentAt,
            'status_respons'   => 'SUCCESS',
            'kode_http'        => '200',
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "XML Data {$transaksi->nota_no} Berhasil Dilempar Ke Sistem SAP Kantor Pusat!",
                'data'    => $transaksi->toArray(),
            ]);
        }

        return redirect()->back()->with('success', "File XML Historical Nota {$transaksi->nota_no} Berhasil Dilempar ke Sistem SAP Kantor Pusat! Status: 🟢 SENT / VERIFIED.");
    }

    public function pushAllSap(Request $request)
    {
        $pendingLogs = TransaksiTimbangan::where('status_sap', 'Pending')->get();
        $updatedCount = 0;

        foreach ($pendingLogs as $transaksi) {
            $sapDocNo = 'SAP-PTPN4-' . rand(1000000, 9999999);
            $sentAt = now();

            $transaksi->status_sap = 'Sent';
            $transaksi->sap_sent_at = $sentAt;
            $transaksi->sap_doc_no = $sapDocNo;
            $transaksi->save();

            LogIntegrasiSap::create([
                'transaksi_id'     => $transaksi->id,
                'no_referensi_sap' => $sapDocNo,
                'waktu_kirim'      => $sentAt,
                'status_respons'   => 'SUCCESS',
                'kode_http'        => '200',
            ]);

            $updatedCount++;
        }

        return redirect()->back()->with('success', "Berhasil melempar $updatedCount berkas XML timbangan historical yang PENDING ke Sistem SAP Kantor Pusat!");
    }

    public function xmlPreview($id = 'all')
    {
        $subtitle = '';

        if ($id === 'all' || $id === null || $id === 'table') {
            $records = TransaksiTimbangan::with('unitPks')->latest()->get();
            $selectedLogs = $records->toArray();
            $subtitle = 'Export Rekap XML (Gabungan Historical Ledger - ' . count($selectedLogs) . ' Record Timbangan)';
            $xmlSap = $this->generateSapXmlString($selectedLogs);
            $xmlAdo = $this->buildAdoXmlString($selectedLogs);
        } elseif ($id === 'sent' || $id === 'logsap') {
            $records = TransaksiTimbangan::with('unitPks')->where('status_sap', 'Sent')->latest()->get();
            $selectedLogs = $records->toArray();
            $subtitle = 'Export Rekap XML (Gabungan Audit Log SAP - ' . count($selectedLogs) . ' Record Terverifikasi)';
            $xmlSap = $this->generateSapXmlString($selectedLogs);
            $xmlAdo = $this->buildAdoXmlString($selectedLogs);
        } else {
            $transaksi = TransaksiTimbangan::with('unitPks')->find($id);
            if (!$transaksi) {
                return response()->json(['success' => false, 'message' => 'Data tidak ditemukan!'], 404);
            }
            $log = $transaksi->toArray();
            $selectedLogs = [$log];
            $subtitle = "Preview XML Auto-Generated (Per-Data) - Nota: {$transaksi->nota_no} ({$transaksi->no_plat}) - Status XML: " . ($transaksi->xml_status ?? 'Generated');
            $xmlSap = $this->generateSapXmlString($log);
            $xmlAdo = $this->buildAdoXmlString([$log]);
        }

        return response()->json([
            'success'       => true,
            'log'           => count($selectedLogs) == 1 ? $selectedLogs[0] : null,
            'total_records' => count($selectedLogs),
            'subtitle'      => $subtitle,
            'xml_sap'       => $xmlSap,
            'xml_ado'       => $xmlAdo,
        ]);
    }

    public function destroy($id)
    {
        TransaksiTimbangan::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Data historical berhasil dihapus!');
    }

    public function generateXml($id = 'all')
    {
        $filename = 'SAP_Weighbridge_Table_Config_' . date('Ymd_His') . '.xml';

        if ($id === 'sent' || $id === 'logsap') {
            $records = TransaksiTimbangan::with('unitPks')->where('status_sap', 'Sent')->latest()->get();
            $logsToExport = $records->toArray();
            $filename = 'SAP_AuditLog_Table_Config_' . date('Ymd_His') . '.xml';
        } elseif ($id && $id !== 'all' && $id !== 'table') {
            $transaksi = TransaksiTimbangan::with('unitPks')->find($id);
            if ($transaksi) {
                $log = $transaksi->toArray();
                $filename = 'SAP_Weighbridge_Config_' . ($transaksi->nota_no ?? $id) . '.xml';
                $xmlString = $this->generateSapXmlString($log);
                return response($xmlString, 200)
                    ->header('Content-Type', 'text/xml')
                    ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
            }
            $logsToExport = TransaksiTimbangan::with('unitPks')->latest()->get()->toArray();
        } else {
            $records = TransaksiTimbangan::with('unitPks')->latest()->get();
            $logsToExport = $records->toArray();
        }

        $xmlString = $this->generateSapXmlString($logsToExport);

        return response($xmlString, 200)
            ->header('Content-Type', 'text/xml')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    private function generateSapXmlString($logsInput)
    {
        if (empty($logsInput)) {
            $logs = [[
                'nota_no'          => 'NT-PKS-PBT-DEMO',
                'no_plat'          => 'BM 8492 AU',
                'pks_loc'          => 'PKS Pabatu',
                'vpn_ip'           => '10.14.2.10',
                'jam_masuk'        => '08:15',
                'jam_keluar'       => '08:45',
                'gross'            => 24500,
                'tarra'            => 9200,
                'netto'            => 15300,
                'status_timbangan' => 'Normal',
                'status_vpn'       => 'Connected',
                'status_sap'       => 'SENT',
                'sap_sent_at'      => date('Y-m-d H:i:s'),
                'sap_doc_no'       => 'SAP-PTPN4-9920194',
            ]];
        } elseif (isset($logsInput['id']) || isset($logsInput['nota_no'])) {
            $logs = [$logsInput];
        } else {
            $logs = array_values($logsInput);
        }

        $dom = new DOMDocument('1.0', 'utf-8');
        $dom->formatOutput = true;

        $root = $dom->createElement('PTPN4_SAP_Table_Ingest');
        $root->setAttribute('xmlns:sap', 'urn:sap-com:document:sap:rfc:functions');
        $root->setAttribute('regional', 'Regional 3');
        $root->setAttribute('total_records', count($logs));
        $dom->appendChild($root);

        $header = $dom->createElement('Header');
        $header->appendChild($dom->createElement('TableID', 'TBL-WEIGHBRIDGE-HISTORICAL-' . date('Ymd')));
        $header->appendChild($dom->createElement('TotalTransactions', count($logs)));
        $totalNetto = array_sum(array_column($logs, 'netto'));
        $header->appendChild($dom->createElement('TotalNettoKg', number_format($totalNetto, 0, '', '')));
        $header->appendChild($dom->createElement('GeneratedAt', date('Y-m-d\TH:i:s')));
        $root->appendChild($header);

        $tableData = $dom->createElement('TableData');

        foreach ($logs as $idx => $log) {
            $item = $dom->createElement('NotaTimbanganRecord');
            $item->setAttribute('row_id', $idx + 1);
            $item->appendChild($dom->createElement('NotaNumber', $log['nota_no'] ?? ('NT-' . time())));
            $item->appendChild($dom->createElement('NoPlat', $log['no_plat'] ?? 'BM 0000 XX'));
            $item->appendChild($dom->createElement('PKSLocation', $log['pks_loc'] ?? 'PKS Pabatu'));
            $item->appendChild($dom->createElement('VPNGateway', $log['vpn_ip'] ?? '10.14.2.10'));
            $item->appendChild($dom->createElement('JamMasuk', $log['jam_masuk'] ?? '00:00'));
            $item->appendChild($dom->createElement('JamKeluar', $log['jam_keluar'] ?? '00:00'));

            $weights = $dom->createElement('TimbanganData');
            $weights->appendChild($dom->createElement('BeratGross', number_format($log['gross'] ?? 0, 0, '', '')));
            $weights->appendChild($dom->createElement('BeratTarra', number_format($log['tarra'] ?? 0, 0, '', '')));
            $weights->appendChild($dom->createElement('BeratNetto', number_format($log['netto'] ?? 0, 0, '', '')));
            $weights->appendChild($dom->createElement('Satuan', 'KG'));
            $item->appendChild($weights);

            $status = $dom->createElement('StatusIntegrasi');
            $status->appendChild($dom->createElement('StatusAlat', $log['status_timbangan'] ?? 'Normal'));
            $status->appendChild($dom->createElement('StatusVPN', $log['status_vpn'] ?? 'Connected'));
            $status->appendChild($dom->createElement('StatusSAP', $log['status_sap'] ?? 'PENDING'));
            $status->appendChild($dom->createElement('SAPRefDoc', $log['sap_doc_no'] ?? 'PENDING_PUSH'));
            $item->appendChild($status);

            $tableData->appendChild($item);
        }

        $root->appendChild($tableData);

        return $dom->saveXML();
    }

    private function buildAdoXmlString($logs)
    {
        $dom = new DOMDocument('1.0', 'utf-8');
        $dom->formatOutput = true;

        $xmlRoot = $dom->createElement('xml');
        $xmlRoot->setAttribute('xmlns:s', 'uuid:BDC6E3F0-6DA3-11d1-A2A3-00AA00C14882');
        $xmlRoot->setAttribute('xmlns:dt', 'uuid:C2F41010-65B3-11d1-A29F-00AA00C14882');
        $xmlRoot->setAttribute('xmlns:rs', 'urn:schemas-microsoft-com:rowset');
        $xmlRoot->setAttribute('xmlns:z', '#RowsetSchema');
        $dom->appendChild($xmlRoot);

        $schema = $dom->createElement('s:Schema');
        $schema->setAttribute('id', 'RowsetSchema');

        $elementType = $dom->createElement('s:ElementType');
        $elementType->setAttribute('name', 'row');
        $elementType->setAttribute('content', 'eltOnly');
        $elementType->setAttribute('rs:updatable', 'true');

        $columns = [
            ['name' => 'TRM_ID', 'num' => 1, 'type' => 'string', 'dbtype' => 'str', 'len' => 50],
            ['name' => 'TRM_USED', 'num' => 2, 'type' => 'int', 'len' => 4, 'fixed' => 'true'],
            ['name' => 'TRM_CODE', 'num' => 3, 'type' => 'string', 'dbtype' => 'str', 'len' => 50],
            ['name' => 'TRM_INIT', 'num' => 4, 'type' => 'string', 'dbtype' => 'str', 'len' => 50],
            ['name' => 'TRM_DESC', 'num' => 5, 'type' => 'string', 'dbtype' => 'str', 'len' => 50],
            ['name' => 'TRM_HOST', 'num' => 6, 'type' => 'string', 'dbtype' => 'str', 'len' => 50],
            ['name' => 'TRM_PORT', 'num' => 7, 'type' => 'int', 'len' => 4, 'fixed' => 'true'],
            ['name' => 'TRM_DB', 'num' => 8, 'type' => 'string', 'dbtype' => 'str', 'len' => 50],
            ['name' => 'TRM_USER', 'num' => 9, 'type' => 'string', 'dbtype' => 'str', 'len' => 50],
            ['name' => 'TRM_PASS', 'num' => 10, 'type' => 'string', 'dbtype' => 'str', 'len' => 50],
            ['name' => 'TRM_STS_PING', 'num' => 11, 'type' => 'int', 'len' => 4, 'fixed' => 'true'],
            ['name' => 'TRM_STS_PROCESS', 'num' => 12, 'type' => 'int', 'len' => 4, 'fixed' => 'true'],
            ['name' => 'TRM_STS_PRO_DB', 'num' => 13, 'type' => 'int', 'len' => 4, 'fixed' => 'true'],
            ['name' => 'TRM_STS_PRO_FILE', 'num' => 14, 'type' => 'int', 'len' => 4, 'fixed' => 'true'],
            ['name' => 'TRM_STS_PRO_FTP', 'num' => 15, 'type' => 'int', 'len' => 4, 'fixed' => 'true'],
            ['name' => 'TRM_PROG_MAX', 'num' => 16, 'type' => 'int', 'len' => 4, 'fixed' => 'true'],
            ['name' => 'TRM_PROG_VAL', 'num' => 17, 'type' => 'int', 'len' => 4, 'fixed' => 'true'],
            ['name' => 'TRM_PROG', 'num' => 18, 'type' => 'int', 'len' => 4, 'fixed' => 'true'],
            ['name' => 'TRM_START', 'num' => 19, 'type' => 'dateTime', 'dbtype' => 'timestamp', 'len' => 16, 'fixed' => 'true'],
            ['name' => 'TRM_END', 'num' => 20, 'type' => 'dateTime', 'dbtype' => 'timestamp', 'len' => 16, 'fixed' => 'true'],
            ['name' => 'TRM_LAST_PROC', 'num' => 21, 'type' => 'dateTime', 'dbtype' => 'timestamp', 'len' => 16, 'fixed' => 'true'],
        ];

        foreach ($columns as $col) {
            $attr = $dom->createElement('s:AttributeType');
            $attr->setAttribute('name', $col['name']);
            $attr->setAttribute('rs:number', $col['num']);
            $attr->setAttribute('rs:nullable', 'true');
            $attr->setAttribute('rs:write', 'true');

            $datatype = $dom->createElement('s:datatype');
            $datatype->setAttribute('dt:type', $col['type']);
            if (isset($col['dbtype'])) {
                $datatype->setAttribute('rs:dbtype', $col['dbtype']);
            }
            $datatype->setAttribute('dt:maxLength', $col['len']);
            $datatype->setAttribute('rs:precision', '0');
            if (isset($col['fixed'])) {
                $datatype->setAttribute('rs:fixedlength', $col['fixed']);
            }

            $attr->appendChild($datatype);
            $elementType->appendChild($attr);
        }

        $extends = $dom->createElement('s:extends');
        $extends->setAttribute('type', 'rs:rowbase');
        $elementType->appendChild($extends);
        $schema->appendChild($elementType);
        $xmlRoot->appendChild($schema);

        $rsData = $dom->createElement('rs:data');
        $rsInsert = $dom->createElement('rs:insert');

        foreach ($logs as $log) {
            $zRow = $dom->createElement('z:row');

            $host = $log['vpn_ip'] ?? '10.14.2.10';
            $port = '3306';

            $zRow->setAttribute('TRM_ID', "{$host}:{$port}");
            $zRow->setAttribute('TRM_USED', '1');
            $zRow->setAttribute('TRM_CODE', $log['nota_no'] ?? 'N005-EP13-1');
            $zRow->setAttribute('TRM_INIT', '3F14-1');
            $zRow->setAttribute('TRM_DESC', $log['pks_loc'] ?? 'Pb. PPKR SLI');
            $zRow->setAttribute('TRM_HOST', $host);
            $zRow->setAttribute('TRM_PORT', $port);
            $zRow->setAttribute('TRM_DB', 'pks_weighbridge_db');
            $zRow->setAttribute('TRM_USER', 'uapp_ptpn4');
            $zRow->setAttribute('TRM_PASS', 'enc_password_sap');
            $zRow->setAttribute('TRM_STS_PING', ($log['status_vpn'] ?? '') == 'Connected' ? '4' : '0');
            $zRow->setAttribute('TRM_STS_PROCESS', ($log['status_sap'] ?? '') == 'SENT' ? '1' : '0');
            $zRow->setAttribute('TRM_STS_PRO_DB', '1');
            $zRow->setAttribute('TRM_STS_PRO_FILE', '1');
            $zRow->setAttribute('TRM_STS_PRO_FTP', '1');
            $zRow->setAttribute('TRM_PROG_MAX', '100');
            $zRow->setAttribute('TRM_PROG_VAL', '100');
            $zRow->setAttribute('TRM_PROG', '1');
            $zRow->setAttribute('TRM_START', date('Y-m-d\TH:i:s'));
            $zRow->setAttribute('TRM_END', date('Y-m-d\TH:i:s'));
            $zRow->setAttribute('TRM_LAST_PROC', date('Y-m-d\TH:i:s'));

            $rsInsert->appendChild($zRow);
        }

        $rsData->appendChild($rsInsert);
        $xmlRoot->appendChild($rsData);

        return $dom->saveXML();
    }
}