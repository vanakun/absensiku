<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Auth;
use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use mPDF;

class UserController extends Controller
{
    public function index()
{

    $office1 = DB::table('kantors')->where('id', 1)->first();
    $office2 = DB::table('kantors')->where('id', 2)->first();
    $office3 = DB::table('kantors')->where('id', 3)->first();

    $hariini = date("Y-m-d");
    $user_id = Auth::id(); // Mengambil ID pengguna yang sedang diautentikasi

    // Memeriksa apakah pengguna sudah absen masuk hari ini
    $absenMasuk = DB::table('absensis')
        ->where('tgl_presensi', $hariini)
        ->where('user_id', $user_id)
        ->whereNotNull('jam_masuk')
        ->exists();

    // Mengambil data absen masuk untuk user saat ini
    $absen = Absensi::where('user_id', $user_id)
                    ->whereDate('tgl_presensi', $hariini)
                    ->first();

    // Mendapatkan bulan dan tahun saat ini
    $now = Carbon::now();
    $month = $now->month;
    $year = $now->year;

    $user_id = auth()->user()->id; // Mengambil ID pengguna yang sedang diautentikasi
    $absensis = Absensi::where('user_id', $user_id)
                        ->whereYear('tgl_presensi', $year)
                        ->whereMonth('tgl_presensi', $month)
                        ->where('user_id', auth()->user()->id)
                        ->whereIn('status', ['terlambat', 'tepat_waktu','Belum Absen'])
                        ->orderBy('tgl_presensi', 'desc') // Urutkan dari yang paling lama
                        ->paginate(5);

        $jam_masuk = null;
        $jam_keluar = null;

        // Jika data absen ditemukan, ambil jam masuk dan keluar
        if ($absen) {
            $jam_masuk = $absen->jam_masuk;
            $jam_keluar = $absen->jam_keluar;
        }

    return view('user.home', compact('year','month','user_id','absensis','office1','office2','office3','absenMasuk', 'jam_masuk', 'jam_keluar'));
}






public function printPDF()
{
    $now = now();
    $month = $now->month;
    $year = $now->year;

    $monthName = date("F", mktime(0, 0, 0, $month, 10));

    $absensis = Absensi::whereYear('tgl_presensi', $year)
                        ->whereMonth('tgl_presensi', $month)
                        ->where('user_id', auth()->user()->id)
                        ->whereIn('status', ['cuti', 'terlambat', 'izin','tepat_waktu'])
                        ->orderBy('tgl_presensi', 'asc') // Urutkan dari yang paling lama
                        ->get();

    $totaltepatwaktu = $absensis->where('status', 'tepat_waktu')->count();
    $totalTerlambat = $absensis->where('status', 'terlambat')->count();
    $totalCuti = $absensis->where('status', 'Cuti')->count();
    $totalIzin = $absensis->where('status', 'Izin')->count();

    return view('user.cetakpdf', compact('absensis', 'monthName', 'year', 'totalTerlambat', 'totalCuti', 'totalIzin','totaltepatwaktu'));
}


        
    


    public function timestamp()
    {
        return view('user.timestamp');
    }

    public function leave()
    {
    $now = now();
    $month = $now->month;
    $year = $now->year;

    $absensis = Absensi::whereYear('tgl_presensi', $year)
                        ->whereMonth('tgl_presensi', $month)
                        ->where('user_id', auth()->user()->id)
                        ->whereIn('status', ['cuti','Menunggu Approve Cuti'])
                        ->orderBy('tgl_presensi', 'asc') // Urutkan dari yang paling lama
                        ->paginate(4);
        return view('user.myleave',compact('absensis'));
    }
    public function Createleave()
    {
        return view('user.leave');
    }
    public function StoreCuti(Request $request)
    {
        $request->validate([
            'tgl_presensi' => 'required|date',
            'lokasi_absen' => 'nullable|string',
            'jam_masuk' => 'nullable|date_format:H:i',
            'jam_keluar' => 'nullable|date_format:H:i|after:jam_masuk',
        ]);
    
        // Memeriksa apakah tanggal presensi sudah ada
        $existingAbsensi = Absensi::where('tgl_presensi', $request->tgl_presensi)
                                    ->where('user_id', auth()->id())
                                    ->exists();
    
        if ($existingAbsensi) {
            return redirect()->back()->with('error', 'Absensi untuk tanggal tersebut sudah ada.');
        }
    
        $absensi = new Absensi();
        $absensi->tgl_presensi = $request->tgl_presensi;
        $absensi->lokasi_absen = $request->lokasi_absen;
        $absensi->jam_masuk = $request->jam_masuk;
        $absensi->jam_keluar = $request->jam_keluar;
        $absensi->status = 'Menunggu Approve Cuti'; // Default status
        $absensi->user_id = auth()->id(); // Assuming you have authentication
    
        $absensi->save();
    
        return redirect()->route('user.leave')->with('success', 'Absensi berhasil disimpan.');
    }

    public function Createizin()
    {
        return view('user.izin');
    }

    public function izin()
    {
    $now = now();
    $month = $now->month;
    $year = $now->year;

    $absensis = Absensi::whereYear('tgl_presensi', $year)
                        ->whereMonth('tgl_presensi', $month)
                        ->where('user_id', auth()->user()->id)
                        ->whereIn('status', ['Izin','Menunggu Approve Izin','Izin Di Tolak'])
                        ->orderBy('tgl_presensi', 'asc') // Urutkan dari yang paling lama
                        ->get();
        return view('user.myizin',compact('absensis'));
    }

    public function storeIzin(Request $request)
{
    $request->validate([
        'tgl_presensi' => 'required|date',
        'lokasi_absen' => 'nullable|string',
        'reason' => 'nullable|string',
        'jam_masuk' => 'nullable|date_format:H:i',
        'jam_keluar' => 'nullable|date_format:H:i|after:jam_masuk',
        'surat_izin' => 'nullable|file|mimes:pdf|max:10240',
    ]);

    // Memeriksa apakah tanggal presensi sudah ada
    $existingAbsensi = Absensi::where('tgl_presensi', $request->tgl_presensi)
                                ->where('user_id', auth()->id())
                                ->exists();

    if ($existingAbsensi) {
        return redirect()->back()->with('error', 'Absensi untuk tanggal tersebut sudah ada.');
    }

    $absensi = new Absensi();
    $absensi->tgl_presensi = $request->tgl_presensi;
    $absensi->lokasi_absen = $request->lokasi_absen;
    $absensi->reason = $request->reason;
    $absensi->jam_masuk = $request->jam_masuk;
    $absensi->jam_keluar = $request->jam_keluar;
    $absensi->status = 'Menunggu Approve Izin'; // Default status
    $absensi->user_id = auth()->id(); // Assuming you have authentication

    // Mengelola file surat izin
    if ($request->hasFile('surat_izin')) {
        $file = $request->file('surat_izin');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('public/surat_izin', $fileName); // Simpan file di storage 'public/surat_izin'
        $absensi->surat_izin = $fileName; // Simpan nama file ke dalam objek Absensi
    }

    $absensi->save();

    return redirect()->route('user.leave')->with('success', 'Absensi berhasil disimpan.');
}

    

    public function misspunch()
    {
    $now = now();
    $month = $now->month;
    $year = $now->year;

    $absensis = Absensi::whereYear('tgl_presensi', $year)
                        ->whereMonth('tgl_presensi', $month)
                        ->where('user_id', auth()->user()->id)
                        ->whereIn('status', ['Belum absen','misspunch approved','misspunch request','misspunch rejected'])
                        ->orderBy('tgl_presensi', 'asc') // Urutkan dari yang paling lama
                        ->get();
        return view('user.misspunch',compact('absensis'));
    }

    public function updateStatus(Request $request)
{
    $misspunchRequests = $request->input('misspunch_requests', []);
    $reasons = $request->input('reasons', []);
    $jamMasuk = $request->input('jam_masuk', []);
    $jamKeluar = $request->input('jam_keluar', []);

    foreach ($misspunchRequests as $id) {
        $absen = Absensi::find($id);
        if ($absen) {
            // Check if more than one day has passed and status is not already 'Misspunch request'
            if ($absen->status !== 'Misspunch request' && now()->diffInDays($absen->tgl_presensi) >= 1) {
                $absen->status = 'Misspunch request';
                $absen->reason = $reasons[$id] ?? ''; // Update the reason
                $absen->jam_masuk = $jamMasuk[$id] ?? $absen->jam_masuk; // Update jam masuk
                $absen->jam_keluar = $jamKeluar[$id] ?? $absen->jam_keluar; // Update jam keluar
                $absen->save();
            }
        }
    }

    return redirect()->back()->with('success', 'Status updated successfully.');
}
}
