<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Transaksi;

class DashboardController extends Controller
{
    public function index()
    {
        $member = Member::count();
        $baru = Transaksi::where('status', '=' , 'Baru' )->count();        
        $proses = Transaksi::where('status', '=' , 'Proses' )->count();
        $pendapatan = Transaksi::where('dibayar' , '=' , 'dibayar')->sum('total_bayar');

        return response()->json([
            'member' => $member,
            'baru' => $baru,
            'proses' => $proses,
            'pendapatan' => $pendapatan,
        ]);
    }
}