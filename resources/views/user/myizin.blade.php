@extends('layouts.user')
@section('content')
<!-- Remove everything INSIDE this div to a really blank page -->
<div class="container px-6 mx-auto grid">
    <br>
    <div class="flex justify-right">
        <a href="{{ route('user.Createizin') }}" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Ajukan Izin</a>
    </div>

    <div class="container mx-auto px-4">
    <div class="overflow-x-auto">
        <div class="p-4 shadow-md rounded my-6">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-200 text-gray-600 uppercase text-sm">
                        <th class="px-6 py-3 text-left">Tanggal izin</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-left">Reason</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 dark:text-gray-300 text-sm font-light divide-y divide-gray-200">
                    @foreach($absensis as $absen)
                    <tr class="hover:bg-white-300">
                        <td class="px-6 py-4 whitespace-nowrap ">{{ $absen->tgl_presensi }}</td>
                        <td class="px-6 py-4">{{ $absen->status }}</td>
                        <td class="px-6 py-4">{{ $absen->reason }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
       
        <br>
    </div>
</div>
</div>
@endsection
