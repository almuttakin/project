<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use DOMDocument;

class BridgeController extends Controller
{
    private function getBridgeData()
    {
        return Session::get('bridge_logs', [
            [
                'id' => 1,
                'no_plat' => 'BM 8492 AU',
                'pks_loc' => 'PKS Pabatu',
                'vpn_ip' => '10.14.2.10',
                'jam_masuk' => '08:15',
                'jam_keluar' => '08:45',
                'gross' => 24500,
                'tarra' => 9200,
                'netto' => 15300,
                'status_timbangan' => 'Normal',
                'status_vpn' => 'Trouble',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'id' => 2,
                'no_plat' => 'BM 9102 CD',
                'pks_loc' => 'PKS Sawit Seberang',
                'vpn_ip' => '10.14.3.15',
                'jam_masuk' => '09:10',
                'jam_keluar' => '09:30',
                'gross' => 18200,
                'tarra' => 8100,
                'netto' => 10100,
                'status_timbangan' => 'Mati/Offline',
                'status_vpn' => 'Connected',
                'created_at' => date('Y-m-d H:i:s')
            ]
        ]);
    }

    public function index()
    {
        $logs = $this->getBridgeData();

        // Data dummy untuk Chart Penjualan / Tonase
        $labels = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni'];
        $data = [15000, 23000, 18000, 29000, 20000, 31000];

        return view('dashboard', compact('logs', 'labels', 'data'));
    }

    public function store(Request $request)
    {
        $logs = $this->getBridgeData();

        $gross = (float) $request->gross;
        $tarra = (float) $request->tarra;
        $netto = $gross - $tarra;

        $newLog = [
            'id' => time(),
            'no_plat' => strtoupper($request->no_plat),
            'pks_loc' => $request->pks_loc,
            'vpn_ip' => $request->vpn_ip,
            'jam_masuk' => $request->jam_masuk,
            'jam_keluar' => $request->jam_keluar,
            'gross' => $gross,
            'tarra' => $tarra,
            'netto' => $netto,
            'status_timbangan' => $request->status_timbangan,
            'status_vpn' => $request->status_vpn,
            'created_at' => date('Y-m-d H:i:s')
        ];

        array_unshift($logs, $newLog);
        Session::put('bridge_logs', $logs);

        return redirect('/#timbangan-tab')->with('success', 'Data Timbangan & Status Jaringan Berhasil Disimpan!');
    }

    public function destroy($id)
    {
        $logs = $this->getBridgeData();
        $logs = array_filter($logs, fn($item) => $item['id'] != $id);
        Session::put('bridge_logs', array_values($logs));

        return redirect()->back()->with('success', 'Data berhasil dihapus!');
    }

    public function generateXml($id = null)
    {
        $logs = $this->getBridgeData();

        // Jika ID spesifik dikirim, filter datanya saja
        if ($id) {
            $logs = array_filter($logs, fn($item) => $item['id'] == $id);
        }

        // Inisialisasi DOMDocument agar kustom Namespace & Prefix (s:, rs:, z:) bekerja
        $dom = new DOMDocument('1.0', 'utf-8');
        $dom->formatOutput = true;

        // Root Element <xml>
        $xmlRoot = $dom->createElement('xml');
        $xmlRoot->setAttribute('xmlns:s', 'uuid:BDC6E3F0-6DA3-11d1-A2A3-00AA00C14882');
        $xmlRoot->setAttribute('xmlns:dt', 'uuid:C2F41010-65B3-11d1-A29F-00AA00C14882');
        $xmlRoot->setAttribute('xmlns:rs', 'urn:schemas-microsoft-com:rowset');
        $xmlRoot->setAttribute('xmlns:z', '#RowsetSchema');
        $dom->appendChild($xmlRoot);

        // 1. Buat Bagian Schema (<s:Schema>)
        $schema = $dom->createElement('s:Schema');
        $schema->setAttribute('id', 'RowsetSchema');

        $elementType = $dom->createElement('s:ElementType');
        $elementType->setAttribute('name', 'row');
        $elementType->setAttribute('content', 'eltOnly');
        $elementType->setAttribute('rs:updatable', 'true');

        // Daftar Kolom Schema ADO Recordset
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

        // 2. Buat Bagian Data (<rs:data><rs:insert><z:row .../></rs:insert></rs:data>)
        $rsData = $dom->createElement('rs:data');
        $rsInsert = $dom->createElement('rs:insert');

        foreach ($logs as $log) {
            $zRow = $dom->createElement('z:row');

            $host = $log['vpn_ip'] ?? '10.14.2.10';
            $port = '3306';

            $zRow->setAttribute('TRM_ID', "{$host}:{$port}");
            $zRow->setAttribute('TRM_USED', '1');
            $zRow->setAttribute('TRM_CODE', 'N005-EP13-1');
            $zRow->setAttribute('TRM_INIT', '3F14-1');
            $zRow->setAttribute('TRM_DESC', $log['pks_loc'] ?? 'Pb. PPKR SLI');
            $zRow->setAttribute('TRM_HOST', $host);
            $zRow->setAttribute('TRM_PORT', $port);
            $zRow->setAttribute('TRM_DB', 'nama_database');
            $zRow->setAttribute('TRM_USER', 'uapp');
            $zRow->setAttribute('TRM_PASS', 'password_database');
            $zRow->setAttribute('TRM_STS_PING', ($log['status_vpn'] ?? '') == 'Connected' ? '4' : '0');
            $zRow->setAttribute('TRM_STS_PROCESS', '0');
            $zRow->setAttribute('TRM_STS_PRO_DB', '1');
            $zRow->setAttribute('TRM_STS_PRO_FILE', '0');
            $zRow->setAttribute('TRM_STS_PRO_FTP', '0');
            $zRow->setAttribute('TRM_PROG_MAX', '0');
            $zRow->setAttribute('TRM_PROG_VAL', '0');
            $zRow->setAttribute('TRM_PROG', '0');
            $zRow->setAttribute('TRM_START', date('Y-m-d\TH:i:s'));
            $zRow->setAttribute('TRM_END', date('Y-m-d\TH:i:s'));
            $zRow->setAttribute('TRM_LAST_PROC', date('Y-m-d\TH:i:s'));

            $rsInsert->appendChild($zRow);
        }

        $rsData->appendChild($rsInsert);
        $xmlRoot->appendChild($rsData);

        return response($dom->saveXML(), 200)
            ->header('Content-Type', 'text/xml')
            ->header('Content-Disposition', 'attachment; filename="Terminal_Config_' . date('Ymd_His') . '.xml"');
    }
}