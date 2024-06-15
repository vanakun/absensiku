@extends('layouts.admin')
@section('content')
@php
  use Carbon\Carbon;
@endphp
<div class="container px-6 mx-auto grid">
  <br>
  <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Menunggu Approve Izin</h2>
  <div class="w-full overflow-hidden rounded-lg shadow-xs mt-4">
    <div class="w-full overflow-x-auto">
      <table class="w-full whitespace-no-wrap">
        <thead>
          <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
            <th class="px-4 py-3">NO</th>
            <th class="px-4 py-3">Nama</th>
            <th class="px-4 py-3">Tanggal Izin</th>
            <th class="px-4 py-3">Status</th>
            <th class="px-4 py-3">Approve Izin</th>
            <th class="px-4 py-3">Reject Izin</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
          @foreach ($izinRequest as $request)
          <tr class="text-gray-700 dark:text-gray-400">
          <td class="px-4 py-3">{{ $loop->iteration }}</td>
            <td class="px-4 py-3 text-sm">{{ $request->user->name_lengkap }}</td>
            <td class="px-4 py-3 text-sm">{{ Carbon::parse($request->tgl_presensi)->translatedFormat('l, d F Y') }}</td>
            <td class="px-4 py-3 text-xs">
              <span class="px-2 py-1 font-semibold leading-tight {{ $request->status == 'Izin' ? 'text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100' : ($request->status == 'Menunggu Approve Izin' ? 'text-orange-700 bg-orange-100 rounded-full dark:text-white dark:bg-orange-600' : ($request->status == 'Denied' ? 'text-red-700 bg-red-100 rounded-full dark:text-red-100 dark:bg-red-700' : 'text-gray-700 bg-gray-100 rounded-full dark:text-gray-100 dark:bg-gray-700')) }}">
                {{ $request->status }}
              </span>
            </td>
           
            <td class="px-4 py-3 text-sm">
              <form action="{{ route('admin.cuti.approveizin', $request->id) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="checkbox" name="approve_cuti" onchange="this.form.submit()">
              </form>
            </td>
            <td class="px-4 py-3 text-sm">
              <form action="{{ route('admin.cuti.rejectizin', $request->id) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="checkbox" name="reject_cuti" onchange="this.form.submit()">
              </form>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
      <span class="flex items-center col-span-3">
        Showing {{ $izinRequest->firstItem() }}-{{ $izinRequest->lastItem() }} of {{ $izinRequest->total() }}
      </span>
      <span class="col-span-2"></span>
      <!-- Pagination -->
      <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
        {{ $izinRequest->links() }}
      </span>
    </div>
  </div>
</div>

<br>
<br>

<div class="container px-6 mx-auto grid">
  <br>
  <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200">History Approve Izin</h2>
  <div class="w-full overflow-hidden rounded-lg shadow-xs mt-4">
    <div class="w-full overflow-x-auto">
      <table class="w-full whitespace-no-wrap">
        <thead>
          <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
            <th class="px-4 py-3">NO</th>

            <th class="px-4 py-3">NAMA</th>
            <th class="px-4 py-3">Tanggal Izin</th>
            <th class="px-4 py-3">Alasan</th>
            <th class="px-4 py-3">Status</th>
            
           
           
          </tr>
        </thead>
        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
          @foreach ($izinRequest1 as $request)
          <tr class="text-gray-700 dark:text-gray-400">
            <td class="px-4 py-3">{{ $loop->iteration }}</td>
            <td class="px-4 py-3 text-sm">{{ $request->user->name_lengkap }}</td>
            <td class="px-4 py-3 text-sm">{{ Carbon::parse($request->tgl_presensi)->translatedFormat('l, d F Y') }}</td>
            <td class="px-4 py-3 text-sm">{{ $request->reason }}</td>
            <td class="px-4 py-3 text-xs">
              <span class="px-2 py-1 font-semibold leading-tight {{ $request->status == 'Izin' ? 'text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100' : ($request->status == 'Pending' ? 'text-orange-700 bg-orange-100 rounded-full dark:text-white dark:bg-orange-600' : ($request->status == 'Izin Di Tolak' ? 'text-red-700 bg-red-100 rounded-full dark:text-red-100 dark:bg-red-700' : 'text-gray-700 bg-gray-100 rounded-full dark:text-gray-100 dark:bg-gray-700')) }}">
                {{ $request->status }}
              </span>
            </td>
           
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
      <span class="flex items-center col-span-3">
        Showing {{ $izinRequest1->firstItem() }}-{{ $izinRequest1->lastItem() }} of {{ $izinRequest1->total() }}
      </span>
      <span class="col-span-2"></span>
      <!-- Pagination -->
      <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
        {{ $izinRequest1->links() }}
      </span>
    </div>
  </div>
</div>
@endsection
