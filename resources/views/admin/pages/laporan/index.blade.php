@extends('layouts.admin')

@section('content')
<br>
<div class="container px-6 mx-auto grid">
    <h1 class="text-2xl font-semibold text-gray-700 dark:text-gray-200 mb-4">Laporan Absensi</h1>

    <form action="{{ route('admin.absensi.report.generate') }}" method="POST" class="mb-8">
        @csrf
      
            <div class="mb-4 text-gray-700 dark:text-gray-200">
                <label for="user_id" class="block text-gray-700 dark:text-gray-200 font-semibold mb-2">User:</label>
                <select id="user_id" name="user_id" required class="w-full px-4 py-2 mt-1 text-gray-700 dark:text-gray-200 border rounded-md bg-gray-700 focus:outline-none focus:border-blue-500 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:outline-none focus:border-blue-500">
                    <option value="" disabled selected>Pilih Pengguna</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name_lengkap }}</option>
                    @endforeach
                </select>
                <input type="text" id="search_user" placeholder="Cari Pengguna" class="w-full px-4 py-2 mt-1 text-gray-700 dark:text-gray-200 border rounded-md bg-gray-700 focus:outline-none focus:border-blue-500 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:outline-none focus:border-blue-500">
            </div>
            <div class="mb-4 text-gray-700 dark:text-gray-200">
                <label for="month" class="block text-gray-700 dark:text-gray-200 font-semibold mb-2">Select Date:</label>
                <input type="month" id="month" name="month" required class="w-full px-4 py-2 mt-1 text-gray-700 dark:text-gray-200 border rounded-md bg-gray-700 focus:outline-none focus:border-blue-500 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:outline-none focus:border-blue-500">
            </div>

    
        <button type="submit" class="px-4 py-2 bg-blue-500 text-white dark:text-gray-200 rounded hover:bg-blue-600 mt-4">Generate Report</button>
    </form>

    <form action="{{ route('admin.export.attendance') }}" method="GET">
        @csrf
        <div class="mb-4 text-gray-700 dark:text-gray-200">
            <label for="month" class="block text-gray-700 dark:text-gray-200 font-semibold mb-2">Select Date:</label>
            <input type="month" id="month" name="month" required class="w-full px-4 py-2 mt-1 text-gray-700 dark:text-gray-200 border rounded-md bg-gray-700 focus:outline-none focus:border-blue-500 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:outline-none focus:border-blue-500">
        </div>
        <button type="submit" class="px-4 py-2 bg-blue-500 text-white dark:text-gray-200 rounded hover:bg-blue-600 mt-4">Export Attendance All Users</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const userSelect = document.getElementById('user_id');
        const searchInput = document.getElementById('search_user');

        searchInput.addEventListener('keyup', function () {
            const filter = searchInput.value.toUpperCase();
            const options = userSelect.querySelectorAll('option');
            options.forEach(option => {
                if (option.innerText.toUpperCase().indexOf(filter) > -1) {
                    option.style.display = '';
                } else {
                    option.style.display = 'none';
                }
            });
        });
    });
</script>
@endsection
