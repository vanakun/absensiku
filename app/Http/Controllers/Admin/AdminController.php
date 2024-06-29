<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\User;
use DB;
use Illuminate\Http\Request;
class AdminController extends Controller
{
    public function settingsKantor()
    {
        $office1 = DB::table('kantors')->where('id', 1)->first();
        $office2 = DB::table('kantors')->where('id', 2)->first();
        $office3 = DB::table('kantors')->where('id', 3)->first();

        
        //dd($office1);
        return view('admin.pages.settings-kantor', compact('office1','office2','office3'));
    }

    public function updateAllKantor(Request $request)
    {
        DB::table('kantors')
            ->where('id', $request->input('office1_id'))
            ->update([
                'nama_kantor' => $request->input('office1_nama_kantor'),
                'lokasi_kantor' => $request->input('office1_lokasi_kantor'),
                'radius' => $request->input('office1_radius'),
            ]);
    
        DB::table('kantors')
            ->where('id', $request->input('office2_id'))
            ->update([
                'nama_kantor' => $request->input('office2_nama_kantor'),
                'lokasi_kantor' => $request->input('office2_lokasi_kantor'),
                'radius' => $request->input('office2_radius'),
            ]);
    
        DB::table('kantors')
            ->where('id', $request->input('office3_id'))
            ->update([
                'nama_kantor' => $request->input('office3_nama_kantor'),
                'lokasi_kantor' => $request->input('office3_lokasi_kantor'),
                'radius' => $request->input('office3_radius'),
            ]);
    
        return redirect()->back()->with('success', 'Data kantor berhasil diperbarui');
    }

    public function indexcuti()
{
   // Fetch all records where status is 'Menunggu Approve Cuti', ordered by the latest
   $cutiRequests = Absensi::where('status', 'Menunggu Approve Cuti')
   ->orderBy('created_at', 'asc')
   ->paginate(5);

// Fetch all records where status is 'Cuti' or 'Cuti Di Tolak', ordered by the latest
$cutiRequests1 = Absensi::whereIn('status', ['Cuti', 'Cuti Di Tolak'])
     ->orderBy('created_at', 'asc')
     ->paginate(5);

// Return the view with the fetched data
return view('admin.pages.cuti.index', compact('cutiRequests1', 'cutiRequests'));
}

        public function approve($id)
        {
            $cutiRequest = Absensi::findOrFail($id);
            $cutiRequest->status = 'Cuti';
            $cutiRequest->save();

            return redirect()->back()->with('success', 'Cuti request approved successfully.');
        }

        public function reject($id)
        {
        $cuti = Absensi::findOrFail($id);
        $cuti->update(['status' => 'Cuti Di Tolak']);
        return redirect()->back()->with('success', 'Cuti berhasil ditolak.');
        }

        public function indexizin()
        {
            // Fetch all records where status is 'Menunggu Approve Izin', ordered by the latest
            $izinRequest = Absensi::where('status', 'Menunggu Approve Izin')
                                  ->orderBy('created_at', 'desc')
                                  ->paginate(5);
        
            // Fetch all records where status is 'Izin', ordered by the latest
            $izinRequest1 = Absensi::wherein('status', ['Izin', 'izin Di Tolak'])
                                   ->orderBy('created_at', 'desc')
                                   ->paginate(5);
            
            // Return the view with the fetched data
            return view('admin.pages.izin.index', compact('izinRequest1', 'izinRequest'));
        }
        
        public function approveizin($id)
        {
            $izinRequest = Absensi::findOrFail($id);
            $izinRequest->status = 'Izin';
            $izinRequest->save();

            return redirect()->back()->with('success', 'Cuti request approved successfully.');
        }

        public function rejectizin($id)
        {
        $cuti = Absensi::findOrFail($id);
        $cuti->update(['status' => 'Izin Di Tolak']);
        return redirect()->back()->with('success', 'Cuti berhasil ditolak.');
        }

        public function generateAbsensiReport(Request $request)
        {
            $user_id = $request->input('user_id');
            $bulan_tahun = explode('-', $request->input('month'));
            $bulan = $bulan_tahun[1];
            $tahun = $bulan_tahun[0];
        
            
            // Mengambil data absensi berdasarkan user, bulan, dan tahun
            $absensi = Absensi::whereYear('tgl_presensi', $tahun)
                        ->whereMonth('tgl_presensi', $bulan)
                        ->where('user_id',  $user_id)
                        ->whereIn('status', ['cuti', 'terlambat', 'izin', 'tepat_waktu'])
                        ->orderBy('tgl_presensi', 'asc') // Urutkan dari yang paling lama
                        ->get();
        
                        //dd($absensi);
            $totalTepatWaktu = $absensi->where('status', 'tepat_waktu')->count();
            $totalTerlambat = $absensi->where('status', 'terlambat')->count();
            $totalCuti = $absensi->where('status', 'cuti')->count();
            $totalIzin = $absensi->where('status', 'izin')->count();
            return view('admin.pages.laporan.cetakpdf', compact('absensi', 'totalTerlambat', 'totalCuti', 'totalIzin', 'totalTepatWaktu'));
        }

        public function indexmisspunch()
        {
            // Fetch all records where status is 'Menunggu Approve Izin', ordered by the latest
            $izinRequest = Absensi::where('status', 'misspunch request')
                                  ->orderBy('created_at', 'desc')
                                  ->paginate(5);
        
            // Fetch all records where status is 'Izin', ordered by the latest
            $izinRequest1 = Absensi::wherein('status', ['misspunch approved', 'misspunch rejected'])
                                   ->orderBy('created_at', 'desc')
                                   ->paginate(5);
            
                                  // dd($izinRequest1);
            // Return the view with the fetched data
            return view('admin.pages.misspunch.index', compact('izinRequest1', 'izinRequest'));
        }
        
        public function approvemisspunch($id)
        {
            $izinRequest = Absensi::findOrFail($id);
            $izinRequest->status = 'misspunch approved';
            $izinRequest->save();

            return redirect()->back()->with('success', 'Cuti request approved successfully.');
        }

        public function rejectmisspunch($id)
        {
        $cuti = Absensi::findOrFail($id);
        $cuti->update(['status' => 'misspunch rejected']);
        return redirect()->back()->with('success', 'Cuti berhasil ditolak.');
        }

        

        public function generateAbsensiReportForm()
        {
            $users = User::all();
        return view('admin.pages.laporan.index', compact('users'));
        }
}
