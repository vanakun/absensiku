<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Absensi;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
{
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        $user = Auth::user(); // Dapatkan objek user yang sedang login
        
        // Dapatkan alamat IP publik dari pengguna
        $ip = $this->getPublicIpAddress();
        
        // Dapatkan lokasi berdasarkan IP
        $location = $this->getLocationFromIP($ip);

        // Update informasi terakhir login
        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $ip,
            'last_login_location' => $location, // Pastikan kolom ini ada di tabel users
        ]);

        // Tambahkan logika absensi hanya jika user bukan admin
        if ($user->name !== 'admin') {
            $existingAbsensi = Absensi::where('tgl_presensi', Carbon::now()->toDateString())
                ->where('user_id', $user->id)
                ->exists();
        
            // Jika belum ada absensi, buat baru
            if (!$existingAbsensi) {
                $absensi = new Absensi();
                $absensi->tgl_presensi = Carbon::now()->toDateString();
                $absensi->lokasi_absen = $request->lokasi_absen;
                $absensi->jam_masuk = $request->jam_masuk;
                $absensi->jam_keluar = $request->jam_keluar;
                $absensi->status = 'belum absen'; // Default status
                $absensi->user_id = $user->id; // User ID
                $absensi->save();
            } else {
                // Jika sudah ada absensi, lakukan sesuai kebutuhan Anda (mungkin hanya perbarui data yang ada)
                // Misalnya:
                $absensi = Absensi::where('tgl_presensi', Carbon::now()->toDateString())
                    ->where('user_id', $user->id)
                    ->first();
                
                // Update data absensi jika diperlukan
                $absensi->save();
            }
        }

        // Redirect berdasarkan peran pengguna
        if ($user->name === 'admin') {
            return redirect()->route('admin.home');
        } elseif ($user->name === 'user') {
            return redirect()->route('user.home');
        }

        return redirect(RouteServiceProvider::HOME); // Redirect default jika peran tidak dikenali
    }

    throw ValidationException::withMessages([
        'email' => __('auth.failed'),
    ]);
}

    
    private function getPublicIpAddress()
    {
        $response = Http::get('http://httpbin.org/ip');
    
        if ($response->successful()) {
            $data = $response->json();
            return $data['origin'];
        }
    
        return request()->ip(); // Gunakan IP lokal jika tidak bisa mendapatkan IP publik
    }

    private function getLocationFromIP($ip)
    {
        $apiKey = env('IPINFO_API_KEY');
        $response = Http::withHeaders([
            'Authorization' => "Bearer {$apiKey}"
        ])->get("https://ipinfo.io/{$ip}/geo");

        if ($response->successful()) {
            $data = $response->json();
            
            // Memastikan kunci ada sebelum mencoba mengaksesnya
            $city = $data['city'] ?? 'Unknown City';
            $region = $data['region'] ?? 'Unknown Region';
            $country = $data['country'] ?? 'Unknown Country';
            
            return "{$city}, {$region}, {$country}";
        }

        return 'Unknown Location';
    }


    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
