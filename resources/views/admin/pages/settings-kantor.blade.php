@extends('layouts.admin')

@section('content')
<br>
<div class="container px-6 mx-auto grid">
    <h1 class="text-2xl font-semibold text-gray-700 dark:text-gray-200 mb-4">Edit Kantor</h1>
    <form action="{{ route('admin.update-all-kantor') }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Kantor 1 -->
        <div class="mb-4 text-gray-700 dark:text-gray-200">
            <input type="hidden" name="office1_id" value="{{ $office1->id }}">
            <label for="nama_kantor_1" class="block text-gray-700 dark:text-gray-200 font-semibold mb-2 ">Nama Kantor 1:</label>
            <input type="text" id="nama_kantor_1" name="office1_nama_kantor" value="{{ $office1->nama_kantor }}" class="w-full px-4 py-2 mt-1 text-gray-700 dark:text-gray-200 border rounded-md bg-gray-700 focus:outline-none focus:border-blue-500 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:outline-none focus:border-blue-500">
        </div>
        <div class="mb-4">
            <label for="lokasi_kantor_1" class="block text-gray-700 dark:text-gray-200 font-semibold mb-2">Lokasi Kantor 1:</label>
            <div id="map1" class="w-full h-64 mb-2 rounded-lg border"></div>
            <input type="text" id="lokasi_kantor_1" name="office1_lokasi_kantor" value="{{ $office1->lokasi_kantor }}" class="w-full px-4 py-2 mt-1 text-gray-700 dark:text-gray-200 border rounded-md bg-gray-700 focus:outline-none focus:border-blue-500 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:outline-none focus:border-blue-500">
        </div>
        <div class="mb-4">
            <label for="radius_1" class="block text-gray-700 dark:text-gray-200 font-semibold mb-2">Radius 1:</label>
            <input type="number" id="radius_1" name="office1_radius" value="{{ $office1->radius }}" class="w-full px-4 py-2 mt-1 text-gray-700 dark:text-gray-200 border rounded-md bg-gray-700 focus:outline-none focus:border-blue-500 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:outline-none focus:border-blue-500">
        </div>
        <hr class="my-4">
<br>
        <!-- Kantor 2 -->
        <div class="mb-4">
            <input type="hidden" name="office2_id" value="{{ $office2->id }}">
            <label for="nama_kantor_2" class="block text-gray-700 dark:text-gray-200 font-semibold mb-2">Nama Kantor 2:</label>
            <input type="text" id="nama_kantor_2" name="office2_nama_kantor" value="{{ $office2->nama_kantor }}" class="w-full px-4 py-2 mt-1 text-gray-700 dark:text-gray-200 border rounded-md bg-gray-700 focus:outline-none focus:border-blue-500 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:outline-none focus:border-blue-500">
        </div>
        <div class="mb-4">
            <label for="lokasi_kantor_2" class="block text-gray-700 dark:text-gray-200 font-semibold mb-2">Lokasi Kantor 2:</label>
            <div id="map2" class="w-full h-64 mb-2 rounded-lg border"></div>
            <input type="text" id="lokasi_kantor_2" name="office2_lokasi_kantor" value="{{ $office2->lokasi_kantor }}" class="w-full px-4 py-2 mt-1 text-gray-700 dark:text-gray-200 border rounded-md bg-gray-700 focus:outline-none focus:border-blue-500 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:outline-none focus:border-blue-500">
        </div>
        <div class="mb-4">
            <label for="radius_2" class="block text-gray-700 dark:text-gray-200 font-semibold mb-2">Radius 2:</label>
            <input type="number" id="radius_2" name="office2_radius" value="{{ $office2->radius }}" class="w-full px-4 py-2 mt-1 text-gray-700 dark:text-gray-200 border rounded-md bg-gray-700 focus:outline-none focus:border-blue-500 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:outline-none focus:border-blue-500">
        </div>
        <hr class="my-4">
        <br>
        <!-- Kantor 3 -->
        <div class="mb-4">
            <input type="hidden" name="office3_id" value="{{ $office3->id }}">
            <label for="nama_kantor_3" class="block text-gray-700 dark:text-gray-200 font-semibold mb-2">Nama Kantor 3:</label>
            <input type="text" id="nama_kantor_3" name="office3_nama_kantor" value="{{ $office3->nama_kantor }}" class="w-full px-4 py-2 mt-1 text-gray-700 dark:text-gray-200 border rounded-md bg-gray-700 focus:outline-none focus:border-blue-500 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:outline-none focus:border-blue-500">
        </div>
        <div class="mb-4">
            <label for="lokasi_kantor_3" class="block text-gray-700 dark:text-gray-200 font-semibold mb-2">Lokasi Kantor 3:</label>
            <div id="map3" class="w-full h-64 mb-2 rounded-lg border"></div>
            <input type="text" id="lokasi_kantor_3" name="office3_lokasi_kantor" value="{{ $office3->lokasi_kantor }}" class="w-full px-4 py-2 mt-1 text-gray-700 dark:text-gray-200 border rounded-md bg-gray-700 focus:outline-none focus:border-blue-500 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:outline-none focus:border-blue-500">
        </div>
        <div class="mb-4">
            <label for="radius_3" class="block text-gray-700 dark:text-gray-200 font-semibold mb-2">Radius 3:</label>
            <input type="number" id="radius_3" name="office3_radius" value="{{ $office3->radius }}" class="w-full px-4 py-2 mt-1 text-gray-700 dark:text-gray-200 border rounded-md bg-gray-700 focus:outline-none focus:border-blue-500 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:outline-none focus:border-blue-500">
        </div>
        <hr class="my-4">
        <br>
        <button type="submit" class="px-4 py-2 bg-blue-500 text-gray-700 dark:text-gray-200 rounded hover:bg-blue-600">Simpan
        </button>
    </form>
</div>

<!-- CSS untuk menetapkan ukuran peta -->
<style>
    #map1,
    #map2,
    #map3 {
        width: 100%;
        height: 300px; /* Sesuaikan dengan ukuran yang diinginkan */
    }
</style>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var map1 = L.map('map1').setView([{{ explode(',', $office1->lokasi_kantor)[0] }}, {{ explode(',', $office1->lokasi_kantor)[1] }}], 13);
        var map2 = L.map('map2').setView([{{ explode(',', $office2->lokasi_kantor)[0] }}, {{ explode(',', $office2->lokasi_kantor)[1] }}], 13);
        var map3 = L.map('map3').setView([{{ explode(',', $office3->lokasi_kantor)[0] }}, {{ explode(',', $office3->lokasi_kantor)[1] }}], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }).addTo(map1);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }).addTo(map2);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }).addTo(map3);

        var marker1;
        var marker2;
        var marker3;

        // Function to add marker at specified lat, lng
        function addMarker1(lat, lng) {
            if (marker1) {
                map1.removeLayer(marker1);
            }
            marker1 = L.marker([lat, lng]).addTo(map1);
            map1.setView([lat, lng], 17); // Zoom ke marker
            document.getElementById('lokasi_kantor_3').value = lat + ',' + lng;
}
function addMarker2(lat, lng) {
        if (marker2) {
            map2.removeLayer(marker2);
        }
        marker2 = L.marker([lat, lng]).addTo(map2);
        map2.setView([lat, lng], 17); // Zoom ke marker
        document.getElementById('lokasi_kantor_2').value = lat + ',' + lng;
    }

    function addMarker3(lat, lng) {
        if (marker3) {
            map3.removeLayer(marker3);
        }
        marker3 = L.marker([lat, lng]).addTo(map3);
        map3.setView([lat, lng], 17); // Zoom ke marker
        document.getElementById('lokasi_kantor_3').value = lat + ',' + lng;
    }

    // Add marker on map click
    map1.on('click', function (e) {
        var lat = e.latlng.lat;
        var lng = e.latlng.lng;
        addMarker1(lat, lng);
    });

    map2.on('click', function (e) {
        var lat = e.latlng.lat;
        var lng = e.latlng.lng;
        addMarker2(lat, lng);
    });

    map3.on('click', function (e) {
        var lat = e.latlng.lat;
        var lng = e.latlng.lng;
        addMarker3
        (lat, lng);
    });

    // Set marker initial positions
    addMarker1({{ explode(',', $office1->lokasi_kantor)[0] }}, {{ explode(',', $office1->lokasi_kantor)[1] }});
    addMarker2({{ explode(',', $office2->lokasi_kantor)[0] }}, {{ explode(',', $office2->lokasi_kantor)[1] }});
    addMarker3({{ explode(',', $office3->lokasi_kantor)[0] }}, {{ explode(',', $office3->lokasi_kantor)[1] }});
});
</script>
@endsection
