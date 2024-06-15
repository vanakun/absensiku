<?php

namespace Database\Seeders;

use App\Models\kantor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class KantorSeeder extends Seeder
{
    public function run()
    {
        // Empty the table first
        DB::table('kantors')->delete();

        Kantor::create([
            'id' => '1',
            'nama_kantor' => "DHL1",
            'lokasi_kantor' => "-7.285307,112.5841075",
            'radius' => "20",
        ]);

        Kantor::create([
            'id' => '2',
            'nama_kantor' => "DHL2",
            'lokasi_kantor' => "-7.285285046566463,112.58486924736025",
            'radius' => "20",
        ]);

        Kantor::create([
            'id' => '3',
            'nama_kantor' => "DHL3",
            'lokasi_kantor' => "-7.286603349955651,112.58325992195131",
            'radius' => "20",
        ]);
        
    }
}
