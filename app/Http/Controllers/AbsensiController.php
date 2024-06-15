<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Absensi;

class AbsensiController extends Controller
{
    public function showForm()
    {
        return view('absensi.form');
    }

    public function submitAbsen(Request $request)
    {
        $hariini = date("Y-m-d");
        $jam = date("H:i:s");
        $user_id = Auth::id(); // Mengambil ID pengguna yang sedang diautentikasi
    
        // Memeriksa apakah pengguna sudah absen masuk hari ini
        $cekMasuk = DB::table('absensis')
            ->where('tgl_presensi', $hariini)
            ->where('user_id', $user_id)
            ->whereNotNull('jam_masuk')
            ->exists();
    
        if ($cekMasuk) {
            // Jika pengguna sudah absen masuk, lakukan absen pulang
            $update = DB::table('absensis')
                ->where('tgl_presensi', $hariini)
                ->where('user_id', $user_id)
                ->update(['jam_keluar' => $jam]);
    
            return redirect()->back()->with('success', 'Absen pulang berhasil disimpan.');
        } else
        {
        $hariIni = date("Y-m-d");
        $jam = date("H:i:s");
        $user_id = Auth::id();
        
        // Set start time to 08:45 pagi
        $start_time = strtotime("08:45:00");
        // Check if the user is late
        $late_time = strtotime("09:15:00");
        $end_time = strtotime("17:00:00"); // 05:00 sore
        $current_time = strtotime($jam);
        
        $update = [
            'tgl_presensi' => $hariIni,
            'jam_masuk' => $jam,
            'lokasi_absen' => $request->lokasi_absen,
        ];
        
        // Check if the status is not 'Cuti' or 'Izin', then update the status
        if ($request->status != 'Cuti' && $request->status != 'Izin') {
            if ($current_time > $start_time && $current_time <= $end_time) {
                if ($current_time > $late_time) {
                    $update['status'] = 'terlambat';
                } else {
                    $update['status'] = 'tepat_waktu';
                }
            } else {
                // Jika absen dilakukan setelah jam 17:00 atau sebelum jam 08:45, tidak akan disimpan
                Alert::error('Maaf!', 'Belum waktunya untuk absen.')->persistent(true)->autoClose(4000);
                return redirect()->back();
            }
        }
        
        // Update data which matches the tgl_presensi today and the user_id currently logged in
        DB::table('absensis')
            ->where('tgl_presensi', $hariIni)
            ->where('user_id', $user_id)
            ->whereNotIn('status', ['Cuti', 'Izin']) // Exclude 'Cuti' and 'Izin' statuses
            ->update($update);
        
        return redirect()->back()->with('success', 'Absen masuk berhasil disimpan.');            
    }             
    
        return redirect()->back()->with('success', 'Absen berhasil disimpan.');
    }

    public function exportAttendance(Request $request)
{
    $month = $request->input('month');
    if (!$month) {
        return redirect()->back()->with('error', 'Month is required.');
    }

    // Extract year and month
    $year = date('Y', strtotime($month));
    $month = date('m', strtotime($month));

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Set header
    $sheet->setCellValue('A4', 'Nama Karyawan');
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    for ($i = 1; $i <= $daysInMonth; $i++) {
        $cell = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i + 1) . '4';
        $sheet->setCellValue($cell, $i);
    }
    $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($daysInMonth + 2) . '4', 'TM');

    // Get data from database
    $absensis = DB::table('absensis')
        ->join('users', 'absensis.user_id', '=', 'users.id')
        ->select('users.name_lengkap', 'absensis.tgl_presensi', 'absensis.jam_masuk', 'absensis.jam_keluar', 'absensis.status', DB::raw("IF(absensis.status = 'terlambat', 'Terlambat', '') as status_terlambat"))
        ->whereYear('absensis.tgl_presensi', '=', $year)
        ->whereMonth('absensis.tgl_presensi', '=', $month)
        ->get();

    $data = [];
    $totalTerlambat = [];
    foreach ($absensis as $absensi) {
        $name = $absensi->name_lengkap;
        $date = (int) date('j', strtotime($absensi->tgl_presensi));
        $timeIn = $absensi->jam_masuk;
        $timeOut = $absensi->jam_keluar;
        $status = $absensi->status;
        if (!isset($data[$name])) {
            $data[$name] = array_fill(1, $daysInMonth, '');
            $totalTerlambat[$name] = 0;
        }

        $data[$name][$date] = trim("$timeIn\n$timeOut");
        if ($status === 'terlambat') {
            $totalTerlambat[$name]++;
        }
    }

    // Fill data
    $row = 5; // Start from row 5
    foreach ($data as $name => $days) {
        $sheet->setCellValue('A' . $row, $name);

        foreach ($days as $day => $time) {
            $cell = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($day + 1) . $row;
            $timeParts = explode('\n', $time);
            $timeIn = isset($timeParts[0]) ? $timeParts[0] : '';
            $timeOut = isset($timeParts[1]) ? $timeParts[1] : '';
            $sheet->setCellValue($cell, $timeIn);

            // Set font color for time in and time out
            if (!empty($timeOut)) {
                $sheet->getStyle($cell)->getFont()->getColor()->setARGB(Color::COLOR_RED);
            } else {
                $sheet->getStyle($cell)->getFont()->getColor()->setARGB(Color::COLOR_BLACK);
            }
        }

        // Set TM columns
        $sheet->setCellValue(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($daysInMonth + 2) . $row, $totalTerlambat[$name]); // Total Terlambat
        $row++;
    }

    // Apply border styles
    $styleArray = [
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['argb' => '000000'],
            ],
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical' => Alignment::VERTICAL_CENTER,
            'wrapText' => true, // Set wrap text to true
        ],
    ];
    $sheet->getStyle('A4:' . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($daysInMonth + 2) . $sheet->getHighestRow())->applyFromArray($styleArray);

    // Adjust column widths after filling data
    foreach (range('A', \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($daysInMonth + 2)) as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    // Set column width for date columns
    foreach (range('B', \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($daysInMonth + 1)) as $columnID) {
        $sheet->getColumnDimension($columnID)->setWidth(9); // Set the width to 9
    }

    foreach (range(5, $sheet->getHighestRow()) as $rowIndex) {
        $sheet->getRowDimension($rowIndex)->setRowHeight(35); // Set the height to 35
    }

    // Save to file
    $writer = new Xlsx($spreadsheet);
    $fileName = 'attendance.xlsx';
    $writer->save($fileName);

    return response()->download($fileName)->deleteFileAfterSend(true);
}





    



    
    
    
}
