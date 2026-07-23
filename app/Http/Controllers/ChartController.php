<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use Carbon\Carbon;

class ChartController extends Controller
{
    public function index() 
    {
        // Ambil semua data penjualan
        $sales = Sale::all();

        // Kelompokkan berdasarkan nama bulan menggunakan Carbon
        $grouped = $sales->groupBy(function ($sale) {
            return Carbon::parse($sale->created_at)->format('F'); // Contoh: 'January'
        });

        // Hitung total amount per bulan
        $labels = [];
        $data = [];

        foreach ($grouped as $month => $items) {
            $labels[] = $month;
            $data[] = $items->sum('amount');
        }

        return view('dashboard', compact('labels', 'data'));
    }
}