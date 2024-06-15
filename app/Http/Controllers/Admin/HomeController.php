<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    /**
     * Provision a new web server.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        $totalUsers = User::count();
        $totalCutiMenungguApprove = Absensi::where('status', 'Menunggu Approve Cuti')->count();
        $totalIzinMenungguApprove = Absensi::where('status', 'Menunggu Approve Izin')->count();

            $totalAbsenHariIni = Absensi::whereDate('tgl_presensi', Carbon::today())
            ->whereNotIn('status', ['Menunggu Approve Izin', 'Menunggu Approve Cuti', 'Cuti', 'Izin'])
            ->count();

            $absenHariIni = Absensi::whereDate('tgl_presensi', Carbon::today())
            ->whereNotIn('status', ['Menunggu Approve Izin', 'Menunggu Approve Cuti'])
            ->paginate(5);

        return view('admin.index', compact('totalUsers', 'totalCutiMenungguApprove','totalIzinMenungguApprove','totalAbsenHariIni','absenHariIni'));
    }

   
}
