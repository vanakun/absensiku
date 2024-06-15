<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Absensi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.16/tailwind.min.css">
    <style>
        .kop-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
           
        }

        .dhl-info {
            text-align: right;
        }

        .dhl-info h2 {
            font-size: 1.5rem;
            margin-bottom: 5px;
        }

        .dhl-info p {
            margin: 0;
        }

        .logo {
            max-width: 200px;
        }

        /* Custom style for A4 paper size */
        @media print {
            body {
                width: 210mm;
                height: 297mm;
                /* to centre page on screen */
                margin-left: auto;
                margin-right: auto;
            }
        }

        /* Surat resmi styling */
        .surat-container {
            margin-top: 20px;
            max-width: 800px;
            margin: auto;
            padding: 20px;
            border: 2px solid #000;
        }

        .kop-container {
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .garis {
            border-top: 1px solid #000;
            margin-bottom: 20px;
        }

        .header-surat {
            margin-bottom: 20px;
        }

        .header-surat h1 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: bold;
            text-align: center;
        }

        .info-user {
            margin-bottom: 20px;
        }

        .info-user h3 {
            font-size: 0.8rem;
            margin: 0;
            font-weight: normal;
        }

        .info-user h3:last-child {
            margin-top: 10px;
        }

        .info-user p {
            margin: 0;
        }

        .info-total {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .info-total h2 {
            font-size: 1.2rem;
            font-weight: bold;
            margin: 0;
        }

        .info-total h2:last-child {
            text-align: right;
        }

       

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .button-container {
            text-align: right;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="surat-container">
        <div class="kop-container">
        <div class="logo">
            <img src="{{ asset('assets/img/DHL-Logo.png') }}" alt="DHL Logo">
        </div>
            <div class="dhl-info">
                <h2>DHL Surabaya</h2>
                <p>Jl. DHL No. 123, Surabaya</p>
                <p>Telp: (031) 123456</p>
            </div>
        </div>
        <div class="garis"></div>
        <div class="header-surat">
            <h1>Laporan Absensi</h1>
        </div>
       <br>
  
        @if ($absensi->isNotEmpty())
        <h3>Nama: {{ $absensi->first()->user->name_lengkap }}</h3>
@endif

            <br>
            
            <h3>Tepat Waktu  : {{$totalTepatWaktu}}</h3>
            <h3>Terlambat  : {{$totalTerlambat}}</h3>
            <h3>Cuti  : {{$totalCuti}}</h3>
            <h3>Izin  : {{$totalIzin}}</h3>
            <br>
        <div class="table-container">
            <table class="divide-y divide-gray-200">
                <!-- Table Header -->
                <thead>
                    <tr class="bg-gray-200 text-gray-600 uppercase text-sm">
                        <th class="px-6 py-3 text-left">Tanggal Presensi</th>
                        <th class="px-6 py-3 text-left">Lokasi Absen</th>
                        <th class="px-6 py-3 text-left">Jam Masuk</th>
                        <th class="px-6 py-3 text-left">Jam Keluar</th>
                        <th class="px-6 py-3 text-left">Status</th>
                    </tr>
                </thead>
                <!-- Table Body -->
                <tbody
                class="text-gray-600 dark:text-gray-300 text-sm font-light divide-y divide-gray-200">
                    @foreach($absensi as $absen)
                    <tr class="hover:bg-white-300">
                        <td class="px-6 py-4 whitespace-nowrap">{{ $absen->tgl_presensi }}</td>
                        <td class="px-6 py-4">{{ $absen->lokasi_absen }}</td>
                        <td class="px-6 py-4">{{ $absen->jam_masuk }}</td>
                        <td class="px-6 py-4">{{ $absen->jam_keluar }}</td>
                        <td class="px-6 py-4">{{ $absen->status }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
       
    </div>
</body>
</html>
