@extends('layouts.user')
@section('content')
<div class="container px-6 mx-auto grid">
    <br>
    <div class="container mx-auto px-4">
        <div class="overflow-x-auto">
            <div class="p-4 shadow-md rounded my-6">
                <form action="{{ route('user.updateStatus') }}" method="POST" id="absensiForm">
                    @csrf
                    @method('PUT')
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-200 text-gray-600 uppercase text-sm">
                                <th class="px-6 py-3 text-left">Tanggal Misspunch</th>
                                <th class="px-6 py-3 text-left">Jam Masuk</th>
                                <th class="px-6 py-3 text-left">Jam Keluar</th>
                                <th class="px-6 py-3 text-left">Status</th>
                                <th class="px-6 py-3 text-left">Reason</th>
                                <th class="px-6 py-3 text-left">Misspunch Request</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 dark:text-gray-300 text-sm font-light divide-y divide-gray-200">
                            @foreach($absensis as $absen)
                            <tr class="hover:bg-white-300">
                                <td class="px-6 py-4 whitespace-nowrap">{{ $absen->tgl_presensi }}</td>
                                <td class="px-6 py-4">
                                    <input type="time" name="jam_masuk[{{ $absen->id }}]" class="time-input" style="color: black;" value="{{ $absen->jam_masuk }}" {{ in_array($absen->status, ['misspunch request', 'misspunch rejected','misspunch approved']) ? 'readonly' : '' }}>
                                </td>
                                <td class="px-6 py-4">
                                    <input type="time" name="jam_keluar[{{ $absen->id }}]" class="time-input" style="color: black;" value="{{ $absen->jam_keluar }}" {{ in_array($absen->status, ['misspunch request', 'misspunch rejected','misspunch approved']) ? 'readonly' : '' }}>
                                </td>
                                <td class="px-6 py-4">{{ $absen->status }}</td>
                                <td class="px-6 py-4">
                                    <input type="text" name="reasons[{{ $absen->id }}]" class="reason-input" style="color: black;" value="{{ $absen->reason }}" {{ in_array($absen->status, ['misspunch request', 'misspunch rejected','misspunch approved']) ? 'readonly' : '' }}>
                                </td>
                                <td class="px-6 py-4">
                                    <input type="checkbox" name="misspunch_requests[]" value="{{ $absen->id }}" class="misspunch-checkbox" data-tgl-presensi="{{ $absen->tgl_presensi }}" {{ in_array($absen->status, ['misspunch request', 'misspunch rejected','misspunch approved']) ? 'checked disabled' : '' }}>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <button type="submit" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkboxes = document.querySelectorAll('.misspunch-checkbox');

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function (e) {
                const checkbox = e.target;
                const tglPresensi = checkbox.getAttribute('data-tgl-presensi');
                const presensiDate = new Date(tglPresensi);
                const currentDate = new Date();

                // Check if more than a day has passed since presensi date
                const oneDay = 24 * 60 * 60 * 1000;
                if ((currentDate - presensiDate) < oneDay) {
                    alert('You can only request a misspunch after one day has passed.');
                    checkbox.checked = false;
                }
            });
        });

        const form = document.getElementById('absensiForm');
        form.addEventListener('submit', function (e) {
            const checkboxes = document.querySelectorAll('.misspunch-checkbox:checked');
            let valid = true;
            checkboxes.forEach(checkbox => {
                const reasonInput = checkbox.closest('tr').querySelector('.reason-input');
                if (!reasonInput.value.trim()) {
                    valid = false;
                    alert('Please provide a reason for the misspunch request.');
                    reasonInput.focus();
                }
            });
            if (!valid) {
                e.preventDefault();
            }
        });
    });
</script>
@endsection
