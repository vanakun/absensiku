@extends('layouts.user')
@section('content')
<div class="container px-6 mx-auto grid">
    <br>
    <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Pengajuan Izin</h2>
    <form action="{{ route('user.Storeizin') }}" method="POST" class="mt-4" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="tgl_presensi" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Izin:</label>
                <input type="date" id="tgl_presensi" name="tgl_presensi" class="form-input mt-1 block w-full">
            </div>
            <div>
                <label for="reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Reason:</label>
                <input type="text" id="reason" name="reason" class="form-input mt-1 block w-full">
            </div>
            <div>
                <label for="surat_izin" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Surat Izin:</label>
                <input type="file" name="surat_izin" id="surat_izin" class="form-input mt-1 block w-full">
            </div>
        </div>
        <div class="mt-4">
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Submit</button>
        </div>
    </form>
</div>
@endsection
